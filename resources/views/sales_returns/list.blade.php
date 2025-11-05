<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Liste des Retours de Vente - AZ ERP</title>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card {
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            font-size: 1.8rem;
            color: #007bff;
            font-weight: 700;
        }
        .card h6 {
            font-size: 1rem;
            color: #6c757d;
        }
        .card-body {
            padding: 2rem;
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
        .text-info {
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
                    <h4>📋 Liste des retours de vente :</h4>
<div class="alert alert-primary" role="alert">
  La création d’un retour de vente se fait à partir d’un <a href="/delivery_notes/list" class="alert-link">bon de livraison</a>, ou est générée automatiquement à partir d’un avoir.
</div>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="GET" action="{{ route('delivery_notes.salesreturns.list') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                        <select name="customer_id" class="form-select form-select-sm select2" style="width: 150px;">
                            <option value="">Client (Tous)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="delivery_note_id" class="form-select form-select-sm select2" style="width: 150px;">
                            <option value="">Bon de livraison (Tous)</option>
                            @foreach($deliveryNotes as $deliveryNote)
                                <option value="{{ $deliveryNote->id }}" {{ request('delivery_note_id') == $deliveryNote->id ? 'selected' : '' }}>
                                    {{ $deliveryNote->numdoc }}
                                </option>
                            @endforeach
                        </select>
                        <select name="type" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Type (Tous)</option>
                            <option value="total" {{ request('type') == 'total' ? 'selected' : '' }}>Total</option>
                            <option value="partiel" {{ request('type') == 'partiel' ? 'selected' : '' }}>Partiel</option>
                        </select>
                        <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" value="{{ request('date_from') }}">
                        <span>à</span>
                        <input type="date" name="date_to" class="form-control form-control-sm" style="width: 120px;" value="{{ request('date_to') }}">
                        <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fas fa-filter me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('delivery_notes.salesreturns.list') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-undo me-1"></i> Réinitialiser
                        </a>
                    </form>


                                                                                                                        <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $returns->appends(request()->query())->links() }}
</div>


                    @foreach($returns as $return)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                <div>
                                    <h6 class="mb-0">
                                        <strong>Retour N° : {{ $return->numdoc }}</strong> –
                                        Client : {{ $return->customer->name ?? 'Inconnu' }}
                                        <span class="text-muted small">({{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }})</span>
                                    </h6>
                                    <span class="badge bg-primary">{{ ucfirst($return->type) }}</span>
                                    <span class="badge bg-info">BL: {{ $return->deliveryNote->numdoc ?? '-' }}</span>
                                    @if($return->invoiced)
                                        <span class="badge bg-success">Facturé</span>
                                    @endif
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $return->id }})">
                                        ➕ Détails
                                    </button>

                                    <a href="{{ route('delivery_notes.salesreturns.export_single', $return->id) }}" class="btn btn-sm btn-outline-success">
                                        EXCEL <i class="fas fa-file-excel"></i>
                                    </a>
                                    <a href="{{ route('delivery_notes.salesreturns.print_single', $return->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        PDF <i class="fas fa-print"></i>
                                    </a>

                                    <div class="btn-group">
                                <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('delivery_notes.salesreturns.show', $return->id) }}">
                                        <i class="fas fa-eye"></i> Voir détails
                                    </a>
                                </div>
                            </div>


                                </div>
                            </div>
                            <div id="lines-{{ $return->id }}" class="card-body d-none bg-light">
                                <h6 class="fw-bold mb-3">🧾 Lignes du retour</h6>
                                <table class="table table-sm table-bordered table-striped align-middle">
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
                                        @foreach($return->lines as $line)
                                            <tr>
                                                <td>{{ $line->article_code }}</td>
                                                <td>{{ $line->item->name ?? '-' }}</td>
                                                <td class="text-center">{{ $line->returned_quantity }}</td>
                                                <td class="text-end">{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                                <td class="text-end">{{ $line->remise }}%</td>
                                                <td class="text-end">{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="text-end mt-3">
                                    <div class="p-3 bg-white border rounded d-inline-block">
                                        <strong>Total HT :</strong> {{ number_format($return->total_ht, 2, ',', ' ') }} €<br>
                                        <strong>Total TTC :</strong> {{ number_format($return->total_ttc, 2, ',', ' ') }} €
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                                                                                                                                            <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $returns->appends(request()->query())->links() }}
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
    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- Chart Circle -->
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <!-- Select2 -->
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