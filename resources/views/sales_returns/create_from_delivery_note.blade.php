<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ NEGOCE - Créer un Retour Vente</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
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
            background: linear-gradient(135deg, #dc3545, #a71d2a);
            border-radius: 8px 8px 0 0;
        }
        .card h3 {
            font-size: 1.6rem;
            color: #dc3545;
            font-weight: 600;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn-primary, .btn-success, .btn-danger {
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
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
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
            max-width: 600px;
        }
        .small-text {
            font-size: 0.95em;
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
            .btn-primary, .btn-success, .btn-danger {
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
                        <li class="nav-item active"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/salesnotes/list"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
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
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
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
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
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
                            <h5 class="mb-0">Liste des retours d'achat</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('sales_returns.list') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                                <select name="customer_id" class="form-select form-select-sm select2" style="width: 150px;">
                                    <option value="">Client (Tous)</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="delivery_note_id" class="form-select form-select-sm" style="width: 170px;">
                                    <option value="">BL (Tous)</option>
                                    @foreach($deliveryNotes as $note)
                                        <option value="{{ $note->id }}" {{ request('delivery_note_id') == $note->id ? 'selected' : '' }}>
                                            {{ $note->numdoc }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="type" class="form-select form-select-sm" style="width: 160px;">
                                    <option value="">Type (Tous)</option>
                                    <option value="total" {{ request('type') == 'total' ? 'selected' : '' }}>Total</option>
                                    <option value="partiel" {{ request('type') == 'partiel' ? 'selected' : '' }}>Partiel</option>
                                    <option value="libre" {{ request('type') == 'libre' ? 'selected' : '' }}>Libre</option>
                                </select>

                                <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" placeholder="Date début" value="{{ request('date_from') }}">
                                <span>à</span>
                                <input type="date" name="date_to" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ request('date_to') }}">

                                <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>

                                <button type="submit" name="action" value="export" formaction="{{ route('sales_returns.export') }}" class="btn btn-outline-success btn-sm px-3">
                                    <i class="fas fa-file-excel me-1"></i> Exporter
                                </button>

                                <a href="{{ route('sales_returns.list') }}" class="btn btn-outline-secondary btn-sm px-3">
                                    <i class="fas fa-undo me-1"></i> Réinitialiser
                                </a>
                            </form>

                            @foreach ($returns as $return)
                                <div class="card mb-4 shadow-sm border-0">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                        <div>
                                            <h6 class="mb-0">
                                                <strong>Retour N° : {{ $return->numdoc }}</strong> –
                                                {{ $return->customer->name }}
                                                <span class="text-muted small">({{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }})</span>
                                            </h6>
                                            <span class="badge bg-{{ $return->type === 'total' ? 'danger' : ($return->type === 'partiel' ? 'warning' : 'info') }}">{{ ucfirst($return->type) }}</span>
                                            <span class="text-muted small">
                                                @if($return->deliveryNote)
                                                    | BL: {{ $return->deliveryNote->numdoc }}
                                                @endif
                                                @if($return->invoiced)
                                                    | ☑ Facturé
                                                @endif
                                            </span>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $return->id }})">
                                                ➕ Détails
                                            </button>
                                            <a href="{{ route('sales_returns.export_single', $return->id) }}" class="btn btn-xs btn-outline-success">
                                                EXCEL <i class="fas fa-file-excel"></i>
                                            </a>
                                            <a href="{{ route('sales_returns.print_single', $return->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                                PDF <i class="fas fa-print"></i>
                                            </a>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('sales_returns.edit', $return->id) }}">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                    @if(!$return->invoiced)
                                                        <a class="dropdown-item" href="{{ route('avoirs.create_from_return', $return->id) }}">
                                                            <i class="fas fa-file-invoice"></i> Créer un avoir
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="lines-{{ $return->id }}" class="card-body d-none bg-light">
                                        <h6 class="fw-bold mb-3">🧾 Lignes du retour</h6>
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th>Code Article</th>
                                                    <th>Désignation</th>
                                                    <th>Qté Retournée</th>
                                                    <th>PU HT</th>
                                                    <th>Remise (%)</th>
                                                    <th>Total Ligne</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($return->lines as $line)
                                                    <tr>
                                                        <td>{{ $line->article_code }}</td>
                                                        <td>{{ $line->item->name ?? '-' }}</td>
                                                        <td class="text-center">{{ $line->returned_quantity }}</td>
                                                        <td class="text-end">{{ number_format($line->unit_price_ht, 2) }} €</td>
                                                        <td class="text-end">{{ $line->remise }}%</td>
                                                        <td class="text-end">{{ number_format($line->total_ligne_ht, 2) }} €</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="text-end mt-3">
                                            <div class="p-3 bg-white border rounded d-inline-block">
                                                <strong>Total HT :</strong> {{ number_format($return->total_ht, 2) }} €<br>
                                                <strong>Total TTC :</strong> {{ number_format($return->total_ttc, 2) }} €
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });
        });

        function toggleLines(id) {
            const section = document.getElementById('lines-' + id);
            section.classList.toggle('d-none');
        }
    </script>
</body>
</html>