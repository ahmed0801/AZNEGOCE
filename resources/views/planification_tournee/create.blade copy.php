
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une Tournée</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .container {
            max-width: 100%;
            padding: 10px;
        }
        .document-details {
            margin-left: 20px;
            font-size: 0.9rem;
        }
        .document-details li {
            margin-bottom: 5px;
        }
        @media (max-width: 576px) {
            .form-control, .select2-container {
                font-size: 0.9rem;
            }
            .document-details {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Créer une Tournée</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('planification.tournee.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Chauffeur</label>
                <select name="user_id" class="form-control select2" required>
                    <option value="">Sélectionner un chauffeur</option>
                    @foreach ($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}">{{ $utilisateur->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Date et Heure Planifiées</label>
                <input type="datetime-local" name="datetime_planifie" class="form-control" min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="form-group">
                <label>Commandes d'Achat</label>
                <select name="commande_achat_ids[]" class="form-control select2" multiple id="commande_achat_select">
                    <option value="">Aucune</option>
                    @foreach ($commandesAchats as $ca)
                        <option value="{{ $ca->id }}" data-details="{{ json_encode($ca->lines->map(function($line) { return ['code' => $line->article_code, 'name' => $line->item->name ?? 'Non disponible', 'quantity' => $line->ordered_quantity]; })) }}">
                            {{ $ca->numdoc }} - {{ $ca->supplier->name ?? 'Fournisseur inconnu' }}
                        </option>
                    @endforeach
                </select>
                <div id="commande_achat_details" class="document-details"></div>
            </div>
            <div class="form-group">
                <label>Bons de Livraison</label>
                <select name="bon_livraison_ids[]" class="form-control select2" multiple id="bon_livraison_select">
                    <option value="">Aucun</option>
                    @foreach ($bonsLivraisons as $bl)
                        <option value="{{ $bl->id }}" data-details="{{ json_encode($bl->lines->map(function($line) { return ['code' => $line->article_code, 'name' => $line->item->name ?? 'Non disponible', 'quantity' => $line->delivered_quantity]; })) }}">
                            {{ $bl->numdoc }} - {{ $bl->customer->name ?? 'Client inconnu' }}
                        </option>
                    @endforeach
                </select>
                <div id="bon_livraison_details" class="document-details"></div>
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "-- Sélectionner une option --",
                allowClear: true
            });

            function updateDetails(selectId, detailsId) {
                const selectedOptions = $(`#${selectId} option:selected`);
                let html = '';
                selectedOptions.each(function() {
                    const details = $(this).data('details');
                    if (details && details.length > 0) {
                        html += `<div><strong>${$(this).text()}</strong></div>`;
                        html += '<ul>';
                        details.forEach(item => {
                            html += `<li>${item.code} - ${item.name} - Qté: ${item.quantity}</li>`;
                        });
                        html += '</ul>';
                    }
                });
                $(`#${detailsId}`).html(html);
            }

            $('#commande_achat_select').on('change', function() {
                updateDetails('commande_achat_select', 'commande_achat_details');
            });

            $('#bon_livraison_select').on('change', function() {
                updateDetails('bon_livraison_select', 'bon_livraison_details');
            });
        });
    </script>
</body>
</html>
