<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Liste des Commandes Vente</title>
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

    <style>
        #panierDropdown + .dropdown-menu { width: 900px; min-width: 350px; padding: 10px; border-radius: 8px; }
        .panier-dropdown { width: 100%; min-width: 350px; }
        .panier-dropdown .notif-item { padding: 10px; margin-bottom: 5px; border-bottom: 1px solid #ddd; }
        .dropdown-title { font-weight: bold; margin-bottom: 10px; }
        .notif-scroll { padding: 10px; }
        .notif-center { padding: 5px 0; }
        .dropdown-footer { padding: 10px; border-top: 1px solid #ddd; }
        .table { width: 100%; margin-bottom: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f2f2f2; }
        .btn-sm { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
        .text-muted { font-size: 0.85rem; }
        .text-center { text-align: center; }
        .card { border-radius: 12px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card h3 { font-size: 1.8rem; color: #007bff; margin-bottom: 1rem; font-weight: 700; }
        .card h6 { font-size: 1rem; color: #6c757d; }
        .card-body { padding: 2rem; }
        .card .text-info { color: #17a2b8 !important; }
        .btn-primary { font-size: 1.1rem; padding: 1rem 1.5rem; border-radius: 8px; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #0056b3; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); }



        @keyframes blink {
    50% {
        opacity: 0;
    }
}

.blinking-btn {
    animation: blink 1.5s infinite;
}





.filter-box {
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    padding: 6px 8px !important;
    background: #f2f1f1ff;
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
                            <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
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
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <h4>📋 Liste des commandes vente :

                        <!-- <a href="{{ route('sales.create') }}" class="btn btn-sm btn-success">
                            Nouvelle <i class="fas fa-plus-circle ms-2"></i>
                        </a> -->

                                                <a href="{{ route('sales.delivery.create') }}" class="btn btn-outline-success btn-round ms-2">
                            Nouvelle Commande <i class="fas fa-plus-circle ms-2"></i>
                        </a>


                    </h4>

                         <div class="filter-box mb-2 p-2">
    <form method="GET"
          action="{{ route('sales.list') }}"
          class="d-flex flex-wrap align-items-end gap-2">

        {{-- Client --}}
        <select name="customer_id"
                class="form-select form-select-sm select2"
                style="width: 140px;">
            <option value="">Client (Tous)</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}"
                    {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>

        {{-- Vendeur --}}
        <select name="vendeur"
                class="form-select form-select-sm"
                style="width: 120px;">
            <option value="">Vendeur (Tous)</option>
            @foreach($vendeurs as $vendeur)
                <option value="{{ $vendeur }}"
                    {{ request('vendeur') == $vendeur ? 'selected' : '' }}>
                    {{ $vendeur }}
                </option>
            @endforeach
        </select>

        {{-- Statut devis --}}
        <select name="status"
                class="form-select form-select-sm"
                style="width: 90px;">
            <option value="">Statut</option>
            <option value="brouillon" {{ request('status') == 'brouillon' ? 'selected' : '' }}>
                Brouillon
            </option>
            <option value="validée" {{ request('status') == 'validée' ? 'selected' : '' }}>
                Validée
            </option>
        </select>

        {{-- Statut BL --}}
        <select name="delivery_status"
                class="form-select form-select-sm"
                style="width: 100px;">
            <option value="">BL (Tous)</option>
            <option value="en_cours" {{ request('delivery_status') == 'en_cours' ? 'selected' : '' }}>
                En cours
            </option>
            <option value="livré" {{ request('delivery_status') == 'livré' ? 'selected' : '' }}>
                Livré
            </option>
        </select>

        {{-- Dates --}}
        <input type="date"
               name="date_from"
               class="form-control form-control-sm"
               style="width: 97px;"
               value="{{ request('date_from') }}">

        <span class="mx-0">à</span>

        <input type="date"
               name="date_to"
               class="form-control form-control-sm"
               style="width: 97px;"
               value="{{ request('date_to') }}">

        {{-- Boutons --}}
        <button type="submit"
                class="btn btn-outline-primary btn-sm px-3">
            <i class="fas fa-filter me-1"></i> Filtrer
        </button>

        <a href="{{ route('sales.devislist') }}"
           class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-undo me-1"></i> Réinitialiser
        </a>

    </form>
</div>   

                                                                <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $sales->appends(request()->query())->links() }}
</div>


                    @foreach ($sales as $order)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                <div>
                                    <h6 class="mb-0">
                                        <strong>Commande N° : {{ $order->numdoc }}</strong> 
                                        ( {{ $order->numclient }} – {{ $order->customer->name }} )
                                        <span class="text-muted small">({{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }})</span>

<a href="{{ config('services.tournee.url', 'http://127.0.0.1:8001') }}/suivi/{{ $order->numdoc }}"
                   target="_blank"
                   class="btn btn-sm"
                   title="Suivi tournée de ce BL"
                   style="background:#dcfce7;border:1px solid #86efac;color:#166534;font-weight:600;border-radius:6px;">
                    <i class="fas fa-truck-loading me-1"></i> Suivi
                    <i class="fas fa-external-link-alt ms-1" style="font-size:0.65rem;opacity:0.7;"></i>
                </a>

                                    </h6>
                                    @if($order->status === 'brouillon')
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @elseif($order->status === 'Devis')
                                        <span class="badge bg-dark">{{ ucfirst($order->status) }}</span>
                                    @elseif($order->status === 'validée')
                                        <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                         @elseif($order->status === 'en_cours')
                                        <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>

                                    @endif
                                    @if($order->deliveryNote)
                                        <span class="badge bg-info">Expédition {{ ucfirst($order->deliveryNote->status) }}</span>
                                        @if(!ucfirst($order->deliveryNote->invoiced))

<a type="button"
   class="btn btn-danger btn-sm blinking-btn"
   href="{{ route('salesinvoices.create_direct', ucfirst($order->deliveryNote->id)) }}">
   cliquer ici pour facturer
</a>
@else 
<span class="badge rounded-pill text-bg-light">Facturé</span>
@endif
@elseif($order->status === 'brouillon' or $order->status === 'Devis')

<a type="button"
   class="btn btn-warning btn-sm blinking-btn"
   href="{{ route('sales.edit', $order->id) }}">
   Valider Pour Facturer
</a>
                                    @endif

                                                <span class="badge rounded-pill text-bg-light"><i class="fas fa-user-tie"></i> Vendeur :  {{ $order->vendeur}}</span>


                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $order->id }})">
                                        ➕ Détails
                                    </button>
                                    
                                    <a href="{{ route('sales.export_single', $order->id) }}" class="btn btn-xs btn-outline-success">
                                        EXCEL <i class="fas fa-file-excel"></i>
                                    </a>

                                                            <a href="{{ route('sales.print_single', $order->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                            PDF <i class="fas fa-print"></i>
                        </a>

                                    <a href="{{ route('sales.print_singlesansref', $order->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                            PDF SANS REFERENCE <i class="fas fa-print"></i>
                        </a>
                                    

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">


                                         <a class="dropdown-item" href="#" data-toggle="modal" data-target="#sendEmailModal{{ $order->id }}">
    <i class="fas fa-envelope"></i> Envoyer par mail
</a>



                                            @if($order->status === 'brouillon' or $order->status === 'Devis')
                                                <a class="dropdown-item" href="{{ route('sales.edit', $order->id) }}">
                                                    <i class="fas fa-edit"></i> Modifier & valider
                                                </a>
                                                <form action="{{ route('sales.validate', $order->id) }}" method="POST" onsubmit="return confirm('Valider cette commande ?')" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check"></i> Générer BL
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="lines-{{ $order->id }}" class="card-body d-none bg-light">

                            <h6 class="fw-bold mb-3"><i class="fa fa-solid fa-car"></i> : {{ $order->vehicle ? ($order->vehicle->license_plate . ' (' . $order->vehicle->brand_name . ' ' . $order->vehicle->model_name . ')') : '-' }}                     @if($order->notes )<p> Note : {{ $order->notes ?? '-' }}</p> @endif
 </h6>


                                <!-- <h6 class="fw-bold mb-3">🧾 Lignes de la commande</h6> -->
                                <table class="table table-sm table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Code Article</th>
                                            <th>Désignation</th>
                                            <th>Qté</th>
                                            <th>PU HT</th>
                                            <th>Remise (%)</th>
                                            <th>Total Ligne</th>
                                            <th style="width:50px;" title="Tournée">🚚</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->lines as $line)
                                            <tr>
                                                <td>{{ $line->article_code }}</td>
                                                <td>{{ $line->item->name ?? '-' }}</td>
                                                <td class="text-center">{{ $line->ordered_quantity }}</td>
                                                <td class="text-end">{{ number_format($line->unit_price_ht, 2) }} €</td>
                                                <td class="text-end">{{ $line->remise }}%</td>
                                                <td class="text-end">{{ number_format($line->total_ligne_ht, 2) }} €</td>

                                                <td class="text-center p-1">
    <button type="button"
            class="btn btn-tournee-cmd btn-sm btn-outline-primary"
            style="min-width:38px;font-size:0.95rem;"
            data-order-id="{{ $order->id }}"
            data-order-numdoc="{{ $order->numdoc }}"
            data-line-id="{{ $line->id }}"
            data-article-code="{{ $line->article_code ?? '-' }}"
            data-article-name="{{ $line->item->name ?? $line->article_code }}"
            data-quantity="{{ $line->quantity }}"
            data-supplier-id="{{ $line->supplier_id ?? '' }}"
            data-customer-name="{{ $order->customer->name ?? '' }}"
            title="Ajouter à la tournée">
        <i class="fas fa-truck"></i>
    </button>
    
    {{-- Badge statut tournée (vide au départ) --}}
    <span id="tournee-statut-bl-{{ $line->id }}" class="ms-1"></span>

</td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="text-end mt-3">
                                    <div class="p-3 bg-white border rounded d-inline-block">
                                        <strong>Total HT :</strong> {{ number_format($order->total_ht, 2) }} €<br>
                                        <strong>Total TTC :</strong> {{ number_format($order->total_ttc, 2) }} €
                                    </div>
                                </div>
                            </div>
                        </div>




                                              
<!-- Modal Send Email -->
<div class="modal fade" id="sendEmailModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('salesorder.sendEmail', $order->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">📧 Envoyer le Document N° :  {{ $order->numdoc }}</h5>
          <button type="button" class="btn-close" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Email principal -->
          <div class="form-group mb-2">
            <label>Email client</label>
            <input type="email" name="emails[]" class="form-control" value="{{ $order->customer->email ?? '' }}" required>
          </div>

          <!-- Autres destinataires -->
          <div id="extraEmails{{ $order->id }}"></div>
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addEmailField({{ $order->id }})">
            + Ajouter un autre destinataire
          </button>

          <!-- Message -->
          <div class="form-group mt-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="4">{{ \App\Models\EmailMessage::first()->messagefacturevente ?? 'Veuillez trouver ci-joint votre Devis.' }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function addEmailField(id) {
    let container = document.getElementById('extraEmails' + id);
    let input = document.createElement('input');
    input.type = 'email';
    input.name = 'emails[]';
    input.placeholder = 'Autre email';
    input.classList.add('form-control','mt-2');
    container.appendChild(input);
}
</script>
<!-- end mail  -->





                    @endforeach
                </div>

                <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $sales->appends(request()->query())->links() }}
