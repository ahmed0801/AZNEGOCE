<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Commande #{{ $order->numdoc }}</title>
<style>
@page { margin: 10mm 10mm; }

body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    color: #2c3e50;
    font-size: 12px;
    margin: 0;
    padding: 0;
}

/* === HEADER AVEC CADRE === */
.header-box {
    border: 2px solid #007bff;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 15px;
    background-color: #f8fbff;
}

.header-table {
    width: 100%;
    border-collapse: collapse;
}
.header-table td {
    vertical-align: top;
    border: none;
    padding: 0 5px;
}
.header-left {
    width: 50%;
}
.header-left img {
    height: 100px;
}
.header-left p {
    margin: 3px 0 0 5px;
    font-size: 11px;
    color: #555;
}
.header-left .address {
    margin: 3px 0 0 5px;
    font-size: 11px;
    color: #555;
    white-space: pre-line;
    word-wrap: break-word;
    max-width: 250px;
    line-height: 1.3;
}

.header-right {
    width: 50%;
    text-align: right;
}
.header-right h2 {
    margin: 0;
    font-size: 16px;
    color: #0056b3;
    text-transform: uppercase;
    font-weight: bold;
}
.header-right img {
    height: 25px;
    margin-top: 3px;
}
.header-right .details {
    margin-top: 6px;
    font-size: 12px;
    line-height: 1.6;
    text-align: right;
}
.header-right .details strong {
    color: #003f88;
    font-size: 12px;
}
.validite {
    margin-top: 5px;
    font-size: 10px;
    color: #555;
    font-style: italic;
}

