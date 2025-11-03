<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture #{{ $invoice->numdoc }}</title>
<style>
@page { margin: 20mm 15mm; }
body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    color: #2c3e50;
    font-size: 12px;
    margin: 0;
    padding: 0;
}

/* === HEADER === */
header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 4px solid #004a99;
}
header img.logo {
    height: 70px;
}
header .info {
    text-align: right;
}
header h1 {
    font-size: 22px;
    margin-bottom: 5px;
    text-transform: uppercase;
}
header .subtitle {
    font-size: 13px;
    opacity: 0.9;
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
    padding: 8px 20px;
}
footer p { margin: 2px 0; }

/* === SECTIONS === */
.section {
    margin: 20px 0;
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

/* === INFO CLIENT / FACTURE === */
.info-table td {
    border: none;
    padding: 4px 6px;
}
.info-table tr:nth-child(odd) { background-color: #f6f8fb; }
.info-table strong { color: #0056b3; }

/* === TOTALS === */
.totals-box {
    margin-top: 15px;
    width: 280px;
    margin-left: auto;
    border: 2px solid #0056b3;
    border-radius: 6px;
    padding: 8px 10px;
    background-color: #f8fbff;
}
.totals-box table {
    width: 100%;
    border: none;
}
.totals-box td {
    border: none;
    padding: 4px 2px;
}
.totals-box td.label {
    text-align: left;
    font-weight: bold;
}
.totals-box td.amount {
    text-align: right;
}

/* === NOTES === */
.notes {
    margin-top: 15px;
    font-style: italic;
    color: #555;
    border-left: 3px solid #007bff;
    padding-left: 10px;
}
</style>
</head>

<body>
<header>
    <img src="{{ public_path($company->logo_path) }}" alt="Logo" class="logo">
    <h4>Facture N° : {{ $invoice->numdoc }}</h4>
    <img src="{{ $barcode }}" alt="Code-barres" class="barcode">
    <div class="triangle-top-right"></div>
    <div class="triangle-bottom-left"></div>
</header>

<style>
header {
    text-align: center;
    border: 3px double #007bff;
    background-color: #f8f9fa;
    padding: 10px 0 6px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
    height: auto;
}

header .logo {
    height: 70px;
    width: auto;
    display: block;
    margin: 0 auto 5px auto;
}

header h4 {
    margin: 4px 0 2px 0;
    font-size: 14px;
    color: #2c3e50;
    font-weight: bold;
}

header .barcode {
    height: 14px;
    margin-top: 3px;
}

header .triangle-top-right,
header .triangle-bottom-left {
    position: absolute;
    width: 0; height: 0; border-style: solid;
}

header .triangle-top-right {
    top: -1px; right: -1px;
    border-width: 0 15px 15px 0;
    border-color: transparent #007bff transparent transparent;
}

header .triangle-bottom-left {
    bottom: -1px; left: -1px;
    border-width: 15px 0 0 15px;
    border-color: transparent transparent transparent #007bff;
}
</style>


<main>
    <div class="section">
        <table class="info-table">
            <tr>
                <td><strong>Client :</strong> {{ $invoice->customer->name ?? '-' }}</td>
                <td><strong>N° Client :</strong> {{ $invoice->numclient ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Adresse :</strong> {{ $invoice->customer->address ?? '-' }}</td>
                <td><strong>Statut :</strong> {{ ucfirst($invoice->status) }}</td>
            </tr>
            <tr>
                <td><strong>Date Facture :</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                <td><strong>Véhicule :</strong> {{ $invoice->vehicle ? ($invoice->vehicle->license_plate . ' (' . $invoice->vehicle->brand_name . ' ' . $invoice->vehicle->model_name . ')') : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Désignation</th>
                    <th>Qté</th>
                    <th>PU HT</th>
                    <th>Remise (%)</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->lines as $line)
                <tr>
                    <td>{{ $line->article_code ?? '-' }}</td>
                    <td>{{ $line->item->name ?? $line->description ?? '-' }}</td>
                    <td>{{ number_format($line->quantity, 0, ',', ' ') }}</td>
                    <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($line->remise ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($line->total_ligne_ttc, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($invoice->notes)
    <div class="notes">
        <strong>Note :</strong> {{ $invoice->notes }}
    </div>
    @endif

    <div class="totals-box">
        <table>
            <tr>
                <td class="label">Total HT :</td>
                <td class="amount">{{ number_format($invoice->total_ht, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">TVA ({{ number_format($invoice->tva_rate, 2, ',', ' ') }}%) :</td>
                <td class="amount">{{ number_format($invoice->total_ttc - $invoice->total_ht, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">Total TTC :</td>
                <td class="amount"><strong>{{ number_format($invoice->total_ttc, 2, ',', ' ') }} €</strong></td>
            </tr>
        </table>
    </div>


    <div class="conditions">
    <h3>Conditions Générales de Vente</h3>
    <ul>
        <li>Aucun remboursement n’est possible après validation — seul un avoir peut être accordé.</li>
        <li>Les retours sont acceptés sous 15 jours maximum avec la facture originale.</li>
        <li>Pièces refusées si emballage abîmé, montage effectué ou référence erronée.</li>
        <li>Les pièces électriques ne sont ni reprises ni échangées.</li>
        <li>Le traitement des garanties peut nécessiter jusqu’à 3 mois selon les fournisseurs.</li>
        <li>Pour les échanges standards, la consigne doit être identique et non endommagée.</li>
        <li>Les commandes spéciales (≥ 24h de délai) ne sont pas reprises sauf défaut constaté.</li>
        <li>Les commandes sont conservées 7 jours maximum au magasin.</li>
        <li><strong style="color:#c0392b;">Aucune pièce ne sera servie sans présentation de la facture.</strong></li>
    </ul>
</div>

<style>
.conditions {
    margin-top: 20px;
    border: 1px solid #007bff;
    border-radius: 6px;
    padding: 8px 12px;
    background-color: #f9fbff;
    font-size: 10.5px;
    line-height: 1.4;
    color: #333;
}

.conditions h3 {
    text-align: center;
    font-size: 12px;
    color: #0056b3;
    text-transform: uppercase;
    margin-bottom: 5px;
    border-bottom: 1px solid #007bff;
    padding-bottom: 2px;
}

.conditions ul {
    margin: 0;
    padding-left: 15px;
}

.conditions li {
    margin-bottom: 2px;
}
</style>


</main>

<footer>
    <p><strong>{{ $company->name }}</strong> | {{ $company->address }}</p>
    <p>MF : {{ $company->matricule_fiscal }} | Tél : {{ $company->phone }} | Email : {{ $company->email }}</p>
    <p>RIB : {{ $company->rib ?? '-' }} | IBAN : {{ $company->iban ?? '-' }} | SWIFT : {{ $company->swift ?? '-' }}</p>
</footer>
</body>
</html>