</div>

            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        © AZ NEGOCE. All Rights Reserved.
                    </div>
                    <div>
                        by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.
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
            $('.select2').select2({ width: '15%' });
        });

        function toggleLines(id) {
    const section = document.getElementById('lines-' + id);
    section.classList.toggle('d-none');

    // Charger les statuts tournée uniquement quand on ouvre
    if (!section.classList.contains('d-none')) {
        loadTourneeStatutsCMD(id);
    }
}

        function loadTourneeStatutsBL(sourceId) {
            fetch('/tournee/lines/' + sourceId + '?source_type=bl')
                .then(function(r) { return r.json(); })
                .then(function(lines) {
                    var colorMap = {
                        'en_attente': 'secondary', 'assigné': 'primary',
                        'en_route': 'info', 'recupere': 'success',
                        'au_magasin': 'dark', 'probleme': 'danger'
                    };
                    lines.forEach(function(l) {
                        var span = document.getElementById('tournee-statut-bl-' + l.source_line_id);
                        var btn  = span ? span.previousElementSibling : null;
                        if (span) {
                            span.innerHTML = '<span class="badge badge-' + (colorMap[l.statut] || 'secondary') + '">'
                                + l.statut_label + '</span>';
                        }
                        if (btn && btn.classList.contains('btn-tournee')) {
                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-warning');
                            btn.title = 'Déjà en tournée — ' + l.statut_label + (l.chauffeur ? ' (' + l.chauffeur + ')' : '');
                        }
                    });
                })
                .catch(function() {});
        }

         function setCommentForm(url, id) {
            document.getElementById('commentForm').action = url;
                        document.getElementById('comment').value = ''; // Réinitialiser le champ de commentaire

        }
    </script>










