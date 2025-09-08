
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Actions de Tournée</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
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
            .table {
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
        <h3>Rapport des Actions de Tournée</h3>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Chauffeur</th>
                    <th>Action</th>
                    <th>Document</th>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($actions as $action)
                    <tr>
                        <td>{{ $action->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $action->utilisateur->name }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $action->type_action)) }}</td>
                        <td>
                            @if ($action->type_document === 'commande_achat')
                                @php
                                    $ca = $action->planificationTournee->commandesAchats->firstWhere('id', $action->document_id);
                                @endphp
                                Commande Achat : {{ $ca->numdoc ?? 'Document inconnu' }} ({{ $ca->status ?? 'N/A' }})
                                @if ($ca && $ca->lines->isNotEmpty())
                                    <ul class="document-details">
                                        @foreach ($ca->lines as $line)
                                            <li>
                                                {{ $line->article_code }} - 
                                                {{ $line->item->name ?? 'Non disponible' }} - 
                                                Qté à recevoir : {{ $line->ordered_quantity }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @elseif ($action->type_document === 'bon_livraison')
                                @php
                                    $bl = $action->planificationTournee->bonsLivraisons->firstWhere('id', $action->document_id);
                                @endphp
                                Bon de Livraison : {{ $bl->numdoc ?? 'Document inconnu' }} ({{ $bl->status ?? 'N/A' }})
                                @if ($bl && $bl->lines->isNotEmpty())
                                    <ul class="document-details">
                                        @foreach ($bl->lines as $line)
                                            <li>
                                                {{ $line->article_code }} - 
                                                {{ $line->item->name ?? 'Non disponible' }} - 
                                                Qté à livrer : {{ $line->delivered_quantity }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @else
                                Aucun
                            @endif
                        </td>
                        <td>{{ $action->code_article ?? 'N/A' }}</td>
                        <td>{{ $action->quantite ?? 'N/A' }}</td>
                        <td>{{ $action->notes ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>
