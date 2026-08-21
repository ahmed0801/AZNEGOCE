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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


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

/* ── AZ Modal Design (création + édition) ─────────────────── */
:root {
    --c-navy:   #1E2D4A;
    --c-blue:   #3B82F6;
    --c-mint:   #10B981;
    --c-amber:  #F59E0B;
    --c-red:    #EF4444;
    --c-bg:     #F0F4FF;
    --c-white:  #FFFFFF;
    --c-text:   #1A2B4A;
    --c-sub:    #6B7A99;
    --c-border: #E2E8F0;
    --shadow:   0 8px 32px rgba(30,45,74,0.12);
}
.az-modal .modal-content {
    border:none; border-radius:20px;
    overflow:hidden; box-shadow:var(--shadow);
}
.az-modal .modal-header {
    background:linear-gradient(135deg, var(--c-navy) 0%, #2D4A8A 100%);
    padding:20px 28px 16px; border:none; position:relative;
}
.az-modal .modal-header::after {
    content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
    background:linear-gradient(90deg, var(--c-blue), var(--c-mint), var(--c-amber));
}
.az-modal .modal-title { color:white; font-size:1.05rem; font-weight:700; letter-spacing:0.02em; }
.az-modal .modal-subtitle { color:rgba(255,255,255,0.55); font-size:0.75rem; margin-top:2px; }
.az-modal .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
.az-stepper {
    display:flex; align-items:center;
    padding:18px 28px 0; background:var(--c-bg); gap:0;
}
.az-step { display:flex; align-items:center; flex:1; position:relative; }
.az-step-circle {
    width:32px; height:32px; border-radius:50%;
    background:white; border:2px solid var(--c-border);
    color:var(--c-sub); font-size:0.78rem; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    transition:all 0.3s ease; flex-shrink:0; z-index:1;
}
.az-step.active .az-step-circle {
    background:var(--c-blue); border-color:var(--c-blue); color:white;
    box-shadow:0 0 0 4px rgba(59,130,246,0.2);
}
.az-step.done .az-step-circle { background:var(--c-mint); border-color:var(--c-mint); color:white; }
.az-step-label { font-size:0.72rem; font-weight:600; color:var(--c-sub); margin-left:8px; white-space:nowrap; transition:color 0.3s; }
.az-step.active .az-step-label { color:var(--c-blue); }
.az-step.done .az-step-label   { color:var(--c-mint); }
.az-step-line { flex:1; height:2px; background:var(--c-border); margin:0 10px; transition:background 0.4s; }
.az-step-line.done { background:var(--c-mint); }
.az-step-panel { display:none; animation:fadeSlide 0.3s ease; }
.az-step-panel.active { display:block; }
@keyframes fadeSlide {
    from { opacity:0; transform:translateX(12px); }
    to   { opacity:1; transform:translateX(0); }
}
.az-modal .modal-body { background:var(--c-bg); padding:20px 28px 8px; }
.az-field-group { display:grid; gap:14px; }
.az-field { display:flex; flex-direction:column; gap:5px; }
.az-label { font-size:0.73rem; font-weight:700; color:var(--c-sub); text-transform:uppercase; letter-spacing:0.06em; }
.az-label .required { color:var(--c-red); margin-left:2px; }
.az-input {
    border:1.5px solid var(--c-border); border-radius:8px;
    padding:9px 13px; font-size:0.875rem; color:var(--c-text);
    background:white; transition:border-color 0.2s, box-shadow 0.2s;
    outline:none; width:100%;
}
.az-input:focus { border-color:var(--c-blue); box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
.az-input.is-invalid { border-color:var(--c-red); }
.az-input:disabled { background:#f1f5f9; color:#64748b; cursor:not-allowed; }
.az-select {
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7A99' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 12px center; padding-right:32px;
}
.az-section-title {
    font-size:0.8rem; font-weight:700; color:var(--c-navy);
    letter-spacing:0.08em; text-transform:uppercase;
    display:flex; align-items:center; gap:8px;
    margin-bottom:12px; padding-bottom:8px; border-bottom:1.5px solid var(--c-border);
}
.az-section-title i { color:var(--c-blue); font-size:0.9rem; }
.az-modal .modal-footer {
    background:white; border-top:1.5px solid var(--c-border);
    padding:14px 28px; display:flex; justify-content:space-between; align-items:center;
}
.az-btn-prev {
    background:none; border:1.5px solid var(--c-border); color:var(--c-sub);
    border-radius:8px; padding:8px 18px; font-size:0.82rem; font-weight:600;
    cursor:pointer; transition:all 0.2s;
}
.az-btn-prev:hover { border-color:var(--c-blue); color:var(--c-blue); }
.az-btn-next {
    background:var(--c-blue); border:none; color:white; border-radius:8px;
    padding:8px 22px; font-size:0.82rem; font-weight:700; cursor:pointer;
    transition:all 0.2s; display:flex; align-items:center; gap:6px;
}
.az-btn-next:hover { background:#2563EB; transform:translateY(-1px); }
.az-btn-submit {
    background:linear-gradient(135deg, var(--c-mint), #059669);
    border:none; color:white; border-radius:8px; padding:8px 22px;
    font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.2s;
    display:none; align-items:center; gap:6px;
}
.az-btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,0.3); }
.az-step-counter { font-size:0.75rem; color:var(--c-sub); }
/* Edit */
.az-edit-header {
    background:linear-gradient(135deg, #1a2b4a 0%, #2d4a8a 100%);
    padding:16px 24px; display:flex; align-items:center; justify-content:space-between;
    border-radius:20px 20px 0 0;
}
.az-edit-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
    border-radius:20px; padding:4px 12px; font-size:0.72rem; font-weight:700;
    color:white; letter-spacing:0.05em;
}
.az-edit-title { color:white; font-weight:700; font-size:1rem; margin:0; }
.az-edit-subtitle { color:rgba(255,255,255,0.6); font-size:0.78rem; margin:2px 0 0; }
.az-lock-btn {
    background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.3);
    color:white; border-radius:8px; padding:6px 14px; font-size:0.78rem; font-weight:700;
    cursor:pointer; transition:all 0.25s; display:flex; align-items:center; gap:6px;
}
.az-lock-btn:hover { background:rgba(255,255,255,0.25); }
.az-lock-btn.editing { background:rgba(239,68,68,0.2); border-color:rgba(239,68,68,0.4); color:#FCA5A5; }
.az-tabs {
    display:flex; gap:2px; background:var(--c-bg);
    padding:8px 24px 0; border-bottom:2px solid var(--c-border);
}
.az-tab {
    padding:8px 16px; font-size:0.78rem; font-weight:600; color:var(--c-sub);
    cursor:pointer; border-radius:8px 8px 0 0; border:none; background:none;
    border-bottom:2px solid transparent; margin-bottom:-2px; transition:all 0.2s;
}
.az-tab.active { color:var(--c-blue); background:white; border-bottom-color:var(--c-blue); }
.az-tab-panel { display:none; padding:20px 24px; background:var(--c-bg); }
.az-tab-panel.active { display:block; animation:fadeSlide 0.25s ease; }
.az-solde-card {
    background:linear-gradient(135deg, var(--c-navy), #2D4A8A);
    border-radius:12px; padding:16px 20px; color:white;
    display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;
}
.az-solde-amount { font-size:1.8rem; font-weight:800; }
.az-solde-label  { font-size:0.72rem; opacity:0.7; text-transform:uppercase; letter-spacing:0.08em; }
.az-status-pill {
    display:inline-flex; align-items:center; gap:6px; padding:6px 14px;
    border-radius:20px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s;
}
.az-status-pill.active  { background:#D1FAE5; color:#065F46; }
.az-status-pill.blocked { background:#FEE2E2; color:#991B1B; }
.az-save-btn {
    background:linear-gradient(135deg, var(--c-mint), #059669);
    border:none; color:white; border-radius:8px; padding:8px 20px;
    font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.2s;
    display:none; align-items:center; gap:6px;
}
.az-save-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,0.3); }

    </style>





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



        <div class="container mt-4">
        {{-- Affichage des messages d'erreur --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    
        <div class="container mt-4">

       <h4>Liste des Clients :

                  <button type="submit" class="btn btn-outline-success btn-round ms-2" data-bs-toggle="modal" data-bs-target="#createItemModal">Nouveau Client
           <i class="fas fa-plus-circle ms-2"></i>
          </button>


<button type="submit" class="btn btn-outline-secondary btn-round ms-2" data-bs-toggle="modal" data-bs-target="#allAccountingModal">
                                Ecritures Comptables Ventes <i class="fas fa-balance-scale me-1"></i>
                            </button>



                            <!-- Après la row des KPIs -->

    <a href="{{ route('customer.behavior') }}" class="btn btn-outline-danger btn-round ms-2">
        <i class="fas fa-chart-line fa-lg me-2"></i>
        Analyse Comportement Clients
    </a>





       </h4>





       

      





    <!-- Modal création — 3 étapes -->
    <div class="modal fade az-modal" id="createItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <div class="modal-title">Nouveau Client</div>
                        <div class="modal-subtitle">Identité → Coordonnées → Finances</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="az-stepper">
                    <div class="az-step active" data-step="1">
                        <div class="az-step-circle">1</div>
                        <div class="az-step-label">Identité</div>
                    </div>
                    <div class="az-step-line" id="line1"></div>
                    <div class="az-step" data-step="2">
                        <div class="az-step-circle">2</div>
                        <div class="az-step-label">Coordonnées</div>
                    </div>
                    <div class="az-step-line" id="line2"></div>
                    <div class="az-step" data-step="3">
                        <div class="az-step-circle">3</div>
                        <div class="az-step-label">Finances</div>
                    </div>
                </div>

                <form action="{{ route('customer.store') }}" method="POST" id="createCustomerForm">
                    @csrf
                    <div class="modal-body">

                        {{-- Étape 1 : Identité --}}
                        <div class="az-step-panel active" id="panel-1">
                            <div class="az-section-title"><i class="fas fa-user"></i> Identité</div>
                            <div class="az-field-group" style="grid-template-columns:2fr 1fr;">
                                <div class="az-field">
                                    <label class="az-label">Nom complet <span class="required">*</span></label>
                                    <input type="text" name="name" class="az-input" required placeholder="Ex: DUPONT Jean">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Type <span class="required">*</span></label>
                                    <select name="type" class="az-input az-select" required>
                                        <option value="particulier">Particulier</option>
                                        <option value="jobber">Jobber</option>
                                        <option value="professionnel">Professionnel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:2fr 1fr;margin-top:12px;">
                                <div class="az-field">
                                    <label class="az-label">SIRET / Matricule fiscal</label>
                                    <input type="text" name="matfiscal" class="az-input" placeholder="12345678901234">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Pays</label>
                                    <input type="text" name="country" class="az-input" value="France">
                                </div>
                            </div>
                        </div>

                        {{-- Étape 2 : Coordonnées --}}
                        <div class="az-step-panel" id="panel-2">
                            <div class="az-section-title"><i class="fas fa-map-marker-alt"></i> Adresse</div>
                            <div class="az-field-group" style="grid-template-columns:2fr 1fr 1fr;">
                                <div class="az-field">
                                    <label class="az-label">Adresse</label>
                                    <input type="text" name="address" class="az-input" placeholder="Rue, numéro...">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Code Postal</label>
                                    <input type="text" name="address_delivery" class="az-input" placeholder="75001">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Ville</label>
                                    <input type="text" name="city" class="az-input" placeholder="Paris">
                                </div>
                            </div>
                            <div class="az-section-title" style="margin-top:18px;"><i class="fas fa-address-card"></i> Contact</div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                <div class="az-field">
                                    <label class="az-label">Email</label>
                                    <input type="email" name="email" class="az-input" placeholder="contact@exemple.fr">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">IBAN</label>
                                    <input type="text" name="bank_no" class="az-input" placeholder="FR76...">
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:12px;">
                                <div class="az-field">
                                    <label class="az-label">Téléphone principal</label>
                                    <input type="text" name="phone1" class="az-input" placeholder="06 00 00 00 00">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Téléphone secondaire</label>
                                    <input type="text" name="phone2" class="az-input" placeholder="06 00 00 00 00">
                                </div>
                            </div>
                        </div>

                        {{-- Étape 3 : Finances --}}
                        <div class="az-step-panel" id="panel-3">
                            <div class="az-section-title"><i class="fas fa-euro-sign"></i> Paramètres financiers</div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                <div class="az-field">
                                    <label class="az-label">Condition de paiement <span class="required">*</span></label>
                                    <select name="payment_term_id" class="az-input az-select" required>
                                        @foreach($paymentTerms as $term)
                                            <option value="{{ $term->id }}">{{ $term->label }} : {{ $term->days }} Jours</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Mode de paiement <span class="required">*</span></label>
                                    <select name="payment_mode_id" class="az-input az-select" required>
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:12px;">
                                <div class="az-field">
                                    <label class="az-label">TVA <span class="required">*</span></label>
                                    <select name="tva_group_id" class="az-input az-select" required>
                                        @foreach($tvaGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->rate }}%)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Groupe remise <span class="required">*</span></label>
                                    <select name="discount_group_id" class="az-input az-select" required>
                                        @foreach($discountGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->discount_rate }}%)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Plafond (€)</label>
                                    <input type="number" step="0.01" name="plafond" class="az-input" value="0">
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:12px;">
                                <div class="az-field">
                                    <label class="az-label">Risque</label>
                                    <input type="number" name="risque" class="az-input" value="0">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Solde initial (€)</label>
                                    <input type="number" step="0.01" name="solde" class="az-input" value="0" readonly style="background:#f0f4ff;">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer" style="display:flex;justify-content:space-between;align-items:center;">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="az-btn-prev" id="btnPrev" style="display:none;" onclick="stepNav(-1)">
                                <i class="fas fa-arrow-left"></i> Précédent
                            </button>
                            <span class="az-step-counter" id="stepCounter">Étape 1 sur 3</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="az-btn-next" id="btnNext" onclick="stepNav(1)">
                                Suivant <i class="fas fa-arrow-right"></i>
                            </button>
                            <button type="submit" name="action" value="create" class="az-btn-submit" id="btnSubmit" style="display:none;">
                                <i class="fas fa-check"></i> Créer
                            </button>
                            <button type="button" class="az-btn-submit" id="btnSubmitClose"
                                    style="display:none;background:linear-gradient(135deg,#059669,#047857);">
                                <i class="fas fa-check-double"></i> Créer et Fermer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- fin modal creation -->






    <!-- New Modal for All Accounting Entries -->
                        <div class="modal fade" id="allAccountingModal" tabindex="-1" aria-labelledby="allAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allAccountingModalLabel">Ecritures Comptables de Toutes les Ventes</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des Ventes</h6>
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Total Débits</th>
                                                            <th>Total Crédits</th>
                                                            <th>Solde Net</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td id="allDebits">0,00 €</td>
                                                            <td id="allCredits">0,00 €</td>
                                                            <td id="allBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="allAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <option value="Règlements">Règlements</option>
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <select name="customer_id" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Client (Tous)</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAllAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Type</th>
                                                        <th>Num Document / Lettrage</th>
                                                        <th>Date</th>
                                                        <th>Montant TTC</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="allAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>







    



   <!-- Filtres -->
<div class="mb-4">
    <form method="GET" action="{{ route('customer.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
        <!-- Recherche générale -->
        <input type="text" name="search" class="form-control form-control-sm" 
               style="width: 250px;" placeholder="🔍 Recherche (nom, code, téléphone, email...)" 
               value="{{ request('search') }}">
        
        <!-- Statut -->
        <select name="status" class="form-select form-select-sm" style="width: 120px;">
            <option value="">Statut (Tous)</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Actif</option>
            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>🔴 Bloqué</option>
        </select>
        
        <!-- Ville -->
        <select name="city" class="form-select form-select-sm" style="width: 160px;">
            <option value="">Ville (Toutes)</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                    {{ $city }}
                </option>
            @endforeach
        </select>
        
        <!-- Solde min -->
        <input type="number" step="0.01" name="min_solde" class="form-control form-control-sm" 
               style="width: 110px;" placeholder="Solde min" value="{{ request('min_solde') }}">
        <span class="mx-1 text-muted">à</span>
        
        <!-- Solde max -->
        <input type="number" step="0.01" name="max_solde" class="form-control form-control-sm" 
               style="width: 110px;" placeholder="Solde max" value="{{ request('max_solde') }}">
        
        <!-- Actions -->
        <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
            <i class="fas fa-filter me-1"></i> Filtrer
        </button>
        
        <button type="submit" name="action" value="export" 
                formaction="{{ route('customers.export') . '?' . http_build_query(request()->query()) }}" 
                class="btn btn-outline-success btn-sm px-3" target="_blank">
            <i class="fas fa-file-excel me-1"></i> EXCEL
        </button>
        
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-undo me-1"></i> Réinitialiser
        </a>
    </form>
</div>
<!-- Recherche rapide (garder l'ancienne) -->
<!-- <div class="mb-2 d-flex justify-content-center">
    <input type="text" id="searchItemInput" class="form-control search-box" placeholder="🔍 Rechercher un client...">
</div> -->







<!-- KPI Toggle -->
<div class="mb-3">
    <button type="button" id="toggleKpiBtn" onclick="toggleKpis()"
        style="background: linear-gradient(135deg, #0056b3, #17a2b8); border: none; border-radius: 10px; padding: 10px 22px; color: #fff; font-size: 0.95rem; font-weight: 600; box-shadow: 0 4px 12px rgba(0,86,179,0.25); transition: all 0.3s ease; cursor: pointer;">
        <i class="fas fa-chart-bar me-2"></i> Statistiques Clients
        <i class="fas fa-chevron-down ms-2" id="kpiChevron"></i>
    </button>
</div>

<div id="kpiSection" style="display:none;">
    <div class="row mb-4 g-3">
        <!-- 1. Clients & Véhicules -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <h6 class="display-5 fw-bold mb-0">TOTAL : {{ number_format($totalCustomers, 0, ',', ' ') }} CLIENTS</h6>
                    <i class="fas fa-car-side fa-3x mb-3 text-info"></i>
                    <h6 class="fw-bold mb-1">CLIENTS & VÉHICULES</h6>
                    <div class="d-flex justify-content-center gap-4 mb-3">
                        <div>
                            <h4 class="fw-bold text-info mb-0">{{ number_format($clientsAvecVehicules, 0, ',', ' ') }}</h4>
                            <small>Avec véhicule</small>
                        </div>
                        <div class="vr mx-2"></div>
                        <div>
                            <h4 class="fw-bold text-secondary mb-0">{{ number_format($clientsSansVehicules, 0, ',', ' ') }}</h4>
                            <small>Sans véhicule</small>
                        </div>
                    </div>
                    <small class="text-muted">
                        Taux équipés : <strong>{{ $totalCustomers > 0 ? round(($clientsAvecVehicules / $totalCustomers) * 100, 1) : 0 }}%</strong>
                    </small>
                </div>
            </div>
        </div>

        <!-- 2. Actifs / Inactifs -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <div class="d-flex justify-content-center gap-4 mb-3">
                        <div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                            <h6 class="small mb-1">Actifs</h6>
                            <h4 class="fw-bold text-success mb-0">{{ number_format($activeCustomers, 0, ',', ' ') }}</h4>
                        </div>
                        <div class="vr mx-2"></div>
                        <div>
                            <i class="fas fa-ban fa-2x text-danger"></i>
                            <h6 class="small mb-1">Inactifs</h6>
                            <h4 class="fw-bold text-danger mb-0">{{ number_format($inactiveCustomers, 0, ',', ' ') }}</h4>
                        </div>
                    </div>
                    <small class="text-muted">
                        Taux d'activité : <strong>{{ $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100, 1) : 0 }}%</strong>
                    </small>
                </div>
            </div>
        </div>

        <!-- 3. Répartition par type -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <i class="fas fa-layer-group fa-2x text-primary mb-3"></i>
                    <h6 class="fw-bold mb-2">RÉPARTITION PAR TYPE</h6>
                    <div class="progress mb-3" style="height: 12px; background: #e9ecef;">
                        <div class="progress-bar bg-primary" role="progressbar"
                             style="width: {{ $totalCustomers > 0 ? ($particuliersCount / $totalCustomers * 100) : 0 }}%"></div>
                        <div class="progress-bar bg-warning" role="progressbar"
                             style="width: {{ $totalCustomers > 0 ? ($jobbersCount / $totalCustomers * 100) : 0 }}%"></div>
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $totalCustomers > 0 ? ($prosCount / $totalCustomers * 100) : 0 }}%"></div>
                    </div>
                    <div class="small fw-bold text-center">
                        <span class="text-primary">{{ number_format($particuliersCount) }}</span> Part. •
                        <span class="text-warning">{{ number_format($jobbersCount) }}</span> Job. •
                        <span class="text-success">{{ number_format($prosCount) }}</span> Pros
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Clients non soldés -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                    <h6 class="fw-bold mb-1">CLIENTS NON SOLDÉS</h6>
                    <h2 class="display-5 fw-bold text-warning mb-2">{{ number_format($clientsNonSoldes, 0, ',', ' ') }}</h2>
                    <small class="d-block mb-2">
                        <span class="text-danger fw-bold">{{ number_format($clientsNousDoivent) }}</span> nous doivent •
                        <span class="text-success fw-bold">{{ number_format($clientsOnDoit) }}</span> on doit
                    </small>
                    <h6 class="text-muted mb-1">Solde global</h6>
                    <h4 class="{{ $totalSoldeClients >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                        {{ number_format($totalSoldeClients, 2, ',', ' ') }} €
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleKpis() {
    const section = document.getElementById('kpiSection');
    const chevron = document.getElementById('kpiChevron');
    const btn = document.getElementById('toggleKpiBtn');
    const isHidden = section.style.display === 'none';

    section.style.display = isHidden ? 'block' : 'none';
    chevron.className = isHidden ? 'fas fa-chevron-up ms-2' : 'fas fa-chevron-down ms-2';
    btn.innerHTML = `<i class="fas fa-chart-bar me-2"></i> ${isHidden ? 'Masquer' : 'Statistiques Clients'} <i class="${chevron.className}"></i>`;

    localStorage.setItem('kpiVisible', isHidden ? '1' : '0');
}

document.addEventListener('DOMContentLoaded', function () {
    // Par défaut toujours fermé, on ignore le localStorage
});
</script>










    @if ($customers->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-text-small" id="itemsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Adresse & Ville</th>
                        <th>Contact</th>
                        <th>Solde</th>
                        <!-- <th>Non.Fact</th> -->
                        <th>Plafond</th>
                        <th>Véhicules</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>🧑‍💼{{ $customer->code }}
                              <br>
                                  @if ($customer->blocked)
        <span class="badge bg-danger badge-very-sm">🔴 Bloqué</span>
    @else
        <span class="badge bg-success badge-very-sm">🟢 Actif</span>
    @endif
                            </td>
                            <td>{{ $customer->name }}<br>
                            
                                @switch($customer->type)
        @case('particulier')
            <span class="badge bg-primary">Particulier</span>
            @break
        @case('jobber')
            <span class="badge bg-warning text-dark">Jobber</span>
            @break
        @case('professionnel')
            <span class="badge bg-success">Professionnel</span>
            @break
    @endswitch

                            </td>
                            <td>{{ $customer->address }} <br>
                          🏴󠁢󠁹󠁭󠁩󠁿{{ $customer->city }}</td>
                            <td>📞 {{ $customer->phone1 }} <br>
                         📧 {{ $customer->email }} </td>



                             <td>
 <button type="button" class="btn btn-sm btn-outline-primary solde-btn" data-bs-toggle="modal" data-bs-target="#accountingModal{{ $customer->id }}" data-customer-id="{{ $customer->id }}">
                                                        {{ number_format($customer->solde, 2, ',', ' ') }} €
                                                    </button>

</td>
                            <td>
{{ $customer->plafond }} €
</td>

                         <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewVehiclesModal{{ $customer->id }}">
  <i class="fas fa-car"></i> <span class="badge bg-success">{{$customer->vehicles->count()}}</span>
</button>
                         </td>


                            <td>
                                <!-- Bouton Modifier -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $customer->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>











                                 

 <!-- Accounting Entries Modal -->
             <!-- Accounting Entries Modal -->
<div class="modal fade accounting-modal" id="accountingModal{{ $customer->id }}" tabindex="-1" aria-labelledby="accountingModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountingModalLabel{{ $customer->id }}">Ecritures comptables Client : {{ $customer->name }}</h5>
<button type="button" class="btn btn-secondary btn-round ms-2 dropdown-toggle" onclick="showBalance({{ $customer->id }})">
                    <i class="fas fa-balance-scale me-1"></i> Balance
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Balance Summary (Hidden by Default) -->
                <div id="balanceSummary{{ $customer->id }}" class="card mb-3" style="display: none;">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Balance Comptable</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Total Débits</th>
                                    <th>Total Crédits</th>
                                    <th>Solde Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="debits{{ $customer->id }}">0,00 €</td>
                                    <td id="credits{{ $customer->id }}">0,00 €</td>
                                    <td id="balance{{ $customer->id }}">0,00 €</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Filter Form -->
                <form id="accountingFilterForm{{ $customer->id }}" class="d-flex flex-wrap gap-2 mb-3">
                    <select name="type" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Type (Tous)</option>
                        <option value="Factures">Factures</option>
                        <option value="Avoirs">Avoirs</option>
                        <option value="Règlements">Règlements</option>
                    </select>
                    <input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début">
                    <input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin">
                    <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAccountingFilter({{ $customer->id }})">
                        <i class="fas fa-undo me-1"></i> Réinitialiser
                    </button>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover accounting-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Type</th>
                                <th>Num Document / Lettrage</th>
                                <th>Date</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody id="accountingEntries{{ $customer->id }}">
                            <tr>
                                <td colspan="5" class="text-center">Chargement...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>



  <!-- View Vehicles Modal -->
<div class="modal fade" id="viewVehiclesModal{{ $customer->id }}" tabindex="-1" aria-labelledby="viewVehiclesModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewVehiclesModalLabel{{ $customer->id }}">Véhicules associés à {{ $customer->name }}</h5>
                <button type="button" class="btn btn-outline-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#addVehicleModal{{ $customer->id }}">
                    <i class="fas fa-car"></i> Associer un véhicule
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                @if($customer->vehicles && $customer->vehicles->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-text-small">
                            <thead class="table-dark">
                                <tr>
                                    <th>Immatriculation</th>
                                    <th>Marque</th>
                                    <th>Modèle</th>
                                    <th>Motorisation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->vehicles as $vehicle)
                                    <tr>
                                        <td>{{ $vehicle->license_plate }}</td>
                                        <td>{{ $vehicle->brand_name }}</td>
                                        <td>{{ $vehicle->model_name }}</td>
                                        <td>{{ $vehicle->engine_description }}</td>
                                        <td>
                                            <a href="{{ route('customer.vehicle.catalog', [$customer->id, $vehicle->id]) }}" class="btn btn-outline-primary btn-sm px-2 py-1" style="font-size: 0.90rem;"  onclick="window.open(this.href, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); return false;">
                                                <i class="fas fa-list"></i> Charger le Catalogue
                                            </a>
                                            <form action="{{ route('customer.vehicle.destroy', [$customer->id, $vehicle->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce véhicule ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i>Supprimer le vehicule </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Aucun véhicule associé.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


      <!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal{{ $customer->id }}" tabindex="-1" aria-labelledby="addVehicleModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel{{ $customer->id }}">Associer un véhicule à {{ $customer->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('customer.vehicle.store', $customer->id) }}" id="vehicleForm{{ $customer->id }}">
                @csrf
                <div class="modal-body">

                 <div class="mb-3">
                        <label for="license_plate_{{ $customer->id }}" class="form-label">Immatriculation :</label>
                        <input type="text" id="license_plate_{{ $customer->id }}" name="license_plate" class="form-control" required>
                    </div>
                    <!-- Marque -->
<div class="mb-3">
    <label class="form-label">Marque :</label>
    <select id="brand_id_{{ $customer->id }}" name="brand_id" class="form-control select2-brand" style="width: 100%;" required>
        <option value="">Rechercher une marque...</option>
        @foreach($brands as $brand)
            <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
        @endforeach
    </select>
    <input type="hidden" name="brand_name" id="brand_name_{{ $customer->id }}">
</div>



<!-- Modèle -->
<div class="mb-3">
    <label class="form-label">Modèle :</label>
    <select id="model_id_{{ $customer->id }}" name="model_id" class="form-control select2-model" style="width: 100%;" required>
        <option value="">Rechercher un modèle...</option>
    </select>
    <input type="hidden" name="model_name" id="model_name_{{ $customer->id }}">
</div>

<!-- Motorisation -->
<div class="mb-3">
    <label class="form-label">Motorisation :</label>
    <select id="engine_id_{{ $customer->id }}" name="engine_id" class="form-control select2-engine" style="width: 100%;" required>
        <option value="">Rechercher une motorisation...</option>
    </select>
    <input type="hidden" name="engine_description" id="engine_description_{{ $customer->id }}">
    <input type="hidden" name="linkage_target_id" id="linkage_target_id_{{ $customer->id }}">
</div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Associer</button>
                </div>
            </form>
        </div>
    </div>
</div>














                                <!-- Formulaire suppression -->
                                <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce client ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Modifier — design moderne onglets -->
                        <div class="modal fade az-modal" id="editItemModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="az-edit-header">
                                        <div>
                                            <div class="az-edit-badge">{{ $customer->code }}</div>
                                            <h5 class="az-edit-title mt-1">{{ $customer->name }}</h5>
                                            <p class="az-edit-subtitle">{{ ucfirst($customer->type ?? 'particulier') }} · {{ $customer->city ?? '—' }}</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="az-lock-btn" id="lockBtn{{ $customer->id }}" onclick="toggleEdit({{ $customer->id }})">
                                                <i class="fas fa-lock"></i> Modifier
                                            </button>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                    </div>

                                    <form action="{{ route('customer.update', $customer->id) }}" method="POST" id="editForm{{ $customer->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="az-tabs">
                                            <button type="button" class="az-tab active" data-tab="identite-{{ $customer->id }}" onclick="switchTab(this, {{ $customer->id }})">Identité</button>
                                            <button type="button" class="az-tab" data-tab="contact-{{ $customer->id }}" onclick="switchTab(this, {{ $customer->id }})">Contact</button>
                                            <button type="button" class="az-tab" data-tab="finances-{{ $customer->id }}" onclick="switchTab(this, {{ $customer->id }})">Finances</button>
                                            <button type="button" class="az-tab" data-tab="statut-{{ $customer->id }}" onclick="switchTab(this, {{ $customer->id }})">Statut</button>
                                        </div>

                                        {{-- Tab Identité --}}
                                        <div class="az-tab-panel active" id="tab-identite-{{ $customer->id }}">
                                            <div class="az-section-title"><i class="fas fa-user"></i> Identité</div>
                                            <div class="az-field-group" style="grid-template-columns:2fr 1fr;">
                                                <div class="az-field">
                                                    <label class="az-label">Nom complet</label>
                                                    <input type="text" name="name" class="az-input ef-{{ $customer->id }}" value="{{ $customer->name }}" required disabled>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Type</label>
                                                    <select name="type" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                        <option value="particulier" {{ $customer->type == 'particulier' ? 'selected' : '' }}>Particulier</option>
                                                        <option value="jobber" {{ $customer->type == 'jobber' ? 'selected' : '' }}>Jobber</option>
                                                        <option value="professionnel" {{ $customer->type == 'professionnel' ? 'selected' : '' }}>Professionnel</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="az-field-group" style="grid-template-columns:2fr 1fr;margin-top:12px;">
                                                <div class="az-field">
                                                    <label class="az-label">SIRET</label>
                                                    <input type="text" name="matfiscal" class="az-input ef-{{ $customer->id }}" value="{{ $customer->matfiscal }}" disabled>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Pays</label>
                                                    <input type="text" name="country" class="az-input ef-{{ $customer->id }}" value="{{ $customer->country }}" disabled>
                                                </div>
                                            </div>
                                            <div class="az-field-group" style="grid-template-columns:2fr 1fr 1fr;margin-top:12px;">
                                                <div class="az-field">
                                                    <label class="az-label">Adresse</label>
                                                    <input type="text" name="address" class="az-input ef-{{ $customer->id }}" value="{{ $customer->address }}" disabled>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Code Postal</label>
                                                    <input type="text" name="address_delivery" class="az-input ef-{{ $customer->id }}" value="{{ $customer->address_delivery }}" disabled>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Ville</label>
                                                    <input type="text" name="city" class="az-input ef-{{ $customer->id }}" value="{{ $customer->city }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tab Contact --}}
                                        <div class="az-tab-panel" id="tab-contact-{{ $customer->id }}">
                                            <div class="az-section-title"><i class="fas fa-address-card"></i> Contact</div>
                                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                                <div class="az-field">
                                                    <label class="az-label">Email</label>
                                                    <input type="email" name="email" class="az-input ef-{{ $customer->id }}" value="{{ $customer->email }}" disabled>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">IBAN</label>
                                                    <input type="text" name="bank_no" class="az-input ef-{{ $customer->id }}" value="{{ $customer->bank_no }}" disabled>
                                                </div>
                                            </div>
                                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:12px;">
                                                <div class="az-field">
                                                    <label class="az-label">Téléphone principal</label>
                                                    <input type="text" name="phone1" class="az-input ef-{{ $customer->id }}" value="{{ $customer->phone1 }}" disabled>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Téléphone secondaire</label>
                                                    <input type="text" name="phone2" class="az-input ef-{{ $customer->id }}" value="{{ $customer->phone2 }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tab Finances --}}
                                        <div class="az-tab-panel" id="tab-finances-{{ $customer->id }}">
                                            <div class="az-solde-card">
                                                <div>
                                                    <div class="az-solde-label">Solde actuel</div>
                                                    <div class="az-solde-amount">{{ number_format($customer->solde ?? 0, 2, ',', ' ') }} €</div>
                                                </div>
                                                <i class="fas fa-wallet" style="font-size:2rem;opacity:0.3;"></i>
                                            </div>
                                            <div class="az-section-title"><i class="fas fa-euro-sign"></i> Paramètres financiers</div>
                                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                                <div class="az-field">
                                                    <label class="az-label">Condition de paiement</label>
                                                    <select name="payment_term_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                        @foreach($paymentTerms as $term)
                                                            <option value="{{ $term->id }}" {{ $customer->payment_term_id == $term->id ? 'selected' : '' }}>
                                                                {{ $term->label }} : {{ $term->days }} Jours
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Mode de paiement</label>
                                                    <select name="payment_mode_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                        @foreach($paymentModes as $mode)
                                                            <option value="{{ $mode->id }}" {{ $customer->payment_mode_id == $mode->id ? 'selected' : '' }}>
                                                                {{ $mode->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:12px;">
                                                <div class="az-field">
                                                    <label class="az-label">TVA</label>
                                                    <select name="tva_group_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                        @foreach($tvaGroups as $group)
                                                            <option value="{{ $group->id }}" {{ $customer->tva_group_id == $group->id ? 'selected' : '' }}>
                                                                {{ $group->name }} ({{ $group->rate }}%)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Groupe remise</label>
                                                    <select name="discount_group_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                        @foreach($discountGroups as $group)
                                                            <option value="{{ $group->id }}" {{ $customer->discount_group_id == $group->id ? 'selected' : '' }}>
                                                                {{ $group->name }} ({{ $group->discount_rate }}%)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="az-field">
                                                    <label class="az-label">Plafond (€)</label>
                                                    <input type="number" step="0.01" name="plafond" class="az-input ef-{{ $customer->id }}" value="{{ $customer->plafond ?? 0 }}" disabled>
                                                </div>
                                            </div>
                                            <div class="az-field-group" style="grid-template-columns:1fr;margin-top:12px;max-width:200px;">
                                                <div class="az-field">
                                                    <label class="az-label">Risque</label>
                                                    <input type="number" name="risque" class="az-input ef-{{ $customer->id }}" value="{{ $customer->risque }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tab Statut --}}
                                        <div class="az-tab-panel" id="tab-statut-{{ $customer->id }}">
                                            <div class="az-section-title"><i class="fas fa-shield-alt"></i> Statut client</div>
                                            <input type="hidden" name="blocked" value="0">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <label class="az-status-pill {{ $customer->blocked ? 'blocked' : 'active' }}" id="statusPill{{ $customer->id }}"
                                                       style="cursor:default;">
                                                    <input type="checkbox" name="blocked" value="1" class="ef-{{ $customer->id }}"
                                                           id="blockedSwitch{{ $customer->id }}"
                                                           {{ $customer->blocked ? 'checked' : '' }}
                                                           disabled
                                                           onchange="toggleStatusPill({{ $customer->id }})"
                                                           style="margin-right:6px;">
                                                    <span id="blockedLabel{{ $customer->id }}">
                                                        {{ $customer->blocked ? 'Client Bloqué 🚫' : 'Client Actif ✅' }}
                                                    </span>
                                                </label>
                                            </div>
                                            <small class="text-muted">
                                                ⚠️ Le blocage concerne uniquement l'expédition des ventes.<br>
                                                La facturation reste possible même si le client est bloqué.
                                            </small>
                                        </div>

                                        <div class="modal-footer" style="justify-content:flex-end;background:white;border-top:1.5px solid var(--c-border);padding:14px 24px;">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="az-save-btn" id="saveBtn{{ $customer->id }}">
                                                <i class="fas fa-save"></i> Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination avec conservation des filtres -->
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->appends(request()->query())->links() }}
    </div>

    @else
        <p class="text-muted text-center">Aucun client trouvé.</p>
    @endif

</div>

<!-- JS Recherche -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Recherche en temps réel sur le tableau
    document.getElementById("searchItemInput").addEventListener("keyup", function () {
        const input = this.value.toLowerCase();
        document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
        });
    });

    // Auto-submit des filtres après 1 seconde d'inactivité
    let filterTimeout;
    document.getElementById('filterForm').addEventListener('input', function(e) {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            this.submit();
        }, 1000);
    });
});
</script>

