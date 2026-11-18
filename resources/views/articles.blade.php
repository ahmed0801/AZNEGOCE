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




/* Version compactée - filtres plus petits */
.select2-wrapper {
    position: relative;
}

/* Hauteur réduite + texte plus petit */
.select2-container--default .select2-selection--single {
    height: 28px !important;           /* plus petit que la version originale */
    min-height: 28px !important;
    padding: 0.2rem 0.4rem !important; /* padding intérieur réduit */
    font-size: 0.82rem !important;     /* texte légèrement plus petit */
    line-height: 1.35 !important;
    border-radius: 0.25rem;
}

/* Texte dans le champ sélectionné */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px !important;      /* aligné avec la hauteur */
    padding-left: 6px !important;
    padding-right: 22px !important;    /* espace pour la flèche */
    color: #495057;
}

/* Flèche de dropdown */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px !important;
    width: 20px !important;
}

/* Optionnel : placeholder plus discret */
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #999;
    font-size: 0.82rem;
}








/* Tableau compact multi-fournisseurs */
.table-responsive {
    overflow-x: auto;
    max-width: 100%;
}

/* .table-text-small th, .table-text-small td {
    font-size: 10px !important;
    padding: 4px 6px !important;
    white-space: nowrap;
} */

.multi-supplier-cell {
    font-size: 9px;
    line-height: 1.1;
}

.multi-supplier-cell .sup {
    color: #0069d9;
    font-weight: bold;
}

.multi-supplier-cell .price {
    color: #dc3545;
}

.multi-supplier-cell .rem {
    color: #28a745;
}

/* Sur mobile : réduire encore + masquer certaines colonnes */
@media (max-width: 992px) {
    .table-text-small th:nth-child(n+8), 
    .table-text-small td:nth-child(n+8) {
        display: none;
    }
    .table-text-small {
        font-size: 9px !important;
    }
}





 .modal-md { max-width: 1000px; }


 #itemsTable td.col-actions{min-width:150px;white-space:nowrap}
#itemsTable .act-btns{display:flex;flex-direction:column;gap:.3rem}
#itemsTable .act-btns .btn{display:inline-flex;align-items:center;justify-content:flex-start;gap:.4rem;font-size:.78rem;font-weight:600;text-align:left}

    </style>



