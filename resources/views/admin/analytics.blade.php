<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ NEGOCE - Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
                                    <li><a href="/sales"><span class="sub-item">Devis & Précommandes</span></a></li>
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

            <div class="container">
<div class="page-inner">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Analytics Ventes</h3>
            <p class="text-muted">Pilotage commercial en temps réel – CA Net après retours</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-sync"></i> Actualiser
            </button>
            <a href="" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Onglets pour les périodes -->
    <ul class="nav nav-tabs mb-4" id="periodTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="last30-tab" data-bs-toggle="tab" data-bs-target="#last30" type="button" role="tab" aria-controls="last30" aria-selected="true">Derniers 30 jours</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="thisMonth-tab" data-bs-toggle="tab" data-bs-target="#thisMonth" type="button" role="tab" aria-controls="thisMonth" aria-selected="false">Ce mois</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="today-tab" data-bs-toggle="tab" data-bs-target="#today" type="button" role="tab" aria-controls="today" aria-selected="false">Aujourd'hui</button>
        </li>
    </ul>

    <div class="tab-content" id="periodTabsContent">
        <!-- Derniers 30 jours -->
        <div class="tab-pane fade show active" id="last30" role="tabpanel" aria-labelledby="last30-tab">
            <!-- KPI CARDS -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">CA NET (30j)</p>
                                    <h4 class="mb-0 text-success fw-bold">
                                        {{ number_format($last30Metrics['caNet'], 0, ',', ' ') }} €
                                    </h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-success">
                                        <i class="fas fa-euro-sign"></i>
                                    </div>
                                </div>
                            </div>
<small class="text-success">
    @if($caNetPrev > 0)
        +{{ round((($last30Metrics['caNet'] - $caNetPrev) / $caNetPrev) * 100, 1) }}%
    @else
        N/A
    @endif
    vs période précédente
</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Retours</p>
                                    <h4 class="mb-0 text-danger fw-bold">
                                        -{{ number_format($last30Metrics['caRetour'], 0, ',', ' ') }} €
                                    </h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-danger">
                                        <i class="fas fa-undo"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-danger">{{ round(($last30Metrics['caRetour'] / $last30Metrics['caBrut']) * 100, 1) }}% du CA brut</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Bons Livrés</p>
                                    <h4 class="mb-0 text-primary fw-bold">{{ $last30Metrics['nbBl'] }}</h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-primary">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>
<small class="text-danger">
    @if($thisMonthMetrics['caBrut'] > 0)
        {{ round(($thisMonthMetrics['caRetour'] / $thisMonthMetrics['caBrut']) * 100, 1) }}%
    @else
        0%
    @endif
    du CA brut
