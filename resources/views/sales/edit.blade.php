<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Modifier une commande de vente</title>
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
                            <h5 class="mb-0">Modifier la commande de vente #{{ $order->numdoc }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sales.update', $order->numdoc) }}" method="POST" id="salesForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-2">

                                    <a href="/newcustomer"
   onclick="window.open(this.href, 'popupWindow', 'width=1200,height=700,scrollbars=yes'); return false;"
   class="btn btn-outline-success btn-round ms-2">
  Créer ou Modifier des Clients <i class="fas fa-plus-circle ms-1"></i>
</a>


                                    <a href="/articles"
   onclick="window.open(this.href, 'popupWindow', 'width=1200,height=700,scrollbars=yes'); return false;"
   class="btn btn-outline-primary btn-round ms-2">
  Créer ou Modifier des Articles <i class="fas fa-plus-circle ms-1"></i>
</a>


<hr>


                                        <label class="form-label">Client</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            <option value="" disabled>Sélectionner un client</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                        data-tva="{{ $customer->tvaGroup->rate ?? 0 }}"
                                                        data-code="{{ $customer->code ?? '' }}"
                                                        data-name="{{ $customer->name }}"
                                                        data-email="{{ $customer->email ?? '' }}"
                                                        data-phone1="{{ $customer->phone1 ?? '' }}"
                                                        data-phone2="{{ $customer->phone2 ?? '' }}"
                                                        data-address="{{ $customer->address ?? '' }}"
                                                        data-address_delivery="{{ $customer->address_delivery ?? '' }}"
                                                        data-city="{{ $customer->city ?? '' }}"
                                                        data-country="{{ $customer->country ?? '' }}"
                                                        {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tva_rate" id="tva_rate" value="{{ $order->tva_rate }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Date de commande</label>
<input type="date" name="order_date" id="order_date" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" class="form-control" required>                                    </div>
                                </div>
                                <div class="mb-3" id="customer_details">
                                    <h6 class="font-weight-bold mb-2">Détails du client</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nom:</strong> <span id="customer_name">{{ $order->customer->name ?? 'N/A' }}</span></p>
                                            <!-- <p><strong>Code client:</strong> <span id="customer_code">{{ $order->customer->code ?? 'N/A' }}</span></p>
                                            <p><strong>Taux TVA:</strong> <span id="customer_tva">{{ $order->tva_rate }}</span>%</p> -->
                                            <p><strong>Email:</strong> <span id="customer_email">{{ $order->customer->email ?? 'N/A' }}</span></p>
                                            <p><strong>Téléphone 1:</strong> <span id="customer_phone1">{{ $order->customer->phone1 ?? 'N/A' }}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Téléphone 2:</strong> <span id="customer_phone2">{{ $order->customer->phone2 ?? 'N/A' }}</span></p>
                                            <p><strong>Adresse:</strong> <span id="customer_address">{{ $order->customer->address ?? 'N/A' }}</span></p>
                                            <p><strong>Adresse de livraison:</strong> <span id="customer_address_delivery">{{ $order->customer->address_delivery ?? 'N/A' }}</span></p>
                                            <!-- <p><strong>Ville:</strong> <span id="customer_city">{{ $order->customer->city ?? 'N/A' }}</span></p>
                                            <p><strong>Pays:</strong> <span id="customer_country">{{ $order->customer->country ?? 'N/A' }}</span></p> -->
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
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lines_body">
                                                @foreach ($order->lines as $line)
                                                    @php
                                                        $item = \App\Models\Item::where('code', $line->article_code)->first();
                                                        $costPrice = $item ? $item->cost_price : 0;
                                                        $salePrice = $item ? $item->sale_price : $line->unit_price_ht;
                                                        $stock = $item ? $item->stock_quantity : 0;
                                                        $location = $item ? ($item->location ?? '-') : '-';
                                                        $isActive = $item ? $item->is_active : false;
                                                    @endphp
                                                    <tr data-line-id="{{ $loop->index }}">
                                                        <td>
                                                            {{ $line->article_code }}<br>
                                                            <span class="badge bg-{{ $isActive ? 'success' : 'danger' }} badge-very-sm">{{ $isActive ? '🟢 actif' : '🔴 bloqué' }}</span>
                                                            <input type="hidden" name="lines[{{ $loop->index }}][line_id]" value="{{ $line->id }}">
                                                            <input type="hidden" name="lines[{{ $loop->index }}][article_code]" value="{{ $line->article_code }}">
                                                        </td>
                                                        <td>{{ $item ? $item->name : '-' }}</td>
                                                        <td>{{ number_format($costPrice, 2) }} €</td>
                                                        <td>
                                                            {{ number_format($salePrice, 2) }} €<br>
                                                            <small class="{{ $salePrice >= $costPrice ? 'text-success' : 'text-danger' }} small-text">
                                                                {{ $salePrice >= $costPrice ? '+' : '' }}{{ number_format($salePrice - $costPrice, 2) }} €
                                                                ({{ $costPrice > 0 ? number_format((($salePrice - $costPrice) / $costPrice) * 100, 0) : 0 }}%)
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-primary btn-sm stock-details-btn" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#stockDetailsModal" 
                                                                    data-code="{{ $line->article_code }}"
                                                                    data-name="{{ $item ? $item->name : '-' }}"
                                                                    title="Voir les détails du stock">
                                                                {{ number_format($stock, 0) }}
                                                            </button><br>
                                                            <small class="text-muted" style="font-size: 0.7rem;">📦 {{ $location }}</small>
                                                        </td>
                                                        <td><input type="number" name="lines[{{ $loop->index }}][ordered_quantity]" class="form-control quantity" value="{{ $line->ordered_quantity }}" min="0"></td>
                                                        <td><input type="number" name="lines[{{ $loop->index }}][unit_price_ht]" class="form-control unit_price_ht" value="{{ number_format($line->unit_price_ht, 2) }}" step="0.01"></td>
                                                        <td><input type="number" name="lines[{{ $loop->index }}][remise]" class="form-control remise" value="{{ $line->remise }}" min="0" max="100" step="0.01"></td>
                                                        <td class="text-right total_ht">{{ number_format($line->total_ligne_ht, 2) }}</td>
                                                        <td class="text-right total_ttc">{{ number_format($line->total_ligne_ttc, 2) }}</td>
                                                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line">×</button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="total-display mt-2 text-end">
                                        <h5 class="mb-1">Total HT : <span id="total_ht_global" class="text-success fw-bold">{{ number_format($order->total_ht, 2) }}</span> €</h5>
                                        <h6 class="mb-0">Total TTC : <span id="total_ttc_global" class="text-danger fw-bold">{{ number_format($order->total_ttc, 2) }}</span> €</h6>
                                    </div>
<a href="/articles" 
                                    onclick="window.open(this.href, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); return false;"
                                     type="button" class="btn btn-outline-success btn-round ms-2">+ Créer ou Modifier des Articles</a>
                                                                    </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes / Commentaire</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de livraison, etc.">{{ $order->notes }}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="action" value="save" class="btn btn-primary px-3">✅ Enregistrer Brouillon</button>
                                    <button type="submit" name="action" value="validate" class="btn btn-success px-3 ms-2">✔️ Valider BL</button>
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
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
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
            $('.select2').select2({ width: '100%' });
            let tvaRates = @json($tvaRates);
            let lineCount = {{ $order->lines->count() }};

            function updateGlobalTotals() {
                let totalHtGlobal = 0;
                let totalTtcGlobal = 0;
                $('#lines_body tr').each(function () {
                    let totalHt = parseFloat($(this).find('.total_ht').text()) || 0;
                    let totalTtc = parseFloat($(this).find('.total_ttc').text()) || 0;
                    totalHtGlobal += totalHt;
                    totalTtcGlobal += totalTtc;
                });
                $('#total_ht_global').text(totalHtGlobal.toFixed(2));
                $('#total_ttc_global').text(totalTtcGlobal.toFixed(2));
                console.log('Global Totals - HT:', totalHtGlobal, 'TTC:', totalTtcGlobal);
            }

            $('#customer_id').change(function () {
                let customerId = $(this).val();
                let $selectedOption = $(this).find('option:selected');
                let tvaRate = customerId ? parseFloat($selectedOption.data('tva') || 0) : 0;
                let tvaRateFromObject = customerId ? parseFloat(tvaRates[customerId] || 0) : 0;

                if (customerId && isNaN(tvaRateFromObject)) {
                    console.warn('tvaRates[customerId] is NaN for Customer ID:', customerId, 'Using data-tva:', tvaRate);
                }
                if (customerId && tvaRate !== tvaRateFromObject && !isNaN(tvaRateFromObject)) {
                    console.warn('TVA Mismatch - Customer ID:', customerId, 'data-tva:', tvaRate, 'tvaRates[customerId]:', tvaRateFromObject);
                }

                if (customerId) {
                    if (tvaRate === 0 && $selectedOption.data('tva') == null) {
                        Swal.fire('Erreur', 'Taux TVA non défini pour ce client.', 'error');
                        console.error('TVA Rate undefined for Customer ID:', customerId, 'tvaRates:', tvaRates);
                    }
                    $('#customer_name').text($selectedOption.data('name') || 'N/A');
                    $('#customer_code').text($selectedOption.data('code') || 'N/A');
                    $('#customer_tva').text(tvaRate.toFixed(2));
                    $('#customer_email').text($selectedOption.data('email') || 'N/A');
                    $('#customer_phone1').text($selectedOption.data('phone1') || 'N/A');
                    $('#customer_phone2').text($selectedOption.data('phone2') || 'N/A');
                    $('#customer_address').text($selectedOption.data('address') || 'N/A');
                    $('#customer_address_delivery').text($selectedOption.data('address_delivery') || 'N/A');
                    $('#customer_city').text($selectedOption.data('city') || 'N/A');
                    $('#customer_country').text($selectedOption.data('country') || 'N/A');
                    $('#customer_details').show();
                    $('#tva_rate').val(tvaRate);
                } else {
                    $('#customer_details').hide();
                    $('#tva_rate').val(0);
                }

                $('#lines_body tr').each(function () {
                    let unitPriceHt = parseFloat($(this).find('.unit_price_ht').val()) || 0;
                    let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    let remise = parseFloat($(this).find('.remise').val()) || 0;
                    updateLineTotals($(this), unitPriceHt, quantity, remise, tvaRate);
                });

                updateGlobalTotals();
                console.log('Customer ID:', customerId, 'TVA Rate (data-tva):', tvaRate, 'tvaRates[customerId]:', tvaRateFromObject, 'tvaRates:', tvaRates);
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
                                            ${item.name} (${item.code}) - ${item.sale_price} €
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
                if (!customerId) {
                    Swal.fire('Erreur', 'Veuillez sélectionner un client avant d\'ajouter un article.', 'error');
                    return;
                }
                let tvaRate = parseFloat($('#customer_id').find('option:selected').data('tva') || 0);
                if (tvaRate === 0 && $('#customer_id').find('option:selected').data('tva') == null) {
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
                                    data-bs-toggle="modal" 
                                    data-bs-target="#stockDetailsModal" 
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
                        <td class="text-right total_ht">0.00</td>
                        <td class="text-right total_ttc">0.00</td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line">×</button></td>
                    </tr>
                `;
                $('#lines_body').append(row);
                updateLineTotals($('#lines_body tr:last'), price, 1, 0, tvaRate);
                lineCount++;
                $('#search_results').empty();
                $('#search_item').val('');
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
                let tvaRate = customerId ? parseFloat($('#customer_id').find('option:selected').data('tva') || 0) : 0;
                if (customerId && $('#customer_id').find('option:selected').data('tva') == null) {
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
                let totalTtc = totalHt + (totalHt * tvaRate / 100);

                row.find('.total_ht').text(totalHt.toFixed(2));
                row.find('.total_ttc').text(totalTtc.toFixed(2));

                console.log('Line Totals - HT:', totalHt, 'TTC:', totalTtc, 'TVA Rate:', tvaRate);
            }

            // Stock Details Modal Handler
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

            // Initialize customer details and totals
            $('#customer_id').trigger('change');
            updateGlobalTotals();
        });
    </script>
</body>
</html>