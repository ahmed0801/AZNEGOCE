<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />
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






    <style>
#panierDropdown + .dropdown-menu {
    width: 900px; /* Adjust the width as needed */
    min-width: 350px; /* Ensure a minimum width */
    padding: 10px; /* Add padding to create space inside the dropdown */
    border-radius: 8px; /* Optional: Rounded corners for a cleaner look */
}

.panier-dropdown {
    width: 100%; /* Use full width of the parent container */
    min-width: 350px; /* Ensure minimum width */
}

.panier-dropdown .notif-item {
    padding: 10px; /* Add padding between items */
    margin-bottom: 5px; /* Space between items */
    border-bottom: 1px solid #ddd; /* Optional: Border between items */
}

.dropdown-title {
    font-weight: bold;
    margin-bottom: 10px; /* Space below the title */
}

.notif-scroll {
    padding: 10px; /* Add padding inside the scrollable area */
}

.notif-center {
    padding: 5px 0; /* Space around each notification */
}

.dropdown-footer {
    padding: 10px;
    border-top: 1px solid #ddd; /* Optional: Border to separate the footer */
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
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="{{ asset('assets/img/logop.png')}}"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
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


                

 





              <!-- test panier -->
      

         
                
                
                

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="{{ asset('assets/img/avatar.png')}}"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <!-- <span class="op-7">Hi,</span> -->
                      <span class="fw-bold">{{ Auth::user()->name}}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="{{ asset('assets/img/avatar.png')}}"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
<div class="u-text">
                            <h4>{{ Auth::user()->name}}</h4>

                            <p class="text-muted">{{ Auth::user()->email}}</p>
                            <a
                              href="/setting"
                              class="btn btn-xs btn-secondary btn-sm"
                              >Paramétres</a>

                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <!-- <a class="dropdown-item" href="#">My Profile</a> -->
                        <!-- <a class="dropdown-item" href="#">My Balance</a> -->
                        <!-- <div class="dropdown-divider"></div> -->

    <!-- Formulaire de déconnexion -->
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
          <!-- End Navbar -->
        </div>

        <div class="container">
    <div class="page-inner">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="container mt-4">
            <h4>📄 Liste des factures d'achat :
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-success btn-round ms-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Nouvelle facture Achat <i class="fas fa-plus-circle ms-2"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('invoices.create_free') }}">Facture Libre (hors stock)</a>
                        <a class="dropdown-item" href="{{ route('invoices.create_grouped') }}">Facture Achat Groupée</a>
                    </div>

                    <a href="/purchases" class="btn btn-outline-success btn-round ms-2">Nouvelle Commande Achat 
                <i class="fas fa-plus-circle ms-2"></i>
            </a>

                </div>
            </h4>

            <form method="GET" action="{{ route('invoices.list') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                <select name="supplier_id" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Fournisseur (Tous)</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="form-select form-select-sm" style="width: 110px;">
                    <option value="">Statut (Tous)</option>
                    <option value="brouillon" {{ request('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="validée" {{ request('status') == 'validée' ? 'selected' : '' }}>Validée</option>
                </select>

                <select name="paid" class="form-select form-select-sm" style="width: 110px;">
                                <option value="">Payé (Tous)</option>
                                <option value="1" {{ request('paid') == '1' ? 'selected' : '' }}>Payé</option>
                                <option value="0" {{ request('paid') == '0' ? 'selected' : '' }}>Non payé</option>
                            </select>

                <select name="type" class="form-select form-select-sm" style="width: 110px;">
                    <option value="">Type (Tous)</option>
                    <option value="direct" {{ request('type') == 'direct' ? 'selected' : '' }}>Directe</option>
                    <option value="groupée" {{ request('type') == 'groupée' ? 'selected' : '' }}>Groupée</option>
                    <option value="libre" {{ request('type') == 'libre' ? 'selected' : '' }}>Libre</option>
                </select>
de
                <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" placeholder="Date début"
                    value="{{ request('date_from') }}">
à
                <input type="date" name="date_to" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin"
                    value="{{ request('date_to') }}">

                <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                    <i class="fas fa-filter me-1"></i> Filtrer
                </button>

                <button type="submit" name="action" value="export" formaction="{{ route('invoices.export') }}"
                    class="btn btn-outline-success btn-sm px-3">
                    <i class="fas fa-file-excel me-1"></i> EXCEL
                </button>
                                    <!-- <button type="submit" name="action" value="export_pdf" formaction="{{ route('invoices.export') }}"
                            class="btn btn-outline-primary btn-sm px-3">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </button> -->

                <a href="{{ route('invoices.list') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="fas fa-undo me-1"></i> Réinitialiser
                </a>
            </form>



            <div class="d-flex justify-content-center mt-3">
    {{ $invoices->appends(request()->query())->links() }}
</div>


            @foreach ($invoices as $invoice)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                        <div>
                            <h6 class="mb-0">
                                <strong>Facture N° : {{ $invoice->numdoc }}</strong> –

                                {{ $invoice->supplier->name }}
                                <span class="text-muted small">({{ $invoice->supplier->code ?? 'N/A' }})</span>
                                <span class="text-muted small">- 📆 ({{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }})</span>
                            </h6>
                            @if($invoice->status === 'brouillon')
                                <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                            @else
                                <span class="badge bg-success">{{ ucfirst($invoice->status) }}</span>
                            @endif


                                <span class="badge rounded-pill text-bg-secondary">Total TTC : {{ number_format($invoice->total_ttc, 2, ',', ' ') }} €</span>
@if($invoice->status != 'brouillon')
                                            @if($invoice->paid)
                                                <span class="badge bg-success">Payé</span>
                                            @else
                                                <span class="badge bg-danger">Non payé ({{ number_format($invoice->getRemainingBalanceAttribute(), 2, ',', ' ') }} €)</span>
                                            @endif
                                        @endif
                                        <span class="text-muted small">&#8594; type: {{ ucfirst($invoice->type ?? 'N/A') }}</span>

    @if($invoice->acheteur)
            <span class="badge rounded-pill text-bg-light"><i class="fas fa-user-tie"></i> Facturée Par :  {{ $invoice->acheteur}}</span>

    @endif


                                    
                                    </div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $invoice->id }})">
                                ➕ Détails
                            </button>
                            <a href="{{ route('invoices.export_single', $invoice->id) }}" class="btn btn-xs btn-outline-success">
                                EXCEL <i class="fas fa-file-excel"></i>
                            </a>
                            <a href="{{ route('invoices.print_single', $invoice->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                PDF <i class="fas fa-print"></i>
                            </a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu">

                                @if($invoice->status === 'validée' && !$invoice->paid)
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#makePaymentModal{{ $invoice->id }}">
                                                        <i class="fas fa-credit-card"></i> Faire un règlement
                                                    </a>
                                                    <form action="{{ route('purchaseinvoices.markAsPaid', $invoice->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Marquer cette facture comme payée ?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-check"></i> Marquer Payé
                                                        </button>
                                                    </form>
                                                @endif


                                    @if($invoice->status === 'brouillon')
                                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    @endif
                                    @if($invoice->type === 'direct' && $invoice->orders()->exists())
                                        @foreach($invoice->orders as $order)
                                            <a class="dropdown-item" href="{{ route('purchases.edit', $order->id) }}">
                                                <i class="fas fa-eye"></i> Commande #{{ $order->numdoc }}
                                            </a>
                                        @endforeach
                                    @endif


                                    <!-- Supplier Invoice Actions -->
                            @if($invoice->supplier_invoice_file)
                                <a class="dropdown-item" href="{{ route('invoices.download_supplier_invoice', $invoice->id) }}" target="_blank">
                                    <i class="fas fa-eye"></i> Voir Facture Fournisseur
                                </a>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteSupplierInvoiceModal{{ $invoice->id }}">
                                    <i class="fas fa-trash"></i> Supprimer Facture Fournisseur
                                </a>
                            @else
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadSupplierInvoiceModal{{ $invoice->id }}">
                                    <i class="fas fa-upload"></i> Joindre Facture Fournisseur
                                </a>
                            @endif


                            
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="lines-{{ $invoice->id }}" class="card-body d-none bg-light">
                        <h6 class="fw-bold mb-3">🧾 Lignes de la facture</h6>
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
                                        <td class="text-end">{{ number_format($line->unit_price_ht, 2) }} €</td>
                                        <td class="text-end">{{ $line->remise }}%</td>
                                        <td class="text-end">{{ $line->tva }}%</td>
                                        <td class="text-end">{{ number_format($line->total_ligne_ht, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-end mt-3">
                            <div class="p-3 bg-white border rounded d-inline-block">
                                <strong>Total HT :</strong> {{ number_format($invoice->total_ht, 2) }} €<br>
                                <strong>Total TTC :</strong> {{ number_format($invoice->total_ttc, 2) }} €
                            </div>
                        </div>
                    </div>
                </div>








                <!-- Make Payment Modal -->
                            <div class="modal fade" id="makePaymentModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="makePaymentModalLabel{{ $invoice->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="makePaymentModalLabel{{ $invoice->id }}">Faire un règlement pour {{ $invoice->numdoc }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('purchaseinvoices.make_payment', $invoice->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="amount{{ $invoice->id }}" class="form-label">Montant (€)</label>
                                                    <input type="number" step="0.01" class="form-control" id="amount{{ $invoice->id }}" name="amount" max="{{ $invoice->getRemainingBalanceAttribute() }}" required>
                                                    <small>Reste à payer : {{ number_format($invoice->getRemainingBalanceAttribute(), 2, ',', ' ') }} €</small>
                                                                                                                    <!-- Bouton Lettrer -->
                                                                <button 
        type="button" 
        class="btn btn-outline-danger btn-sm"
        onclick="document.getElementById('amount{{ $invoice->id }}').value = '{{ abs($invoice->getRemainingBalanceAttribute()) }}'"
    >
        Lettrer
    </button>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="payment_date{{ $invoice->id }}" class="form-label">Date de paiement</label>
                                                    <input type="date" class="form-control" id="payment_date{{ $invoice->id }}" name="payment_date" value="{{ now()->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="payment_mode{{ $invoice->id }}" class="form-label">Mode de paiement</label>
                                                    <select class="form-control select2" id="payment_mode{{ $invoice->id }}" name="payment_mode" required>
                                                    <option value="">Sélectionner le mode de paiement</option>
                                                        @foreach(\App\Models\PaymentMode::all() as $mode)
                                                            <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reference{{ $invoice->id }}" class="form-label">Référence (optionnel)</label>
                                                    <input type="text" class="form-control" id="reference{{ $invoice->id }}" name="reference">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="notes{{ $invoice->id }}" class="form-label">Notes (optionnel)</label>
                                                    <textarea class="form-control" id="notes{{ $invoice->id }}" name="notes"></textarea>
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










                
<!-- facture fournisseur -->
<!-- Upload Supplier Invoice Modal -->
            <div class="modal fade" id="uploadSupplierInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="uploadSupplierInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadSupplierInvoiceModalLabel{{ $invoice->id }}">Joindre Facture Fournisseur pour {{ $invoice->numdoc }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('invoices.upload_supplier_invoice', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="supplier_invoice_file{{ $invoice->id }}" class="form-label">Choisir le fichier (PDF uniquement)</label>
                                    <input type="file" class="form-control" id="supplier_invoice_file{{ $invoice->id }}" name="supplier_invoice_file" accept="application/pdf,image/jpeg,image/png" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Joindre</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Supplier Invoice Modal -->
            <div class="modal fade" id="deleteSupplierInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="deleteSupplierInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteSupplierInvoiceModalLabel{{ $invoice->id }}">Supprimer Facture Fournisseur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('invoices.delete_supplier_invoice', $invoice->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer la facture fournisseur associée à {{ $invoice->numdoc }} ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
<!-- fin facture fournisseur -->







            @endforeach
        </div>

                  <div class="d-flex justify-content-center mt-3">
    {{ $invoices->appends(request()->query())->links() }}
</div>


    </div>














        </div>
    
        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <!-- <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul> -->
            </nav>
            <div class="copyright">
            © AZ NEGOCE. All Rights Reserved.
              <!-- <a href="http://www.themekita.com">By Ahmed Arfaoui</a> -->
            </div>
            <div>
               by
              <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.
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



<!-- Ajoute select2 si pas déjà inclus -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function toggleLines(id) {
        const section = document.getElementById('lines-' + id);
        section.classList.toggle('d-none');
    }
</script>












<script>
document.getElementById("searchItemInput").addEventListener("keyup", function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll("#itemsTable tbody tr");

    rows.forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
    });
});
</script>
















  </body>
</html>
