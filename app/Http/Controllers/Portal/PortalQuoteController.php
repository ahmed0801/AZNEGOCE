<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PortalQuote;
use App\Services\PortalQuoteNotices;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Demandes de devis du client connecté (saisie libre : véhicule + pièces). Toujours limité au client de l'utilisateur. */
class PortalQuoteController extends Controller
{
    private function ctx(Request $r)
    {
        $u = $r->attributes->get('portal_user');
        return [$r->attributes->get('portal_settings'), $u, $u->customer, $r->attributes->get('portal_customer_settings')];
    }

    private function view($name, Request $r, array $data = [])
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        return view($name, ['s' => $s, 'pUser' => $u, 'customer' => $c, 'cs' => $cs] + $data);
    }

    private function mine(Request $r, $id)
    {
        $u = $r->attributes->get('portal_user');
        return PortalQuote::with('lines')->where('customer_id', $u->customer_id)->where('id', $id)->firstOrFail();
    }

    public function index(Request $r)
    {
        $u = $r->attributes->get('portal_user');
        $quotes = PortalQuote::where('customer_id', $u->customer_id)->orderByDesc('id')->paginate(15);
        $erp = collect(); $erpNew = [];
        try {
            if (PortalQuoteNotices::showErp($r->attributes->get('portal_settings'))) {
                $erp = PortalQuoteNotices::erpQuery($u->customer_id)->orderByDesc('id')->limit(15)->get(['id', 'numdoc', 'order_date', 'total_ttc', 'created_at']);
                $erpNew = DB::table('portal_erp_quote_views')->where('customer_id', $u->customer_id)->pluck('sales_order_id')->all();
            }
        } catch (\Throwable $e) { }
        return $this->view('portal.quotes', $r, ['quotes' => $quotes, 'erpQuotes' => $erp, 'erpSeen' => $erpNew]);
    }

    public function create(Request $r)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $vehicles = [];
        try {
            $vehicles = $c->vehicles()->orderByDesc('id')->limit(60)->get()->map(function ($v) {
                $label = trim(implode(' ', array_filter([$v->license_plate, $v->brand_name, $v->model_name, $v->engine_description])));
                return ['id' => $v->id, 'label' => $label !== '' ? $label : ('Véhicule ' . $v->id)];
            })->all();
        } catch (\Throwable $e) { }
        // pré-remplissage depuis le catalogue : ?ref=CODE&name=Désignation&brand=Marque
        $pre = [];
        if ($r->filled('ref') || $r->filled('name')) {
            $pre[] = ['reference' => mb_substr((string) $r->query('ref'), 0, 80), 'designation' => mb_substr((string) $r->query('name'), 0, 255), 'brand' => mb_substr((string) $r->query('brand'), 0, 120), 'qty' => 1];
        }
        return $this->view('portal.quote_new', $r, ['vehicles' => $vehicles, 'pre' => $pre]);
    }

    public function store(Request $r)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $v = $r->validate([
            'vehicle_id' => 'nullable|integer', 'vehicle_text' => 'nullable|string|max:255', 'vin' => 'nullable|string|max:30', 'note' => 'nullable|string|max:2000', 'plate' => 'nullable|string|max:20',
            'lines' => 'required|array|min:1|max:40',
            'lines.*.reference' => 'nullable|string|max:80', 'lines.*.designation' => 'nullable|string|max:255', 'lines.*.brand' => 'nullable|string|max:120',
            'lines.*.qty' => 'nullable|integer|min:1|max:9999',
        ] + \App\Services\PortalQuoteFiles::rules(), ['lines.required' => 'Ajoutez au moins une pièce.', 'lines.max' => 'Maximum 40 pièces par demande.'] + \App\Services\PortalQuoteFiles::messages());

        $lines = [];
        foreach ($v['lines'] as $l) {
            $ref = trim((string) ($l['reference'] ?? '')); $des = trim((string) ($l['designation'] ?? ''));
            if ($ref === '' && $des === '') continue;
            $lines[] = ['reference' => $ref !== '' ? $ref : null, 'designation' => $des !== '' ? $des : $ref, 'brand' => trim((string) ($l['brand'] ?? '')) ?: null, 'qty' => max(1, (int) ($l['qty'] ?? 1))];
        }
        if (!$lines) return $this->fail($r, 'Ajoutez au moins une pièce (référence ou désignation).');

        // le véhicule choisi doit appartenir à CE client
        $vehId = null; $vehText = trim((string) ($v['vehicle_text'] ?? ''));
        if (!empty($v['vehicle_id'])) {
            $veh = $c->vehicles()->where('id', (int) $v['vehicle_id'])->first();
            if ($veh) {
                $vehId = $veh->id;
                $vehText = trim(implode(' ', array_filter([$veh->license_plate, $veh->brand_name, $veh->model_name, $veh->engine_description])));
            }
        }
        // plaque identifiée automatiquement (relue côté serveur, jamais crue sur parole) → véhicule du client, comme dans l'ERP
        $plateN = \App\Services\PlateLookup::valid($v['plate'] ?? '') ? \App\Services\PlateLookup::normalize($v['plate']) : null;
        $plateData = null;
        if ($plateN && !$vehId) {
            $plateData = \App\Services\PlateLookup::lookup($plateN);
            $veh = \App\Services\PlateLookup::vehicleFor($c, $plateN, $plateData);
            if ($veh) { $vehId = $veh->id; $vehText = trim(implode(' ', array_filter([$veh->license_plate, $veh->brand_name, $veh->model_name, $veh->engine_description]))); }
            elseif ($plateData) { $vehText = trim($plateData['plate'] . ' ' . $plateData['label']); }
            elseif ($vehText === '') { $vehText = \App\Services\PlateLookup::pretty($plateN); }
        }
        $vin = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) ($v['vin'] ?? '')));
        if ($vin === '' && $plateData && !empty($plateData['vin'])) $vin = $plateData['vin'];

        try {
            $q = DB::transaction(function () use ($u, $c, $vehId, $vehText, $vin, $v, $lines, $plateN) {
                $q = PortalQuote::create([
                    'customer_id' => $c->id, 'b2b_user_id' => $u->id, 'status' => 'new',
                    'vehicle_id' => $vehId, 'vehicle_text' => $vehText !== '' ? mb_substr($vehText, 0, 255) : null, 'vin' => $vin !== '' ? $vin : null, 'plate' => $plateN ? \App\Services\PlateLookup::pretty($plateN) : null,
                    'note' => trim((string) ($v['note'] ?? '')) ?: null, 'lines_count' => count($lines),
                ]);
                $q->number = 'DEV-' . str_pad($q->id, 6, '0', STR_PAD_LEFT);
                $q->save();
                foreach ($lines as $l) $q->lines()->create($l);
                return $q;
            });
        } catch (\Throwable $e) {
            Log::channel('single')->error('portal quote failed', ['user' => $u->id, 'e' => $e->getMessage()]);
            return $this->fail($r, "La demande n'a pas pu être enregistrée. Réessayez dans un instant.");
        }

        $nFiles = \App\Services\PortalQuoteFiles::store($q, $r->file('files'), 'client', $u->name);
        $q->log('client', 'created', $nFiles ? $nFiles . ' pièce(s) jointe(s)' : null, $u->name);
        $this->mailVendor($s, $q, $c, 'Demande de devis ' . $q->number . ' - ' . $c->name, 'Nouvelle demande de devis portail ' . $q->number . "\n\nClient : " . $c->name . "\nVéhicule : " . ($q->vehicle_text ?: '—') . "\nPièces demandées : " . $q->lines_count . "\n");
        if ($r->expectsJson()) return response()->json(['ok' => true, 'url' => route('portal.quote', ['id' => $q->id], false)]);
        return redirect()->route('portal.quote', ['id' => $q->id])->with('status', 'Votre demande ' . $q->number . ' a bien été envoyée.');
    }

    private function fail(Request $r, $msg)
    {
        if ($r->expectsJson()) return response()->json(['ok' => false, 'message' => $msg], 422);
        return back()->withInput()->withErrors(['lines' => $msg]);
    }

    public function show(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        $q->load('events');
        return $this->view('portal.quote', $r, ['q' => $q, 'files' => \App\Services\PortalQuoteFiles::listFor($q)]);
    }

    /** Devis en PDF, même format que le devis de l'ERP. Seulement quand le vendeur a chiffré la demande. ?inline=1 pour l'afficher. */
    public function pdf(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        abort_unless(in_array($q->status, ['answered', 'accepted', 'negotiating'], true), 404);
        list($pdf, $name) = (new \App\Services\PortalQuotePdf())->make($q);
        return $r->boolean('inline') ? $pdf->stream($name) : $pdf->download($name);
    }

    /** Le client accepte le devis (en entier ou seulement certaines lignes) : l'équipe est prévenue et prépare la commande. */
    public function accept(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        if ($q->status !== 'answered') return back()->withErrors(['q' => 'Ce devis ne peut plus être accepté.']);
        if ($q->expired) return back()->withErrors(['q' => 'Ce devis a expiré. Contactez-nous pour le renouveler.']);
        $u = $r->attributes->get('portal_user');
        $eligible = $q->lines->filter(function ($l) { return $l->unit_ht !== null && $l->availability !== 'unavailable'; });
        if ($eligible->isEmpty()) return back()->withErrors(['q' => 'Aucune ligne chiffrée à accepter.']);
        $keepIds = $r->has('keep') ? array_map('intval', (array) $r->input('keep')) : $eligible->pluck('id')->all();   // sans sélection : tout
        $keep = $eligible->filter(function ($l) use ($keepIds) { return in_array((int) $l->id, $keepIds, true); });
        if ($keep->isEmpty()) return back()->withErrors(['q' => 'Cochez au moins une ligne à accepter, ou refusez le devis.']);
        $refused = $eligible->diff($keep);

        $done = DB::transaction(function () use ($q, $eligible, $keep) {
            $fresh = PortalQuote::lockForUpdate()->find($q->id);
            if (!$fresh || $fresh->status !== 'answered') return false;
            $ht = 0.0; $ttc = 0.0;
            foreach ($eligible as $l) {
                $kept = $keep->contains('id', $l->id);
                $l->update(['accepted' => $kept]);
                if ($kept) { $ht += (float) $l->line_ht; $ttc += (float) $l->line_ht * (1 + (float) $l->tva_rate / 100); }
            }
            $ht = round($ht, 2); $ttc = round($ttc, 2);
            $fresh->update([
                'status' => 'accepted', 'decided_at' => now(),
                'offered_ht' => $fresh->offered_ht !== null ? $fresh->offered_ht : $fresh->total_ht, 'offered_ttc' => $fresh->offered_ttc !== null ? $fresh->offered_ttc : $fresh->total_ttc,
                'total_ht' => $ht, 'total_ttc' => $ttc, 'total_tva' => round($ttc - $ht, 2),
            ]);
            return [$ht, $ttc];
        });
        if (!$done) return back()->withErrors(['q' => 'Ce devis a déjà été traité.']);
        $q->refresh();

        $partial = $refused->count() > 0;
        $text = $partial ? $keep->count() . ' ligne(s) acceptée(s), ' . $refused->count() . ' refusée(s) : ' . $refused->map(function ($l) { return $l->designation; })->implode(' ; ') : null;
        $q->log('client', 'accepted', $text, $u->name);
        $this->mailVendor($r->attributes->get('portal_settings'), $q, $u->customer, 'Devis accepté' . ($partial ? ' (partiel)' : '') . ' ' . $q->number . ' - ' . $u->customer->name,
            'Le client ' . $u->customer->name . ' a accepté ' . ($partial ? 'en partie ' : '') . 'le devis ' . $q->number . ' (' . number_format($q->total_ttc, 2, ',', ' ') . " € TTC).\n" . ($text ? "\n" . $text : ''));
        return back()->with('status', $partial ? 'Devis accepté pour les lignes sélectionnées. Notre équipe prépare votre commande.' : 'Devis accepté. Notre équipe prépare votre commande.');
    }

    /** Plaque d'immatriculation → marque / modèle / motorisation (appel API côté serveur). */
    public function plate(Request $r)
    {
        $v = $r->validate(['plate' => 'required|string|max:20']);
        if (!\App\Services\PlateLookup::valid($v['plate'])) return response()->json(['ok' => false, 'message' => 'Plaque invalide : vérifiez les caractères.'], 422);
        if (!\App\Services\PlateLookup::enabled()) return response()->json(['ok' => false, 'message' => "L'identification automatique n'est pas disponible. Décrivez le véhicule ci-dessous."], 200);
        $d = \App\Services\PlateLookup::lookup($v['plate']);
        if (!$d) return response()->json(['ok' => false, 'message' => 'Plaque non reconnue. Vous pouvez décrire le véhicule ci-dessous : nous la garderons telle quelle.', 'plate' => \App\Services\PlateLookup::pretty($v['plate'])], 200);
        return response()->json(['ok' => true] + $d);
    }

    /** Affiche / télécharge une pièce jointe de CE devis (le devis est d'abord vérifié : il doit appartenir au client). */
    public function file(Request $r, $id, $fileId)
    {
        $q = $this->mine($r, $id);
        return \App\Services\PortalQuoteFiles::response($q, $fileId, $r->boolean('dl'));
    }

    /** Le client ajoute des photos / documents à une demande en cours. */
    public function addFiles(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        if (!in_array($q->status, ['new', 'processing', 'negotiating', 'answered'], true)) return back()->withErrors(['q' => 'Vous ne pouvez plus ajouter de fichier à ce devis.']);
        $r->validate(\App\Services\PortalQuoteFiles::rules(), \App\Services\PortalQuoteFiles::messages());
        $u = $r->attributes->get('portal_user');
        $n = \App\Services\PortalQuoteFiles::store($q, $r->file('files'), 'client', $u->name);
        if (!$n) return back()->withErrors(['q' => 'Aucun fichier valide reçu (photos JPG/PNG/WebP ou PDF, 8 Mo maximum).']);
        $q->log('client', 'file', $n . ' pièce(s) jointe(s) ajoutée(s)', $u->name);
        $this->mailVendor($r->attributes->get('portal_settings'), $q, $u->customer, 'Pièce jointe ajoutée ' . $q->number . ' - ' . $u->customer->name, 'Le client ' . $u->customer->name . ' a ajouté ' . $n . ' fichier(s) à la demande ' . $q->number . '.');
        return back()->with('status', $n . ' fichier(s) ajouté(s).');
    }

    /** Renégociation : le client propose une quantité / un prix souhaité par ligne et/ou un message ; la demande repart chez le vendeur. */
    public function negotiate(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        if ($q->status !== 'answered') return back()->withErrors(['q' => 'Ce devis ne peut plus être modifié.']);
        if ($q->expired) return back()->withErrors(['q' => 'Ce devis a expiré. Contactez-nous pour le renouveler.']);
        $v = $r->validate([
            'message' => 'nullable|string|max:1000', 'lines' => 'nullable|array|max:60',
            'lines.*.qty' => 'nullable|integer|min:1|max:9999', 'lines.*.target' => 'nullable|numeric|min:0|max:10000000', 'lines.*.comment' => 'nullable|string|max:255',
        ]);
        $summary = []; $changed = false;
        foreach ($q->lines as $l) {
            $in = $v['lines'][$l->id] ?? null;                     // uniquement les lignes DE CE devis
            if (!$in) continue;
            $qty = !empty($in['qty']) ? (int) $in['qty'] : (int) $l->qty;
            $target = (isset($in['target']) && $in['target'] !== '' && $in['target'] !== null) ? round((float) $in['target'], 4) : null;
            $comment = trim((string) ($in['comment'] ?? '')) ?: null;
            if ($qty === (int) $l->qty && $target === null && $comment === null) continue;
            $bits = [];
            if ($qty !== (int) $l->qty) $bits[] = 'quantité ' . $l->qty . ' → ' . $qty;
            if ($target !== null) $bits[] = 'prix souhaité ' . number_format($target, 2, ',', ' ') . ' € HT';
            if ($comment) $bits[] = '« ' . $comment . ' »';
            $summary[] = $l->designation . ' : ' . implode(', ', $bits);
            $l->update(['qty' => $qty, 'target_price' => $target, 'client_comment' => $comment, 'line_ht' => $l->unit_ht !== null && $l->availability !== 'unavailable' ? round(($l->list_ht !== null ? $l->list_ht : $l->unit_ht) * $qty * (1 - (float) $l->remise / 100), 2) : 0]);
            $changed = true;
        }
        $msg = trim((string) ($v['message'] ?? ''));
        if (!$changed && $msg === '') return back()->withErrors(['q' => 'Indiquez au moins un changement (quantité, prix souhaité, commentaire) ou un message.']);
        $q->update(['status' => 'negotiating']);
        $u = $r->attributes->get('portal_user');
        $text = trim(($msg !== '' ? $msg . "\n" : '') . implode("\n", $summary));
        $q->log('client', 'negotiate', $text, $u->name);
        $this->mailVendor($r->attributes->get('portal_settings'), $q, $u->customer, 'Modification demandée ' . $q->number . ' - ' . $u->customer->name, 'Le client ' . $u->customer->name . ' demande une modification du devis ' . $q->number . ".\n\n" . $text);
        return back()->with('status', 'Votre demande de modification est envoyée. Nous revenons vers vous avec un devis mis à jour.');
    }

    /** Devis créé dans l'ERP par un vendeur pour CE client (statut « Devis »), au format PDF de l'ERP. Marque le devis comme vu. */
    public function erpPdf(Request $r, $id)
    {
        $u = $r->attributes->get('portal_user');
        abort_unless(PortalQuoteNotices::showErp($r->attributes->get('portal_settings')), 404);
        $found = PortalQuoteNotices::erpQuery($u->customer_id)->where('id', $id)->firstOrFail(['id']);
        try {
            DB::table('portal_erp_quote_views')->updateOrInsert(['sales_order_id' => $found->id], ['customer_id' => $u->customer_id, 'viewed_at' => now()]);
        } catch (\Throwable $e) { }
        $order = \App\Models\SalesOrder::with(['customer', 'deliveryNote', 'lines.item', 'customer.tvaGroup'])->findOrFail($found->id);
        $s = $r->attributes->get('portal_settings');
        $company = \App\Models\CompanyInformation::first() ?? new \App\Models\CompanyInformation(['name' => (string) ($s->company_name ?: 'Société')]);
        $barcode = 'data:image/png;base64,' . base64_encode((new \Picqer\Barcode\BarcodeGeneratorPNG())->getBarcode($order->numdoc, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128));
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sales_order', compact('order', 'company', 'barcode'));
        $name = 'devis_' . preg_replace('/[^A-Za-z0-9._-]+/', '_', $order->numdoc) . '.pdf';
        return $r->boolean('inline') ? $pdf->stream($name) : $pdf->download($name);
    }

    public function decline(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        if ($q->status !== 'answered') return back()->withErrors(['q' => 'Ce devis ne peut plus être refusé.']);
        $q->update(['status' => 'declined', 'decided_at' => now()]);
        $q->log('client', 'declined', trim((string) $r->input('reason')) ?: null, $r->attributes->get('portal_user')->name);
        return back()->with('status', 'Devis refusé.');
    }

    public function cancel(Request $r, $id)
    {
        $q = $this->mine($r, $id);
        if (!in_array($q->status, ['new', 'processing', 'negotiating'], true)) return back()->withErrors(['q' => 'Cette demande ne peut plus être annulée.']);
        $q->update(['status' => 'cancelled', 'decided_at' => now()]);
        $q->log('client', 'cancelled', null, $r->attributes->get('portal_user')->name);
        return back()->with('status', 'Demande annulée.');
    }

    private function mailVendor($s, PortalQuote $q, $c, $subject, $body)
    {
        $to = trim((string) ($s->notification_email ?: $s->email));
        if (!$s->notify_new_order || $to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) return;
        try {
            dispatch(function () use ($to, $subject, $body) {
                try { \Illuminate\Support\Facades\Mail::raw($body, function ($m) use ($to, $subject) { $m->to($to)->subject($subject); }); }
                catch (\Throwable $e) { Log::channel('single')->warning('portal quote mail failed: ' . $e->getMessage()); }
            })->afterResponse();
        } catch (\Throwable $e) { }
    }
}
