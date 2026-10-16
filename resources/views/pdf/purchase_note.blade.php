<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Avoir #{{ $note->numdoc }}</title>
@include('pdf.partials.pa_style')
</head>
<body>
@php
    $lines = $note->lines;
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
    'docTitle' => 'AVOIR', 'docNo' => $note->numdoc, 'docDate' => $note->note_date, 'supplier' => $note->supplier,
    'meta' => [
        'Origine' => $note->purchaseInvoice ? 'Facture ' . $note->purchaseInvoice->numdoc : ($note->purchaseReturn ? 'Retour ' . $note->purchaseReturn->numdoc : ($note->type === 'from_return' ? 'Retours fournisseur' : 'Factures fournisseur')),
        'Statut' => ucfirst($note->status),
    ],
])

<main>
    @if($note->notes)<p>Note : {{ $note->notes }}</p>@endif
    <table class="items-table">
        <thead><tr><th>Code</th><th>Désignation</th><th>Qté</th><th>PU HT</th><th>Remise</th><th>TVA</th><th>Total HT</th><th>Total TTC</th></tr></thead>
        <tbody>
        @foreach($lines as $line)
            <tr>
                <td>{{ $line->article_code ?? '-' }}</td>
                <td>{{ $line->item->name ?? $line->description ?? '-' }}</td>
                <td class="ctr">{{ rtrim(rtrim(number_format((float) $line->quantity, 2, ',', ''), '0'), ',') }}</td>
                <td class="num">{{ $fmt($line->unit_price_ht) }} €</td>
                <td class="ctr">{{ $fmt($line->remise ?? 0) }}%</td>
                <td class="ctr">{{ $fmt($line->tva) }}%</td>
                <td class="num">{{ $fmt($line->total_ligne_ht) }} €</td>
                <td class="num">{{ $fmt($line->prix_ttc) }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('pdf.partials.pa_totals', ['vat' => $vat, 'netHT' => $netHT, 'netTTC' => $netTTC, 'labelHT' => 'Total HT à nous créditer', 'labelTTC' => 'Total TTC'])
    <p style="font-size:9px;color:#555;margin-top:6px;text-align:center;font-style:italic">Cet avoir vient en déduction du solde dû au fournisseur.</p>
</main>
@include('pdf.partials.pa_footer')
</body>
</html>
