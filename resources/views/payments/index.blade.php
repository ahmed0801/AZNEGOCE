<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ NEGOCE - Liste des Règlements</title>
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
                    @if(Auth::user()->role == 'livreur')
                        <!-- Interface spéciale pour livreur -->
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
                        <!-- Payments Content -->
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                            <div>
                                <h3 class="fw-bold mb-3">Règlements</h3>
                                <h6 class="op-7 mb-2">Liste des règlements clients et fournisseurs</h6>
                            </div>
                            <div class="ms-md-auto py-2 py-md-0">
                                <button class="btn btn-label-primary btn-round me-2" data-bs-toggle="modal" data-bs-target="#depositModal">
                                    <span class="btn-label"><i class="fas fa-plus-circle"></i></span> Alimentation Compte
                                </button>
                                <button class="btn btn-label-warning btn-round me-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                                    <span class="btn-label"><i class="fas fa-minus-circle"></i></span> Retrait Compte
                                </button>
                                
                                <!-- Nouveau bouton qui ouvre le modal -->
<button type="button" class="btn btn-label-success btn-round me-2" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
    <span class="btn-label"><i class="fas fa-file-pdf"></i></span> Journal Global
</button>

<!-- NOUVEAU : Journal des Encaissements (seulement les encaissements clients) -->
    <button type="button" class="btn btn-label-info btn-round me-2" data-bs-toggle="modal" data-bs-target="#exportEncaissementsModal">
        <i class="fas fa-euro-sign"></i> Journal Encaissements
    </button>




                                <a href="{{ route('payments.export_excel') }}?{{ request()->getQueryString() }}" class="btn btn-label-success btn-round">
                                    <span class="btn-label"><i class="fas fa-file-excel"></i></span> Exporter Excel
                                </a>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($isLimited)
                            <div class="alert alert-info">
                                Affichage des 150 derniers règlements. Utilisez les filtres pour voir plus de résultats.
                            </div>
                        @endif

                        <!-- Deposit Modal -->
                        <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="depositModalLabel">Alimentation Compte</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('payments.deposit') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label for="account_id" class="form-label">Compte Destination</label>
                                                    <select name="account_id" id="account_id" class="form-select" required>
                                                        <option value="">Sélectionner un compte</option>
                                                        @foreach ($generalAccounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->account_number }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('account_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="amount" class="form-label">Montant</label>
                                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" required>
                                                    @error('amount')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="transaction_date" class="form-label">Date</label>
                                                    <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                                    @error('transaction_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="reference" class="form-label">Référence</label>
                                                    <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}">
                                                    @error('reference')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label for="notes" class="form-label">Notes</label>
                                                    <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                                                    @error('notes')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Withdraw Modal -->
                        <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="withdrawModalLabel">Retrait Compte</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('payments.withdraw') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label for="account_id_withdraw" class="form-label">Compte Source</label>
                                                    <select name="account_id" id="account_id_withdraw" class="form-select" required>
                                                        <option value="">Sélectionner un compte</option>
                                                        @foreach ($generalAccounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->account_number }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('account_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="amount_withdraw" class="form-label">Montant</label>
                                                    <input type="number" name="amount" id="amount_withdraw" class="form-control" step="0.01" min="0.01" required>
                                                    @error('amount')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="transaction_date_withdraw" class="form-label">Date</label>
                                                    <input type="date" name="transaction_date" id="transaction_date_withdraw" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                                    @error('transaction_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="reference_withdraw" class="form-label">Référence</label>
                                                    <input type="text" name="reference" id="reference_withdraw" class="form-control" value="{{ old('reference') }}">
                                                    @error('reference')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label for="notes_withdraw" class="form-label">Notes</label>
                                                    <textarea name="notes" id="notes_withdraw" class="form-control">{{ old('notes') }}</textarea>
                                                    @error('notes')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-warning">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-round">
                            <div class="card-body">
                                <form method="GET" action="{{ route('payments.index') }}" class="mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-4 mb-2">
                                            <label for="customer_id" class="form-label">Client</label>
                                            <select name="customer_id" id="customer_id" class="form-select form-select-sm select3">
                                                <option value="">Tous</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="supplier_id" class="form-label">Fournisseur</label>
                                            <select name="supplier_id" id="supplier_id" class="form-select form-select-sm select3">
                                                <option value="">Tous</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="payment_mode" class="form-label">Mode de paiement</label>
                                            <select name="payment_mode" id="payment_mode" class="form-select form-select-sm select3">
                                                <option value="">Tous</option>
                                                @foreach($paymentModes as $mode)
                                                    <option value="{{ $mode->name }}" {{ request('payment_mode') == $mode->name ? 'selected' : '' }}>
                                                        {{ $mode->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="lettrage_code" class="form-label">Code lettrage</label>
                                            <input type="text" name="lettrage_code" id="lettrage_code" class="form-control form-control-sm" placeholder="Code lettrage" value="{{ request('lettrage_code') }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="date_from" class="form-label">Date début</label>
                                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" placeholder="Date début" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="date_to" class="form-label">Date fin</label>
                                            <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" placeholder="Date fin" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3 mb-2 d-flex align-items-end gap-2">
                                            <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </a>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-striped table-sm table-bordered align-items-center mb-0" id="paymentsTable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>Date</th>
                                                <th>Montant</th>
                                                <th>Mode de Paiement</th>
                                                <th>Compte Associé</th>
                                                <th>Client/Fourn.</th>
                                                <th>Document</th>
                                                <th>Code de Lettrage</th>
                                                <th>Référence</th>
                                                <th>Notes</th>
                                                <th>Validation Comptable</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ $payment->payment_mode }}</td>
                                                    <td>
                                                        @php
                                                            $paymentMode = $payment->paymentMode;
                                                            $account = $payment->account ?? ($paymentMode ? ($paymentMode->debitAccount ?? $paymentMode->creditAccount) : null);
                                                            $transfer = $payment->transfers->first();
                                                        @endphp
                                                        {{ $account ? $account->name . ' (' . $account->account_number . ')' : '-' }}
                                                        @if ($transfer)
                                                            <br><span class="text-success">Transféré vers {{ $transfer->toAccount->name }} ({{ $transfer->toAccount->account_number }})</span>
                                                        @endif
                                                        @if ($paymentMode && ($paymentMode->debit_account_id || $paymentMode->credit_account_id))
                                                            <br>
                                                            @if ($transfer)
                                                                <form action="{{ route('payments.cancel_transfer', $transfer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Annuler ce transfert ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger" title="Annuler le transfert">
                                                                        <i class="fas fa-times"></i> Annuler le transfert
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#transferModal{{ $payment->id }}">
                                                                    <i class="fas fa-exchange-alt"></i> Transférer
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $payment->customer ? $payment->customer->name : ($payment->supplier ? $payment->supplier->name : '-') }}
                                                    </td>
                                                    <td>
                                                        @if ($payment->payable)
                                                            {{ $payment->payable_type == \App\Models\Invoice::class ? 'Facture Vente' : ($payment->payable_type == \App\Models\PurchaseInvoice::class ? 'Facture Achat' : ($payment->payable_type == \App\Models\SalesNote::class ? 'Avoir Vente' : 'Avoir Achat')) }}
                                                            ({{ $payment->payable->numdoc }})
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $payment->lettrage_code ?? '-' }}</td>
                                                    <td>{{ $payment->reference ?? '-' }}</td>
                                                    <td>{{ $payment->notes ?? '-' }}</td>
                                                    <td class="text-center">
                                                        @if($payment->validation_comptable === 'en_attente')
                                                            <form action="{{ route('payments.validate', $payment->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button class="btn btn-sm btn-outline-success" type="submit">
                                                                    <i class="fas fa-check"></i> Valider
                                                                </button>
                                                            </form>
                                                        @elseif($payment->validation_comptable === 'validé')
                                                            <span class="text-muted">Validé</span>
                                                        @else
                                                            <span class="text-muted">Refusé</span>
                                                        @endif
                                                       <hr>
                                                         @if(!$payment->childPayments()->exists() && !$payment->parent_payment_id && $payment->validation_comptable === 'en_attente')
                                                         @if (!$transfer)
                                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelPaymentModal{{ $payment->id }}" title="Annuler le règlement">
                                                                <i class="fas fa-times"></i> Annuler
                                                            </button>
                                                            @endif
                                                        @else
                                                            <!-- <span class="text-muted">Contrepassé</span> -->
                                                        @endif
                                                    </td>

                                                </tr>

                                                <!-- Transfer Modal (unchanged) -->
                                                <div class="modal fade" id="transferModal{{ $payment->id }}" tabindex="-1" aria-labelledby="transferModalLabel{{ $payment->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="transferModalLabel{{ $payment->id }}">Transférer le Paiement : {{ $payment->lettrage_code ?? $payment->id }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('payments.transfer', $payment->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="mb-3 col-md-4">
                                                                            <label for="to_account_id_{{ $payment->id }}" class="form-label">Compte Destination</label>
                                                                            <select name="to_account_id" id="to_account_id_{{ $payment->id }}" class="form-select" required>
                                                                                <option value="">Sélectionner un compte</option>
                                                                                @foreach ($generalAccounts as $account)
                                                                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->account_number }})</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('to_account_id')
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="mb-3 col-md-4">
                                                                            <label for="transfer_date_{{ $payment->id }}" class="form-label">Date de Transfert</label>
                                                                            <input type="date" name="transfer_date" id="transfer_date_{{ $payment->id }}" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                                                            @error('transfer_date')
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="mb-3 col-md-4">
                                                                            <label for="reference_{{ $payment->id }}" class="form-label">Référence</label>
                                                                            <input type="text" name="reference" id="reference_{{ $payment->id }}" class="form-control" value="{{ old('reference') }}">
                                                                            @error('reference')
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="mb-3 col-md-12">
                                                                            <label for="notes_{{ $payment->id }}" class="form-label">Notes</label>
                                                                            <textarea name="notes" id="notes_{{ $payment->id }}" class="form-control">{{ old('notes') }}</textarea>
                                                                            @error('notes')
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                        <button type="submit" class="btn btn-success">Transférer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Cancel Payment Modal -->
                                                <div class="modal fade" id="cancelPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="cancelPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="cancelPaymentModalLabel{{ $payment->id }}">Annuler le règlement : {{ $payment->lettrage_code ?? $payment->id }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <form action="{{ route('payments.cancel', $payment->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="payment_mode{{ $payment->id }}" class="form-label">Mode de paiement</label>
                                                                        <select class="form-control select2" id="payment_mode{{ $payment->id }}" name="payment_mode" required>
                                                                            <option value="">Sélectionner le mode de paiement</option>
                                                                            @foreach(\App\Models\PaymentMode::where('type', 'décaissement')->get() as $mode)
                                                                                <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('payment_mode')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="payment_date{{ $payment->id }}" class="form-label">Date de paiement</label>
                                                                        <input type="date" class="form-control" id="payment_date{{ $payment->id }}" name="payment_date" value="{{ now()->format('Y-m-d') }}" required>
                                                                        @error('payment_date')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="reference{{ $payment->id }}" class="form-label">Référence (optionnel)</label>
                                                                        <input type="text" class="form-control" id="reference{{ $payment->id }}" name="reference" value="{{ 'ANNULATION-' . ($payment->reference ?? $payment->id) }}">
                                                                        @error('reference')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="notes{{ $payment->id }}" class="form-label">Notes (optionnel)</label>
                                                                        <textarea class="form-control" id="notes{{ $payment->id }}" name="notes">Annulation du paiement #{{ $payment->id }}</textarea>
                                                                        @error('notes')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <p><strong>Montant à annuler :</strong> {{ number_format(abs($payment->amount), 2, ',', ' ') }} €</p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
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

            // Initialize DataTables for payments table
            $('table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
                },
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                order: [], // Disable default sorting to respect server-side order
                columnDefs: [
                    { orderable: false, targets: 9 } // Disable sorting on Action column
                ]
            });
        });
    </script>









