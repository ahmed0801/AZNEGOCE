<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Liste des Factures de Vente</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- jQuery + Bootstrap JS (v4) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .table { width: 100%; margin-bottom: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f2f2f2; }
        .btn-sm { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
        .text-muted { font-size: 0.85rem; }
        .text-center { text-align: center; }
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
        .card-body { padding: 2rem; }
        .card .text-info { color: #17a2b8 !important; }
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
        .form-select-sm { width: auto; display: inline-block; }
        .badge { font-size: 0.85rem; }
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
                        <li class="nav-item"><a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item active"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
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
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
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
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="container mt-4">
                        <h4>📋 Liste des factures de vente :
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Nouvelle facture <i class="fas fa-plus-circle ms-2"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('salesinvoices.create_grouped') }}">Facture groupée</a>
                                    <a class="dropdown-item" href="{{ route('salesinvoices.create_free') }}">Facture libre</a>
                                </div>
                            </div>
                        </h4>

                        <form method="GET" action="{{ route('salesinvoices.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                            <select name="customer_id" class="form-select form-select-sm select2" style="width: 150px;">
                                <option value="">Client (Tous)</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" class="form-select form-select-sm" style="width: 170px;">
                                <option value="">Statut facture (Tous)</option>
                                <option value="brouillon" {{ request('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="validée" {{ request('status') == 'validée' ? 'selected' : '' }}>Validée</option>
                            </select>
                            <select name="paid" class="form-select form-select-sm" style="width: 120px;">
                                <option value="">Payé (Tous)</option>
                                <option value="1" {{ request('paid') == '1' ? 'selected' : '' }}>Payé</option>
                                <option value="0" {{ request('paid') == '0' ? 'selected' : '' }}>Non payé</option>
                            </select>
                            <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" placeholder="Date début" value="{{ request('date_from') }}">
                            <span class="mx-1">à</span>
                            <input type="date" name="date_to" class="form-control form-control-sm" style="width: 120px;" placeholder="Date fin" value="{{ request('date_to') }}">
                            <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                                <i class="fas fa-filter me-1"></i> Filtrer
                            </button>
                            <button type="submit" name="action" value="export" formaction="{{ route('salesinvoices.export') }}" class="btn btn-outline-success btn-sm px-3">
                                <i class="fas fa-file-excel me-1"></i> EXCEL
                            </button>
                            <a href="{{ route('salesinvoices.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-undo me-1"></i> Réinitialiser
                            </a>
                        </form>

                        @foreach ($invoices as $invoice)
                            <div class="card mb-4 shadow-sm border-0">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                    <div>
                                        <h6 class="mb-0">
                                            <strong>Facture N° : {{ $invoice->numdoc }}</strong> –
                                            &#x1F482;{{ $invoice->customer->name ?? 'N/A' }}
                                            <span class="text-muted small">({{ $invoice->numclient ?? 'N/A' }})</span>
                                            <span class="text-muted small">- 📆{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                                        </h6>
                                        @if($invoice->status === 'brouillon')
                                            <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                                        @else
                                            <span class="badge bg-success">{{ ucfirst($invoice->status) }}</span>
                                        @endif

                                        @if($invoice->status != 'brouillon')
                                        @if($invoice->paid)
                                            <span class="badge bg-success">Payé</span>
                                        @else
                                            <span class="badge bg-danger">Non payé</span>
                                        @endif
                                        @endif

                                             <span class="text-muted small">&#8594; type: {{ ucfirst($invoice->type ?? 'N/A') }}</span>


                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $invoice->id }})">
                                            ➕ Détails
                                        </button>
                                        <a href="{{ route('salesinvoices.export_single', $invoice->id) }}" class="btn btn-xs btn-outline-success">
                                            EXCEL <i class="fas fa-file-excel"></i>
                                        </a>
                                        <a href="{{ route('salesinvoices.print', $invoice->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                            PDF <i class="fas fa-print"></i>
                                        </a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if($invoice->status === 'brouillon')
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.edit', $invoice->id) }}">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                @endif

                                                @if($invoice->status === 'validée')
                                                <a class="dropdown-item" href="{{ route('salesinvoices.printduplicata', $invoice->id) }}" target="_blank">
                                                        <i class="fas fa-print"></i> imp. DUPLICATA
                                                    </a>
                                                    @endif

                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printsansref', $invoice->id) }}" target="_blank">
                                                        <i class="fas fa-print"></i> imp. Sans Réf.
                                                    </a>

                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printsansrem', $invoice->id) }}" target="_blank">
                                                        <i class="fas fa-print"></i> imp. Sans Rémise
                                                    </a>

                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printsans2', $invoice->id) }}" target="_blank">
                                                        <i class="fas fa-print"></i> imp. Sans Réf & Rém
                                                    </a>


                                                @if($invoice->type === 'direct' && $invoice->deliveryNotes()->exists())
                                                    @foreach($invoice->deliveryNotes as $deliveryNote)
                                                        <a class="dropdown-item" href="{{ route('delivery_notes.edit', $deliveryNote->id) }}">
                                                            <i class="fas fa-eye"></i> Bon de livraison #{{ $deliveryNote->numdoc }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                                @if($invoice->status === 'validée' && !$invoice->paid)
                                                    <form action="{{ route('salesinvoices.markAsPaid', $invoice->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Marquer cette facture comme payée ?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-check"></i> Marquer Payé
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="lines-{{ $invoice->id }}" class="card-body d-none bg-light">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-solid fa-car"></i> : {{ $invoice->vehicle ? ($invoice->vehicle->license_plate . ' (' . $invoice->vehicle->brand_name . ' ' . $invoice->vehicle->model_name . ')') : '-' }}</h6>
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Code Article</th>
                                                <th>Désignation</th>
                                                <th>Qté</th>
                                                <th>PU HT</th>
                                                <th>Remise (%)</th>
                                                <th>TVA (%)</th>
                                                <th>Total Ligne</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice->lines as $line)
                                                <tr>
                                                    <td>{{ $line->article_code ?? '-' }}</td>
                                                    <td>{{ $line->item->name ?? $line->description ?? '-' }}</td>
                                                    <td class="text-center">{{ $line->quantity }}</td>
                                                    <td class="text-end">{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                                    <td class="text-end">{{ $line->remise ?? 0 }}%</td>
                                                    <td class="text-end">{{ $invoice->tva_rate ?? 0 }}%</td>
                                                    <td class="text-end">{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-end mt-3">
                                        <div class="p-3 bg-white border rounded d-inline-block">
                                            <strong>Total HT :</strong> {{ number_format($invoice->total_ht, 2, ',', ' ') }} €<br>
                                            <strong>Total TTC :</strong> {{ number_format($invoice->total_ttc, 2, ',', ' ') }} €
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.</div>
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