/* === TABLES === */
table {
    width: 100%;
    border-collapse: collapse;
}
th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    padding: 6px;
    border: 1px solid #0056b3;
}
td {
    padding: 6px;
    border: 1px solid #ddd;
}
.items-table tr:nth-child(even) { background-color: #f9f9f9; }

/* === TOTALS === */
.totals-box {
    margin-top: 10px;
    width: 280px;
    margin-left: auto;
    border: 2px solid #0056b3;
    border-radius: 6px;
    padding: 6px 10px;
    background-color: #f8fbff;
}
.totals-box td {
    border: none;
    padding: 3px;
}
.totals-box td.label {
    text-align: left;
    font-weight: bold;
}
.totals-box td.amount {
    text-align: right;
}

/* === CONDITIONS === */
.conditions {
    margin-top: 15px;
    font-size: 9px;
    color: #333;
    border: 1px solid #007bff;
    border-radius: 6px;
    padding: 8px 10px;
    background: #f8fbff;
    page-break-inside: avoid;
}
.conditions h3 {
    text-align: center;
    color: #0056b3;
    font-size: 11px;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.conditions ul {
    margin: 0;
    padding-left: 15px;
}
.conditions li {
    margin-bottom: 3px;
    line-height: 1.3;
}
.conditions li ul {
    margin-top: 2px;
    padding-left: 15px;
    list-style-type: circle;
}

/* === FOOTER === */
footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #f8f9fa;
    color: #333;
    border-top: 2px solid #007bff;
    font-size: 10px;
    text-align: center;
    padding: 6px 15px;
}
footer p { margin: 2px 0; }
footer .hours {
    color: #0056b3;
    font-size: 10px;
    margin-top: 1px;
}
</style>
</head>

<body>

<!-- === HEADER === -->
<div class="header-box">
    <table class="header-table">
        <tr>
            <td class="header-left">
                <img src="{{ public_path($company->logo_path) }}" alt="Logo">
                <p class="address">{{ $company->address }}</p>
                <p>Tél : {{ $company->phone ?? '-' }}</p>
                <p>Email : {{ $company->email ?? '-' }}</p>
            </td>

            <td class="header-right">
                <h2>@if($order->status == 'Devis') Devis N° @else Commande N° @endif {{ $order->numdoc }}</h2>
                <img src="{{ $barcode }}" alt="Code-barres">
                <div class="details">
                    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</p>
                    <p><strong>Client :</strong> {{ $order->customer->name ?? '-' }}</p>
                    <!-- <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p> -->
                </div>
                <p class="validite">Validité de l’offre : <strong>30 jours hors promotion</strong></p>
            </td>
        </tr>
    </table>
</div>

<!-- === CONTENU PRINCIPAL === -->
<main>

                    @if($order->notes )<p> Note : {{ $order->notes ?? '-' }}</p> @endif

    <table class="items-table">
        <thead>
            <tr>
                <th>Code Article</th>
                <th>Désignation</th>
                <th>Qté</th>
                <th>PU HT</th>
                <th>Remise</th>
                <th>Total HT</th>
                <th>Total TTC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHT = 0;
                $tvaRate = $order->customer->tvaGroup->rate ?? ($order->total_ht > 0 ? ($order->total_ttc / $order->total_ht - 1) * 100 : 0);
            @endphp
            @foreach ($order->lines as $line)
                @php
                    $totalLigne = $line->unit_price_ht * $line->ordered_quantity * (1 - ($line->remise / 100));
                    $totalLigneTTC = $totalLigne * (1 + $tvaRate / 100);
                    $totalHT += $totalLigne;
                @endphp
                <tr>
                    <td>{{ $line->article_code ?? '-' }}</td>
                    <td>{{ $line->item->name ?? '-' }}</td>
                    <td>{{ $line->ordered_quantity }}</td>
                    <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                    <td>{{ $line->remise }}%</td>
                    <td>{{ number_format($totalLigne, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($totalLigneTTC, 2, ',', ' ') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-box">
        <table>
            <tr>
                <td class="label">Total HT :</td>
                <td class="amount">{{ number_format($totalHT, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">TVA {{ number_format($tvaRate, 2, ',', ' ') }}% :</td>
                <td class="amount">{{ number_format($totalHT * ($tvaRate / 100), 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">Total TTC :</td>
                <td class="amount"><strong>{{ number_format($order->total_ttc, 2, ',', ' ') }} €</strong></td>
            </tr>
        </table>
    </div>

    <!-- === CONDITIONS === -->
    <div class="conditions">
        <h3>Conditions Générales de Vente</h3>
        <ul>
            <li>En cas de désistement, aucun remboursement ne sera effectué — seul un avoir pourra être proposé.</li>
            <li>Aucun retour ne sera accepté après <strong>15 jours</strong>.</li>
            <li>Tout retour sera refusé si :
                <ul>
                    <li>l’emballage d’origine est détérioré, marqué ou scotché ;</li>
                    <li>le produit présente des traces de montage ;</li>
                    <li>les pièces ne correspondent pas à la référence d’origine ;</li>
                    <li>des pièces sont manquantes dans l’emballage.</li>
                </ul>
            </li>
            <li>Pour un retour ou une garantie, <strong>la facture est obligatoire</strong>.</li>
            <li>Les pièces électriques ne sont <strong>ni reprises, ni échangées</strong>.</li>
            <li>Le traitement des garanties fournisseurs peut nécessiter <strong>2 à 3 mois</strong>.</li>
            <li>Articles en échange standard :
                <ul>
                    <li>la consigne doit être retournée dans la boîte d’origine ;</li>
                    <li>elle ne doit présenter aucun dommage physique (cassures, fissures, etc.) ;</li>
                    <li>elle doit être <strong>identique à la pièce commandée</strong> pour remboursement.</li>
                </ul>
            </li>
            <li>Les pièces avec un délai ≥ 24h ne sont ni reprises ni échangées, sauf en cas de dysfonctionnement.</li>
            <li>Les commandes sont disponibles <strong>7 jours</strong> au magasin avant retour fournisseur.</li>
            <li><strong style="color:#c0392b;">Aucune pièce ne sera servie sans présentation de la facture.</strong></li>
        </ul>
    </div>
</main>

<!-- === FOOTER === -->
<footer>
    <p><strong>{{ $company->name }}</strong> | Tél : {{ $company->phone ?? '-' }} | Email : {{ $company->email ?? '-' }}</p>
    <p>SIRET : {{ $company->matricule_fiscal }}</p>
    <p class="hours">🕒 Horaires : Lundi à Samedi de 9h à 19h — Fermé le Vendredi de 12h30 à 15h</p>
</footer>

</body>
</html>
