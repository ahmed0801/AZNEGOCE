<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Véhicule #{{ $vehicle->license_plate }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; margin: 40px; line-height: 1.4; }
        @page { margin: 15mm; }
        header { text-align: center; border: 4px double #007bff; background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; }
        header img.logo { height: 85px; float: left; margin-left: 5px; }
        header h1 { font-size: 20px; color: #2c3e50; font-weight: bold; }
        header h4 { margin-top: 5px; font-size: 14px; color: #555; }
        footer { text-align: center; font-size: 10px; color: #555; border-top: 2px double #007bff; padding-top: 5px; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #2c3e50; padding: 6px; text-align: center; }
        th { background-color: #e9ecef; font-weight: bold; }
        .info-table td { text-align: left; border: none; padding: 5px; }
        .vehicle-row { background-color: #e3f2fd; font-style: italic; }
        .items-table tr:nth-child(even) { background-color: #f9f9f9; }
        .totals-box { width: 300px; margin: 10px 0 20px auto; border: 2px double #2c3e50; padding: 8px; background: #fff; }
        .totals-box td { border: none; padding: 4px; text-align: right; }
        .totals-box td.label { text-align: left; font-weight: bold; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>
<header>
    <img src="{{ public_path($company->logo_path) }}" alt="Logo" class="logo">
    <h1>{{ $company->name }}</h1>
    <h4>Historique des Réparations : {{ $vehicle->license_plate }}</h4>
</header>

<main>
    @foreach($deliveryNotes as $bl)
        <table class="info-table">
            <tr>
                <td><strong>BL N° :</strong> {{ $bl->numdoc }}</td>
                <td><strong>Date :</strong> {{ $bl->delivery_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Client :</strong> {{ $bl->customer->name ?? '-' }}</td>
                <td><strong>N° Client :</strong> {{ $bl->numclient ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>TVA :</strong> {{ number_format($bl->tva_rate, 2, ',', ' ') }}%</td>
                <td><strong>Commande :</strong> {{ $bl->salesOrder->numdoc ?? '-' }}</td>
            </tr>
            <tr class="vehicle-row">
                <td colspan="2"><strong>Véhicule :</strong> {{ $bl->vehicle ? $bl->vehicle->license_plate . ' (' . $bl->vehicle->brand_name . ' ' . $bl->vehicle->model_name . ')' : '-' }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Code Article</th>
                    <th>Désignation</th>
                    <th>Qté Livrée</th>
                    <th>PU HT</th>
                    <th>Remise (%)</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bl->lines as $line)
                    <tr>
                        <td>{{ $line->article_code ?? '-' }}</td>
                        <td>{{ $line->item->name ?? '-' }}</td>
                        <td>{{ $line->delivered_quantity }}</td>
                        <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                        <td>{{ $line->remise }}%</td>
                        <td>{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($line->total_ligne_ttc, 2, ',', ' ') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($bl->notes)
            <p><b>Note :</b> <u>{{ $bl->notes }}</u></p>
        @endif

        <div class="totals-box">
            <table>
                <tr>
                    <td class="label">Total HT :</td>
                    <td>{{ number_format($bl->total_ht, 2, ',', ' ') }} €</td>
                </tr>
                <tr>
                    <td class="label">TVA :</td>
                    <td>{{ number_format($bl->total_ttc - $bl->total_ht, 2, ',', ' ') }} €</td>
                </tr>
                <tr>
                    <td class="label">Total TTC :</td>
                    <td>{{ number_format($bl->total_ttc, 2, ',', ' ') }} €</td>
                </tr>
            </table>
        </div>

        <div class="clearfix"></div>
        <hr style="margin: 20px 0; border-top: 1px solid #007bff;">
    @endforeach
</main>

<footer>
    <p>
        <strong>{{ $company->name }}</strong> | {{ $company->address }}<br>
        MF : {{ $company->matricule_fiscal }} | SWIFT : {{ $company->swift }} | Tél : {{ $company->phone }}<br>
        RIB : {{ $company->rib }} | IBAN : {{ $company->iban }} | Email : <strong>{{ $company->email }}</strong>
    </p>
</footer>
</body>
</html>
