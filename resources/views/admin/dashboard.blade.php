
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

<!-- debut condition livreur -->
  @if(Auth::user()->role == 'livreur')
            {{-- Interface spéciale pour livreur --}}
            <div class="text-center mt-5">
                <h3 class="fw-bold mb-4">Bienvenue {{ Auth::user()->name }}</h3>
                <p class="mb-5">Choisissez une option pour continuer :</p>

                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3">
                        <a href="/planification-tournee/planning-chauffeur" class="btn btn-primary btn-lg w-100 py-4">
                            🚚 Ma Journée
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/planification-tournee" class="btn btn-info btn-lg w-100 py-4">
                            🏢 Planning Société
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/planification-tournee/rapport" class="btn btn-success btn-lg w-100 py-4">
                            📊 Rapport & Historique Scan
                        </a>
                    </div>
                </div>
            </div>


            @else


                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Dashboard</h3>
                            <h6 class="op-7 mb-2">Aperçu général des ventes
                            </h6>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">

                        <!-- analytics -->
                                                 <a href="{{ route('analytics') }}" class="btn btn-label-warning btn-round me-2">
        <span class="btn-label"><i class="fas fa-chart-line"></i></span> Analytics
    </a>
                        <!-- analytics -->


                            <a href="/salesinvoices" class="btn btn-label-info btn-round me-2">
                                <span class="btn-label"><i class="fas fa-list"></i></span> Voir Factures Ventes
                            </a>

                             <a href="/delivery_notes/list" class="btn btn-label-info btn-round me-2">
                                <span class="btn-label"><i class="fas fa-list"></i></span> Voir Bons de Livraison
                            </a>

                            <a href="/sales/delivery/create" class="btn btn-primary btn-round">
                                <span class="btn-label"><i class="fas fa-plus"></i></span> Nouvelle Commande
                            </a>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">CA Aujourd’hui</p>
                                                @can('view_sales')
                                                <h4 class="card-title">{{ number_format($todayRevenue, 2, ',', ' ') }} €</h4>
                                                @else Non autorisé 
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">CA Ce Mois</p>
                                                @can('view_sales')
                                                <h4 class="card-title">{{ number_format($monthRevenue, 2, ',', ' ') }} €</h4>
                                                @else Non autorisé 
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                              <a href="/planification-tournee">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Livraisons en Cours</p>
                                                <h4 class="card-title">{{ $pendingDeliveries }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Nouveaux Clients</p>
                                                <h4 class="card-title">{{ $newCustomers->count() }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graphiques -->
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Chiffre d’Affaires (30 derniers jours)</div>
                                        <div class="card-tools">
                                            <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                                <span class="btn-label"><i class="fa fa-download"></i></span> Exporter
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-category">Bons de livraison Expédiés et en cours</div>
                                </div>
                                @can('view_sales')
                                <div class="card-body">
                                    <canvas id="chartCa"></canvas>
                                    
                                </div>
                               @else  --- Non autorisé

                                @endcan
                            </div>
                        </div>


<!-- After the CA chart in the row mt-4 -->
<div class="col-md-4">
    <div class="card card-round">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right">
                <h4 class="card-title">Derniers factures</h4>
                <div class="card-tools">
                    <a href="/salesinvoices" class="btn btn-label-primary btn-round btn-sm">
                        <span class="btn-label"><i class="fa fa-list"></i></span> Voir Tous
                    </a>
                </div>
            </div>
            <!-- <p class="card-category">Derniers 7 factures</p> -->
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Facture</th>
                            <th scope="col" class="text-end">TTC (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvoices as $invoice)
                            <tr>
                                <th scope="row">
                                    <a href="{{ route('salesinvoices.edit', $invoice->id) }}">{{ $invoice->numdoc }}</a> - {{ $invoice->customer->name ?? 'N/A' }} <br> {{ $invoice->created_at }}
                                </th>
                                <td class="text-end">{{ number_format($invoice->total_ttc, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


                        
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title">Top 5 Articles par Quantité Expédié</h4>
                                        <div class="card-tools">
                                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                                <span class="btn-label"><i class="fa fa-download"></i></span> Exporter
                                            </a>
                                        </div>
                                    </div>
                                    <p class="card-category">Articles les plus livrés (bons Expédiés et en cours)</p>
                                </div>
                                <div class="card-body">
                                    <canvas id="topArticlesChart"></canvas>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Bons de Livraison par Statut</div>
                                    </div>
                                    <div class="card-category">Répartition</div>
                                </div>
                                <div class="card-body">
                                    <canvas id="deliveriesByStatusChart"></canvas>
                                   
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title">Top 5 Clients par CA</h4>
                                        <div class="card-tools">
                                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                                <span class="btn-label"><i class="fa fa-download"></i></span> Exporter
                                            </a>
                                        </div>
                                    </div>
                                    <p class="card-category">Clients avec le plus de CA (bons livrés et en cours)</p>
                                </div>
                                <div class="card-body">
                                    <canvas id="topClientsChart"></canvas>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title">Historique Récent des Bons de Livraison</h4>
                                        <div class="card-tools">
                                            <a href="/delivery_notes/list" class="btn btn-label-primary btn-round btn-sm">
                                                <span class="btn-label"><i class="fa fa-list"></i></span> Voir Tous
                                            </a>
                                        </div>
                                    </div>
                                    <p class="card-category">Derniers 7 bons de livraison</p>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Numéro</th>
                                                    <th scope="col" class="text-end">Date</th>
                                                    <th scope="col" class="text-end">Client</th>
                                                    <th scope="col" class="text-end">Montant (€)</th>
                                                    <th scope="col" class="text-end">Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentDeliveries as $delivery)
                                                    <tr>
                                                        <th scope="row">
                                                            <a href="{{ route('delivery_notes.edit', $delivery->id) }}">{{ $delivery->numdoc }}</a>
                                                        </th>
                                                        <td class="text-end">{{ $delivery->delivery_date ? $delivery->delivery_date->format('d/m/Y') : 'N/A' }}</td>
                                                        <td class="text-end">{{ $delivery->customer->name }}</td>
                                                        <td class="text-end">{{ number_format($delivery->total_ttc, 2, ',', ' ') }}</td>
                                                        <td class="text-end">
                                                            <span class="badge bg-{{ $delivery->status === 'en_cours' ? 'warning' : ($delivery->status === 'expédié' ? 'success' : 'danger') }}">
                                                                {{ ucfirst($delivery->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Nouveaux Clients</div>
                                        <div class="card-tools">
                                            <a href="/customers" class="btn btn-icon btn-link btn-primary btn-xs">
                                                <i class="fa fa-users"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-list py-4">
                                        @foreach($newCustomers as $customer)
                                            <div class="item-list">
                                                <div class="avatar">
                                                    <span class="avatar-title rounded-circle border border-white bg-primary">
                                                        {{ substr($customer->name ?? 'N/A', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="info-user ms-3">
                                                    <div class="username">{{ $customer->name ?? 'N/A' }}</div>
                                                    <div class="status">{{ $customer->created_at->format('d/m/Y') }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
@endif
<!-- fin condition livreur -->

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
        $(document).ready(function () {
            $('.select3').select2({
                placeholder: "-- Sélectionner une option --",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                theme: "classic"
            });

            // Initialize DataTables for transaction history
            $('table').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "ordering": true
            });

            // Initialize Chart.js charts
            // Chiffre d’Affaires (30 derniers jours)
            new Chart(document.getElementById('chartCa'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($salesLastMonth)) !!},
                    datasets: [{
                        label: 'CA TTC (€)',
                        data: {!! json_encode(array_values($salesLastMonth)) !!},
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Chiffre d’Affaires (€)'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Bons de Livraison par Statut
            new Chart(document.getElementById('deliveriesByStatusChart'), {
                type: 'pie',
                data: {
                    labels: ['En Cours', 'Expédié', 'Annulé'],
                    datasets: [{
                        data: [
                            {!! json_encode($deliveriesByStatus['en_cours'] ?? 0) !!},
                            {!! json_encode($deliveriesByStatus['Expédié'] ?? 0) !!},
                            {!! json_encode($deliveriesByStatus['annulé'] ?? 0) !!}
                        ],
                        backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                        borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });

            // Top 5 Articles par Quantité Livrée
            new Chart(document.getElementById('topArticlesChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topArticles->pluck('article_code')) !!},
                    datasets: [{
                        label: 'Quantité Livrée',
                        data: {!! json_encode($topArticles->pluck('total_quantity')) !!},
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Article'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Quantité'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Top 5 Clients par CA
            new Chart(document.getElementById('topClientsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topClients->pluck('customer.name')) !!},
                    datasets: [{
                        label: 'CA TTC (€)',
                        data: {!! json_encode($topClients->pluck('total')) !!},
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Client'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Chiffre d’Affaires (€)'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