</small>                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Prévision J+1</p>
                                    <h4 class="mb-0 text-info fw-bold">{{ number_format($forecast, 0, ',', ' ') }} €</h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-info">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-info">+3% vs moyenne 7j</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRAPHIQUES -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card"> 
                        <div class="card-header"><div class="card-title">Classement Vendeurs (CA + Retours)</div></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="bg-light">
                                        <tr><th>#</th><th>Vendeur</th><th>CA Net</th><th>Retours</th><th>Taux Retour</th></tr>
                                    </thead>
                                    <tbody>
                                        @if(auth::user()->role == "vendeur") 
                                        <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                                        @else
                                            @forelse($last30ReturnRateBySeller as $index => $s)
                                                <tr class="{{ $index === 0 ? 'table-success' : '' }}">
                                                    <td><strong>{{ $index + 1 }}</strong></td>
                                                    <td>{{ $s['vendeur'] }}</td>
                                                    <td>{{ number_format($s['ventes'] - $s['retours'], 0, ',', ' ') }} €</td>
                                                    <td class="text-danger">-{{ number_format($s['retours'], 0, ',', ' ') }} €</td>
                                                    <td><span class="badge bg-{{ $s['taux_retour'] > 10 ? 'danger' : ($s['taux_retour'] > 5 ? 'warning' : 'success') }}">{{ $s['taux_retour'] }}%</span></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center">Aucun vendeur</td></tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top Vendeurs (CA Net)</div>
                        </div>
                        @if(auth::user()->role == "vendeur") 
                        <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                        @else
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="sellerChartLast30" height="300"></canvas>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Évolution CA Net</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="caNetChartLast30" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Taux de Retour (%)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="returnRateChartLast30" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Répartition Statut BL</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusChartLast30" height="300"></canvas>
                            </div>
                            <div class="mt-3 text-center">
                                @foreach($last30StatusRepartition as $label => $count)
                                    <span class="badge bg-{{ $label === 'Expédié' ? 'success' : ($label === 'En cours' ? 'warning' : 'danger') }} me-2">
                                        {{ $label }}: {{ $count }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top 10 Clients (CA Net)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="topClientsChartLast30" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ce mois -->
        <div class="tab-pane fade" id="thisMonth" role="tabpanel" aria-labelledby="thisMonth-tab">
            <!-- KPI CARDS -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">CA NET (Ce mois)</p>
                                    <h4 class="mb-0 text-success fw-bold">
                                        {{ number_format($thisMonthMetrics['caNet'], 0, ',', ' ') }} €
                                    </h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-success">
                                        <i class="fas fa-euro-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-success">
                                N/A vs mois précédent <!-- À calculer si nécessaire -->
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Retours</p>
                                    <h4 class="mb-0 text-danger fw-bold">
                                        -{{ number_format($thisMonthMetrics['caRetour'], 0, ',', ' ') }} €
                                    </h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-danger">
                                        <i class="fas fa-undo"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-danger">{{ round(($thisMonthMetrics['caRetour'] / $thisMonthMetrics['caBrut']) * 100, 1) }}% du CA brut</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Bons Livrés</p>
                                    <h4 class="mb-0 text-primary fw-bold">{{ $thisMonthMetrics['nbBl'] }}</h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-primary">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-primary">Panier moyen: {{ number_format($thisMonthMetrics['panierMoyen'], 0, ',', ' ') }} €</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Prévision J+1</p>
                                    <h4 class="mb-0 text-info fw-bold">{{ number_format($forecast, 0, ',', ' ') }} €</h4> <!-- Gardée globale -->
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-info">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-info">+3% vs moyenne 7j</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRAPHIQUES similaires -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card"> 
                        <div class="card-header"><div class="card-title">Classement Vendeurs (CA + Retours)</div></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="bg-light">
                                        <tr><th>#</th><th>Vendeur</th><th>CA Net</th><th>Retours</th><th>Taux Retour</th></tr>
                                    </thead>
                                    <tbody>
                                        @if(auth::user()->role == "vendeur") 
                                        <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                                        @else
                                            @forelse($thisMonthReturnRateBySeller as $index => $s)
                                                <tr class="{{ $index === 0 ? 'table-success' : '' }}">
                                                    <td><strong>{{ $index + 1 }}</strong></td>
                                                    <td>{{ $s['vendeur'] }}</td>
                                                    <td>{{ number_format($s['ventes'] - $s['retours'], 0, ',', ' ') }} €</td>
                                                    <td class="text-danger">-{{ number_format($s['retours'], 0, ',', ' ') }} €</td>
                                                    <td><span class="badge bg-{{ $s['taux_retour'] > 10 ? 'danger' : ($s['taux_retour'] > 5 ? 'warning' : 'success') }}">{{ $s['taux_retour'] }}%</span></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center">Aucun vendeur</td></tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top Vendeurs (CA Net)</div>
                        </div>
                        @if(auth::user()->role == "vendeur") 
                        <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                        @else
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="sellerChartThisMonth" height="300"></canvas>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Évolution CA Net</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="caNetChartThisMonth" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Taux de Retour (%)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="returnRateChartThisMonth" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Répartition Statut BL</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusChartThisMonth" height="300"></canvas>
                            </div>
                            <div class="mt-3 text-center">
                                @foreach($thisMonthStatusRepartition as $label => $count)
                                    <span class="badge bg-{{ $label === 'Expédié' ? 'success' : ($label === 'En cours' ? 'warning' : 'danger') }} me-2">
                                        {{ $label }}: {{ $count }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top 10 Clients (CA Net)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="topClientsChartThisMonth" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aujourd'hui -->
        <div class="tab-pane fade" id="today" role="tabpanel" aria-labelledby="today-tab">
            <!-- KPI CARDS -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">CA NET (Aujourd'hui)</p>
                                    <h4 class="mb-0 text-success fw-bold">
                                        {{ number_format($todayMetrics['caNet'], 0, ',', ' ') }} €
                                    </h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-success">
                                        <i class="fas fa-euro-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-success">
                                N/A vs hier <!-- À calculer si nécessaire -->
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Retours</p>
                                    <h4 class="mb-0 text-danger fw-bold">
                                        -{{ number_format($todayMetrics['caRetour'], 0, ',', ' ') }} €
                                    </h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-danger">
                                        <i class="fas fa-undo"></i>
                                    </div>
                                </div>
                            </div>
<small class="text-danger">
    @if($todayMetrics['caBrut'] > 0)
        {{ round(($todayMetrics['caRetour'] / $todayMetrics['caBrut']) * 100, 1) }}%
    @else
        0%
    @endif
    du CA brut
</small>                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Bons Livrés</p>
                                    <h4 class="mb-0 text-primary fw-bold">{{ $todayMetrics['nbBl'] }}</h4>
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-primary">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-primary">Panier moyen: {{ number_format($todayMetrics['panierMoyen'], 0, ',', ' ') }} €</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Prévision J+1</p>
                                    <h4 class="mb-0 text-info fw-bold">{{ number_format($forecast, 0, ',', ' ') }} €</h4> <!-- Gardée globale -->
                                </div>
                                <div class="avatar avatar-xl">
                                    <div class="avatar-title rounded-circle bg-info">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="text-info">+3% vs moyenne 7j</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRAPHIQUES (simplifiés pour aujourd'hui, car un seul jour) -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card"> 
                        <div class="card-header"><div class="card-title">Classement Vendeurs (CA + Retours)</div></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="bg-light">
                                        <tr><th>#</th><th>Vendeur</th><th>CA Net</th><th>Retours</th><th>Taux Retour</th></tr>
                                    </thead>
                                    <tbody>
                                        @if(auth::user()->role == "vendeur") 
                                        <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                                        @else
                                            @forelse($todayReturnRateBySeller as $index => $s)
                                                <tr class="{{ $index === 0 ? 'table-success' : '' }}">
                                                    <td><strong>{{ $index + 1 }}</strong></td>
                                                    <td>{{ $s['vendeur'] }}</td>
                                                    <td>{{ number_format($s['ventes'] - $s['retours'], 0, ',', ' ') }} €</td>
                                                    <td class="text-danger">-{{ number_format($s['retours'], 0, ',', ' ') }} €</td>
                                                    <td><span class="badge bg-{{ $s['taux_retour'] > 10 ? 'danger' : ($s['taux_retour'] > 5 ? 'warning' : 'success') }}">{{ $s['taux_retour'] }}%</span></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center">Aucun vendeur</td></tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top Vendeurs (CA Net)</div>
                        </div>
                        @if(auth::user()->role == "vendeur") 
                        <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                        @else
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="sellerChartToday" height="300"></canvas>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Évolution CA Net (un seul jour)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="caNetChartToday" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Taux de Retour (%)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="returnRateChartToday" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Répartition Statut BL</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusChartToday" height="300"></canvas>
                            </div>
                            <div class="mt-3 text-center">
                                @foreach($todayStatusRepartition as $label => $count)
                                    <span class="badge bg-{{ $label === 'Expédié' ? 'success' : ($label === 'En cours' ? 'warning' : 'danger') }} me-2">
                                        {{ $label }}: {{ $count }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top 10 Clients (CA Net)</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="topClientsChartToday" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparatif des 3 derniers mois (global, hors onglets) -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Comparatif CA Net (3 derniers mois)</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthsCaChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        © AZ NEGOCE. All Rights Reserved.
                    </div>
                    <div>
                        by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fonction générique pour créer un chart line
    function createLineChart(ctxId, labels, data, label, borderColor, backgroundColor) {
        const ctx = document.getElementById(ctxId).getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: borderColor,
                    backgroundColor: backgroundColor,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return label + ': ' + context.formattedValue + (label.includes('%') ? '%' : ' €');
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, max: label.includes('%') ? 100 : undefined }
                }
            }
        });
    }

    // Fonction générique pour bar chart
    function createBarChart(ctxId, labels, data, label, backgroundColor, indexAxis = 'x') {
        const ctx = document.getElementById(ctxId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: backgroundColor
                }]
            },
            options: {
                indexAxis: indexAxis,
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }

    // Fonction générique pour doughnut chart
    function createDoughnutChart(ctxId, labels, data) {
        const ctx = document.getElementById(ctxId).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return `${context.label}: ${context.parsed} BL (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Derniers 30 jours
    createLineChart('caNetChartLast30', @json(array_keys($last30CaNetByDay)), @json(array_values($last30CaNetByDay)), 'CA Net (€)', '#28a745', 'rgba(40, 167, 69, 0.1)');
    createBarChart('topClientsChartLast30', @json($last30TopClients->pluck('client')), @json($last30TopClients->pluck('ca')), 'CA Net (€)', '#1d7af3', 'y');
    createDoughnutChart('statusChartLast30', @json(array_keys($last30StatusRepartition)), @json(array_values($last30StatusRepartition)));
    createBarChart('sellerChartLast30', @json($last30SalesBySeller->pluck('vendeur')), @json($last30SalesBySeller->pluck('ca')), 'CA Net (€)', '#fd7e14');
    createLineChart('returnRateChartLast30', @json(array_keys($last30ReturnRateByDay)), @json(array_values($last30ReturnRateByDay)), 'Taux de retour (%)', '#dc3545', 'rgba(220, 53, 69, 0.1)');

    // Ce mois
    createLineChart('caNetChartThisMonth', @json(array_keys($thisMonthCaNetByDay)), @json(array_values($thisMonthCaNetByDay)), 'CA Net (€)', '#28a745', 'rgba(40, 167, 69, 0.1)');
    createBarChart('topClientsChartThisMonth', @json($thisMonthTopClients->pluck('client')), @json($thisMonthTopClients->pluck('ca')), 'CA Net (€)', '#1d7af3', 'y');
    createDoughnutChart('statusChartThisMonth', @json(array_keys($thisMonthStatusRepartition)), @json(array_values($thisMonthStatusRepartition)));
    createBarChart('sellerChartThisMonth', @json($thisMonthSalesBySeller->pluck('vendeur')), @json($thisMonthSalesBySeller->pluck('ca')), 'CA Net (€)', '#fd7e14');
    createLineChart('returnRateChartThisMonth', @json(array_keys($thisMonthReturnRateByDay)), @json(array_values($thisMonthReturnRateByDay)), 'Taux de retour (%)', '#dc3545', 'rgba(220, 53, 69, 0.1)');

    // Aujourd'hui
    createLineChart('caNetChartToday', @json(array_keys($todayCaNetByDay)), @json(array_values($todayCaNetByDay)), 'CA Net (€)', '#28a745', 'rgba(40, 167, 69, 0.1)');
    createBarChart('topClientsChartToday', @json($todayTopClients->pluck('client')), @json($todayTopClients->pluck('ca')), 'CA Net (€)', '#1d7af3', 'y');
    createDoughnutChart('statusChartToday', @json(array_keys($todayStatusRepartition)), @json(array_values($todayStatusRepartition)));
    createBarChart('sellerChartToday', @json($todaySalesBySeller->pluck('vendeur')), @json($todaySalesBySeller->pluck('ca')), 'CA Net (€)', '#fd7e14');
    createLineChart('returnRateChartToday', @json(array_keys($todayReturnRateByDay)), @json(array_values($todayReturnRateByDay)), 'Taux de retour (%)', '#dc3545', 'rgba(220, 53, 69, 0.1)');

    // Comparatif 3 mois
    createBarChart('monthsCaChart', @json(array_keys($monthsCa)), @json(array_values($monthsCa)), 'CA Net (€)', '#28a745');
});
</script>
</body>
</html>