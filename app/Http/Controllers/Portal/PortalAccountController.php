<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteLine;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Espace « Mon compte » du portail : situation financière, factures, bons de livraison.
 * Toujours limité au client de l'utilisateur connecté, et réservé aux clients dont « Voit sa situation financière » est activé.
 */
class PortalAccountController extends Controller
{
    private function ctx(Request $r)
    {
        $u = $r->attributes->get('portal_user');
        $cs = $r->attributes->get('portal_customer_settings');
        if (!$cs->show_finance) abort(404);
        return [$r->attributes->get('portal_settings'), $u, $u->customer, $cs];
    }

    private function view($name, Request $r, $c, array $data = [])
    {
        return view($name, ['s' => $r->attributes->get('portal_settings'), 'pUser' => $r->attributes->get('portal_user'), 'customer' => $c, 'cs' => $r->attributes->get('portal_customer_settings')] + $data);
    }

    /** Documents du client (jamais ceux d'un autre) : par customer_id, ou par code client quand customer_id est vide. Hors brouillons / annulés. */
    private function scope($q, $c)
    {
        return \App\Services\PortalDashboardService::scope($q, $c);
    }

    /** Règlements déjà reçus par facture (si l'ERP a une table de règlements reconnue ; sinon vide = on s'appuie sur le statut « payée »). */
    private function paidMap(array $ids)
    {
        if (!$ids) return [];
        try {
            foreach (['payments', 'invoice_payments'] as $t) {
                if (Schema::hasTable($t) && Schema::hasColumn($t, 'invoice_id') && Schema::hasColumn($t, 'amount')) {
                    return DB::table($t)->whereIn('invoice_id', $ids)->select('invoice_id', DB::raw('SUM(amount) as s'))->groupBy('invoice_id')->pluck('s', 'invoice_id')->all();
                }
            }
        } catch (\Throwable $e) { }
        return [];
    }

    private function remaining($inv, array $paid)
    {
        if ($inv->paid) return 0.0;
        return max(0.0, round((float) $inv->total_ttc - (float) ($paid[$inv->id] ?? 0), 2));
    }

    private function overdue($inv, $rest)
    {
        return $rest > 0 && $inv->due_date && Carbon::parse($inv->due_date)->startOfDay()->lt(Carbon::today());
    }

    private function invRow($inv, array $paid)
    {
        $rest = $this->remaining($inv, $paid);
        $inv->rest = $rest; $inv->late = $this->overdue($inv, $rest);
        return $inv;
    }

