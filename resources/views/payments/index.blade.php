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
                                    <table class="table table-sm table-bordered align-items-center mb-0">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>Date</th>
                                                <th>Client/Fournisseur</th>
                                                <th>Facture</th>
                                                <th>Mode de Paiement</th>
                                                <th class="text-end">Montant (€)</th>
                                                <th>Code Lettrage</th>
                                                <th>Référence</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($payment->customer)
                                                            {{ $payment->customer->name }} (Client)
                                                        @elseif($payment->supplier)
                                                            {{ $payment->supplier->name }} (Fournisseur)
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payment->payable_type === 'App\\Models\\Invoice')
                                                            Facture Vente: {{ $payment->payable->numdoc ?? 'N/A' }}
                                                        @elseif($payment->payable_type === 'App\\Models\\PurchaseInvoice')
                                                            Facture Achat: {{ $payment->payable->numdoc ?? 'N/A' }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ $payment->payment_mode }}</td>
                                                    <td class="text-end">{{ number_format($payment->amount, 2, ',', ' ') }}</td>
                                                    <td>{{ $payment->lettrage_code ?? '-' }}</td>
                                                    <td>{{ $payment->reference ?? '-' }}</td>
                                                    <td>{{ $payment->notes ?? '-' }}</td>
                                                </tr>
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
                ordering: true
            });
        });
    </script>
</body>
</html>