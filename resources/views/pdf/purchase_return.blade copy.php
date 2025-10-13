<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Retour d'achat #{{ $return->numdoc }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 150px 40px 100px 40px; /* espace pour header et footer */
            position: relative;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            text-align: center;
            border: 2px double #2c3e50; /* Joli cadre double pour un look professionnel */
            background-color: #f8f9fa; /* Fond gris clair pour démarquer */
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombre subtile pour un effet élégant */
            z-index: 1000;
        }

        header img {
            height: 65px;
            float: left;
            margin-left: 10px; /* Ajout d'une marge pour éviter que le logo ne touche le cadre */
        }

        header h1 {
            margin: 10px 0 5px 0; /* Ajuster les marges pour centrer verticalement */
            font-size: 24px;
            color: #2c3e50;
            font-weight: bold;
        }

        header h4 {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 90px;
            padding: 10px 40px;
            background-color: #f9f9f9;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #ddd;
            line-height: 1.3;
            box-sizing: border-box;
            overflow: visible;
            z-index: 1000;
        }

        footer hr {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .totals-box {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
            border: 1px solid #000;
            padding: 10px;
        }

        .totals-box table {
            border: none;
        }

        .totals-box td {
            border: none;
            padding: 5px 0;
            text-align: right;
        }

        .totals-box td.label {
            text-align: left;
        }

        .info-table td {
            text-align: left;
            padding: 4px;
        }

        .notes {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path($company->logo_path) }}" alt="Logo">
        <h1>{{ $company->name }}</h1>
        <h4>Retour d'achat N° : {{ $return->numdoc }}</h4>
    </header>

    <footer>
        <hr>
        <p>
            <strong>{{ $company->name }}</strong> | {{ $company->address }}<br>
            MF : {{ $company->matricule_fiscal }} | SWIFT : {{ $company->swift }} | Tél. : {{ $company->phone }}<br>
            RIB : {{ $company->rib }} | IBAN : {{ $company->iban }} | Email : <strong>{{ $company->email }}</strong>
        </p>
    </footer>

    <main>
        <table class="info-table">
            <tr>
                <td><strong>Fournisseur :</strong>{{ $return->supplier ? $return->supplier->name : '-' }}</td>
                <td><strong>Date Retour :</strong> {{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Numéro Fournisseur :</strong> {{ $return->purchaseOrder->supplier->code ?? '-' }}</td>
                <td><strong>Type :</strong> {{ ucfirst($return->type) }}</td>
            </tr>
            <tr>
                <td><strong>TVA Fournisseur :</strong> {{ $return->purchaseOrder->supplier->tvaGroup->rate ?? '-' }} %</td>
                <td><strong>Commande associée :

                </strong> 
                                    @if($return->purchaseOrder)
{{ $return->purchaseOrder->numdoc }}
 @else
                            Géneré automatiquement a partir d'un Avoir Achat
                        @endif 

    </td>
            </tr>
        </table>


        <table>
            <thead>
                <tr>
                    <th>Code Article</th>
                    <th>Désignation</th>
                    <th>Qté Retournée</th>
                    <th>PU HT</th>
                    <th>Remise (%)</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHT = 0;
                    $tvaRate = $return->purchaseOrder->supplier->tvaGroup->rate ?? ($return->total_ht > 0 ? ($return->total_ttc / $return->total_ht - 1) * 100 : 0);
                @endphp
                @foreach ($return->lines as $line)
                    @php
                        $totalLigne = $line->unit_price_ht * $line->returned_quantity * (1 - ($line->remise / 100));
                        $totalLigneTTC = $totalLigne * (1 + $tvaRate / 100);
                        $totalHT += $totalLigne;
                    @endphp
                    <tr>
                        <td>{{ $line->article_code }}</td>
                        <td>{{ $line->item->name ?? '-' }}</td>
                        <td>{{ $line->returned_quantity }}</td>
                        <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }}</td>
                        <td>{{ $line->remise }}%</td>
                        <td>{{ number_format($totalLigne, 2, ',', ' ') }}</td>
                        <td>{{ number_format($totalLigneTTC, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-box">
            <table>
                <tr>
                    <td class="label"><strong>Total HT :</strong></td>
                    <td><strong>{{ number_format($totalHT, 2, ',', ' ') }} €</strong></td>
                </tr>
                <tr>
                    <td class="label"><strong>TVA :</strong></td>
                    <td><strong>{{ number_format($totalHT * ($tvaRate / 100), 2, ',', ' ') }} € ({{ number_format($tvaRate, 2) }}%)</strong></td>
                </tr>
                <tr>
                    <td class="label"><strong>Total TTC :</strong></td>
                    <td><strong>{{ number_format($return->total_ttc, 2, ',', ' ') }} €</strong></td>
                </tr>
            </table>
        </div>

        @if($return->notes)
            <div class="notes">
                <strong>Notes :</strong>
                <p>{{ $return->notes }}</p>
            </div>
        @endif
    </main>
</body>
</html>