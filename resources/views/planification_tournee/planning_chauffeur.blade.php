<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>AZ NEGOCE - Planning Chauffeur</title>
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon">
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .page-inner { padding: 10px; }
        .accordion-button { font-size: 1.1rem; padding: 12px; background-color: #343a40; color: #fff; }
        .accordion-button:not(.collapsed) { background-color: #495057; }
        .accordion-body { padding: 10px; }
        .document-item { margin-bottom: 10px; padding: 10px; border: 1px solid #dee2e6; border-radius: 5px; background-color: #fff; }
        .document-item.completed { background-color: #d4edda; }
        .document-link { font-weight: bold; color: #007bff; text-decoration: none; font-size: 1.1rem; }
        .document-link:hover { color: #0056b3; text-decoration: underline; }
        .progress { height: 10px; margin: 5px 0; }
        .scan-input { font-size: 1.2rem; height: 50px; border-radius: 5px; }
        .scan-btn { font-size: 1.2rem; padding: 10px; width: 100%; border-radius: 5px; }
        .error-message { color: #dc3545; font-size: 0.9rem; margin-top: 5px; display: none; }
        .badge-type { font-size: 0.9rem; margin-right: 5px; }
        .scan-feedback { font-size: 1rem; color: #28a745; }
        .article-table th, .article-table td { font-size: 1rem; padding: 8px; vertical-align: middle; }
        .completed { background-color: #d4edda; }
        .scan-section { display: none; margin-top: 10px; }
        .scan-section.active { display: block; }
        .btn-icon { font-size: 1.2rem; padding: 8px; }
        .status-badge { font-size: 0.9rem; }
        .article-details { margin-top: 10px; padding-left: 20px; }
        .article-details li { font-size: 0.9rem; margin-bottom: 5px; }
        #signature-pad { border: 1px solid #ced4da; border-radius: 5px; background-color: #fff; width: 100%; height: 200px; }
        .signature-error { color: #dc3545; font-size: 0.9rem; margin-top: 5px; display: none; }
        @media (max-width: 576px) {
            .page-inner { padding: 5px; }
            .accordion-button { font-size: 1rem; padding: 10px; }
            .document-item { padding: 8px; }
            .document-link { font-size: 1rem; }
            .scan-input { font-size: 1rem; height: 45px; }
            .scan-btn { font-size: 1rem; }
            .article-table th, .article-table td { font-size: 0.9rem; padding: 6px; }
            .btn-icon { font-size: 1rem; padding: 6px; }
            .article-details li { font-size: 0.8rem; }
            #signature-pad { height: 150px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <div class="logo-header" data-background-color="dark">
                    <a href="/" class="logo">
                        <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="40">
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                        <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                    </div>
                    <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                </div>
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item"><a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
                        @if(Auth::user()->role !='livreur') 
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/avoirs"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
                        <li class="nav-item"><a href="/reglement-client"><i class="fas fa-credit-card"></i><p>Règlement Client</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                        <li class="nav-item"><a href="/reglement-fournisseur"><i class="fas fa-credit-card"></i><p>Règlement Fournisseur</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        @endif
                        <li class="nav-item active"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        @if(Auth::user()->role !='livreur') 
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-robot"></i></span><h4 class="text-section">Autres</h4></li>
                        <li class="nav-item"><a href="/voice"><i class="fas fa-robot"></i><p>NEGOBOT</p></a></li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="20">
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                        </div>
                        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                    </div>
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                    <span class="profile-username">
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded">
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramètres</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('logout.admin') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Déconnexion</button>
                                            </form>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Planning Chauffeur</h3>
                            <h6 class="op-7 mb-2">Tournées pour {{ Auth::user()->name }} - {{ $aujourdHui->format('d/m/Y') }}</h6>
                        </div>
                            <div class="ms-md-auto py-2 py-md-0">
        <a href="/dashboard" class="btn btn-secondary btn-round btn-sm">Accueil</a>
    </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card card-round mt-4">
                        <div class="card-header">
                            <div class="card-title">Tournées du Jour</div>
                        </div>
                        <div class="card-body">
                            @if ($planifications->isEmpty())
                                <p class="text-center">Aucune tournée prévue pour aujourd'hui.</p>
                            @else
                                <div class="accordion" id="tourneesAccordion">
                                    @foreach ($planifications as $planification)
                                        <div class="accordion-item" data-planification-id="{{ $planification->id }}">
                                            <h2 class="accordion-header" id="heading-{{ $planification->id }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $planification->id }}" aria-expanded="false" aria-controls="collapse-{{ $planification->id }}">
                                                    <div class="d-flex w-100 align-items-center justify-content-between">
                                                        <span>{{ $planification->datetime_planifie->format('d/m/Y H:i') }}</span>
                                                        <span class="status-badge badge bg-{{ $planification->isValidee() ? 'success' : 'secondary' }}">{{ $planification->isValidee() ? 'Validée' : 'Non validée' }}</span>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $planification->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $planification->id }}" data-bs-parent="#tourneesAccordion">
                                                <div class="accordion-body">
                                                    <div class="mb-3">
                                                        <strong>Documents :</strong>
                                                        @if ($planification->commandesAchats->isNotEmpty() || $planification->bonsLivraisons->isNotEmpty())
                                                            @foreach ($planification->commandesAchats as $ca)
                                                                <div class="document-item {{ ($ca->status_livraison === 'reçu') ? 'completed' : '' }}" data-document-id="{{ $ca->id }}" data-type="commande_achat">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <span class="badge badge-primary badge-type">Commande Achat</span>
                                                                            <a class="document-link" data-document-id="{{ $ca->id }}" data-type="commande_achat" data-numdoc="{{ $ca->numdoc ?? $ca->reference ?? $ca->id }}" data-lines="{{ json_encode($ca->lines->map(function($line) use ($articleQuantities, $ca) { return ['code' => $line->article_code, 'name' => $line->item->name ?? 'Non disponible', 'demanded' => $line->ordered_quantity, 'scanned' => $articleQuantities[$ca->id][$line->article_code]['scanned'], 'remaining' => $articleQuantities[$ca->id][$line->article_code]['remaining']]; })->toArray()) }}">{{ $ca->numdoc ?? $ca->reference ?? $ca->id }}</a> ({{ $ca->status_livraison ?? 'en cours' }})
                                                                        </div>
                                                                        <button class="btn btn-icon btn-primary scan-toggle"><i class="fas fa-barcode"></i></button>
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($scannedQuantities[$ca->id] ?? 0) / ($ca->lines->sum('ordered_quantity') ?: 1) * 100 }}%;" aria-valuenow="{{ $scannedQuantities[$ca->id] ?? 0 }}" aria-valuemin="0" aria-valuemax="{{ $ca->lines->sum('ordered_quantity') ?: 1 }}"></div>
                                                                    </div>
                                                                    <small>Scanné : {{ $scannedQuantities[$ca->id] ?? 0 }} / {{ $ca->lines->sum('ordered_quantity') ?: 1 }}</small>
                                                                    @if ($ca->lines->isNotEmpty())
                                                                        <ul class="article-details">
                                                                            @foreach ($ca->lines as $line)
                                                                                <li class="{{ $articleQuantities[$ca->id][$line->article_code]['scanned'] >= $line->ordered_quantity ? 'completed' : '' }}" data-code="{{ $line->article_code }}">
                                                                                    {{ $line->article_code }} - {{ $line->item->name ?? 'Non disponible' }} - Qté à recevoir : {{ $line->ordered_quantity }} (Scannée : {{ $articleQuantities[$ca->id][$line->article_code]['scanned'] }})
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                    <div class="scan-section">
                                                                        <form class="scan-form" action="{{ route('planification.tournee.scan') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="document_id" value="{{ $ca->id }}">
                                                                            <input type="hidden" name="document_type" value="commande_achat">
                                                                            <div class="form-group mb-3">
                                                                                <input type="text" name="code_article" class="form-control scan-input" placeholder="Scanner la référence" required>
                                                                                <div class="error-message"></div>
                                                                            </div>
                                                                            <!-- <div class="form-group mb-3"> -->
                                                                                <textarea hidden  name="notes" class="form-control" placeholder="Notes"></textarea>
                                                                            <!-- </div> -->
                                                                            <button type="submit" class="btn btn-primary scan-btn">Scanner</button>
                                                                        </form>
                                                                        <table class="table table-bordered article-table mt-3">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Code</th>
                                                                                    <th>Demandée</th>
                                                                                    <th>Scannée</th>
                                                                                    <th>Restant</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($ca->lines as $line)
                                                                                    <tr data-code="{{ $line->article_code }}" class="{{ $articleQuantities[$ca->id][$line->article_code]['scanned'] >= $line->ordered_quantity ? 'completed' : '' }}">
                                                                                        <td>{{ $line->article_code }}</td>
                                                                                        <td>{{ $line->ordered_quantity }}</td>
                                                                                        <td>{{ $articleQuantities[$ca->id][$line->article_code]['scanned'] }}</td>
                                                                                        <td>{{ $articleQuantities[$ca->id][$line->article_code]['remaining'] }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @foreach ($planification->bonsLivraisons as $bonLivraison)
                                                                <div class="document-item {{ ($bonLivraison->status_livraison === 'livré') ? 'completed' : '' }}" data-document-id="{{ $bonLivraison->id }}" data-type="bon_livraison">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <span class="badge badge-success badge-type">Bon de Livraison</span>
                                                                            <a class="document-link" data-document-id="{{ $bonLivraison->id }}" data-type="bon_livraison" data-numdoc="{{ $bonLivraison->numdoc ?? $bonLivraison->reference ?? $bonLivraison->id }}" data-lines="{{ json_encode($bonLivraison->lines->map(function($line) use ($articleQuantities, $bonLivraison) { return ['code' => $line->article_code, 'name' => $line->item->name ?? 'Non disponible', 'demanded' => $line->delivered_quantity, 'scanned' => $articleQuantities[$bonLivraison->id][$line->article_code]['scanned'], 'remaining' => $articleQuantities[$bonLivraison->id][$line->article_code]['remaining']]; })->toArray()) }}">{{ $bonLivraison->numdoc ?? $bonLivraison->reference ?? $bonLivraison->id }}</a> ({{ $bonLivraison->status_livraison ?? 'en cours' }})
                                                                        </div>
                                                                        <button class="btn btn-icon btn-primary scan-toggle"><i class="fas fa-barcode"></i></button>
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($scannedQuantities[$bonLivraison->id] ?? 0) / ($bonLivraison->lines->sum('delivered_quantity') ?: 1) * 100 }}%;" aria-valuenow="{{ $scannedQuantities[$bonLivraison->id] ?? 0 }}" aria-valuemin="0" aria-valuemax="{{ $bonLivraison->lines->sum('delivered_quantity') ?: 1 }}"></div>
                                                                    </div>
                                                                    <small>Scanné : {{ $scannedQuantities[$bonLivraison->id] ?? 0 }} / {{ $bonLivraison->lines->sum('delivered_quantity') ?: 1 }}</small>
                                                                    @if ($bonLivraison->lines->isNotEmpty())
                                                                        <ul class="article-details">
                                                                            @foreach ($bonLivraison->lines as $line)
                                                                                <li class="{{ $articleQuantities[$bonLivraison->id][$line->article_code]['scanned'] >= $line->delivered_quantity ? 'completed' : '' }}" data-code="{{ $line->article_code }}">
                                                                                    {{ $line->article_code }} - {{ $line->item->name ?? 'Non disponible' }} - Qté à livrer : {{ $line->delivered_quantity }} (Scannée : {{ $articleQuantities[$bonLivraison->id][$line->article_code]['scanned'] }})
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                    <div class="scan-section">
                                                                        <form class="scan-form" action="{{ route('planification.tournee.scan') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="document_id" value="{{ $bonLivraison->id }}">
                                                                            <input type="hidden" name="document_type" value="bon_livraison">
                                                                            <div class="form-group mb-3">
                                                                                <input type="text" name="code_article" class="form-control scan-input" placeholder="Scanner la référence" required>
                                                                                <div class="error-message"></div>
                                                                            </div>
                                                                            <!-- <div class="form-group mb-3"> -->
                                                                                <textarea hidden name="notes" class="form-control" placeholder="Notes"></textarea>
                                                                            <!-- </div> -->
                                                                            <button type="submit" class="btn btn-primary scan-btn">Scanner</button>
                                                                        </form>
                                                                        <table class="table table-bordered article-table mt-3">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Code</th>
                                                                                    <th>Demandée</th>
                                                                                    <th>Scannée</th>
                                                                                    <th>Restant</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($bonLivraison->lines as $line)
                                                                                    <tr data-code="{{ $line->article_code }}" class="{{ $articleQuantities[$bonLivraison->id][$line->article_code]['scanned'] >= $line->delivered_quantity ? 'completed' : '' }}">
                                                                                        <td>{{ $line->article_code }}</td>
                                                                                        <td>{{ $line->delivered_quantity }}</td>
                                                                                        <td>{{ $articleQuantities[$bonLivraison->id][$line->article_code]['scanned'] }}</td>
                                                                                        <td>{{ $articleQuantities[$bonLivraison->id][$line->article_code]['remaining'] }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p>Aucun document</p>
                                                        @endif
                                                    </div>
<div class="mb-3">
    <strong>Adresses :</strong>
    @if ($planification->commandesAchats->isNotEmpty() || $planification->bonsLivraisons->isNotEmpty())
        @foreach ($planification->commandesAchats as $ca)
            <div>
                @if ($ca->supplier)
                    <strong>Fournisseur:  {{ $ca->supplier->code }} - {{ $ca->supplier->name }}</strong><br>
                    {{ $ca->supplier->address_delivery ?? $ca->supplier->address ?? 'Adresse inconnue' }}
                @else
                    Adresse inconnue (Fournisseur non défini)
                @endif
            </div>
        @endforeach
        @foreach ($planification->bonsLivraisons as $bonLivraison)
            <div>
                @if ($bonLivraison->customer)
                    <strong>Client: {{ $bonLivraison->customer->code }} - {{ $bonLivraison->customer->name }}</strong><br>
                    {{ $bonLivraison->customer->address_delivery ?? $bonLivraison->customer->address ?? 'Adresse inconnue' }}
                @else
                    Adresse inconnue (Client non défini)
                @endif
            </div>
        @endforeach
    @else
        <p>Aucune adresse</p>
    @endif
</div>
                                                    <div class="mb-3">
                                                        <strong>Notes :</strong>
                                                        <p>{{ $planification->notes ?? 'Aucune note' }}</p>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('planification.tournee.rapport') }}?planification_tournee_id={{ $planification->id }}" class="btn btn-sm btn-info">Voir Rapport</a>
                                                        @if ($planification->statut === 'terminé' && !$planification->isValidee())
                                                            <form action="{{ route('planification.tournee.valider', $planification->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous valider cette tournée ? Cette action est irréversible.');">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">Valider Tournée</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.</div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize articleQuantities
            window.articleQuantities = @json($articleQuantities);

            // Play beep sound on successful scan
            const beepSound = new Audio('https://www.soundjay.com/buttons/beep-01a.mp3');

            // Toggle scan section
            $(document).on('click', '.scan-toggle', function () {
                const documentItem = $(this).closest('.document-item');
                const scanSection = documentItem.find('.scan-section');
                const isActive = scanSection.hasClass('active');

                // Hide all other scan sections
                $('.scan-section').removeClass('active').hide();
                $('.scan-input').val('');

                if (!isActive) {
                    scanSection.addClass('active').show();
                    documentItem.find('.scan-input').focus();
                }
            });

            // Handle barcode scan
            $('.scan-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const code_article = form.find('.scan-input').val().trim().replace(/[\r\n]+/g, '');
                const document_id = form.find('input[name="document_id"]').val();
                const document_type = form.find('input[name="document_type"]').val();
                const notes = form.find('textarea[name="notes"]').val();
                const document_numdoc = $(`.document-item[data-document-id="${document_id}"]`).find('.document-link').data('numdoc');

                if (!code_article) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Veuillez scanner un code article.',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                    form.find('.scan-input').val('').focus();
                    return;
                }

                // Validate article
                $.ajax({
                    url: '{{ route('planification.tournee.scan') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        code_article: code_article,
                        document_id: document_id,
                        document_type: document_type,
                        quantite: 1,
                        validate_only: true
                    },
                    success: function (response) {
                        if (response.valid) {
                            form.find('.error-message').hide();
                            // Submit scan
                            $.ajax({
                                url: '{{ route('planification.tournee.scan') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    code_article: code_article,
                                    document_id: document_id,
                                    document_type: document_type,
                                    quantite: 1,
                                    notes: notes
                                },
                                success: function (response) {
                                    console.log('Scan response:', response); // Debug response
                                    if (response.success) {
                                        // Play beep sound
                                        beepSound.play();

                                        // Update article quantities
                                        window.articleQuantities = response.article_quantities;

                                        // Update table in scan section
                                        const documentItem = $(`.document-item[data-document-id="${document_id}"]`);
                                        const tbody = documentItem.find('.article-table tbody');
                                        const lines = tbody.find('tr');
                                        lines.each(function () {
                                            const code = $(this).data('code');
                                            // Skip if article data is missing
                                            if (!window.articleQuantities[document_id] || !window.articleQuantities[document_id][code]) {
                                                console.warn(`Article data missing for document_id: ${document_id}, code: ${code}`);
                                                return;
                                            }
                                            const scanned = window.articleQuantities[document_id][code].scanned || 0;
                                            const demanded = window.articleQuantities[document_id][code].demanded || 0;
                                            const remaining = window.articleQuantities[document_id][code].remaining || 0;
                                            $(this).find('td').eq(3).text(scanned);
                                            $(this).find('td').eq(4).text(remaining);
                                            if (scanned >= demanded) {
                                                $(this).addClass('completed');
                                            } else {
                                                $(this).removeClass('completed');
                                            }
                                        });

                                        // Update article details list
                                        const articleList = documentItem.find('.article-details');
                                        if (articleList.length) {
                                            articleList.find('li').each(function () {
                                                const code = $(this).data('code');
                                                // Skip if article data is missing
                                                if (!window.articleQuantities[document_id] || !window.articleQuantities[document_id][code]) {
                                                    console.warn(`Article data missing for document_id: ${document_id}, code: ${code}`);
                                                    return;
                                                }
                                                const scanned = window.articleQuantities[document_id][code].scanned || 0;
                                                const demanded = window.articleQuantities[document_id][code].demanded || 0;
                                                const name = window.articleQuantities[document_id][code].name || 'Non disponible';
                                                $(this).text(`${code} - ${name} - Qté à ${document_type === 'commande_achat' ? 'recevoir' : 'livrer'} : ${demanded} (Scannée : ${scanned})`);
                                                if (scanned >= demanded) {
                                                    $(this).addClass('completed');
                                                } else {
                                                    $(this).removeClass('completed');
                                                }
                                            });
                                        }

                                        // Update progress bar and status
                                        $.each(response.scanned_quantities, function (docId, qty) {
                                            const total = document_type === 'commande_achat'
                                                ? $(`.document-item[data-document-id="${docId}"]`).find('.progress').attr('aria-valuemax')
                                                : $(`.document-item[data-document-id="${docId}"]`).find('.progress').attr('aria-valuemax');
                                            const percentage = total > 0 ? (qty / total) * 100 : 0;
                                            $(`.document-item[data-document-id="${docId}"]`).find('.progress-bar').css('width', `${percentage}%`).attr('aria-valuenow', qty);
                                            $(`.document-item[data-document-id="${docId}"]`).find('small').text(`Scanné : ${qty} / ${total}`);
                                            if (response.scan_data.document_completed && docId == document_id) {
                                                $(`.document-item[data-document-id="${docId}"]`).addClass('completed').find('.scan-section').removeClass('active').hide();
                                                $(`.document-item[data-document-id="${docId}"]`).find('.document-link').parent().find('span').eq(1).text(document_type === 'commande_achat' ? 'reçu' : 'livré');
                                            }
                                        });

                                        // Show notification
                                        Swal.fire({
                                            icon: 'success',
                                            title: `+1 ${response.scan_data.code_article}`,
                                            text: `Scanné: ${response.article_quantities[document_id][code_article].scanned} | Restant: ${response.scan_data.remaining_quantity}`,
                                            timer: 1500,
                                            showConfirmButton: false,
                                            position: 'top-end',
                                            toast: true
                                        });

                                        // Reset input
                                        form.find('.scan-input').val('').focus();
                                        form.find('textarea[name="notes"]').val('');

                                        // Handle article completion
                                        if (response.scan_data.article_completed) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Article Complété !',
                                                text: `L'article ${response.scan_data.code_article} est entièrement scanné.`,
                                                timer: 2000,
                                                showConfirmButton: false,
                                                position: 'top-end',
                                                toast: true
                                            });
                                            const row = tbody.find(`tr[data-code="${code_article}"]`);
                                            if (row.length) {
                                                row.addClass('completed');
                                            }
                                            const articleItem = articleList.find(`li[data-code="${code_article}"]`);
                                            if (articleItem.length) {
                                                articleItem.addClass('completed');
                                            }
                                        }

                                        // Handle document completion
                                        if (response.scan_data.document_completed) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Document Complété !',
                                                text: `Le ${document_type === 'commande_achat' ? 'Commande Achat' : 'Bon de Livraison'} ${document_numdoc} est entièrement scanné.`,
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                        }

                                        // Handle tour validation
                                        if (response.scan_data.tour_validated) {
                                            let signaturePad;
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Tournée terminée avec succès',
                                                html: `
                                                    <p>La tournée ${response.scan_data.planification_id} a été validée.</p>
                                                    <p>Veuillez fournir la signature du destinataire :</p>
                                                    <canvas id="signature-pad" width="400" height="200"></canvas>
                                                    <div id="signature-error" class="signature-error">Veuillez dessiner une signature.</div>
                                                    <button id="clear-signature" class="btn btn-secondary btn-sm mt-2">Effacer</button>
                                                `,
                                                showConfirmButton: true,
                                                confirmButtonText: 'OK',
                                                showCancelButton: true,
                                                cancelButtonText: 'Annuler',
                                                allowOutsideClick: false,
                                                didOpen: () => {
                                                    const canvas = document.getElementById('signature-pad');
                                                    signaturePad = new SignaturePad(canvas, {
                                                        backgroundColor: 'rgb(255, 255, 255)',
                                                        penColor: 'rgb(0, 0, 0)'
                                                    });
                                                    const confirmButton = Swal.getConfirmButton();
                                                    confirmButton.disabled = true;

                                                    signaturePad.addEventListener('beginStroke', () => {
                                                        confirmButton.disabled = signaturePad.isEmpty();
                                                        document.getElementById('signature-error').style.display = signaturePad.isEmpty() ? 'block' : 'none';
                                                    });
                                                    signaturePad.addEventListener('endStroke', () => {
                                                        confirmButton.disabled = signaturePad.isEmpty();
                                                        document.getElementById('signature-error').style.display = signaturePad.isEmpty() ? 'block' : 'none';
                                                    });

                                                    document.getElementById('clear-signature').addEventListener('click', () => {
                                                        signaturePad.clear();
                                                        confirmButton.disabled = true;
                                                        document.getElementById('signature-error').style.display = 'block';
                                                    });

                                                    if (window.innerWidth <= 576) {
                                                        canvas.width = 280;
                                                        canvas.height = 150;
                                                    }
                                                },
                                                preConfirm: () => {
                                                    if (signaturePad.isEmpty()) {
                                                        document.getElementById('signature-error').style.display = 'block';
                                                        return false;
                                                    }
                                                    return true;
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.reload();
                                                }
                                            });
                                        }
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erreur',
                                            text: response.message,
                                            timer: 2000,
                                            showConfirmButton: false,
                                            position: 'top-end',
                                            toast: true
                                        });
                                        form.find('.scan-input').val('').focus();
                                    }
                                },
                                error: function (xhr) {
                                    let errorMessage = xhr.responseJSON?.message || 'Erreur lors du scan.';
                                    if (xhr.responseJSON?.errors) {
                                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(' ');
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: errorMessage,
                                        timer: 2000,
                                        showConfirmButton: false,
                                        position: 'top-end',
                                        toast: true
                                    });
                                    form.find('.scan-input').val('').focus();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                            form.find('.scan-input').val('').focus();
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'Erreur lors de la validation de l\'article.';
                        if (xhr.responseJSON?.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(' ');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: errorMessage,
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true
                        });
                        form.find('.scan-input').val('').focus();
                    }
                });
            });

            // Auto-focus input when scan section is opened
            $(document).on('shown.bs.collapse', '.accordion-collapse', function () {
                const activeScanSection = $(this).find('.scan-section.active');
                if (activeScanSection.length) {
                    activeScanSection.find('.scan-input').focus();
                }
            });
        });
    </script>
</body>
</html>