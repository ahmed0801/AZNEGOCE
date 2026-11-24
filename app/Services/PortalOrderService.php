<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\B2bUser;
use App\Models\Customer;
use App\Models\CustomerB2bSetting;
use App\Models\Item;
use App\Models\PortalOrder;
use App\Models\PrintJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Création d'une commande portail à partir du panier. Tous les prix sont recalculés ici, jamais lus du navigateur. */
class PortalOrderService
{
    private $s; private $user; private $customer; private $cs; private $pricing;

    public function __construct(B2bSetting $s, B2bUser $user, Customer $customer, CustomerB2bSetting $cs)
    {
        $this->s = $s; $this->user = $user; $this->customer = $customer; $this->cs = $cs;
        $this->pricing = new B2bPricingService($s);
    }

    public function autoValidate()
    {
        $m = $this->cs->validation_mode;
        return $m === 'auto' || ($m !== 'manual' && (bool) $this->s->auto_validate);
    }

    /** @return array ['ok'=>bool, 'message'=>?, 'order'=>?] */
    public function place(array $in)
    {
        $cart = new PortalCartService($this->s, $this->user, $this->customer, $this->cs);
        if ($msg = $cart->orderBlock()) return ['ok' => false, 'message' => $msg];

        $allowed = $this->cs->fulfillment ?: 'pickup';
        $mode = (isset($in['mode']) && $in['mode'] === 'pickup') ? 'pickup' : 'delivery';
        if ($allowed === 'pickup' || $allowed === 'delivery') $mode = $allowed;   // imposé par la fiche client, quoi que le navigateur envoie
        $po   = mb_substr(trim((string) ($in['po_ref'] ?? '')), 0, 60);
        $note = mb_substr(trim((string) ($in['note'] ?? '')), 0, 1000);

        try {
            $order = DB::transaction(function () use ($mode, $po, $note) {
                $rows = DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->orderBy('id')->lockForUpdate()->get();
                if ($rows->isEmpty()) return ['err' => 'Votre panier est vide.'];

                $items = Item::with(['brand', 'discountGroup'])->whereIn('id', $rows->pluck('item_id')->all())->where('is_active', 1)->get()->keyBy('id');
                $lines = []; $ht = 0.0; $tva = 0.0; $units = 0;
                foreach ($rows as $r) {
                    $it = $items->get($r->item_id);
                    if (!$it) continue;                                   // article retiré du catalogue entre-temps
                    $p = $this->pricing->price($it, $this->customer);
                    if (!$p || $p['net_ht'] === null) return ['err' => 'Le prix de « ' . $it->code . ' » est indisponible. Contactez-nous.'];
                    if ((float) $p['net_ht'] <= 0 && in_array($this->s->zero_price_mode ?: 'both', ['block', 'both'], true)) return ['err' => '« ' . $it->code . ' » est sur demande : retirez-le du panier ou contactez-nous.'];
                    $lineHt = round($p['net_ht'] * $r->quantity, 2);
                    $ht += $lineHt; $tva += round($lineHt * $p['tva_rate'] / 100, 2); $units += (int) $r->quantity;
                    $lines[] = [
                        'item_id' => $it->id, 'code' => $it->code, 'name' => mb_substr($it->name, 0, 255), 'brand' => optional($it->brand)->name,
                        'qty' => (int) $r->quantity, 'list_ht' => $p['list_ht'], 'discount' => $p['effective_rate'],
                        'unit_ht' => $p['net_ht'], 'tva_rate' => $p['tva_rate'], 'line_ht' => $lineHt,
                    ];
                }
                if (!$lines) return ['err' => 'Les articles de votre panier ne sont plus disponibles.'];
                $ht = round($ht, 2); $tva = round($tva, 2);

                $min = (float) $this->s->min_order_amount;
                if ($min > 0 && $ht < $min) return ['err' => 'Le minimum de commande est de ' . number_format($min, 2, ',', ' ') . ' € HT : il manque ' . number_format($min - $ht, 2, ',', ' ') . ' €.'];

                $auto = $this->autoValidate();
                $o = PortalOrder::create([
                    'customer_id' => $this->customer->id, 'b2b_user_id' => $this->user->id,
                    'status' => $auto ? 'validated' : 'pending', 'auto_validated' => $auto, 'validated_at' => $auto ? now() : null,
                    'delivery_mode' => $mode, 'po_ref' => $po ?: null, 'note' => $note ?: null,
                    'total_ht' => $ht, 'total_tva' => $tva, 'total_ttc' => round($ht + $tva, 2),
                    'lines_count' => count($lines), 'units' => $units,
                    'customer_solde' => $this->customer->solde, 'customer_plafond' => $this->customer->plafond,
                ]);
                $o->number = 'WEB-' . str_pad($o->id, 6, '0', STR_PAD_LEFT);
                $o->save();
                foreach ($lines as $l) { $o->lines()->create($l); }
                DB::table('portal_cart_items')->where('b2b_user_id', $this->user->id)->delete();
                return ['order' => $o];
            });
        } catch (\Throwable $e) {
            Log::channel('single')->error('portal order failed', ['user' => $this->user->id, 'e' => $e->getMessage()]);
            return ['ok' => false, 'message' => "La commande n'a pas pu être enregistrée. Votre panier est conservé : réessayez dans un instant."];
        }

        if (isset($order['err'])) return ['ok' => false, 'message' => $order['err']];
        $o = $order['order'];

        // Validation automatique seulement si TOUT le stock est disponible : sinon le vendeur décide.
        $bridge = new PortalErpBridge();
        if ($o->auto_validated && !$bridge->stockCovers($o)) {
            $o->update(['status' => 'pending', 'auto_validated' => false, 'validated_at' => null, 'internal_note' => 'Validation automatique impossible : stock insuffisant sur au moins une ligne.']);
        }
        $bridge->sync($o);            // commande vente (+ BL + facture si validation automatique)
        $o->refresh();

        $this->enqueueTicket($o);
        $this->notifyByMail($o);
        return ['ok' => true, 'order' => $o];
    }

