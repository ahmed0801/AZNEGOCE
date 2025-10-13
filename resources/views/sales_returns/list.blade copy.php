<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Retours de Vente</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .table { width: 100%; margin-bottom: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-inner">
            <h4>📋 Liste des retours de vente</h4>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="GET" action="{{ route('delivery_notes.salesreturns.list') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                <select name="customer_id" class="form-select form-select-sm select2" style="width: 150px;">
                    <option value="">Client (Tous)</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                <select name="delivery_note_id" class="form-select form-select-sm select2" style="width: 150px;">
                    <option value="">Bon de livraison (Tous)</option>
                    @foreach($deliveryNotes as $deliveryNote)
                        <option value="{{ $deliveryNote->id }}" {{ request('delivery_note_id') == $deliveryNote->id ? 'selected' : '' }}>
                            {{ $deliveryNote->numdoc }}
                        </option>
                    @endforeach
                </select>
                <select name="type" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Type (Tous)</option>
                    <option value="total" {{ request('type') == 'total' ? 'selected' : '' }}>Total</option>
                    <option value="partiel" {{ request('type') == 'partiel' ? 'selected' : '' }}>Partiel</option>
                </select>
                <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" value="{{ request('date_from') }}">
                <span>à</span>
                <input type="date" name="date_to" class="form-control form-control-sm" style="width: 120px;" value="{{ request('date_to') }}">
                <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                    <i class="fas fa-filter me-1"></i> Filtrer
                </button>
                <a href="{{ route('delivery_notes.salesreturns.list') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="fas fa-undo me-1"></i> Réinitialiser
                </a>
            </form>

            @foreach($returns as $return)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                        <div>
                            <h6 class="mb-0">
                                <strong>Retour N° : {{ $return->numdoc }}</strong>
                                ({{ $return->customer->name ?? 'Inconnu' }})
                                <span class="text-muted small">({{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }})</span>
                            </h6>
                            <span class="badge bg-info">Type: {{ ucfirst($return->type) }}</span>
                            <span class="badge bg-primary">BL: {{ $return->deliveryNote->numdoc ?? '-' }}</span>
                            @if($return->invoiced)
                                <span class="badge bg-success">Facturé</span>
                            @endif
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('delivery_notes.salesreturns.export_single', $return->id) }}" class="btn btn-sm btn-outline-success">
                                EXCEL <i class="fas fa-file-excel"></i>
                            </a>
                            <a href="{{ route('delivery_notes.salesreturns.print_single', $return->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                PDF <i class="fas fa-print"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Code Article</th>
                                    <th>Désignation</th>
                                    <th>Qté Retournée</th>
                                    <th>PU HT</th>
                                    <th>Remise (%)</th>
                                    <th>Total HT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($return->lines as $line)
                                    <tr>
                                        <td>{{ $line->article_code }}</td>
                                        <td>{{ $line->item->name ?? '-' }}</td>
                                        <td>{{ $line->returned_quantity }}</td>
                                        <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                        <td>{{ $line->remise }}%</td>
                                        <td>{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-end mt-3">
                            <strong>Total HT :</strong> {{ number_format($return->total_ht, 2, ',', ' ') }} €<br>
                            <strong>Total TTC :</strong> {{ number_format($return->total_ttc, 2, ',', ' ') }} €
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $returns->links() }}
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });
        });
    </script>
</body>
</html>