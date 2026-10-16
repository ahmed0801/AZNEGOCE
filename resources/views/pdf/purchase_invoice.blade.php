<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture #{{ $invoice->numdoc }}</title>
@include('pdf.partials.pa_style')
</head>
<body>
@php
    $lines = $invoice->lines;
    $rows = $lines->map(fn($l) => ['ht' => (float) $l->total_ligne_ht, 'ttc' => (float) $l->prix_ttc, 'tva' => (float) $l->tva]);
@endphp
@php
    $fmt = fn($v) => number_format((float) $v, 2, ',', ' ');
    $vat = $rows->groupBy(fn($r) => number_format((float) $r['tva'], 2, '.', ''))->map(fn($g) => [
        'base' => round($g->sum('ht'), 2), 'tva' => round($g->sum('ttc') - $g->sum('ht'), 2),
    ])->sortKeys();
    $netHT = round($rows->sum('ht'), 2); $netTTC = round($rows->sum('ttc'), 2);
@endphp
@include('pdf.partials.pa_header', [
    'docTitle' => 'FACTURE', 'docNo' => $invoice->numdoc, 'docDate' => $invoice->invoice_date, 'supplier' => $invoice->supplier,
    'meta' => [
        'Réf. fournisseur' => $invoice->supplier_ref,
        'Échéance' => $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : null,
        'Statut' => ucfirst($invoice->status),
    ],
])

<main>
    @if($invoice->notes)<p>Note : {{ $invoice->notes }}</p>@endif
    @php
        $hasReturns = $lines->contains(fn($l) => $l->purchase_return_id || (float) $l->quantity < 0);
        $groups = $lines->groupBy(function ($l) {
            if ($l->purchase_return_id) return 'R|Retour N° ' . ($l->purchaseReturn->numdoc ?? '#'.$l->purchase_return_id) . ($l->purchaseReturn && $l->purchaseReturn->return_date ? ' — ' . \Carbon\Carbon::parse($l->purchaseReturn->return_date)->format('d/m/Y') : '');
            if ($l->reception_id) return 'B|Réception N° ' . ($l->reception->numdoc ?? '#'.$l->reception_id) . ($l->reception && $l->reception->reception_date ? ' — ' . \Carbon\Carbon::parse($l->reception->reception_date)->format('d/m/Y') : '');
            return 'D|Facture directe';
        });
        $grouped = $invoice->type === 'groupée';
    @endphp
    <table class="items-table">
        <thead><tr>
            <th>Code</th><th>Désignation</th><th>Qté</th><th>PU HT</th><th>Remise</th><th>TVA</th><th>Total HT</th><th>Total TTC</th>
        </tr></thead>
        <tbody>
        @foreach($groups as $key => $glines)
            @php $isRet = str_starts_with($key, 'R|'); $label = substr($key, 2); @endphp
            @if($grouped)
            <tr><td colspan="8" class="grp" style="background-color: {{ $isRet ? '#fff5f5' : '#f0f8ff' }}; color: {{ $isRet ? '#c62828' : '#1976d2' }}; border-left: 3px solid {{ $isRet ? '#e57373' : '#2196f3' }};">{{ $label }}</td></tr>
            @endif
            @foreach($glines as $line)
            <tr @if($isRet) style="background-color:#fffafa" @endif>
                <td>{{ $line->article_code ?? '-' }}</td>
                <td>@if($isRet)<strong class="badge-ret">RETOUR :</strong> @endif{{ $line->item->name ?? $line->description ?? '-' }}</td>
                <td class="ctr {{ $isRet ? 'badge-ret' : '' }}">{{ $isRet ? '-' . rtrim(rtrim(number_format(abs((float) $line->quantity), 2, ',', ''), '0'), ',') : rtrim(rtrim(number_format((float) $line->quantity, 2, ',', ''), '0'), ',') }}</td>
                <td class="num">{{ $fmt($line->unit_price_ht) }} €</td>
                <td class="ctr">{{ $fmt($line->remise ?? 0) }}%</td>
                <td class="ctr">{{ $fmt($line->tva) }}%</td>
                <td class="num" @if($isRet) style="color:#c62828" @endif>{{ $fmt($line->total_ligne_ht) }} €</td>
                <td class="num" @if($isRet) style="color:#c62828" @endif>{{ $fmt($line->prix_ttc) }} €</td>
            </tr>
            @endforeach
            @if($grouped)
            <tr style="font-weight:bold;background-color:#f8f9fa"><td colspan="6" class="num" style="border-top:2px solid #ddd">Total {{ $isRet ? 'retour' : 'réception' }} TTC :</td><td colspan="2" class="num" style="border-top:2px solid #ddd;color:#1976d2">{{ $fmt($glines->sum('prix_ttc')) }} €</td></tr>
            <tr><td colspan="8" style="height:6px;border:none;background:transparent"></td></tr>
            @endif
        @endforeach
        @if($lines->isEmpty())<tr><td colspan="8" class="ctr">Aucune ligne</td></tr>@endif
        </tbody>
    </table>

    @if($hasReturns)
        <p style="font-size:9px;color:#a94442;margin-top:3px">Les lignes de retour fournisseur sont déduites de la facture (montants négatifs).</p>
    @endif

    @include('pdf.partials.pa_totals', ['vat' => $vat, 'netHT' => $netHT, 'netTTC' => $netTTC, 'labelHT' => $hasReturns ? 'Net HT' : 'Total HT', 'labelTTC' => $hasReturns ? 'Net à payer TTC' : 'Total TTC'])

    {{-- Règlements --}}
    <div style="margin-top:10px; border:2px solid #28a745; border-radius:15px; padding:4px; background-color:#f8fff8;">
        @if($invoice->payments->count() > 0)
        <table style="width:100%; font-size:11px; border-collapse:collapse;">
            <tbody>
            @foreach($invoice->payments as $payment)
                <tr>
                    <td style="padding:4px;border:1px solid #ddd">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                    <td style="padding:4px;border:1px solid #ddd">{{ $payment->payment_mode }}</td>
                    <td style="padding:4px;border:1px solid #ddd;text-align:right;font-weight:bold;color:#1e7e34">{{ $fmt(abs($payment->amount)) }} €</td>
                    <td style="padding:4px;border:1px solid #ddd">{{ $payment->reference ?? '-' }}</td>
                </tr>
            @endforeach
            <tr style="background-color:#f0fdf0">
                <td style="padding:5px;text-align:right;font-weight:bold;color:#1e7e34">{{ $invoice->paid ? 'Payée' : 'Reste à payer : ' . $fmt($invoice->getRemainingBalanceAttribute()) . ' €' }}</td>
                <td style="padding:5px;text-align:right;font-weight:bold">Total réglé :</td>
                <td style="padding:5px;text-align:right;font-weight:bold;color:#1e7e34">{{ $fmt($invoice->payments->sum('amount')) }} €</td>
                <td></td>
            </tr>
            </tbody>
        </table>
        @else
            <p style="margin:0;font-style:italic;color:#6c757d">Aucun règlement enregistré.</p>
        @endif
    </div>
</main>
@include('pdf.partials.pa_footer')
</body>
</html>