    /** E-mail « nouvelle commande » à l'adresse de la société (si l'option est cochée), envoyé APRÈS la réponse au client. */
    public function notifyByMail(PortalOrder $o)
    {
        $s = $this->s; $to = trim((string) ($s->notification_email ?: $s->email));
        if (!$s->notify_new_order || $to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) return;
        $o->loadMissing('customer');
        $body = "Nouvelle commande portail " . $o->number . "\n\nClient : " . $o->customer->name . "\nTotal : " . number_format($o->total_ttc, 2, ',', ' ') . " € TTC (" . $o->lines_count . " références)\n"
              . "Statut : " . $o->status_label . ($o->erp_numdoc ? "\nCommande vente ERP : " . $o->erp_numdoc : '') . ($o->erp_invoice_numdoc ? "\nFacture : " . $o->erp_invoice_numdoc : '') . "\n";
        $subject = 'Nouvelle commande portail ' . $o->number . ' - ' . $o->customer->name;
        try {
            dispatch(function () use ($to, $subject, $body) {
                try { \Illuminate\Support\Facades\Mail::raw($body, function ($m) use ($to, $subject) { $m->to($to)->subject($subject); }); }
                catch (\Throwable $e) { \Illuminate\Support\Facades\Log::channel('single')->warning('portal order mail failed: ' . $e->getMessage()); }
            })->afterResponse();
        } catch (\Throwable $e) { /* jamais bloquant */ }
    }

    /** Ticket à l'arrivée de la commande, si l'impression automatique est configurée. */
    public function enqueueTicket(PortalOrder $o, $force = false)
    {
        $s = $this->s;
        if (!$force && !$s->auto_print) return false;
        if (empty($s->printer_ip) || empty($s->agent_token)) return false;
        try {
            $o->loadMissing('lines', 'customer');
            $clean = function ($t) { return trim(preg_replace('/\s+/', ' ', (string) $t)); };
            $payload = EscPosBuilder::orderTicket($s->company_name, $s->paper_width, [
                'number' => $o->number, 'customer' => $o->customer->name, 'mode' => $o->delivery_mode, 'po_ref' => $clean($o->po_ref), 'note' => $clean($o->note),
                'total_ht' => $o->total_ht, 'total_tva' => $o->total_tva, 'total_ttc' => $o->total_ttc,
                'lines' => $o->lines->where('removed', false)->map(function ($l) { return ['code' => $l->code, 'name' => $l->name, 'qty' => $l->qty, 'unit_ht' => $l->unit_ht, 'line_ht' => $l->line_ht]; })->all(),
            ]);
            PrintJob::create([
                'type' => 'order', 'title' => 'Commande ' . $o->number, 'printer_ip' => $s->printer_ip,
                'printer_port' => $s->printer_port ?: 9100, 'copies' => $s->print_copies ?: 1,
                'payload' => base64_encode($payload), 'status' => 'pending',
            ]);
            return true;
        } catch (\Throwable $e) {
            Log::channel('single')->error('portal order ticket failed', ['order' => $o->id, 'e' => $e->getMessage()]);
            return false;
        }
    }
}
