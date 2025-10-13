<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Véhicule</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <style>
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }
        .table thead th {
            background-color: #e9ecef;
        }
        .total-row {
            background-color: #d1ecf1;
            font-weight: bold;
        }
        .badge-status {
            font-size: 0.85rem;
        }
        .vehicle-info {
            margin-bottom: 20px;
        }
        .vehicle-info h4 span,
        .vehicle-info h5 span,
        .vehicle-info h6 span {
            font-weight: normal;
            color: #555;
        }
        .pdf-button-top {
            float: right;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <div class="vehicle-info d-flex justify-content-between align-items-center">
        <div>
            <h4>Véhicule : <span>{{ $vehicle->license_plate }}</span></h4>
            <h5>Modèle : <span>{{ $vehicle->brand_name }} {{ $vehicle->model_name }}</span></h5>
            <h6>Moteur : <span>{{ $vehicle->engine_description }}</span></h6>
        </div>
        <div class="pdf-button-top">
            <a href="{{ route('vehicles.history.pdf', $vehicle->id) }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Générer l’historique de réparation
            </a>
        </div>
    </div>

    <h5 class="mb-3 mt-4">Bons de Livraison</h5>

    @foreach($deliveryNotes as $bl)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    BL <strong>{{ $bl->numdoc }}</strong> | Date : {{ $bl->delivery_date->format('d/m/Y') }}
                </div>
                <div>
                    Client : <strong>{{ $bl->customer->name ?? '-' }}</strong>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered mb-0">
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
                            <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                            <td>{{ number_format($line->unit_price_ttc, 2, ',', ' ') }} €</td>
                            <td>{{ $line->remise }} %</td>
                            <td>{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                            <td>{{ number_format($line->total_ligne_ttc, 2, ',', ' ') }} €</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="5" class="text-end">Total BL</td>
                            <td>{{ number_format($bl->total_ht, 2, ',', ' ') }} €</td>
                            <td>{{ number_format($bl->total_ttc, 2, ',', ' ') }} €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @if($bl->notes)
                <div class="card-footer text-muted">
                    <strong>Note :</strong> {{ $bl->notes }}
                </div>
            @endif
        </div>
    @endforeach

    <div class="text-end mb-4">
        <a href="{{ route('vehicles.history.pdf', $vehicle->id) }}" target="_blank" class="btn btn-primary">
            <i class="bi bi-file-earmark-pdf"></i> Générer l’historique de réparation
        </a>
    </div>

</div>
</body>
</html>