<div class="modal fade" id="exportPdfModal" tabindex="-1" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exportPdfModalLabel">
                    <i class="fas fa-file-pdf"></i> Exporter en PDF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('payments.export_pdf') }}" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date de début</label>
                        <input type="date" name="date_from" class="form-control" 
                               value="{{ request('date_from', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date de fin</label>
                        <input type="date" name="date_to" class="form-control" 
                               value="{{ request('date_to', now()->format('Y-m-d')) }}" required>
                    </div>

                    <!-- Garde les filtres actuels (client, fournisseur, mode, lettrage, etc.) -->
                    @foreach(request()->except(['date_from', 'date_to', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-pdf"></i> Générer le PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>















<!-- Modal : Journal des Encaissements (seulement les encaissements clients) -->
<div class="modal fade" id="exportEncaissementsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-euro-sign"></i> Journal des Encaissements
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('payments.export_pdf') }}" target="_blank">
                <!-- On force le type "encaissement" -->
                <input type="hidden" name="type" value="encaissement">

                

<div class="modal-body">
    <p class="text-muted small">Seuls les <strong>Réglements clients</strong> seront inclus.</p>

    <div class="mb-3">
        <label class="form-label fw-bold">Date de début</label>
        <input type="date" name="date_from" class="form-control"
               value="{{ request('date_from', now()->format('Y-m-d')) }}" required>
                              <!-- value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required> -->

    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Date de fin</label>
        <input type="date" name="date_to" class="form-control"
               value="{{ request('date_to', now()->format('Y-m-d')) }}" required>
    </div>

    <input type="hidden" name="type" value="encaissement">

    @php
        $otherParams = request()->except(['date_from', 'date_to', 'page', 'type']);
    @endphp
    @foreach($otherParams as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-file-pdf"></i> Générer Journal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>















</body>
</html>