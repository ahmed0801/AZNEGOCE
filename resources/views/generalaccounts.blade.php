<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Comptes Généraux</title>
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

    <style>
        #panierDropdown + .dropdown-menu {
            width: 900px;
            min-width: 350px;
            padding: 10px;
            border-radius: 8px;
        }

        .panier-dropdown {
            width: 100%;
            min-width: 350px;
        }

        .panier-dropdown .notif-item {
            padding: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .notif-scroll {
            padding: 10px;
        }

        .notif-center {
            padding: 5px 0;
        }

        .dropdown-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .btn-sm {
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
        }

        .text-muted {
            font-size: 0.85rem;
        }

        .text-center {
            text-align: center;
        }

        .card {
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 1.8rem;
            color: #007bff;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .card h6 {
            font-size: 1rem;
            color: #6c757d;
        }

        .card-body {
            padding: 2rem;
        }

        .card .text-info {
            color: #17a2b8 !important;
        }

        .btn-primary {
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        .search-box {
            max-width: 400px;
            height: 35px;
            padding: 5px 12px;
            border: 2px solid #007bff;
            border-radius: 20px;
            font-size: 14px;
            transition: 0.3s ease-in-out;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .search-box:focus {
            border-color: #0056b3;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.5);
        }

        .table-text-small th,
        .table-text-small td,
        .table-text-small input,
        .table-text-small button,
        .table-text-small span,
        .table-text-small svg {
            font-size: 11px !important;
        }

        .table-text-small th {
            font-size: 10px !important;
        }
    </style>
</head>
<body>
    <div class="wrapper sidebar_minimize">
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
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="container mt-4">
                        <h4>Comptes Généraux :

                                          <button type="submit" class="btn btn-outline-success btn-round ms-2" data-bs-toggle="modal" data-bs-target="#createAccountModal">Nouveau Compte Général
           <i class="fas fa-plus-circle ms-2"></i>
          </button>

                        </h4>

                        <!-- Modal Créer -->
                        <div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createAccountModalLabel">Créer un Compte Général</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="createAccountForm" action="{{ route('generalaccounts.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <!-- Numéro de Compte -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="account_number" class="form-label">Numéro de Compte</label>
                                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" required>
                                                    @error('account_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- Nom -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="name" class="form-label">Nom</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- Type -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="type" class="form-label">Type</label>
                                                    <select name="type" id="type" class="form-select" required>
                                                        <option value="caisse" {{ old('type') == 'caisse' ? 'selected' : '' }}>Caisse</option>
                                                        <option value="banque" {{ old('type') == 'banque' ? 'selected' : '' }}>Banque</option>
                                                        <option value="coffre" {{ old('type') == 'coffre' ? 'selected' : '' }}>Coffre</option>
                                                    </select>
                                                    @error('type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-success">Créer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recherche -->
                        <div class="mb-1 d-flex justify-content-center">
                            <input type="text" id="searchAccountInput" class="form-control search-box" placeholder="🔍 Rechercher un Compte Général...">
                        </div>

                        @if ($generalAccounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-text-small" id="accountsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Numéro de Compte</th>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>Solde</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<!-- comptes par défaut -->
                                    <tr>
                                                <td>707.</td>
                                                <td>Ventes de Marchandises</td>
                                                <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                    <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>

                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#allAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>


                                                                        <tr>
                                                <td>607.</td>
                                                <td>Achat de Marchandises</td>
                                                 <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                    <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allsupplierAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>
                                                
                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#allsupplierAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>





                                     <tr>
                                                <td>44571.</td>
                                                <td>TVA Collectée</td>
                                                 <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                 <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#TVAAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>

                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#TVAAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>





                                     <tr>
                                                <td>44566.</td>
                                                <td>TVA déductible</td>
                                                 <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                 <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#TVADAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>

                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#TVADAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>








                                                                        <tr>
                                                <td>411.</td>
                                                <td>Clients</td>
                                                 <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                 <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allcustomerAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>

                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#allcustomerAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>


                                                                        <tr>
                                                <td>4091.</td>
                                                <td>Fournisseurs</td>
                                                 <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                 <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#allfourAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>

                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#allfourAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>




                                                                        <tr>
                                                <td>6037.</td>
                                                <td>stocks de marchandises</td>
                                                 <td><span class="badge rounded-pill text-bg-light">Compte Systéme</span>
</td>
                                                <td>
                                                 <a type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#StockAccountingModal"> Champs Calculé <i class="fa fa-calculator" aria-hidden="true"></i></a>

                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info" title="Consultation des écritures" data-bs-toggle="modal" data-bs-target="#StockAccountingModal">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a>
    </td>
                                    </tr>










                                    <!-- fin comptes par défaut -->

                                        @foreach ($generalAccounts as $account)
                                            <tr>
                                                <td>{{ $account->account_number }}</td>
                                                <td>{{ $account->name }}</td>
                                                <td>{{ ucfirst($account->type) }}</td>
                                                <td>{{ number_format($account->balance, 2) }}</td>
                                                <td>
                                                    <!-- Rapprochement -->
                                                    <a href="{{ route('generalaccounts.reconcile', $account->id) }}" class="btn btn-sm btn-info" title="Rapprochement">
                                                        Rapprochement <i class="fas fa-check-circle"></i>
                                                    </a> 
                                                    <!-- Consultation des écritures -->
                                                    <a href="{{ route('generalaccounts.transactions', $account->id) }}" class="btn btn-sm btn-info" title="Consultation des écritures">
                                                        Ecritures comptables <i class="fas fa-list"></i>
                                                    </a> <hr>
                                                    <!-- Modifier -->
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAccountModal{{ $account->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <!-- Supprimer -->
                                                    <form action="{{ route('generalaccounts.destroy', $account->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce compte ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal Modifier -->
                                            <div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1" aria-labelledby="editAccountModalLabel{{ $account->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modifier le Compte Général : {{ $account->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('generalaccounts.update', $account->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="row">
                                                                    <!-- Numéro de Compte -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label class="form-label">Numéro de Compte</label>
                                                                        <input type="text" name="account_number" class="form-control" value="{{ $account->account_number }}" required>
                                                                        @error('account_number')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Nom -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label class="form-label">Nom</label>
                                                                        <input type="text" name="name" class="form-control" value="{{ $account->name }}" required>
                                                                        @error('name')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Type -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label for="type_{{ $account->id }}" class="form-label">Type</label>
                                                                        <select name="type" id="type_{{ $account->id }}" class="form-select" required>
                                                                            <option value="caisse" {{ $account->type == 'caisse' ? 'selected' : '' }}>Caisse</option>
                                                                            <option value="banque" {{ $account->type == 'banque' ? 'selected' : '' }}>Banque</option>
                                                                            <option value="coffre" {{ $account->type == 'coffre' ? 'selected' : '' }}>Coffre</option>
                                                                        </select>
                                                                        @error('type')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">Aucun compte général trouvé.</p>
                        @endif
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
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTables
            $('#accountsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
                },
                paging: true,
                searching: false, // Disable DataTables search since we have custom search
                info: true,
                ordering: true
            });

            // Custom search functionality
            document.getElementById("searchAccountInput").addEventListener("keyup", function() {
                var input = this.value.toLowerCase();
                var rows = document.querySelectorAll("#accountsTable tbody tr");

                rows.forEach(function(row) {
                    row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
                });
            });
        });
    </script>










<!-- New Modal for All Accounting Entries -->
                        <div class="modal fade" id="allAccountingModal" tabindex="-1" aria-labelledby="allAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allAccountingModalLabel">Ecritures Compte : 70. Ventes de Marchandises</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des Ventes</h6>
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
                                                            <td id="allDebits">0,00 €</td>
                                                            <td id="allCredits">0,00 €</td>
                                                            <td id="allBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="allAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <!-- <option value="Règlements">Règlements</option> -->
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <!-- <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Client (Tous)</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select> -->
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAllAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Type</th>
                                                        <th>Num Document</th>
                                                        <th>Date</th>
                                                        <th>Montant HT</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="allAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
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


<script>

    
// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('allAccountingModal');
    const allFilterForm = document.getElementById('allAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('allAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('allcustomer.accounting-entriesHT') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllAccountingFilter = function () {
        const filterForm = document.getElementById('allAccountingFilterForm');
        const balanceSummary = document.getElementById('allBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showAllBalance = function () {
        const balanceSummary = document.getElementById('allBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('allAccountingEntries');
        const filterForm = document.getElementById('allAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
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

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
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

        // Update balance summary
        const debitsElement = document.getElementById('allDebits');
        const creditsElement = document.getElementById('allCredits');
        const balanceElement = document.getElementById('allBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>







    <!-- New supplier Modal for All Accounting Entries -->
                        <div class="modal fade" id="allsupplierAccountingModal" tabindex="-1" aria-labelledby="allsupplierAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allsupplierAccountingModalLabel">Ecritures Compte : 60. Achat de Marchandises</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllsupplierBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allsupplierBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des Achats</h6>
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
                                                            <td id="allsupplierDebits">0,00 €</td>
                                                            <td id="allsupplierCredits">0,00 €</td>
                                                            <td id="allsupplierBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="allsupplierAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <!-- <option value="Règlements">Règlements</option> -->
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <!-- <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Fournisseur (Tous)</option>
                                                @foreach($suppliers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select> -->
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAllAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Fourn.</th>
                                                        <th>Type</th>
                                                        <th>Num Document</th>
                                                        <th>Date</th>
                                                        <th>Montant HT</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="allsupplierAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
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



<script> 


// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allsupplierAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allsupplierAccountingModal = document.getElementById('allsupplierAccountingModal');
    const allsupplierFilterForm = document.getElementById('allsupplierAccountingFilterForm');

    if (allsupplierAccountingModal) {
        allsupplierAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('allsupplierAccountingEntries');

            // If entries are cached, apply filters and render
            if (allsupplierAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('allsupplier.accounting-entriesHT') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allsupplierAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allsupplierFilterForm) {
        allsupplierFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllsupplierAccountingFilter = function () {
        const filterForm = document.getElementById('allsupplierAccountingFilterForm');
        const balanceSummary = document.getElementById('allsupplierBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showAllsupplierBalance = function () {
        const balanceSummary = document.getElementById('allsupplierBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('allsupplierAccountingEntries');
        const filterForm = document.getElementById('allsupplierAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allsupplierAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
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

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
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

        // Update balance summary
        const debitsElement = document.getElementById('allsupplierDebits');
        const creditsElement = document.getElementById('allsupplierCredits');
        const balanceElement = document.getElementById('allsupplierBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>













<!-- New Modal for TVA collectée Accounting Entries -->
                        <div class="modal fade" id="TVAAccountingModal" tabindex="-1" aria-labelledby="TVAAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="TVAAccountingModalLabel">Ecritures Compte : 4457. TVA Collectée</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showTVABalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="TVABalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des TVA Collectées</h6>
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
                                                            <td id="TVADebits">0,00 €</td>
                                                            <td id="TVACredits">0,00 €</td>
                                                            <td id="TVABalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="TVAAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <!-- <option value="Règlements">Règlements</option> -->
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <!-- <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Client (Tous)</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select> -->
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetTVAAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Type</th>
                                                        <th>Num Document</th>
                                                        <th>Date</th>
                                                        <th>Total TVA</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TVAAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
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


<script>

    
// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('TVAAccountingModal');
    const allFilterForm = document.getElementById('TVAAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('TVAAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('TVA.accounting-entries') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllAccountingFilter = function () {
        const filterForm = document.getElementById('TVAAccountingFilterForm');
        const balanceSummary = document.getElementById('TVABalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showTVABalance = function () {
        const balanceSummary = document.getElementById('TVABalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('TVAAccountingEntries');
        const filterForm = document.getElementById('TVAAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
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

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
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

        // Update balance summary
        const debitsElement = document.getElementById('TVADebits');
        const creditsElement = document.getElementById('TVACredits');
        const balanceElement = document.getElementById('TVABalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>
<!-- fin tva collecté -->


















<!-- New Modal for TVA collectée Accounting Entries -->
                        <div class="modal fade" id="TVADAccountingModal" tabindex="-1" aria-labelledby="TVADAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="TVADAccountingModalLabel">Ecritures Compte : 44566. TVA Déductibles</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showTVADBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="TVADBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des TVA Déductibles</h6>
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
                                                            <td id="TVADDebits">0,00 €</td>
                                                            <td id="TVADCredits">0,00 €</td>
                                                            <td id="TVADBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="TVADAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <!-- <option value="Règlements">Règlements</option> -->
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <!-- <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Client (Tous)</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select> -->
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetTVADAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Fourn.</th>
                                                        <th>Type</th>
                                                        <th>Num Document</th>
                                                        <th>Date</th>
                                                        <th>Total TVA</th>
                                                        <th>Statut Doc.</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TVADAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
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


<script>

    
// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('TVADAccountingModal');
    const allFilterForm = document.getElementById('TVADAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('TVADAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('TVAD.accounting-entries') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllAccountingFilter = function () {
        const filterForm = document.getElementById('TVADAccountingFilterForm');
        const balanceSummary = document.getElementById('TVADBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showTVADBalance = function () {
        const balanceSummary = document.getElementById('TVADBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('TVADAccountingEntries');
        const filterForm = document.getElementById('TVADAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
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

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
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

        // Update balance summary
        const debitsElement = document.getElementById('TVADDebits');
        const creditsElement = document.getElementById('TVADCredits');
        const balanceElement = document.getElementById('TVADBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>

<!-- fin tva déductible -->















<!-- compte clients -->
<!-- New Modal for All Accounting Entries -->
                        <div class="modal fade" id="allcustomerAccountingModal" tabindex="-1" aria-labelledby="allcustomerAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allcustomerAccountingModalLabel">Ecritures Compte : 411. Clients</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllcustomerBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allcustomerBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale Clients</h6>
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
                                                            <td id="allcustomerDebits">0,00 €</td>
                                                            <td id="allcustomerCredits">0,00 €</td>
                                                            <td id="allcustomerBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="allcustomerAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <option value="Règlements">Règlements</option>
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Client (Tous)</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAllcustomerAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Type</th>
                                                        <th>Num Document</th>
                                                        <th>Date</th>
                                                        <th>Montant HT</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="allcustomerAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
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


<script>

    
// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('allcustomerAccountingModal');
    const allFilterForm = document.getElementById('allcustomerAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('allcustomerAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('allcustomer.accounting-entries') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllcustomerAccountingFilter = function () {
        const filterForm = document.getElementById('allcustomerAccountingFilterForm');
        const balanceSummary = document.getElementById('allcustomerBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showAllcustomerBalance = function () {
        const balanceSummary = document.getElementById('allcustomerBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('allcustomerAccountingEntries');
        const filterForm = document.getElementById('allcustomerAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
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

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
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

        // Update balance summary
        const debitsElement = document.getElementById('allcustomerDebits');
        const creditsElement = document.getElementById('allcustomerCredits');
        const balanceElement = document.getElementById('allcustomerBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>
<!-- fin compte clients -->
















<!-- compte fournisseurs -->
<!-- New Modal for All Accounting Entries -->
                        <div class="modal fade" id="allfourAccountingModal" tabindex="-1" aria-labelledby="allfourAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allfourAccountingModalLabel">Ecritures Compte : 4091. Fournisseurs</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllfourBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allfourBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale Fournisseurs</h6>
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
                                                            <td id="allfourDebits">0,00 €</td>
                                                            <td id="allfourCredits">0,00 €</td>
                                                            <td id="allfourBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="allfourAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <option value="Règlements">Règlements</option>
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Fournisseur (Tous)</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAllsupplierAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Fourn.</th>
                                                        <th>Type</th>
                                                        <th>Num Document</th>
                                                        <th>Date</th>
                                                        <th>Montant TTC</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="allfourAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
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


<script>

    
// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('allfourAccountingModal');
    const allFilterForm = document.getElementById('allfourAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('allfourAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('allsupplier.accounting-entries') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllfourAccountingFilter = function () {
        const filterForm = document.getElementById('allfourAccountingFilterForm');
        const balanceSummary = document.getElementById('allfourBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showAllfourBalance = function () {
        const balanceSummary = document.getElementById('allfourBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('allfourAccountingEntries');
        const filterForm = document.getElementById('allfourAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
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

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
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

        // Update balance summary
        const debitsElement = document.getElementById('allfourDebits');
        const creditsElement = document.getElementById('allfourCredits');
        const balanceElement = document.getElementById('allfourBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>
<!-- fin compte clients -->













<!-- compte stock -->
<!-- Modal Stock Accounting Entries -->
<div class="modal fade" id="StockAccountingModal" tabindex="-1" aria-labelledby="StockAccountingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StockAccountingModalLabel">Écritures Compte : 37. Stock de Marchandises</h5>
                <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showStockBalance()">
                    <i class="fas fa-balance-scale me-1"></i> Balance Générale
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Balance Summary (Hidden by Default) -->
                <div id="StockBalanceSummary" class="card mb-3" style="display: none;">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Balance Générale du Stock</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Total Débits (Entrées)</th>
                                    <th>Total Crédits (Sorties)</th>
                                    <th>Solde Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="StockDebits">0,00 €</td>
                                    <td id="StockCredits">0,00 €</td>
                                    <td id="StockBalance">0,00 €</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Filter Form -->
                <!-- Filter Form -->
<form id="StockAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
    <select name="type" class="form-select form-select-sm" style="width: 200px;">
        <option value="">Type (Tous)</option>
        <option value="Entrée">Entrées</option>
        <option value="Sortie">Sorties</option>
    </select>

    <!-- Si tu veux filtrer par type technique aussi (achat, vente, ...) -->
    <select name="movement_type" class="form-select form-select-sm" style="width: 200px;">
        <option value="">Type mouvement (Tous)</option>
        <option value="achat">achat</option>
        <option value="vente">vente</option>
        <option value="retour_vente">retour_vente</option>
        <option value="retour_achat">retour_achat</option>
        <option value="ajustement">ajustement</option>
        <option value="annulation_expedition">annulation_expedition</option>
    </select>

    <input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;">
    <input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;">
    <button type="submit" class="btn btn-outline-primary btn-sm px-3">
        <i class="fas fa-filter me-1"></i> Filtrer
    </button>
    <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetStockAccountingFilter()">
        <i class="fas fa-undo me-1"></i> Réinitialiser
    </button>


    <a href="{{ route('stock.accounting-entries.export') }}" 
   class="btn btn-outline-success btn-sm px-3">
   <i class="fas fa-file-excel me-1"></i> Export Excel
</a>



</form>


                <div class="table-responsive">
                    <table class="table table-bordered table-hover accounting-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Article</th>
                                <th>Type</th>
                                <th>Référence</th>
                                <th>Date</th>
                                <th>Quantité</th>
                                <th>Montant (€)</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="StockAccountingEntries">
                            <tr>
                                <td colspan="7" class="text-center">Chargement...</td>
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


<script>
document.addEventListener("DOMContentLoaded", function () {
    let stockAccountingEntriesCache = [];

    const stockModal = document.getElementById('StockAccountingModal');
    const stockFilterForm = document.getElementById('StockAccountingFilterForm');

    if (stockModal) {
        stockModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('StockAccountingEntries');

            if (stockAccountingEntriesCache.length > 0) {
                applyStockFilters();
                return;
            }

            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('stock.accounting-entries') }}", {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP error ' + response.status);
                return response.json();
            })
            .then(data => {
                stockAccountingEntriesCache = data.entries || [];
                applyStockFilters();
            })
            .catch(error => {
                console.error('Error fetching stock accounting entries:', error);
                document.getElementById('StockAccountingEntries').innerHTML =
                    `<tr><td colspan="7" class="text-center text-danger">Erreur: Impossible de charger les écritures du stock.</td></tr>`;
            });
        });
    }

    if (stockFilterForm) {
        stockFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyStockFilters();
        });
    }

    window.resetStockAccountingFilter = function () {
        stockFilterForm.reset();
        document.getElementById('StockBalanceSummary').style.display = 'none';
        applyStockFilters();
    };

    window.showStockBalance = function () {
        document.getElementById('StockBalanceSummary').style.display = 'block';
        applyStockFilters();
    };

    function applyStockFilters() {
        const tbody = document.getElementById('StockAccountingEntries');
        const formData = new FormData(stockFilterForm);
        const directionFilter = formData.get('type') || '';          // 'Entrée' | 'Sortie' | ''
        const movementTypeFilter = formData.get('movement_type') || ''; // 'achat','vente',...
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;

        let entries = stockAccountingEntriesCache.slice();

        // Filter by direction (Entrée / Sortie)
        if (directionFilter) {
            entries = entries.filter(e => (e.direction === directionFilter));
        }

        // Filter by raw movement type if selected
        if (movementTypeFilter) {
            entries = entries.filter(e => (e.type === movementTypeFilter));
        }

        // Date filter (entries.date is 'dd/mm/YYYY')
        if (startDate || endDate) {
            entries = entries.filter(entry => {
                if (!entry.date || entry.date === '-') return false;
                const parts = entry.date.split('/');
                const entryDate = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                if (startDate && entryDate < startDate) return false;
                if (endDate && entryDate > endDate) return false;
                return true;
            });
        }

        // Calculate balance: débuts = Entrées, crédits = Sorties
        let debits = 0, credits = 0;
        entries.forEach(entry => {
            const amount = parseFloat(entry.amount) || 0;
            if (entry.direction === 'Entrée') debits += amount;
            else credits += amount;
        });
        const balance = debits - credits;

        // Update balance UI
        const debitsEl = document.getElementById('StockDebits');
        const creditsEl = document.getElementById('StockCredits');
        const balanceEl = document.getElementById('StockBalance');
        if (debitsEl && creditsEl && balanceEl) {
            debitsEl.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsEl.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceEl.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceEl.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render rows
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Aucune écriture trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.item_name || '-'}</td>
                <td>${entry.direction || '-' } <small class="text-muted">(${entry.type || '-'})</small></td>
                <td>${entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${entry.quantity ?? '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') + ' €' : '-'}</td>
                <td>${entry.note || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>





<!-- fin compte stock  -->



</body>
</html>