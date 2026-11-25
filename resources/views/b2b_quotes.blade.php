<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Demandes de devis — AZ ERP</title>
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
.qa-files{display:flex;flex-wrap:wrap;gap:8px;margin-top:8px}.qa-fl{display:flex;flex-direction:column;align-items:center;gap:3px;width:92px;font-size:.7rem;text-align:center;color:#495057;text-decoration:none;position:relative}.qa-fl .th{width:92px;height:70px;border-radius:8px;border:1px solid #dfe3e8;background:#f4f6f8 center/cover no-repeat;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#c0392b}.qa-fl.v .th{border-color:#7fb3f5}.qa-fl .x{position:absolute;top:-6px;right:0;background:#fff;border:1px solid #dfe3e8;border-radius:50%;width:20px;height:20px;line-height:17px;font-size:.8rem;padding:0;cursor:pointer;color:#c0392b}.qa-tools{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:8px;font-size:.82rem}.qa-chips{display:flex;flex-wrap:wrap;gap:6px;margin-top:6px}.qa-chip{border:1px solid #dfe3e8;background:#fff;border-radius:99px;padding:2px 10px;font-size:.76rem;cursor:pointer;color:#495057}.qa-chip:hover{background:#e7f1fe;border-color:#7fb3f5}.qa-ref{opacity:.55}.qa-ref td:first-child b{text-decoration:line-through}.qa-toast{position:fixed;left:50%;bottom:28px;transform:translateX(-50%) translateY(20px);z-index:3000;background:#198754;color:#fff;border-radius:10px;padding:12px 20px;box-shadow:0 8px 30px rgba(0,0,0,.25);font-weight:600;max-width:90vw;opacity:0;transition:.25s;pointer-events:none}.qa-toast.on{opacity:1;transform:translateX(-50%)}.qa-toast.err{background:#c0392b}.qa-ev{border-top:1px solid #eef0f2;padding:7px 0;font-size:.86rem}.qa-ev.client b{color:#198754}.qa-ev.vendor b{color:#1572e8}.qa-lines tr.gone{opacity:.4}.qa-lines tr.auto td{background:#f3fbf6}.qa-sr td{background:#f5f9ff;padding:10px}.qa-hit{display:flex;gap:10px;align-items:center;width:100%;text-align:left;border:1px solid #e1e6ec;background:#fff;border-radius:8px;padding:7px 10px;margin-top:5px;cursor:pointer}.qa-hit:hover{border-color:#1572e8;background:#eef5ff}.qa-hit b{font-family:ui-monospace,Menlo,Consolas,monospace}.qa-hit .st{margin-left:auto;font-size:.8rem;white-space:nowrap}.qa-tot{display:flex;justify-content:flex-end;gap:20px;padding:10px 6px;font-size:.92rem}
</style>
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 gap-2">
          <h3 class="fw-bold mb-0"><i class="fas fa-file-invoice me-2 text-info"></i>Demandes de devis</h3>
          <input type="search" id="qSearch" class="form-control" style="max-width:320px" placeholder="Client, n° de devis, véhicule…">
        </div>
        <div class="po-tabs" id="qTabs">
          <button class="po-tab on hot" data-s="todo">À traiter<b id="cTodo">0</b></button>
          <button class="po-tab" data-s="answered">Devis envoyés<b id="cAnswered">0</b></button>
          <button class="po-tab" data-s="accepted">Acceptés<b id="cAccepted">0</b></button>
          <button class="po-tab" data-s="declined">Refusés<b id="cDeclined">0</b></button>
          <button class="po-tab" data-s="cancelled">Annulés<b id="cCancelled">0</b></button>
        </div>
        <div class="po-wrap"><table><thead><tr><th>Devis</th><th>Client</th><th>Véhicule</th><th class="text-end">Pièces</th><th class="text-end">Total TTC</th><th>Reçu</th><th>Statut</th></tr></thead><tbody id="qBody"><tr><td colspan="7" class="text-center text-muted py-4">Chargement…</td></tr></tbody></table></div>
        <div class="d-flex justify-content-between align-items-center mt-3"><span id="qPageInfo" class="text-muted small"></span><div><button class="btn btn-sm btn-outline-secondary" id="qPrev">Précédent</button> <button class="btn btn-sm btn-outline-secondary" id="qNext">Suivant</button></div></div>
<div class="po-veil" id="poVeil"></div>
<aside class="po-pan" id="poPan" aria-hidden="true">
  <div class="po-ph"><h4 class="mb-0" id="poTitle">Devis</h4><button class="btn btn-link text-muted" id="poClose" aria-label="Fermer"><i class="fas fa-times"></i></button></div>
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
    var URL = { data: {!! json_encode(route('b2b.quotes.data')) !!}, show: {!! json_encode(url('/b2b/quotes')) !!}, items: {!! json_encode(route('b2b.quotes.items')) !!} };
    var $ = function (id) { return document.getElementById(id); };
    var esc = function (v) { return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
    var fmt = function (n) { return Number(n || 0).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
    var st = 'todo', page = 1, q = '', cur = null, timer = null, toastT = null;
    function toast(text, err) {
        var t = $('qaToast'); if (!t) { t = document.createElement('div'); t.id = 'qaToast'; t.className = 'qa-toast'; t.setAttribute('role', 'status'); document.body.appendChild(t); }
        t.textContent = text; t.classList.toggle('err', !!err); void t.offsetWidth; t.classList.add('on');
        clearTimeout(toastT); toastT = setTimeout(function () { t.classList.remove('on'); }, err ? 6000 : 4500);
    }
    function evHtml(d) {
        if (!d.events || !d.events.length) return '';
        return '<div class="po-box mt-3"><b>Historique</b>' + d.events.slice().reverse().map(function (e) {
            return '<div class="qa-ev ' + esc(e.actor) + '"><b>' + esc(e.label) + '</b> <span class="text-muted">' + esc(e.at) + (e.who ? ' · ' + esc(e.who) : '') + '</span>' + (e.message ? '<div style="white-space:pre-line">' + esc(e.message) + '</div>' : '') + '</div>';
        }).join('') + '</div>';
    }

    function api(url, method, body) {
        return fetch(url, {method: method || 'GET', credentials: 'same-origin', headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest'}, body: body ? JSON.stringify(body) : undefined})
            .then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { j.__ok = r.ok; if (!r.ok && !j.message) j.message = (j.errors ? Object.keys(j.errors).map(function (k) { return j.errors[k][0]; })[0] : 'Erreur ' + r.status); return j; }); });
    }
    function load() {
        api(URL.data + '?status=' + st + '&page=' + page + '&q=' + encodeURIComponent(q)).then(function (j) {
            if (!j.rows) return;
            $('cTodo').textContent = j.counts.todo; $('cAnswered').textContent = j.counts.answered; $('cAccepted').textContent = j.counts.accepted; $('cDeclined').textContent = j.counts.declined; $('cCancelled').textContent = j.counts.cancelled;
            $('qBody').innerHTML = j.rows.length ? j.rows.map(function (r) {
                return '<tr data-id="' + r.id + '"><td class="po-n">' + esc(r.number) + '</td><td><b>' + esc(r.customer) + '</b><div class="small text-muted">' + esc(r.customer_code || '') + '</div></td><td>' + esc(r.vehicle || '—') + '</td><td class="text-end">' + r.lines + '</td><td class="text-end">' + (r.status === 'answered' || r.status === 'accepted' ? fmt(r.total_ttc) + ' €' : '—') + '</td><td>' + esc(r.created) + '<div class="small text-muted">' + esc(r.ago) + '</div></td><td><span class="po-st ' + esc(r.status) + '">' + esc(r.status_label) + '</span></td></tr>';
            }).join('') : '<tr><td colspan="7" class="text-center text-muted py-4">Aucune demande ici.</td></tr>';
            $('qPageInfo').textContent = j.total + ' demande(s) · page ' + j.page + '/' + j.last;
            $('qPrev').disabled = j.page <= 1; $('qNext').disabled = j.page >= j.last;
        });
    }
    $('qTabs').addEventListener('click', function (e) { var b = e.target.closest('.po-tab'); if (!b) return; st = b.getAttribute('data-s'); page = 1; document.querySelectorAll('.po-tab').forEach(function (x) { x.classList.toggle('on', x === b); }); load(); });
    $('qSearch').addEventListener('input', function () { clearTimeout(timer); var v = this.value; timer = setTimeout(function () { q = v; page = 1; load(); }, 300); });
    $('qPrev').addEventListener('click', function () { if (page > 1) { page--; load(); } });
    $('qNext').addEventListener('click', function () { page++; load(); });
    $('qBody').addEventListener('click', function (e) { var tr = e.target.closest('tr[data-id]'); if (tr) open(tr.getAttribute('data-id')); });

    function closePan() { $('poPan').classList.remove('on'); $('poVeil').classList.remove('on'); cur = null; }
    $('poClose').addEventListener('click', closePan); $('poVeil').addEventListener('click', closePan);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePan(); });

    function lineRow(l) {
        var auto = false;
        if (l.auto && l.unit_ht == null) {
            var a = l.auto; auto = true;
            l = Object.assign({}, l, {unit_ht: a.unit_ht, list_ht: a.list_ht, remise: a.remise, tva_rate: a.tva_rate != null ? a.tva_rate : l.tva_rate, availability: a.availability, vendor_ref: a.code, item_id: a.item_id, _label: a.code + ' · stock ' + a.stock});
        }
        var av = l.availability || '';
        return '<tr class="' + (auto ? 'auto' : '') + '" data-id="' + (l.id || '') + '" data-item="' + (l.item_id || '') + '"><td><input class="f-ref" value="' + esc(l.reference) + '" placeholder="Réf. demandée"><input class="f-vref" value="' + esc(l.vendor_ref) + '" placeholder="Réf. proposée" style="margin-top:4px"></td>' +
            '<td><input class="f-des" value="' + esc(l.designation) + '"><input class="f-brand" value="' + esc(l.brand) + '" placeholder="Marque" style="margin-top:4px"><div class="small mt-1"><button type="button" class="btn btn-link btn-sm p-0 f-find"><i class="fas fa-search me-1"></i>Chercher un article</button> <span class="f-link text-success">' + (l.item_id ? '<i class="fas fa-link"></i> ' + esc(l._label || 'article lié') : '') + (auto ? ' <em>(proposé : à vérifier)</em>' : '') + '</span></div>' + ((l.target_price != null || l.client_comment) ? '<div class="small mt-1" style="color:#a14a00"><i class="fas fa-comment-dollar me-1"></i>Souhait du client : ' + (l.target_price != null ? '<b>' + fmt(l.target_price) + ' € HT</b> ' : '') + (l.client_comment ? '« ' + esc(l.client_comment) + ' »' : '') + '</div>' : '') + '</td>' +
            '<td><input class="f-qty w-q" type="number" min="1" value="' + (l.qty || 1) + '"></td>' +
            '<td><select class="f-av"><option value="">—</option><option value="stock"' + (av === 'stock' ? ' selected' : '') + '>En stock</option><option value="delay"' + (av === 'delay' ? ' selected' : '') + '>Sous délai</option><option value="unavailable"' + (av === 'unavailable' ? ' selected' : '') + '>Indisponible</option></select><input class="f-delay" value="' + esc(l.delay_text) + '" placeholder="ex. 48 h" style="margin-top:4px;' + (av === 'delay' ? '' : 'display:none') + '"></td>' +
            '<td><input class="f-pu w-p" type="number" min="0" step="0.01" value="' + (l.list_ht == null ? (l.unit_ht == null ? '' : l.unit_ht) : l.list_ht) + '" placeholder="Tarif HT"></td>' +
            '<td><input class="f-rem w-t" type="number" min="0" max="100" step="0.01" value="' + (l.remise == null ? 0 : l.remise) + '"></td>' +
            '<td class="text-end"><span class="f-net text-muted">—</span></td>' +
            '<td><input class="f-tva w-t" type="number" min="0" max="100" step="0.1" value="' + (l.tva_rate == null ? 0 : l.tva_rate) + '"></td>' +
            '<td class="text-end"><b class="f-tot">0,00</b></td><td><button type="button" class="btn btn-sm btn-link text-danger f-rm" title="Retirer">&times;</button></td></tr>';
    }
    function calc() {
        var ht = 0, tva = 0;
        document.querySelectorAll('#qaBody tr:not(.qa-sr)').forEach(function (tr) {
            if (tr.classList.contains('gone')) { return; }
            var pu = tr.querySelector('.f-pu').value, av = tr.querySelector('.f-av').value, qty = parseInt(tr.querySelector('.f-qty').value, 10) || 0, rate = parseFloat(tr.querySelector('.f-tva').value) || 0;
            var rem = Math.min(100, Math.max(0, parseFloat(tr.querySelector('.f-rem').value) || 0));
            var l = (pu !== '' && av !== 'unavailable') ? Math.round(parseFloat(pu) * qty * (1 - rem / 100) * 100) / 100 : 0;
            tr.querySelector('.f-net').textContent = pu !== '' ? fmt(parseFloat(pu) * (1 - rem / 100)) : '—';
            tr.querySelector('.f-tot').textContent = fmt(l); ht += l; tva += Math.round(l * rate) / 100;
        });
        if ($('qaHt')) { $('qaHt').textContent = fmt(ht); $('qaTva').textContent = fmt(tva); $('qaTtc').textContent = fmt(ht + tva); }
    }
    function collect() {
        var lines = [];
        document.querySelectorAll('#qaBody tr:not(.qa-sr)').forEach(function (tr) {
            var id = tr.getAttribute('data-id');
            var o = {id: id ? parseInt(id, 10) : null, removed: tr.classList.contains('gone'), reference: tr.querySelector('.f-ref').value, vendor_ref: tr.querySelector('.f-vref').value, designation: tr.querySelector('.f-des').value, brand: tr.querySelector('.f-brand').value,
                qty: parseInt(tr.querySelector('.f-qty').value, 10) || 1, availability: tr.querySelector('.f-av').value || null, delay_text: tr.querySelector('.f-delay').value, list_ht: tr.querySelector('.f-pu').value, remise: tr.querySelector('.f-rem').value || 0, tva_rate: tr.querySelector('.f-tva').value || 0, item_id: tr.getAttribute('data-item') ? parseInt(tr.getAttribute('data-item'), 10) : null};
            if (o.list_ht === '') o.list_ht = null;
            lines.push(o);
        });
        return lines;
    }
    function msg(t, kind) { var m = $('qaMsg'); if (m) { m.innerHTML = t ? '<div class="po-alert ' + (kind || 'o') + '">' + esc(t) + '</div>' : ''; } }

    function filesHtml(d) {
        var fs = d.files || [];
        var list = fs.map(function (f) {
            var th = f.image ? '<span class="th" style="background-image:url(\'' + esc(f.url) + '\')"></span>' : '<span class="th"><i class="fas fa-file-pdf"></i></span>';
            return '<span style="position:relative"><a class="qa-fl ' + (f.by === 'vendor' ? 'v' : '') + '" href="' + esc(f.url) + '" target="_blank" rel="noopener" title="' + esc(f.name) + ' · ' + (f.by === 'vendor' ? 'ajouté par vous' : 'envoyé par le client') + ' · ' + esc(f.at) + '">' + th + '<span>' + esc(f.name.length > 16 ? f.name.slice(0, 13) + '…' : f.name) + '</span></a>' + (f.by === 'vendor' ? '<button type="button" class="x" data-del-file="' + f.id + '" aria-label="Supprimer">×</button>' : '') + '</span>';
        }).join('');
        return '<b><i class="fas fa-paperclip me-1"></i>Pièces jointes</b>' + (fs.length ? '' : ' <span class="text-muted small">aucune</span>') +
            (list ? '<div class="qa-files">' + list + '</div>' : '') +
            (d.status === 'cancelled' || d.status === 'declined' ? '' : '<div class="mt-2"><label class="btn btn-sm btn-outline-secondary mb-0"><i class="fas fa-upload me-1"></i>Ajouter un fichier pour le client<input type="file" id="qaFileIn" multiple accept="image/*,application/pdf" style="display:none"></label> <span class="small text-muted">bleu = ajouté par vous, visible du client</span></div>');
    }
    var QUICK = ['Pièces disponibles en stock, livraison avec votre prochaine tournée.', 'Pièces d\'origine constructeur, disponibles sous 48 h.', 'Qualité équivalente première monte, garantie 2 ans.', 'Merci de nous confirmer le côté (gauche / droit) et le numéro de châssis.', 'Photo de la pièce proposée jointe pour vérification.'];
    function pdfBtn(d) { return d.can_pdf ? '<a class="btn btn-outline-secondary" target="_blank" rel="noopener" href="' + URL.show + '/' + d.id + '/pdf?inline=1"><i class="fas fa-file-pdf me-1"></i>PDF</a>' : ''; }
    function open(id) {
        api(URL.show + '/' + id).then(function (d) {
            if (!d.id) return; cur = d;
            $('poTitle').textContent = d.number + ' · ' + d.customer;
            var h = '<div id="qaMsg"></div>';
            if (d.status === 'negotiating') {
                var ng = (d.events || []).filter(function (e) { return e.type === 'negotiate'; }).pop();
                h += '<div class="po-alert o"><b>Le client demande une modification</b> (version ' + d.round + ').' + (ng && ng.message ? '<div style="white-space:pre-line;margin-top:4px">' + esc(ng.message) + '</div>' : '') + 'Ajustez les prix ou quantités ci-dessous puis renvoyez le devis.</div>';
            }
            if (d.status === 'accepted') {
                var nref = (d.items || []).filter(function (l) { return l.accepted === false; }).length;
                h += '<div class="po-alert g"><b>Le client a accepté ce devis' + (nref ? ' en partie' : '') + '</b> le ' + esc(d.decided) + '.' + (nref ? ' <b>' + nref + ' ligne(s) refusée(s)</b> (barrées ci-dessous) : total retenu ' + fmt(d.total_ttc) + ' € TTC au lieu de ' + fmt(d.offered_ttc) + ' €.' : '') + ' Créez maintenant la commande vente dans l\'ERP pour ce client.</div>';
            }
            if (d.status === 'declined') h += '<div class="po-alert o">Refusé' + (d.decided ? ' le ' + esc(d.decided) : '') + '.</div>';
            if (d.status === 'cancelled') h += '<div class="po-alert o">Annulée par le client.</div>';
            h += '<div class="po-box"><b>' + esc(d.customer) + '</b> <span class="text-muted">' + esc(d.customer_code || '') + '</span>' + (d.customer_phone ? ' · ' + esc(d.customer_phone) : '') + (d.customer_city ? ' · ' + esc(d.customer_city) : '') +
                '<div class="mt-2"><b>Véhicule :</b> ' + esc(d.vehicle || '—') + (d.vin ? ' · VIN <span class="po-n">' + esc(d.vin) + '</span>' : '') + '</div>' +
                (d.note ? '<div class="mt-1"><b>Message :</b> <span style="white-space:pre-line">' + esc(d.note) + '</span></div>' : '') + '<div class="small text-muted mt-1">Demandé par ' + esc(d.user || '—') + ' le ' + esc(d.created) + '</div></div>';
            h += '<div class="po-box" id="qaFiles">' + filesHtml(d) + '</div>';
            if (d.editable) {
                h += '<div class="qa-tools"><b>Outils :</b><button type="button" class="btn btn-sm btn-outline-secondary" id="qaAllStock">Tout en stock</button><span>Remise sur toutes les lignes <input type="number" id="qaBulkRem" min="0" max="100" step="0.01" style="width:70px" class="form-control form-control-sm d-inline-block"> %</span><button type="button" class="btn btn-sm btn-outline-secondary" id="qaBulkGo">Appliquer</button></div>' +
                    '<div style="overflow:auto"><table class="qa-lines"><thead><tr><th style="min-width:130px">Référence</th><th style="min-width:190px">Désignation</th><th>Qté</th><th style="min-width:110px">Disponibilité</th><th>Tarif HT</th><th>Remise %</th><th class="text-end">PU net</th><th>TVA %</th><th class="text-end">Total HT</th><th></th></tr></thead><tbody id="qaBody">' +
                    d.items.map(function (l) { if (l.tva_rate === 0 && l.unit_ht === null) l.tva_rate = d.tva_default; return lineRow(l); }).join('') + '</tbody></table></div>' +
                    '<button type="button" class="btn btn-sm btn-outline-primary mt-2" id="qaAdd"><i class="fas fa-plus me-1"></i>Ajouter une ligne</button>' +
                    '<div class="qa-tot"><span>Total HT <b id="qaHt">0,00</b> €</span><span>TVA <b id="qaTva">0,00</b> €</span><span>Total TTC <b id="qaTtc">0,00</b> €</span></div>' +
                    '<div class="row g-2 mt-1"><div class="col-md-8"><label class="small fw-bold">Message au client (visible)</label><textarea id="qaMsgC" class="form-control" rows="2" maxlength="1000" placeholder="Ex. Pièces d\'origine disponibles sous 48 h, livraison demain matin.">' + esc(d.vendor_message || '') + '</textarea><div class="qa-chips">' + QUICK.map(function (t, i) { return '<button type="button" class="qa-chip" data-quick="' + i + '">' + esc(t.length > 38 ? t.slice(0, 36) + '…' : t) + '</button>'; }).join('') + '</div></div>' +
                    '<div class="col-md-4"><label class="small fw-bold">Validité (jours)</label><input id="qaDays" type="number" min="0" max="365" class="form-control" value="15"><div class="form-text">0 = sans limite</div></div>' +
                    '<div class="col-12"><label class="small fw-bold">Note interne</label><input id="qaInt" class="form-control" maxlength="1000" value="' + esc(d.internal_note || '') + '"></div></div>';
                $('poFoot').innerHTML = '<button class="btn btn-success" id="qaSend"><i class="fas fa-paper-plane me-1"></i>' + (d.status === 'answered' ? 'Mettre à jour et renvoyer' : (d.status === 'negotiating' ? 'Envoyer le devis mis à jour' : 'Envoyer le devis')) + '</button><button class="btn btn-outline-primary" id="qaSave">Enregistrer le brouillon</button><button class="btn btn-outline-secondary" id="qaErp"><i class="fas ' + (d.erp_id ? 'fa-sync' : 'fa-share-square') + ' me-1"></i>' + (d.erp_id ? 'Mettre à jour le devis ERP' : 'Créer le devis dans l\'ERP') + '</button>' + pdfBtn(d) + '<button class="btn btn-outline-danger ms-auto" id="qaDecline">Refuser la demande</button>';
            } else {
                h += '<table class="qa-lines"><thead><tr><th>Pièce</th><th>Qté</th><th>Dispo</th><th class="text-end">Tarif HT</th><th class="text-end">Remise</th><th class="text-end">Total HT</th></tr></thead><tbody>' + d.items.map(function (l) {
                    return '<tr class="' + (l.accepted === false ? 'qa-ref' : '') + '"><td><b>' + esc(l.designation) + '</b>' + (l.accepted === false ? ' <span class="badge bg-danger">Refusée</span>' : (l.accepted === true ? ' <span class="badge bg-success">Gardée</span>' : '')) + '<div class="small text-muted">' + esc(l.reference || '') + ' ' + esc(l.brand || '') + (l.vendor_ref ? ' · proposé ' + esc(l.vendor_ref) : '') + '</div></td><td>' + l.qty + '</td><td>' + esc(l.availability === 'delay' ? (l.delay_text || 'Sous délai') : (l.availability === 'stock' ? 'En stock' : (l.availability === 'unavailable' ? 'Indisponible' : '—'))) + '</td><td class="text-end">' + (l.list_ht == null ? '—' : fmt(l.list_ht)) + '</td><td class="text-end">' + (l.remise > 0 ? '-' + l.remise + ' %' : '—') + '</td><td class="text-end">' + (l.list_ht == null || l.availability === 'unavailable' ? '—' : fmt(l.list_ht * l.qty * (1 - l.remise / 100))) + '</td></tr>';
                }).join('') + '</tbody></table><div class="qa-tot"><span>Total TTC <b>' + fmt(d.total_ttc) + ' €</b></span></div>' + (d.vendor_message ? '<div class="po-box"><b>Message envoyé :</b> ' + esc(d.vendor_message) + '</div>' : '');
                $('poFoot').innerHTML = (d.status === 'accepted' || d.status === 'answered' ? '<button class="btn btn-primary" id="qaErp"><i class="fas ' + (d.erp_id ? 'fa-sync' : 'fa-share-square') + ' me-1"></i>' + (d.erp_id ? 'Mettre à jour le devis ERP' : 'Créer le devis dans l\'ERP') + '</button>' : '') + pdfBtn(d);
            }
            if (d.erp_url) { var em = $('qaMsg'); if (em) em.innerHTML = '<div class="po-alert i">Devis ERP <b>' + esc(d.erp_numdoc) + '</b> : <a href="' + esc(d.erp_url) + '" target="_blank">ouvrir dans l\'ERP</a> (pour le basculer en commande vente).</div>'; }
            h += evHtml(d);
            $('poDetail').innerHTML = h;
            $('poPan').classList.add('on'); $('poVeil').classList.add('on');
            calc();
        });
    }
    var findT = null;
    function applyItem(tr, r) {
        tr.setAttribute('data-item', r.item_id); tr.classList.remove('auto');
        tr.querySelector('.f-vref').value = r.code;
        if (!tr.querySelector('.f-ref').value) tr.querySelector('.f-ref').value = r.code;
        if (r.below_margin) toast('Prix net sous la marge minimale du portail : vérifiez la remise.', 'warning');
        if (r.list_ht != null) { tr.querySelector('.f-pu').value = r.list_ht; tr.querySelector('.f-rem').value = r.remise || 0; } else if (r.unit_ht != null) { tr.querySelector('.f-pu').value = r.unit_ht; tr.querySelector('.f-rem').value = 0; }
        if (r.tva_rate != null) tr.querySelector('.f-tva').value = r.tva_rate;
        if (r.availability) { var sel = tr.querySelector('.f-av'); sel.value = r.availability; sel.dispatchEvent(new Event('change', {bubbles: true})); }
        tr.querySelector('.f-link').innerHTML = '<i class="fas fa-link"></i> ' + esc(r.code) + ' · stock ' + r.stock;
        calc();
    }
    function openFind(tr) {
        var old = document.querySelector('.qa-sr'); if (old) old.remove();
        var sr = document.createElement('tr'); sr.className = 'qa-sr';
        sr.innerHTML = '<td colspan="10"><input type="search" class="form-control form-control-sm" placeholder="Référence, code-barres ou désignation…"><div class="res"></div></td>';
        tr.parentNode.insertBefore(sr, tr.nextSibling);
        var inp = sr.querySelector('input'), res = sr.querySelector('.res'), rows = [];
        inp.value = tr.querySelector('.f-ref').value || tr.querySelector('.f-des').value || '';
        function run() {
            var q = inp.value.trim(); if (q.length < 2) { res.innerHTML = ''; return; }
            res.innerHTML = '<div class="small text-muted mt-2">Recherche…</div>';
            api(URL.items + '?q=' + encodeURIComponent(q) + '&customer_id=' + (cur ? cur.customer_id : '') + '&qty=' + (parseInt(tr.querySelector('.f-qty').value, 10) || 1)).then(function (j) {
                rows = j.rows || [];
                res.innerHTML = rows.length ? rows.map(function (r, i) {
                    return '<button type="button" class="qa-hit" data-i="' + i + '"><b>' + esc(r.code) + '</b><span>' + esc(r.name) + (r.brand ? ' <span class="text-muted">· ' + esc(r.brand) + '</span>' : '') + '</span><span class="st"><b style="font-family:inherit">' + (r.unit_ht != null ? fmt(r.unit_ht) + ' € HT' + (r.remise > 0 ? ' <span class="text-muted">(tarif ' + fmt(r.list_ht) + ', -' + r.remise + ' %)</span>' : '') : '—') + '</b> · stock ' + r.stock + '</span></button>';
                }).join('') : '<div class="small text-muted mt-2">Aucun article trouvé. Saisissez le prix à la main : la ligne restera une ligne libre.</div>';
            });
        }
        inp.addEventListener('input', function () { clearTimeout(findT); findT = setTimeout(run, 250); });
        res.addEventListener('click', function (e) { var b = e.target.closest('.qa-hit'); if (!b) return; applyItem(tr, rows[parseInt(b.getAttribute('data-i'), 10)]); sr.remove(); });
        inp.focus(); run();
    }
    document.addEventListener('input', function (e) { if (e.target.closest('#qaBody')) calc(); });
    function refreshFiles() {
        if (!cur) return;
        api(URL.show + '/' + cur.id).then(function (d) { if (d && d.id && $('qaFiles')) { cur.files = d.files; $('qaFiles').innerHTML = filesHtml(d); } });
    }
    document.addEventListener('change', function (e) {
        var fi = e.target.closest('#qaFileIn'); if (!fi || !fi.files.length || !cur) return;
        var fd = new FormData(); [].forEach.call(fi.files, function (f) { fd.append('files[]', f); });
        fetch(URL.show + '/' + cur.id + '/files', {method: 'POST', credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest'}, body: fd})
            .then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { toast(j.message || (r.ok ? 'Fichier ajouté.' : 'Échec de l\'envoi (fichier trop lourd ?)'), !r.ok); if (r.ok) refreshFiles(); }); });
    });
    document.addEventListener('click', function (e) {
        var q = e.target.closest('[data-quick]'); if (q) { var ta = $('qaMsgC'); if (ta) { ta.value = (ta.value ? ta.value.replace(/\s+$/, '') + ' ' : '') + QUICK[parseInt(q.getAttribute('data-quick'), 10)]; ta.focus(); } return; }
        var df = e.target.closest('[data-del-file]'); if (df && cur) { if (!confirm('Supprimer ce fichier ?')) return; api(URL.show + '/' + cur.id + '/files/' + df.getAttribute('data-del-file') + '/delete', 'POST').then(function (j) { toast(j.message, !j.ok); refreshFiles(); }); return; }
        if (e.target.closest('#qaAllStock')) { [].forEach.call(document.querySelectorAll('#qaBody tr:not(.qa-sr)'), function (tr) { var sel = tr.querySelector('.f-av'); if (sel && !sel.value && tr.querySelector('.f-pu').value !== '') { sel.value = 'stock'; sel.dispatchEvent(new Event('change', {bubbles: true})); } }); toast('Lignes chiffrées marquées « En stock ».'); return; }
        if (e.target.closest('#qaBulkGo')) { var v = parseFloat($('qaBulkRem').value); if (isNaN(v) || v < 0 || v > 100) { toast('Indiquez une remise entre 0 et 100.', true); return; } [].forEach.call(document.querySelectorAll('#qaBody tr:not(.qa-sr)'), function (tr) { if (tr.querySelector('.f-pu').value !== '') tr.querySelector('.f-rem').value = v; }); calc(); toast('Remise de ' + v + ' % appliquée aux lignes chiffrées.'); return; }
    });
    document.addEventListener('change', function (e) {
        var s = e.target.closest('.f-av'); if (s) { s.parentNode.querySelector('.f-delay').style.display = s.value === 'delay' ? '' : 'none'; calc(); }
    });
    document.addEventListener('click', function (e) {
        var fd = e.target.closest('.f-find'); if (fd) { openFind(fd.closest('tr')); return; }
        var rm = e.target.closest('.f-rm');
        if (rm) { rm.closest('tr').classList.toggle('gone'); calc(); return; }
        if (e.target.closest('#qaAdd')) { var t = document.createElement('tbody'); t.innerHTML = lineRow({qty: 1, tva_rate: cur ? cur.tva_default : 0}); $('qaBody').appendChild(t.firstChild); return; }
        function save(send, cb) {
            var b = $('qaSend'), s2 = $('qaSave'); b.disabled = true; s2.disabled = true; msg('');
            api(URL.show + '/' + cur.id + '/update', 'POST', {send: send, lines: collect(), vendor_message: $('qaMsgC').value, internal_note: $('qaInt').value, valid_days: parseInt($('qaDays').value, 10) || 0}).then(function (j) {
                b.disabled = false; s2.disabled = false;
                if (!j.ok) { msg(j.message || 'Enregistrement impossible.', 'o'); toast(j.message || 'Enregistrement impossible.', true); return; }
                if (cb) { load(); cb(); return; }
                if (send) { closePan(); toast('Devis ' + (j.number || '') + ' envoyé à ' + (j.customer || 'le client') + (j.mailed ? ' — e-mail de notification envoyé.' : ' — visible dans son portail (aucun e-mail valide enregistré).')); }
                else { msg('Brouillon enregistré.', 'g'); toast('Brouillon enregistré.'); }
                load();
            }).catch(function () { b.disabled = false; s2.disabled = false; msg('Erreur réseau, réessayez.'); });
        }
        function erp(btn) {
            btn.disabled = true; msg('');
            api(URL.show + '/' + cur.id + '/erp', 'POST', {}).then(function (j) {
                btn.disabled = false;
                if (!j.ok) { msg(j.message || 'Création impossible.', 'o'); toast(j.message || 'Création impossible.', true); return; }
                toast('Devis ERP ' + j.erp_numdoc + ' enregistré.');
                msg('Devis ERP ' + j.erp_numdoc + ' enregistré' + (j.skipped ? ' (' + j.skipped + ' ligne(s) libre(s) à saisir dans l\'ERP, indiquées dans la note)' : '') + '. ', 'g');
                var m = $('qaMsg'); if (m && j.erp_url) m.firstChild.innerHTML += '<a href="' + esc(j.erp_url) + '" target="_blank">Ouvrir le devis dans l\'ERP</a>';
                if (cur) { cur.erp_id = true; cur.erp_numdoc = j.erp_numdoc; cur.erp_url = j.erp_url; }
                var eb = $('qaErp'); if (eb) eb.innerHTML = '<i class="fas fa-sync me-1"></i>Mettre à jour le devis ERP';
                load();
            }).catch(function () { btn.disabled = false; msg('Erreur réseau, réessayez.'); });
        }
        if (e.target.closest('#qaErp')) { var eb2 = e.target.closest('#qaErp'); if ($('qaSave')) save(false, function () { erp(eb2); }); else erp(eb2); return; }
        if (e.target.closest('#qaSend')) save(true);
        if (e.target.closest('#qaSave')) save(false);
        if (e.target.closest('#qaDecline')) {
            var why = window.prompt('Motif du refus (visible par le client) :', 'Pièce introuvable.');
            if (!why) return;
            api(URL.show + '/' + cur.id + '/decline', 'POST', {reason: why}).then(function (j) { if (j.ok) { closePan(); load(); toast('Demande ' + (j.number || '') + ' refusée : le client est prévenu dans son portail.'); } else { msg(j.message, 'o'); toast(j.message || 'Refus impossible.', true); } });
        }
    });
    load();
    setInterval(function () { if (!document.hidden && !cur) load(); }, 30000);
})();
</script>

</body>
</html>
