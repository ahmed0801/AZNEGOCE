<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture #{{ $invoice->numdoc }}</title>
<style>
@page { 
    margin: 8mm 10mm 4mm 10mm; /* on réduit un peu le haut et le bas */
}
body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    color: #2c3e50;
    font-size: 10px;
    margin: 0;
    padding: 0;
}

/* === HEADER AVEC CADRE === */
.header-box {
    border: 2px solid #007bff;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 6px;
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
    line-height: 1.1;
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

.no-print {
    display: none !important;
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
    margin-top: 4px;
    width: 280px;
    margin-left: auto;
    border: 2px solid #0056b3;
    border-radius: 6px;
    padding: 6px 10px;
    background-color: #f8fbff;
}
.totals-box td {
    border: none;
    padding: 2px;
}
.totals-box td.label {
    text-align: left;
    font-weight: bold;
}
.totals-box td.amount {
    text-align: right;
}

/* === CONDITIONS === */
/* === CONDITIONS DE VENTE EN 2 COLONNES === */
/* === CONDITIONS DE VENTE EN 2 COLONNES (compatible DomPDF) === */
.conditions {
    margin-top: 3px;
    font-size: 8px;
    line-height: 1.3;
    color: #333;
    border: 1.5px solid #007bff;
    border-radius: 8px;
    padding: 10px;
    background: #f8fbff;
    page-break-inside: avoid;
    overflow: hidden;
}

.conditions h3 {
    text-align: center;
    color: #0056b3;
    font-size: 11px;
    margin: 0 0 2px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.columns {
    display: table;
    width: 100%;
    table-layout: fixed;
}

.col {
    display: table-cell;
    width: 50%;
    padding: 0 10px;
    vertical-align: top;
}

.col:first-child {
    padding-left: 0;
    border-right: 1px dotted #007bff;
}

.col:last-child {
    padding-right: 0;
}

.conditions ul {
    margin: 0;
    padding-left: 16px;
    list-style-type: disc;
}

.conditions li {
    margin-bottom: 4px;
}

.conditions li ul {
    margin: 3px 0 0 0;
    padding-left: 14px;
    list-style-type: circle;
}



/* === ENCAISSEMENTS === */
.enc-payment-table {
    font-size: 10px;
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
<div class="header-box" style="padding:0; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; border:none;">
        <tr>
            {{-- ── COLONNE 1 : Logo + Coordonnées société ── --}}
            <td style="width:33%; vertical-align:top; padding:10px 12px;
                        border-right:2px solid #007bff;">
                <img src="{{ public_path($company->logo_path) }}" alt="Logo" style="height:80px;">
                <div style="margin-top:5px; font-size:9px; color:#555; line-height:1.4;">
                    {{ $company->address }}
                </div>
                <div style="font-size:9px; color:#555; margin-top:2px;">
                    Tél : <img src="{{ public_path('assets/img/whatsapp.png') }}"
                          style="height:11px; vertical-align:middle; margin-right:1px;">
                    {{ $company->phone ?? '-' }}
                </div>
                <div style="font-size:9px; color:#555;">
                    {{ $company->email ?? '-' }}
                </div>
            </td>

            {{-- ── COLONNE 2 : Coordonnées CLIENT centrées ── --}}
            <td style="width:34%; vertical-align:middle; padding:10px 12px;
                        border-right:2px solid #007bff; text-align:center;">
                <div style="font-size:7px; font-weight:bold; text-transform:uppercase;
                            color:#007bff; letter-spacing:1px; margin-bottom:5px;">
                    CLIENT
                </div>
                <div style="font-size:13px; font-weight:bold; color:#003f88;">
                    {{ $invoice->customer->name ?? '-' }}
                </div>
                @if($invoice->customer->address)
                <div style="font-size:9px; color:#555; margin-top:3px; line-height:1.4;">
                    {{ $invoice->customer->address }}
                </div>
                @endif
                @if(($invoice->customer->address_delivery ?? null) || ($invoice->customer->city ?? null))
<div style="font-size:9px; color:#555;">
    {{ $invoice->customer->address_delivery ?? '' }} {{ $invoice->customer->city ?? '' }}
</div>
@endif
                @if($invoice->vehicle)
                <div style="font-size:9px; color:#444; margin-top:4px;
                            background:#eef4ff; border-radius:4px; padding:2px 6px;
                            display:inline-block;">
                    {{ $invoice->vehicle->license_plate }}
                    ({{ $invoice->vehicle->brand_name }} {{ $invoice->vehicle->model_name }})
                </div>
                @endif
            </td>

            {{-- ── COLONNE 3 : Facture + Code-barres + Date + Vendeur ── --}}
            <td style="width:33%; vertical-align:top; padding:10px 12px; text-align:right;">
                <div style="font-size:15px; font-weight:bold; color:#0056b3;
            text-transform:uppercase; margin-bottom:3px; text-align:right;">
    FACTURE N°<br>{{ $invoice->numdoc }}
</div>
                <img src="{{ $barcode }}" alt="Code-barres" style="height:25px; margin-bottom:4px;">
                <div style="font-size:10px; color:#333; margin-top:3px;">
                    <strong>Date :</strong>
                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                </div>
                @if($invoice->due_date && $invoice->due_date != $invoice->invoice_date)
                <div style="font-size:9px; color:#888;">
                    Échéance : {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                </div>
                @endif
                @if($invoice->type === 'direct' && $invoice->deliveryNotes()->exists())
                    @php $firstDeliveryNote = $invoice->deliveryNotes->first(); @endphp
                    @if($firstDeliveryNote)
                    <div style="font-size:9px; color:#333; margin-top:6px; text-align:right;">
    Vendeur : <strong>{{ $firstDeliveryNote->vendeur }}</strong>
</div>
                    @endif
                @endif
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
                <th style="width:12px; padding:2px;"></th>
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





            @php
                $groupedLines = $invoice->lines->groupBy(function ($line) {
                    if ($line->sales_return_id) {
                        $return = \App\Models\SalesReturn::find($line->sales_return_id);
                        $vehicle = $return && $return->vehicle ? ' — ' . $return->vehicle->license_plate . ' (' . $return->vehicle->brand_name . ' ' . $return->vehicle->model_name . ')' : '';
                        
                        
            $notes = $return && $return->notes && trim($return->notes) !== '' 
                ? '<br><span style="font-style: italic; color: #555;">Note : ' . e($return->notes) . '</span>' 
                : '';


                        return 'Retour N° ' . ($return ? $return->numdoc : 'Inconnu') .
                               ($return && $return->return_date ? ' — ' . \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') : '') .
                               $vehicle . $notes;
                    } elseif ($line->delivery_note_id) {
                        $dn = \App\Models\DeliveryNote::find($line->delivery_note_id);
                        $vehicle = $dn && $dn->vehicle ? ' — ' . $dn->vehicle->license_plate . ' (' . $dn->vehicle->brand_name . ' ' . $dn->vehicle->model_name . ')' : '';

            $notes = $dn && $dn->notes && trim($dn->notes) !== '' 
                ? '<br><span style="font-style: italic; color: #555;">Note : ' . e($dn->notes) . '</span>' 
                : '';


                        return 'Bon de Livraison N° ' . ($dn ? $dn->numdoc : 'Inconnu') .
                               ($dn && $dn->delivery_date ? ' — ' . \Carbon\Carbon::parse($dn->delivery_date)->format('d/m/Y') : '') .
                               $vehicle . $notes;
                    } else {
                        return 'Facture directe';
                    }
                });
            @endphp

            @foreach($groupedLines as $header => $lines)
                <!-- Entête discrète avec véhicule si présent -->

                                                                    @if($invoice->type =="groupée")

                <tr>
                    <td colspan="7" style="
                        background-color: {{ str_starts_with($header, 'Retour') ? '#fff5f5' : '#f0f8ff' }};
                        color: {{ str_starts_with($header, 'Retour') ? '#c62828' : '#1976d2' }};
                        font-weight: 600;
                        font-size: 10px;
                        text-align: left;
                        padding: 4px 8px;
                        border-left: 3px solid {{ str_starts_with($header, 'Retour') ? '#e57373' : '#2196f3' }};
                        border-bottom: 1px solid #ddd;
                    ">
                       {!! $header !!}
                      
                    </td>
                </tr>
 @endif



                <!-- Lignes du groupe -->
                @foreach($lines as $line)
                    <tr style="background-color: {{ str_starts_with($header, 'Retour') ? '#fffafa' : 'inherit' }};">
                        <td style="width:12px; text-align:center; padding:2px;">
                            <span style="display:inline-block;width:9px;height:9px;
                                         border:1px solid #555;border-radius:1px;"></span>
                        </td>
                        <td>{{ $line->article_code ?? '-' }}</td>
                        <td>
                            @if(str_starts_with($header, 'Retour'))
                                <strong style="color: #c62828; font-size: 9px;">RETOUR :</strong>
                            @endif
                            {{ $line->item->name ?? $line->description ?? '-' }}
                        </td>
                        <td style="{{ str_starts_with($header, 'Retour') ? 'color: #c62828; font-weight: bold;' : '' }}">
                            @if(str_starts_with($header, 'Retour'))
                                -{{ number_format(abs($line->quantity ?? 0), 0, ',', ' ') }}
                            @else
                                {{ number_format($line->quantity ?? 0, 0, ',', ' ') }}
                            @endif
                        </td>
                        <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($line->remise ?? 0, 2, ',', ' ') }}%</td>
                        <td style="{{ str_starts_with($header, 'Retour') ? 'color: #c62828;' : '' }}">
                            {{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €
                        </td>
                        <td style="{{ str_starts_with($header, 'Retour') ? 'color: #c62828;' : '' }}">
                            {{ number_format($line->total_ligne_ttc, 2, ',', ' ') }} €
                        </td>
                    </tr>
                @endforeach

                <!-- ──────────────────────────────────────────────── -->
    <!-- AJOUT DU TOTAL PAR GROUPE (seulement si groupée) -->
    <!-- ──────────────────────────────────────────────── -->
    @if($invoice->type === "groupée")
        @php
            // Calcul du total TTC pour ce groupe uniquement
            $groupTotalTTC = $lines->sum('total_ligne_ttc');
        @endphp

        <tr style="font-weight: bold; background-color: #f8f9fa;">
            <td colspan="5" style="text-align: right; padding: 8px; border-top: 2px solid #ddd;">
                Total {{ str_starts_with($header, 'Retour') ? 'retour' : 'bon de livraison' }} TTC :
            </td>
            <td colspan="2" style="text-align: right; padding: 8px; border-top: 2px solid #ddd; color: #1976d2;">
                {{ number_format($groupTotalTTC, 2, ',', ' ') }} €
            </td>
        </tr>

        <!-- Optionnel : petite ligne de séparation visuelle -->
        <tr>
            <td colspan="7" style="height: 8px; border: none; background: transparent;"></td>
        </tr>
    @endif
    
            @endforeach

            @if($invoice->lines->isEmpty())
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucune ligne</td>
                </tr>
            @endif
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

<td style="padding: 5px; text-align: right; font-weight: bold; color: #1e7e34;">
                @if($invoice->paid)
            Payée
        @else
            Non payé : {{ number_format($invoice->getRemainingBalanceAttribute(), 2, ',', ' ') }} €
        @endif

</td>
                    <td style="padding: 5px; text-align: right; font-weight: bold;">Total encaissé :</td>
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






   <!-- Remplace tout ton bloc .conditions actuel par ÇA : -->
<div class="conditions">
    <h3>Conditions Générales de Vente</h3>
    
    <div class="columns">
        <div class="col">
            <ul>
                <li>En cas de désistement, aucun remboursement ne sera effectué — seul un avoir pourra être proposé.</li>
                <li>Aucun retour ne sera accepté après <strong>15 jours</strong>.</li>
                <li>Tout retour sera refusé si :
                    <ul>
                        <li>l’emballage d’origine est détérioré, marqué ou scotché / le produit présente des traces de montage.</li>
                        <li>les pièces ne correspondent pas à la référence d’origine / des pièces sont manquantes dans l’emballage.</li>
                    </ul>
                </li>
                <li>Pour un retour ou une garantie, <strong>la facture est obligatoire</strong>.</li>
                <li>Les pièces électriques ne sont <strong>ni reprises, ni échangées</strong>.</li>
            </ul>
        </div>
        
        <div class="col">
            <ul>
                <li>Le traitement des garanties fournisseurs peut nécessiter <strong>2 à 3 mois</strong>.</li>
                <li>Articles en échange standard :
                    <ul>
                        <li>la consigne doit être retournée dans la boîte d’origine / elle ne doit présenter aucun dommage physique (cassures, fissures, etc.)</li>
                        <li>elle doit être <strong>identique à la pièce commandée</strong> pour remboursement.</li>
                    </ul>
                </li>
                <li>Les pièces avec un délai ≥ 24h ne sont ni reprises ni échangées, sauf en cas de dysfonctionnement.</li>
                <li>Les commandes sont disponibles <strong>7 jours</strong> au magasin avant retour fournisseur.</li>
                <li style="color:#c0392b; font-weight:bold;">Aucune pièce ne sera servie sans présentation de la facture.</li>
            </ul>
        </div>
    </div>
</div>
</main>

<!-- === FOOTER === -->
<footer class="{{ $invoice->type === 'groupée' ? 'no-print' : '' }}">
        <p><strong>{{ $company->name }}</strong> | Tél : <img src="{{ public_path('assets/img/whatsapp.png') }}"
         style="height: 14px; vertical-align: middle; margin-right: 1px;"> {{ $company->phone ?? '-' }} | Email : {{ $company->email ?? '-' }}</p>
    <p>SIRET : {{ $company->matricule_fiscal }}</p>
    <p class="hours">🕒 Horaires : Lundi à Samedi de 9h à 19h — Fermé le Vendredi de 12h30 à 15h</p>
</footer>

</body>
</html>