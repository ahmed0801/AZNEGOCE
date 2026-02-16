<!-- resources/views/admin/customer-behavior.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Analyse Comportement Clients - AZ ERP</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .tooltip-inner { max-width: 320px; text-align: left; }
        .info-icon { cursor: help; opacity: 0.7; }
        .info-icon:hover { opacity: 1; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <div class="logo-header" data-background-color="dark">
                    <a href="/" class="logo">
                        <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="40" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                        <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                    </div>
                </div>
            </div>

            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>
@if(Auth::user()->role != 'livreur')
                        <!-- Ventes --> 
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#ventes" aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i><p>Ventes</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="ventes">
                                <ul class="nav nav-collapse">
                                    <li><a href="/sales/delivery/create"><span class="sub-item">Nouvelle Commande</span></a></li>
                                    <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
                                    <li><a href="/delivery_notes/list"><span class="sub-item">Bons de Livraison</span></a></li>
                                    <li><a href="/delivery_notes/returns/list"><span class="sub-item">Retours Vente</span></a></li>
                                    <li><a href="/salesinvoices"><span class="sub-item">Factures</span></a></li>
                                    <li><a href="/salesnotes/list"><span class="sub-item">Avoirs</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Achats -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#achats" aria-expanded="false">
                                <i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="achats">
                                <ul class="nav nav-collapse">
                                    <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                                    <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d’Achat</span></a></li>
                                    <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                                    <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                                    <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Comptabilité -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#compta" aria-expanded="false">
                                <i class="fas fa-balance-scale"></i><p>Comptabilité</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="compta">
                                <ul class="nav nav-collapse">
                                    <li><a href="{{ route('generalaccounts.index') }}"><span class="sub-item">Plan Comptable</span></a></li>
                                    <li><a href="{{ route('payments.index') }}"><span class="sub-item">Règlements</span></a></li>
                                </ul>
                            </div>
                        </li>
 @endif
                        <!-- Stock -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#stock" aria-expanded="false">
                                <i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="stock">
                                <ul class="nav nav-collapse">
                                    @if(Auth::user()->role != 'livreur')
                                    <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                                    <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                                    @endif
                                    <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                                </ul>
                            </div>
                        </li>
@if(Auth::user()->role != 'livreur')
                        <!-- Référentiel -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#referentiel" aria-expanded="false">
                                <i class="fas fa-users"></i><p>Référentiel</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="referentiel">
                                <ul class="nav nav-collapse">
                                    <li><a href="/customers"><span class="sub-item">Clients</span></a></li>
                                    <li><a href="/suppliers"><span class="sub-item">Fournisseurs</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Paramètres -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#parametres" aria-expanded="false">
                                <i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="parametres">
                                <ul class="nav nav-collapse">
                                    <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Outils -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#outils" aria-expanded="false">
                                <i class="fab fa-skyatlas"></i><p>Outils</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="outils">
                                <ul class="nav nav-collapse">
                                    <li><a href="/analytics"><span class="sub-item">Analytics</span></a></li>
                                    <li><a href="/tecdoc"><span class="sub-item">TecDoc</span></a></li>
                                    <li><a href="/voice"><span class="sub-item">NEGOBOT</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Assistance -->
<li class="nav-item">
    <a href="/contact">
        <i class="fas fa-headset"></i>
        <p>Assistance</p>
    </a>
</li>
@endif

                        <!-- Déconnexion -->
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" class="nav-link"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="20" />
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


                              <!-- test quick action  -->
<li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Actions Rapides</span>
                      <!-- <span class="subtitle op-7">Liens Utiles</span> -->
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">

                                                  <a class="col-6 col-md-4 p-0" href="/articles">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-sitemap"></i>
                              </div>
                              <span class="text">Articles</span>
                            </div>
                          </a>

                                                                            <a class="col-6 col-md-4 p-0" href="/customers">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-users"></i>
                              </div>
                              <span class="text">Clients</span>
                            </div>
                          </a>


                                                                                                      <a class="col-6 col-md-4 p-0" href="/suppliers">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-user-tag"></i>
                              </div>
                              <span class="text">Fournisseurs</span>
                            </div>
                          </a>



                          <a class="col-6 col-md-4 p-0" href="/delivery_notes/list">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="fa fa-cart-plus"></i>
                              </div>
                              <span class="text">Commandes Ventes</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/salesinvoices">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Factures Ventes</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/generalaccounts">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-money-check-alt"></i>
                              </div>
                              <span class="text">Plan Comptable</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/purchases/list">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fa fa-cart-plus"></i>
                              </div>
                              <span class="text">Commandes Achats</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="/invoices">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Factures Achats</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/paymentlist">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Paiements</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                        <!-- fin test quick action  -->


                        
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
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
                                                    <img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" />
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

            <!-- Contenu principal -->
            <div class="container">
                <div class="page-inner">

                    <!-- En-tête -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">Analyse Comportement Clients

<a href="{{ route('analytics') }}" class="btn btn-label-warning btn-round me-2">
        <span class="btn-label"><i class="fas fa-chart-line"></i></span> Analytics
    </a>

        <a href="/dashboard" class="btn btn-label-secondary btn-round me-2">
        <span class="btn-label"></span>Dashboard
    </a>

    <a href="/customers" class="btn btn-label-primary btn-round me-2">
        <span class="btn-label"></span>Page Clients
    </a>


                            </h3>
                            <p class="text-muted mb-0">Segmentation RFM, indicateurs clés & recommandations stratégiques</p>
                        </div>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
                                <i class="fas fa-sync-alt"></i> Actualiser
                            </button>
                        </div>
                    </div>

                    <!-- Graphiques principaux -->
                    <div class="row g-4 mb-4">
                        <div class="col-lg-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                        Segmentation RFM
                                        <span class="info-icon text-info" 
                                              data-bs-toggle="tooltip" 
                                              data-bs-placement="top" 
                                              title="Segmentation basée sur la Récence (dernier achat), Fréquence (nombre d’achats) et Montant dépensé. Ici simplifiée en 4 catégories.">
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 280px;">
                                        <canvas id="segmentsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Répartition par Type de Client</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 280px;">
                                        <canvas id="typesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPIs avec tooltips -->
                    <div class="row g-3 mb-4">
                        @php
                            $kpis = [
                                ['title' => 'Total Clients', 'value' => number_format($totalCustomers), 'icon' => 'users', 'color' => 'primary'],
                                ['title' => 'Clients Actifs', 'value' => number_format($activeCustomers), 'icon' => 'user-check', 'color' => 'success'],
                                ['title' => 'Solde Moyen', 'value' => number_format($avgSolde ?? 0, 2) . ' €', 'icon' => 'balance-scale', 'color' => 'info'],
                                ['title' => 'Avec Véhicules', 'value' => number_format($withVehicles) . ' (' . ($totalCustomers > 0 ? round(($withVehicles / $totalCustomers) * 100, 1) : 0) . '%)', 'icon' => 'car', 'color' => 'warning'],
                                ['title' => 'Panier Moyen', 'value' => number_format($avgOrderValue ?? 0, 2) . ' €', 'icon' => 'shopping-cart', 'color' => 'primary'],
                                ['title' => 'Taux Rétention', 'value' => round($retentionRate ?? 0, 1) . '%', 'icon' => 'redo', 'color' => 'success'],
                                ['title' => 'Taux Churn', 'value' => round($churnRate ?? 0, 1) . '%', 'icon' => 'user-times', 'color' => 'danger'],
                                ['title' => 'Fréquence Achat', 'value' => round($purchaseFrequency ?? 0, 2), 'icon' => 'repeat', 'color' => 'info'],
                                ['title' => 'Taux Repeat', 'value' => round($repeatPurchaseRate ?? 0, 1) . '%', 'icon' => 'sync', 'color' => 'warning'],
                                ['title' => 'Délai entre Achats', 'value' => round($avgTimeBetweenPurchases ?? 0, 1) . ' j', 'icon' => 'clock', 'color' => 'secondary'],
                                ['title' => 'CLV Estimé', 'value' => number_format($clv ?? 0, 0) . ' €', 'icon' => 'euro-sign', 'color' => 'success'],
                                ['title' => 'Récence Moyenne', 'value' => round($avgRecency ?? 0, 1) . ' j', 'icon' => 'calendar-alt', 'color' => 'primary'],
                            ];

                            $kpiExplanations = [
                                'Total Clients'          => 'Nombre total de clients enregistrés dans la base, qu’ils aient déjà acheté ou non.',
                                'Clients Actifs'         => 'Clients non bloqués (champ "blocked" = 0). Ce sont ceux qui peuvent théoriquement passer commande.',
                                'Solde Moyen'            => 'Moyenne des soldes clients. Positif = nous devons au client, négatif = le client nous doit.',
                                'Avec Véhicules'         => 'Pourcentage de clients ayant au moins un véhicule associé (utile pour personnalisation pièces auto).',
                                'Panier Moyen'           => 'Valeur moyenne d’une commande (AOV = Average Order Value). Plus élevé = clients dépensent plus par achat.',
                                'Taux Rétention'         => 'Pourcentage de clients ayant acheté dans les 30 derniers jours (clients "actifs" dans la segmentation RFM).',
                                'Taux Churn'             => 'Pourcentage de clients perdus : aucun achat depuis plus de 90 jours (segment "Perdus").',
                                'Fréquence Achat'        => 'Nombre moyen de commandes par client ayant déjà acheté au moins une fois.',
                                'Taux Repeat'            => 'Pourcentage de clients qui ont passé plus d’une commande dans leur historique.',
                                'Délai entre Achats'     => 'Nombre moyen de jours entre deux achats pour les clients multi-achats.',
                                'CLV Estimé'             => 'Customer Lifetime Value : estimation de la valeur totale qu’un client moyen va générer sur sa durée de vie (approximation).',
                                'Récence Moyenne'        => 'Nombre moyen de jours écoulés depuis le dernier achat pour les clients ayant déjà commandé.',
                            ];
                        @endphp

                        @foreach ($kpis as $kpi)
                            <div class="col-md-3 col-sm-6">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div style="flex: 1;">
                                                <p class="text-muted mb-1 small d-flex align-items-center gap-2">
                                                    {{ $kpi['title'] }}
                                                    <span class="info-icon text-info"
                                                          data-bs-toggle="tooltip"
                                                          data-bs-placement="top"
                                                          title="{{ $kpiExplanations[$kpi['title']] ?? 'Aucune description disponible.' }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </span>
                                                </p>
                                                <h4 class="mb-0 fw-bold text-{{ $kpi['color'] }}">
                                                    {{ $kpi['value'] }}
                                                </h4>
                                            </div>
                                            <div class="avatar avatar-xl">
                                                <div class="avatar-title rounded-circle bg-{{ $kpi['color'] }} text-white">
                                                    <i class="fas fa-{{ $kpi['icon'] }}"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Répartition Villes + Top Soldes -->
                    <div class="row g-4 mb-4">
                        <div class="col-lg-8">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                        Répartition Géographique (Top 10 Villes)
                                        <span class="info-icon text-info" 
                                              data-bs-toggle="tooltip" 
                                              data-bs-placement="top" 
                                              title="Nombre de clients par ville. Permet d’identifier les zones de forte concentration.">
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 320px;">
                                        <canvas id="citiesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top 5 Soldes Clients</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Client</th>
                                                    <th class="text-end">Solde</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topSolde as $client)
                                                    <tr>
                                                        <td>{{ $client->name }}</td>
                                                        <td class="text-end {{ $client->solde >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ number_format($client->solde, 2, ',', ' ') }} €
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="2" class="text-center py-4">Aucun client</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conseils & Recommandations -->
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Conseils Stratégiques</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @forelse($advice as $item)
                                            <li class="list-group-item">{{ $item }}</li>
                                        @empty
                                            <li class="list-group-item text-muted">Aucun conseil particulier pour le moment</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recommandations</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @forelse($recommendations as $item)
                                            <li class="list-group-item">{{ $item }}</li>
                                        @empty
                                            <li class="list-group-item text-muted">Aucune recommandation pour le moment</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        © AZ NEGOCE. All Rights Reserved.
                    </div>
                    <div>
                        by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <!-- Activation des tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialisation des tooltips Bootstrap
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].forEach(el => new bootstrap.Tooltip(el, {
                html: true,
                boundary: 'window'
            }));

            // Charts
            const ctx = id => document.getElementById(id)?.getContext('2d');
            if (!ctx) return;

            if (ctx('segmentsChart')) {
                new Chart(ctx('segmentsChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Nouveaux', 'Actifs', 'À Risque', 'Perdus'],
                        datasets: [{
                            data: [{{ $segments['new'] }}, {{ $segments['active'] }}, {{ $segments['at_risk'] }}, {{ $segments['lost'] }}],
                            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            if (ctx('typesChart')) {
                new Chart(ctx('typesChart'), {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($types->pluck('type')->toArray()) !!},
                        datasets: [{
                            data: {!! json_encode($types->pluck('count')->toArray()) !!},
                            backgroundColor: ['#6f42c1', '#fd7e14', '#20c997', '#17a2b8'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            if (ctx('citiesChart')) {
                new Chart(ctx('citiesChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($cities->pluck('city')->toArray()) !!},
                        datasets: [{
                            label: 'Nombre de clients',
                            data: {!! json_encode($cities->pluck('count')->toArray()) !!},
                            backgroundColor: '#1d7af3',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
</body>
</html>