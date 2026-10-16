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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />





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








    /* ===== En-tête liste : boutons + filtres ===== */
.lh-bar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
.lh-title{margin:0;font-weight:700;display:flex;align-items:center;gap:.5rem}
.lh-btns{display:flex;flex-wrap:wrap;gap:.5rem}
.lh-btns .btn,.lh-filters .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:0 1rem;font-size:.88rem;font-weight:600;border-radius:8px;white-space:nowrap;margin:0}
.lh-filters{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;margin-bottom:1rem}
.lh-filters .lh-field{flex:1 1 150px;min-width:130px;position:relative}
.lh-filters .lh-field.lh-wide{flex:2 1 220px}
.lh-filters .lh-field.lh-narrow{flex:0 1 130px;min-width:120px}
.lh-filters .lh-field i.lh-ico{position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none}
.lh-filters .lh-field i.lh-ico + input{padding-left:2.1rem}
.lh-filters .form-control,.lh-filters .form-select{height:38px;border-radius:8px;font-size:.88rem}
.lh-filters .lh-dates{display:flex;align-items:center;gap:.4rem;flex:1 1 280px}
.lh-filters .lh-dates span{color:#6b7280;font-size:.8rem;white-space:nowrap}
.lh-filters .lh-spacer{flex:1 1 auto}
.lh-filters .select2-container{width:100%!important}
.lh-filters .select2-container--default .select2-selection--single{height:38px!important;min-height:38px!important;border-radius:8px!important;display:flex;align-items:center;font-size:.88rem!important;padding:0 .4rem!important}
.lh-filters .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px!important}
.lh-filters .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px!important}
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
                            <li><a href="{{ route('purchase_docs.create') }}"><span class="sub-item"><i class="fas fa-plus-circle"></i> Nouveau document</span></a></li>
                            <li><a href="{{ route('purchaseprojects.list') }}"><span class="sub-item">Projets d’Achat</span></a></li>
                            <li><a href="{{ route('purchases.list') }}"><span class="sub-item">Commandes</span></a></li>
                            <li><a href="{{ route('receptions.index') }}"><span class="sub-item">Réceptions</span></a></li>
                            <li><a href="{{ route('invoices.list') }}"><span class="sub-item">Factures</span></a></li>
                            <li><a href="{{ route('returns.list') }}"><span class="sub-item">Retours</span></a></li>
                            <li><a href="{{ route('notes.list') }}"><span class="sub-item">Avoirs</span></a></li>
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
                            <li><a href="{{ route('admin.golda.index') }}"><span class="sub-item">Import GOLDA</span></a></li>
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
                        <div class="lh-bar">
    <h4 class="lh-title"><i class="fas fa-file-invoice text-primary"></i> Avoirs d'achat</h4>
    <div class="lh-btns">
        @if($returns->isEmpty())
            <span class="btn btn-outline-secondary disabled" title="Aucun retour disponible"><i class="fas fa-undo-alt"></i> Avoir depuis un retour</span>
        @else
            <a href="{{ route('notes.create_from_return') }}" class="btn btn-primary"><i class="fas fa-undo-alt"></i> Avoir depuis un retour</a>
        @endif
        @if($invoices->isEmpty())
            <span class="btn btn-outline-secondary disabled" title="Aucune facture disponible"><i class="fas fa-file-invoice-dollar"></i> Avoir depuis une facture</span>
        @else
            <a href="{{ route('notes.create_from_invoice') }}" class="btn btn-outline-primary"><i class="fas fa-file-invoice-dollar"></i> Avoir depuis une facture</a>
        @endif
    </div>
</div>

