<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réception {{ $reception->numdoc ?: $reception->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; margin: 18px; color: #111; }
        h1 { font-size: 20px; margin: 0 0 8px; color: #0d4fb3; }
        .meta { width: 100%; margin-bottom: 12px; }
        .meta td { padding: 2px 6px; vertical-align: top; }
        table.l { width: 100%; border-collapse: collapse; }
        table.l th, table.l td { border: 1px solid #cfd6df; padding: 5px 6px; }
        table.l th { background: #eef2f7; text-align: left; }
        .r { text-align: right; } .c { text-align: center; }
        .cancel { color: #b91c1c; font-weight: bold; }
        .sign td { height: 60px; border: 1px solid #cfd6df; width: 50%; vertical-align: top; padding: 6px; }
    </style>
</head>
<body>
    <h1>Bon de réception {{ $reception->numdoc ?: ('#' . $reception->id) }} @if($reception->status === 'annulée')<span class="cancel">— ANNULÉE</span>@endif</h1>
    <table class="meta">
        <tr>
            <td><b>Fournisseur :</b> {{ $supplier->name ?? 'Non défini' }} ({{ $supplier->code ?? '-' }})<br>
                <b>Adresse :</b> {{ $supplier->address_delivery ?? $supplier->address ?? '—' }}</td>
            <td><b>Date :</b> {{ \Carbon\Carbon::parse($reception->reception_date)->format('d/m/Y') }}<br>
                <b>Commande :</b> {{ optional($reception->purchaseOrder)->numdoc ?? 'Réception directe' }}<br>
                <b>N° BL fournisseur :</b> {{ $reception->supplier_ref ?: '—' }}<br>
                <b>Magasin :</b> {{ optional($reception->store)->name ?? '—' }}</td>
        </tr>
    </table>
    <table class="l">
        <thead><tr><th>Référence</th><th>Désignation</th><th class="c">Commandé</th><th class="c">Reçu</th><th class="r">PU HT</th><th class="c">Rem %</th><th class="r">Total HT</th></tr></thead>
        <tbody>
        @php($tot = 0)@php($units = 0)
        @foreach($reception->lines as $l)
            @php($tot += (float) $l->total_ligne_ht)@php($units += (int) $l->received_quantity)
            <tr>
                <td>{{ $l->article_code }}</td>
                <td>{{ optional($l->item)->name ?? '-' }}</td>
                <td class="c">{{ $l->orderLine ? (int) round((float) $l->orderLine->ordered_quantity) : 'hors cde' }}</td>
                <td class="c"><b>{{ (int) $l->received_quantity }}</b></td>
                <td class="r">{{ number_format((float) $l->unit_price_ht, 2, ',', ' ') }}</td>
                <td class="c">{{ (float) $l->remise }}</td>
                <td class="r">{{ number_format((float) $l->total_ligne_ht, 2, ',', ' ') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot><tr><td colspan="3" class="r"><b>Total unités</b></td><td class="c"><b>{{ $units }}</b></td><td colspan="2" class="r"><b>Total HT</b></td><td class="r"><b>{{ number_format($tot, 2, ',', ' ') }}</b></td></tr></tfoot>
    </table>
    @if($reception->notes)<p><b>Notes :</b> {!! nl2br(e($reception->notes)) !!}</p>@endif
    <table class="sign" style="width:100%;margin-top:18px;border-collapse:collapse"><tr><td>Réceptionné par :</td><td>Contrôle / observations :</td></tr></table>
</body>
</html>
