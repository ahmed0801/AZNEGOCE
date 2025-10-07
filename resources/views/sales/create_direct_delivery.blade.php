<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Créer un bon de livraison</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and Icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .card {
            border-radius: 8px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 8px 8px 0 0;
        }
        .card h3 {
            font-size: 1.6rem;
            color: #007bff;
            font-weight: 600;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-info {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .btn-danger:hover {
            background-color: #c82333;
            box-shadow: 0 4px 10px rgba(200, 35, 51, 0.3);
        }
        .btn-warning:hover {
            background-color: #e0a800;
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        .btn-outline-info:hover {
            background-color: #17a2b8;
            color: #fff;
            box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3);
        }
        .table {
            width: 100%;
            margin-bottom: 0;
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
        }
        .table th, .table td {
            text-align: left;
            vertical-align: middle;
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        .table thead {
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .table-responsive {
            max-height: 350px;
            overflow-y: auto;
        }
        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.4rem;
            font-size: 0.9rem;
            min-width: 80px;
        }
        .form-control.quantity, .form-control.unit_price_ht, .form-control.remise {
            width: 100px;
            font-size: 0.9rem;
        }
        .form-control.order_date {
            width: 150px;
            font-size: 0.85rem;
            padding: 0.3rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.8rem;
        }
        .form-label {
            font-weight: 600;
            color: #343a40;
            font-size: 0.9rem;
        }
        #customer_details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 1rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        #search_results {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 180px;
            overflow-y: auto;
        }
        #search_results div:hover {
            background: #e9ecef;
            cursor: pointer;
        }
        .total-display {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 0.8rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .table-text-small th,
        .table-text-small td,
        .table-text-small input,
        .table-text-small button,
        .table-text-small span,
        .table-text-small svg {
            font-size: 0.8rem !important;
        }
        .table-text-small th {
            font-size: 0.75rem !important;
        }
        .badge-very-sm {
            font-size: 0.5rem;
            padding: 0.1em 0.2em;
            vertical-align: middle;
        }
        .modal-md {
            max-width: 800px;
        }
        @media (max-width: 768px) {
            .table-responsive {
                max-height: none;
            }
            .table th, .table td {
                font-size: 0.75rem;
                padding: 0.4rem;
            }
            .form-control.quantity, .form-control.unit_price_ht, .form-control.remise {
                width: 80px;
                font-size: 0.8rem;
            }
            .form-control.order_date {
                width: 100%;
                font-size: 0.8rem;
            }
            .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-info {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
            .card-body {
                padding: 1rem;
            }
            .modal-md {
                max-width: 90%;
            }
        }
        .small-text {
            font-size: 0.95em;
        }
        #balanceSummary .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #balanceSummary .card-title {
            font-size: 1.1rem;
            font-weight: bold;
        }
        .balance-btn {
            min-width: 100px;
        }
        .select2-results__option--disabled {
            color: #999 !important;
            background-color: #f8f9fa !important;
            cursor: not-allowed;
        }
        .select2-container--default .select2-selection--single.vehicle-empty .select2-selection__rendered {
            color: #999;
            font-style: italic;
        }
        #vehicle_group {
            display: none;
        }
        #vehicle_id.select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            height: 34px;
            line-height: 34px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
        }
        .select2-results__option--all-customers {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- masquer navbar avec sidebar_minimize -->
    <div class="wrapper sidebar_minimize">
        
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

                <!-- Stock -->
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
                                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
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
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramétres</a>
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
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{!! session('error') !!}</div>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Créer un Document de Vente</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sales.delivery.store') }}" method="POST" id="salesForm">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-4 col-12 mb-2">
<a href="/newcustomer"
   onclick="window.open(this.href, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); return false;"
   class="btn btn-outline-secondary btn-sm px-2 py-1" style="font-size: 0.75rem;">
  Créer & Modifier Client <i class="fas fa-plus-circle ms-1"></i>
</a>

                                                                                    
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            <option value="" disabled selected>Sélectionner un client</option>
                                            <option value="%%%" data-select2-id="all-customers">Récupérer tous les clients</option>
                                        </select>
                                        <input type="hidden" name="tva_rate" id="tva_rate" value="0">
                                    </div>
                                    <div class="col-md-5 col-12 mb-2" id="vehicle_group">
                                        <label class="form-label">Véhicule</label>
                                        <div class="input-group">
                                            <select name="vehicle_id" id="vehicle_id" class="form-control select2">
                                                <option value="" disabled selected>Sélectionner un véhicule</option>
                                            </select>
                                            <div class="input-group-append">
                                                <hr>
                                                <a id="loadCatalogBtn" class="btn btn-outline-primary btn-sm px-2 py-1" style="font-size: 0.90rem;" disabled>
                                                    <i class="fas fa-list"></i> Charger le Catalogue
                                                </a>
                                                <a id="viewHistoryBtn" class="btn btn-outline-secondary btn-sm px-2 py-1" style="font-size: 0.90rem;" disabled>
                                                    <i class="fas fa-history"></i> Voir l'historique
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12 mb-2">
                                        <label class="form-label">Date de commande</label>
                                        <input type="date" name="order_date" id="order_date" value="{{ now()->format('Y-m-d') }}" class="form-control order_date" required>
                                    </div>
                                </div>
                                <div class="mb-3" id="customer_details" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Client:</strong> <span id="customer_code"></span> <span id="customer_name"></span></p>
                                            <p><strong>Taux TVA:</strong> <span id="customer_tva"></span>%</p>
                                            <p><strong>Email:</strong> <span id="customer_email"></span></p>
                                            <p><strong>Téléphones :</strong> <span id="customer_phone1"></span> / <span id="customer_phone2"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Adresse:</strong> <span id="customer_address"></span></p>
                                            <p><strong>Adresse de livraison:</strong> <span id="customer_address_delivery"></span></p>
                                            <p><strong>Ville & Pays:</strong> <span id="customer_city"></span>, <span id="customer_country"></span></p>
                                            <p>
                                                <strong>Solde:</strong>
                                                <button type="button" class="btn btn-outline-info btn-sm balance-btn" id="balanceBtn" data-customer-id="" data-customer-name="" disabled>
                                                    <i class="fas fa-balance-scale me-1"></i> <span id="customer_balance">0,00 €</span>
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rechercher un article</label>
                                    <input type="text" id="search_item" class="form-control" placeholder="Par code, nom, description ou barcode">
                                    <div id="search_results" class="mt-2"></div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="font-weight-bold mb-2">Lignes de commande</h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-text-small" id="lines_table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Réference</th>
                                                    <th>Désignation</th>
                                                    <th>Prix A.HT</th>
                                                    <th>Prix V.HT</th>
                                                    <th>Stock</th>
                                                    <th>Qté</th>
                                                    <th>PU HT</th>
                                                    <th>Remise %</th>
                                                    <th>Total HT</th>
                                                    <th>Total TTC</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="lines_body"></tbody>
                                        </table>
                                    </div>
                                    <div class="total-display mt-2 text-end">
                                        <h5 class="mb-1">Total HT : <span id="total_ht_global" class="text-success fw-bold">0,00</span> €</h5>
                                        <h6 class="mb-0">Total TTC : <span id="total_ttc_global" class="text-danger fw-bold">0,00</span> €</h6>
                                    </div>
                                    <a href="/articles" target="_blank" type="button" class="btn btn-outline-secondary btn-sm mt-2">+ Aller a la Page Articles</a>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes / Commentaire</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de livraison, etc."></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="action" value="validate" class="btn btn-success px-3 ms-2">✔️ Valider BL</button>
                                    <button type="submit" name="action" value="validate_and_invoice" class="btn btn-primary px-3 ms-2">📄 Valider et Facturer</button>
                                    <button type="submit" name="action" value="save_draft" class="btn btn-warning px-3 ms-2">📝 Enregistrer Devis</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Details Modal -->
            <div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-labelledby="stockDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="stockDetailsModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6><i class="fas fa-warehouse me-1"></i> Quantité actuelle par magasin :</h6>
                            <table class="table table-bordered table-sm" id="stockTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Magasin</th>
                                        <th>Quantité</th>
                                        <th>Dernière mise à jour</th>
                                    </tr>
                                </thead>
                                <tbody id="stockTableBody"></tbody>
                            </table>
                            <hr>
                            <h6><i class="fas fa-exchange-alt me-1"></i> Mouvements de stock récents :</h6>
                            <table class="table table-bordered table-sm" id="movementTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>QTE</th>
                                        <th>Magasin</th>
                                        <th>Prix.HT</th>
                                        <th>Source</th>
                                        <th>Référence</th>
                                    </tr>
                                </thead>
                                <tbody id="movementTableBody"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounting Entries Modal -->
            <div class="modal fade accounting-modal" id="accountingModal" tabindex="-1" aria-labelledby="accountingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="accountingModalLabel">Historique des écritures comptables</h5>
                            <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="showBalance()">
                                <i class="fas fa-balance-scale me-1"></i> Balance
                            </button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div id="balanceSummary" class="card mb-3" style="display: none;">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">Balance Comptable</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Total Débits</th>
                                                <th>Total Crédits</th>
                                                <th>Solde Net</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="debits">0,00 €</td>
                                                <td id="credits">0,00 €</td>
                                                <td id="balance">0,00 €</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <form id="accountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                    <option value="">Type (Tous)</option>
                                    <option value="Factures">Factures</option>
                                    <option value="Avoirs">Avoirs</option>
                                    <option value="Règlements">Règlements</option>
                                </select>
                                <input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début">
                                <input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin">
                                <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAccountingFilter()">
                                    <i class="fas fa-undo me-1"></i> Réinitialiser
                                </button>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover accounting-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Type</th>
                                            <th>Num Document / Lettrage</th>
                                            <th>Date</th>
                                            <th>Montant TTC</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody id="accountingEntries">
                                        <tr>
                                            <td colspan="5" class="text-center">Chargement...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 with AJAX for customer search
            $('#customer_id').select2({
                width: '100%',
                placeholder: 'Rechercher un client',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route("customers.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        let query = params.term || '';
                        if ($('#customer_id').val() === '%%%') {
                            query = '%%%';
                        }
                        return { query: query };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    tva: item.tva,
                                    solde: item.solde,
                                    code: item.code,
                                    name: item.name,
                                    email: item.email,
                                    phone1: item.phone1,
                                    phone2: item.phone2,
                                    address: item.address,
                                    address_delivery: item.address_delivery,
                                    city: item.city,
                                    country: item.country,
                                    blocked: item.blocked,
                                    disabled: item.disabled
                                };
                            })
                        };
                    },
                    cache: true
                },
                templateResult: function (data) {
                    if (!data.id) {
                        return data.text;
                    }
                    var $result = $(
                        '<span' + (data.disabled ? ' class="select2-results__option--disabled"' : '') + 
                        (data.id === '%%%' ? ' class="select2-results__option--all-customers"' : '') + '>' +
                        data.text +
                        (data.blocked && data.id !== '%%%' ? ' <span class="badge bg-danger badge-very-sm"> &#x1F512;</span>' : '') +
                        '</span>'
                    );
                    return $result;
                },
                templateSelection: function (data) {
                    if (!data.id) {
                        return data.text || 'Sélectionner un client';
                    }
                    return $('<span>' + (data.text || data.name) +
                        (data.blocked && data.id !== '%%%' ? ' <span class="badge bg-danger badge-very-sm"> &#x1F512;</span>' : '') +
                        '</span>');
                }
            });

            let tvaRates = @json($tvaRates);
            let lineCount = 0;
            const accountingEntriesCache = {};

            // Initialize vehicle select as hidden
            $('#vehicle_group').hide();

            function updateGlobalTotals() {
                let totalHtGlobal = 0;
                let totalTtcGlobal = 0;
                $('#lines_body tr').each(function () {
                    let totalHt = parseFloat($(this).find('.total_ht').text()) || 0;
                    let totalTtc = parseFloat($(this).find('.total_ttc').text()) || 0;
                    totalHtGlobal += totalHt;
                    totalTtcGlobal += totalTtc;
                });
                $('#total_ht_global').text(totalHtGlobal.toFixed(2).replace('.', ','));
                $('#total_ttc_global').text(totalTtcGlobal.toFixed(2).replace('.', ','));
                console.log('Global Totals - HT:', totalHtGlobal, 'TTC:', totalTtcGlobal);
            }

            function updateCatalogButton() {
                let customerId = $('#customer_id').val();
                let vehicleId = $('#vehicle_id').val();
                let $catalogBtn = $('#loadCatalogBtn');
                
                if (customerId && vehicleId && !$('#vehicle_id').prop('disabled') && customerId !== '%%%') {
                    $catalogBtn
                        .attr('href', `/customers/${customerId}/vehicles/${vehicleId}/catalog`)
                        .removeAttr('disabled')
                        .on('click', function(e) {
                            e.preventDefault();
                            window.open(this.href, 'popupWindow', 'width=1000,height=700,scrollbars=yes');
                            return false;
                        });
                    console.log('Catalog button enabled - Customer ID:', customerId, 'Vehicle ID:', vehicleId);
                } else {
                    $catalogBtn
                        .removeAttr('href')
                        .attr('disabled', 'disabled');
                    console.log('Catalog button disabled - Customer ID:', customerId, 'Vehicle ID:', vehicleId);
                }
            }

            function updateHistoryButton() {
                let vehicleId = $('#vehicle_id').val();
                let customerId = $('#customer_id').val();
                let $historyBtn = $('#viewHistoryBtn');
                
                if (vehicleId && customerId && customerId !== '%%%') {
                    $historyBtn.removeAttr('disabled');
                    $historyBtn.off('click').on('click', function(e) {
                        e.preventDefault();
                        let url = `/vehicles/${vehicleId}/history`;
                        window.open(url, 'popupWindow', 'width=1000,height=700,scrollbars=yes');
                        return false;
                    });
                } else {
                    $historyBtn.attr('disabled', 'disabled');
                }
            }

            $('#customer_id').on('change', function () {
                let customerId = $(this).val();
                let selectedData = $(this).select2('data')[0];
                
                const $balanceBtn = $('#balanceBtn');
                const $balanceSpan = $('#customer_balance');
                let tvaRate, solde;

                if (customerId && selectedData && customerId !== '%%%') {
                    tvaRate = parseFloat(selectedData.tva || 0);
                    solde = parseFloat(selectedData.solde || 0);
                    $balanceBtn
                        .attr('data-customer-id', customerId)
                        .attr('data-customer-name', selectedData.name || 'N/A')
                        .removeAttr('disabled');
                    $balanceSpan.text(solde.toFixed(2).replace('.', ',') + ' €');
                    $balanceSpan.removeClass('text-success text-danger').addClass(solde >= 0 ? 'text-success' : 'text-danger');
                    $('#customer_name').text(selectedData.name || 'N/A');
                    $('#customer_code').text(selectedData.code || 'N/A');
                    $('#customer_tva').text(tvaRate.toFixed(2));
                    $('#customer_email').text(selectedData.email || 'N/A');
                    $('#customer_phone1').text(selectedData.phone1 || 'N/A');
                    $('#customer_phone2').text(selectedData.phone2 || 'N/A');
                    $('#customer_address').text(selectedData.address || 'N/A');
                    $('#customer_address_delivery').text(selectedData.address_delivery || 'N/A');
                    $('#customer_city').text(selectedData.city || 'N/A');
                    $('#customer_country').text(selectedData.country || 'N/A');
                    $('#customer_details').show();
                    $('#tva_rate').val(tvaRate);

                    $('#vehicle_group').show();
                    let $vehicleSelect = $('#vehicle_id');
                    $vehicleSelect.empty().append('<option value="" disabled selected>Aucun véhicule disponible</option>');
                    $vehicleSelect.prop('disabled', true).addClass('vehicle-empty');

                    $.ajax({
                        url: '/customers/' + customerId + '/vehicles',
                        method: 'GET',
                        success: function (data) {
                            console.log('Vehicles fetched for Customer ID:', customerId, 'Data:', data);
                            $vehicleSelect.empty();
                            if (data.length > 0) {
                                $vehicleSelect.append('<option value="" disabled selected>Sélectionner un véhicule</option>');
                                data.forEach((vehicle, index) => {
                                    let vehicleText = `${vehicle.license_plate} (${vehicle.brand_name} ${vehicle.model_name} - ${vehicle.engine_description})`;
                                    $vehicleSelect.append(`<option value="${vehicle.id}" ${index === 0 ? 'selected' : ''}>${vehicleText}</option>`);
                                });
                                $vehicleSelect.prop('disabled', false).removeClass('vehicle-empty');
                            } else {
                                $vehicleSelect.append('<option value="" disabled selected>Aucun véhicule disponible</option>');
                                $vehicleSelect.addClass('vehicle-empty');
                            }
                            $vehicleSelect.select2({ width: '100%' });
                            updateCatalogButton();
                            updateHistoryButton();
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error fetching vehicles:', status, error, xhr.responseText);
                            $vehicleSelect.empty().append('<option value="" disabled selected>Erreur lors du chargement des véhicules</option>').addClass('vehicle-empty');
                            Swal.fire('Erreur', 'Impossible de charger les véhicules pour ce client.', 'error');
                            updateCatalogButton();
                            updateHistoryButton();
                        }
                    });

                    if (tvaRate === 0 && selectedData && selectedData.tva == null) {
                        Swal.fire('Erreur', 'Taux TVA non défini pour ce client.', 'error');
                        console.error('TVA Rate undefined for Customer ID:', customerId);
                    }
                } else {
                    $balanceBtn
                        .attr('disabled', 'disabled')
                        .removeAttr('data-customer-id')
                        .removeAttr('data-customer-name');
                    $balanceSpan.text('0,00 €').removeClass('text-success text-danger');
                    accountingEntriesCache[customerId] = null;
                    $('#customer_details').hide();
                    $('#tva_rate').val(0);
                    tvaRate = 0;

                    $('#vehicle_group').hide();
                    $('#vehicle_id').empty().append('<option value="" disabled selected>Aucun véhicule disponible</option>').addClass('vehicle-empty');
                    $('#vehicle_id').select2({ width: '100%' });
                    updateCatalogButton();
                    updateHistoryButton();
                }

                $('#lines_body tr').each(function () {
                    let unitPriceHt = parseFloat($(this).find('.unit_price_ht').val()) || 0;
                    let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    let remise = parseFloat($(this).find('.remise').val()) || 0;
                    updateLineTotals($(this), unitPriceHt, quantity, remise, tvaRate);
                });

                updateGlobalTotals();
                console.log('Customer ID:', customerId, 'TVA Rate:', tvaRate, 'Solde:', solde);
            });

            $('#vehicle_id').on('change', function () {
                let vehicleId = $(this).val();
                console.log('Vehicle selected:', vehicleId);
                if (vehicleId) {
                    $('#vehicle_id').removeClass('vehicle-empty');
                } else {
                    $('#vehicle_id').addClass('vehicle-empty');
                }
                updateCatalogButton();
                updateHistoryButton();
            });

            $('#search_item').on('input', function () {
                let query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: '{{ route("sales.items.search") }}',
                        method: 'GET',
                        data: { query: query },
                        success: function (data) {
                            let results = $('#search_results').empty();
                            if (data.length === 0) {
                                results.append('<div class="p-2 text-gray-500">Aucun article trouvé.</div>');
                            } else {
                                data.forEach(item => {
                                    results.append(`
                                        <div class="p-2 border-b cursor-pointer hover:bg-gray-100"
                                             data-code="${item.code}"
                                             data-name="${item.name}"
                                             data-price="${item.sale_price}"
                                             data-cost-price="${item.cost_price}"
                                             data-stock="${item.stock_quantity || 0}"
                                             data-location="${item.location || ''}"
                                             data-is-active="${item.is_active}">
                                            ${item.name} (${item.code}) : ${item.sale_price} € HT

                                                ${item.stock_quantity> 0
? `🟢 ${item.stock_quantity} En Stock`
: `🔴 À Commander Hors Stock`}

                                        </div>
                                    `);
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error, xhr.responseText);
                            $('#search_results').empty().append('<div class="p-2 text-red-500">Erreur lors de la recherche.</div>');
                        }
                    });
                } else {
                    $('#search_results').empty();
                }
            });

            $(document).on('click', '#search_results div', function () {
                let customerId = $('#customer_id').val();
                if (!customerId || customerId === '%%%') {
                    Swal.fire('Erreur', 'Veuillez sélectionner un client valide avant d\'ajouter un article.', 'error');
                    return;
                }
                let tvaRate = parseFloat($('#customer_id').select2('data')[0]?.tva || 0);
                if (tvaRate === 0 && $('#customer_id').select2('data')[0]?.tva == null) {
                    Swal.fire('Erreur', 'Taux TVA non défini pour ce client.', 'error');
                    console.error('TVA Rate undefined for Customer ID:', customerId);
                    return;
                }
                let code = $(this).data('code');
                let name = $(this).data('name');
                let price = parseFloat($(this).data('price')) || 0;
                let costPrice = parseFloat($(this).data('cost-price')) || 0;
                let stock = parseFloat($(this).data('stock')) || 0;
                let location = $(this).data('location') || '-';
                let isActive = $(this).data('is-active') ? 1 : 0;

                let row = `
                    <tr data-line-id="${lineCount}">
                        <td>
                            ${code}<br>
                            <span class="badge bg-${isActive ? 'success' : 'danger'} badge-very-sm">${isActive ? '🟢 actif' : '🔴 bloqué'}</span>
                            <input type="hidden" name="lines[${lineCount}][article_code]" value="${code}">
                        </td>
                        <td>${name}</td>
                        <td>${costPrice.toFixed(2)} €</td>
                        <td>
                            ${price.toFixed(2)} €<br>
                            <small class="${price >= costPrice ? 'text-success' : 'text-danger'} small-text">
                                ${price >= costPrice ? '+' : ''}${(price - costPrice).toFixed(2)} €
                                (${costPrice > 0 ? (((price - costPrice) / costPrice) * 100).toFixed(0) : 0}%)
                            </small>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn-sm stock-details-btn"
                                    data-toggle="modal"
                                    data-target="#stockDetailsModal"
                                    data-code="${code}"
                                    data-name="${name}"
                                    title="Voir les détails du stock">
                                ${stock.toFixed(0)}
                            </button><br>
                            <small class="text-muted" style="font-size: 0.7rem;">📦 ${location}</small>
                        </td>
                        <td><input type="number" name="lines[${lineCount}][ordered_quantity]" class="form-control quantity" value="1" min="0"></td>
                        <td><input type="number" name="lines[${lineCount}][unit_price_ht]" class="form-control unit_price_ht" value="${price.toFixed(2)}" step="0.01"></td>
                        <td><input type="number" name="lines[${lineCount}][remise]" class="form-control remise" value="0" min="0" max="100" step="0.01"></td>
                        <td class="text-right total_ht">0,00</td>
                        <td class="text-right total_ttc">0,00</td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line">×</button></td>
                    </tr>
                `;
                $('#lines_body').append(row);
                updateLineTotals($('#lines_body tr:last'), price, 1, 0, tvaRate);
                lineCount++;
                $('#search_item').val('');
                $('#search_results').empty();
                updateGlobalTotals();
                console.log('Added line - Code:', code, 'Price:', price, 'TVA Rate:', tvaRate, 'Customer ID:', customerId);
            });

            $(document).on('click', '.remove_line', function () {
                $(this).closest('tr').remove();
                updateGlobalTotals();
            });

            $(document).on('input', '.quantity, .unit_price_ht, .remise', function () {
                let row = $(this).closest('tr');
                let unitPriceHt = parseFloat(row.find('.unit_price_ht').val()) || 0;
                let quantity = parseFloat(row.find('.quantity').val()) || 0;
                let remise = parseFloat(row.find('.remise').val()) || 0;
                let customerId = $('#customer_id').val();
                let tvaRate = customerId && customerId !== '%%%' ? parseFloat($('#customer_id').select2('data')[0]?.tva || 0) : 0;
                if (customerId && customerId !== '%%%' && $('#customer_id').select2('data')[0]?.tva == null) {
                    Swal.fire('Erreur', 'Taux TVA non défini pour ce client.', 'error');
                    console.error('TVA Rate undefined for Customer ID:', customerId);
                    tvaRate = 0;
                }
                updateLineTotals(row, unitPriceHt, quantity, remise, tvaRate);
                updateGlobalTotals();
                console.log('Input changed - UnitPriceHt:', unitPriceHt, 'Quantity:', quantity, 'Remise:', remise, 'TVA Rate:', tvaRate, 'Customer ID:', customerId);
            });

            function updateLineTotals(row, unitPriceHt, quantity, remise, tvaRate) {
                unitPriceHt = parseFloat(unitPriceHt) || 0;
                quantity = parseFloat(quantity) || 0;
                remise = parseFloat(remise) || 0;
                tvaRate = parseFloat(tvaRate) || 0;
                let totalHt = unitPriceHt * quantity * (1 - remise / 100);
                let totalTtc = totalHt * (1 + tvaRate / 100);
                row.find('.total_ht').text(totalHt.toFixed(2).replace('.', ','));
                row.find('.total_ttc').text(totalTtc.toFixed(2).replace('.', ','));
                console.log('Line Totals - HT:', totalHt, 'TTC:', totalTtc, 'TVA Rate:', tvaRate);
            }

            $(document).on('click', '.stock-details-btn', function (event) {
                event.preventDefault();
                event.stopPropagation();
                let code = $(this).data('code');
                let name = $(this).data('name');
                $('#stockDetailsModalLabel').text(`Détail du stock – ${code} : ${name}`);
                $('#stockTableBody').empty();
                $('#movementTableBody').empty();
                $.ajax({
                    url: '{{ route("items.stock.details") }}',
                    method: 'GET',
                    data: { code: code },
                    success: function (data) {
                        console.log('AJAX Response:', data);
                        if (data.error) {
                            console.error('Server returned error:', data.error);
                            $('#stockTableBody').append(`
                                <tr><td colspan="3" class="text-center text-danger">Erreur: ${data.error}</td></tr>
                            `);
                            $('#movementTableBody').append(`
                                <tr><td colspan="6" class="text-center text-danger">Erreur: ${data.error}</td></tr>
                            `);
                            return;
                        }
                        if (data.stocks && data.stocks.length > 0) {
                            data.stocks.forEach(stock => {
                                $('#stockTableBody').append(`
                                    <tr>
                                        <td>${stock.store_name || '-'}</td>
                                        <td>${stock.quantity}</td>
                                        <td>${stock.updated_at || '-'}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            $('#stockTableBody').append(`
                                <tr><td colspan="3" class="text-center text-muted">Aucun stock trouvé</td></tr>
                            `);
                        }
                        if (data.movements && data.movements.length > 0) {
                            data.movements.forEach(movement => {
                                let costPrice = parseFloat(movement.cost_price);
                                let costPriceFormatted = isNaN(costPrice) ? '-' : costPrice.toFixed(2) + ' €';
                                $('#movementTableBody').append(`
                                    <tr>
                                        <td>
                                            <span class="badge bg-${movement.quantity >= 0 ? 'success' : 'danger'}">
                                                ${movement.type || 'Unknown'}
                                            </span><br>
                                            ${movement.created_at || '-'}
                                        </td>
                                        <td>${movement.quantity || 0}</td>
                                        <td>${movement.store_name || '-'}</td>
                                        <td>${costPriceFormatted}</td>
                                        <td>${movement.supplier_name || '-'}</td>
                                        <td>${movement.reference || '-'}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            $('#movementTableBody').append(`
                                <tr><td colspan="6" class="text-center text-muted">Aucun mouvement trouvé</td></tr>
                            `);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusCode: xhr.status
                        });
                        let errorMessage = xhr.status === 404 ? 'Article non trouvé' : `Erreur serveur: ${xhr.status} ${xhr.statusText} - ${xhr.responseJSON?.message || 'Erreur inconnue'}`;
                        $('#stockTableBody').append(`
                            <tr><td colspan="3" class="text-center text-danger">${errorMessage}</td></tr>
                        `);
                        $('#movementTableBody').append(`
                            <tr><td colspan="6" class="text-center text-danger">${errorMessage}</td></tr>
                        `);
                    }
                });
                $('#stockDetailsModal').modal('show');
            });

            $('#salesForm').on('submit', function (e) {
                const actionValue = $(this).find('button[type="submit"]:focus').val();
                if (actionValue === 'validate_and_invoice') {
                    e.preventDefault();
                    $(this).attr('action', '{{ route("sales.delivery.store_and_invoice") }}');
                    this.submit();
                } else if (actionValue === 'save_draft') {
                    e.preventDefault();
                    $(this).attr('action', '{{ route("devis.store") }}');
                    this.submit();
                }
            });

            document.getElementById('salesForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const actionValue = e.submitter ? e.submitter.value : null;
                let confirmMessage = '';
                if (actionValue === 'validate') {
                    confirmMessage = 'Vous êtes sûr de valider ?';
                } else if (actionValue === 'validate_and_invoice') {
                    confirmMessage = 'Vous êtes sûr de facturer ce bon de livraison ?';
                } else if (actionValue === 'save_draft') {
                    confirmMessage = 'Vous êtes sûr d\'enregistrer ce devis ?';
                }
                if (confirmMessage && confirm(confirmMessage)) {
                    if (actionValue === 'validate_and_invoice') {
                        this.action = '{{ route("sales.delivery.store_and_invoice") }}';
                    } else if (actionValue === 'save_draft') {
                        this.action = '{{ route("devis.store") }}';
                    }
                    this.submit();
                }
            });

            document.querySelectorAll('.modal').forEach(modal => {
                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                modal.querySelectorAll('button, input, select, a').forEach(el => {
                    el.blur();
                });
            });

            $(document).on('click', '.balance-btn', function () {
                const customerId = $(this).data('customer-id');
                const customerName = $(this).data('customer-name');
                if (!customerId || customerId === '%%%') {
                    Swal.fire('Erreur', 'Veuillez sélectionner un client valide.', 'error');
                    return;
                }
                $('#accountingModalLabel').text(`Historique des écritures comptables - ${customerName}`);
                const tbody = $('#accountingEntries');
                tbody.html('<tr><td colspan="5" class="text-center">Chargement...</td></tr>');
                $('#balanceSummary').hide();
                if (accountingEntriesCache[customerId]) {
                    applyFilters(customerId);
                    $('#accountingModal').modal('show');
                } else {
                    $.ajax({
                        url: `/customers/${customerId}/accounting-entries`,
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function (data) {
                            accountingEntriesCache[customerId] = data.entries || [];
                            applyFilters(customerId);
                            $('#accountingModal').modal('show');
                        },
                        error: function (xhr, status, error) {
                            console.error(`Error fetching accounting entries for customer ID ${customerId}:`, error);
                            tbody.html(`<tr><td colspan="5" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables. Veuillez réessayer plus tard.</td></tr>`);
                            Swal.fire('Erreur', 'Impossible de charger les écritures comptables.', 'error');
                        }
                    });
                }
            });

            $('#accountingFilterForm').on('submit', function (e) {
                e.preventDefault();
                const customerId = $('#balanceBtn').data('customer-id');
                if (customerId) {
                    applyFilters(customerId);
                }
            });

            window.resetAccountingFilter = function () {
                const filterForm = $('#accountingFilterForm');
                const customerId = $('#balanceBtn').data('customer-id');
                if (filterForm && customerId) {
                    filterForm[0].reset();
                    $('#balanceSummary').hide();
                    applyFilters(customerId);
                }
            };

            window.showBalance = function () {
                const customerId = $('#balanceBtn').data('customer-id');
                if (customerId) {
                    $('#balanceSummary').show();
                    applyFilters(customerId);
                }
            };

            function applyFilters(customerId) {
                const tbody = $('#accountingEntries');
                const filterForm = $('#accountingFilterForm');
                const formData = new FormData(filterForm[0]);
                const typeFilter = formData.get('type') || '';
                const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
                const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;

                let entries = accountingEntriesCache[customerId] || [];
                if (typeFilter) {
                    entries = entries.filter(entry => {
                        if (typeFilter === 'Factures') return entry.type === 'Facture';
                        if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                        if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                        return true;
                    });
                }
                if (startDate || endDate) {
                    entries = entries.filter(entry => {
                        if (!entry.date || entry.date === '-') return false;
                        const entryDateParts = entry.date.split('/');
                        const entryDate = new Date(`${entryDateParts[2]}-${entryDateParts[1]}-${entryDateParts[0]}`);
                        if (startDate && entryDate < startDate) return false;
                        if (endDate && entryDate > endDate) return false;
                        return true;
                    });
                }
                let debits = 0;
                let credits = 0;
                entries.forEach(entry => {
                    if (entry.type === 'Facture') {
                        debits += parseFloat(entry.amount) || 0;
                    } else {
                        credits += parseFloat(entry.amount) || 0;
                    }
                });
                const balance = debits - credits;
                const debitsElement = $('#debits');
                const creditsElement = $('#credits');
                const balanceElement = $('#balance');
                if (debitsElement && creditsElement && balanceElement) {
                    debitsElement.text(debits.toFixed(2).replace('.', ',') + ' €');
                    creditsElement.text(credits.toFixed(2).replace('.', ',') + ' €');
                    balanceElement.text(balance.toFixed(2).replace('.', ',') + ' €');
                    balanceElement.removeClass('text-success text-danger').addClass(balance >= 0 ? 'text-success' : 'text-danger');
                }
                tbody.html('');
                if (entries.length === 0) {
                    tbody.html('<tr><td colspan="5" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>');
                    return;
                }
                entries.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${entry.type || '-'}</td>
                        <td>${entry.numdoc || entry.reference || '-'}</td>
                        <td>${entry.date || '-'}</td>
                        <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                        <td>${entry.status || '-'}</td>
                    `;
                    tbody.append(row);
                });
            }
        });
    </script>
</body>
</html>