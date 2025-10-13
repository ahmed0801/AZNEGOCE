<div class="container">
    <h1>Créer une Facture Libre</h1>
    <form method="POST" action="{{ route('salesinvoices.store_free') }}">
        @csrf
        <div class="form-group">
            <label for="customer_id">Client</label>
            <select name="customer_id" id="customer_id" class="form-control select2" required>
                <option value="">Sélectionnez un client</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" data-tva="{{ $customer->tvaGroup->rate ?? 0 }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="invoice_date">Date de Facture</label>
            <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="tva_rate">Taux de TVA</label>
            <input type="number" name="tva_rate" id="tva_rate" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <h3>Lignes</h3>
        <table class="table table-bordered" id="lines-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire HT</th>
                    <th>Remise (%)</th>
                    <th>Total HT</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="lines[0][description]" class="form-control" required></td>
                    <td><input type="number" name="lines[0][quantity]" class="form-control quantity" step="1" min="0" required></td>
                    <td><input type="number" name="lines[0][unit_price_ht]" class="form-control unit_price" step="0.01" min="0" required></td>
                    <td><input type="number" name="lines[0][remise]" class="form-control remise" step="0.01" min="0" max="100" value="0"></td>
                    <td><span class="total-ligne-ht">0.00</span></td>
                    <td><button type="button" class="btn btn-danger remove-line">Supprimer</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-success" id="add-line">Ajouter une ligne</button>
        <div class="form-group mt-3">
            <button type="submit" name="action" value="save" class="btn btn-secondary">Enregistrer comme Brouillon</button>
            <button type="submit" name="action" value="validate" class="btn btn-primary">Valider</button>
            <a href="{{ route('salesinvoices.index') }}" class="btn btn-danger">Annuler</a>
        </div>
    </form>
</div>

@section('scripts')
<script>
    let lineIndex = 1;
    $('#add-line').click(function() {
        let row = `
            <tr>
                <td><input type="text" name="lines[${lineIndex}][description]" class="form-control" required></td>
                <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control quantity" step="1" min="0" required></td>
                <td><input type="number" name="lines[${lineIndex}][unit_price_ht]" class="form-control unit_price" step="0.01" min="0" required></td>
                <td><input type="number" name="lines[${lineIndex}][remise]" class="form-control remise" step="0.01" min="0" max="100" value="0"></td>
                <td><span class="total-ligne-ht">0.00</span></td>
                <td><button type="button" class="btn btn-danger remove-line">Supprimer</button></td>
            </tr>`;
        $('#lines-table tbody').append(row);
        lineIndex++;
        bindEvents();
    });

    function bindEvents() {
        $('.remove-line').click(function() {
            $(this).closest('tr').remove();
            recalculate();
        });
        $('.quantity, .unit_price, .remise').on('input', recalculate);
    }

    function recalculate() {
        let totalHt = 0;
        $('#lines-table tbody tr').each(function() {
            let quantity = parseFloat($(this).find('.quantity').val()) || 0;
            let unitPrice = parseFloat($(this).find('.unit_price').val()) || 0;
            let remise = parseFloat($(this).find('.remise').val()) || 0;
            let totalLigneHt = quantity * unitPrice * (1 - remise / 100);
            $(this).find('.total-ligne-ht').text(totalLigneHt.toFixed(2));
            totalHt += totalLigneHt;
        });
    }

    $(document).ready(function() {
        $('.select2').select2();
        $('#customer_id').on('change', function() {
            let tvaRate = $(this).find('option:selected').data('tva') || 0;
            $('#tva_rate').val(tvaRate);
        });
        bindEvents();
    });
</script>