    public function index(Request $r)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $unpaid = $this->scope(Invoice::query(), $c)->where(function ($w) { $w->whereNull('paid')->orWhere('paid', false); })->get(['id', 'total_ttc', 'paid', 'due_date']);
        $paid = $this->paidMap($unpaid->pluck('id')->all());
        $open = 0.0; $late = 0.0; $nLate = 0;
        foreach ($unpaid as $i) { $rest = $this->remaining($i, $paid); $open += $rest; if ($this->overdue($i, $rest)) { $late += $rest; $nLate++; } }
        $dnOpen = $this->scope(DeliveryNote::query(), $c)->where(function ($w) { $w->whereNull('invoiced')->orWhere('invoiced', false); });
        $dnTotal = (float) (clone $dnOpen)->sum('total_ttc'); $dnCount = (clone $dnOpen)->count();
        $invs = $this->scope(Invoice::query(), $c)->orderByDesc('invoice_date')->orderByDesc('id')->limit(6)->get();
        $paid2 = $this->paidMap($invs->pluck('id')->all());
        $invs->each(function ($i) use ($paid2) { $this->invRow($i, $paid2); });
        $dns = $dnOpen->orderByDesc('delivery_date')->orderByDesc('id')->limit(6)->get();
        return $this->view('portal.account', $r, $c, [
            'solde' => (float) ($c->solde ?? 0), 'open' => $open, 'late' => $late, 'nLate' => $nLate, 'nUnpaid' => $unpaid->count(),
            'dnTotal' => $dnTotal, 'dnCount' => $dnCount, 'exposure' => (float) ($c->solde ?? 0) + $dnTotal,
            'invs' => $invs, 'dns' => $dns,
        ]);
    }

    public function invoices(Request $r)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $f = $r->query('f') === 'unpaid' ? 'unpaid' : 'all';
        $q = $this->scope(Invoice::query(), $c)->orderByDesc('invoice_date')->orderByDesc('id');
        if ($f === 'unpaid') $q->where(function ($w) { $w->whereNull('paid')->orWhere('paid', false); });
        $p = $q->paginate(20)->appends(['f' => $f]);
        $paid = $this->paidMap(collect($p->items())->pluck('id')->all());
        foreach ($p->items() as $i) $this->invRow($i, $paid);
        return $this->view('portal.account_invoices', $r, $c, ['rows' => $p, 'f' => $f]);
    }

    public function invoice(Request $r, $id)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $inv = $this->scope(Invoice::query(), $c)->where('id', $id)->firstOrFail();
        $this->invRow($inv, $this->paidMap([$inv->id]));
        $lines = InvoiceLine::where('invoice_id', $inv->id)->orderBy('id')->get();
        $names = Item::whereIn('code', $lines->pluck('article_code')->unique()->all())->pluck('name', 'code')->all();
        $bls = DeliveryNote::whereIn('id', $lines->pluck('delivery_note_id')->filter()->unique()->all())->get(['id', 'numdoc', 'delivery_date']);
        return $this->view('portal.account_invoice', $r, $c, ['inv' => $inv, 'lines' => $lines, 'names' => $names, 'bls' => $bls]);
    }

    /** Facture au même format PDF que dans l'ERP (vue pdf.invoice), uniquement pour une facture de CE client. ?inline=1 pour l'afficher au lieu de la télécharger. */
    public function invoicePdf(Request $r, $id)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $found = $this->scope(Invoice::query(), $c)->where('id', $id)->firstOrFail(['id']);
        $invoice = Invoice::with(['customer', 'lines.item', 'deliveryNotes', 'salesReturns', 'vehicle'])->findOrFail($found->id);
        $company = \App\Models\CompanyInformation::first() ?? new \App\Models\CompanyInformation(['name' => (string) ($s->company_name ?: 'Société')]);
        $barcode = 'data:image/png;base64,' . base64_encode((new \Picqer\Barcode\BarcodeGeneratorPNG())->getBarcode($invoice->numdoc, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128));
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('invoice', 'company', 'barcode'));
        $name = 'facture_' . preg_replace('/[^A-Za-z0-9._-]+/', '_', $invoice->numdoc) . '.pdf';
        return $r->boolean('inline') ? $pdf->stream($name) : $pdf->download($name);
    }

    /** Bon de livraison au même format PDF que dans l'ERP (vue pdf.delivery_note), uniquement pour un BL de CE client. ?inline=1 pour l'afficher. */
    public function deliveryNotePdf(Request $r, $id)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $found = $this->scope(DeliveryNote::query(), $c)->where('id', $id)->firstOrFail(['id']);
        $q = DeliveryNote::with(['salesOrder', 'lines.item', 'vehicle'])->where('delivery_notes.id', $found->id);
        if (Schema::hasTable('customers')) {
            $q->leftJoin('customers', 'delivery_notes.numclient', '=', 'customers.code')->select('delivery_notes.*', 'customers.name as customer_name');
        }
        $deliveryNote = $q->firstOrFail();
        $company = \App\Models\CompanyInformation::first() ?? new \App\Models\CompanyInformation(['name' => (string) ($s->company_name ?: 'Société')]);
        $barcode = 'data:image/png;base64,' . base64_encode((new \Picqer\Barcode\BarcodeGeneratorPNG())->getBarcode($deliveryNote->numdoc, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128));
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.delivery_note', compact('deliveryNote', 'company', 'barcode'));
        $name = 'bon_livraison_' . preg_replace('/[^A-Za-z0-9._-]+/', '_', $deliveryNote->numdoc) . '.pdf';
        return $r->boolean('inline') ? $pdf->stream($name) : $pdf->download($name);
    }

    public function deliveryNotes(Request $r)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $f = $r->query('f') === 'open' ? 'open' : 'all';
        $base = $this->scope(DeliveryNote::query(), $c);
        $open = (clone $base)->where(function ($w) { $w->whereNull('invoiced')->orWhere('invoiced', false); });
        $tot = (float) (clone $open)->sum('total_ttc'); $n = (clone $open)->count();
        $q = ($f === 'open' ? $open : $base)->orderByDesc('delivery_date')->orderByDesc('id');
        return $this->view('portal.account_dns', $r, $c, ['rows' => $q->paginate(20)->appends(['f' => $f]), 'f' => $f, 'openTotal' => $tot, 'openCount' => $n]);
    }

    public function deliveryNote(Request $r, $id)
    {
        list($s, $u, $c, $cs) = $this->ctx($r);
        $bl = $this->scope(DeliveryNote::query(), $c)->where('id', $id)->firstOrFail();
        $lines = DeliveryNoteLine::where('delivery_note_id', $bl->id)->orderBy('id')->get();
        $names = Item::whereIn('code', $lines->pluck('article_code')->unique()->all())->pluck('name', 'code')->all();
        $inv = InvoiceLine::where('delivery_note_id', $bl->id)->value('invoice_id');
        $inv = $inv ? $this->scope(Invoice::query(), $c)->where('id', $inv)->first(['id', 'numdoc']) : null;
        return $this->view('portal.account_dn', $r, $c, ['bl' => $bl, 'lines' => $lines, 'names' => $names, 'inv' => $inv]);
    }
}
