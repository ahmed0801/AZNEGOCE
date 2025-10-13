<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avoir {{ $NumAvoir }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { text-align: center; padding: 20px; }
        .header img { height: 80px; }
        .company-name { font-size: 24px; font-weight: bold; margin-top: 10px; }
        .customer-info { margin-top: 20px; text-align: left; }
        .customer-info th, .customer-info td { padding: 8px; }
        .total-section { margin-top: 20px; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>

    <!-- Header with Company Name and Logo -->
    <div class="header">
        <img src="assets/img/logop.png" alt="PREMAGROS Logo" class="navbar-brand">
        <div class="company-name">DUPLICATA</div>
    </div>

    <!-- Customer Information -->
    <div class="customer-info">
        <table>
            <tr>
                <th>Nom du Client</th>
                <td>{{ session('user')['CustomerName'] }}</td>
            </tr>
            <tr>
                <th>Numéro du Client</th>
                <td>{{ session('user')['CustomerNo'] }}</td>
            </tr>
        </table>
    </div>

    <h3>Duplicata de l'Avoir N° {{ $NumAvoir }}</h3>

    <!-- Credit Note Details Table -->
    <table>
        <thead>
            <tr>
                <th>Num Article</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total HT</th>
                <th>Total TTC</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($creditNoteDetails as $detail)
                <tr>
                    <td>{{ $detail['CodeArticle'] }}</td>
                    <td>{{ $detail['Description'] }}</td>
                    <td>{{ $detail['Quantite'] }}</td>
                    <td>{{ number_format(floatval(str_replace(',', '', $detail['PrixUnitaire'] ?? 0)), 3) }} TND</td>
                    <td>{{ number_format(floatval(str_replace(',', '', $detail['MontantHT'] ?? 0)), 3) }} TND</td>
                    <td>{{ number_format(floatval(str_replace(',', '', $detail['MontantTTC'] ?? 0)), 3) }} TND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <h4>Total Avoir : {{ number_format($totalAmount, 3) }} TND</h4>
    </div>

</body>
</html>