<!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- Select2 JS (après jQuery) -->






  </head>
  <body>
    <div class="wrapper sidebar_minimize">
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
                            <li><a href="{{ route('purchase_docs.create') }}"><span class="sub-item"></i> Nouveau document</span></a></li>
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



        <div class="container-fluid mt-4">
        {{-- Affichage des messages d'erreur --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    
        <div class="container-fluid mt-4">

        <style>
.modal-content{position:relative}
.modal-content .select2-dropdown{z-index:1070}
.art-bar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
.art-bar h4{margin:0;font-weight:700}
.art-btns{display:flex;flex-wrap:wrap;gap:.5rem}
.art-btns .btn,.art-filters .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:0 1rem;font-size:.88rem;font-weight:600;border-radius:8px;white-space:nowrap;margin:0}
.art-btns .dropdown-menu{border-radius:10px;border:1px solid #e5e9f2;box-shadow:0 8px 24px rgba(30,45,74,.12);font-size:.85rem;padding:.35rem}
.art-btns .dropdown-item{border-radius:6px;padding:.4rem .7rem;display:flex;align-items:center;gap:.55rem}
.art-btns .dropdown-item i{width:16px;text-align:center;color:#64748b}
.art-btns .dropdown-divider{margin:.3rem 0}

/* ===== Filtres ===== */
.af-filters{background:#fff;border:1px solid #e5e9f2;border-radius:12px;padding:.6rem .7rem;margin-bottom:1rem;box-shadow:0 1px 3px rgba(30,45,74,.05)}
.af-row{display:flex;flex-wrap:wrap;gap:.45rem;align-items:center}
.af-row + .af-adv{margin-top:.5rem}
.af-filters .af-search{flex:2 1 230px;position:relative;min-width:200px}
.af-filters .af-search i{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.78rem}
.af-filters .af-search input{padding-left:2rem}
.af-filters .form-control,.af-filters .btn{height:34px;font-size:.82rem;border-radius:8px}
.af-filters .btn{display:inline-flex;align-items:center;gap:.4rem;padding:0 .85rem;font-weight:600;white-space:nowrap;margin:0}
.af-filters .art-sel{flex:1 1 140px;min-width:130px}
.af-filters .art-sel select{width:100%}
.af-filters .select2-container{width:100%!important}
.af-filters .select2-container--default .select2-selection--single{height:34px!important;min-height:34px!important;border-radius:8px!important;display:flex;align-items:center;font-size:.82rem!important}
.af-filters .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:32px!important}
.af-filters .select2-container--default .select2-selection--single .select2-selection__arrow{height:32px!important}
.af-adv{display:none;border-top:1px dashed #e5e9f2;padding-top:.55rem}
.af-adv.show{display:block}
.af-adv .af-row{align-items:flex-end}
.af-fl{display:flex;flex-direction:column;gap:2px;flex:1 1 140px;min-width:130px}
.af-fl>.af-fn{font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8}
.af-period{flex:2 1 300px}
.af-period .af-pr{display:flex;gap:.35rem;align-items:center}
.af-period .af-pr input{flex:1 1 0;min-width:0}
.af-chips{display:flex;gap:.25rem;flex-wrap:wrap;margin-top:3px}
.af-chip{border:1px solid #dbe3f0;background:#f8fafc;color:#475569;border-radius:20px;font-size:.68rem;font-weight:600;padding:1px 9px;cursor:pointer;line-height:1.5}
.af-chip:hover{background:#eef4ff;border-color:#93c5fd;color:#1d4ed8}
.af-btn-adv{background:#f1f5f9;color:#334155;border:1px solid #e2e8f0}
.af-btn-adv:hover{background:#e2e8f0}
.af-btn-adv .badge{background:#2563eb;color:#fff;border-radius:10px;font-size:.65rem;padding:2px 6px}
.af-spacer{flex:0 0 auto;display:flex;gap:.35rem}

/* ===== Formulaires article (création / modification) ===== */
.af .modal-content{border:0;border-radius:14px;overflow:hidden;box-shadow:0 20px 50px rgba(15,23,42,.25)}
.af .modal-header{padding:.7rem 1.1rem;border-bottom:1px solid #eef1f6;background:linear-gradient(180deg,#fbfcfe,#f5f8fd)}
.af .modal-title{font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.55rem;color:#1e2d4a}
.af-ico{width:28px;height:28px;border-radius:8px;background:#e8f5ee;color:#16a34a;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem}
.af-ico-e{background:#e8f0fe;color:#2563eb}
.af .modal-body{padding:.85rem 1.1rem;background:#f6f8fc;max-height:calc(100vh - 180px);overflow-y:auto}
.af .modal-footer{padding:.55rem 1.1rem;border-top:1px solid #eef1f6;background:#fff;gap:.4rem}
.af-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:.7rem}
.af-g{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:.45rem .6rem}
.af .s-3{grid-column:span 3}.af .s-4{grid-column:span 4}.af .s-6{grid-column:span 6}.af .s-8{grid-column:span 8}.af .s-9{grid-column:span 9}.af .s-12{grid-column:span 12}
.af-card{background:#fff;border:1px solid #e8edf5;border-radius:10px;padding:.65rem .75rem}
.af-t{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#2563eb;margin-bottom:.5rem;display:flex;align-items:center;gap:.4rem}
.af-t i{font-size:.72rem}
.af-l{display:block;font-size:.68rem;font-weight:600;color:#64748b;margin:0 0 2px}
.af .form-control-sm,.af .form-select-sm{height:32px;font-size:.82rem;border-radius:7px;padding-top:.2rem;padding-bottom:.2rem}
.af textarea.form-control-sm{height:auto;min-height:0;resize:vertical}
.af .af-margin{font-style:italic;background:#f1f5f9}
.af .select2-container{width:100%!important}
.af .select2-container--default .select2-selection--single{height:32px!important;border-radius:7px!important;border:1px solid #ced4da!important;display:flex;align-items:center}
.af .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:30px!important;font-size:.82rem;padding-left:.6rem}
.af .select2-container--default .select2-selection--single .select2-selection__arrow{height:30px!important}
.af-sw{display:flex;align-items:center;min-height:32px;background:#f8fafc;border:1px solid #e8edf5;border-radius:8px;padding:.25rem .6rem}
.af-sw .form-check-label{font-size:.82rem;cursor:pointer}
.af-h{font-size:.68rem;color:#94a3b8;font-weight:500;margin-left:.25rem}
.af-sup{display:grid;grid-template-columns:minmax(0,1fr) 140px 140px;gap:.45rem;margin-bottom:.4rem;align-items:center}
.af-sup:last-child{margin-bottom:0}
.af-sup-h{margin-bottom:.15rem}
.af-sup-h span{font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8}
.af-supsel{display:flex;align-items:center;gap:.5rem;min-width:0}
.af-supsel .select2-container{flex:1 1 auto;min-width:0}
.af-rk{flex:0 0 88px;font-size:.72rem;font-weight:600;color:#475569}
.af-supsel select{flex:1 1 auto;min-width:0}
.af .form-control[disabled],.af .form-select[disabled],.af textarea[disabled]{background-color:#f1f5f9;cursor:not-allowed;color:#334155}
@media (max-width:991px){.af .s-3,.af .s-4,.af .s-6,.af .s-8,.af .s-9{grid-column:span 12}.af .af-g .s-6{grid-column:span 6}.af .af-g .s-3,.af .af-g .s-4{grid-column:span 6}.af-sup{grid-template-columns:1fr 1fr}.af-supsel{grid-column:span 2}.af-sup-h{display:none}}
</style>

@php $suppliersAll = \App\Models\Supplier::orderBy('code')->get(); @endphp
<div class="art-bar">
    <h4><i class="fas fa-boxes text-primary me-2"></i>Articles</h4>

    <div class="art-btns">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">
            <i class="fas fa-plus-circle"></i> Nouvel article
        </button>
        <a href="{{ route('sales.stats') }}" class="btn btn-outline-dark" title="Statistiques de vente, rotation, top ventes"><i class="fas fa-chart-line"></i> Statistiques</a>
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-sliders-h"></i> Gérer</button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ url('/categories') }}"><i class="fas fa-sitemap"></i> Familles &amp; sous-familles</a></li>
                <li><a class="dropdown-item" href="{{ url('/brands') }}"><i class="fas fa-tags"></i> Marques</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/groupremises"><i class="fas fa-percent"></i> Groupes remises</a></li>
                <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createDiscountGroupModal"><i class="fas fa-plus"></i> Nouveau groupe remise</button></li>
                <li><button type="button" class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#massDiscountPanel" aria-expanded="false" aria-controls="massDiscountPanel"><i class="fas fa-percent text-warning"></i> Remise en masse</button></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-file-import"></i> Importer</button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.golda.index') }}"><i class="fas fa-cloud-download-alt"></i> Import GOLDA</a></li>
                <li><a class="dropdown-item" href="{{ route('articles.import') }}"><i class="fas fa-file-excel"></i> Import Excel</a></li>
            </ul>
        </div>
    </div>
</div>




<!-- Modal création article -->
<div class="modal fade af" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createItemModalLabel"><span class="af-ico"><i class="fas fa-plus"></i></span> Nouvel article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form id="createItemForm" action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="af-grid">
<div class="af-card s-8"><div class="af-t"><i class="fas fa-id-card"></i> Identification</div><div class="af-g">
<div class="s-3"><label class="af-l">Code article</label><input type="text" name="code" id="code" class="form-control form-control-sm" value="{{ old('code') }}" required></div>
<div class="s-9"><label class="af-l">Désignation</label><input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ old('name') }}" required></div>
<div class="s-6"><label class="af-l">Famille</label><select name="category_id" id="category_id" class="form-select form-select-sm">@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
<div class="s-6"><label class="af-l">Sous-famille</label><select name="subcategory_id" class="form-select form-select-sm" data-subcat-select data-cat-source="#category_id"><option value="">-- Aucune --</option>@foreach($subcategories as $sub)<option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}">{{ $sub->code }} — {{ $sub->name }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Marque</label><select name="brand_id" id="brand_id" class="form-select form-select-sm">@foreach($brands as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Unité</label><select name="unit_id" id="unit_id" class="form-select form-select-sm">@foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->label ?? $unit->code }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">TVA</label><select name="tva_group_id" id="tva_group_id" class="form-select form-select-sm">@foreach($tvaGroups as $tva)<option value="{{ $tva->id }}">{{ $tva->name }} ({{ $tva->rate }}%)</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Code-barres</label><input type="text" name="barcode" id="barcode" class="form-control form-control-sm"></div>
<div class="s-4"><label class="af-l">Magasin</label><select name="store_id" class="form-select form-select-sm">@foreach($stores as $store)<option value="{{ $store->id }}">{{ $store->name }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Emplacement</label><input type="text" name="location" class="form-control form-control-sm" placeholder="Ex : Rayon A2"></div>
<div class="s-12"><label class="af-l">Description technique</label><textarea name="description" class="form-control form-control-sm" rows="2"></textarea></div>
</div></div>
<div class="af-card s-4"><div class="af-t"><i class="fas fa-euro-sign"></i> Vente &amp; stock</div><div class="af-g">
<div class="s-6"><label class="af-l">Marge (%)</label><input type="number" step="0.01" id="margin" class="form-control form-control-sm af-margin" placeholder="Ex : 20" autocomplete="off"></div>
<div class="s-6"><label class="af-l">Prix de vente HT</label><input type="number" name="sale_price" step="0.01" class="form-control form-control-sm" value="0.00"></div>
<div class="s-12"><label class="af-l">Groupe de remise</label><select name="discount_group_id" id="discount_group_id" class="form-select form-select-sm"><option value="">-- Aucun (standard) --</option>@foreach($discountGroups as $group)<option value="{{ $group->id }}" {{ old('discount_group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }} (G:{{ $group->discount_rate }}% · J:{{ $group->discount_rate_jobber }}% · P:{{ $group->discount_rate_professionnel }}%)</option>@endforeach</select></div>
<div class="s-12"><div class="af-sw"><input type="hidden" name="prix_net" value="0"><div class="form-check form-switch m-0"><input class="form-check-input" type="checkbox" role="switch" id="prix_net" name="prix_net" value="1" {{ old('prix_net') == '1' ? 'checked' : '' }}><label class="form-check-label" for="prix_net"><b>Prix net</b> <span class="af-h">protégé des imports</span></label></div></div></div>
<div class="s-6"><label class="af-l">Stock min</label><input type="number" name="stock_min" class="form-control form-control-sm" value="0"></div>
<div class="s-6"><label class="af-l">Stock max</label><input type="number" name="stock_max" class="form-control form-control-sm" value="0"></div>
</div></div>
<div class="af-card s-12"><div class="af-t"><i class="fas fa-truck-loading"></i> Fournisseurs &amp; prix d'achat</div>
<div class="af-sup af-sup-h"><span>Fournisseur</span><span>Prix achat</span><span>Remise (%)</span></div>
<div class="af-sup"><div class="af-supsel"><span class="af-rk">Principal</span><select name="codefournisseur" class="form-select form-select-sm"><option value="">-- Aucun --</option>@foreach($suppliersAll as $s)<option value="{{ $s->code }}" {{ old('codefournisseur', '') == $s->code ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>@endforeach</select></div><input type="number" step="0.0001" name="cost_price" class="form-control form-control-sm" value="{{ old('cost_price', 0) }}" title="Prix achat"><input type="number" step="0.01" name="remise_achat" class="form-control form-control-sm" value="{{ old('remise_achat', 0) }}" title="Remise achat %"></div>
<div class="af-sup"><div class="af-supsel"><span class="af-rk">Fournisseur 2</span><select name="codefournisseur_2" class="form-select form-select-sm"><option value="">-- Aucun --</option>@foreach($suppliersAll as $s)<option value="{{ $s->code }}" {{ old('codefournisseur_2', '') == $s->code ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>@endforeach</select></div><input type="number" step="0.0001" name="cost_price_2" class="form-control form-control-sm" value="{{ old('cost_price_2', 0) }}" title="Prix achat"><input type="number" step="0.01" name="remise_achat_2" class="form-control form-control-sm" value="{{ old('remise_achat_2', 0) }}" title="Remise achat %"></div>
<div class="af-sup"><div class="af-supsel"><span class="af-rk">Fournisseur 3</span><select name="codefournisseur_3" class="form-select form-select-sm"><option value="">-- Aucun --</option>@foreach($suppliersAll as $s)<option value="{{ $s->code }}" {{ old('codefournisseur_3', '') == $s->code ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>@endforeach</select></div><input type="number" step="0.0001" name="cost_price_3" class="form-control form-control-sm" value="{{ old('cost_price_3', 0) }}" title="Prix achat"><input type="number" step="0.01" name="remise_achat_3" class="form-control form-control-sm" value="{{ old('remise_achat_3', 0) }}" title="Remise achat %"></div>
</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success btn-sm px-4"><i class="fas fa-check me-1"></i> Créer l'article</button>
                </div>
            </form>
        </div>
    </div>
</div>


@php
    $advKeys = ['discount_group_id','prix_net','is_active','stock','rotation','date_from','date_to'];
    $advCount = 0;
    foreach ($advKeys as $k) { if (request($k) !== null && request($k) !== '') $advCount++; }
@endphp
<form method="GET" action="{{ route('articles.index') }}" class="af-filters" id="afFilters">
    <div class="af-row">
        <div class="af-search">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Référence, désignation…" value="{{ request('search') }}">
        </div>
        <div class="art-sel">
            <select name="brand_id" class="form-select select2-filter">
                <option value="">Marques (Toutes)</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ (string) request('brand_id') === (string) $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="art-sel">
            <select name="category_id" class="form-select select2-filter">
                <option value="">Familles (Toutes)</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="art-sel">
            <select name="subcategory_id" class="form-select select2-filter" data-subcat-select data-cat-source="select[name=category_id].select2-filter">
                <option value="">Sous-familles (Toutes)</option>
                @foreach($subcategories as $sub)
                    <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" {{ (string) request('subcategory_id') === (string) $sub->id ? 'selected' : '' }}>{{ $sub->code }} — {{ $sub->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="art-sel">
            <select name="codefournisseur" class="form-select select2-filter">
                <option value="">Fournisseurs (Tous)</option>
                @foreach($suppliersAll as $supplier)
                    <option value="{{ $supplier->code }}" {{ request('codefournisseur') == $supplier->code ? 'selected' : '' }}>{{ $supplier->code }} - {{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="af-spacer">
            <button type="button" class="btn af-btn-adv" id="afAdvBtn" title="Groupe remise, prix net, statut, stock, rotation…">
                <i class="fas fa-sliders-h"></i> Plus de filtres
                <span class="badge" id="afAdvCount" style="{{ $advCount ? '' : 'display:none' }}">{{ $advCount }}</span>
            </button>
            <button type="submit" name="action" value="filter" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary" title="Réinitialiser"><i class="fas fa-undo"></i></a>
            <button type="submit" name="action" value="export" formaction="{{ route('articles.export') }}" class="btn btn-outline-success" title="Exporter en Excel avec les filtres actifs"><i class="fas fa-file-excel"></i> Exporter</button>
        </div>
    </div>

    <div class="af-adv {{ $advCount ? 'show' : '' }}" id="afAdv">
        <div class="af-row">
            <label class="af-fl"><span class="af-fn">Groupe remise</span>
                <select name="discount_group_id" class="form-select select2-filter">
                    <option value="">Tous</option>
                    @foreach($discountGroups as $group)
                        <option value="{{ $group->id }}" {{ (string) request('discount_group_id') === (string) $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="af-fl" style="flex:0 1 120px;min-width:110px"><span class="af-fn">Prix net</span>
                <select name="prix_net" class="form-select select2-filter">
                    <option value="">Tous</option>
                    <option value="1" {{ request('prix_net') === '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ request('prix_net') === '0' ? 'selected' : '' }}>Non</option>
                </select>
            </label>
            <label class="af-fl" style="flex:0 1 130px;min-width:120px"><span class="af-fn">Statut</span>
                <select name="is_active" class="form-select select2-filter">
                    <option value="">Tous</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Autorisé</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Bloqué</option>
                </select>
            </label>
            <label class="af-fl" style="flex:0 1 150px;min-width:130px"><span class="af-fn">Stock</span>
                <select name="stock" class="form-select select2-filter">
                    <option value="">Tous</option>
                    <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>En stock</option>
                    <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Rupture</option>
                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Sous le minimum</option>
                </select>
            </label>
            <label class="af-fl" style="flex:0 1 150px;min-width:130px" title="Basé sur les bons de livraison. Sans dates : jamais vendu. Avec dates : aucune vente sur la période."><span class="af-fn">Rotation</span>
                <select name="rotation" class="form-select select2-filter">
                    <option value="">Tous</option>
                    <option value="sold" {{ request('rotation') === 'sold' ? 'selected' : '' }}>Vendus</option>
                    <option value="never" {{ request('rotation') === 'never' ? 'selected' : '' }}>Non vendus</option>
                </select>
            </label>
            <div class="af-fl af-period"><span class="af-fn">Période de ventes <em style="text-transform:none;font-weight:500">(optionnel)</em></span>
                <div class="af-pr">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="Du">
                    <span style="color:#94a3b8">→</span>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="Au">
                </div>
                <div class="af-chips">
                    <button type="button" class="af-chip" data-days="30">30 j</button>
                    <button type="button" class="af-chip" data-days="90">3 mois</button>
                    <button type="button" class="af-chip" data-days="180">6 mois</button>
                    <button type="button" class="af-chip" data-days="365">1 an</button>
                    <button type="button" class="af-chip" data-days="0">Effacer</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
(function () {
    var btn = document.getElementById('afAdvBtn'), box = document.getElementById('afAdv');
    if (!btn || !box) return;
    btn.addEventListener('click', function () { box.classList.toggle('show'); });
    function iso(d) { var m = d.getMonth() + 1, j = d.getDate(); return d.getFullYear() + '-' + (m < 10 ? '0' : '') + m + '-' + (j < 10 ? '0' : '') + j; }
    box.querySelectorAll('.af-chip').forEach(function (c) {
        c.addEventListener('click', function () {
            var f = box.querySelector('[name=date_from]'), t = box.querySelector('[name=date_to]'), n = parseInt(c.getAttribute('data-days'), 10);
            if (!n) { f.value = ''; t.value = ''; return; }
            var e = new Date(), s = new Date(); s.setDate(e.getDate() - n);
            f.value = iso(s); t.value = iso(e);
            var r = box.querySelector('[name=rotation]');
            if (r && !r.value) { r.value = 'sold'; if (window.jQuery) jQuery(r).trigger('change.select2'); }
        });
    });
})();
</script>







<!-- essai groupe remise -->

<!-- Application massive de groupe de remise (repliable) -->
<div class="collapse" id="massDiscountPanel">
    <div class="card border-warning mb-3" style="border-radius:12px;">
        <div class="card-body py-3 px-3 d-flex align-items-end gap-3 flex-wrap">
            <div style="flex:1 1 320px;max-width:520px;">
                <label class="form-label fw-bold small mb-1">Groupe de remise à appliquer à la liste filtrée</label>
                <select name="discount_group_id_mass" id="discount_group_id_mass" class="form-select" style="width:100%;">
                    <option value="">-- Choisir un groupe --</option>
                    @foreach($discountGroups as $group)
                        <option value="{{ $group->id }}">
                            {{ $group->name }}
                            (Particulier: {{ $group->discount_rate }}% | Jobbeur: {{ $group->discount_rate_jobber }}% | Pro: {{ $group->discount_rate_professionnel }}%)
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="button" class="btn btn-warning" id="applyMassDiscountBtn"
                    style="height:38px;font-weight:600;border-radius:8px;"
                    data-bs-toggle="modal" data-bs-target="#confirmMassDiscountModal"
                    disabled>
                <i class="fas fa-percent me-1"></i> Appliquer à la liste filtrée
            </button>

            <button type="button" class="btn btn-light border" style="height:38px;border-radius:8px;"
                    data-bs-toggle="collapse" data-bs-target="#massDiscountPanel">
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmMassDiscountModal" tabindex="-1" aria-labelledby="confirmMassDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmMassDiscountModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Vous allez appliquer le groupe de remise sélectionné à
                <strong id="countItemsToUpdate">0</strong> article(s).<br><br>
                Cette action est <strong>irréversible</strong>.<br>
                Continuer ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" id="confirmMassApplyBtn">Oui, appliquer</button>
            </div>
        </div>
    </div>
</div>




















<!-- Modal Création Groupe Remise -->
<div class="modal fade" id="createDiscountGroupModal" tabindex="-1" aria-labelledby="createDiscountGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="createDiscountGroupModalLabel">
                    Créer un nouveau groupe de remise
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('groupremise.store') }}" method="POST" id="createDiscountGroupForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du groupe</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               placeholder="Exemple: filtres Valeo" 
                               required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="rate" class="form-label">Particulier (%)</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   class="form-control" 
                                   name="rate" 
                                   value="0" 
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label for="rate_jobber" class="form-label">Jobber (%)</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   class="form-control" 
                                   name="rate_jobber" 
                                   value="0" 
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label for="rate_professionnel" class="form-label">Professionnel (%)</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   class="form-control" 
                                   name="rate_professionnel" 
                                   value="0" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Créer le groupe</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin essai groupe remise -->




<!-- fin recherche -->


<style>
    .search-box {
        max-width: 400px;
        height: 35px; /* Hauteur réduite */
        padding: 5px 12px;
        border: 2px solid #007bff; /* Bordure bleue */
        border-radius: 20px; /* Coins arrondis */
        font-size: 14px;
        transition: 0.3s ease-in-out;
        box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1);
    }

    .search-box:focus {
        border-color: #0056b3;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.5);
    }



    /* taille tableau */
    /* Réduction uniquement de la taille du texte pour ce tableau */
.table-text-small th,
.table-text-small td,
.table-text-small input,
.table-text-small button,
.table-text-small span,
.table-text-small svg {
    font-size: 11px !important;
}
/* Réduction supplémentaire pour les en-têtes de colonne */
.table-text-small th {
    font-size: 10px !important;
}


.badge-very-sm {
    font-size: 0.5rem;
    padding: 0.15em 0.3em;
    vertical-align: middle;
}


/* fin taille tableau */
</style>

<!-- fin recherche dans le tableau -->


  @if ($items->count() > 0)
    <div class="table-responsive">
        @php $lockedCodes = \App\Models\Item::lockedCodes($items->pluck('code')->all()); @endphp
        <table class="table table-bordered table-hover table-text-small" id="itemsTable">
            <thead class="table-dark">
                <tr>
                    <th>Réference</th>
                    <th>Désignation</th>
                    <th>Marque</th>
                    <th>Famille / Sous-famille</th>
                    <th>Groupe Rem%</th>
                    <!-- <th>Prix A.HT</th> -->
                     <th>Fourn./Prix A.HT</th>
                     <th>Prix.V/Marge</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
<td>
    {{ $item->code }}<br>
    @if ($item->is_active)
        <span class="badge bg-success badge-very-sm">🟢 actif</span>
    @else
        <span class="badge bg-danger badge-very-sm">🔴 bloqué</span>
    @endif
    @if ($item->prix_net)
        <span class="badge bg-warning text-dark badge-very-sm" title="Prix net : non modifié par les imports Excel / GOLDA">prix net</span>
    @endif
</td>


                        <td>{{ $item->name }}</td>
                        <td>{{ $item->brand->name ?? '-' }}</td>
                        <td>{{ $item->category->name ?? '-' }}
                            @if($item->subcategory)<br><small class="text-muted">{{ $item->subcategory->code }} · {{ $item->subcategory->name }}</small>@endif</td>
                        
                        <td>
    {{ $item->discountGroup->name ?? 'Standard' }}<br>
    <small class="text-muted">
        Particulier: {{ $item->discountGroup->discount_rate ?? 0 }}%
        | Jobbeur: {{ $item->discountGroup->discount_rate_jobber ?? 0 }}%
        | Pro: {{ $item->discountGroup->discount_rate_professionnel ?? 0 }}%
    </small>
</td>

                        <!-- <td>{{ number_format($item->cost_price, 2, ',', ' ') }} €</td> -->



              
<td class="multi-supplier-cell">
    @if($item->codefournisseur)
        <span class="sup">1</span>: {{ $item->supplier->name  }}<br>
        <span class="price">{{ number_format($item->cost_price, 2, ',', ' ') }} €</span> 
        <span class="rem">(-{{ number_format($item->remise_achat ?? 0, 2) }}%)</span><br>
    @endif

    @if($item->codefournisseur_2)
        <span class="sup">2</span>: {{ $item->supplier2->name  }}<br>
        <span class="price">{{ number_format($item->cost_price_2, 2, ',', ' ') }} €</span> 
        <span class="rem">(-{{ number_format($item->remise_achat_2 ?? 0, 2) }}%)</span><br>
    @endif

    @if($item->codefournisseur_3)
        <span class="sup">3</span>: {{ $item->supplier3->name  }}<br>
        <span class="price">{{ number_format($item->cost_price_3, 2, ',', ' ') }} €</span> 
        <span class="rem">(-{{ number_format($item->remise_achat_3 ?? 0, 2) }}%)</span>
    @endif

    @if(!$item->codefournisseur && !$item->codefournisseur_2 && !$item->codefournisseur_3)
        <span class="text-muted">—</span>
    @endif
</td>

@php
    $marge = $item->sale_price - $item->cost_price;
    $marge_pct = $item->cost_price > 0 ? ($marge / $item->cost_price) * 100 : 0;
@endphp
<td>
    {{ number_format($item->sale_price, 2, ',', ' ') }} € HT <br>
    <small class="{{ $marge >= 0 ? 'text-success' : 'text-danger' }}">
        {{ $marge >= 0 ? '+' : '' }}{{ number_format($marge, 2, ',', ' ') }} €
        ({{ number_format($marge_pct, 0, ',', ' ') }}%)
    </small>
</td>

          

<td>
    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stockDetailsModal{{ $item->id }}" title="Voir les détails du stock">
        {{ number_format($item->stock_quantity, 0, ',', ' ') }}
    </button> <br><small class="text-muted" style="font-size: 9px;">📦 {{ $item->location ?? '—' }}</small>

   
    
    <!-- Modal Stock Details -->
<div class="modal fade" id="stockDetailsModal{{ $item->id }}" tabindex="-1" aria-labelledby="stockDetailsModalLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="stockDetailsModalLabel{{ $item->id }}">
          Détail du stock – {{ $item->code }} : {{ $item->name }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        {{-- Stock par magasin --}}
        <h6><i class="fas fa-warehouse me-1"></i> Quantité actuelle par magasin : 
                        <button type="button" class="btn btn-info btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#adjustStockModal{{ $item->id }}" title="Ajuster le stock">
                <i class="fas fa-cubes"></i> Ajuster Stock
                </button>
              </h6>
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr>
              <th>Magasin</th>
              <th>Quantité</th>
              <th>Dernière mise à jour</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($item->stocks as $stock)
              <tr>
                <td>{{ $stock->store->name ?? '-' }}</td>
                <td>{{ $stock->quantity }}</td>
                <td>{{ $stock->updated_at->format('d/m/Y H:i') }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">Aucun stock trouvé</td></tr>
            @endforelse
          </tbody>
        </table>

        {{-- Historique des mouvements --}}
        
        
        <hr>
<h6><i class="fas fa-exchange-alt me-1"></i> Mouvements de stock récents :</h6>
@php
    $tvaRate = is_numeric(optional($item->tvaGroup)->rate) ? (float) $item->tvaGroup->rate : 20.0;
@endphp
<table class="table table-bordered table-sm">
  <thead class="table-light">
    <tr>
      <th>Type</th>
      <th>QTE</th>
      <th>Magasin</th>
      <th>PU HT net<br><small class="fw-normal text-muted">après remise</small></th>
      <th>PU TTC net<br><small class="fw-normal text-muted">après remise</small></th>
      <th>Source</th>
      <th>Référence</th>
      <th>Note</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($item->stockMovements()->with('store')->latest()->limit(20)->get() as $movement)
      <tr>
        <td>
          <span class="badge bg-{{ $movement->quantity >= 0 ? 'success' : 'danger' }}">{{ ucfirst($movement->type) }}</span><br>
          {{ $movement->created_at->format('d/m/Y H:i') }}
        </td>
        <td>{{ $movement->quantity }}</td>
        <td>{{ $movement->store->name ?? '-' }}</td>
        <td>{{ is_numeric($movement->cost_price) && $movement->cost_price > 0 ? number_format($movement->cost_price, 2, ',', ' ') . ' €' : '-' }}</td>
        <td>
          @if(is_numeric($movement->cost_price) && $movement->cost_price > 0)
            <b>{{ number_format($movement->cost_price * (1 + $tvaRate / 100), 2, ',', ' ') }} €</b><br>
            <small class="text-muted">TVA {{ $tvaRate + 0 }} %</small>
          @else
            -
          @endif
        </td>
        <td>{{ $movement->supplier_name ?? '-' }}</td>
        <td>{{ $movement->reference ?? '-' }}</td>
        <td>{{ $movement->note ?? '-' }}</td>
      </tr>
    @empty
      <tr><td colspan="8" class="text-center text-muted">Aucun mouvement trouvé</td></tr>
    @endforelse
  </tbody>
</table>


      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>






</td>

                        
<td class="col-actions">
  <div class="act-btns">
    <!-- Consulter / Éditer -->
    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}" title="Consulter / Éditer">
        <i class="fas fa-edit"></i> Consulter / Éditer
    </button>

    <!-- Ajuster le stock -->
    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#adjustStockModal{{ $item->id }}" title="Ajuster le stock">
        <i class="fas fa-cubes"></i> Ajuster le stock
    </button>

    <!-- Supprimer (bloqué si l'article a un historique) -->
    @if(isset($lockedCodes[$item->code]))
        <span title="Suppression impossible : article avec stock, mouvements ou documents. Vous pouvez le bloquer (inactif) à la place.">
            <button type="button" class="btn btn-sm btn-secondary w-100" disabled style="pointer-events:none">
                <i class="fas fa-lock"></i> Supprimer
            </button>
        </span>
    @else
        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet article ?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger w-100" title="Supprimer"><i class="fas fa-trash-alt"></i> Supprimer</button>
        </form>
    @endif
  </div>
  {{-- Le modal adjustStockModal{{ $item->id }} existant se place ici, inchangé --}}

  <!-- Modal Ajuster Stock -->
<div class="modal fade" id="adjustStockModal{{ $item->id }}" tabindex="-1" aria-labelledby="adjustStockModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('stock.update') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="reference" value="{{ $item->code }}">


                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="adjustStockModalLabel{{ $item->id }}">
                        Ajuster le stock : <strong>{{ $item->code }}</strong>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="store_id" class="form-label">Magasin</label>
                        <select name="store_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                                        <div class="mb-3">
                        <label for="movement_type" class="form-label">Type de mouvement</label>
                        <select name="movement_type" class="form-select" required>
                            <option value="ajustement">Ajustement</option>
                            <option value="inventaire">Inventaire</option>
                            <option value="transfert">Transfert</option>

                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité</label>
                        <input type="number" name="quantity" class="form-control" required placeholder="Ex: 100">
                    </div>



                    <div class="mb-3">
                        <label for="reason" class="form-label">Motif (facultatif)</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="Raison de l'ajustement..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Valider l’ajustement</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


</td>

                    </tr>






                 <!-- Modal Modifier Article -->
<div class="modal fade af" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}"><span class="af-ico af-ico-e"><i class="fas fa-pen"></i></span> {{ $item->code }}
                    @if($item->prix_net)<span class="badge bg-warning text-dark ms-1" style="font-size:.62rem">prix net</span>@endif
                </h5>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="editBtn{{ $item->id }}"><i class="fas fa-edit"></i> Modifier</button>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
            </div>
            <form action="{{ route('items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="af-grid" id="editForm{{ $item->id }}">
<div class="af-card s-8"><div class="af-t"><i class="fas fa-id-card"></i> Identification</div><div class="af-g">
<div class="s-3"><label class="af-l">Code article</label><input type="text" name="code" class="form-control form-control-sm" value="{{ $item->code }}" required disabled></div>
<div class="s-9"><label class="af-l">Désignation</label><input type="text" name="name" class="form-control form-control-sm" value="{{ $item->name }}" required disabled></div>
<div class="s-6"><label class="af-l">Famille</label><select name="category_id" id="edit_cat_{{ $item->id }}" class="form-select form-select-sm" disabled><option value="">-- Choisir --</option>@foreach($categories as $category)<option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>@endforeach</select></div>
<div class="s-6"><label class="af-l">Sous-famille</label><select name="subcategory_id" class="form-select form-select-sm" data-subcat-select data-cat-source="#edit_cat_{{ $item->id }}" disabled><option value="">-- Aucune --</option>@foreach($subcategories as $sub)<option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" {{ $item->subcategory_id == $sub->id ? 'selected' : '' }}>{{ $sub->code }} — {{ $sub->name }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Marque</label><select name="brand_id" class="form-select form-select-sm" disabled><option value="">-- Choisir --</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" {{ $item->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Unité</label><select name="unit_id" class="form-select form-select-sm" disabled><option value="">-- Choisir --</option>@foreach($units as $unit)<option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->label }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">TVA</label><select name="tva_group_id" class="form-select form-select-sm" disabled><option value="">-- Choisir --</option>@foreach($tvaGroups as $tva)<option value="{{ $tva->id }}" {{ $item->tva_group_id == $tva->id ? 'selected' : '' }}>{{ $tva->name }} ({{ $tva->rate }}%)</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Code-barres</label><input type="text" name="barcode" class="form-control form-control-sm" value="{{ $item->barcode }}" disabled></div>
<div class="s-4"><label class="af-l">Magasin</label><select name="store_id" class="form-select form-select-sm" disabled><option value="">-- Choisir --</option>@foreach($stores as $store)<option value="{{ $store->id }}" {{ $item->store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>@endforeach</select></div>
<div class="s-4"><label class="af-l">Emplacement</label><input type="text" name="location" class="form-control form-control-sm" value="{{ $item->location }}" placeholder="Ex : Rayon A2" disabled></div>
<div class="s-12"><label class="af-l">Description technique</label><textarea name="description" class="form-control form-control-sm" rows="2" disabled>{{ $item->description }}</textarea></div>
</div></div>
<div class="af-card s-4"><div class="af-t"><i class="fas fa-euro-sign"></i> Vente &amp; stock</div><div class="af-g">
<div class="s-6"><label class="af-l">Marge (%)</label><input type="number" step="0.01" id="edit-margin-{{ $item->id }}" class="form-control form-control-sm af-margin" placeholder="Auto" autocomplete="off" disabled></div>
<div class="s-6"><label class="af-l">Prix de vente HT</label><input type="number" name="sale_price" step="0.01" class="form-control form-control-sm" value="{{ $item->sale_price }}" disabled></div>
<div class="s-12"><label class="af-l">Groupe de remise</label><select name="discount_group_id" class="form-select form-select-sm" disabled><option value="">-- Aucun --</option>@foreach($discountGroups as $group)<option value="{{ $group->id }}" {{ $item->discount_group_id == $group->id ? 'selected' : '' }}>{{ $group->name }} (G:{{ $group->discount_rate }}% · J:{{ $group->discount_rate_jobber }}% · P:{{ $group->discount_rate_professionnel }}%)</option>@endforeach</select></div>
<div class="s-12"><div class="af-sw"><input type="hidden" name="prix_net" value="0" disabled><div class="form-check form-switch m-0"><input class="form-check-input" type="checkbox" role="switch" id="prix_net_{{ $item->id }}" name="prix_net" value="1" {{ $item->prix_net ? 'checked' : '' }} disabled><label class="form-check-label" for="prix_net_{{ $item->id }}"><b>Prix net</b> <span class="af-h">protégé des imports</span></label></div></div></div>
<div class="s-6"><label class="af-l">Stock min</label><input type="number" name="stock_min" class="form-control form-control-sm" value="{{ $item->stock_min }}" disabled></div>
<div class="s-6"><label class="af-l">Stock max</label><input type="number" name="stock_max" class="form-control form-control-sm" value="{{ $item->stock_max }}" disabled></div>
<div class="s-12"><div class="af-sw"><input type="hidden" name="is_active" value="0" disabled><div class="form-check form-switch m-0"><input class="form-check-input" type="checkbox" role="switch" id="blockedSwitch{{ $item->id }}" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} onchange="toggleBlockedLabel({{ $item->id }})" disabled><label class="form-check-label" id="blockedLabel{{ $item->id }}" for="blockedSwitch{{ $item->id }}" title="Le blocage concerne uniquement l'expédition des ventes. La facturation reste possible. Utile aussi pendant l'inventaire."><b>{{ $item->is_active ? 'Vente autorisée' : 'Vente bloquée' }}</b></label></div></div></div>
</div></div>
<div class="af-card s-12"><div class="af-t"><i class="fas fa-truck-loading"></i> Fournisseurs &amp; prix d'achat</div>
<div class="af-sup af-sup-h"><span>Fournisseur</span><span>Prix achat</span><span>Remise (%)</span></div>
<div class="af-sup"><div class="af-supsel"><span class="af-rk">Principal</span><select name="codefournisseur" class="form-select form-select-sm" disabled><option value="">-- Aucun --</option>@foreach($suppliersAll as $s)<option value="{{ $s->code }}" {{ $item->codefournisseur == $s->code ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>@endforeach</select></div><input type="number" step="0.0001" name="cost_price" class="form-control form-control-sm" value="{{ $item->cost_price ?? 0 }}" title="Prix achat" disabled><input type="number" step="0.01" name="remise_achat" class="form-control form-control-sm" value="{{ $item->remise_achat ?? 0 }}" title="Remise achat %" disabled></div>
<div class="af-sup"><div class="af-supsel"><span class="af-rk">Fournisseur 2</span><select name="codefournisseur_2" class="form-select form-select-sm" disabled><option value="">-- Aucun --</option>@foreach($suppliersAll as $s)<option value="{{ $s->code }}" {{ $item->codefournisseur_2 == $s->code ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>@endforeach</select></div><input type="number" step="0.0001" name="cost_price_2" class="form-control form-control-sm" value="{{ $item->cost_price_2 ?? 0 }}" title="Prix achat" disabled><input type="number" step="0.01" name="remise_achat_2" class="form-control form-control-sm" value="{{ $item->remise_achat_2 ?? 0 }}" title="Remise achat %" disabled></div>
<div class="af-sup"><div class="af-supsel"><span class="af-rk">Fournisseur 3</span><select name="codefournisseur_3" class="form-select form-select-sm" disabled><option value="">-- Aucun --</option>@foreach($suppliersAll as $s)<option value="{{ $s->code }}" {{ $item->codefournisseur_3 == $s->code ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>@endforeach</select></div><input type="number" step="0.0001" name="cost_price_3" class="form-control form-control-sm" value="{{ $item->cost_price_3 ?? 0 }}" title="Prix achat" disabled><input type="number" step="0.01" name="remise_achat_3" class="form-control form-control-sm" value="{{ $item->remise_achat_3 ?? 0 }}" title="Remise achat %" disabled></div>
</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success btn-sm px-4"><i class="fas fa-check me-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour activer les champs -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editForm{{ $item->id }}");
    const button = document.getElementById("editBtn{{ $item->id }}");
    if (!form || !button) return;
    let isEditable = false;
    const fields = Array.prototype.slice.call(form.querySelectorAll("input, select, textarea"));

    // Valeurs initiales (par élément, pas par nom : le champ caché et la case partagent le même name)
    const original = fields.map(function (el) { return { v: el.value, c: el.checked }; });

    button.addEventListener("click", function () {
        if (!isEditable) {
            fields.forEach(function (el) { el.removeAttribute("disabled"); });
            button.innerHTML = '<i class="fas fa-times-circle"></i> Annuler la modification';
            button.classList.remove("btn-outline-primary");
            button.classList.add("btn-outline-danger");
            isEditable = true;
        } else {
            fields.forEach(function (el, k) {
                el.setAttribute("disabled", true);
                el.value = original[k].v;
                if (el.type === "checkbox") el.checked = original[k].c;
                if (window.jQuery && jQuery(el).data("select2")) jQuery(el).trigger("change.select2");
            });
            var sw = document.getElementById("blockedSwitch{{ $item->id }}");
            if (sw) toggleBlockedLabel({{ $item->id }});
            button.innerHTML = '<i class="fas fa-edit"></i> Modifier';
            button.classList.remove("btn-outline-danger");
            button.classList.add("btn-outline-primary");
            isEditable = false;
        }
    });
});
function toggleBlockedLabel(itemid) {
    var cb = document.getElementById('blockedSwitch' + itemid);
    var lb = document.getElementById('blockedLabel' + itemid);
    if (cb && lb) lb.innerHTML = '<b>' + (cb.checked ? 'Vente autorisée' : 'Vente bloquée') + '</b>';
}
</script>


<!-- Style optionnel pour champs désactivés -->
<style>
    .form-control[disabled], .form-select[disabled], textarea[disabled] {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>












                @endforeach
            </tbody>
        </table>


    </div>
@else
    <p class="text-center text-muted">Aucun article trouvé.</p>
@endif

</div>

         



           
           
          </div>
          
<div class="d-flex justify-content-center mt-3">
    {{ $items->links() }}
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
var _si = document.getElementById("searchItemInput");
if (_si) _si.addEventListener("keyup", function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll("#itemsTable tbody tr");

    rows.forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
    });
});
</script>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        const costInput = document.querySelector('input[name="cost_price"]');
        const saleInput = document.querySelector('input[name="sale_price"]');
        const marginInput = document.getElementById('margin');

        function updateSalePriceFromMargin() {
            const cost = parseFloat(costInput.value);
            const margin = parseFloat(marginInput.value);

            if (!isNaN(cost) && !isNaN(margin)) {
                const salePrice = cost + (cost * margin / 100);
                saleInput.value = salePrice.toFixed(2);
            }
        }

        function updateMarginFromSalePrice() {
            const cost = parseFloat(costInput.value);
            const sale = parseFloat(saleInput.value);

            if (!isNaN(cost) && !isNaN(sale) && cost !== 0) {
                const margin = ((sale - cost) / cost) * 100;
                marginInput.value = margin.toFixed(2);
            }
        }

        marginInput.addEventListener('input', updateSalePriceFromMargin);
        costInput.addEventListener('input', () => {
            updateSalePriceFromMargin();
            updateMarginFromSalePrice();
        });
        saleInput.addEventListener('input', updateMarginFromSalePrice);
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const codeInput = document.getElementById('code');
        const barcodeInput = document.getElementById('barcode');
        let barcodeManuallyEdited = false;

        // Si l'utilisateur modifie manuellement le code-barres
        barcodeInput.addEventListener('input', function () {
            barcodeManuallyEdited = true;
        });

        // Copier automatiquement le code dans le code-barres si pas modifié
        codeInput.addEventListener('input', function () {
            if (!barcodeManuallyEdited) {
                barcodeInput.value = codeInput.value;
            }
        });
    });
</script>




<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[id^="editForm"]').forEach(function (form) {
        const itemId = form.id.replace('editForm', '');
        const costInput = document.querySelector(`#editForm${itemId} input[name="cost_price"]`);
        const saleInput = document.querySelector(`#editForm${itemId} input[name="sale_price"]`);
        const marginInput = document.getElementById(`edit-margin-${itemId}`);
        const toggleBtn = document.getElementById(`editBtn${itemId}`);

        if (!costInput || !saleInput || !marginInput || !toggleBtn) return;

        let marginManuallyEdited = false;
        let initialMargin = null;

        function calculateMargin(cost, sale) {
            if (!isNaN(cost) && !isNaN(sale) && cost !== 0) {
                return ((sale - cost) / cost) * 100;
            }
            return null;
        }

        function calculateSale(cost, margin) {
            if (!isNaN(cost) && !isNaN(margin)) {
                return cost + (cost * margin / 100);
            }
            return null;
        }

        function initMargin() {
            const cost = parseFloat(costInput.value);
            const sale = parseFloat(saleInput.value);
            const margin = calculateMargin(cost, sale);
            if (margin !== null) {
                marginInput.value = margin.toFixed(2);
                initialMargin = marginInput.value;
            }
        }

        marginInput.addEventListener("input", function () {
            marginManuallyEdited = true;
            const cost = parseFloat(costInput.value);
            const margin = parseFloat(marginInput.value);
            const newSale = calculateSale(cost, margin);
            if (newSale !== null) {
                saleInput.value = newSale.toFixed(2);
            }
        });

        saleInput.addEventListener("input", function () {
            if (!marginManuallyEdited) {
                const cost = parseFloat(costInput.value);
                const sale = parseFloat(saleInput.value);
                const newMargin = calculateMargin(cost, sale);
                if (newMargin !== null) {
                    marginInput.value = newMargin.toFixed(2);
                }
            }
        });

        toggleBtn.addEventListener("click", function () {
            setTimeout(() => {
                if (costInput.disabled && initialMargin !== null) {
                    marginInput.value = initialMargin;
                    marginManuallyEdited = false;
                }
            }, 100);
        });

        initMargin();
    });
});
</script>



<script>
$(document).ready(function() {
    // 1. Initialisation Select2 pour tous les filtres
    $('.select2-filter').select2({
        placeholder: function() {
            return $(this).find('option:first-child').text();
        },
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0,
        dropdownAutoWidth: true,
        language: {
            noResults: function() {
                return "Aucun résultat trouvé";
            }
        }
    });

    // 2. Select2 pour le groupe remise massive
    const selectGroup = $('#discount_group_id_mass').select2({
        placeholder: "-- Choisir un groupe --",
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0
    });

    const btnApply = $('#applyMassDiscountBtn');
    const confirmBtn = $('#confirmMassApplyBtn');
    const countSpan = $('#countItemsToUpdate');

    // 3. Écouter les ÉVÉNEMENTS SELECT2 (pas change natif !)
    selectGroup.on('select2:select select2:unselect change', function(e) {
        const hasValue = $(this).val() && $(this).val().trim() !== '';
        btnApply.prop('disabled', !hasValue);
    });

    // 4. Déclenchement initial (si valeur pré-sélectionnée)
    selectGroup.trigger('change');

    // 5. Compteur d'articles (approximation)
    function updateCountDisplay() {
        const visibleRows = $('#itemsTable tbody tr:not([style*="display: none"])').length;
        countSpan.text(visibleRows);
    }
    updateCountDisplay();

    // 6. Mise à jour du compteur sur pagination
    $('.pagination a').on('click', function() {
        setTimeout(updateCountDisplay, 800);
    });

    // 7. Confirmation et envoi AJAX
    confirmBtn.on('click', function() {
        const groupId = selectGroup.val();
        if (!groupId) return;

        const form = $('form[action="{{ route("articles.index") }}"]');
        const formData = new FormData(form[0]);
        
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        params.append('mass_discount_group_id', groupId);
        params.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("articles.mass-update-discount") }}', {
            method: 'POST',
            body: params,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Mise à jour réussie ! ' + data.updated + ' article(s) modifié(s).');
                location.reload();
            } else {
                alert('❌ Erreur : ' + (data.message || 'Action impossible'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('❌ Erreur technique lors de la mise à jour massive.');
        });

        // Ferme la modal
        $('#confirmMassDiscountModal').modal('hide');
    });
});
</script>

  
<script>
/* Sous-familles : liste filtrée selon la famille choisie + Select2 (création, modification, filtre) */
(function () {
    var SUBS = @json($subcategories);

    function catSelectOf(sel) {
        var src = sel.getAttribute('data-cat-source') || '';
        if (src.indexOf('closest-form:') === 0) {
            var f = sel.closest('form');
            return f ? f.querySelector(src.substring(13)) : null;
        }
        return document.querySelector(src);
    }

    function esc(t) { return String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;'); }

    function rebuild(sel) {
        var cat = catSelectOf(sel);
        var catVal = cat ? cat.value : '';
        var wanted = sel.value;
        var first = sel.options.length ? sel.options[0].outerHTML : '<option value="">-- Aucune --</option>';
        var html = first;
        SUBS.forEach(function (s) {
            if (catVal && String(s.category_id) !== String(catVal)) return;
            html += '<option value="' + s.id + '" data-category="' + esc(s.category_id) + '">' + esc(s.code + ' — ' + s.name) + '</option>';
        });
        sel.innerHTML = html;
        sel.value = wanted;
        if (sel.value !== wanted) sel.value = '';
        jQuery(sel).trigger('change.select2');
    }

    function initSelect2(el, parent) {
        var $el = jQuery(el);
        if (!el || $el.data('select2')) return;
        var opts = { width: '100%', language: { noResults: function () { return 'Aucun résultat trouvé'; } } };
        if (parent && parent.length) opts.dropdownParent = parent;
        $el.select2(opts);
    }

    function start() {
        try {
            // filtre initial de toutes les listes de sous-familles
            document.querySelectorAll('[data-subcat-select]').forEach(rebuild);

            // à chaque changement de famille, on reconstruit les sous-familles liées (délégation : marche aussi pour Select2)
            jQuery(document).on('change', 'select', function () {
                var changed = this;
                document.querySelectorAll('[data-subcat-select]').forEach(function (sel) {
                    if (sel !== changed && catSelectOf(sel) === changed) rebuild(sel);
                });
            });

            // Select2 dans une modale Bootstrap : la modale (tabindex=-1) reprend le focus au clic
            // et referme la liste aussitôt. On redonne le focus au champ de recherche à l'ouverture.
            jQuery(document).on('select2:open', function () {
                setTimeout(function () {
                    var f = document.querySelector('.select2-container--open .select2-search__field');
                    if (f) f.focus();
                }, 0);
            });

            // sécurité : on re-filtre à l'ouverture d'une modale et quand on active la modification
            document.addEventListener('shown.bs.modal', function (e) {
                e.target.querySelectorAll('[data-subcat-select]').forEach(rebuild);
            });
            document.addEventListener('click', function (e) {
                var b = e.target.closest ? e.target.closest('[id^="editBtn"]') : null;
                if (!b) return;
                setTimeout(function () {
                    var m = b.closest('.modal');
                    if (m) m.querySelectorAll('[data-subcat-select]').forEach(rebuild);
                }, 0);
            });

            var $create = jQuery('#createItemModal');
            var $createBox = $create.find('.modal-content').first();
            initSelect2(document.getElementById('category_id'), $createBox);
            $create.find('select[data-subcat-select], select[name^="codefournisseur"]').each(function () { initSelect2(this, $createBox); });

            jQuery('[id^="editItemModal"]').each(function () {
                var $m = jQuery(this).find('.modal-content').first();
                $m.find('select[name="category_id"], select[data-subcat-select], select[name^="codefournisseur"]').each(function () { initSelect2(this, $m); });
            });
        } catch (e) { console.error('Sous-familles :', e); }
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
</script>
</body>
</html>