{{-- ════ MODAL TOURNÉE BL ════ --}}
    <div class="modal fade" id="tourneeCMDModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px;overflow:hidden;">
                <div class="modal-header text-white" style="background:linear-gradient(135deg,#1a2b4a,#2d4a8a);">
                    <h5 class="modal-title mb-0" style="font-size:0.95rem;">
                        <i class="fas fa-route me-2"></i> Ajouter à la tournée
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border mb-3 py-2 px-3">
                        <strong id="tcmd-article-code" style="font-family:monospace;font-size:1rem;color:#0040c0;background:#eef4ff;padding:2px 8px;border-radius:3px;border-left:3px solid #0040c0;"></strong>
                        <div id="tcmd-article-name" class="text-muted" style="font-size:0.82rem;margin-top:3px;"></div>
                        <div style="font-size:0.78rem;margin-top:3px;">
                            BL : <strong id="tcmd-numdoc"></strong> &nbsp;|&nbsp; Qté : <strong id="tcmd-qty"></strong>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label mb-1" style="font-size:0.82rem;font-weight:700;">
                                <i class="fas fa-industry me-1 text-warning"></i> Fournisseur <span class="text-danger">*</span>
                            </label>
                            <select id="tcmd-supplier" class="form-control form-control-sm select2-bl" required style="width:100%;">
                                <option value="">-- Choisir le fournisseur --</option>
                                @foreach(\App\Models\Supplier::where('has_b2b', true)->orderBy('name')->get() as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}@if($supplier->city) — {{ $supplier->city }}@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1" style="font-size:0.82rem;font-weight:700;">
                                <i class="fas fa-user me-1 text-info"></i> Chauffeur <small class="text-muted">(optionnel)</small>
                            </label>
                            <select id="tcmd-chauffeur" class="form-control form-control-sm">
                                <option value="" selected>⏳ À affecter (dispatcher assignera)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1" style="font-size:0.82rem;font-weight:700;">
                                <i class="fas fa-calendar me-1 text-danger"></i> Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="tcmd-date" class="form-control form-control-sm" value="{{ today()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1" style="font-size:0.82rem;font-weight:700;">
                                <i class="fas fa-clock me-1 text-success"></i> Créneau <span class="text-danger">*</span>
                            </label>
                            
                            {{-- APRÈS --}}
