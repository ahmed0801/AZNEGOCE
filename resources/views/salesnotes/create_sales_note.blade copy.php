<!DOCTYPE html>
<html>
<head>
    <title>Créer un Avoir</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Créer un Avoir</h1>
        <form action="{{ route('salesnotes.store_sales_note') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer_id">Client</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Sélectionner un client</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" data-tva-rate="{{ $tvaRates[$customer->id] ?? 0 }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="note_date">Date de l'avoir</label>
                <input type="date" name="note_date" id="note_date" class="form-control" required value="{{ now()->format('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label for="tva_rate">Taux de TVA (%)</label>
                <input type="number" name="tva_rate" id="tva_rate" class="form-control" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="source_type">Type de source</label>
                <select name="source_type" id="source_type" class="form-control" required>
                    <option value="">Sélectionner le type</option>
                    <option value="return">Retour (non facturé)</option>
                    <option value="invoice">Facture (validée)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="source_ids">Sélectionner les documents</label>
                <select name="source_ids[]" id="source_ids" class="form-control select2" multiple required>
                    <option value="">Sélectionner un document</option>
                </select>
            </div>

            <div class="form-group">
                <label>Lignes de l'avoir</label>
                <table class="table" id="lines_table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Code Article</th>
                            <th>Description</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire HT</th>
                            <th>Remise (%)</th>
                            <th>Total HT</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="action">Action</label>
                <select name="action" id="action" class="form-control" required>
                    <option value="save">Enregistrer comme brouillon</option>
                    <option value="validate">Valider</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Créer l'Avoir</button>
        </form>
    </div>

   <script>
    console.log('JavaScript loaded');

    // Initialize Select2 for source_ids
    $(document).ready(function () {
        $('#source_ids').select2({
            placeholder: 'Sélectionner un ou plusieurs documents',
            allowClear: true,
            width: '100%'
        });
    });

    const sourceTypeSelect = document.getElementById('source_type');
    const sourceIdsSelect = document.getElementById('source_ids');
    const customerSelect = document.getElementById('customer_id');

    if (!sourceTypeSelect || !sourceIdsSelect || !customerSelect) {
        console.error('DOM elements not found:', {
            sourceTypeSelect: !!sourceTypeSelect,
            sourceIdsSelect: !!sourceIdsSelect,
            customerSelect: !!customerSelect
        });
    }

    // Function to fetch documents based on source_type and customer_id
    function fetchDocuments(sourceType, customerId) {
        const sourceSelect = document.getElementById('source_ids');
        sourceSelect.innerHTML = '<option value="">Sélectionner un document</option>';
        if (sourceType === 'return' || sourceType === 'invoice') {
            console.log('Fetching documents for:', { sourceType, customerId });
            const url = new URL('/salesnotes/source/documents', window.location.origin);
            url.searchParams.append('source_type', sourceType);
            if (customerId) {
                url.searchParams.append('customer_id', customerId);
            }
            
            fetch(url)
                .then(response => {
                    console.log('Fetch response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetch response data:', data);
                    if (data.documents && Array.isArray(data.documents)) {
                        data.documents.forEach(doc => {
                            const option = new Option(`${doc.numdoc} - ${doc.customer_name || 'N/A'}`, doc.id);
                            sourceSelect.add(option);
                        });
                        $(sourceSelect).trigger('change');
                    } else {
                        console.error('No documents found in response:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching documents:', error);
                });
        } else {
            console.error('Invalid source type:', sourceType);
        }
    }

    // Update source_ids when source_type changes
    sourceTypeSelect.addEventListener('change', function () {
        const sourceType = this.value;
        const customerId = customerSelect.value;
        console.log('Source type changed:', { sourceType, customerId });
        fetchDocuments(sourceType, customerId);
    });

    // Update source_ids when customer_id changes
    customerSelect.addEventListener('change', function () {
        const customerId = this.value;
        const sourceType = sourceTypeSelect.value;
        console.log('Customer ID changed:', { customerId, sourceType });
        const selectedOption = this.options[this.selectedIndex];
        const tvaRate = selectedOption ? selectedOption.getAttribute('data-tva-rate') : 0;
        document.getElementById('tva_rate').value = tvaRate;
        fetchDocuments(sourceType, customerId);
    });

    // Populate lines_table when source_ids changes
    $('#source_ids').on('select2:select select2:unselect', function () {
        const sourceType = sourceTypeSelect.value;
        const sourceIds = $(this).val() || [];
        console.log('Source IDs changed (Select2):', { sourceType, sourceIds });
        const tbody = document.getElementById('lines_table').querySelector('tbody');
        tbody.innerHTML = '';
        
        if (sourceIds.length > 0 && (sourceType === 'return' || sourceType === 'invoice')) {
            console.log('Fetching lines for source IDs:', sourceIds);
            const url = new URL('/salesnotes/source/lines', window.location.origin);
            url.searchParams.append('source_type', sourceType);
            sourceIds.forEach(id => url.searchParams.append('source_ids[]', id));
            
            fetch(url)
                .then(response => {
                    console.log('Fetch response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetch response data:', data);
                    if (data.lines && Array.isArray(data.lines) && data.lines.length > 0) {
                        data.lines.forEach((line, index) => {
                            const quantity = parseFloat(line.quantity) || 0;
                            const unitPrice = parseFloat(line.unit_price_ht) || 0;
                            const remise = parseFloat(line.remise) || 0;
                            
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${line.source_numdoc || 'N/A'}</td>
                                <td><input type="hidden" name="lines[${index}][source_id]" value="${line.source_id || ''}">${line.article_code || 'N/A'}</td>
                                <td>${line.description || line.article_code || 'N/A'}</td>
                                <td><input type="number" name="lines[${index}][quantity]" class="form-control" value="${quantity}" max="${quantity}" step="0.01" required></td>
                                <td><input type="number" name="lines[${index}][unit_price_ht]" class="form-control" value="${unitPrice}" step="0.01" required></td>
                                <td><input type="number" name="lines[${index}][remise]" class="form-control" value="${remise}" step="0.01"></td>
                                <td class="total-ht"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-line">Supprimer</button></td>
                                <input type="hidden" name="lines[${index}][article_code]" value="${line.article_code || ''}">
                            `;
                            tbody.appendChild(row);
                        });

                        document.querySelectorAll('.remove-line').forEach(button => {
                            button.addEventListener('click', function () {
                                this.closest('tr').remove();
                            });
                        });
                    } else {
                        console.warn('No lines found in response:', data);
                        tbody.innerHTML = '<tr><td colspan="8">Aucune ligne trouvée.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching source lines:', error);
                    tbody.innerHTML = '<tr><td colspan="8">Erreur lors du chargement des lignes.</td></tr>';
                });
        } else {
            console.warn('No source IDs or invalid source type:', { sourceIds, sourceType });
            tbody.innerHTML = '<tr><td colspan="8">Sélectionnez un type et des documents.</td></tr>';
        }
    });
</script>
</body>
</html>