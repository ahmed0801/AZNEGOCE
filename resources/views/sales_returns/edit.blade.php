<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Retour de Vente {{ $return->numdoc }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .form-group { margin-bottom: 1.5rem; }
        .table { width: 100%; margin-bottom: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .quantity-input { width: 100px; }
        .error { font-size: 0.85rem; color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-inner">
            <h4>📋 Modifier le Retour de Vente : {{ $return->numdoc }}</h4>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('delivery_notes.returns.update', $return->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Client</label>
                                    <input type="text" class="form-control" value="{{ $return->customer->name ?? 'Inconnu' }}" readonly>
                                    <input type="hidden" name="customer_id" value="{{ $return->customer->id }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date du retour</label>
                                    <input type="date" name="return_date" class="form-control @error('return_date') is-invalid @enderror" value="{{ old('return_date', $return->return_date->format('Y-m-d')) }}" required>
                                    @error('return_date')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type de retour</label>
                                    <select name="type" class="form-control select2 @error('type') is-invalid @enderror" id="returnType" required>
                                        <option value="total" {{ old('type', $return->type) == 'total' ? 'selected' : '' }}>Total</option>
                                        <option value="partiel" {{ old('type', $return->type) == 'partiel' ? 'selected' : '' }}>Partiel</option>
                                    </select>
                                    @error('type')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control">{{ old('notes', $return->notes) }}</textarea>
                        </div>
                        <div id="partialReturnSection" class="mt-4" style="{{ old('type', $return->type) == 'total' ? 'display: none;' : '' }}">
                            <h5>Lignes du retour</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sélectionner</th>
                                        <th>Code Article</th>
                                        <th>Désignation</th>
                                        <th>Qté Livrée</th>
                                        <th>Qté Retournée</th>
                                        <th>PU HT</th>
                                        <th>Remise (%)</th>
                                        <th>Total HT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($return->deliveryNote->lines as $line)
                                        @php
                                            $existingLine = $return->lines->where('article_code', $line->article_code)->first();
                                            $totalReturned = \App\Models\SalesReturnLine::where('article_code', $line->article_code)
                                                ->whereHas('salesReturn', fn($q) => $q->where('delivery_note_id', $return->deliveryNote->id)->where('id', '!=', $return->id))
                                                ->sum('returned_quantity');
                                            $maxReturnable = $line->delivered_quantity - $totalReturned;
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="lines[{{ $line->article_code }}][selected]" value="1" class="select-line" {{ old("lines.{$line->article_code}.selected", $existingLine ? 1 : 0) == 1 ? 'checked' : '' }}>
                                                <input type="hidden" name="lines[{{ $line->article_code }}][article_code]" value="{{ $line->article_code }}">
                                            </td>
                                            <td>{{ $line->article_code }}</td>
                                            <td>{{ $line->item->name ?? '-' }}</td>
                                            <td>{{ $line->delivered_quantity }}</td>
                                            <td>
                                                <input type="number" name="lines[{{ $line->article_code }}][returned_quantity]" class="form-control quantity-input @error("lines.{$line->article_code}.returned_quantity") is-invalid @enderror" min="0" max="{{ $maxReturnable }}" value="{{ old("lines.{$line->article_code}.returned_quantity", $existingLine ? $existingLine->returned_quantity : 0) }}" {{ old("lines.{$line->article_code}.selected", $existingLine ? 1 : 0) != 1 ? 'disabled' : '' }}>
                                                @error("lines.{$line->article_code}.returned_quantity")
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                            <td>{{ $line->remise }}%</td>
                                            <td>{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="{{ route('delivery_notes.return.show', $return->id) }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });

            $('#returnType').on('change', function () {
                if ($(this).val() === 'partiel') {
                    $('#partialReturnSection').show();
                    $('.select-line').prop('disabled', false);
                    $('.quantity-input').each(function () {
                        if ($(this).closest('tr').find('.select-line').is(':checked')) {
                            $(this).prop('disabled', false);
                        }
                    });
                } else {
                    $('#partialReturnSection').hide();
                    $('.select-line').prop('disabled', true);
                    $('.quantity-input').prop('disabled', true);
                }
            });

            $('.select-line').on('change', function () {
                const quantityInput = $(this).closest('tr').find('.quantity-input');
                quantityInput.prop('disabled', !this.checked);
                if (!this.checked) {
                    quantityInput.val(0);
                }
            });

            $('.quantity-input').on('input', function () {
                const max = parseFloat($(this).attr('max')) || 0;
                const value = parseFloat($(this).val()) || 0;
                if (value > max) {
                    $(this).val(max);
                    alert('La quantité à retourner ne peut pas dépasser la quantité livrée restante.');
                }
            });
        });
    </script>
</body>
</html>