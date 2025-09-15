<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Règlements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .company-info, .filters {
            margin-bottom: 20px;
            font-size: 11px;
        }
        .company-info p, .filters p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        td.text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Rapport des Règlements</h1>

    <div class="company-info">
        <p><strong>{{ $company->name }}</strong></p>
        <p>{{ $company->address }}</p>
        <p>Téléphone: {{ $company->phone }}</p>
        <p>Email: {{ $company->email }}</p>
    </div>

    <div class="filters">
        <p><strong>Filtres Appliqués:</strong></p>
        <p>Date de début: {{ $request->date_from ? \Carbon\Carbon::parse($request->date_from)->format('d/m/Y') : 'N/A' }}</p>
        <p>Date de fin: {{ $request->date_to ? \Carbon\Carbon::parse($request->date_to)->format('d/m/Y') : 'N/A' }}</p>
        <p>Client: {{ $request->customer_id ? \App\Models\Customer::find($request->customer_id)->name ?? 'N/A' : 'Tous' }}</p>
        <p>Fournisseur: {{ $request->supplier_id ? \App\Models\Supplier::find($request->supplier_id)->name ?? 'N/A' : 'Tous' }}</p>
        <p>Mode de paiement: {{ $request->payment_mode ?: 'Tous' }}</p>
        <p>Code lettrage: {{ $request->lettrage_code ?: 'Tous' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date de Paiement</th>
                <th>Client/Fournisseur</th>
                <th>Facture</th>
                <th>Mode de Paiement</th>
                <th class="text-right">Montant (€)</th>
                <th>Code Lettrage</th>
                <th>Référence</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                    <td>
                        @if($payment->customer)
                            {{ $payment->customer->name }} (Client)
                        @elseif($payment->supplier)
                            {{ $payment->supplier->name }} (Fournisseur)
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($payment->payable_type === 'App\\Models\\Invoice')
                            Facture Vente: {{ $payment->payable->numdoc ?? 'N/A' }}
                        @elseif($payment->payable_type === 'App\\Models\\PurchaseInvoice')
                            Facture Achat: {{ $payment->payable->numdoc ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $payment->payment_mode }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 2, ',', ' ') }}</td>
                    <td>{{ $payment->lettrage_code ?? '-' }}</td>
                    <td>{{ $payment->reference ?? '-' }}</td>
                    <td>{{ $payment->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p>© {{ $company->name }}. Tous droits réservés.</p>
    </div>
</body>
</html>