<div id="tcmd-slot-loading" class="text-muted" style="font-size:0.8rem;">
    <i class="fas fa-spinner fa-spin me-1"></i> Chargement des créneaux...
</div>
<div id="tcmd-slot-closed" class="alert alert-danger py-2 px-3" style="display:none;font-size:0.82rem;">
    <i class="fas fa-ban me-1"></i>
    <strong id="tcmd-slot-closed-msg"></strong>
</div>
<div id="tcmd-slot-options" class="d-flex flex-wrap gap-2 mt-1" style="display:none;"></div>
<div id="tcmd-slot-warning" class="alert alert-warning py-1 px-2 mt-2" style="display:none;font-size:0.75rem;">
    <i class="fas fa-exclamation-triangle me-1"></i>
    <strong>Créneau modifié</strong> — vous prenez la responsabilité de ce changement.
</div>

                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1" style="font-size:0.82rem;font-weight:700;">Quantité</label>
                            <input type="number" id="tcmd-quantity" class="form-control form-control-sm" min="1" value="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1" style="font-size:0.82rem;font-weight:700;">Note (optionnel)</label>
                            <textarea id="tcmd-notes" class="form-control form-control-sm" rows="2"
          placeholder="Ex: demander au comptoir, pièce urgente..."></textarea>
