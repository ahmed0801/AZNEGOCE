<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $invoice->numdoc }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333333; margin: 40px 40px 60px 40px; line-height: 1.4; }
        @page { margin: 15mm; }
        header { position: fixed; top: 0; left: 0; right: 0; height: 110px; text-align: center; border: 4px double #007bff; background-color: #f8f9fa; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); z-index: 1000; position: relative; }
        header::before, header::after, header .triangle-top-right, header .triangle-bottom-left { content: ''; position: absolute; width: 0; height: 0; border-style: solid; z-index: 1001; }
        header::before { top: -2px; left: -2px; border-width: 25px 25px 0 0; border-color: #007bff transparent transparent transparent; }
        header::after { bottom: -2px; right: -2px; border-width: 0 0 25px 25px; border-color: transparent transparent #007bff transparent; }
        header .triangle-top-right { top: -2px; right: -2px; border-width: 0 15px 15px 0; border-color: transparent #007bff transparent transparent; }
        header .triangle-bottom-left { bottom: -2px; left: -2px; border-width: 15px 0 0 15px; border-color: transparent transparent transparent #007bff; }
        header img.logo { height: 85px; float: left; margin-left: 5px; }
        header h1 { margin: 20px 0 10px 0; font-size: 20px; color: #2c3e50; font-weight: bold; }
        header h4 { margin: 0; font-size: 14px; color: #555555; }
        header h4.barcode-container { margin-bottom: 10px; }
        header img.barcode { height: 15px; margin-top: 3px; margin-left: 80px; }
        footer { position: fixed; bottom: 0; left: 0; right: 0; height: 90px; padding: 10px 40px; background-color: #2c3e50; color: #ecf0f1; font-size: 10px; border-top: 2px double #007bff; line-height: 1.3; text-align: center; z-index: 1000; }
        footer hr { margin-bottom: 5px; border-color: #555555; }
        main { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #2c3e50; padding: 8px; text-align: center; }
        th { background-color: #e9ecef; color: #2c3e50; font-weight: bold; border-bottom: 2px solid #007bff; }
        .info-table { margin-bottom: 20px; border: 1px solid #2c3e50; }
        .info-table td { text-align: left; padding: 6px; border: none; }
        .info-table tr:nth-child(odd) { background-color: #f8f9fa; }
        .items-table tr:nth-child(even) { background-color: #f9f9f9; }
        .totals-box { width: 300px; margin: 20px 0 0 auto; border: 2px double #2c3e50; padding: 10px; background-color: #ffffff; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        .totals-box table { border: none; }
        .totals-box td { padding: 5px; border: none; text-align: right; }
        .totals-box td.label { text-align: left; font-weight: bold; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path($company->logo_path) }}" alt="Logo" class="logo">
        <h1>{{ $company->name }}</h1>
        <h4>Facture N° : {{ $invoice->numdoc }}</h4>
        <h4 class="barcode-container"><img src="{{ $barcode }}" alt="Code-barres" class="barcode"></h4>
        <div class="triangle-top-right"></div>
        <div class="triangle-bottom-left"></div>
    </header>

    <main>

        <table class="info-table">

            <tr>
                <td><strong>Client :</strong> {{ $invoice->customer->name ?? '-' }}</td>
                <td><strong>Date Facture :</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>N° Client :</strong> {{ $invoice->numclient ?? '-' }}</td>
                <td><strong>Statut :</strong> {{ ucfirst($invoice->status) }}</td>
            </tr>
            <tr>
                <td><strong>Adresse :</strong> {{ $invoice->customer->address ?? '-' }}</td>
                <td><strong>Date d'Échéance :</strong> {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>TVA :</strong> {{ number_format($invoice->tva_rate, 2, ',', ' ') }}%</td>
                <td><strong>Type :</strong> {{ ucfirst($invoice->type) }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Code Article</th>
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
                        <td></td>
                        <td>{{ $line->item->name ?? $line->description ?? '-' }}</td>
                        <td>{{ number_format($line->quantity, 0, ',', ' ') }}</td>
                        <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($line->remise ?? 0, 2, ',', ' ') }}%</td>
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
                    <td>{{ number_format($invoice->total_ht, 2, ',', ' ') }} €</td>
                </tr>
                <tr>
                    <td class="label">TVA ({{ number_format($invoice->tva_rate, 2, ',', ' ') }}%) :</td>
                    <td>{{ number_format($invoice->total_ttc - $invoice->total_ht, 2, ',', ' ') }} €</td>
                </tr>
                <tr>
                    <td class="label">Total TTC :</td>
                    <td>{{ number_format($invoice->total_ttc, 2, ',', ' ') }} €</td>
                </tr>
            </table>
        </div>

        <div class="clearfix"></div>
    </main>

    <footer>
        <hr>
        <p>
            <strong>{{ $company->name }}</strong> | {{ $company->address }}<br>
            MF : {{ $company->matricule_fiscal }} | SWIFT : {{ $company->swift ?? '-' }} | Tél : {{ $company->phone }}<br>
            RIB : {{ $company->rib ?? '-' }} | IBAN : {{ $company->iban ?? '-' }} | Email : <strong>{{ $company->email }}</strong>
        </p>
    </footer>
</body>
</html>