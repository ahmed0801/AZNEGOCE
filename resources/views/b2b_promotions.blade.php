<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Promotions portail — AZ ERP</title>
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
.po-st.new,.po-st.processing{background:#fff3cd;color:#8a6200}.po-st.negotiating{background:#ffe5d0;color:#a14a00}.po-st.answered{background:#e7f1fe;color:#0b4fa8}.po-st.accepted{background:#d6f3e4;color:#0f7b55}.po-st.declined,.po-st.cancelled{background:#fbdcd9;color:#b3261e}
.po-veil{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:2000;display:none}.po-veil.on{display:block}
.po-pan{position:fixed;top:0;right:0;bottom:0;width:min(980px,100vw);background:#fff;z-index:2001;transform:translateX(102%);transition:transform .25s;display:flex;flex-direction:column;box-shadow:-10px 0 40px rgba(0,0,0,.25)}
.po-pan.on{transform:none}.po-ph{padding:16px 20px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center}
.po-pb{padding:18px 20px;overflow:auto;flex:1}.po-pf{padding:14px 20px;border-top:1px solid #eee;background:#f8f9fa;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.po-box{border:1px solid #e6e9ed;border-radius:10px;padding:12px 14px;margin-bottom:12px;font-size:.92rem}
.po-alert{border-radius:10px;padding:10px 14px;margin-bottom:10px;font-size:.9rem}.po-alert.o{background:#fff3cd;color:#7a5600}.po-alert.i{background:#e7f1fe;color:#0b4fa8}.po-alert.g{background:#d6f3e4;color:#0f7b55}
.qa-lines{width:100%;border-collapse:collapse;font-size:.86rem}.qa-lines th{font-size:.74rem;color:#6c757d;font-weight:600;text-align:left;padding:6px 6px}.qa-lines td{padding:5px 4px;border-top:1px solid #eef0f2;vertical-align:top}
.qa-lines input,.qa-lines select{width:100%;border:1px solid #dfe3e8;border-radius:6px;padding:5px 7px;font-size:.86rem}.qa-lines .w-q{width:62px}.qa-lines .w-p{width:100px}.qa-lines .w-t{width:66px}
.qa-toast{position:fixed;left:50%;bottom:28px;transform:translateX(-50%) translateY(20px);z-index:3000;background:#198754;color:#fff;border-radius:10px;padding:12px 20px;box-shadow:0 8px 30px rgba(0,0,0,.25);font-weight:600;max-width:90vw;opacity:0;transition:.25s;pointer-events:none}.qa-toast.on{opacity:1;transform:translateX(-50%)}.qa-toast.err{background:#c0392b}.qa-ev{border-top:1px solid #eef0f2;padding:7px 0;font-size:.86rem}.qa-ev.client b{color:#198754}.qa-ev.vendor b{color:#1572e8}.qa-lines tr.gone{opacity:.4}.qa-lines tr.auto td{background:#f3fbf6}.qa-sr td{background:#f5f9ff;padding:10px}.qa-hit{display:flex;gap:10px;align-items:center;width:100%;text-align:left;border:1px solid #e1e6ec;background:#fff;border-radius:8px;padding:7px 10px;margin-top:5px;cursor:pointer}.qa-hit:hover{border-color:#1572e8;background:#eef5ff}.qa-hit b{font-family:ui-monospace,Menlo,Consolas,monospace}.qa-hit .st{margin-left:auto;font-size:.8rem;white-space:nowrap}.qa-tot{display:flex;justify-content:flex-end;gap:20px;padding:10px 6px;font-size:.92rem}
</style>
<style>
.pr-ed .form-label{font-size:.78rem;font-weight:700;color:#6c757d;margin-bottom:3px}
.pr-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
.pr-grid input,.pr-grid select,.pr-grid textarea{width:100%;border:1px solid #dfe3e8;border-radius:6px;padding:7px 9px;font-size:.9rem}
.pr-grp{display:flex;flex-wrap:wrap;gap:6px 14px;margin-top:6px}.pr-grp label{font-size:.88rem;margin:0;display:flex;gap:6px;align-items:center}
.pr-lines{width:100%;border-collapse:collapse;font-size:.85rem}.pr-lines th{font-size:.73rem;color:#6c757d;font-weight:600;text-align:left;padding:6px 5px;white-space:nowrap}
.pr-lines td{padding:5px 4px;border-top:1px solid #eef0f2;vertical-align:middle}.pr-lines input,.pr-lines select{border:1px solid #dfe3e8;border-radius:6px;padding:5px 7px;font-size:.85rem;width:100%}
.pr-lines .w-v{width:92px}.pr-lines .w-m{width:104px}.pr-lines .w-q{width:80px}
.pr-low{color:#c0392b;font-weight:700}.pr-ok{color:#198754;font-weight:700}
.pr-sr td{background:#f8fbff}.pr-hit{display:flex;justify-content:space-between;gap:10px;padding:7px 10px;border-radius:8px;cursor:pointer;font-size:.88rem;border:0;background:none;width:100%;text-align:left}
.pr-hit:hover{background:#e7f1fe}.pr-hit small{color:#6c757d}
.pn-ov{position:fixed;inset:0;background:rgba(17,22,29,.55);z-index:2000;display:none;align-items:center;justify-content:center;padding:16px}.pn-ov.on{display:flex}
.pn-box{background:#fff;border-radius:14px;width:100%;max-width:760px;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 24px 70px rgba(0,0,0,.35)}
.pn-h{padding:16px 20px;border-bottom:1px solid #eef0f2;display:flex;justify-content:space-between;align-items:center}.pn-b{padding:16px 20px;overflow:auto}.pn-f{padding:14px 20px;border-top:1px solid #eef0f2;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.pn-list{border:1px solid #e3e7ea;border-radius:10px;max-height:260px;overflow:auto}.pn-row{display:flex;align-items:center;gap:10px;padding:8px 12px;border-top:1px solid #f0f2f4;font-size:.9rem;cursor:pointer}.pn-row:first-child{border-top:0}.pn-row:hover{background:#f7fafd}
.pn-row small{color:#6c757d;margin-left:auto;text-align:right}.pn-bar{height:8px;border-radius:99px;background:#e9edf0;overflow:hidden}.pn-bar i{display:block;height:100%;width:0;background:#1572e8;transition:width .25s}
.pn-warn{background:#fff4e0;border:1px solid #f3d29a;color:#8a5a00;border-radius:8px;padding:8px 12px;font-size:.85rem;margin-bottom:10px}.pn-err{background:#fde8e8;border:1px solid #f3b8bd;color:#a12626;border-radius:8px;padding:8px 12px;font-size:.85rem;margin-top:10px}.pn-ok{background:#e3f6ec;border:1px solid #a9dcc0;color:#0d6b3b;border-radius:8px;padding:8px 12px;font-size:.85rem;margin-top:10px}
.po-st.running{background:#d6f3e4;color:#0d6b3b}.po-st.upcoming{background:#e7f1fe;color:#0b4fa8}.po-st.ended{background:#eceff1;color:#546e7a}.po-st.off{background:#fde8e8;color:#a12626}
</style>
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 gap-2">
          <h3 class="fw-bold mb-0"><i class="fas fa-tags me-2 text-info"></i>Promotions portail</h3>
          <button class="btn btn-primary" id="prNew"><i class="fas fa-plus me-1"></i>Nouvelle promotion</button>
        </div>
        <div class="po-tabs" id="prTabs">
          <button class="po-tab on" data-s="running">En cours<b id="cRunning">0</b></button>
          <button class="po-tab" data-s="upcoming">À venir<b id="cUpcoming">0</b></button>
          <button class="po-tab" data-s="ended">Terminées<b id="cEnded">0</b></button>
          <button class="po-tab" data-s="off">Désactivées<b id="cOff">0</b></button>
        </div>
        <div class="po-wrap"><table><thead><tr><th>Promotion</th><th>Période</th><th class="text-end">Articles</th><th>Clients</th><th>Statut</th><th></th></tr></thead><tbody id="prBody"></tbody></table></div>
<div class="pn-ov" id="pnOv" aria-hidden="true"><div class="pn-box" role="dialog" aria-modal="true" aria-labelledby="pnTitle">
  <div class="pn-h"><h5 class="mb-0" id="pnTitle">Informer les clients</h5><button class="btn btn-link text-muted" id="pnX" aria-label="Fermer"><i class="fas fa-times"></i></button></div>
  <div class="pn-b">
    <div id="pnWarn"></div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
      <label class="mb-0" style="display:flex;gap:8px;align-items:center;font-weight:700"><input type="checkbox" id="pnAll"> Informer tous les clients (<span id="pnCount">0</span>)</label>
      <input type="search" id="pnFind" placeholder="Filtrer un client…" style="border:1px solid #dfe3e8;border-radius:6px;padding:5px 10px;font-size:.88rem">
    </div>
    <div class="pn-list" id="pnList"></div>
    <div class="small text-muted mt-1" id="pnSel"></div>
    <div class="mt-3"><label class="form-label small fw-bold text-muted mb-1">Objet de l'e-mail</label><input id="pnSubj" maxlength="150" style="width:100%;border:1px solid #dfe3e8;border-radius:6px;padding:7px 9px"></div>
    <div class="mt-2"><label class="form-label small fw-bold text-muted mb-1">Message (affiché avant le bouton « Voir les promotions »)</label><textarea id="pnMsg" rows="6" maxlength="2000" style="width:100%;border:1px solid #dfe3e8;border-radius:6px;padding:8px 10px"></textarea></div>
    <div id="pnProg" style="display:none" class="mt-3"><div class="pn-bar"><i id="pnBar"></i></div><div class="small text-muted mt-1" id="pnProgTxt"></div></div>
    <div id="pnRes"></div>
  </div>
  <div class="pn-f"><button class="btn btn-primary" id="pnSend"><i class="fas fa-paper-plane me-1"></i>Envoyer</button><button class="btn btn-light" id="pnClose">Plus tard</button><span class="ms-auto small text-muted">La promotion est déjà enregistrée : l'envoi est indépendant.</span></div>
</div></div>
<div class="po-veil" id="poVeil"></div>
<aside class="po-pan pr-ed" id="poPan" aria-hidden="true">
  <div class="po-ph"><h4 class="mb-0" id="poTitle">Promotion</h4><button class="btn btn-link text-muted" id="poClose" aria-label="Fermer"><i class="fas fa-times"></i></button></div>
  <div class="po-pb" id="poDetail">
    <div class="pr-grid">
      <div style="grid-column:1/-1"><label class="form-label">Nom de la promotion</label><input id="fName" maxlength="150" placeholder="Ex. Promo freinage novembre"></div>
      <div><label class="form-label">Début</label><input id="fStart" type="datetime-local"></div>
      <div><label class="form-label">Fin</label><input id="fEnd" type="datetime-local"></div>
      <div><label class="form-label">Clients concernés</label><select id="fAud"><option value="all">Tous les clients</option><option value="groups">Certains groupes remise</option></select></div>
      <div style="display:flex;align-items:flex-end"><label style="margin:0 0 8px;display:flex;gap:8px;align-items:center"><input type="checkbox" id="fActive" style="width:auto" checked> Promotion active</label></div>
    </div>
    <div id="fGroupsBox" class="po-box mt-3" style="display:none"><b>Groupes remise concernés</b>
      <div class="pr-grp">@foreach($groups as $g)<label><input type="checkbox" class="fGroup" value="{{ $g->id }}"> {{ $g->name }}</label>@endforeach @if(!count($groups))<span class="text-muted">Aucun groupe client défini.</span>@endif</div></div>
    <div class="mt-3"><label class="form-label">Description (affichée aux clients, facultatif)</label><input id="fDesc" maxlength="1000" style="width:100%;border:1px solid #dfe3e8;border-radius:6px;padding:7px 9px"></div>
    <div class="po-alert i mt-3 mb-2">Le client paie toujours <b>le prix le plus bas</b> entre la promo et son prix habituel (tarif moins remise de son groupe). Les deux ne se cumulent pas.</div>
    <div class="d-flex justify-content-between align-items-center mt-2 mb-1"><b>Articles en promotion</b>
      <span><a class="btn btn-sm btn-outline-success" id="prTplBtn" href="{{ route('b2b.promotions.template') }}"><i class="fas fa-download me-1"></i>Modèle Excel</a> <button class="btn btn-sm btn-outline-secondary" id="prImpBtn"><i class="fas fa-file-excel me-1"></i>Importer Excel</button> <button class="btn btn-sm btn-outline-secondary" id="prPasteBtn"><i class="fas fa-paste me-1"></i>Coller une liste</button> <button class="btn btn-sm btn-outline-primary" id="prAddBtn"><i class="fas fa-search me-1"></i>Ajouter un article</button></span></div>
    <input type="file" id="prFile" accept=".xlsx,.xls,.csv,.txt" style="display:none">
    <div id="prImp" class="po-box" style="display:none"></div>
    <div id="prBulk" style="display:none;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:8px;font-size:.85rem"><span class="text-muted">Appliquer à toutes les lignes :</span>
      <select id="bkMode" style="border:1px solid #dfe3e8;border-radius:6px;padding:4px 6px"><option value="percent">% remise</option><option value="amount">Remise € HT</option><option value="price">Prix HT</option></select>
      <input id="bkVal" type="number" min="0" step="0.01" style="width:90px;border:1px solid #dfe3e8;border-radius:6px;padding:4px 6px" placeholder="valeur">
      <button class="btn btn-sm btn-outline-primary" id="bkGo" type="button">Appliquer</button><span class="text-muted">Le prix HT fixe ne convient qu'à un article ; pour plusieurs articles, utilisez le % de remise.</span></div>
    <div id="prPaste" class="po-box" style="display:none"><div class="small text-muted mb-1">Une ligne par article : « référence » ou « référence prix promo HT ». Les références doivent correspondre exactement à un article.</div>
      <textarea id="prPasteTxt" rows="5" style="width:100%;border:1px solid #dfe3e8;border-radius:6px;padding:7px" placeholder="BP1234 12,50&#10;DF5678"></textarea>
      <div class="mt-2"><button class="btn btn-sm btn-primary" id="prPasteGo">Ajouter ces articles</button> <span id="prPasteRes" class="small ms-2"></span></div></div>
    <div style="overflow-x:auto"><table class="pr-lines"><thead><tr><th>Code</th><th>Désignation</th><th class="text-end">Tarif HT</th><th class="text-end">Coût net</th><th>Type</th><th>Valeur</th><th class="text-end">Prix promo</th><th class="text-end">Marge</th><th>Qté max</th><th></th></tr></thead><tbody id="prLines"></tbody></table></div>
    <div id="prEmpty" class="text-muted text-center py-3">Aucun article. Cherchez un article ou collez une liste.</div>
  </div>
  <div class="po-pf"><button class="btn btn-primary" id="prSave"><i class="fas fa-check me-1"></i>Enregistrer</button><button class="btn btn-outline-primary" id="prNotify" style="display:none"><i class="fas fa-envelope me-1"></i>Informer les clients</button><button class="btn btn-light" id="prCancel">Fermer</button><span class="ms-auto small text-muted" id="prHint"></span></div>
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
    var MIN = {!! json_encode($minMargin) !!};
    var URL = { data: {!! json_encode(route('b2b.promotions.data')) !!}, base: {!! json_encode(url('/b2b/promotions')) !!}, items: {!! json_encode(route('b2b.promotions.items')) !!}, resolve: {!! json_encode(route('b2b.promotions.resolve')) !!}, create: {!! json_encode(route('b2b.promotions.create')) !!}, imp: {!! json_encode(route('b2b.promotions.import')) !!}, tpl: {!! json_encode(route('b2b.promotions.template')) !!} };
    var $ = function (id) { return document.getElementById(id); };
    var esc = function (v) { return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
    var fmt = function (n) { return Number(n || 0).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
    var st = 'running', curId = null, toastT = null;
    function toast(text, err) {
        var t = $('qaToast'); if (!t) { t = document.createElement('div'); t.id = 'qaToast'; t.className = 'qa-toast'; t.setAttribute('role', 'status'); document.body.appendChild(t); }
        t.textContent = text; t.classList.toggle('err', !!err); void t.offsetWidth; t.classList.add('on');
        clearTimeout(toastT); toastT = setTimeout(function () { t.classList.remove('on'); }, err ? 6000 : 4000);
    }
    function api(url, method, body) {
        return fetch(url, {method: method || 'GET', credentials: 'same-origin', headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest'}, body: body ? JSON.stringify(body) : undefined})
            .then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { j.__ok = r.ok; if (!r.ok && !j.message) j.message = j.errors ? Object.keys(j.errors).map(function (k) { return j.errors[k][0]; }).join(' ') : 'Erreur ' + r.status; return j; }); });
    }
    function load() {
        api(URL.data + '?status=' + st).then(function (j) {
            if (!j.rows) return;
            $('cRunning').textContent = j.counts.running; $('cUpcoming').textContent = j.counts.upcoming; $('cEnded').textContent = j.counts.ended; $('cOff').textContent = j.counts.off;
            $('prBody').innerHTML = j.rows.length ? j.rows.map(function (r) {
                return '<tr data-id="' + r.id + '"><td><b>' + esc(r.name) + '</b></td><td class="small">' + esc(r.starts) + '<br>→ ' + esc(r.ends) + '</td><td class="text-end">' + r.items + '</td><td class="small">' + esc(r.audience) + '</td>' +
                    '<td><span class="po-st ' + esc(r.state) + '">' + esc(r.state_label) + '</span></td>' +
                    '<td class="text-end" style="white-space:nowrap"><button class="btn btn-sm btn-outline-secondary" data-act="toggle" data-id="' + r.id + '">' + (r.state === 'off' ? 'Activer' : 'Désactiver') + '</button> <button class="btn btn-sm btn-outline-danger" data-act="del" data-id="' + r.id + '" aria-label="Supprimer"><i class="fas fa-trash"></i></button></td></tr>';
            }).join('') : '<tr><td colspan="6" class="text-center text-muted py-4">Aucune promotion ici.</td></tr>';
        });
    }
    document.getElementById('prTabs').addEventListener('click', function (e) { var b = e.target.closest('.po-tab'); if (!b) return; st = b.getAttribute('data-s'); [].forEach.call(document.querySelectorAll('#prTabs .po-tab'), function (x) { x.classList.toggle('on', x === b); }); load(); });
    $('prBody').addEventListener('click', function (e) {
        var a = e.target.closest('[data-act]');
        if (a) {
            e.stopPropagation(); var id = a.getAttribute('data-id');
            if (a.getAttribute('data-act') === 'toggle') api(URL.base + '/' + id + '/toggle', 'POST').then(function (j) { toast(j.message, !j.__ok); load(); });
            else if (confirm('Supprimer cette promotion ? Les prix reviendront à la normale immédiatement.')) api(URL.base + '/' + id + '/delete', 'POST').then(function (j) { toast(j.message, !j.__ok); load(); });
            return;
        }
        var tr = e.target.closest('tr[data-id]'); if (tr) openEd(tr.getAttribute('data-id'));
    });

    // ───── panneau d'édition ─────
    function openPan() { $('poVeil').classList.add('on'); $('poPan').classList.add('on'); $('poPan').setAttribute('aria-hidden', 'false'); }
    function closePan() { $('poVeil').classList.remove('on'); $('poPan').classList.remove('on'); $('poPan').setAttribute('aria-hidden', 'true'); }
    $('poClose').onclick = closePan; $('prCancel').onclick = closePan; $('poVeil').onclick = closePan;
    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function dtLocal(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes()); }
    function fillForm(p) {
        $('fName').value = p.name || ''; $('fDesc').value = p.description || ''; $('fStart').value = p.starts_at; $('fEnd').value = p.ends_at;
        $('fAud').value = p.audience || 'all'; $('fActive').checked = p.active !== false;
        [].forEach.call(document.querySelectorAll('.fGroup'), function (c) { c.checked = (p.group_ids || []).indexOf(parseInt(c.value, 10)) >= 0; });
        $('fGroupsBox').style.display = $('fAud').value === 'groups' ? '' : 'none';
        $('prLines').innerHTML = ''; (p.items || []).forEach(addLine); refresh();
    }
    function openEd(id) {
        curId = id || null; $('prPaste').style.display = 'none';
        if (!id) {
            var s = new Date(); s.setMinutes(0, 0, 0); var e = new Date(s.getTime() + 14 * 86400000); e.setHours(23, 59, 0, 0);
            $('prNotify').style.display = 'none'; $('poTitle').textContent = 'Nouvelle promotion'; fillForm({name: '', starts_at: dtLocal(s), ends_at: dtLocal(e), audience: 'all', active: true, items: []}); openPan(); return;
        }
        $('prNotify').style.display = id ? '' : 'none';
        api(URL.base + '/' + id).then(function (p) { if (!p.items) { toast(p.message || 'Chargement impossible.', true); return; } $('poTitle').textContent = p.name; fillForm(p); openPan(); });
    }
    $('prNew').onclick = function () { openEd(null); };
    $('fAud').onchange = function () { $('fGroupsBox').style.display = this.value === 'groups' ? '' : 'none'; };

    function addLine(it) {
        if (document.querySelector('#prLines tr[data-item="' + it.item_id + '"]')) { toast('Cet article est déjà dans la promotion.', true); return; }
        var tr = document.createElement('tr'); tr.setAttribute('data-item', it.item_id); tr.setAttribute('data-sale', it.sale_price); tr.setAttribute('data-cost', it.cost_net);
        var mode = it.mode || 'price', val = mode === 'percent' ? it.percent : (mode === 'amount' ? it.amount : it.promo_price);
        if (val == null && it.price_hint) { mode = 'price'; val = it.price_hint; }
        tr.innerHTML = '<td><b>' + esc(it.code) + '</b></td><td>' + esc(it.name) + (it.brand ? '<div class="small text-muted">' + esc(it.brand) + '</div>' : '') + '</td>' +
            '<td class="text-end">' + fmt(it.sale_price) + '</td><td class="text-end text-muted">' + (it.cost_net > 0 ? fmt(it.cost_net) : '—') + '</td>' +
            '<td><select class="l-mode w-m"><option value="price"' + (mode === 'price' ? ' selected' : '') + '>Prix HT</option><option value="percent"' + (mode === 'percent' ? ' selected' : '') + '>% remise</option><option value="amount"' + (mode === 'amount' ? ' selected' : '') + '>Remise € HT</option></select></td>' +
            '<td><input class="l-val w-v" type="number" min="0" step="0.01" value="' + (val == null ? '' : val) + '"></td>' +
            '<td class="text-end"><b class="l-price">—</b></td><td class="text-end l-mar">—</td>' +
            '<td><input class="l-max w-q" type="number" min="1" step="1" placeholder="illimité" value="' + (it.max_qty || '') + '">' + (it.sold_qty ? '<div class="small text-muted">' + it.sold_qty + ' vendu(s)</div>' : '') + '</td>' +
            '<td><button class="btn btn-sm btn-link text-danger l-del" aria-label="Retirer"><i class="fas fa-times"></i></button></td>';
        $('prLines').appendChild(tr);
    }
    function lineData(tr) {
        var sale = parseFloat(tr.getAttribute('data-sale')) || 0, cost = parseFloat(tr.getAttribute('data-cost')) || 0, mode = tr.querySelector('.l-mode').value, v = parseFloat(tr.querySelector('.l-val').value);
        var price = isNaN(v) ? null : (mode === 'percent' ? Math.round(sale * (1 - v / 100) * 100) / 100 : (mode === 'amount' ? Math.round((sale - v) * 100) / 100 : v));
        return {sale: sale, cost: cost, mode: mode, v: isNaN(v) ? null : v, price: price};
    }
    function refresh() {
        var low = 0;
        [].forEach.call(document.querySelectorAll('#prLines tr[data-item]'), function (tr) {
            var d = lineData(tr), pe = tr.querySelector('.l-price'), me = tr.querySelector('.l-mar');
            pe.textContent = d.price == null ? '—' : fmt(d.price);
            if (d.price != null && d.sale > 0 && d.price >= d.sale) { pe.className = 'l-price pr-low'; pe.title = 'Le prix promo doit être inférieur au tarif : cette ligne sera ignorée.'; } else { pe.className = 'l-price'; pe.title = ''; }
            if (d.price != null && d.cost > 0) {
                var m = (d.price - d.cost) / d.cost * 100, bad = MIN !== null && m < MIN;
                me.textContent = m.toFixed(1).replace('.', ',') + ' %'; me.className = 'text-end l-mar ' + (bad ? 'pr-low' : 'pr-ok'); me.title = bad ? 'Sous la marge minimale du portail (' + MIN + ' %)' : '';
                if (bad) low++;
            } else { me.textContent = '—'; me.className = 'text-end l-mar'; }
        });
        var n = document.querySelectorAll('#prLines tr[data-item]').length;
        $('prEmpty').style.display = n ? 'none' : ''; $('prBulk').style.display = n > 1 ? 'flex' : 'none';
        $('prHint').innerHTML = n + ' article(s)' + (low ? ' · <span class="pr-low">' + low + ' sous la marge minimale (autorisé)</span>' : '');
    }
    $('poPan').addEventListener('input', function (e) { if (e.target.closest('#prLines')) refresh(); });
    $('poPan').addEventListener('change', function (e) { if (e.target.closest('#prLines')) refresh(); });
    $('prLines').addEventListener('click', function (e) { var d = e.target.closest('.l-del'); if (d) { d.closest('tr').remove(); refresh(); } });

    // recherche d'article
    $('prAddBtn').onclick = function () {
        if (document.querySelector('.pr-sr')) { document.querySelector('.pr-sr input').focus(); return; }
        var tr = document.createElement('tr'); tr.className = 'pr-sr'; tr.innerHTML = '<td colspan="10"><input type="search" placeholder="Code, désignation, code-barres…" style="width:100%"><div class="res"></div></td>';
        $('prLines').insertBefore(tr, $('prLines').firstChild);
        var inp = tr.querySelector('input'), res = tr.querySelector('.res'), rows = [], t = null;
        function run() { var v = inp.value.trim(); if (v.length < 2) { res.innerHTML = ''; return; }
            api(URL.items + '?q=' + encodeURIComponent(v)).then(function (j) { rows = j.rows || []; res.innerHTML = rows.length ? rows.map(function (r, i) { return '<button type="button" class="pr-hit" data-i="' + i + '"><span><b>' + esc(r.code) + '</b> ' + esc(r.name) + (r.brand ? ' <small>' + esc(r.brand) + '</small>' : '') + '</span><span>' + fmt(r.sale_price) + ' € HT</span></button>'; }).join('') : '<div class="small text-muted p-2">Aucun article.</div>'; }); }
        inp.addEventListener('input', function () { clearTimeout(t); t = setTimeout(run, 250); });
        res.addEventListener('click', function (e) { var b = e.target.closest('.pr-hit'); if (!b) return; addLine(rows[parseInt(b.getAttribute('data-i'), 10)]); tr.remove(); refresh(); });
        inp.addEventListener('keydown', function (e) { if (e.key === 'Escape') tr.remove(); });
        inp.focus();
    };
    // ───── import Excel / CSV avec aperçu ─────
    var impRows = [];
    $('prImpBtn').onclick = function () { $('prFile').value = ''; $('prFile').click(); };
    $('prFile').onchange = function () {
        var f = this.files[0]; if (!f) return;
        var fd = new FormData(); fd.append('file', f);
        $('prImp').style.display = ''; $('prImp').innerHTML = '<span class="text-muted">Lecture du fichier…</span>';
        fetch(URL.imp, {method: 'POST', credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest'}, body: fd})
            .then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { j.__ok = r.ok; if (!r.ok && !j.message) j.message = j.errors ? Object.keys(j.errors).map(function (k) { return j.errors[k][0]; }).join(' ') : 'Erreur ' + r.status; return j; }); })
            .then(function (j) {
                if (!j.__ok) { $('prImp').innerHTML = '<span class="pr-low">' + esc(j.message) + '</span> <button class="btn btn-sm btn-link" id="impX">Fermer</button>'; $('impX').onclick = function () { $('prImp').style.display = 'none'; }; return; }
                impRows = j.rows || [];
                var valid = impRows.filter(function (x) { return x.status !== 'error'; }).length;
                var h = '<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"><b>Aperçu de l\'import</b><span class="small"><span class="pr-ok">' + valid + ' ligne(s) valide(s)</span>' + (j.bad ? ' · <span class="pr-low">' + j.bad + ' en erreur (ignorées)</span>' : '') + (j.header ? '' : ' · <span class="text-muted">pas d\'en-tête détecté : colonne A = référence, B = prix</span>') + '</span></div>';
                h += '<div style="max-height:260px;overflow:auto"><table class="pr-lines"><thead><tr><th>Ligne</th><th>Référence</th><th>Désignation</th><th>Valeur</th><th class="text-end">Prix promo</th><th>Contrôle</th></tr></thead><tbody>' + impRows.map(function (x) {
                    var it = x.item, cls = x.status === 'error' ? 'pr-low' : (x.status === 'warn' ? 'text-warning' : 'pr-ok');
                    return '<tr><td>' + x.line + '</td><td><b>' + esc(x.ref) + '</b></td><td>' + (it ? esc(it.name) : '—') + '</td><td>' + (it ? (it.mode === 'percent' ? it.percent + ' %' : (it.mode === 'amount' ? '−' + fmt(it.amount) + ' €' : fmt(it.promo_price) + ' €')) + (it.max_qty ? ' · max ' + it.max_qty : '') : '—') + '</td><td class="text-end">' + (x.price != null ? fmt(x.price) : '—') + '</td><td class="' + cls + '">' + (x.status === 'ok' && !x.message ? 'OK' : esc(x.message)) + '</td></tr>';
                }).join('') + '</tbody></table></div>';
                h += '<div class="mt-2"><button class="btn btn-sm btn-primary" id="impGo"' + (valid ? '' : ' disabled') + '>Ajouter les ' + valid + ' ligne(s) valide(s)</button> <button class="btn btn-sm btn-light" id="impNo">Annuler</button> <a class="small ms-2" href="' + URL.tpl + '">Télécharger le modèle</a></div>';
                $('prImp').innerHTML = h;
                $('impNo').onclick = function () { $('prImp').style.display = 'none'; impRows = []; };
                $('impGo').onclick = function () {
                    var added = 0, skipped = 0;
                    impRows.forEach(function (x) { if (x.status === 'error' || !x.item) return; if (document.querySelector('#prLines tr[data-item="' + x.item.item_id + '"]')) { skipped++; return; } addLine(x.item); added++; });
                    refresh(); $('prImp').style.display = 'none'; impRows = [];
                    toast(added + ' article(s) ajouté(s) à la promotion' + (skipped ? ' · ' + skipped + ' déjà présent(s)' : '') + '. Pensez à enregistrer.');
                };
            });
    };
    $('bkGo').onclick = function () {
        var v = parseFloat($('bkVal').value); if (isNaN(v) || v < 0) { toast('Indiquez une valeur.', true); return; }
        [].forEach.call(document.querySelectorAll('#prLines tr[data-item]'), function (tr) { tr.querySelector('.l-mode').value = $('bkMode').value; tr.querySelector('.l-val').value = v; });
        refresh();
    };
    $('prPasteBtn').onclick = function () { var b = $('prPaste'); b.style.display = b.style.display === 'none' ? '' : 'none'; };
    $('prPasteGo').onclick = function () {
        api(URL.resolve, 'POST', {text: $('prPasteTxt').value}).then(function (j) {
            (j.found || []).forEach(addLine); refresh();
            var msg = (j.found || []).length + ' ajouté(s)'; if ((j.dup || []).length) msg += ' · ' + j.dup.length + ' doublon(s)';
            $('prPasteRes').innerHTML = esc(msg) + ((j.missing || []).length ? ' · <span class="pr-low">Introuvables : ' + esc(j.missing.join(', ')) + '</span>' : '');
            if (!(j.missing || []).length) $('prPasteTxt').value = '';
        });
    };

    // ───── informer les clients par e-mail (indépendant de l'enregistrement) ─────
    var pn = {rows: [], busy: false};
    function pnOpen(id) {
        $('pnOv').classList.add('on'); $('pnOv').setAttribute('aria-hidden', 'false'); $('pnRes').innerHTML = ''; $('pnProg').style.display = 'none'; $('pnWarn').innerHTML = '';
        $('pnList').innerHTML = '<div class="p-3 text-muted">Chargement…</div>'; $('pnSend').disabled = true;
        api(URL.base + '/' + id + '/recipients').then(function (j) {
            if (!j.recipients) { $('pnList').innerHTML = '<div class="p-3 pr-low">' + esc(j.message || 'Chargement impossible.') + '</div>'; return; }
            pn.rows = j.recipients; $('pnSubj').value = j.subject; $('pnMsg').value = j.message; $('pnCount').textContent = j.recipients.length;
            if (!j.mail_ready) $('pnWarn').innerHTML = '<div class="pn-warn">Les e-mails ne sont pas configurés sur ce serveur (MAIL_MAILER = log ou array) : aucun message ne partira réellement.</div>';
            $('pnList').innerHTML = j.recipients.length ? j.recipients.map(function (c) {
                return '<label class="pn-row" data-name="' + esc(c.name.toLowerCase()) + '"><input type="checkbox" class="pn-c" value="' + c.customer_id + '"' + (c.informed ? '' : ' checked') + '> <b>' + esc(c.name) + '</b><small>' + esc(c.emails.join(', ')) + (c.informed ? '<br><span class="text-success">déjà informé le ' + esc(c.informed) + '</span>' : '') + '</small></label>';
            }).join('') : '<div class="p-3 text-muted">Aucun client avec un accès portail actif et une adresse e-mail pour ce public.</div>';
            pnSync(); curNotifyId = id;
        });
    }
    var curNotifyId = null;
    function pnBoxes() { return [].slice.call(document.querySelectorAll('.pn-c')); }
    function pnSync() { var b = pnBoxes(), n = b.filter(function (x) { return x.checked; }).length; $('pnAll').checked = b.length > 0 && n === b.length; $('pnSel').textContent = n + ' client(s) sélectionné(s)'; $('pnSend').disabled = n === 0 || pn.busy; }
    function pnClose() { if (pn.busy && !confirm('Un envoi est en cours. Fermer quand même ?')) return; $('pnOv').classList.remove('on'); $('pnOv').setAttribute('aria-hidden', 'true'); }
    $('pnX').onclick = pnClose; $('pnClose').onclick = pnClose;
    $('pnAll').onchange = function () { var v = this.checked; pnBoxes().forEach(function (b) { b.checked = v; }); pnSync(); };
    $('pnList').addEventListener('change', pnSync);
    $('pnFind').oninput = function () { var q = this.value.trim().toLowerCase(); [].forEach.call(document.querySelectorAll('.pn-row'), function (r) { r.style.display = !q || r.getAttribute('data-name').indexOf(q) >= 0 ? '' : 'none'; }); };
    $('prNotify').onclick = function () { if (curId) pnOpen(curId); };
    $('pnSend').onclick = function () {
        var ids = pnBoxes().filter(function (x) { return x.checked; }).map(function (x) { return parseInt(x.value, 10); });
        if (!ids.length || pn.busy) return;
        if (!$('pnSubj').value.trim() || !$('pnMsg').value.trim()) { toast('Renseignez l\'objet et le message.', true); return; }
        pn.busy = true; $('pnSend').disabled = true; $('pnRes').innerHTML = ''; $('pnProg').style.display = ''; $('pnBar').style.width = '0';
        var sent = 0, failed = 0, errs = [], stopped = false, stopMsg = '', i = 0, total = ids.length, B = 5;
        function next() {
            if (i >= total || stopped) return finish();
            var chunk = ids.slice(i, i + B); i += B;
            $('pnProgTxt').textContent = 'Envoi en cours… ' + Math.min(i, total) + ' / ' + total;
            api(URL.base + '/' + curNotifyId + '/notify', 'POST', {customer_ids: chunk, subject: $('pnSubj').value, message: $('pnMsg').value}).then(function (j) {
                sent += j.sent || 0; failed += j.failed || 0; (j.errors || []).forEach(function (e) { errs.push(e); });
                if (j.stopped) { stopped = true; stopMsg = j.message || ''; }
                if (j.ok === false && !j.stopped) { failed += chunk.length; }
                $('pnBar').style.width = Math.round(Math.min(i, total) / total * 100) + '%'; next();
            }).catch(function () { stopped = true; stopMsg = 'Connexion perdue pendant l\'envoi.'; finish(); });
        }
        function finish() {
            pn.busy = false; $('pnProg').style.display = 'none'; pnSync();
            var notSent = Math.max(0, total - sent - failed);
            var h = '<div class="' + (sent ? 'pn-ok' : 'pn-err') + '"><b>' + sent + ' client(s) informé(s)</b>' + (failed ? ' · ' + failed + ' échec(s)' : '') + (stopped && notSent ? ' · ' + notSent + ' non envoyé(s)' : '') + '</div>';
            if (stopped) h += '<div class="pn-err">' + esc(stopMsg || 'Envoi interrompu.') + ' Votre promotion reste enregistrée. Vous pourrez relancer l\'envoi plus tard avec « Informer les clients ».</div>';
            if (errs.length) h += '<div class="pn-err"><b>Non envoyés :</b><br>' + errs.slice(0, 10).map(function (e) { return esc(e.customer) + (e.error ? ' — ' + esc(e.error) : ''); }).join('<br>') + (errs.length > 10 ? '<br>…' : '') + '</div>';
            $('pnRes').innerHTML = h;
        }
        next();
    };

    $('prSave').onclick = function () {
        var items = [].map.call(document.querySelectorAll('#prLines tr[data-item]'), function (tr) {
            var d = lineData(tr), mx = parseInt(tr.querySelector('.l-max').value, 10);
            return {item_id: parseInt(tr.getAttribute('data-item'), 10), mode: d.mode, promo_price: d.mode === 'price' ? d.v : null, percent: d.mode === 'percent' ? d.v : null, amount: d.mode === 'amount' ? d.v : null, max_qty: isNaN(mx) ? null : mx};
        });
        var body = {name: $('fName').value, description: $('fDesc').value, starts_at: $('fStart').value, ends_at: $('fEnd').value, active: $('fActive').checked, audience: $('fAud').value,
            group_ids: [].filter.call(document.querySelectorAll('.fGroup'), function (c) { return c.checked; }).map(function (c) { return parseInt(c.value, 10); }), items: items};
        var btn = $('prSave'); btn.disabled = true;
        api(curId ? URL.base + '/' + curId + '/save' : URL.create, 'POST', body).then(function (j) {
            btn.disabled = false; toast(j.message, !j.__ok);
            if (j.__ok) { var wasNew = !curId; curId = j.id; $('prNotify').style.display = ''; closePan(); load(); if (wasNew && $('fActive').checked) setTimeout(function () { pnOpen(curId); }, 350); }
        });
    };
    load();
})();
</script>

</body>
</html>
