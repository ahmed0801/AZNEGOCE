<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche TecDoc - Résultats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-part {
            border-left: 5px solid #0d6efd;
            transition: transform 0.2s ease;
        }
        .card-part:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4 text-primary"><i class="bi bi-search"></i> Résultats de la recherche de pièces auto</h1>

    @if(isset($articles))
        @if($articles)
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($articles as $article)
                    <div class="col">
                        <div class="card card-part shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-gear-fill text-secondary"></i>
                                    {{ $article->directArticle->articleNo ?? 'N/A' }}
                                    <span class="badge bg-info text-dark">{{ $article->directArticle->brandName ?? 'Fabricant inconnu' }}</span>
                                </h5>
                                <p class="card-text text-muted">{{ $article->directArticle->articleName ?? 'Nom inconnu' }}</p>

                                @if(!empty($article->oenNumbers))
                                    <h6 class="mt-3">Références d'origine :</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($article->oenNumbers as $oen)
                                            <li class="list-group-item">
                                                <i class="bi bi-link"></i>
                                                <strong>{{ $oen->brandName }}:</strong> {{ $oen->oeNumber }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mt-2"><i class="bi bi-exclamation-circle"></i> Aucune référence OE disponible.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle-fill"></i> Aucun résultat trouvé pour votre recherche.
            </div>
        @endif
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