<button type="button"
        onclick="document.getElementById('tcmd-notes').value='🚪 Livraison: ' + this.getAttribute('data-customer'); this.style.background='#dcfce7'; this.style.borderColor='#86efac'; this.style.color='#166534'; this.innerHTML='✅ Noté — Livraison directe au client';"
        data-customer=""
        id="btn-livraison-directe-bl"
        style="background:#ede9fe;border:1px solid #a78bfa;color:#6f42c1;border-radius:6px;
               padding:3px 10px;font-size:0.72rem;font-weight:600;cursor:pointer;margin-top:4px;">
    🚪 Livraison directe au client
</button>

                        </div>
                    </div>

                    <div id="tcmd-existing-lines" class="mt-3" style="display:none;">
    <hr class="my-2">
    <small class="fw-bold text-muted" style="font-size:0.75rem;">
        <i class="fas fa-list me-1"></i>Déjà en tournée pour cette commande :
    </small>
    <div id="tcmd-existing-content" class="mt-1"></div>
</div>


                    <div id="tcmd-error" class="alert alert-danger mt-2 py-2" style="display:none;font-size:0.82rem;"></div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Annuler</button>
                    <button type="button" id="tcmd-submit" class="btn btn-warning btn-sm px-4">
                        <i class="fas fa-route me-1"></i> Ajouter à la tournée
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <script>
(function () {
    var currentCMDLine = {};

    // Charger les chauffeurs
    function loadChauffeursBL() {
        fetch('{{ route("tournee.chauffeurs") }}')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var sel = document.getElementById('tcmd-chauffeur');
                sel.innerHTML = '<option value="">⏳ À affecter (dispatcher assignera)</option>';
                data.forEach(function(c) {
                    sel.innerHTML += '<option value="' + c.id + '">' + c.name + (c.phone ? ' — ' + c.phone : '') + '</option>';
                });
            })
            .catch(function() {
                document.getElementById('tcmd-chauffeur').innerHTML = '<option value="">Serveur indisponible</option>';
            });
    }
    loadChauffeursBL();

    // ── Créneaux dynamiques ──────────────────────────────
    var autoSlotBL = null;

    function loadCreneauxBL(date) {
        var url     = '/tournee/parametres' + (date ? '?date=' + date : '');
        var loading = document.getElementById('tcmd-slot-loading');
        var closed  = document.getElementById('tcmd-slot-closed');
        var options = document.getElementById('tcmd-slot-options');
        var submit  = document.getElementById('tcmd-submit');

        loading.style.display = 'block';
        closed.style.display  = 'none';
        options.style.display = 'none';
        submit.disabled       = false;

        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loading.style.display = 'none';

                if (data.pour_demain) {
                    document.getElementById('tcmd-date').value = data.date;
                    if (data.message) {
                        var info = document.createElement('div');
                        info.className = 'alert alert-info py-1 px-2 mb-2';
                        info.style.fontSize = '0.78rem';
                        info.innerHTML = '<i class="fas fa-info-circle me-1"></i>' + data.message;
                        loading.insertAdjacentElement('afterend', info);
                        setTimeout(function() { if(info.parentNode) info.parentNode.removeChild(info); }, 5000);
                    }
                    loadCreneauxBL(data.date);
                    return;
                }

                if (!data.is_open) {
                    closed.style.display = 'block';
                    document.getElementById('tcmd-slot-closed-msg').textContent = data.message || 'Fermé ce jour';
                    submit.disabled = true;
                    return;
                }

                var creneaux = (data.creneaux_dispo && data.creneaux_dispo.length)
                    ? data.creneaux_dispo : data.creneaux;

                if (!creneaux || !creneaux.length) {
                    closed.style.display = 'block';
                    document.getElementById('tcmd-slot-closed-msg').textContent = 'Plus de créneau disponible';
                    submit.disabled = true;
                    return;
                }

                autoSlotBL = data.creneau_suggere;
                options.innerHTML = '';
                var icons = {'9h-11h':'🌅','11h-12h':'🕚','13h-14h':'🌞','15h-16h':'🕒','17h-18h':'🌇'};

                creneaux.forEach(function(c, i) {
                    var isDefault = (c.label === autoSlotBL);
                    var div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML =
                        '<input class="form-check-input" type="radio" name="tcmd-slot" id="tcmd-slot-' + i + '" value="' + c.label + '"' + (isDefault ? ' checked' : '') + '>' +
                        '<label class="form-check-label" for="tcmd-slot-' + i + '" style="font-size:0.82rem;">' +
                        (icons[c.label] || '🕐') + ' ' + c.label +
                        (isDefault ? ' <span class="badge bg-success ms-1" style="font-size:0.6rem;">Suggéré</span>' : '') +
                        '</label>';
                    options.appendChild(div);
                });
                options.style.display = 'flex';
            })
            .catch(function() {
                loading.style.display = 'none';
                options.style.display = 'flex';
                var defaults = [{label:'9h-11h'},{label:'11h-12h'},{label:'13h-14h'},{label:'15h-16h'},{label:'17h-18h'}];
                var icons = {'9h-11h':'🌅','11h-12h':'🕚','13h-14h':'🌞','15h-16h':'🕒','17h-18h':'🌇'};
                defaults.forEach(function(c, i) {
                    var div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML = '<input class="form-check-input" type="radio" name="tcmd-slot" id="tcmd-slot-' + i + '" value="' + c.label + '"' + (i===0?' checked':'') + '>' +
                        '<label class="form-check-label" for="tcmd-slot-' + i + '" style="font-size:0.82rem;">' + icons[c.label] + ' ' + c.label + '</label>';
                    options.appendChild(div);
                });
            });
    }

    // Détecter changement manuel du créneau
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'tcmd-slot') {
            var warning = document.getElementById('tcmd-slot-warning');
            if (warning) warning.style.display = (e.target.value !== autoSlotBL) ? 'block' : 'none';
        }
    });

    // Ouvrir le modal
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-tournee-cmd');
        if (!btn) return;

        currentCMDLine = {
            blId:         btn.getAttribute('data-order-id'),
            numdoc:       btn.getAttribute('data-order-numdoc'),
            lineId:       btn.getAttribute('data-line-id'),
            articleCode:  btn.getAttribute('data-article-code'),
            articleName:  btn.getAttribute('data-article-name'),
            quantity:     btn.getAttribute('data-quantity') || '1',
            supplierId:   btn.getAttribute('data-supplier-id') || '',
            customerName: btn.getAttribute('data-customer-name') || '',
        };

        document.getElementById('tcmd-article-code').textContent = currentCMDLine.articleCode;
        document.getElementById('tcmd-article-name').textContent = currentCMDLine.articleName;
        document.getElementById('tcmd-numdoc').textContent       = currentCMDLine.numdoc;
        document.getElementById('tcmd-qty').textContent          = currentCMDLine.quantity;
        document.getElementById('tcmd-quantity').value           = currentCMDLine.quantity;
        document.getElementById('tcmd-error').style.display      = 'none';
        document.getElementById('tcmd-notes').value              = '';
        document.getElementById('tcmd-supplier').value           = currentCMDLine.supplierId || '';

        var btnLivraisonBL = document.getElementById('btn-livraison-directe-bl');
        if (btnLivraisonBL) btnLivraisonBL.setAttribute('data-customer', currentCMDLine.customerName || '');

        loadCreneauxBL(document.getElementById('tcmd-date').value);

        // Éviter d'ajouter plusieurs fois le même listener
        var dateInput = document.getElementById('tcmd-date');
        dateInput.onchange = function() {
            loadCreneauxBL(this.value);
        };

        $('#tourneeCMDModal').modal('show');
        loadExistingLinesCMD(currentCMDLine.blId);
