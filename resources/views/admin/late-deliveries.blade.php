<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - BL en retard non facturés</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; }
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
</head>
<body>
<div class="wrapper">

    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <div class="logo-header" data-background-color="dark">
                <a href="/" class="logo">
                    <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="70" />
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
                    <li class="nav-item">
                        <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                    </li>
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
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#achats" aria-expanded="false">
                            <i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span>
                        </a>
                        <div class="collapse" id="achats">
                            <ul class="nav nav-collapse">
                                <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                                <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d'Achat</span></a></li>
                                <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                                <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                                <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
                            </ul>
                        </div>
                    </li>
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
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#stock" aria-expanded="false">
                            <i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span>
                        </a>
                        <div class="collapse" id="stock">
                            <ul class="nav nav-collapse">
                                <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                                <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                                <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                            </ul>
                        </div>
                    </li>
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
                    <li class="nav-item">
                        <a href="/contact"><i class="fas fa-headset"></i><p>Assistance</p></a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout.admin') }}" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display:none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->

    <div class="main-panel">
        <!-- Navbar -->
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
                        <li class="nav-item topbar-icon dropdown hidden-caret">
                            <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fas fa-layer-group"></i>
                            </a>
                            <div class="dropdown-menu quick-actions animated fadeIn">
                                <div class="quick-actions-header"><span class="title mb-1">Actions Rapides</span></div>
                                <div class="quick-actions-scroll scrollbar-outer">
                                    <div class="quick-actions-items">
                                        <div class="row m-0">
                                            <a class="col-6 col-md-4 p-0" href="/articles"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fas fa-sitemap"></i></div><span class="text">Articles</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/customers"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-users"></i></div><span class="text">Clients</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/suppliers"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-user-tag"></i></div><span class="text">Fournisseurs</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/delivery_notes/list"><div class="quick-actions-item"><div class="avatar-item bg-danger rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Ventes</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/salesinvoices"><div class="quick-actions-item"><div class="avatar-item bg-warning rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Ventes</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/generalaccounts"><div class="quick-actions-item"><div class="avatar-item bg-info rounded-circle"><i class="fas fa-money-check-alt"></i></div><span class="text">Plan Comptable</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/purchases/list"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Achats</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/invoices"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Achats</span></div></a>
                                            <a class="col-6 col-md-4 p-0" href="/paymentlist"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-credit-card"></i></div><span class="text">Paiements</span></div></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
                                </div>
                                <span class="profile-username"><span class="fw-bold">{{ Auth::user()->name }}</span></span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" /></div>
                                            <div class="u-text">
                                                <h4>{{ Auth::user()->name }}</h4>
                                                <p class="text-muted">{{ Auth::user()->email }}</p>
                                                <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramètres</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout.admin') }}" method="POST" style="display:inline;">
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
        <!-- End Navbar -->

        <div class="container">
            <div class="page-inner">

                <div class="d-flex align-items-center justify-content-between pt-2 pb-4">
                    <div>
                        <h3 class="fw-bold mb-1">
                            <i class="fas fa-bell text-danger me-2"></i>
                            Bons de Livraison en retard non facturés
                        </h3>
                        <p class="text-muted mb-0">Mois précédents — groupés par client</p>
                    </div>
                    <a href="/dashboard" class="btn btn-outline-secondary btn-round">
                        <i class="fas fa-arrow-left me-1"></i> Retour Dashboard
                    </a>
                </div>

                @if($lateDeliveries->isEmpty())
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Aucun bon de livraison en retard non facturé.
                    </div>
                @else
                    {{-- Résumé global --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 text-center py-3"
                                 style="background:linear-gradient(135deg,#fff5f5,#ffe0e0); border-left:4px solid #dc3545 !important;">
                                <h2 class="fw-bold text-danger mb-0">{{ $lateDeliveries->sum('count') }}</h2>
                                <small class="text-muted">BL non facturés</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 text-center py-3"
                                 style="background:linear-gradient(135deg,#fff8e1,#fff3cd); border-left:4px solid #ffc107 !important;">
                                <h2 class="fw-bold text-warning mb-0">{{ $lateDeliveries->count() }}</h2>
                                <small class="text-muted">Clients concernés</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 text-center py-3"
                                 style="background:linear-gradient(135deg,#f0fff4,#d4edda); border-left:4px solid #28a745 !important;">
                                <h2 class="fw-bold text-success mb-0">
                                    {{ number_format($lateDeliveries->sum('total_ttc'), 2, ',', ' ') }} €
                                </h2>
                                <small class="text-muted">Montant total TTC</small>
                            </div>
                        </div>
                    </div>

                    {{-- Tableau par client --}}
                    @foreach($lateDeliveries->sortByDesc('total_ttc') as $group)
                    <div class="card shadow-sm mb-3 border-0">
                        <div class="card-header d-flex justify-content-between align-items-center"
                             style="background:#f8f9fa; cursor:pointer;"
                             data-bs-toggle="collapse"
                             data-bs-target="#client_{{ Str::slug($group['customer_id']) }}">
                            <div>
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                <strong>{{ $group['customer_name'] }}</strong>
                                <span class="badge bg-danger ms-2">{{ $group['count'] }} BL</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-success">
                                    {{ number_format($group['total_ttc'], 2, ',', ' ') }} € TTC
                                </span>
                                <i class="fas fa-chevron-down text-muted"></i>
                            </div>
                        </div>
                        <div class="collapse" id="client_{{ Str::slug($group['customer_id']) }}">
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0" style="font-size:0.88rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° BL</th>
                                            <th>Date</th>
                                            <th class="text-end">Total TTC</th>
                                            <th>Statut</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group['bons'] as $bon)
                                        <tr>
                                            <td><strong>{{ $bon['numdoc'] }}</strong></td>
                                            <td>{{ $bon['delivery_date'] }}</td>
                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($bon['total_ttc'], 2, ',', ' ') }} €
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $bon['status'] === 'en_cours' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($bon['status']) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('delivery_notes.edit', $bon['id']) }}"
                                                   class="btn btn-sm btn-outline-primary btn-round px-3">
                                                    <i class="fas fa-file-invoice me-1"></i> Ouvrir BL
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                <div>by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.</div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
</body>
</html>