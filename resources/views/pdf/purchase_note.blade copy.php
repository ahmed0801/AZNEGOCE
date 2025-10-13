<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Avoir Achat #{{ $note->numdoc }}</title>
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
            height: 90px;
            float: left;
            margin-left: 5px; /* Ajout d'une marge pour éviter que le logo ne touche le cadre */
        }

        header h1 {
            margin: 20px 0 10px 0; /* Ajuster les marges pour centrer verticalement */
            font-size: 20px;
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
    </style>
</head>
<body>
    <header>
        <img src="{{ $company->logo_path }}" alt="Logo">
        <h1>{{ $company->name }}</h1>
        <h4>Avoir Achat N° : {{ $note->numdoc }}</h4>
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
                <td><strong>Fournisseur :</strong> {{ $note->supplier->name ?? '-' }}</td>
                <td><strong>Date Avoir :</strong> {{ \Carbon\Carbon::parse($note->note_date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Numéro Fournisseur :</strong> {{ $note->supplier->code ?? '-' }}</td>
                <td><strong>Statut :</strong> {{ ucfirst($note->status) }}</td>
            </tr>
            <tr>
                <td><strong>Type Avoir :</strong> {{ ucfirst($note->type) }}</td>
                <td><strong>TVA Avoir :</strong> {{ number_format($note->tva_rate, 2) }} %</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Code Article</th>
                    <th>Désignation</th>
                    <th>Qté</th>
                    <th>PU HT</th>
                    <th>Remise (%)</th>
                    <th>TVA (%)</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHT = 0;
                @endphp
                @foreach ($note->lines as $line)
                    @php
                        $totalLigne = $line->total_ligne_ht;
                        $totalLigneTTC = $line->prix_ttc;
                        $totalHT += $totalLigne;
                    @endphp
                    <tr>
                        <td>{{ $line->article_code ?? '-' }}</td>
                        <td>{{ $line->item->name ?? $line->description ?? '-' }}</td>
                        <td>{{ $line->quantity }}</td>
                        <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }}</td>
                        <td>{{ $line->remise }}%</td>
                        <td>{{ number_format($line->tva, 2) }}%</td>
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
                    <td><strong>{{ number_format($totalHT * ($note->tva_rate / 100), 2, ',', ' ') }} € ({{ number_format($note->tva_rate, 2) }}%)</strong></td>
                </tr>
                <tr>
                    <td class="label"><strong>Total TTC :</strong></td>
                    <td><strong>{{ number_format($note->total_ttc, 2, ',', ' ') }} €</strong></td>
                </tr>
            </table>
        </div>
    </main>
</body>
</html>