document.getElementById('tcmd-numdoc').setAttribute('data-order-id', currentCMDLine.blId);


        // Initialiser select2 après ouverture
        setTimeout(function() {
            if ($('#tcmd-supplier').hasClass('select2-hidden-accessible')) {
                $('#tcmd-supplier').select2('destroy');
            }
            $('#tcmd-supplier').select2({
                width: '100%',
                placeholder: 'Rechercher un fournisseur...',
                allowClear: true,
                dropdownParent: $('#tourneeCMDModal'),
                language: { noResults: function() { return 'Aucun fournisseur trouvé'; } }
            });
            if (currentCMDLine.supplierId) {
                $('#tcmd-supplier').val(currentCMDLine.supplierId).trigger('change');
            }
        }, 350);
    });

    // Soumettre
    document.getElementById('tcmd-submit').addEventListener('click', function() {
        var supplierId  = document.getElementById('tcmd-supplier').value;
        var chauffeurId = document.getElementById('tcmd-chauffeur').value;
        var date        = document.getElementById('tcmd-date').value;
        var slotEl      = document.querySelector('input[name="tcmd-slot"]:checked');
        var slot        = slotEl ? slotEl.value : 'matin';
        var errorDiv    = document.getElementById('tcmd-error');

        errorDiv.style.display = 'none';
        if (!supplierId) { errorDiv.textContent = 'Veuillez choisir un fournisseur.'; errorDiv.style.display = 'block'; return; }
        if (!date)       { errorDiv.textContent = 'Veuillez choisir une date.';       errorDiv.style.display = 'block'; return; }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Envoi...';

        var csrfMeta = document.querySelector('meta[name="csrf-token"]') || document.querySelector('input[name="_token"]');
        var csrf = csrfMeta ? (csrfMeta.getAttribute('content') || csrfMeta.value) : '';

        fetch('{{ route("tournee.store") }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                invoice_id:      currentCMDLine.blId,
                invoice_line_id: currentCMDLine.lineId,
                article_code:    currentCMDLine.articleCode,
                article_name:    currentCMDLine.articleName,
                quantity:        document.getElementById('tcmd-quantity').value || 1,
                supplier_id:     supplierId,
                chauffeur_id:    (chauffeurId && chauffeurId !== '') ? chauffeurId : null,
                date_tournee:    date,
                slot:            slot,
                notes:           document.getElementById('tcmd-notes').value,
                source_type:     'commande_vente',
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                var statut = document.getElementById('tournee-statut-bl-' + currentCMDLine.lineId);
                if (statut) statut.innerHTML = '<span class="badge badge-warning" style="font-size:0.6rem;">🚚</span>';

                // Optionnel : recharger tous les statuts de la commande
        loadTourneeStatutsCMD(currentCMDLine.blId);


                $('#tourneeCMDModal').modal('hide');
                
                var toast = document.createElement('div');
                toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;min-width:280px;';
                toast.innerHTML = '<div class="alert alert-success shadow-lg d-flex align-items-center mb-0" style="border-radius:8px;"><i class="fas fa-check-circle me-2"></i>' + d.message + '</div>';
                document.body.appendChild(toast);
                setTimeout(function() { toast.remove(); }, 4000);
            } else {
                errorDiv.textContent = d.error || "Erreur lors de l'ajout.";
                errorDiv.style.display = 'block';
            }
        })
        .catch(function() {
            errorDiv.textContent = 'Erreur réseau.';
            errorDiv.style.display = 'block';
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-route me-1"></i> Ajouter à la tournée';
        });
    });





    
})();





