
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer une Tournée</title>
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
        <h3>Éditer la Tournée</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('planification.tournee.update', $planification->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Chauffeur</label>
                <select name="user_id" class="form-control select2" required>
                    <option value="">Sélectionner un chauffeur</option>
                    @foreach ($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}" {{ $planification->user_id == $utilisateur->id ? 'selected' : '' }}>{{ $utilisateur->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Date et Heure Planifiées</label>
                <input type="datetime-local" name="datetime_planifie" class="form-control" value="{{ $planification->datetime_planifie->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="form-group">
                <label>Commandes d'Achat</label>
                <select name="commande_achat_ids[]" class="form-control select2" multiple>
                    <option value="">Aucune</option>
                    @foreach ($commandesAchats as $ca)
                        <option value="{{ $ca->id }}" {{ $planification->commandesAchats->contains($ca->id) ? 'selected' : '' }}>
                            {{ $ca->numdoc }} - {{ $ca->supplier->name ?? 'Fournisseur inconnu' }}
                        </option>
                    @endforeach
                </select>
                @if ($planification->commandesAchats->isNotEmpty())
                    <div class="document-details">
                        <strong>Détails des Commandes d'Achat sélectionnées :</strong>
                        @foreach ($planification->commandesAchats as $ca)
                            <div>{{ $ca->numdoc }} ({{ $ca->status }})</div>
                            <ul>
                                @foreach ($ca->lines as $line)
                                    <li>
                                        {{ $line->article_code }} - 
                                        {{ $line->item->name ?? 'Non disponible' }} - 
                                        Qté à recevoir : {{ $line->ordered_quantity }}
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>Bons de Livraison</label>
                <select name="bon_livraison_ids[]" class="form-control select2" multiple>
                    <option value="">Aucun</option>
                    @foreach ($bonsLivraisons as $bl)
                        <option value="{{ $bl->id }}" {{ $planification->bonsLivraisons->contains($bl->id) ? 'selected' : '' }}>
                            {{ $bl->numdoc }} - {{ $bl->customer->name ?? 'Client inconnu' }}
                        </option>
                    @endforeach
                </select>
                @if ($planification->bonsLivraisons->isNotEmpty())
                    <div class="document-details">
                        <strong>Détails des Bons de Livraison sélectionnés :</strong>
                        @foreach ($planification->bonsLivraisons as $bl)
                            <div>{{ $bl->numdoc }} ({{ $bl->status }})</div>
                            <ul>
                                @foreach ($bl->lines as $line)
                                    <li>
                                        {{ $line->article_code }} - 
                                        {{ $line->item->name ?? 'Non disponible' }} - 
                                        Qté à livrer : {{ $line->delivered_quantity }}
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control">{{ $planification->notes }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('planification.tournee.index') }}" class="btn btn-secondary">Annuler</a>
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
        });
    </script>
</body>
</html>