<style>
    .search-box {
        max-width: 400px;
        height: 35px;
        padding: 5px 12px;
        border: 2px solid #007bff;
        border-radius: 20px;
        font-size: 14px;
        box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1);
    }

    .search-box:focus {
        border-color: #0056b3;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.5);
    }

    .table-text-small td,
    .table-text-small th {
        font-size: 11px !important;
    }
</style>

</div>

         
            
           
           
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    

<script>
document.getElementById("searchItemInput").addEventListener("keyup", function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll("#itemsTable tbody tr");

    rows.forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
    });
});
</script>




<script>
document.addEventListener("DOMContentLoaded", function () {
    // Store entries for each customer to avoid refetching
    const accountingEntriesCache = {};

    // Accounting Entries Handler
    document.querySelectorAll('.solde-btn').forEach(button => {
        const modal = document.getElementById(`accountingModal${button.dataset.customerId}`);
        const filterForm = document.getElementById(`accountingFilterForm${button.dataset.customerId}`);

        modal.addEventListener('show.bs.modal', function () {
            const customerId = button.dataset.customerId;
            const tbody = document.getElementById(`accountingEntries${customerId}`);

            // If entries are already cached, apply filters and render
            if (accountingEntriesCache[customerId]) {
                applyFilters(customerId);
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Chargement...</td></tr>';
            fetch(`/customers/${customerId}/accounting-entries`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    accountingEntriesCache[customerId] = data.entries || [];
                    applyFilters(customerId);
                })
                .catch(error => {
                    console.error(`Error fetching accounting entries for customer ID ${customerId}:`, error);
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables. Veuillez réessayer plus tard.</td></tr>`;
                });
        });

        // Handle filter form submission
        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                applyFilters(button.dataset.customerId);
            });
        }
    });

    // Reset filter function
    window.resetAccountingFilter = function (customerId) {
        const filterForm = document.getElementById(`accountingFilterForm${customerId}`);
        const balanceSummary = document.getElementById(`balanceSummary${customerId}`);
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyFilters(customerId);
        }
    };

    // Show balance summary
    window.showBalance = function (customerId) {
        const balanceSummary = document.getElementById(`balanceSummary${customerId}`);
        balanceSummary.style.display = 'block'; // Show balance summary
        applyFilters(customerId); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyFilters(customerId) {
        const tbody = document.getElementById(`accountingEntries${customerId}`);
        const filterForm = document.getElementById(`accountingFilterForm${customerId}`);
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;

        // Get cached entries
        let entries = accountingEntriesCache[customerId] || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
        if (startDate || endDate) {
            entries = entries.filter(entry => {
                if (!entry.date || entry.date === '-') return false;
                const entryDateParts = entry.date.split('/');
                const entryDate = new Date(`${entryDateParts[2]}-${entryDateParts[1]}-${entryDateParts[0]}`);
                if (startDate && entryDate < startDate) return false;
                if (endDate && entryDate > endDate) return false;
                return true;
            });
        }

        // Calculate balance
        let debits = 0;
        let credits = 0;
        entries.forEach(entry => {
            if (entry.type === 'Facture') {
                debits += parseFloat(entry.amount) || 0;
            } else {
                credits += parseFloat(entry.amount) || 0;
            }
        });
        const balance = debits - credits;

        // Update balance summary
        const debitsElement = document.getElementById(`debits${customerId}`);
        const creditsElement = document.getElementById(`credits${customerId}`);
        const balanceElement = document.getElementById(`balance${customerId}`);
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
















// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('allAccountingModal');
    const allFilterForm = document.getElementById('allAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('allAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('allcustomer.accounting-entries') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Reset filter function
    window.resetAllAccountingFilter = function () {
        const filterForm = document.getElementById('allAccountingFilterForm');
        const balanceSummary = document.getElementById('allBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showAllBalance = function () {
        const balanceSummary = document.getElementById('allBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('allAccountingEntries');
        const filterForm = document.getElementById('allAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
        if (startDate || endDate) {
            entries = entries.filter(entry => {
                if (!entry.date || entry.date === '-') return false;
                const entryDateParts = entry.date.split('/');
                const entryDate = new Date(`${entryDateParts[2]}-${entryDateParts[1]}-${entryDateParts[0]}`);
                if (startDate && entryDate < startDate) return false;
                if (endDate && entryDate > endDate) return false;
                return true;
            });
        }

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => entry.customer_id === customerIdFilter);
        }

        // Calculate balance
        let debits = 0;
        let credits = 0;
        entries.forEach(entry => {
            if (entry.type === 'Facture') {
                debits += parseFloat(entry.amount) || 0;
            } else {
                credits += parseFloat(entry.amount) || 0;
            }
        });
        const balance = debits - credits;

        // Update balance summary
        const debitsElement = document.getElementById('allDebits');
        const creditsElement = document.getElementById('allCredits');
        const balanceElement = document.getElementById('allBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>












<script>
document.addEventListener("DOMContentLoaded", function () {
    // === FONCTION GLOBALE POUR INITIALISER SELECT2 DANS N'IMPORTE QUEL MODAL ===
    function initVehicleSelect2(modal) {
        const customerId = modal.id.replace('addVehicleModal', '');

        const $brand = $(`#brand_id_${customerId}`);
        const $model = $(`#model_id_${customerId}`);
        const $engine = $(`#engine_id_${customerId}`);

        // Détruire si déjà initialisé
        [$brand, $model, $engine].forEach($el => {
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
        });

        // Initialiser Select2
        $brand.select2({
            placeholder: "Rechercher une marque...",
            allowClear: true,
            width: '100%',
            dropdownParent: modal
        });

        $model.select2({
            placeholder: "Rechercher un modèle...",
            allowClear: true,
            width: '100%',
            dropdownParent: modal
        });

        $engine.select2({
            placeholder: "Rechercher une motorisation...",
            allowClear: true,
            width: '100%',
            dropdownParent: modal
        });
    }

    // === ÉCOUTEUR GLOBAL SUR TOUS LES MODALS ===
    $(document).on('shown.bs.modal', '[id^="addVehicleModal"]', function () {
        initVehicleSelect2(this);
    });

    // === MARQUE CHANGE (délégation d'événements) ===
    $(document).on('change', '[id^="brand_id_"]', function () {
        const customerId = this.id.replace('brand_id_', '');
        const brandId = this.value;
        const brandName = $(this).find('option:selected').data('name') || '';
        $(`#brand_name_${customerId}`).val(brandName);

        const $model = $(`#model_id_${customerId}`);
        const $engine = $(`#engine_id_${customerId}`);

        if (brandId) {
            fetch(`{{ route('getModels') }}?brand_id=${brandId}`)
                .then(r => r.json())
                .then(data => {
                    $model.empty().append('<option value="">Rechercher un modèle...</option>');
                    data.forEach(m => {
                        $model.append(`<option value="${m.id}" data-name="${m.name}">${m.name}</option>`);
                    });
                    $model.val('').trigger('change');
                });
        } else {
            $model.empty().append('<option value="">Rechercher un modèle...</option>').val('').trigger('change');
        }
        $engine.empty().append('<option value="">Rechercher une motorisation...</option>').val('').trigger('change');
    });

    // === MODÈLE CHANGE ===
    $(document).on('change', '[id^="model_id_"]', function () {
        const customerId = this.id.replace('model_id_', '');
        const modelId = this.value;
        const modelName = $(this).find('option:selected').data('name') || '';
        $(`#model_name_${customerId}`).val(modelName);

        const $engine = $(`#engine_id_${customerId}`);

        if (modelId) {
            fetch(`{{ route('getEngines') }}?model_id=${modelId}`)
                .then(r => r.json())
                .then(data => {
                    $engine.empty().append('<option value="">Rechercher une motorisation...</option>');
                    data.forEach(e => {
                        $engine.append(
                            `<option value="${e.id}" 
                                     data-description="${e.description}" 
                                     data-linking-target-id="${e.linkageTargetId}">
                                ${e.description}
                             </option>`
                        );
                    });
                    $engine.val('').trigger('change');
                });
        } else {
            $engine.empty().append('<option value="">Rechercher une motorisation...</option>').val('').trigger('change');
        }
    });

    // === MOTORISATION CHANGE ===
    $(document).on('change', '[id^="engine_id_"]', function () {
        const customerId = this.id.replace('engine_id_', '');
        const desc = $(this).find('option:selected').data('description') || '';
        const link = $(this).find('option:selected').data('linking-target-id') || '';
        $(`#engine_description_${customerId}`).val(desc);
        $(`#linkage_target_id_${customerId}`).val(link);
    });

    // === RESET QUAND MODAL FERMÉ ===
    $(document).on('hidden.bs.modal', '[id^="addVehicleModal"]', function () {
        const customerId = this.id.replace('addVehicleModal', '');
        $(`#vehicleForm${customerId}`)[0].reset();
        $(`#model_id_${customerId}, #engine_id_${customerId}`)
            .empty()
            .append('<option value="">Rechercher...</option>')
            .val('').trigger('change');
        $(`#brand_name_${customerId}, #model_name_${customerId}, #engine_description_${customerId}, #linkage_target_id_${customerId}`).val('');
    });
});
</script>







<script>
// ════════════════════════════════════════════════════════
// STEPPER — Création client (3 étapes)
// ════════════════════════════════════════════════════════
var currentStep = 1;
var totalSteps  = 3;

function stepNav(dir) {
    var next = currentStep + dir;
    if (next < 1 || next > totalSteps) return;

    if (dir > 0) {
        var panel = document.getElementById('panel-' + currentStep);
        var required = panel.querySelectorAll('[required]');
        var valid = true;
        required.forEach(function(el) {
            if (!el.value.trim()) {
                el.classList.add('is-invalid');
                el.style.borderColor = 'var(--c-red)';
                valid = false;
            } else {
                el.style.borderColor = '';
                el.classList.remove('is-invalid');
            }
        });
        if (!valid) return;
    }

    document.getElementById('panel-' + currentStep).classList.remove('active');
    currentStep = next;
    document.getElementById('panel-' + currentStep).classList.add('active');

    for (var i = 1; i <= totalSteps; i++) {
        var step = document.querySelector('#createItemModal .az-stepper [data-step="' + i + '"]');
        if (!step) continue;
        step.classList.remove('active', 'done');
        if (i < currentStep)        step.classList.add('done');
        else if (i === currentStep) step.classList.add('active');
        var circle = step.querySelector('.az-step-circle');
        circle.innerHTML = i < currentStep ? '<i class="fas fa-check" style="font-size:0.7rem;"></i>' : i;
    }
    var line1 = document.getElementById('line1');
    var line2 = document.getElementById('line2');
    if (line1) line1.classList.toggle('done', currentStep > 1);
    if (line2) line2.classList.toggle('done', currentStep > 2);

    document.getElementById('btnPrev').style.display = currentStep > 1 ? 'flex' : 'none';
    var btnNext        = document.getElementById('btnNext');
    var btnSubmit      = document.getElementById('btnSubmit');
    var btnSubmitClose = document.getElementById('btnSubmitClose');

    if (currentStep === totalSteps) {
        btnNext.style.display        = 'none';
        btnSubmit.style.display      = 'flex';
        btnSubmitClose.style.display = 'flex';
    } else {
        btnNext.style.display        = 'flex';
        btnSubmit.style.display      = 'none';
        btnSubmitClose.style.display = 'none';
    }
    document.getElementById('stepCounter').textContent = 'Étape ' + currentStep + ' sur ' + totalSteps;
}

document.getElementById('createItemModal').addEventListener('hidden.bs.modal', function() {
    currentStep = 1;
    for (var i = 1; i <= totalSteps; i++) {
        var panel = document.getElementById('panel-' + i);
        if (panel) panel.classList.toggle('active', i === 1);
        var step = document.querySelector('#createItemModal .az-stepper [data-step="' + i + '"]');
        if (!step) continue;
        step.classList.remove('active', 'done');
        if (i === 1) step.classList.add('active');
        step.querySelector('.az-step-circle').textContent = i;
    }
    var line1 = document.getElementById('line1');
    var line2 = document.getElementById('line2');
    if (line1) line1.classList.remove('done');
    if (line2) line2.classList.remove('done');
    document.getElementById('btnPrev').style.display        = 'none';
    document.getElementById('btnNext').style.display        = 'flex';
    document.getElementById('btnSubmit').style.display      = 'none';
    document.getElementById('btnSubmitClose').style.display = 'none';
    document.getElementById('stepCounter').textContent = 'Étape 1 sur 3';
    document.getElementById('createCustomerForm').reset();
});

// Créer et Fermer (AJAX + window.close)
document.getElementById('btnSubmitClose').addEventListener('click', function () {
    var form = document.getElementById('createCustomerForm');
    var valid = true;
    form.querySelectorAll('[required]').forEach(function (el) {
        if (!el.value.trim()) {
            el.classList.add('is-invalid');
            el.style.borderColor = 'var(--c-red)';
            valid = false;
        } else {
            el.style.borderColor = '';
            el.classList.remove('is-invalid');
        }
    });
    if (!valid) {
        for (var s = 1; s <= totalSteps; s++) {
            var p = document.getElementById('panel-' + s);
            if (p && p.querySelector('.is-invalid')) {
                while (currentStep !== s) stepNav(currentStep < s ? 1 : -1);
                break;
            }
        }
        return;
    }

    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';

    var fd = new FormData(form);
    fd.set('action', 'create_and_close');

    fetch(form.action, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json, text/html, */*' },
        credentials: 'same-origin'
    })
    .then(function (res) {
        if (res.ok || res.redirected || res.status === 302) {
            try { window.close(); } catch (e) {}
            setTimeout(function () {
                document.body.innerHTML =
                    '<div style="font-family:system-ui,sans-serif;text-align:center;padding:60px 20px;">' +
                    '<div style="font-size:3rem;margin-bottom:12px;">✅</div>' +
                    '<h2 style="margin:0 0 8px;">Client créé avec succès</h2>' +
                    '<p style="color:#64748b;">Vous pouvez fermer cette fenêtre.</p>' +
                    '<button onclick="window.close()" style="margin-top:20px;padding:10px 24px;border:none;border-radius:8px;background:#059669;color:#fff;font-weight:600;cursor:pointer;">Fermer la fenêtre</button>' +
                    '</div>';
            }, 300);
            return;
        }
        return res.json().then(function (data) { throw data; })
            .catch(function () { throw { message: 'Erreur HTTP ' + res.status }; });
    })
    .catch(function (err) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-double"></i> Créer et Fermer';
        var msg = 'Erreur lors de la création du client.';
        if (err && err.errors) msg = Object.values(err.errors).flat().join('\\n');
        else if (err && err.message) msg = err.message;
        alert(msg);
    });
});

// ════════════════════════════════════════════════════════
// EDIT — tabs + lock/unlock
// ════════════════════════════════════════════════════════
var editState = {};

function switchTab(btn, id) {
    var modal = document.getElementById('editItemModal' + id);
    modal.querySelectorAll('.az-tab').forEach(function(t) { t.classList.remove('active'); });
    modal.querySelectorAll('.az-tab-panel').forEach(function(p) { p.classList.remove('active'); });
    btn.classList.add('active');
    var tabId = btn.getAttribute('data-tab');
    var panel = document.getElementById('tab-' + tabId);
    if (panel) panel.classList.add('active');
}

function toggleEdit(id) {
    var fields  = document.querySelectorAll('.ef-' + id);
    var btn     = document.getElementById('lockBtn' + id);
    var saveBtn = document.getElementById('saveBtn' + id);
    var isEditing = editState[id] || false;

    if (!isEditing) {
        fields.forEach(function(el) { el.removeAttribute('disabled'); });
        btn.innerHTML = '<i class="fas fa-times"></i> Annuler';
        btn.classList.add('editing');
        if (saveBtn) saveBtn.style.display = 'flex';
        editState[id] = true;
    } else {
        // reload to restore original values
        location.reload();
    }
}

function toggleStatusPill(id) {
    var cb = document.getElementById('blockedSwitch' + id);
    var label = document.getElementById('blockedLabel' + id);
    var pill = document.getElementById('statusPill' + id);
    if (cb.checked) {
        label.innerText = 'Client Bloqué 🚫';
        pill.classList.remove('active');
        pill.classList.add('blocked');
    } else {
        label.innerText = 'Client Actif ✅';
        pill.classList.remove('blocked');
        pill.classList.add('active');
    }
}
</script>

  </body>
</html>
