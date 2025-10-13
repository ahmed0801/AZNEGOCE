<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Devis #{{ $entetedevis->id }}</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    font-size: 12px;
    margin: 150px 40px 100px 40px; /* espace pour header */
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
    height: 90px; /* ou plus selon ta hauteur */
    padding: 10px 40px; /* ajouter du padding pour que le texte ne colle pas */
    background-color: #f9f9f9;
    font-size: 10px;
    color: #555;
    border-top: 1px solid #ddd;
    line-height: 1.3;
    box-sizing: border-box; /* pour que padding soit inclus dans la hauteur */
    overflow: visible; /* autoriser le contenu à déborder si besoin */
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
    <h4>Devis N° : DEV-{{ $entetedevis->id }}</h4>
</header>

<footer>
    <hr>
    <p>
        <strong>TRUCK PARTS GROUP s.a.r.l</strong> &nbsp;&nbsp; | &nbsp;&nbsp; 28, Boulevard de l'Environnement, L'Ariana 2080 Tunis<br>
        MF : 1347574QBM000 &nbsp;&nbsp; | &nbsp;&nbsp; | &nbsp;&nbsp; SWIFT : BHBKTNTT &nbsp;&nbsp; | &nbsp;&nbsp; Tél. : 70 732 415 / 20 467 467<br>
        RIB : 9041017008642 &nbsp;&nbsp; | &nbsp;&nbsp; IBAN : TN59 1490 4904 1017 0086 4226 &nbsp;&nbsp; | &nbsp;&nbsp; Email : <strong>truckparts.ls@gmail.com</strong><br>
        
    </p>
</footer>

<main>
    <table class="info-table">
        <tr>
            <td><strong>Code client :</strong> {{ $entetedevis->user_id ?? '-' }}</td>
            <td><strong>Nom Client :</strong> {{ $entetedevis->CustomerName ?? '-' }}</td>

        </tr>
        <tr>
            <td><strong>Matricule fiscale :</strong> {{ $entetedevis->MatFiscale ?? '-' }}</td>
            <td><strong>Adresse :</strong> {{ $entetedevis->VATCode ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Date :</strong> {{ $entetedevis->created_at->format('d/m/Y H:i') }}</td>
            <td><strong>Ce devis est valable 30 jours à compter de la date d’émission</strong> </td>

        </tr>
    </table>

    <table>
        <thead>
            <tr>
            <th>Référence</th>
                <th>Désignation</th>
                <th>Prix U.HT</th>
                <th>Qté</th>
                <!-- <th>Remise</th> -->
                <th>Total HT</th>
                <th>Total TTC</th>
            </tr>
        </thead>
        <tbody>
            @php $totalHT = 0; @endphp
            @foreach ($details as $item)
                @php
                    $remise = $item->remise ?? 0;
                    $prixRemise = $item->price * (1 - $remise / 100);
                    $totalLigne = $prixRemise * $item->quantity;
                    $totalHT += $totalLigne;
                @endphp
                <tr>
                <td>{{ $item->item_reference }}</td>
                <td>{{ $item->item_name }}</td>
                    <td>{{ number_format($item->price, 3, ',', ' ') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <!-- <td>{{ $remise }}%</td> -->
                    <td>{{ number_format($totalLigne, 3, ',', ' ') }}</td>
                    <td><strong>{{ number_format($totalHT * 1.19, 3, ',', ' ') }} TND</strong></td>

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
                <td class="label"><strong>TVA (19%) :</strong></td>
                <td><strong>{{ number_format($totalHT * 0.19, 3, ',', ' ') }} TND</strong></td>
            </tr>
            <tr>
                <td class="label"><strong>Total TTC :</strong></td>
                <td><strong>{{ number_format($totalHT * 1.19, 3, ',', ' ') }} TND</strong></td>
            </tr>
        </table>
    </div>
</main>

</body>
</html>