function loadTourneeStatutsCMD(orderId) {
    fetch('/tournee/lines/' + orderId + '?source_type=commande_vente')
        .then(function(r) { return r.json(); })
        .then(function(lines) {
            var colorMap = {
                'en_attente': 'secondary',
                'assigné':    'primary',
                'en_route':   'info',
                'recupere':   'success',
                'au_magasin': 'dark',
                'livre_client': 'success',
                'probleme':   'danger'
            };

            lines.forEach(function(l) {
                var span = document.getElementById('tournee-statut-bl-' + l.source_line_id);
                var btn  = span ? span.previousElementSibling : null;

                if (span) {
                    span.innerHTML = '<span class="badge badge-' + (colorMap[l.statut] || 'secondary') + '">'
                        + (l.statut_label || l.statut) + '</span>';
                }

                // Changer le bouton en jaune si déjà en tournée
                if (btn && btn.classList.contains('btn-tournee-cmd')) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-warning');
                    btn.title = 'Déjà en tournée — ' + (l.statut_label || l.statut)
                        + (l.chauffeur ? ' (' + l.chauffeur + ')' : '');
                }
            });
        })
        .catch(function() {});
}





function loadExistingLinesCMD(orderId) {
    var container = document.getElementById('tcmd-existing-lines');
    var content   = document.getElementById('tcmd-existing-content');
    content.innerHTML = '<small class="text-muted">Chargement...</small>';
    container.style.display = 'block';

    fetch('/tournee/lines/' + orderId + '?source_type=commande_vente')
        .then(function(r) { return r.json(); })
        .then(function(lines) {
            if (!lines.length) { container.style.display = 'none'; return; }
            var html = '';
            lines.forEach(function(l) {
                html += '<div class="d-flex justify-content-between align-items-center py-1 border-bottom">'
                    + '<span style="font-size:0.75rem;">'
                    + '<strong style="font-family:monospace;">' + l.article_code + '</strong>'
                    + ' — ' + l.slot_label + ' ' + l.date_tournee
                    + ' <span class="badge bg-' + l.statut_color + ' ms-1">' + l.statut_label + '</span>'
                    + (l.chauffeur ? ' 👤 ' + l.chauffeur : '')
                    + '</span>'
                    + '<button class="btn btn-xs btn-outline-danger btn-remove-tournee-cmd ms-2"'
                    + ' data-line-id="' + l.id + '"'
                    + ' style="font-size:0.65rem; padding:1px 6px;">✕</button>'
                    + '</div>';
            });
            content.innerHTML = html;
        })
        .catch(function() { container.style.display = 'none'; });
}

// Supprimer une ligne CMD de la tournée
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-remove-tournee-cmd');
    if (!btn) return;
    if (!confirm('Retirer cette ligne de la tournée ?')) return;

    var lineId  = btn.getAttribute('data-line-id');
    var csrf    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var orderId = document.getElementById('tcmd-numdoc').getAttribute('data-order-id');

    fetch('/tournee/lines/' + lineId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            if (orderId) loadExistingLinesCMD(orderId);
            else btn.closest('.d-flex').remove();
        }
    });
});
</script>






</body>
</html>

