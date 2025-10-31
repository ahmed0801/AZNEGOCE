<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FACT #{{ $NumFacture }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 150px 40px 100px 40px;
            position: relative;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            background-color: white;
            z-index: 1000;
        }

        header img {
            height: 65px;
            float: left;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
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
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('assets/img/logop.png') }}" alt="Logo">
    <h1>TRUCK PARTS GROUP</h1>
    <h4>Facture N° : {{ $NumFacture }}</h4>
</header>

<footer>
    <hr>
    <p>
        <strong>TRUCK PARTS GROUP s.a.r.l</strong> &nbsp;&nbsp; | &nbsp;&nbsp; 28, Boulevard de l'Environnement, L'Ariana 2080 Tunis<br>
        MF : 1347574QBM000 &nbsp;&nbsp; | &nbsp;&nbsp; SWIFT : BHBKTNTT &nbsp;&nbsp; | &nbsp;&nbsp; Tél. : 70 732 415 / 20 467 467<br>
        RIB : 9041017008642 &nbsp;&nbsp; | &nbsp;&nbsp; IBAN : TN59 1490 4904 1017 0086 4226 &nbsp;&nbsp; | &nbsp;&nbsp; Email : <strong>truckparts.ls@gmail.com</strong><br>
    </p>
</footer>

<main>
    <table class="info-table">
        <tr>
            <td><strong>Num. Document :</strong> {{ $NumFacture }}</td>
            <td><strong>Date :</strong> {{ $DateFacture }}</td>
        </tr>
        <tr>
            <td><strong>Code client :</strong> {{ $customerNo ?? '-' }}</td>
            <td><strong>Nom Client :</strong> {{ $customerName ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Matricule fiscale :</strong> {{ $MatFiscale ?? '-' }}</td>
            <td><strong>Adresse :</strong> {{ $VATCode ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Désignation</th>
                <th>Prix U.</th>
                <th>Qté</th>
                <th>Net HT</th>
                <th>Net TTC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHT = 0;
                $totalttc = 0;
            @endphp
            @foreach ($invoiceDetails as $item)
                @php
                    $montantHT = floatval(str_replace(',', '.', str_replace(' ', '', $item['MontantHT'])));
                    $montantTTC = floatval(str_replace(',', '.', str_replace(' ', '', $item['MontantTTC'])));
                    $prixUnitaire = floatval(str_replace(',', '.', str_replace(' ', '', $item['PrixUnitaire'])));
                    $totalHT += $montantHT;
                    $totalttc += $montantTTC;
                @endphp
                <tr>
                    <td>{{ $item['CodeArticle'] }}</td>
                    <td>{{ $item['Description'] }}</td>
                    <td>{{ number_format($prixUnitaire, 3, ',', ' ') }}</td>
                    <td>{{ $item['Quantite'] }}</td>
                    <td>{{ number_format($montantHT, 3, ',', ' ') }}</td>
                    <td>{{ number_format($montantTTC, 3, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-box">
        <table>
            <tr>
                <td class="label"><strong>Total HT :</strong></td>
                <td><strong>{{ number_format($totalHT, 3, ',', ' ') }} TND</strong></td>
            </tr>
            <tr>
                <td class="label"><strong>Total TVA :</strong></td>
                <td><strong>{{ number_format(($totalttc - $totalHT), 3, ',', ' ') }} TND</strong></td>
            </tr>
            <tr>
                <td class="label"><strong>Timbre :</strong></td>
                <td><strong>1 TND</strong></td>
            </tr>
            <tr>
                <td class="label"><strong>Total TTC :</strong></td>
                <td><strong>{{ number_format($totalttc + 1, 3, ',', ' ') }} TND</strong></td>
            </tr>
        </table>
    </div>
</main>

</body>
</html>