EXCEL
<form method="GET" action="{{ route('notes.list') }}" class="lh-filters">

    <div class="lh-field lh-wide">
        <select name="supplier_id" class="form-select select2-filter">
            <option value="">Fournisseurs (Tous)</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="lh-field lh-narrow">
        <select name="status" class="form-select select2-filter">
            <option value="">Statut (Tous)</option>
        <option value="brouillon" {{ request('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
        <option value="validée" {{ request('status') == 'validée' ? 'selected' : '' }}>Validée</option>
        </select>
    </div>

    <div class="lh-field">
        <select name="type" class="form-select select2-filter">
            <option value="">Type (Tous)</option>
        <option value="from_return" {{ request('type') == 'from_return' ? 'selected' : '' }}>Depuis un retour</option>
        <option value="from_invoice" {{ request('type') == 'from_invoice' ? 'selected' : '' }}>Depuis une facture</option>
        </select>
    </div>

    <div class="lh-dates">
        <span>Du</span>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        <span>au</span>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>

    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
    <button type="submit" name="action" value="export" formaction="{{ route('notes.export') }}" class="btn btn-outline-success">
        <i class="fas fa-file-excel"></i> Exporter
    </button>
    <a href="{{ route('notes.list') }}" class="btn btn-outline-secondary"><i class="fas fa-undo"></i> Réinitialiser</a>
</form>


                                                                                                                                        <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $notes->appends(request()->query())->links() }}
</div>


                        @forelse ($notes as $note)
                            <div class="card mb-4 shadow-sm border-0">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                    <div>
                                        <h6 class="mb-0">
                                            <strong>Avoir N° : {{ $note->numdoc }}</strong> –
                                            {{ $note->supplier ? $note->supplier->name : 'Fournisseur inconnu' }}
                                            <span class="text-muted small">({{ \Carbon\Carbon::parse($note->note_date)->format('d/m/Y') }})</span>
                                        </h6>
                                        @if($note->status === 'brouillon')
                                            <span class="badge bg-secondary">{{ ucfirst($note->status) }}</span>
                                        @elseif($note->status === 'annulée')
                                            <span class="badge bg-dark">Annulé</span>
                                        @else
                                            <span class="badge bg-success">{{ ucfirst($note->status) }}</span>
                                        @endif
                                        <span class="badge rounded-pill text-bg-secondary">Total TTC : {{ number_format($note->total_ttc, 2, ',', ' ') }} €</span>
                                        @if($note->return_goods)<span class="badge bg-light text-dark border" title="La validation a fait ressortir la marchandise du stock"><i class="fas fa-dolly"></i> Marchandise retournée</span>@endif

                                        @if($note->status === 'validée')
                                            @if($note->paid)
                                                <span class="badge bg-success">Payé</span>
                                            @else
                                                <span class="badge bg-danger">Non payé ({{ number_format($note->getRemainingBalanceAttribute(), 2, ',', ' ') }} €)</span>
                                            @endif
                                        @endif


                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $note->type)) }}</span>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $note->id }})">
                                            ➕ Détails
                                        </button>
                                        <a href="{{ route('notes.exportSingle', $note->id) }}" class="btn btn-xs btn-outline-success">
                                            EXCEL <i class="fas fa-file-excel"></i>
                                        </a>
                                        <a href="{{ route('notes.printSingle', $note->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                            PDF <i class="fas fa-print"></i>
                                        </a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if($note->status === 'brouillon')
                                                    <a class="dropdown-item" href="{{ route('notes.edit', $note->id) }}">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('notes.validate', $note->id) }}" method="POST" onsubmit="return confirm('Valider cet avoir ? Le solde du fournisseur diminuera de {{ number_format(abs($note->total_ttc), 2, ',', ' ') }} €.{{ $note->return_goods ? ' La marchandise sera ressortie du stock.' : '' }}')">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-check"></i> Valider l'avoir</button>
                                                    </form>
                                                @endif
                                                @if($note->status !== 'annulée')
                                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#cancelNote{{ $note->id }}">
                                                        <i class="fas fa-ban"></i> Annuler l'avoir
                                                    </a>
                                                @endif

                                                 @if($note->status === 'validée' && !$note->paid)
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#makePaymentModal{{ $note->id }}">
                                                        <i class="fas fa-credit-card"></i> Faire un règlement
                                                    </a>
                                                @endif


                                                @foreach($note->lines->pluck('purchaseReturn')->filter()->unique('id') as $srcReturn)
                                                    <a class="dropdown-item" href="{{ route('purchases.return.show', $srcReturn->id) }}">
                                                        <i class="fas fa-eye"></i> Retour #{{ $srcReturn->numdoc }}
                                                    </a>
                                                @endforeach
                                                @foreach($note->lines->pluck('purchaseInvoice')->filter()->unique('id') as $srcInvoice)
                                                    <a class="dropdown-item" href="{{ route('invoices.list', ['q' => $srcInvoice->numdoc]) }}">
                                                        <i class="fas fa-eye"></i> Facture #{{ $srcInvoice->numdoc }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($note->status !== 'annulée')
                                <div class="modal fade" id="cancelNote{{ $note->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog"><div class="modal-content">
                                        <form action="{{ route('notes.cancel', $note->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Annuler l'avoir {{ $note->numdoc }} ?</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                            <div class="modal-body">
                                                <ul class="small">
                                                    @if($note->status === 'validée')<li>Le solde du fournisseur remonte de {{ number_format(abs($note->total_ttc), 2, ',', ' ') }} €.</li>@endif
                                                    @if($note->return_goods && $note->status === 'validée')<li>La marchandise ressortie du stock par cet avoir y revient.</li>@endif
                                                    <li>Les retours qu'il couvrait redeviennent disponibles. Refusé s'il existe des règlements.</li>
                                                </ul>
                                                <input type="text" name="reason" class="form-control" maxlength="150" placeholder="Motif (facultatif)">
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button><button type="submit" class="btn btn-danger">Annuler l'avoir</button></div>
                                        </form>
                                    </div></div>
                                </div>
                                @endif

                                <div id="lines-{{ $note->id }}" class="card-body d-none bg-light">
                                    <h6 class="fw-bold mb-3">🧾 Lignes de l’avoir</h6>
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
                                            @foreach ($note->lines as $line)
                                                <tr>
                                                    <td>{{ $line->article_code ?? '-' }}</td>
                                                    <td>{{ $line->item ? $line->item->name : ($line->description ?? '-') }}</td>
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
                                            <strong>Total HT :</strong> {{ number_format($note->total_ht, 2) }} €<br>
                                            <strong>Total TTC :</strong> {{ number_format($note->total_ttc, 2) }} €
                                        </div>
                                    </div>
                                </div>



                                 <!-- Make Payment Modal -->
                                @if($note->status === 'validée' && !$note->paid)
                                    <div class="modal fade" id="makePaymentModal{{ $note->id }}" tabindex="-1" aria-labelledby="makePaymentModalLabel{{ $note->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="makePaymentModalLabel{{ $note->id }}">Faire un règlement pour {{ $note->numdoc }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('notes.make_payment', $note->id) }}" method="POST" class="payment-form">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label for="amount{{ $note->id }}" class="form-label">Montant (€)</label>
                                                            <input type="number" step="0.01" class="form-control" id="amount{{ $note->id }}" name="amount"  value="{{ old('amount') }}" required>
                                                            <small>Reste à payer : {{ number_format($note->getRemainingBalanceAttribute(), 2, ',', ' ') }} €</small>
                                                                <!-- Bouton Lettrer -->
                                                                <button 
        type="button" 
        class="btn btn-outline-danger btn-sm"
        onclick="document.getElementById('amount{{ $note->id }}').value = '{{ abs($note->getRemainingBalanceAttribute()) }}'"
    >
        Lettrer
    </button>

                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_date{{ $note->id }}" class="form-label">Date de paiement</label>
                                                            <input type="date" class="form-control" id="payment_date{{ $note->id }}" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_mode{{ $note->id }}" class="form-label">Mode de paiement</label>
                                                            <select class="form-control select2" id="payment_mode{{ $note->id }}" name="payment_mode" required>
                                                            <option value="">Sélectionner le mode de paiement</option>
                                                                @foreach(\App\Models\PaymentMode::all() as $mode)
                                                                    <option value="{{ $mode->name }}" {{ old('payment_mode') == $mode->name ? 'selected' : '' }}>{{ $mode->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="reference{{ $note->id }}" class="form-label">Référence (optionnel)</label>
                                                            <input type="text" class="form-control" id="reference{{ $note->id }}" name="reference" value="{{ old('reference') }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="notes{{ $note->id }}" class="form-label">Notes (optionnel)</label>
                                                            <textarea class="form-control" id="notes{{ $note->id }}" name="notes">{{ old('notes') }}</textarea>
                                                        </div>
                                                        <input type="hidden" name="form_id" value="payment_form_{{ $note->id }}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary payment-submit-btn">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif





                            </div>
                        @empty
                            <div class="alert alert-info">
                                Aucun avoir trouvé.
                            </div>


                            
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-3">
    {{ $notes->appends(request()->query())->links() }}
</div>


   </div>   </div>


    
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
              <!-- <a href="http://www.themekita.com">By AZ NEGOCE</a> -->
            </div>
            <div>
               by
              <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.
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



          <script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Debug: Log when Select2 is initialized
        console.log('Select2 initialized');

        // Handle return selection
        $('#return_id').on('select2:select', function(e) {
            e.stopPropagation();
            const returnId = $(this).val();
            console.log('Return selected:', returnId);
            if (returnId) {
                const url = '{{ route("notes.create_from_return", ":returnId") }}'.replace(':returnId', returnId);
                $('#createFromReturnForm').attr('action', url);
                $('#createFromReturnButton').removeAttr('disabled').removeClass('disabled');
                console.log('Updated return form action:', url);
            } else {
                $('#createFromReturnForm').attr('action', '#');
                $('#createFromReturnButton').attr('disabled', 'disabled').addClass('disabled');
                console.log('Return form action reset to #');
            }
        });

        // Handle invoice selection
        $('#invoice_id').on('select2:select', function(e) {
            e.stopPropagation();
            const invoiceId = $(this).val();
            console.log('Invoice selected:', invoiceId);
            if (invoiceId) {
                const url = '{{ route("notes.create_from_invoice", ":invoiceId") }}'.replace(':invoiceId', invoiceId);
                $('#createFromInvoiceForm').attr('action', url);
                $('#createFromInvoiceButton').removeAttr('disabled').removeClass('disabled');
                console.log('Updated invoice form action:', url);
            } else {
                $('#createFromInvoiceForm').attr('action', '#');
                $('#createFromInvoiceButton').attr('disabled', 'disabled').addClass('disabled');
                console.log('Invoice form action reset to #');
            }
        });

        // Debug: Log when modals are opened
        $('#selectReturnModal').on('shown.bs.modal', function() {
            console.log('Return modal opened');
        });
        $('#selectInvoiceModal').on('shown.bs.modal', function() {
            console.log('Invoice modal opened');
        });

        // Debug: Log when buttons are clicked
        $('#createFromReturnButton').on('click', function() {
            const url = $('#createFromReturnForm').attr('action');
            console.log('Create from return button clicked, form action:', url);
            if (url && url !== '#') {
                console.log('Submitting return form to:', url);
                // Form submission will handle navigation
            } else {
                console.error('Invalid return URL');
                alert('Veuillez sélectionner un retour valide.');
            }
        });

        $('#createFromInvoiceButton').on('click', function() {
            const url = $('#createFromInvoiceForm').attr('action');
            console.log('Create from invoice button clicked, form action:', url);
            if (url && url !== '#') {
                console.log('Submitting invoice form to:', url);
                // Form submission will handle navigation
            } else {
                console.error('Invalid invoice URL');
                alert('Veuillez sélectionner une facture valide.');
            }
        });
    });

    function toggleLines(id) {
        const section = document.getElementById('lines-' + id);
        section.classList.toggle('d-none');
    }
</script>

  @include('partials.select2_init')
</body>
</html>
