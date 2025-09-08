
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
                    <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                </div>
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item active">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>
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
         
                                                <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
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
                            <a href="/delivery_notes/list" class="btn btn-label-info btn-round me-2">
                                <span class="btn-label"><i class="fas fa-list"></i></span> Voir Bons de Livraison
                            </a>
                            <a href="/sales/delivery/create" class="btn btn-primary btn-round">
                                <span class="btn-label"><i class="fas fa-plus"></i></span> Nouveau Bon de Livraison
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
                        <div class="col-md-12">
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
