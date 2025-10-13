<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->numdoc }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logo { max-width: 150px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .totals { margin-top: 20px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{ public_path($company->logo_path) }}" alt="Logo" class="logo">
            <h2>{{ $company->name }}</h2>
            <p>{{ $company->address }}</p>
            <p>Tél: {{ $company->phone }}</p>
            <p>Email: {{ $company->email }}</p>
            <p>Matricule Fiscal: {{ $company->matricule_fiscal }}</p>
        </div>
        <div>
            <h2>Facture N° {{ $invoice->numdoc }}</h2>
            <p>Date: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
            <p>Client: {{ $invoice->customer->name }}</p>
            <p>Adresse: {{ $invoice->customer->address ?? 'N/A' }}</p>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Article/Description</th>
                <th>Quantité</th>
                <th>Prix Unitaire HT</th>
                <th>Remise</th>
                <th>Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->lines as $line)
                <tr>
                    <td>{{ $line->item->name ?? $line->description ?? $line->article_code }}</td>
                    <td>{{ $line->quantity }}</td>
                    <td>{{ number_format($line->unit_price_ht, 2) }} TND</td>
                    <td>{{ $line->remise ?? 0 }}%</td>
                    <td>{{ number_format($line->total_ligne_ht, 2) }} TND</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="totals">
        <p>Total HT: {{ number_format($invoice->total_ht, 2) }} TND</p>
        <p>TVA ({{ $invoice->tva_rate }}%): {{ number_format($invoice->total_ttc - $invoice->total_ht, 2) }} TND</p>
        <p>Total TTC: {{ number_format($invoice->total_ttc, 2) }} TND</p>
    </div>
    <p><img src="{{ $barcode }}" alt="Barcode"></p>
</body>
</html>