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
    <h4>Historique du véhicule ID: {{ $vehicleId }}</h4>

    <h5 class="mt-3">Bons de Livraison</h5>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Date</th>
                <th>Numéro BL</th>
                <th>Client</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveryNotes as $bl)
            <tr>
                <td>{{ $bl->date }}</td>
                <td>{{ $bl->number }}</td>
                <td>{{ $bl->customer->name ?? '-' }}</td>
                <td>{{ number_format($bl->total_ht, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5 class="mt-3">Retours</h5>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Date</th>
                <th>Numéro Retour</th>
                <th>Client</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returnNotes as $retour)
            <tr>
                <td>{{ $retour->date }}</td>
                <td>{{ $retour->number }}</td>
                <td>{{ $retour->customer->name ?? '-' }}</td>
                <td>{{ number_format($retour->total_ht, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 text-end">
        <a href="{{ route('vehicles.history.pdf', $vehicleId) }}" target="_blank" class="btn btn-primary">
            Exporter en PDF
        </a>
    </div>
</div>
</body>
</html>
