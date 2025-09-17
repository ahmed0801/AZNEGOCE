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
                        <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>
                        @if(Auth::user()->role != 'livreur')
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
                        @endif
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        @if(Auth::user()->role != 'livreur')
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
                                <a href="{{ route('payments.export_pdf') }}?{{ request()->getQueryString() }}" class="btn btn-label-success btn-round me-2">
                                    <span class="btn-label"><i class="fas fa-file-pdf"></i></span> Exporter PDF
                                </a>
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
                                Affichage des 50 derniers règlements. Utilisez les filtres pour voir plus de résultats.
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
                                                <th>Action</th>
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
        <br> <span class="text-success">Transféré vers {{ $transfer->toAccount->name }} ({{ $transfer->toAccount->account_number }})</span>
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
                                                    <td>
                                                        @if ($paymentMode && ($paymentMode->debit_account_id || $paymentMode->credit_account_id))
                                                            @if ($transfer)
                                                                <form action="{{ route('payments.cancel_transfer', $transfer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Annuler ce transfert ?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger" title="Annuler le transfert">
                                                                        <i class="fas fa-times"></i> Annuler
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#transferModal{{ $payment->id }}">
                                                                    <i class="fas fa-exchange-alt"></i> Transférer
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Transfer -->
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
</body>
</html>