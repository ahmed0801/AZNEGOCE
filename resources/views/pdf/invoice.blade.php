<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture #{{ $invoice->numdoc }}</title>
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
    height: 110px; /* Logo encore plus grand */
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
    margin-top: 5px;
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
    margin-top: 5px;
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



/* === ENCAISSEMENTS === */
.enc-payment-table {
    font-size: 11px;
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
}
.enc-payment-table th {
    background-color: #e8f5e8;
    color: #1e7e34;
    font-weight: bold;
    padding: 5px;
    border: 1px solid #28a745;
    text-align: left;
}
.enc-payment-table td {
    padding: 4px;
    border: 1px solid #ddd;
}
.enc-payment-table .total-row {
    background-color: #f0fdf0;
    font-weight: bold;
}
.enc-payment-table .amount {
    text-align: right;
    color: #1e7e34;
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
                <h2>Facture N° {{ $invoice->numdoc }}</h2>
                <img src="{{ $barcode }}" alt="Code-barres">
                <div class="details">
                    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
                    <p><strong>Client :</strong> {{ $invoice->customer->name ?? '-' }}</p>
                    <!-- <p><strong>N° Client :</strong> {{ $invoice->numclient ?? '-' }}</p> -->
                    <p><strong>Véhicule :</strong> {{ $invoice->vehicle ? ($invoice->vehicle->license_plate . ' (' . $invoice->vehicle->brand_name . ' ' . $invoice->vehicle->model_name . ')') : '-' }}</p>
                        
                    @if($invoice->type === 'direct' && $invoice->deliveryNotes()->exists())
                                                   @php
        $firstDeliveryNote = $invoice->deliveryNotes->first();
    @endphp
    @if($firstDeliveryNote)
            <p><strong> Vendeur :</strong>   {{ $firstDeliveryNote->vendeur}}</p>

    @endif
    @endif

                </div>
            </td>
        </tr>
    </table>
</div>

<!-- === CONTENU PRINCIPAL === -->
<main>
                    @if($invoice->notes )<p> Note : {{ $invoice->notes ?? '-' }}</p> @endif
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

    <div class="totals-box">
        <table>
            <tr>
                <td class="label">Total HT :</td>
                <td class="amount">{{ number_format($invoice->total_ht, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">TVA {{ number_format($invoice->tva_rate, 2, ',', ' ') }}% :</td>
                <td class="amount">{{ number_format($invoice->total_ttc - $invoice->total_ht, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">Total TTC :</td>
                <td class="amount"><strong>{{ number_format($invoice->total_ttc, 2, ',', ' ') }} €</strong></td>
            </tr>
        </table>
    </div>

<!-- === STATUT PAIEMENT + ENCAISSEMENTS === -->
<div style="margin-top: 10px; border: 2px solid #28a745; border-radius: 15px; padding: 4px; background-color: #f8fff8;">
    <p style="margin: 0 0 8px 0; font-weight: bold; color: #1e7e34;">
        @if($invoice->paid)
            Payée
        @else
            Non payé : {{ number_format($invoice->getRemainingBalanceAttribute(), 2, ',', ' ') }} €
        @endif
    </p>

    @if($invoice->payments->count() > 0)
        <table style="width: 100%; font-size: 11px; border-collapse: collapse;">

            <tbody>
                @foreach($invoice->payments as $payment)
                    <tr>
                        <td style="padding: 4px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                        <td style="padding: 4px; border: 1px solid #ddd;">{{$payment->payment_mode }}</td>
                        <td style="padding: 4px; border: 1px solid #ddd; text-align: right; font-weight: bold; color: #1e7e34;">
                            {{ number_format(abs($payment->amount), 2, ',', ' ') }} €
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">{{ $payment->reference ?? '-' }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #f0fdf0;">
                    <td colspan="2" style="padding: 5px; text-align: right; font-weight: bold;">Total encaissé :</td>
                    <td style="padding: 5px; text-align: right; font-weight: bold; color: #1e7e34;">
                        {{ number_format($invoice->payments->sum('amount'), 2, ',', ' ') }} €
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="margin: 0; font-style: italic; color: #6c757d;">Aucun encaissement enregistré.</p>
    @endif
</div>






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
