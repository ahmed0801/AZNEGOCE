<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\CompanyInformation;
use App\Models\Customer;
use App\Models\Item;
use App\Models\PortalQuote;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

/**
 * PDF d'une demande de devis du portail, dans EXACTEMENT le format du devis de l'ERP (vue pdf.sales_order).
 * On construit en mémoire une commande vente « Devis » (rien n'est écrit en base) à partir des lignes chiffrées.
 */
class PortalQuotePdf
{
    /** @return array [PDF, nom de fichier] */
    public function make(PortalQuote $q)
    {
        $q->loadMissing('lines');
        $customer = Customer::with('tvaGroup')->findOrFail($q->customer_id);

        // TVA unique du document (la vue ERP applique un seul taux) : taux effectif du devis
        $ht = (float) $q->total_ht;
        $rate = $ht > 0 ? round(((float) $q->total_tva / $ht) * 100, 2) : (float) ($customer->tvaGroup->rate ?? 0);
        $customer->setRelation('tvaGroup', (object) ['rate' => $rate]);

        $numdoc = $q->erp_numdoc ?: $q->number;
        $order = new SalesOrder();
        $order->forceFill([
            'numdoc' => $numdoc, 'status' => 'Devis', 'order_date' => ($q->answered_at ?: $q->created_at)->toDateString(),
            'total_ht' => $ht, 'total_ttc' => (float) $q->total_ttc, 'tva_rate' => $rate,
            'notes' => $q->vendor_message ?: null, 'vendeur' => $this->sellerName($q),
        ]);
        $order->setRelation('customer', $customer);
        $order->setRelation('deliveryNote', null);

        $veh = null;
        if ($q->vehicle_id) { try { $veh = $customer->vehicles()->where('id', $q->vehicle_id)->first(); } catch (\Throwable $e) { } }
        if (!$veh && $q->vehicle_text) {
            $veh = (object) ['license_plate' => $q->vehicle_text, 'brand_name' => $q->vin ? 'VIN ' . $q->vin : 'saisie client', 'model_name' => ''];
        }
        $order->setRelation('vehicle', $veh);

        $lines = [];
        foreach ($q->lines as $l) {
            if ($l->availability === 'unavailable' || $l->unit_ht === null) continue;       // seules les pièces proposées figurent sur le devis
            if ($l->accepted !== null && (int) $l->accepted === 0) continue;                // ligne refusée par le client
            $line = new SalesOrderLine();
            $line->forceFill([
                'article_code' => $l->vendor_ref ?: ($l->reference ?: '-'), 'ordered_quantity' => $l->qty,
                'unit_price_ht' => (float) ($l->list_ht !== null ? $l->list_ht : $l->unit_ht), 'remise' => (float) $l->remise, 'total_ligne_ht' => (float) $l->line_ht,
            ]);
            $name = $l->designation . ($l->availability === 'delay' && $l->delay_text ? ' (' . $l->delay_text . ')' : '');
            $line->setRelation('item', new Item(['name' => $name]));
            $lines[] = $line;
        }
        $order->setRelation('lines', collect($lines));

        $s = B2bSetting::current();
        $company = CompanyInformation::first() ?? new CompanyInformation(['name' => (string) ($s->company_name ?: 'Société')]);
        $barcode = 'data:image/png;base64,' . base64_encode((new BarcodeGeneratorPNG())->getBarcode($numdoc, BarcodeGeneratorPNG::TYPE_CODE_128));

        $pdf = Pdf::loadView('pdf.sales_order', compact('order', 'company', 'barcode'));
        return [$pdf, 'devis_' . preg_replace('/[^A-Za-z0-9._-]+/', '_', $numdoc) . '.pdf'];
    }

    private function sellerName(PortalQuote $q)
    {
        if (!$q->answered_by) return '';
        try { $u = \App\Models\User::find($q->answered_by); return $u ? (string) $u->name : ''; } catch (\Throwable $e) { return ''; }
    }
}
