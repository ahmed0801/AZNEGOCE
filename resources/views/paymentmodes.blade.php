<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Modes de Paiement</title>
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
                        <li class="nav-item">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/salesnotes/list"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                      <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-credit-card"></i></span><h4 class="text-section">Comptabilité</h4></li>
                                                <li class="nav-item {{ Route::is('generalaccounts.index') ? 'active' : '' }}">
                            <a href="{{ route('generalaccounts.index') }}"><i class="fas fa-book"></i><p>Comptes Généraux</p></a>
                        </li>
                                                <li class="nav-item {{ Route::is('payments.index') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i><p>Règlements</p></a>
                        </li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                                   <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item active"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-robot"></i></span><h4 class="text-section">Autres</h4></li>
                        <li class="nav-item"><a href="/voice"><i class="fas fa-robot"></i><p>NEGOBOT</p></a></li>
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
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="container mt-4">
                        <h4>Modes de Paiement</h4>

                        <!-- Bouton pour ouvrir le modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">
                            Créer un Mode de Paiement
                        </button>

                        <!-- Modal Créer -->
                        <div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createItemModalLabel">Créer un Mode de Paiement</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="createItemForm" action="{{ route('paymentmode.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
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
                                                        <option value="décaissement" {{ old('type') == 'décaissement' ? 'selected' : '' }}>Décaissement</option>
                                                        <option value="encaissement" {{ old('type') == 'encaissement' ? 'selected' : '' }}>Encaissement</option>
                                                    </select>
                                                    @error('type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- Action sur Solde Client -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="customer_balance_action" class="form-label">Action sur Solde Client</label>
                                                    <select name="customer_balance_action" id="customer_balance_action" class="form-select" required>
                                                        <option value="+" {{ old('customer_balance_action', '+') == '+' ? 'selected' : '' }}>+ (Augmenter)</option>
                                                        <option value="-" {{ old('customer_balance_action') == '-' ? 'selected' : '' }}>- (Diminuer)</option>
                                                    </select>
                                                    @error('customer_balance_action')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- Action sur Solde Fournisseur -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="supplier_balance_action" class="form-label">Action sur Solde Fournisseur</label>
                                                    <select name="supplier_balance_action" id="supplier_balance_action" class="form-select" required>
                                                        <option value="+" {{ old('supplier_balance_action', '-') == '+' ? 'selected' : '' }}>+ (Augmenter)</option>
                                                        <option value="-" {{ old('supplier_balance_action', '-') == '-' ? 'selected' : '' }}>- (Diminuer)</option>
                                                    </select>
                                                    @error('supplier_balance_action')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- Compte Débit -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="debit_account_id" class="form-label">Compte Débit</label>
                                                    <select name="debit_account_id" id="debit_account_id" class="form-select">
                                                        <option value="">Aucun</option>
                                                        @foreach ($generalAccounts as $account)
                                                            <option value="{{ $account->id }}" {{ old('debit_account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }} ({{ $account->account_number }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('debit_account_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- Compte Crédit -->
                                                <div class="mb-3 col-md-4">
                                                    <label for="credit_account_id" class="form-label">Compte Crédit</label>
                                                    <select name="credit_account_id" id="credit_account_id" class="form-select">
                                                        <option value="">Aucun</option>
                                                        @foreach ($generalAccounts as $account)
                                                            <option value="{{ $account->id }}" {{ old('credit_account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }} ({{ $account->account_number }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('credit_account_id')
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
                            <input type="text" id="searchItemInput" class="form-control search-box" placeholder="🔍 Rechercher un Mode de Paiement...">
                        </div>

                        @if ($paymentmodes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-text-small" id="itemsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>Action Solde Client</th>
                                            <th>Action Solde Fournisseur</th>
                                            <th>Compte Débit</th>
                                            <th>Compte Crédit</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paymentmodes as $paymentmode)
                                            <tr>
                                                <td>{{ $paymentmode->name }}</td>
                                                <td>{{ ucfirst($paymentmode->type) }}</td>
                                                <td>{{ $paymentmode->customer_balance_action ?? '-' }}</td>
                                                <td>{{ $paymentmode->supplier_balance_action ?? '-' }}</td>
                                                <td>{{ $paymentmode->debitAccount ? $paymentmode->debitAccount->name . ' (' . $paymentmode->debitAccount->account_number . ')' : '-' }}</td>
                                                <td>{{ $paymentmode->creditAccount ? $paymentmode->creditAccount->name . ' (' . $paymentmode->creditAccount->account_number . ')' : '-' }}</td>
                                                <td>
                                                    <!-- Modifier -->
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $paymentmode->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <!-- Supprimer -->
                                                    <form action="{{ route('paymentmode.destroy', $paymentmode->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce mode ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal Modifier -->
                                            <div class="modal fade" id="editItemModal{{ $paymentmode->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $paymentmode->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modifier le Mode de Paiement : {{ $paymentmode->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('paymentmode.update', $paymentmode->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="row">
                                                                    <!-- Nom -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label class="form-label">Nom</label>
                                                                        <input type="text" name="name" class="form-control" value="{{ $paymentmode->name }}" required>
                                                                        @error('name')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Type -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label for="type_{{ $paymentmode->id }}" class="form-label">Type</label>
                                                                        <select name="type" id="type_{{ $paymentmode->id }}" class="form-select" required>
                                                                            <option value="décaissement" {{ $paymentmode->type == 'décaissement' ? 'selected' : '' }}>Décaissement</option>
                                                                            <option value="encaissement" {{ $paymentmode->type == 'encaissement' ? 'selected' : '' }}>Encaissement</option>
                                                                        </select>
                                                                        @error('type')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Action sur Solde Client -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label for="customer_balance_action_{{ $paymentmode->id }}" class="form-label">Action sur Solde Client</label>
                                                                        <select name="customer_balance_action" id="customer_balance_action_{{ $paymentmode->id }}" class="form-select" required>
                                                                            <option value="+" {{ $paymentmode->customer_balance_action == '+' ? 'selected' : '' }}>+ (Augmenter)</option>
                                                                            <option value="-" {{ $paymentmode->customer_balance_action == '-' ? 'selected' : '' }}>- (Diminuer)</option>
                                                                        </select>
                                                                        @error('customer_balance_action')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Action sur Solde Fournisseur -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label for="supplier_balance_action_{{ $paymentmode->id }}" class="form-label">Action sur Solde Fournisseur</label>
                                                                        <select name="supplier_balance_action" id="supplier_balance_action_{{ $paymentmode->id }}" class="form-select" required>
                                                                            <option value="+" {{ $paymentmode->supplier_balance_action == '+' ? 'selected' : '' }}>+ (Augmenter)</option>
                                                                            <option value="-" {{ $paymentmode->supplier_balance_action == '-' ? 'selected' : '' }}>- (Diminuer)</option>
                                                                        </select>
                                                                        @error('supplier_balance_action')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Compte Débit -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label for="debit_account_id_{{ $paymentmode->id }}" class="form-label">Compte Débit</label>
                                                                        <select name="debit_account_id" id="debit_account_id_{{ $paymentmode->id }}" class="form-select">
                                                                            <option value="">Aucun</option>
                                                                            @foreach ($generalAccounts as $account)
                                                                                <option value="{{ $account->id }}" {{ $paymentmode->debit_account_id == $account->id ? 'selected' : '' }}>{{ $account->name }} ({{ $account->account_number }})</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('debit_account_id')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Compte Crédit -->
                                                                    <div class="mb-3 col-md-4">
                                                                        <label for="credit_account_id_{{ $paymentmode->id }}" class="form-label">Compte Crédit</label>
                                                                        <select name="credit_account_id" id="credit_account_id_{{ $paymentmode->id }}" class="form-select">
                                                                            <option value="">Aucun</option>
                                                                            @foreach ($generalAccounts as $account)
                                                                                <option value="{{ $account->id }}" {{ $paymentmode->credit_account_id == $account->id ? 'selected' : '' }}>{{ $account->name }} ({{ $account->account_number }})</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('credit_account_id')
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
                            <p class="text-center text-muted">Aucun mode de paiement trouvé.</p>
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
            $('#itemsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
                },
                paging: true,
                searching: false, // Disable DataTables search since we have custom search
                info: true,
                ordering: true
            });

            // Custom search functionality
            document.getElementById("searchItemInput").addEventListener("keyup", function() {
                var input = this.value.toLowerCase();
                var rows = document.querySelectorAll("#itemsTable tbody tr");

                rows.forEach(function(row) {
                    row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
                });
            });
        });
    </script>
</body>
</html>