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
    <!-- Bouton retour -->
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left-circle"></i> Revenir au catalogue
        </a>
    </div>

    <h1 class="mb-4 text-primary">
        <i class="bi bi-search"></i> Résultats de la recherche de pièces auto
    </h1>

    <!-- Barre de recherche locale -->
    <div class="mb-4">
        <input type="text" id="searchArticles" class="form-control" placeholder="🔍 Rechercher dans les résultats...">
    </div>

    @if(isset($articles) && count($articles) > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="articlesList">
            @foreach($articles as $index => $article)
                <div class="col article-card" style="{{ $index >= 20 ? 'display:none;' : '' }}">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 d-flex justify-content-between align-items-center">
    <span>
        <i class="bi bi-gear-fill"></i>
        {{ $article->directArticle->articleNo ?? 'N/A' }}
    </span>
    <button class="btn btn-sm btn-light copy-btn" 
            data-ref="{{ ($article->directArticle->articleNo ?? 'N/A')}}"
            title="Copier la référence">
        <i class="bi bi-clipboard"></i>
    </button>


</h5>

                        </div>
                        <div class="card-body">
                            <span class="badge bg-info text-dark mb-2">
                                {{ $article->directArticle->brandName ?? 'Fabricant inconnu' }}
                            </span>
                            <p class="card-text text-muted">
                                {{ $article->directArticle->articleName ?? 'Nom inconnu' }}
                            </p>

                            <!-- Références OE -->
                            @if(!empty($article->oenNumbers))
                                <div class="accordion" id="accordion-{{ $loop->index }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->index }}">
                                                <i class="bi bi-link"></i> Références d'origine
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $loop->index }}" class="accordion-collapse collapse">
                                            <div class="accordion-body p-0">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($article->oenNumbers as $oen)
                                                        <li class="list-group-item small d-flex justify-content-between align-items-center">
    <span>
        <strong>{{ $oen->brandName }}:</strong> {{ $oen->oeNumber }}
    </span>
    <button class="btn btn-sm btn-outline-secondary copy-btn" data-ref="{{ $oen->oeNumber }}" title="Copier la référence">
        <i class="bi bi-clipboard"></i>
    </button>
</li>

                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted"><i class="bi bi-exclamation-circle"></i> Aucune référence OE disponible.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bouton Charger plus -->
        <div class="text-center mt-4">
            <button id="loadMoreBtn" class="btn btn-primary">
                <i class="bi bi-arrow-down-circle"></i> Charger plus
            </button>
        </div>
    @else
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-triangle-fill"></i> Aucun résultat trouvé pour votre recherche.
        </div>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let itemsPerPage = 20;
    let currentIndex = itemsPerPage;

    const cards = document.querySelectorAll("#articlesList .article-card");
    const loadMoreBtn = document.getElementById("loadMoreBtn");

    // Gestion du bouton "Charger plus"
    loadMoreBtn.addEventListener("click", function () {
        let nextIndex = currentIndex + itemsPerPage;
        for (let i = currentIndex; i < nextIndex && i < cards.length; i++) {
            cards[i].style.display = "";
        }
        currentIndex = nextIndex;

        if (currentIndex >= cards.length) {
            loadMoreBtn.style.display = "none"; // Cacher si tout est affiché
        }
    });

    // Recherche locale
    document.getElementById("searchArticles").addEventListener("keyup", function() {
        const query = this.value.toLowerCase();
        cards.forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(query) ? "" : "none";
        });
    });
});




document.addEventListener("DOMContentLoaded", function () {
    // Gestion des boutons copier
    document.querySelectorAll(".copy-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            const ref = this.getAttribute("data-ref");
            navigator.clipboard.writeText(ref).then(() => {
                // Feedback visuel rapide
                this.innerHTML = '<i class="bi bi-clipboard-check text-success"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-clipboard"></i>';
                }, 1500);
            });
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Boutons copier
    document.querySelectorAll(".copy-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            const ref = this.getAttribute("data-ref");
            navigator.clipboard.writeText(ref).then(() => {
                // Icône check
                this.innerHTML = '<i class="bi bi-clipboard-check text-success"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-clipboard"></i>';
                }, 1500);

                // Afficher toast Bootstrap
                const toastEl = document.getElementById("copyToast");
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
        });
    });
});

</script>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Toast notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                ✅ Référence copiée !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>



</body>
</html>
