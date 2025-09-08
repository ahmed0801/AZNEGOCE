<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réception #{{ $reception->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
        .header p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réception #{{ $reception->id }}</h1>
        <p><strong>Commande :</strong> {{ $reception->purchaseOrder->numdoc }}</p>
        <p><strong>Date :</strong> {{ $date }}</p>
        <p><strong>Fournisseur :</strong> {{ $supplier->name ?? 'Non défini' }} ({{ $supplier->code ?? '-' }})</p>
        <p><strong>Adresse :</strong> {{ $supplier->address_delivery ?? $supplier->address ?? 'Inconnue' }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Code Article</th>
                <th>Désignation</th>
                <th>Quantité Commandée</th>
                <th>Quantité Reçue</th>
                <th>Quantité Scannée</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reception->lines as $line)
                <tr>
                    <td>{{ $line->article_code }}</td>
                    <td>{{ $line->item->name ?? '-' }}</td>
                    <td>{{ optional($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code))->ordered_quantity ?? '-' }}</td>
                    <td>{{ $line->received_quantity }}</td>
                    <td>{{ $line->scanned_quantity ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>