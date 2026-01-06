<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Modifier une commande de vente</title>
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
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .card { border-radius: 8px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card-header { background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 8px 8px 0 0; }
        .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-info {
            font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 6px; transition: all 0.3s ease;
        }
        .table { width: 100%; margin-bottom: 0; background-color: #fff; border-radius: 6px; overflow: hidden; }
        .table th, .table td { padding: 0.5rem; font-size: 0.85rem; vertical-align: middle; }
        .table thead { background: #f8f9fa; position: sticky; top: 0; z-index: 10; }
        .table-responsive { max-height: 350px; overflow-y: auto; }
        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 6px; border: 1px solid #ced4da; padding: 0.4rem; font-size: 0.9rem;
        }
        #customer_details { background: #f8f9fa; border-radius: 6px; padding: 1rem; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05); }
        #search_results { background: #eaf3fc; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); max-height: 180px; overflow-y: auto; }
        .total-display { background: #f8f9fa; border-radius: 6px; padding: 0.8rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .table-text-small th, .table-text-small td, .table-text-small input { font-size: 0.8rem !important; }
        .purchase-price-block { font-size: 0.82rem; line-height: 1.2; }
        .net-price { min-width: 70px; font-weight: 600; color: #28a745 !important; }
        .margin-display { font-weight: 600; }
        #lines_table .quantity, #lines_table .unit_price_ht, #lines_table .unit_price_ttc,
        #lines_table .remise, #lines_table .cost-price-input, #lines_table .purchase-discount {
            width: 62px !important; min-width: 62px !important; max-width: 62px !important;
            padding: 0.25rem 0.35rem !important; font-size: 0.80rem !important; text-align: center;
        }
        @media (max-width: 768px) {
            #lines_table .quantity, #lines_table .unit_price_ht, #lines_table .unit_price_ttc,
            #lines_table .remise, #lines_table .cost-price-input, #lines_table .purchase-discount {
                width: 55px !important; font-size: 0.75rem !important;
            }
        }
        #lines_table thead th { padding: 0.35rem 0.5rem !important; font-size: 0.80rem !important; height: 42px !important; }
        #vehicle_group { display: none; }
    </style>
</head>
<body>
    <div class="wrapper sidebar_minimize">
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <div class="logo-header" data-background-color="dark">
                    <a href="/" class="logo">
                        <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="70" />
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
                        <li class="nav-item"><a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#ventes" aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i><p>Ventes</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="ventes">
                                <ul class="nav nav-collapse">
                                    <li><a href="/sales/delivery/create"><span class="sub-item">Nouvelle Commande</span></a></li>
                                    <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
                                    <li><a href="/delivery_notes/list"><span class="sub-item">Bons de Livraison</span></a></li>
                                    <li><a href="/delivery_notes/returns/list"><span class="sub-item">Retours Vente</span></a></li>
                                    <li><a href="/salesinvoices"><span class="sub-item">Factures</span></a></li>
                                    <li><a href="/salesnotes/list"><span class="sub-item">Avoirs</span></a></li>
                                </ul>
                            </div>
                        </li>
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
                        <li class="nav-item"><a href="/contact"><i class="fas fa-headset"></i><p>Assistance</p></a></li>
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display: none;">@csrf</form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

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
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <i class="fas fa-layer-group"></i>
                                </a>
                                <div class="dropdown-menu quick-actions animated fadeIn">
                                    <div class="quick-actions-header">
                                        <span class="title mb-1">Actions Rapides</span>
                                    </div>
                                    <div class="quick-actions-scroll scrollbar-outer">
                                        <div class="quick-actions-items">
                                            <div class="row m-0">
                                                <a class="col-6 col-md-4 p-0" href="/articles"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fas fa-sitemap"></i></div><span class="text">Articles</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/customers"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-users"></i></div><span class="text">Clients</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/suppliers"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-user-tag"></i></div><span class="text">Fournisseurs</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/delivery_notes/list"><div class="quick-actions-item"><div class="avatar-item bg-danger rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Ventes</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/salesinvoices"><div class="quick-actions-item"><div class="avatar-item bg-warning rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Ventes</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/generalaccounts"><div class="quick-actions-item"><div class="avatar-item bg-info rounded-circle"><i class="fas fa-money-check-alt"></i></div><span class="text">Plan Comptable</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/purchases/list"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Achats</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/invoices"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Achats</span></div></a>
                                                <a class="col-6 col-md-4 p-0" href="/paymentlist"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-credit-card"></i></div><span class="text">Paiements</span></div></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username"><span class="fw-bold">{{ Auth::user()->name }}</span></span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg"><img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" /></div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramètres</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('logout.admin') }}" method="POST" style="display: inline;">@csrf
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
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if(session('error'))<div class="alert alert-danger">{!! session('error') !!}</div>@endif

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
                                    <div class="col-md-4 mb-3">
                                        <button type="button" id="openCreateCustomerModal" class="btn btn-outline-success btn-round">
                                            Nouveau Client <i class="fas fa-plus-circle ms-1"></i>
                                        </button>
                                        <a href="/newcustomer" onclick="window.open(this.href,'popupWindow','width=1200,height=700,scrollbars=yes'); return false;" class="btn btn-outline-primary btn-round ms-2">Liste Clients ⟰</a>
                                        <hr>
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            <option value="" disabled>Sélectionner un client</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" data-tva="{{ $customer->tvaGroup->rate ?? 0 }}"
                                                    {{ $customer->id == $order->customer_id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tva_rate" id="tva_rate" value="{{ $order->tva_rate }}">
                                    </div>

                                    <div class="col-md-5 mb-3" id="vehicle_group">
                                        <label class="form-label fw-semibold text-dark">
                                            Associer un véhicule <span class="text-muted fw-normal fs-sm">(Automatique via plaque)</span>
                                        </label>
                                        <div class="input-group input-group-sm mb-3">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            <input type="text" id="plate_search" class="form-control" placeholder="Ex: AB-123-CD">
                                            <button type="button" id="searchByPlateBtn" class="btn btn-outline-primary">
                                                <span class="spinner-border spinner-border-sm d-none"></span> Rechercher plaque
                                            </button>
                                        </div>
                                        <select name="vehicle_id" id="vehicle_id" class="form-select select2-vehicle">
                                            <option value="">Aucun véhicule</option>
                                        </select>
                                        <div class="btn-group mt-2">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleInlineModal">
                                                <i class="fas fa-plus"></i> Nouveau (TECDOC)
                                            </button>
                                            <a href="javascript:void(0)" id="loadCatalogBtn" class="btn btn-primary btn-sm" disabled>Catalogue</a>
                                            <a href="javascript:void(0)" id="viewHistoryBtn" class="btn btn-secondary btn-sm" disabled>Historique</a>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Date de commande</label>
                                        <input type="date" name="order_date" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3" id="customer_details">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Client:</strong> <span id="customer_name">{{ $order->customer->name ?? '' }}</span></p>
                                            <p><strong>TVA:</strong> <span id="customer_tva">{{ $order->tva_rate }}</span>%</p>
                                            <!-- <p><strong>Email:</strong> <span id="customer_email">{{ $order->customer->email ?? '' }}</span></p>
                                            <p><strong>Téléphones :</strong> <span id="customer_phone1">{{ $order->customer->phone1 ?? '' }}</span> / <span id="customer_phone2">{{ $order->customer->phone2 ?? '' }}</span></p> -->
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Adresse:</strong> <span id="customer_address">{{ $order->customer->address ?? '' }}</span></p>
                                            <p><strong>Solde:</strong> <button type="button" class="btn btn-outline-info btn-sm balance-btn" id="balanceBtn" disabled>
                                                <i class="fas fa-balance-scale me-1"></i> <span id="customer_balance">0,00 €</span>
                                            </button></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rechercher un article</label>
                                    <input type="text" id="search_item" class="form-control" placeholder="Par référence ou description, minimum 4 caractères">
                                    <u><small class="form-text text-muted">Astuce : ajoutez <strong>%</strong> avant/après pour élargir la recherche.</small></u>
                                    <div id="search_results" class="mt-2"></div>
                                </div>

                                <div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-labelledby="itemDetailsLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="itemDetailsLabel">Détails de l’article</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered table-striped mb-0">
                                                    <tbody id="itemDetailsBody"></tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="font-weight-bold mb-2 d-flex align-items-center flex-wrap gap-2">
                                        <button type="button" id="add_divers_item" class="btn btn-outline-primary btn-sm">+ Créer un article manuellement</button>
                                        <button type="button" id="add_divers_item" data-type="consigne" class="btn btn-outline-dark btn-sm">+ Consigne</button>
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-text-small" id="lines_table">
                                            <thead class="table-dark">
                                                <tr style="height: 42px;">
                                                    <th class="py-1">Référence</th>
                                                    <th class="py-1">Désignation</th>
                                                    <th class="py-1 text-center"><div class="small fw-bold">Prix Achat HT</div><div class="text-info" style="font-size: 0.63rem;">Remise → Prix Net → Marge</div></th>
                                                    <th class="py-1 text-center">Stock</th>
                                                    <th class="py-1 text-center">Qté</th>
                                                    <th class="py-1 text-center">PU HT</th>
                                                    <th class="py-1 text-center">PU TTC</th>
                                                    <th class="py-1 text-center">Rem %</th>
                                                    <th class="py-1 text-center">Total HT</th>
                                                    <th class="py-1 text-center">Total TTC</th>
                                                    <th class="py-1"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="lines_body">
                                                @foreach ($order->lines as $index => $line)
                                                    @php
                                                        $item = \App\Models\Item::where('code', $line->article_code)->first();
                                                        $costPrice = $line->unit_coast ?? ($item->cost_price ?? 0);
                                                        $supplierId = $line->supplier_id ?? ($item->supplier_id ?? null);
                                                        $purchaseDiscount = $line->discount_coast ?? 0;
                                                        $stock = $item->stock_quantity ?? 0;
                                                        $location = $item->location ?? '-';
                                                        $isActive = $item->is_active ?? false;
                                                    @endphp
                                                    <tr data-line-id="{{ $index }}">
                                                        <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                <span class="font-weight-bold">{{ $line->article_code }}</span>
                                                                <button type="button" class="btn btn-xs btn-outline-secondary copy-line-code px-1 py-0" data-code="{{ $line->article_code }}"><i class="fas fa-copy"></i></button>
                                                            </div><br>
                                                            <span class="badge bg-{{ $isActive ? 'success' : 'danger' }} badge-very-sm">{{ $isActive ? 'actif' : 'bloqué' }}</span>
                                                            <input type="hidden" name="lines[{{ $index }}][line_id]" value="{{ $line->id }}">
                                                            <input type="hidden" name="lines[{{ $index }}][article_code]" value="{{ $line->article_code }}">
                                                        </td>
                                                        <td>{{ $item ? $item->name : $line->item_name ?? '-' }}</td>
                                                        <td class="p-1">
                                                            <div class="purchase-price-block">
                                                                <div class="d-flex gap-1 align-items-center mb-1">
                                                                    <div class="input-group input-group-sm flex-fill">
                                                                        <span class="input-group-text">€</span>
                                                                        <input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input"
                                                                               value="{{ number_format($costPrice, 2) }}" name="lines[{{ $index }}][unit_coast]">
                                                                    </div>
                                                                    <select class="form-select form-select-sm supplier-select"
                                                                            name="lines[{{ $index }}][supplier_id]" data-selected="{{ $supplierId }}"></select>
                                                                </div>
                                                                <div class="d-flex gap-1 align-items-center justify-content-between">
                                                                    <div class="input-group input-group-sm" style="width:105px;">
                                                                        <input type="number" min="0" max="100" step="0.1"
                                                                               class="form-control form-control-sm text-end purchase-discount"
                                                                               value="{{ $purchaseDiscount }}" name="lines[{{ $index }}][discount_coast]">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                    <span class="text-muted small">→</span>
                                                                    <span class="fw-bold text-success net-price">0,00 €</span>
                                                                </div>
                                                                <small class="text-muted d-block text-end mt-1">
                                                                    Marge nette : <span class="margin-display text-primary fw-bold">0%</span>
                                                                    (<span class="margin-euro text-primary">0,00 €</span>)
                                                                </small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-primary btn-sm stock-details-btn"
                                                                    data-code="{{ $line->article_code }}" data-name="{{ $item ? $item->name : '-' }}">
                                                                {{ $stock }}
                                                            </button><br>
                                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $location }}</small>
                                                        </td>
                                                        <td><input type="number" name="lines[{{ $index }}][ordered_quantity]" class="form-control quantity" value="{{ $line->ordered_quantity }}" min="0"></td>
                                                        <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ht]" class="form-control unit_price_ht" value="{{ number_format($line->unit_price_ht, 2) }}"></td>
                                                        <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ttc]" class="form-control unit_price_ttc" value="{{ number_format($line->unit_price_ttc ?? $line->unit_price_ht * (1 + $order->tva_rate/100), 2) }}"></td>
                                                        <td><input type="number" name="lines[{{ $index }}][remise]" class="form-control remise" value="{{ $line->remise }}" min="0" max="100" step="0.01"></td>
                                                        <td class="text-right total_ht">{{ number_format($line->total_ligne_ht, 2) }}</td>
                                                        <td class="text-right total_ttc">{{ number_format($line->total_ligne_ttc, 2) }}</td>
                                                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="total-display mt-2 text-end">
                                        <h6 class="mb-1">Total HT : <span id="total_ht_global" class="text-danger fw-bold">{{ number_format($order->total_ht, 2) }}</span> €</h6>
                                        <h5 class="mb-0">Total TTC : <span id="total_ttc_global" class="text-success fw-bold">{{ number_format($order->total_ttc, 2) }}</span> €</h5>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes / Commentaire</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" name="action" value="save" class="btn btn-warning px-3">Enregistrer</button>
                                    <button type="submit" name="action" value="validate" class="btn btn-primary px-3 ms-2">Enregistrer et Valider BL</button>
                                    <button type="submit" name="action" value="validate_and_invoice" class="btn btn-success px-3 ms-2">Enregistrer et Facturer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-labelledby="stockDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="stockDetailsModalLabel"></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6><i class="fas fa-warehouse me-1"></i> Quantité actuelle par magasin :</h6>
                            <table class="table table-bordered table-sm" id="stockTable">
                                <thead class="table-light"><tr><th>Magasin</th><th>Quantité</th><th>Dernière mise à jour</th></tr></thead>
                                <tbody id="stockTableBody"></tbody>
                            </table>
                            <hr>
                            <h6><i class="fas fa-exchange-alt me-1"></i> Mouvements de stock récents :</h6>
                            <table class="table table-bordered table-sm" id="movementTable">
                                <thead class="table-light"><tr><th>Type</th><th>QTE</th><th>Magasin</th><th>Prix.HT</th><th>Source</th><th>Référence</th></tr></thead>
                                <tbody id="movementTableBody"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addVehicleInlineModal" tabindex="-1" aria-labelledby="addVehicleInlineModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addVehicleInlineModalLabel"><i class="fas fa-car"></i> Associer un nouveau véhicule</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="quickVehicleForm">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Immatriculation <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="quick_license_plate" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Marque <span class="text-danger">*</span></label>
                                        <select id="quick_brand_id" class="form-control select2-brand" style="width:100%;" required>
                                            <option value="">Rechercher une marque...</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="quick_brand_name">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Modèle <span class="text-danger">*</span></label>
                                        <select id="quick_model_id" class="form-control select2-model" style="width:100%;" required disabled>
                                            <option value="">D'abord choisir une marque</option>
                                        </select>
                                        <input type="hidden" id="quick_model_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Motorisation <span class="text-danger">*</span></label>
                                        <select id="quick_engine_id" class="form-control select2-engine" style="width:100%;" required disabled>
                                            <option value="">D'abord choisir un modèle</option>
                                        </select>
                                        <input type="hidden" id="quick_engine_description">
                                        <input type="hidden" id="quick_linkage_target_id">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Créer et sélectionner</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.</div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.suppliersList = @json($suppliersForSelect2);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $(document).ready(function () {
            function round(number, decimals = 2) {
                const factor = Math.pow(10, decimals);
                return Math.round((number * factor) + Number.EPSILON) / factor;
            }
            function formatFrench(number, decimals = 2) {
                return round(number, decimals).toLocaleString('fr-FR', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
            }

            $('#customer_id').select2({ width: '100%' });
            $('#vehicle_id').select2({ width: '100%', placeholder: 'Aucun véhicule', allowClear: true });

            let lineCount = $('#lines_body tr').length;

            if ($('#customer_id').val()) {
    let customerId = $('#customer_id').val();
    let currentVehicleId = {{ $order->vehicle_id ?? 'null' }}; // ID du véhicule actuel dans la commande
    let tvaRate = parseFloat($('#customer_id option:selected').data('tva') || 0);
    
    $('#tva_rate').val(tvaRate);
    $('#customer_details').show();
    $('#vehicle_group').show();

    $.get('/customers/' + customerId + '/vehicles', function(data) {
        $('#vehicle_id').empty().append('<option value="">Aucun véhicule</option>');
        
        data.forEach(v => {
            let text = `${v.license_plate} (${v.brand_name} ${v.model_name} - ${v.engine_description})`;
            let option = new Option(text, v.id, false, v.id == currentVehicleId);
            $('#vehicle_id').append(option);
        });

        // === PRÉ-SÉLECTION EXPLICITE DU VÉHICULE ACTUEL ===
        if (currentVehicleId) {
            $('#vehicle_id').val(currentVehicleId).trigger('change');
        }

        // Mise à jour des boutons Catalogue et Historique
        updateCatalogButton();
        updateHistoryButton();
    }).fail(function() {
        console.error("Erreur lors du chargement des véhicules du client");
    });
}

            $('.supplier-select').each(function() {
                let $select = $(this);
                let supplierId = $select.data('selected') || null;
                $select.select2({ width: '100%', placeholder: 'Fournisseur', allowClear: true, data: window.suppliersList });
                if (supplierId) $select.val(supplierId).trigger('change');
            });

            function updateGlobalTotals() {
                let totalHtGlobal = 0;
                let totalTtcGlobal = 0;
                $('#lines_body tr').each(function () {
                    let htText = $(this).find('.total_ht').text().replace(/[^\d,\.-]/g, '').replace(',', '.');
                    let ttcText = $(this).find('.total_ttc').text().replace(/[^\d,\.-]/g, '').replace(',', '.');
                    totalHtGlobal += parseFloat(htText) || 0;
                    totalTtcGlobal += parseFloat(ttcText) || 0;
                });
                $('#total_ht_global').text(formatFrench(totalHtGlobal));
                $('#total_ttc_global').text(formatFrench(totalTtcGlobal));
            }

            let negativeMarginTimeout = null;
            let negativeMarginToastShown = null;

            function updatePurchaseMargin(row) {
                if (row.data('is-consigne') == '1') {
                    row.find('.net-price, .margin-display, .margin-euro').text('—');
                    return;
                }
                let costPrice = parseFloat(row.find('.cost-price-input').val().replace(',', '.')) || 0;
                let purchaseDiscount = parseFloat(row.find('.purchase-discount').val().replace(',', '.')) || 0;
                let saleDiscount = parseFloat(row.find('.remise').val().replace(',', '.')) || 0;
                let salePriceHt = parseFloat(row.find('.unit_price_ht').val().replace(',', '.')) || 0;
                let netCost = round(costPrice * (1 - purchaseDiscount / 100), 4);
                let realSalePrice = round(salePriceHt * (1 - saleDiscount / 100), 4);
                row.find('.net-price').text(formatFrench(netCost) + ' €');
                if (realSalePrice > 0 && netCost > 0) {
                    let marginPct = ((realSalePrice - netCost) / netCost) * 100;
                    let marginEur = realSalePrice - netCost;
                    row.find('.margin-display').text(marginPct.toFixed(1) + '%');
                    row.find('.margin-euro').text(formatFrench(marginEur) + ' €');
                    let $m = row.find('.margin-display');
                    $m.removeClass('text-danger text-warning text-success');
                    if (marginPct >= 50) $m.addClass('text-success');
                    else if (marginPct >= 30) $m.addClass('text-warning');
                    else $m.addClass('text-danger');
                    if (marginPct < 0) {
                        if (negativeMarginTimeout) clearTimeout(negativeMarginTimeout);
                        if (negativeMarginToastShown) negativeMarginToastShown.close();
                        negativeMarginTimeout = setTimeout(() => {
                            const code = row.find('td:nth-child(1) .font-weight-bold').text().trim() || '???';
                            const name = row.find('td:nth-child(2)').text().trim();
                            const shortName = name.length > 45 ? name.substring(0,42)+'...' : name;
                            negativeMarginToastShown = Swal.fire({
                                toast: true, position: 'top-end', icon: 'warning', title: 'VENTE À PERTE !',
                                html: `<div class="text-start small ms-3"><b>${code}</b> – ${shortName}<br>Achat net : <b>${formatFrench(netCost)} €</b> → Vente net HT : <b>${formatFrench(realSalePrice)} €</b><br><span class="text-danger fw-bold">Perte : ${formatFrench(Math.abs(marginEur))} € (${marginPct.toFixed(1)}%)</span></div>`,
                                timer: 8000, timerProgressBar: true, background: '#fff8e1'
                            });
                        }, 1700);
                    } else {
                        if (negativeMarginTimeout) clearTimeout(negativeMarginTimeout);
                        if (negativeMarginToastShown) negativeMarginToastShown.close();
                    }
                } else {
                    row.find('.margin-display, .margin-euro').text('—');
                }
            }

            function updateLineTotals(row, tvaRate) {
                tvaRate = parseFloat(tvaRate) || 0;
                let quantity = parseFloat(row.find('.quantity').val().replace(',', '.')) || 0;
                let remise = parseFloat(row.find('.remise').val().replace(',', '.')) || 0;
                let puHt = parseFloat(row.find('.unit_price_ht').val().replace(',', '.')) || 0;
                let puTtc = parseFloat(row.find('.unit_price_ttc').val().replace(',', '.')) || 0;
                let lastModified = row.data('last-modified') || 'ht';
                if (lastModified === 'ttc' && puTtc > 0) {
                    puHt = round(puTtc / (1 + tvaRate / 100));
                    row.find('.unit_price_ht').val(puHt.toFixed(2));
                } else if (lastModified === 'ht' && puHt > 0) {
                    puTtc = round(puHt * (1 + tvaRate / 100));
                    row.find('.unit_price_ttc').val(puTtc.toFixed(2));
                }
                let totalHt = round(puHt * quantity * (1 - remise / 100));
                let totalTtc = round(totalHt * (1 + tvaRate / 100));
                row.find('.total_ht').text(formatFrench(totalHt));
                row.find('.total_ttc').text(formatFrench(totalTtc));
                updateGlobalTotals();
            }

            $(document).on('input', '.unit_price_ht, .unit_price_ttc, .quantity, .remise', function () {
                let row = $(this).closest('tr');
                if ($(this).hasClass('unit_price_ht')) row.data('last-modified', 'ht');
                if ($(this).hasClass('unit_price_ttc')) row.data('last-modified', 'ttc');
                updateLineTotals(row, $('#tva_rate').val());
            });

            $(document).on('input change', '.remise, .cost-price-input, .purchase-discount, .unit_price_ht, .unit_price_ttc', function () {
                updatePurchaseMargin($(this).closest('tr'));
            });

            let searchTimeout;
            $('#search_item').on('input', function () {
                clearTimeout(searchTimeout);
                let query = $(this).val();
                if (query.length > 3) {
                    searchTimeout = setTimeout(() => {
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
                                                 data-code="${item.code}" data-name="${item.name}" data-price="${item.sale_price}"
                                                 data-cost-price="${item.cost_price}" data-stock="${item.stock_quantity || 0}"
                                                 data-location="${item.location || ''}" data-discount-rate="${item.discount_rate || 20}"
                                                 data-discount-rate-jobber="${item.discount_rate_jobber || 0}"
                                                 data-discount-rate-professionnel="${item.discount_rate_professionnel || 0}"
                                                 data-is-active="${item.is_active}" data-supplier-id="${item.supplier_id || ''}">
                                                <span class="badge rounded-pill text-bg-light"><b>${item.code}</b>
                                                    <button class="btn btn-xs btn-outline-secondary copy-code px-1 py-0" data-code="${item.code}"><i class="fas fa-copy"></i></button>
                                                </span> ↔ ${item.name} : ${item.sale_price} € HT
                                                ${item.stock_quantity > 0 ? '<br><button class="btn btn-xs btn-outline-primary voir-details px-2 py-1" style="font-size: 0.75rem;" data-item=\'' + JSON.stringify(item) + '\'><i class="fas fa-eye me-1"></i> Détails Article</button> 🟢 ' + item.stock_quantity + ' En Stock' :
                                                '<br><button class="btn btn-xs btn-outline-primary voir-details px-2 py-1" style="font-size: 0.75rem;" data-item=\'' + JSON.stringify(item) + '\'><i class="fas fa-eye me-1"></i> Détails Article</button> 🔴 Disponible auprès de <span class="badge text-bg-secondary">' + (item.supplier || '') + '</span> au prix de <span class="badge text-bg-success">' + item.cost_price + ' € HT </span>'}
                                            </div><hr class="my-1">
                                        `);
                                    });
                                    $('.copy-code').off('click').on('click', function (e) {
                                        e.stopPropagation();
                                        navigator.clipboard.writeText($(this).data('code'));
                                    });
                                }
                            }
                        });
                    }, 300);
                } else {
                    $('#search_results').empty();
                }
            });

            $(document).on('click', '.voir-details', function (e) {
                e.stopPropagation();
                const item = JSON.parse($(this).attr('data-item'));
                let html = '';
                ['code','name','cost_price','sale_price','stock_quantity','Poids','Hauteur','Longueur','Largeur','Ref_TecDoc','Code_pays','Code_douane','supplier','brand','location'].forEach(k => {
                    html += `<tr><th>${k.replace(/_/g,' ')}</th><td>${item[k] ?? '-'}</td></tr>`;
                });
                html += `<tr><th>État</th><td>${item.is_active ? 'Actif' : 'Inactif'}</td></tr>`;
                $('#itemDetailsBody').html(html);
                $('#itemDetailsModal').modal('show');
            });

            $(document).on('click', '#search_results div', function () {
                let customerId = $('#customer_id').val();
                if (!customerId) return Swal.fire('Erreur', 'Sélectionnez un client d\'abord', 'error');
                let tvaRate = parseFloat($('#customer_id option:selected').data('tva') || 0);
                let code = $(this).data('code');
                let name = $(this).data('name');
                let price = parseFloat($(this).data('price')) || 0;
                let costPrice = parseFloat($(this).data('cost-price')) || 0;
                let stock = parseFloat($(this).data('stock')) || 0;
                let location = $(this).data('location') || '-';
                let isActive = $(this).data('is-active') ? 1 : 0;
                let appliedDiscount = parseFloat($(this).data('discount-rate')) || 0;
                let supplierId = $(this).data('supplier-id') || '';
                let unitPriceHt = price.toFixed(2);
                let unitPriceTtc = (price * (1 + tvaRate / 100)).toFixed(2);
                let row = `
                    <tr data-line-id="${lineCount}">
                        <td><div class="d-flex align-items-center gap-1"><span class="font-weight-bold">${code}</span>
                            <button type="button" class="btn btn-xs btn-outline-secondary copy-line-code px-1 py-0" data-code="${code}"><i class="fas fa-copy"></i></button></div><br>
                            <span class="badge bg-${isActive ? 'success' : 'danger'} badge-very-sm">${isActive ? 'actif' : 'bloqué'}</span>
                            <input type="hidden" name="lines[${lineCount}][article_code]" value="${code}">
                        </td>
                        <td>${name}</td>
                        <td class="p-1">
                            <div class="purchase-price-block">
                                <div class="d-flex gap-1 align-items-center mb-1">
                                    <div class="input-group input-group-sm flex-fill"><span class="input-group-text">€</span>
                                        <input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input" value="${costPrice.toFixed(2)}" name="lines[${lineCount}][unit_coast]">
                                    </div>
                                    <select class="form-select form-select-sm supplier-select" name="lines[${lineCount}][supplier_id]"></select>
                                </div>
                                <div class="d-flex gap-1 align-items-center justify-content-between">
                                    <div class="input-group input-group-sm" style="width:105px;">
                                        <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm text-end purchase-discount" value="0" name="lines[${lineCount}][discount_coast]">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <span class="text-muted small">→</span>
                                    <span class="fw-bold text-success net-price">0,00 €</span>
                                </div>
                                <small class="text-muted d-block text-end mt-1">Marge nette : <span class="margin-display text-primary fw-bold">0%</span> (<span class="margin-euro text-primary">0,00 €</span>)</small>
                            </div>
                        </td>
                        <td><button type="button" class="btn btn-outline-primary btn-sm stock-details-btn" data-code="${code}" data-name="${name}">${stock}</button><br><small class="text-muted" style="font-size: 0.7rem;">${location}</small></td>
                        <td><input type="number" name="lines[${lineCount}][ordered_quantity]" class="form-control quantity" value="1" min="0"></td>
                        <td><input type="number" step="0.01" name="lines[${lineCount}][unit_price_ht]" class="form-control unit_price_ht" value="${unitPriceHt}"></td>
                        <td><input type="number" step="0.01" name="lines[${lineCount}][unit_price_ttc]" class="form-control unit_price_ttc" value="${unitPriceTtc}"></td>
                        <td><input type="number" name="lines[${lineCount}][remise]" class="form-control remise" value="${appliedDiscount.toFixed(2)}" min="0" max="100" step="0.01"></td>
                        <td class="text-right total_ht">0,00</td>
                        <td class="text-right total_ttc">0,00</td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
                $('#lines_body').append(row);
                let $newRow = $('#lines_body tr:last');
                let $supplierSelect = $newRow.find('.supplier-select');
                $supplierSelect.select2({ width: '100%', placeholder: 'Fournisseur', allowClear: true, data: window.suppliersList });
                if (supplierId) $supplierSelect.val(supplierId).trigger('change');
                updatePurchaseMargin($newRow);
                updateLineTotals($newRow, tvaRate);
                lineCount++;
                $('#search_item').val('');
                $('#search_results').empty();
                updateGlobalTotals();
            });

            $(document).on('click', '.remove_line', function () {
                $(this).closest('tr').remove();
                updateGlobalTotals();
            });

            $(document).on('click', '#add_divers_item', function () {
                let tvaRate = parseFloat($('#tva_rate').val()) || 20;
                let i = lineCount++;
                const isConsigne = $(this).data('type') === 'consigne';
                let rowHtml = `
                    <tr class="divers-line" data-line-id="divers_${i}" data-is-consigne="${isConsigne ? '1' : '0'}">
                        <td><input type="text" name="lines[${i}][article_code]" class="form-control form-control-sm bg-light" value="${isConsigne ? 'CONSIGNE' : ''}" placeholder="Réf (ex: DIV001)" ${isConsigne ? 'readonly' : 'required'}>
                            <input type="hidden" name="lines[${i}][is_new_item]" value="1"></td>
                        <td><input type="text" name="lines[${i}][item_name]" class="form-control form-control-sm bg-light" value="${isConsigne ? 'CONSIGNE' : ''}" placeholder="Désignation" ${isConsigne ? 'readonly' : 'required'}></td>
<td class="p-1">
            <div class="purchase-price-block ${isConsigne ? 'd-none' : ''}">
                <div class="d-flex gap-1 align-items-center mb-1">
                    <div class="input-group input-group-sm flex-fill">
                        <span class="input-group-text">€</span>
                        <input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input"
                               value="0.00" name="lines[${i}][unit_coast]" placeholder="Prix achat HT">
                    </div>
                    <select class="form-select form-select-sm supplier-select" name="lines[${i}][supplier_id]" ${isConsigne ? 'disabled' : ''}>
                        <option value="">Fournisseur</option>
                    </select>
                </div>
                <div class="d-flex gap-1 align-items-center justify-content-between">
                    <div class="input-group input-group-sm" style="width: 105px;">
                        <input type="number" min="0" max="100" step="0.1"
                               class="form-control form-control-sm text-end purchase-discount"
                               value="0" name="lines[${i}][discount_coast]" placeholder="Rem%">
                        <span class="input-group-text">%</span>
                    </div>
                    <span class="text-muted small">→</span>
                    <span class="fw-bold text-success net-price">0,00 €</span>
                </div>
                <small class="text-muted d-block text-end mt-1">
                    Marge nette : <span class="margin-display text-primary fw-bold">0%</span>
                    (<span class="margin-euro text-primary">0,00 €</span>)
                </small>
            </div>
            ${isConsigne ? '<div class="text-center text-muted small">Consigne — Pas de marge</div>' : ''}
        </td>                        
                        <td><span class="text-muted">-</span></td>
                        <td><input type="number" name="lines[${i}][ordered_quantity]" class="form-control quantity" value="1" min="1" required></td>
                        <td><input type="number" step="0.01" name="lines[${i}][unit_price_ht]" class="form-control unit_price_ht" value="0.00"></td>
                        <td><input type="number" step="0.01" name="lines[${i}][unit_price_ttc]" class="form-control unit_price_ttc" value="0.00"></td>
                        <td><input type="number" name="lines[${i}][remise]" class="form-control remise" value="0" min="0" max="100"></td>
                        <td class="text-right total_ht">0,00</td>
                        <td class="text-right total_ttc">0,00</td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
                $('#lines_body').append(rowHtml);
                let $newRow = $('#lines_body tr:last');
                if (!isConsigne) {
                    $newRow.find('.supplier-select').select2({ width: '100%', placeholder: 'Fournisseur', allowClear: true, data: window.suppliersList });
                }
                updateLineTotals($newRow, tvaRate);
                updateGlobalTotals();
            });

            function updateCatalogButton() {
                let customerId = $('#customer_id').val();
                let vehicleId = $('#vehicle_id').val();
                let $btn = $('#loadCatalogBtn');
                if (customerId && vehicleId) {
                    $btn.attr('href', `/customers/${customerId}/vehicles/${vehicleId}/catalog`).removeAttr('disabled').off('click').on('click', e => { e.preventDefault(); window.open(this.href, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); });
                } else {
                    $btn.removeAttr('href').attr('disabled', 'disabled');
                }
            }

            function updateHistoryButton() {
                let vehicleId = $('#vehicle_id').val();
                let customerId = $('#customer_id').val();
                let $btn = $('#viewHistoryBtn');
                if (vehicleId && customerId) {
                    $btn.removeAttr('disabled').off('click').on('click', e => { e.preventDefault(); window.open(`/vehicles/${vehicleId}/history`, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); });
                } else {
                    $btn.attr('disabled', 'disabled');
                }
            }

            $('#vehicle_id').on('change', function () {
                updateCatalogButton();
                updateHistoryButton();
            });

            $(document).on('click', '.stock-details-btn', function () {
                let code = $(this).data('code');
                let name = $(this).data('name');
                $('#stockDetailsModalLabel').text(`Détail du stock – ${code} : ${name}`);
                $('#stockTableBody, #movementTableBody').empty();
                $.ajax({
                    url: '{{ route("items.stock.details") }}',
                    method: 'GET',
                    data: { code: code },
                    success: function (data) {
                        if (data.stocks) data.stocks.forEach(s => $('#stockTableBody').append(`<tr><td>${s.store_name || '-'}</td><td>${s.quantity}</td><td>${s.updated_at || '-'}</td></tr>`));
                        if (data.movements) data.movements.forEach(m => $('#movementTableBody').append(`<tr><td><span class="badge bg-${m.quantity >= 0 ? 'success' : 'danger'}">${m.type}</span><br>${m.created_at || '-'}</td><td>${m.quantity}</td><td>${m.store_name || '-'}</td><td>${m.cost_price || '-'}</td><td>${m.supplier_name || '-'}</td><td>${m.reference || '-'}</td></tr>`));
                    }
                });
                $('#stockDetailsModal').modal('show');
            });

            const $modal = $('#addVehicleInlineModal');
            const $form = $('#quickVehicleForm');
            function initTecdocSelect2() {
                $('#quick_brand_id, #quick_model_id, #quick_engine_id').select2('destroy');
                $('#quick_brand_id').select2({ width: '100%', dropdownParent: $modal });
                $('#quick_model_id').select2({ width: '100%', dropdownParent: $modal });
                $('#quick_engine_id').select2({ width: '100%', dropdownParent: $modal });
            }
            $modal.on('shown.bs.modal', function () {
                $form[0].reset();
                $('#quick_model_id, #quick_engine_id').empty().prop('disabled', true);
                initTecdocSelect2();
            });
            $(document).on('change', '#quick_brand_id', function () {
                let id = $(this).val();
                $('#quick_model_id').empty().append('<option>Chargement...</option>').prop('disabled', true);
                if (id) $.get('{{ route("getModels") }}', { brand_id: id }, data => {
                    $('#quick_model_id').empty().append('<option>Choisir un modèle</option>');
                    data.forEach(m => $('#quick_model_id').append(`<option value="${m.id}" data-name="${m.name}">${m.name}</option>`));
                    $('#quick_model_id').prop('disabled', false);
                });
            });
            $(document).on('change', '#quick_model_id', function () {
                let id = $(this).val();
                $('#quick_engine_id').empty().append('<option>Chargement...</option>').prop('disabled', true);
                if (id) $.get('{{ route("getEngines") }}', { model_id: id }, data => {
                    $('#quick_engine_id').empty().append('<option>Choisir une motorisation</option>');
                    data.forEach(e => $('#quick_engine_id').append(`<option value="${e.id}" data-description="${e.description}" data-linking-target-id="${e.linkageTargetId}">${e.description}</option>`));
                    $('#quick_engine_id').prop('disabled', false);
                });
            });
            $(document).on('change', '#quick_engine_id', function () {
                let $opt = $(this).find('option:selected');
                $('#quick_engine_description').val($opt.data('description'));
                $('#quick_linkage_target_id').val($opt.data('linking-target-id') || $(this).val());
            });
            $form.on('submit', function (e) {
                e.preventDefault();
                let customerId = $('#customer_id').val();
                if (!customerId) return Swal.fire('Erreur', 'Sélectionnez un client', 'error');
                let data = {
                    _token: csrfToken,
                    license_plate: $('#quick_license_plate').val().toUpperCase().trim(),
                    brand_id: $('#quick_brand_id').val(),
                    brand_name: $('#quick_brand_id option:selected').text(),
                    model_id: $('#quick_model_id').val(),
                    model_name: $('#quick_model_id option:selected').data('name') || $('#quick_model_id option:selected').text(),
                    engine_id: $('#quick_engine_id').val(),
                    engine_description: $('#quick_engine_description').val() || $('#quick_engine_id option:selected').text(),
                    linkage_target_id: $('#quick_linkage_target_id').val()
                };
                $.post(`/customers/${customerId}/vehicles/quick-store`, data, res => {
                    if (res.success) {
                        let text = `${data.license_plate} (${data.brand_name} ${data.model_name} - ${data.engine_description})`;
                        $('#vehicle_id').append(new Option(text, res.vehicle.id, true, true)).trigger('change');
                        $modal.modal('hide');
                        updateCatalogButton();
                        updateHistoryButton();
                        Swal.fire('Succès', 'Véhicule créé et sélectionné !', 'success');
                    }
                });
            });

            $('#searchByPlateBtn').on('click', function () {
                let plate = $('#plate_search').val().trim().toUpperCase().replace(/[^A-Z0-9]/g, '');
                if (!plate || plate.length < 4) return Swal.fire('Erreur', 'Plaque invalide', 'warning');
                let btn = $(this);
                btn.prop('disabled', true).find('.spinner-border').removeClass('d-none');
                let customerId = $('#customer_id').val();
                if (!customerId) return;
                $.get(`/customers/${customerId}/vehicles/from-plate?license_plate=${plate}`, res => {
                    if (res.success) {
                        $('#vehicle_id').append(new Option(res.vehicle.text, res.vehicle.id, true, true)).trigger('change');
                        $('#plate_search').val('');
                    }
                }).always(() => {
                    btn.prop('disabled', false).find('.spinner-border').addClass('d-none');
                });
            });

            $('#lines_body tr').each(function () {
                updatePurchaseMargin($(this));
                updateLineTotals($(this), $('#tva_rate').val());
            });
            updateGlobalTotals();
        });
    </script>
</body>
</html>