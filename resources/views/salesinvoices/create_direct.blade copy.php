<div class="container">
    <h1>Créer une Facture Directe</h1>
    <form method="POST" action="{{ route('salesinvoices.store_direct', $deliveryNote->id) }}">
        @csrf
        <div class="form-group">
            <label for="customer_id">Client</label>
            <select name="customer_id" id="customer_id" class="form-control" disabled>
                <option value="{{ $deliveryNote->customer->id }}">{{ $deliveryNote->customer->name }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="invoice_date">Date de Facture</label>
            <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <h3>Lignes du Bon de Livraison #{{ $deliveryNote->numdoc }}</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire HT</th>
                    <th>Remise (%)</th>
                    <th>Total HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryNote->lines as $line)
                    <tr>
                        <td>{{ $line->item->name ?? $line->article_code }}</td>
                        <td>{{ $line->delivered_quantity }}</td>
                        <td>{{ number_format($line->unit_price_ht, 2) }} TND</td>
                        <td>{{ $line->remise ?? 0 }}%</td>
                        <td>{{ number_format($line->total_ligne_ht, 2) }} TND</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="form-group">
            <button type="submit" name="action" value="save" class="btn btn-secondary">Enregistrer comme Brouillon</button>
            <button type="submit" name="action" value="validate" class="btn btn-primary">Valider</button>
            <a href="{{ route('salesinvoices.index') }}" class="btn btn-danger">Annuler</a>
        </div>
    </form>
</div>
