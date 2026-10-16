<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Commande #{{ $purchase->numdoc }}</title>
@include('pdf.partials.pa_style')
</head>
<body>
@php
    $lines = $purchase->lines;
    $fallback = (float) ($purchase->supplier->tvaGroup->rate ?? ($purchase->total_ht > 0 ? ($purchase->total_ttc / $purchase->total_ht - 1) * 100 : 0));
    $rows = $lines->map(function ($l) use ($fallback) {
        $ht = (float) $l->unit_price_ht * (float) $l->ordered_quantity * (1 - ((float) $l->remise / 100));
        $t = $l->getAttribute('tva'); $t = $t !== null ? (float) $t : $fallback;
        return ['ht' => $ht, 'ttc' => $ht * (1 + $t / 100), 'tva' => $t];
    });
@endphp
@php
    $fmt = fn($v) => number_format((float) $v, 2, ',', ' ');
    $vat = $rows->groupBy(fn($r) => number_format((float) $r['tva'], 2, '.', ''))->map(fn($g) => [
        'base' => round($g->sum('ht'), 2), 'tva' => round($g->sum('ttc') - $g->sum('ht'), 2),
    ])->sortKeys();
    $netHT = round($rows->sum('ht'), 2); $netTTC = round($rows->sum('ttc'), 2);
@endphp
@include('pdf.partials.pa_header', [
    'docTitle' => 'BON DE COMMANDE', 'docNo' => $purchase->numdoc, 'docDate' => $purchase->order_date, 'supplier' => $purchase->supplier,
    'meta' => [
        'Statut' => ucfirst((string) $purchase->status),
        'Réception' => match($purchase->reception_status ?? null) { 'recue' => 'Reçue', 'partielle' => 'Partielle', 'a_recevoir' => 'À recevoir', default => null },
    ],
])

<main>
    @if($purchase->notes)<p>Note : {{ $purchase->notes }}</p>@endif
    <table class="items-table">
        <thead><tr><th>Code</th><th>Désignation</th><th>Qté</th><th>PU HT</th><th>Remise</th><th>Total HT</th><th>Total TTC</th></tr></thead>
        <tbody>
        @foreach($lines as $i => $line)
            <tr>
                <td>{{ $line->article_code ?? '-' }}</td>
                <td>{{ $line->item->name ?? '-' }}</td>
                <td class="ctr">{{ rtrim(rtrim(number_format((float) $line->ordered_quantity, 2, ',', ''), '0'), ',') }}</td>
                <td class="num">{{ $fmt($line->unit_price_ht) }} €</td>
                <td class="ctr">{{ $fmt($line->remise ?? 0) }}%</td>
                <td class="num">{{ $fmt($rows[$i]['ht']) }} €</td>
                <td class="num">{{ $fmt($rows[$i]['ttc']) }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('pdf.partials.pa_totals', ['vat' => $vat, 'netHT' => $netHT, 'netTTC' => $netTTC])
</main>
@include('pdf.partials.pa_footer')
</body>
</html>
