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

            <div class="container">
            <div class="page-inner">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Analytics Ventes</h3>
                        <p class="text-muted mb-0">Pilotage en temps réel – CA Net après retours</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <a href="#" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>

                <!-- FILTRE + PÉRIODE -->
                <div class="card card-round mb-4">
                    <div class="card-body py-3">
                        <form method="GET" action="{{ route('analytics') }}" class="row g-2 align-items-center">
                            <div class="col-auto">
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="{{ $start ? $start->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-auto"><strong>→</strong></div>
                            <div class="col-auto">
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="{{ $end ? $end->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                                @if(request()->has('start_date'))
                                    <a href="{{ route('analytics') }}" class="btn btn-outline-secondary btn-sm">Supprimer les filtres</a>
                                @endif
                            </div>
                        </form>

                        @if($start && $end)
                            <div class="mt-2 small text-success">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>Période :</strong> {{ $start->format('d/m/Y') }} → {{ $end->format('d/m/Y') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ONGLETS -->
                <ul class="nav nav-tabs mb-4" id="periodTabs">
                    @php $active = !$start || $period === 'last30' ? 'active' : '' @endphp
                    <li class="nav-item">
                        <a href="{{ route('analytics', ['period' => 'last30']) }}"
                           class="nav-link {{ $active }} {{ request()->has('start_date') ? 'disabled' : '' }}">
                            Derniers 30 jours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('analytics', ['period' => 'thisMonth']) }}"
                           class="nav-link {{ $period === 'thisMonth' ? 'active' : '' }} {{ request()->has('start_date') ? 'disabled' : '' }}">
                            Ce mois
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('analytics', ['period' => 'today']) }}"
                           class="nav-link {{ $period === 'today' ? 'active' : '' }} {{ request()->has('start_date') ? 'disabled' : '' }}">
                            Aujourd'hui
                        </a>
                    </li>
                </ul>

                @if(request()->has('start_date'))
                    <div class="alert alert-warning small py-2 mb-4">
                        <i class="fas fa-info-circle"></i> Les onglets sont désactivés en filtre personnalisé.
                    </div>
                @endif

                <!-- KPI CARDS -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">CA NET</p>
                                        <h4 class="mb-0 text-success fw-bold">
                                            {{ number_format($metrics['caNet'], 0, ',', ' ') }} €
                                        </h4>
                                        <small class="text-success">
                                            @if($caNetPrev > 0)
                                                +{{ round((($metrics['caNet'] - $caNetPrev) / $caNetPrev) * 100, 1) }}% VS période précedente
                                            @else - @endif
                                        </small>
                                    </div>
                                    <div class="avatar avatar-xl">
                                        <div class="avatar-title rounded-circle bg-success text-white kpi-icon">
                                            <i class="fas fa-euro-sign"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">RETOURS</p>
                                        <h4 class="mb-0 text-danger fw-bold">
                                            -{{ number_format($metrics['caRetour'], 0, ',', ' ') }} €
                                        </h4>
                                        <small class="text-danger">
                                            {{ $metrics['caBrut'] > 0 ? round(($metrics['caRetour'] / $metrics['caBrut']) * 100, 1) : 0 }}% du CA brut
                                        </small>
                                    </div>
                                    <div class="avatar avatar-xl">
                                        <div class="avatar-title rounded-circle bg-danger text-white kpi-icon">
                                            <i class="fas fa-undo"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6"> 
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">BONS LIVRÉS</p>
                                        <h4 class="mb-0 text-primary fw-bold">{{ $metrics['nbBl'] }}</h4>
                                        <small class="text-primary">
                                            Panier moyen: {{ number_format($metrics['panierMoyen'], 0, ',', ' ') }} €
                                        </small>
                                    </div>
                                    <div class="avatar avatar-xl">
                                        <div class="avatar-title rounded-circle bg-primary text-white kpi-icon">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">PRÉVISION J+1</p>
                                        <h4 class="mb-0 text-info fw-bold">{{ number_format($forecast, 0, ',', ' ') }} €</h4>
                                        <small class="text-info">+3% vs Moyenne 7j</small>
                                    </div>
                                    <div class="avatar avatar-xl">
                                        <div class="avatar-title rounded-circle bg-info text-white kpi-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRAPHIQUES -->
                <div class="row g-4">

                    <!-- Classement Vendeurs + Top Vendeurs -->
                    <div class="col-lg-6">
                        <div class="card card-round">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Classement Vendeurs</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3">#</th>
                                            <th>Vendeur</th>
                                            <th>CA Net</th>
                                            <th>Retours</th>
                                            <th>Taux</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(auth()->user()->role == "vendeur")
                                            <tr><td colspan="5" class="text-center text-muted py-4">
                                            <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>

                                            </td></tr>
                                        @else
                                            @forelse($returnRateBySeller as $i => $s)
                                                <tr class="{{ $i === 0 ? 'table-success' : '' }}">
                                                    <td class="ps-3"><strong>{{ $i + 1 }}</strong></td>
                                                    <td>{{ $s['vendeur'] }}</td>
                                                    <td>{{ number_format($s['ventes'] - $s['retours'], 0, ',', ' ') }} €</td>
                                                    <td class="text-danger">-{{ number_format($s['retours'], 0, ',', ' ') }} €</td>
                                                    <td>
                                                        <span class="badge badge-xs bg-{{ $s['taux_retour'] > 10 ? 'danger' : ($s['taux_retour'] > 5 ? 'warning' : 'success') }}">
                                                            {{ $s['taux_retour'] }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center py-4">Aucun vendeur</td></tr>
                                            @endforelse
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top Vendeurs</h5>
                                </div>
                                <div class="card-body">
                                     @if(auth()->user()->role != "vendeur")
                                    <div class="chart-container">
                                        <canvas id="sellerChart"></canvas>
                                    </div>
                                    @else
                                    <u>vous n'êtes pas autorisé, merci de contacter votre administrateur</u>
                                    @endif
                                </div>
                            </div>
                        
                    </div>

                    <!-- Évolution CA Net -->
                    <div class="col-12">
                        <div class="card card-round">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Évolution CA Net</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="caNetChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Taux de Retour -->
                    <div class="col-md-8">
                        <div class="card card-round">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Taux de Retour (%)</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="returnRateChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Répartition Statut -->
                    <div class="col-md-4">
                        <div class="card card-round">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Répartition Statut BL</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="chart-container">
                                    <canvas id="statusChart"></canvas>
                                </div>
                                <div class="mt-3">
                                    @foreach($statusRepartition as $label => $count)
                                        <span class="badge me-1 mb-1 bg-{{ $label === 'Expédié' ? 'success' : ($label === 'En cours' ? 'warning' : 'danger') }}">
                                            {{ $label }}: {{ $count }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top 10 Clients -->
                    <div class="col-lg-8">
                        <div class="card card-round">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top 10 Clients</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="topClientsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comparatif 3 mois -->
                    <div class="col-lg-4">
                        <div class="card card-round">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Comparatif 3 mois</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="monthsCaChart"></canvas>
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
                        by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.
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
        const ctx = (id) => document.getElementById(id).getContext('2d');

        new Chart(ctx('caNetChart'), {
            type: 'line',
            data: {
                labels: Object.keys(@json($caNetByDay)),
                datasets: [{
                    label: 'CA Net',
                    data: Object.values(@json($caNetByDay)),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40,167,69,0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        new Chart(ctx('returnRateChart'), {
            type: 'line',
            data: {
                labels: Object.keys(@json($returnRateByDay)),
                datasets: [{
                    label: 'Taux (%)',
                    data: Object.values(@json($returnRateByDay)),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220,53,69,0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        new Chart(ctx('sellerChart'), {
            type: 'bar',
            data: {
                labels: @json($salesBySeller->pluck('vendeur')),
                datasets: [{
                    label: 'CA Net',
                    data: @json($salesBySeller->pluck('ca')),
                    backgroundColor: '#fd7e14'
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        new Chart(ctx('topClientsChart'), {
            type: 'bar',
            data: {
                labels: @json($topClients->pluck('client')),
                datasets: [{
                    label: 'CA Net',
                    data: @json($topClients->pluck('ca')),
                    backgroundColor: '#1d7af3'
                }]
            },
            options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
        });

        new Chart(ctx('statusChart'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($statusRepartition)),
                datasets: [{
                    data: @json(array_values($statusRepartition)),
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        new Chart(ctx('monthsCaChart'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($monthsCa)),
                datasets: [{
                    label: 'CA Net',
                    data: @json(array_values($monthsCa)),
                    backgroundColor: '#28a745'
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    });




    
</script>
</body>
</html>