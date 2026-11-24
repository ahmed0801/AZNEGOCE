<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteLine;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\PortalOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Pont entre le portail et les documents de vente de l'ERP.
 *  - validation manuelle : une COMMANDE VENTE en « brouillon » que le vendeur convertit en BL ou facture comme d'habitude ;
 *  - validation automatique : COMMANDE VENTE (validée) + BON DE LIVRAISON (sortie de stock) + FACTURE.
 * Mêmes règles que SalesController (souches, formules de totaux, stock, solde client).
 */
class PortalErpBridge
{
    const STORE_ID = 1;

    /** Crée les documents ERP d'une commande portail. Ne lève jamais : renvoie ['ok'=>bool,'message'=>?]. */
    public function sync(PortalOrder $o)
    {
        if ($o->erp_document_id && (!$o->auto_validated || $o->erp_invoice_id)) return ['ok' => true];
        try {
            DB::transaction(function () use ($o) {
                $o->loadMissing('lines');
                $customer = Customer::with(['tvaGroup', 'paymentTerm'])->findOrFail($o->customer_id);
                $auto = (bool) $o->auto_validated;
                $so = $o->erp_document_id ? SalesOrder::with('lines')->find($o->erp_document_id) : null;
                if (!$so) $so = $this->createSalesOrder($o, $customer, $auto);
                if ($auto) $this->createDeliveryAndInvoice($o, $so, $customer);
                $o->erp_error = null; $o->save();
            });
            return ['ok' => true];
        } catch (\Throwable $e) {
            Log::channel('single')->error('portal erp sync failed', ['order' => $o->id, 'e' => $e->getMessage()]);
            $o->refresh();
            $o->erp_error = mb_substr($e->getMessage(), 0, 500);
            if ($o->auto_validated && !$o->erp_invoice_id) {      // la validation automatique a échoué : la commande repasse « à valider »
                $o->status = 'pending'; $o->auto_validated = false; $o->validated_at = null;
            }
            $o->save();
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    /** Supprime la commande vente brouillon liée (refus / annulation). false = déjà traitée dans l'ERP, on ne touche à rien. */
    public function discardDraft(PortalOrder $o)
    {
        if (!$o->erp_document_id) return true;
        $so = SalesOrder::find($o->erp_document_id);
        if (!$so) { $o->update(['erp_document_id' => null, 'erp_numdoc' => null]); return true; }
        if ($so->status !== 'brouillon' || DeliveryNote::where('sales_order_id', $so->id)->exists()) return false;
        DB::transaction(function () use ($o, $so) {
            SalesOrderLine::where('sales_order_id', $so->id)->delete();
            $so->delete();
            $o->update(['erp_document_id' => null, 'erp_numdoc' => null, 'erp_document_type' => null]);
        });
        return true;
    }

    /** Stock suffisant pour TOUTES les lignes ? (condition de la validation automatique) */
    public function stockCovers(PortalOrder $o)
    {
        $o->loadMissing('lines');
        $act = $o->lines->where('removed', false);
        $sums = DB::table('stocks')->whereIn('item_id', $act->pluck('item_id')->all())->select('item_id', DB::raw('SUM(quantity) as q'))->groupBy('item_id')->pluck('q', 'item_id')->all();
        foreach ($act as $l) { if ((float) ($sums[$l->item_id] ?? 0) < $l->qty) return false; }
        return true;
    }


    /** (Re)crée les lignes de la commande vente à partir des lignes ACTIVES de la commande portail. Renvoie le total HT. */
    private function fillLines(SalesOrder $so, PortalOrder $o, $tva)
    {
        $total = 0;
        foreach ($o->lines as $l) {
            if ($l->removed) continue;
            $item = Item::find($l->item_id);
            if (!$item) throw new \Exception("Article {$l->code} introuvable dans l'ERP.");
            list($pu, $remise) = $this->priceOf($l);
            $ht = $l->qty * $pu * (1 - $remise / 100);
            $total += $ht;
            SalesOrderLine::create([
                'sales_order_id' => $so->id, 'article_code' => $item->code, 'ordered_quantity' => $l->qty,
                'unit_price_ht' => $pu, 'unit_price_ttc' => $pu * (1 - $remise / 100) * (1 + $tva / 100), 'remise' => $remise,
                'total_ligne_ht' => $ht, 'total_ligne_ttc' => $ht * (1 + $tva / 100),
                'supplier_id' => null, 'unit_coast' => $item->cost_price ?? 0, 'discount_coast' => 0,
            ]);
        }
        return $total;
    }

    /** Répercute les modifications du vendeur sur la commande vente brouillon liée (lignes + totaux). Lève une exception si elle n'est plus un brouillon. */
    public function resync(PortalOrder $o)
    {
        if (!$o->erp_document_id) return;
        $so = SalesOrder::lockForUpdate()->find($o->erp_document_id);
        if (!$so) return;
        if ($so->status !== 'brouillon' || DeliveryNote::where('sales_order_id', $so->id)->exists()) {
            throw new \Exception('La commande vente ' . $o->erp_numdoc . " a déjà été traitée dans l'ERP : modifiez-la là-bas.");
        }
        $o->loadMissing('lines');
        $tva = (float) ($so->tva_rate ?? 0);
        SalesOrderLine::where('sales_order_id', $so->id)->delete();
        $total = $this->fillLines($so, $o, $tva);
        $so->update(['total_ht' => $total, 'total_ttc' => $total * (1 + $tva / 100), 'notes' => $this->lineNotes($o)]);
    }

    // ───────────────────────── internes ─────────────────────────

    private function nextNumdoc($type, $model)
    {
        $souche = Souche::where('type', $type)->lockForUpdate()->first();
        if (!$souche) throw new \Exception("Souche « $type » manquante dans l'ERP.");
        for ($i = 0; $i < 5; $i++) {
            $n = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
            $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $n;
            if (!$model::where('numdoc', $numdoc)->exists()) return [$souche, $numdoc];
            $souche->last_number += 1; $souche->save();
        }
        throw new \Exception('Impossible de générer un numéro de document unique.');
    }

    private function lineNotes(PortalOrder $o)
    {
        $t = 'Commande portail ' . $o->number . ' (' . ($o->delivery_mode === 'pickup' ? 'retrait au magasin' : 'livraison') . ')';
        if ($o->po_ref) $t .= ' - Réf. client : ' . $o->po_ref;
        if ($o->note) $t .= ' - Message : ' . trim(preg_replace('/\s+/', ' ', $o->note));
        return $t;
    }

    /** Prix de la ligne au format ERP : tarif + remise % si le calcul tombe juste, sinon prix net sans remise. */
    private function priceOf($l)
    {
        $list = (float) $l->list_ht; $r = (float) $l->discount; $net = (float) $l->unit_ht;
        if ($list > 0 && $r > 0 && abs($list * (1 - $r / 100) - $net) < 0.0001) return [$list, $r];
        return [$net, 0.0];
    }

    private function createSalesOrder(PortalOrder $o, Customer $customer, $auto)
    {
        $tva = (float) ($customer->tvaGroup->rate ?? 0);
        list($souche, $numdoc) = $this->nextNumdoc('commande_vente', SalesOrder::class);

        $so = SalesOrder::create([
            'customer_id' => $customer->id, 'order_date' => now()->toDateString(), 'numclient' => $customer->code,
            'status' => $auto ? 'validée' : 'brouillon', 'total_ht' => 0, 'total_ttc' => 0,
            'notes' => $this->lineNotes($o), 'numdoc' => $numdoc, 'tva_rate' => $tva, 'store_id' => self::STORE_ID,
        ]);
        $total = $this->fillLines($so, $o, $tva);
        $so->update(['total_ht' => $total, 'total_ttc' => $total * (1 + $tva / 100)]);
        $souche->last_number += 1; $souche->save();

        $o->erp_document_type = 'sales_order'; $o->erp_document_id = $so->id; $o->erp_numdoc = $numdoc; $o->save();
        return $so->load('lines');
    }

    private function createDeliveryAndInvoice(PortalOrder $o, SalesOrder $so, Customer $customer)
    {
        $tva = (float) ($so->tva_rate ?? 0);
        list($sBl, $numBl) = $this->nextNumdoc('bon_livraison', DeliveryNote::class);

        $bl = DeliveryNote::create([
            'sales_order_id' => $so->id, 'customer_id' => $customer->id, 'delivery_date' => now(), 'status' => 'expédié',
            'total_delivered' => 0, 'total_ht' => 0, 'total_ttc' => 0, 'numclient' => $customer->code,
            'tva_rate' => $tva, 'numdoc' => $numBl, 'notes' => $this->lineNotes($o), 'store_id' => self::STORE_ID, 'invoiced' => true,
        ]);
        $delivered = 0; $ht = 0;
        foreach ($so->lines as $line) {
            $item = Item::where('code', $line->article_code)->first();
            $lht = $line->ordered_quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100);
            DeliveryNoteLine::create([
                'delivery_note_id' => $bl->id, 'article_code' => $line->article_code, 'delivered_quantity' => $line->ordered_quantity,
                'unit_price_ht' => $line->unit_price_ht, 'unit_price_ttc' => $line->unit_price_ttc, 'remise' => $line->remise ?? 0,
                'total_ligne_ht' => $lht, 'total_ligne_ttc' => $lht * (1 + $tva / 100),
                'supplier_id' => $line->supplier_id, 'unit_coast' => $line->unit_coast, 'discount_coast' => $line->discount_coast,
            ]);
            $delivered += $line->ordered_quantity; $ht += $lht;
            if ($item) {
                $stock = Stock::firstOrNew(['item_id' => $item->id, 'store_id' => self::STORE_ID]);
                $stock->quantity = ($stock->quantity ?? 0) - $line->ordered_quantity; $stock->save();
                StockMovement::create([
                    'item_id' => $item->id, 'store_id' => self::STORE_ID, 'type' => 'vente', 'quantity' => -$line->ordered_quantity,
                    'cost_price' => $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100), 'supplier_name' => $customer->name,
                    'reason' => 'Validation bon de livraison #' . $numBl, 'reference' => $numBl,
                ]);
            }
        }
        $ttc = $ht * (1 + $tva / 100);
        $bl->update(['total_delivered' => $delivered, 'total_ht' => $ht, 'total_ttc' => $ttc]);
        $sBl->last_number += 1; $sBl->save();

        list($sIn, $numIn) = $this->nextNumdoc('facture_vente', Invoice::class);
        $due = $customer->paymentTerm ? Carbon::now()->addDays($customer->paymentTerm->days) : null;
        $inv = Invoice::create([
            'numdoc' => $numIn, 'type' => 'direct', 'numclient' => $customer->code, 'customer_id' => $customer->id,
            'invoice_date' => now(), 'due_date' => $due, 'status' => 'validée', 'paid' => false,
            'total_ht' => $ht, 'total_ttc' => $ttc, 'tva_rate' => $tva, 'notes' => $this->lineNotes($o),
        ]);
        foreach ($bl->lines()->get() as $l) {
            InvoiceLine::create([
                'invoice_id' => $inv->id, 'delivery_note_id' => $bl->id, 'article_code' => $l->article_code, 'quantity' => $l->delivered_quantity,
                'unit_price_ht' => $l->unit_price_ht, 'remise' => $l->remise ?? 0, 'total_ligne_ht' => $l->total_ligne_ht, 'total_ligne_ttc' => $l->total_ligne_ttc,
            ]);
        }
        $customer->solde = ($customer->solde ?? 0) + $ttc; $customer->save();
        $inv->deliveryNotes()->attach($bl->id);
        $sIn->last_number += 1; $sIn->save();

        $o->erp_document_type = 'invoice'; $o->erp_delivery_note_id = $bl->id; $o->erp_invoice_id = $inv->id; $o->erp_invoice_numdoc = $numIn; $o->save();
    }
}
