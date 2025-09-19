<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Véhicule</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</head>
<body>
<div class="container mt-3">
    <h4>Historique du véhicule : {{ $vehicle->license_plate }} | {{ $vehicle->brand_name }} | {{ $vehicle->model_name }} | {{ $vehicle->engine_description}}</h4>

    <h5 class="mt-3">Bons de Livraison</h5>
    @foreach($deliveryNotes as $bl)
        <div class="card mb-3">
            <div class="card-header">
                <strong>BL {{ $bl->numdoc }}</strong> - Date : {{ $bl->delivery_date->format('d/m/Y') }} - Client : {{ $bl->customer->name ?? '-' }}
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Qté Livrée</th>
                            <th>PU HT</th>
                            <th>PU TTC</th>
                            <th>Remise</th>
                            <th>Total HT</th>
                            <th>Total TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bl->lines as $line)
                        <tr>
                            <td>{{ $line->item->name ?? $line->article_code }}</td>
                            <td>{{ $line->delivered_quantity }}</td>
                            <td>{{ number_format($line->unit_price_ht, 2) }} €</td>
                            <td>{{ number_format($line->unit_price_ttc, 2) }} €</td>
                            <td>{{ $line->remise }} %</td>
                            <td>{{ number_format($line->total_ligne_ht, 2) }} €</td>
                            <td>{{ number_format($line->total_ligne_ttc, 2) }} €</td>
                        </tr>
                        @endforeach
                        <tr class="table-primary">
                            <td colspan="5" class="text-end"><strong>Total BL</strong></td>
                            <td><strong>{{ number_format($bl->total_ht, 2) }} €</strong></td>
                            <td><strong>{{ number_format($bl->total_ttc, 2) }} €</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="mt-3 text-end">
        <a href="{{ route('vehicles.history.pdf', $vehicle->id) }}" target="_blank" class="btn btn-primary">
            Exporter en PDF
        </a>
    </div>
</div>
</body>
</html>
