<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Commandes portail — AZ ERP</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
        html {
            scroll-behavior: smooth;
        }

        .search-box {
            max-width: 100%;
            height: 45px;
            padding: 10px 15px;
            border: 2px solid #007bff;
            border-radius: 25px;
            font-size: 16px;
            transition: 0.3s ease-in-out;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .search-box:focus {
            border-color: #0056b3;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        .card-stats {
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .icon-big {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .section-header {
            font-weight: bold;
            font-size: 1.25rem;
            margin-bottom: 10px;
            padding-bottom: 5px;
            position: relative;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
        }

        .section-header.company::after {
            background-color: #17a2b8;
        }

        .section-header.stock::after {
            background-color: #28a745;
        }

        .section-header.accounting::after {
            background-color: #ffc107;
        }

        .section-header.users::after {
            background-color: #dc3545;
        }

        .section-description {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .card-category {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .search-help-links {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-help-links a {
            display: inline-block;
            padding: 5px 12px;
            font-size: 0.85rem;
            color: #007bff;
            background-color: #e9ecef;
            border-radius: 15px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .search-help-links a:hover {
            background-color: #d0d7de;
            color: #0056b3;
        }
    </style>





  <style>
.b2b-nav .nav-link{color:#1E2D4A;font-weight:600;font-size:.88rem;border-radius:10px;padding:10px 14px;text-align:left}
.b2b-nav .nav-link i{width:22px;color:#3B82F6}
.b2b-nav .nav-link.active{background:#1E2D4A;color:#fff}
.b2b-nav .nav-link.active i{color:#fff}
.b2b-card{background:#fff;border-radius:14px;box-shadow:0 2px 14px rgba(30,45,74,.08);padding:22px 24px;margin-bottom:18px}
.b2b-card h5{font-size:.95rem;font-weight:800;color:#1E2D4A;margin:0 0 4px}
.b2b-card .sub{font-size:.8rem;color:#6B7A99;margin-bottom:16px}
.b2b-card label.fl{font-size:.7rem;font-weight:700;color:#6B7A99;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px}
.b2b-preview{border-radius:12px;overflow:hidden;border:1px solid #E2E8F0}
.b2b-preview .bar{display:flex;align-items:center;gap:12px;padding:12px 16px;color:#fff;transition:background .2s}
.b2b-preview .bar img{height:38px;max-width:120px;object-fit:contain;background:#fff;border-radius:6px;padding:3px;display:none}
.b2b-preview .bar b{font-size:1rem}
.b2b-preview .bar small{display:block;opacity:.8;font-size:.72rem}
.b2b-preview .body{padding:14px 16px;font-size:.82rem;color:#475569;background:#F8FAFF}
.b2b-switch{display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid #EEF2F7}
.b2b-switch:last-child{border-bottom:none}
.b2b-switch .form-check-input{margin-top:3px;width:2.6em;height:1.3em;flex-shrink:0}
.b2b-switch b{display:block;font-size:.88rem;color:#1E2D4A}
.b2b-switch span{font-size:.78rem;color:#6B7A99}
.b2b-savebar{position:sticky;bottom:0;background:rgba(255,255,255,.96);border-top:1.5px solid #E2E8F0;padding:12px 0;margin-top:10px;z-index:20;display:flex;justify-content:space-between;align-items:center}
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
                            <li><a href="{{ route('sales.stats') }}"><span class="sub-item">Statistiques ventes</span></a></li>
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
                            <p class="text-muted">{{ Auth::user()->name}}</p>

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
        <div class="page-inner mt-4">
<style>
.po-tabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px}
.po-tab{border:1px solid #dfe3e8;background:#fff;border-radius:999px;padding:7px 16px;font-weight:600;font-size:.9rem;cursor:pointer}
.po-tab.on{background:#1572e8;border-color:#1572e8;color:#fff}.po-tab b{margin-left:6px;opacity:.85}
.po-tab.hot b{background:#f25961;color:#fff;border-radius:99px;padding:1px 8px;opacity:1}
.po-wrap{background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden}
.po-wrap table{width:100%;margin:0}.po-wrap th{font-size:.78rem;color:#6c757d;font-weight:600;background:#f8f9fa;white-space:nowrap}
.po-wrap td,.po-wrap th{padding:11px 14px;vertical-align:middle;border-top:1px solid #eef0f2}.po-wrap tbody tr{cursor:pointer}.po-wrap tbody tr:hover{background:#f5f9ff}
.po-n{font-family:ui-monospace,Menlo,Consolas,monospace;font-weight:700}
.po-st{display:inline-block;border-radius:99px;padding:3px 11px;font-size:.8rem;font-weight:700}
.po-st.pending{background:#fff3cd;color:#8a6200}.po-st.validated{background:#d6f3e4;color:#0f7b55}.po-st.refused,.po-st.cancelled{background:#fbdcd9;color:#b3261e}
.po-warn{color:#b3261e;font-weight:600;font-size:.8rem}
.po-veil{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:2000;display:none}.po-veil.on{display:block}
.po-pan{position:fixed;top:0;right:0;bottom:0;width:min(760px,100vw);background:#fff;z-index:2001;transform:translateX(102%);transition:transform .25s;display:flex;flex-direction:column;box-shadow:-10px 0 40px rgba(0,0,0,.2)}
.po-pan.on{transform:none}.po-ph{padding:16px 20px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center}
.po-pb{padding:18px 20px;overflow:auto;flex:1}.po-pf{padding:14px 20px;border-top:1px solid #eee;background:#f8f9fa;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.po-box{border:1px solid #e6e9ed;border-radius:10px;padding:12px 14px;margin-bottom:12px;font-size:.92rem}
.po-alert{border-radius:10px;padding:10px 14px;margin-bottom:10px;font-size:.9rem}.po-alert.r{background:#fbdcd9;color:#8f1d17}.po-alert.o{background:#fff3cd;color:#7a5600}.po-alert.i{background:#e7f1fe;color:#0b4fa8}
.po-lines th,.po-lines td{padding:8px 10px;font-size:.88rem;border-top:1px solid #eef0f2;text-align:left}.po-lines .r{text-align:right;white-space:nowrap}
.po-short{color:#b3261e;font-weight:600}
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h3 class="fw-bold mb-0"><i class="fas fa-inbox me-2 text-info"></i>Commandes portail</h3>
        <small class="text-muted">Les commandes passées par vos clients depuis le portail B2B. Cette page se met à jour toute seule.</small>
    </div>
    <input type="search" id="poQ" class="form-control" style="max-width:280px" placeholder="N° de commande, client, référence…">
</div>

<div class="po-tabs" id="poTabs">
  <button class="po-tab on" data-s="pending">À valider<b id="c-pending">{{ $pending }}</b></button>
  <button class="po-tab" data-s="validated">Validées<b id="c-validated"></b></button>
  <button class="po-tab" data-s="refused">Refusées<b id="c-refused"></b></button>
  <button class="po-tab" data-s="cancelled">Annulées<b id="c-cancelled"></b></button>
  <button class="po-tab" data-s="all">Toutes</button>
</div>

<div class="po-wrap"><table>
  <thead><tr><th>N°</th><th>Reçue</th><th>Client</th><th>Articles</th><th class="text-end">Total TTC</th><th>Réception</th><th>Statut</th></tr></thead>
  <tbody id="poBody"><tr><td colspan="7" class="text-center text-muted p-4">Chargement…</td></tr></tbody>
</table></div>
<div class="d-flex justify-content-center align-items-center gap-3 mt-3" id="poPager"></div>

<div class="po-veil" id="poVeil"></div>
<aside class="po-pan" id="poPan" aria-hidden="true">
  <div class="po-ph"><h4 class="mb-0" id="poTitle">Commande</h4><button class="btn btn-link text-muted" id="poClose" aria-label="Fermer"><i class="fas fa-times"></i></button></div>
  <div class="po-pb" id="poDetail"></div>
  <div class="po-pf" id="poFoot"></div>
</aside>

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
<script>
(function () {
    var CSRF = {!! json_encode(csrf_token()) !!};
    var U = {
        data: {!! json_encode(route('b2b.orders.data')) !!},
        show: {!! json_encode(url('/b2b/orders')) !!}
    };
    var $ = function (id) { return document.getElementById(id); };
    var esc = function (v) { return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
    var fmt = function (n) { return Number(n).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
    var st = {s: 'pending', q: '', page: 1}, timer, lastPending = {{ (int) $pending }}, cur = null;
    function hdr() { return {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json'}; }
    function post(url, body) { return fetch(url, {method: 'POST', credentials: 'same-origin', headers: hdr(), body: JSON.stringify(body || {})}).then(function (r) { return r.json().then(function (j) { j.__ok = r.ok; return j; }); }); }

    function load(silent) {
        var p = new URLSearchParams({status: st.s === 'all' ? 'all' : st.s, q: st.q, page: st.page});
        fetch(U.data + '?' + p, {credentials: 'same-origin', headers: hdr()}).then(function (r) { return r.json(); }).then(function (j) {
            ['pending', 'validated', 'refused', 'cancelled'].forEach(function (k) { $('c-' + k).textContent = j.counts[k]; });
            $('poTabs').querySelector('[data-s=pending]').classList.toggle('hot', j.counts.pending > 0);
            document.title = (j.counts.pending ? '(' + j.counts.pending + ') ' : '') + 'Commandes portail';
            if (silent && j.counts.pending > lastPending && window.$ && $.notify) { try { $.notify({message: 'Nouvelle commande portail'}, {type: 'info'}); } catch (e) {} }
            lastPending = j.counts.pending;
            $('poBody').innerHTML = j.rows.length ? j.rows.map(function (o) {
                return '<tr data-id="' + o.id + '"><td class="po-n">' + esc(o.number) + '</td><td>' + esc(o.created) + '<div class="small text-muted">' + esc(o.ago) + '</div></td>' +
                    '<td><b>' + esc(o.customer) + '</b><div class="small text-muted">' + esc(o.user || '') + '</div>' + (o.credit_warning ? '<div class="po-warn">Dépasse le plafond de crédit</div>' : '') + '</td>' +
                    '<td>' + o.lines + ' réf., ' + o.units + ' pièces</td><td class="text-end"><b>' + fmt(o.total_ttc) + ' €</b></td>' +
                    '<td>' + (o.mode === 'pickup' ? 'Retrait' : 'Livraison') + '</td><td><span class="po-st ' + o.status + '">' + esc(o.status_label) + '</span>' + (o.auto ? '<div class="small text-muted">auto</div>' : '') + '</td></tr>';
            }).join('') : '<tr><td colspan="7" class="text-center text-muted p-4">Aucune commande dans cette liste.</td></tr>';
            $('poPager').innerHTML = j.last > 1 ? '<button class="btn btn-sm btn-outline-secondary" ' + (j.page <= 1 ? 'disabled' : '') + ' data-p="' + (j.page - 1) + '">Précédent</button><span class="text-muted">Page ' + j.page + ' sur ' + j.last + '</span><button class="btn btn-sm btn-outline-secondary" ' + (j.page >= j.last ? 'disabled' : '') + ' data-p="' + (j.page + 1) + '">Suivant</button>' : '';
        });
    }
    $('poTabs').addEventListener('click', function (e) { var b = e.target.closest('.po-tab'); if (!b) return; st.s = b.getAttribute('data-s'); st.page = 1; [].forEach.call($('poTabs').children, function (x) { x.classList.toggle('on', x === b); }); load(); });
    $('poQ').addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(function () { st.q = $('poQ').value; st.page = 1; load(); }, 300); });
    $('poPager').addEventListener('click', function (e) { var b = e.target.closest('[data-p]'); if (b && !b.disabled) { st.page = parseInt(b.getAttribute('data-p'), 10); load(); } });
    $('poBody').addEventListener('click', function (e) { var tr = e.target.closest('tr[data-id]'); if (tr) open(tr.getAttribute('data-id')); });
    $('poClose').addEventListener('click', close); $('poVeil').addEventListener('click', close);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    function close() { $('poPan').classList.remove('on'); $('poVeil').classList.remove('on'); }

    function open(id) {
        $('poDetail').innerHTML = '<p class="text-muted">Chargement…</p>'; $('poFoot').innerHTML = '';
        $('poPan').classList.add('on'); $('poVeil').classList.add('on');
        fetch(U.show + '/' + id, {credentials: 'same-origin', headers: hdr()}).then(function (r) { return r.json(); }).then(function (o) { cur = o; render(o); });
    }
    function render(o) {
        $('poTitle').innerHTML = esc(o.number) + ' <span class="po-st ' + o.status + '" style="font-size:.75rem;vertical-align:middle">' + esc(o.status_label) + '</span>';
        var short = o.items.filter(function (l) { return l.short; }).length, h = '';
        if (o.customer_blocked) h += '<div class="po-alert r"><b>Client bloqué</b> dans l\'ERP : vérifiez avant de valider.</div>';
        if (o.credit_warning) h += '<div class="po-alert r"><b>Plafond de crédit :</b> solde actuel ' + fmt(o.solde) + ' € + cette commande ' + fmt(o.total_ttc) + ' € dépasse le plafond de ' + fmt(o.plafond) + ' €.</div>';
        if (short) h += '<div class="po-alert o"><b>' + short + ' ligne' + (short > 1 ? 's' : '') + '</b> avec un stock insuffisant (en rouge ci-dessous).</div>';
        h += '<div class="po-box"><b>' + esc(o.customer) + '</b> <span class="text-muted">' + esc(o.customer_code || '') + '</span><br>' + esc(o.user || '') + (o.customer_phone ? ' · ' + esc(o.customer_phone) : '') + (o.customer_city ? ' · ' + esc(o.customer_city) : '') +
            '<br><span class="text-muted">Reçue le ' + esc(o.created) + ' · ' + (o.mode === 'pickup' ? 'Retrait au magasin' : 'Livraison') + '</span>' + (o.po_ref ? '<br>Référence client : <b>' + esc(o.po_ref) + '</b>' : '') + '</div>';
        if (o.note) h += '<div class="po-alert i"><b>Message du client :</b><br>' + esc(o.note).replace(/\n/g, '<br>') + '</div>';
        if (o.refuse_reason) h += '<div class="po-alert r"><b>Motif du refus :</b> ' + esc(o.refuse_reason) + '</div>';
        var ed = o.status === 'pending';
        o.items.forEach(function (l) { if (l.eqty === undefined) { l.eqty = l.qty; l.epx = l.unit_ht; l.edel = l.removed; } });
        h += '<table class="po-lines" style="width:100%"><thead><tr><th>Article</th><th class="r">Qté</th><th class="r">Stock</th><th class="r">Prix HT</th><th class="r">Total HT</th>' + (ed ? '<th></th>' : '') + '</tr></thead><tbody id="poLines">' + o.items.map(rowHtml).join('') + '</tbody></table>';
        h += '<div class="text-end mt-3" id="poTot"></div>';
        if (ed) h += '<div class="mt-3"><label class="small fw-bold">Message au client (visible, facultatif)</label><textarea id="poVm" class="form-control" rows="2" maxlength="500" placeholder="Ex. : quantité réduite, article en rupture, prix mis à jour…">' + esc(o.vendor_message || '') + '</textarea><div class="small text-muted mt-1">Vous pouvez ajuster quantités et prix, ou retirer une ligne : le client verra ce qui a changé.</div></div>';
        else if (o.vendor_message) h += '<div class="po-alert o"><b>Message envoyé au client :</b> ' + esc(o.vendor_message) + '</div>';
        if (o.modified && !ed) h += '<div class="po-alert o">Commande acceptée <b>avec modifications</b>' + (o.orig_total_ttc !== null ? ' (total initial ' + fmt(o.orig_total_ttc) + ' € TTC)' : '') + '.</div>';
        h += '<div class="mt-3"><label class="small fw-bold">Note interne (invisible pour le client)</label><textarea id="poNote" class="form-control" rows="2" maxlength="1000">' + esc(o.internal_note || '') + '</textarea></div>';
        if (o.erp_error) h += '<div class="po-alert r mt-3"><b>Création dans l\'ERP impossible :</b> ' + esc(o.erp_error) + ' <button class="btn btn-sm btn-light ms-2" id="poRetry">Réessayer</button></div>';
        if (o.erp_numdoc) h += '<div class="po-alert i mt-3"><b>ERP :</b> commande vente <a href="' + esc(o.erp_url) + '" target="_blank"><b>' + esc(o.erp_numdoc) + '</b></a>' + (o.erp_invoice_numdoc ? ' · facture <b>' + esc(o.erp_invoice_numdoc) + '</b> générée automatiquement' : (o.status === 'refused' || o.status === 'cancelled' ? '' : ' (brouillon) : convertissez-la en bon de livraison ou en facture depuis la liste des commandes de vente.')) + '</div>';
        if (o.status === 'validated') h += '<div class="po-alert i mt-3">Validée' + (o.validated_at ? ' le ' + esc(o.validated_at) : '') + (o.auto ? ' automatiquement' : '') + '.</div>';
        $('poDetail').innerHTML = h; recalc();
        var f = '';
        if (o.status === 'pending') f += '<button class="btn btn-success" id="poOk"><i class="fas fa-check me-1"></i><span id="poOkT">Accepter la commande</span></button><button class="btn btn-outline-danger" id="poNo">Refuser</button>';
        f += '<button class="btn btn-outline-secondary" id="poPrint"><i class="fas fa-print me-1"></i>Imprimer le ticket</button><button class="btn btn-outline-secondary" id="poSaveNote">Enregistrer la note</button><span class="small text-muted" id="poMsg"></span>';
        $('poFoot').innerHTML = f;
    }
    function rowHtml(l, i) {
        var ed = cur && cur.status === 'pending', ch = l.orig_qty !== null && l.orig_qty !== undefined, was = '';
        if (!ed && (l.removed || (ch && (l.qty != l.orig_qty || (l.orig_unit_ht !== null && Math.abs(l.unit_ht - l.orig_unit_ht) > 0.00005))))) was = l.removed ? '<div class="small po-short">ligne retirée</div>' : '<div class="small text-muted">commandé : ' + l.orig_qty + ' × ' + fmt(l.orig_unit_ht) + '</div>';
        if (!ed) return '<tr' + (l.removed ? ' style="opacity:.5;text-decoration:line-through"' : '') + '><td><b>' + esc(l.code) + '</b> <span class="text-muted">' + esc(l.brand || '') + '</span><br>' + esc(l.name) + was + '</td><td class="r">' + l.qty + '</td><td class="r ' + (l.short ? 'po-short' : '') + '">' + l.stock + '</td><td class="r">' + fmt(l.unit_ht) + (l.discount > 0 ? '<div class="small text-muted">−' + l.discount + ' %</div>' : '') + '</td><td class="r"><b>' + fmt(l.line_ht) + '</b></td></tr>';
        var hint = (l.eqty != l.qty || Math.abs(l.epx - l.unit_ht) > 0.00005) ? '<div class="small text-muted">commandé : ' + l.qty + ' × ' + fmt(l.unit_ht) + '</div>' : '';
        return '<tr id="poR' + i + '" style="' + (l.edel ? 'opacity:.45' : '') + '"><td><b>' + esc(l.code) + '</b> <span class="text-muted">' + esc(l.brand || '') + '</span><br>' + esc(l.name) + (l.discount > 0 ? '<div class="small text-muted">remise client −' + l.discount + ' %</div>' : '') + '<div id="poH' + i + '">' + hint + '</div></td>' +
            '<td class="r"><input type="number" min="1" step="1" class="form-control form-control-sm poQty" data-i="' + i + '" value="' + l.eqty + '" style="width:76px;text-align:right"' + (l.edel ? ' disabled' : '') + '></td>' +
            '<td class="r ' + (l.short ? 'po-short' : '') + '" id="poS' + i + '">' + l.stock + '</td>' +
            '<td class="r"><input type="number" min="0" step="0.01" class="form-control form-control-sm poPx" data-i="' + i + '" value="' + (Math.round(l.epx * 100) / 100) + '" style="width:96px;text-align:right"' + (l.edel ? ' disabled' : '') + '></td>' +
            '<td class="r"><b id="poL' + i + '"></b></td><td class="r"><button type="button" class="btn btn-sm ' + (l.edel ? 'btn-outline-secondary' : 'btn-outline-danger') + ' poDel" data-i="' + i + '" title="' + (l.edel ? 'Remettre la ligne' : 'Retirer la ligne') + '">' + (l.edel ? '↺' : '✕') + '</button></td></tr>';
    }
    function dirty() {
        if (!cur || cur.status !== 'pending') return false;
        var vm = $('poVm'), m = vm ? vm.value.trim() : '';
        return m !== (cur.vendor_message || '') || cur.items.some(function (l) { return l.edel !== l.removed || l.eqty != l.qty || Math.abs(l.epx - l.unit_ht) > 0.00005; });
    }
    function recalc() {
        if (!cur) return;
        var ed = cur.status === 'pending', ht = 0, tva = 0;
        cur.items.forEach(function (l, i) {
            var q = ed ? l.eqty : l.qty, p = ed ? l.epx : l.unit_ht, gone = ed ? l.edel : l.removed, lt = Math.round(q * p * 100) / 100;
            if (!gone) { ht += lt; tva += lt * (l.tva_rate || 0) / 100; }
            var c = $('poL' + i); if (c) c.textContent = gone ? '—' : fmt(lt);
            var s = $('poS' + i); if (s) s.className = 'r ' + (l.stock < q && !gone ? 'po-short' : '');
            var hh = $('poH' + i); if (hh) hh.innerHTML = (l.eqty != l.qty || Math.abs(l.epx - l.unit_ht) > 0.00005) ? '<div class="small text-muted">commandé : ' + l.qty + ' × ' + fmt(l.unit_ht) + '</div>' : '';
        });
        var tot = $('poTot'); if (!tot) return;
        if (!ed) { tot.innerHTML = '<div>Total HT : <b>' + fmt(cur.total_ht) + ' €</b></div><div>TVA : ' + fmt(cur.total_tva) + ' €</div><div style="font-size:1.2rem">Total TTC : <b>' + fmt(cur.total_ttc) + ' €</b></div>'; return; }
        ht = Math.round(ht * 100) / 100; tva = Math.round(tva * 100) / 100;
        tot.innerHTML = '<div>Total HT : <b>' + fmt(ht) + ' €</b></div><div>TVA : ' + fmt(tva) + ' €</div><div style="font-size:1.2rem">Total TTC : <b>' + fmt(ht + tva) + ' €</b></div>' + (Math.abs(ht + tva - cur.total_ttc) > 0.004 ? '<div class="small po-short">Total initial : ' + fmt(cur.total_ttc) + ' € TTC</div>' : '');
        var ok = $('poOkT'); if (ok) ok.textContent = dirty() ? 'Accepter avec modifications' : 'Accepter la commande';
    }
    $('poDetail').addEventListener('input', function (e) {
        var q = e.target.classList.contains('poQty'), p = e.target.classList.contains('poPx');
        if (q || p) { var l = cur.items[parseInt(e.target.getAttribute('data-i'), 10)], v = parseFloat(String(e.target.value).replace(',', '.')); if (isNaN(v) || v < (q ? 1 : 0)) v = q ? 1 : 0; if (q) l.eqty = Math.round(v); else l.epx = v; }
        if (q || p || e.target.id === 'poVm') recalc();
    });
    $('poDetail').addEventListener('click', function (e) {
        var d = e.target.closest('.poDel'); if (!d) return;
        var i = parseInt(d.getAttribute('data-i'), 10), l = cur.items[i];
        if (!l.edel && cur.items.filter(function (x) { return !x.edel; }).length < 2) { alert('Gardez au moins une ligne, ou refusez la commande.'); return; }
        l.edel = !l.edel; var tr = $('poR' + i); tr.outerHTML = rowHtml(l, i); recalc();
    });
    function saveEdits() {
        var vm = $('poVm');
        return post(U.show + '/' + cur.id + '/update', {vendor_message: vm ? vm.value.trim() : '', lines: cur.items.map(function (l) { return {id: l.id, qty: l.eqty, unit_ht: l.epx, removed: l.edel}; })});
    }
    function msg(t, bad) { var m = $('poMsg'); if (m) { m.textContent = t; m.style.color = bad ? '#b3261e' : '#0f7b55'; } }
    $('poDetail').addEventListener('click', function (e) { if (e.target.id === 'poRetry') { e.target.disabled = true; post(U.show + '/' + cur.id + '/retry-erp').then(function (j) { if (j.ok) { load(); open(cur.id); } else { e.target.disabled = false; alert(j.message || 'Impossible.'); } }); } });
    $('poFoot').addEventListener('click', function (e) {
        var id = cur.id, b = e.target.closest('button'); if (!b) return;
        if (b.id === 'poOk') { b.disabled = true; var go = function () { return post(U.show + '/' + id + '/validate', {internal_note: $('poNote').value}); }; (dirty() ? saveEdits().then(function (s) { if (!s.ok) { throw new Error(s.message || 'Modification impossible.'); } return go(); }) : go()).then(function (j) { if (j.ok) { load(); open(id); } else { msg(j.message || 'Impossible.', true); b.disabled = false; } }).catch(function (er) { msg(er.message || 'Impossible.', true); b.disabled = false; }); }
        else if (b.id === 'poNo') {
            $('poFoot').innerHTML = '<input type="text" id="poWhy" class="form-control" style="flex:1;min-width:220px" maxlength="255" placeholder="Motif du refus (visible par le client)"><button class="btn btn-danger" id="poNo2">Confirmer le refus</button><button class="btn btn-light" id="poBack">Retour</button><span class="small" id="poMsg"></span>'; $('poWhy').focus();
        }
        else if (b.id === 'poBack') render(cur);
        else if (b.id === 'poNo2') { var w = $('poWhy').value.trim(); if (!w) { msg('Indiquez un motif.', true); return; } post(U.show + '/' + id + '/refuse', {reason: w}).then(function (j) { if (j.ok) { load(); open(id); } else msg(j.message || 'Impossible.', true); }); }
        else if (b.id === 'poPrint') post(U.show + '/' + id + '/reprint').then(function (j) { msg(j.message, !j.ok); });
        else if (b.id === 'poSaveNote') post(U.show + '/' + id + '/note', {internal_note: $('poNote').value}).then(function (j) { msg(j.ok ? 'Note enregistrée.' : 'Erreur.', !j.ok); });
    });
    load(); setInterval(function () { if (!document.hidden) load(true); }, 30000);
})();
</script>

</body>
</html>
