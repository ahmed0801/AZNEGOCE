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
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h3 class="fw-bold mb-0"><i class="fas fa-globe me-2 text-info"></i>Portail B2B — Paramètres</h3>
        <small class="text-muted">Tout ce que vos clients voient et la façon dont leurs commandes arrivent chez vous.</small>
    </div>
    <div>
        @if($s->portal_enabled)<span class="badge bg-success px-3 py-2"><i class="fas fa-circle me-1"></i> Portail ouvert</span>@else<span class="badge bg-secondary px-3 py-2"><i class="fas fa-circle me-1"></i> Portail fermé</span>@endif
        <a href="{{ url('/setting') }}" class="btn btn-sm btn-outline-secondary ms-2"><i class="fas fa-arrow-left me-1"></i> Paramètres</a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger"><b>Enregistrement impossible :</b> corrigez les champs en rouge.</div>
@endif

<form method="POST" action="{{ route('b2b.settings.update') }}" enctype="multipart/form-data" id="b2bForm" novalidate>
@csrf
<div class="row">
    <div class="col-lg-3 mb-3">
        <div class="nav flex-column nav-pills b2b-nav" role="tablist">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-identity" type="button" role="tab"><i class="fas fa-id-card"></i> Identité &amp; contact</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-orders" type="button" role="tab"><i class="fas fa-shopping-cart"></i> Commandes</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-stock" type="button" role="tab"><i class="fas fa-boxes"></i> Stock &amp; prix</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-sim" type="button" role="tab"><i class="fas fa-calculator"></i> Simulateur de prix</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-print" type="button" role="tab"><i class="fas fa-print"></i> Impression</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-notif" type="button" role="tab"><i class="fas fa-bell"></i> Notifications</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-status" type="button" role="tab"><i class="fas fa-power-off"></i> Statut du portail</button>
        </div>
    </div>

    <div class="col-lg-9">
    <div class="tab-content">

        <div class="tab-pane fade show active" id="tab-identity" role="tabpanel">
            <div class="b2b-card">
                <h5>Identité du portail</h5>
                <div class="sub">Le nom, le logo et la couleur que vos clients voient (ils peuvent différer de ceux de la société).</div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="fl">Nom affiché</label><input type="text" name="company_name" id="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $s->company_name) }}" placeholder="Ex : AZ NEGOCE — Espace Pro" >@error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">Slogan</label><input type="text" name="tagline" id="tagline" class="form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline', $s->tagline) }}" placeholder="Pièces auto pour les professionnels" >@error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6">
                        <label class="fl">Logo (PNG, JPG, WebP — 2 Mo max)</label>
                        <input type="file" name="logo" id="logo" accept="image/png,image/jpeg,image/webp" class="form-control @error('logo') is-invalid @enderror">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($s->logo_path)
                            <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1"><label class="form-check-label small" for="remove_logo">Supprimer le logo actuel</label></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="fl">Couleur principale</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" id="primary_color_picker" class="form-control form-control-color" value="{{ old('primary_color', $s->primary_color ?: '#1E2D4A') }}" style="width:60px;height:38px;">
                            <input type="text" name="primary_color" id="primary_color" class="form-control @error('primary_color') is-invalid @enderror" value="{{ old('primary_color', $s->primary_color ?: '#1E2D4A') }}" maxlength="7" style="max-width:120px;">
                        </div>
                        @error('primary_color')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="fl">Message d’accueil</label>
                        <textarea name="welcome_message" id="welcome_message" rows="2" class="form-control" placeholder="Bienvenue sur votre espace professionnel…">{{ old('welcome_message', $s->welcome_message) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="fl">Aperçu</label>
                        <div class="b2b-preview">
                            <div class="bar" id="pvBar" style="background:{{ old('primary_color', $s->primary_color ?: '#1E2D4A') }}">
                                <img id="pvLogo" src="{{ $s->logo_url }}" alt="" @if($s->logo_url) style="display:block" @endif>
                                <div><b id="pvName">{{ old('company_name', $s->company_name) ?: 'Nom du portail' }}</b><small id="pvTag">{{ old('tagline', $s->tagline) }}</small></div>
                            </div>
                            <div class="body" id="pvWelcome">{{ old('welcome_message', $s->welcome_message) ?: 'Votre message d’accueil apparaîtra ici.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="b2b-card">
                <h5>Coordonnées</h5>
                <div class="sub">Affichées sur le portail, les confirmations de commande et le pied de page.</div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="fl">Téléphone</label><input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $s->phone) }}" placeholder="+33 …" >@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">WhatsApp</label><input type="text" name="whatsapp" id="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $s->whatsapp) }}" placeholder="+33 …" >@error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">Email</label><input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $s->email) }}" placeholder="contact@exemple.fr" >@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">Site web</label><input type="text" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $s->website) }}" placeholder="www.exemple.fr" >@error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-8"><label class="fl">Adresse</label><input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $s->address) }}" placeholder="N° et rue" >@error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label class="fl">Ville</label><input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $s->city) }}" placeholder="" >@error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">Horaires</label><input type="text" name="opening_hours" id="opening_hours" class="form-control @error('opening_hours') is-invalid @enderror" value="{{ old('opening_hours', $s->opening_hours) }}" placeholder="Lun–Sam 8h–18h" >@error('opening_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">Mention de pied de page</label><input type="text" name="legal_footer" id="legal_footer" class="form-control @error('legal_footer') is-invalid @enderror" value="{{ old('legal_footer', $s->legal_footer) }}" placeholder="SIRET … — TVA …" >@error('legal_footer')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-orders" role="tabpanel">
            <div class="b2b-card">
                <h5>Réception des commandes</h5>
                <div class="sub">Comment une commande du portail arrive dans l’ERP.</div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="auto_validate" id="auto_validate" value="1" {{ old('auto_validate', $s->auto_validate) ? 'checked' : '' }}><label for="auto_validate" class="mb-0"><b>Validation automatique</b><span>Désactivé (recommandé au départ) : chaque commande arrive « à valider » et un vendeur la contrôle avant de la valider. Activé : la commande est validée directement.</span></label></div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="block_over_credit" id="block_over_credit" value="1" {{ old('block_over_credit', $s->block_over_credit) ? 'checked' : '' }}><label for="block_over_credit" class="mb-0"><b>Bloquer si le plafond client est dépassé</b><span>La commande est refusée (ou mise en attente) quand le solde du client dépasse son plafond.</span></label></div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="allow_particulier" id="allow_particulier" value="1" {{ old('allow_particulier', $s->allow_particulier) ? 'checked' : '' }}><label for="allow_particulier" class="mb-0"><b>Autoriser les clients « particulier »</b><span>Par défaut seuls les jobbers et professionnels peuvent commander (un particulier est facturé en direct).</span></label></div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6"><label class="fl">Montant minimum de commande (€ HT)</label><input type="number" name="min_order_amount" id="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $s->min_order_amount) }}" placeholder="0" step="0.01" min="0">@error('min_order_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="fl">Articles à prix 0 (prix « sur demande »)</label><select name="zero_price_mode" id="zero_price_mode" class="form-select">
                        @php $zm = old('zero_price_mode', $s->zero_price_mode ?: 'both'); @endphp
                        <option value="both" {{ $zm === 'both' ? 'selected' : '' }}>Masquer le prix ET interdire l’ajout au panier</option>
                        <option value="hide" {{ $zm === 'hide' ? 'selected' : '' }}>Masquer le prix seulement</option>
                        <option value="block" {{ $zm === 'block' ? 'selected' : '' }}>Interdire l’ajout au panier seulement</option>
                        <option value="allow" {{ $zm === 'allow' ? 'selected' : '' }}>Autoriser (afficher 0,00 € et commander)</option>
                    </select><div class="form-text">S’applique à tout le portail : catalogue, fiche article, équivalents, panier, liste collée et « commander à nouveau ».</div></div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-stock" role="tabpanel">
            <div class="b2b-card">
                <h5>Prix et disponibilité</h5>
                <div class="sub">Ce que vos clients voient dans le catalogue. Les prix du portail sont calculés avec le groupe remise du client.</div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="show_prices" id="show_prices" value="1" {{ old('show_prices', $s->show_prices) ? 'checked' : '' }}><label for="show_prices" class="mb-0"><b>Afficher les prix</b><span>Si désactivé, le catalogue est consultable mais les prix restent masqués.</span></label></div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="show_stock" id="show_stock" value="1" {{ old('show_stock', $s->show_stock) ? 'checked' : '' }}><label for="show_stock" class="mb-0"><b>Afficher la disponibilité</b><span>Montre au client si l’article est disponible.</span></label></div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="fl">Affichage du stock</label>
                        <select name="stock_display" class="form-select @error('stock_display') is-invalid @enderror">
                            <option value="exact" {{ old('stock_display', $s->stock_display) === 'exact' ? 'selected' : '' }}>Quantité exacte</option>
                            <option value="threshold" {{ old('stock_display', $s->stock_display) === 'threshold' ? 'selected' : '' }}>Disponible / Stock limité / Rupture</option>
                            <option value="hidden" {{ old('stock_display', $s->stock_display) === 'hidden' ? 'selected' : '' }}>Masqué</option>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="fl">Seuil « stock limité » (quantité)</label><input type="number" name="stock_low_threshold" id="stock_low_threshold" class="form-control @error('stock_low_threshold') is-invalid @enderror" value="{{ old('stock_low_threshold', $s->stock_low_threshold) }}" placeholder="5" min="0">@error('stock_low_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                </div>
            </div>

            <div class="b2b-card">
                <h5>Garde-fou de marge</h5>
                <div class="sub">Empêche qu’un prix du portail tombe sous votre coût d’achat net (coût × (1 − remise d’achat)). Le prix tarif reste toujours le plafond.</div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="margin_guard" id="margin_guard" value="1" {{ old('margin_guard', $s->margin_guard) ? 'checked' : '' }}><label for="margin_guard" class="mb-0"><b>Activer le garde-fou de marge</b><span>Le prix est relevé automatiquement au minimum autorisé si la remise du client le fait descendre trop bas.</span></label></div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6"><label class="fl">Marge minimale sur le coût net (%)</label><input type="number" name="min_margin_percent" id="min_margin_percent" class="form-control" value="{{ old('min_margin_percent', $s->min_margin_percent) }}" placeholder="0" step="0.01" min="0" max="500"><div class="small text-muted mt-1">0 = jamais en dessous du coût d’achat net.</div></div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-sim" role="tabpanel">
            <div class="b2b-card">
                <h5>Simulateur de prix portail</h5>
                <div class="sub">Choisissez un client et un article : vous voyez exactement le prix que le client verra sur le portail, et pourquoi. Aucune modification n’est enregistrée.</div>
                <style>
                    .sim-pick{position:relative}.sim-list{position:absolute;z-index:30;left:0;right:0;top:100%;background:#fff;border:1px solid #E2E8F0;border-radius:10px;box-shadow:0 8px 24px rgba(15,23,42,.12);max-height:260px;overflow:auto;display:none}
                    .sim-list div{padding:8px 12px;cursor:pointer;font-size:.82rem;border-bottom:1px solid #F1F5F9}.sim-list div:hover{background:#F1F5F9}.sim-list small{color:#64748B}
                    .sim-res{margin-top:16px;display:none}.sim-big{font-size:1.9rem;font-weight:800;color:#0F172A}
                    .sim-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin:10px 0}
                    .sim-cell{border:1px solid #E2E8F0;border-radius:10px;padding:10px 12px;background:#FAFBFD}.sim-cell span{display:block;font-size:.68rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em}.sim-cell b{font-size:1.05rem}
                    .sim-steps{margin:8px 0 0;padding-left:18px;font-size:.82rem;color:#334155}.sim-badge{display:inline-block;padding:2px 9px;border-radius:99px;font-size:.7rem;font-weight:700;margin-left:6px}
                </style>
                <div class="row g-3">
                    <div class="col-md-6 sim-pick"><label class="fl">Client</label><input type="text" id="simCust" class="form-control" placeholder="Nom ou code client…" autocomplete="off"><div class="sim-list" id="simCustList"></div><div class="small text-muted mt-1" id="simCustInfo"></div></div>
                    <div class="col-md-6 sim-pick"><label class="fl">Article</label><input type="text" id="simItem" class="form-control" placeholder="Référence ou désignation…" autocomplete="off"><div class="sim-list" id="simItemList"></div><div class="small text-muted mt-1" id="simItemInfo"></div></div>
                </div>
                <div class="sim-res" id="simRes">
                    <div class="sim-big"><span id="simNet"></span><span id="simBadges"></span></div>
                    <div class="sim-grid">
                        <div class="sim-cell"><span>Prix tarif HT</span><b id="simList"></b></div>
                        <div class="sim-cell"><span>Remise effective</span><b id="simRate"></b></div>
                        <div class="sim-cell"><span>Prix client TTC</span><b id="simTtc"></b></div>
                        <div class="sim-cell"><span>Coût d’achat net</span><b id="simCost"></b></div>
                        <div class="sim-cell"><span>Marge sur coût</span><b id="simMargin"></b></div>
                    </div>
                    <div class="fl">Détail du calcul</div>
                    <ul class="sim-steps" id="simSteps"></ul>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-print" role="tabpanel">
            <div class="b2b-card">
                <h5>Impression automatique des commandes</h5>
                <div class="sub">Chaque commande reçue peut s’imprimer toute seule sur l’imprimante de votre préparation de commandes.</div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="auto_print" id="auto_print" value="1" {{ old('auto_print', $s->auto_print) ? 'checked' : '' }}><label for="auto_print" class="mb-0"><b>Imprimer automatiquement chaque commande</b><span>À l’arrivée (ou à la validation) d’une commande du portail.</span></label></div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6"><label class="fl">Nom de l’imprimante</label><input type="text" name="printer_name" id="printer_name" class="form-control @error('printer_name') is-invalid @enderror" value="{{ old('printer_name', $s->printer_name) }}" placeholder="Ex : Préparation — Epson TM-T20" >@error('printer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4"><label class="fl">Adresse IP locale</label><input type="text" name="printer_ip" id="printer_ip" class="form-control @error('printer_ip') is-invalid @enderror" value="{{ old('printer_ip', $s->printer_ip) }}" placeholder="192.168.1.50" >@error('printer_ip')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-2"><label class="fl">Port</label><input type="number" name="printer_port" id="printer_port" class="form-control @error('printer_port') is-invalid @enderror" value="{{ old('printer_port', $s->printer_port) }}" placeholder="9100" min="1" max="65535">@error('printer_port')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-4">
                        <label class="fl">Document imprimé</label>
                        <select name="print_document" class="form-select">
                            <option value="order" {{ old('print_document', $s->print_document) === 'order' ? 'selected' : '' }}>Bon de préparation (commande)</option>
                            <option value="delivery_note" {{ old('print_document', $s->print_document) === 'delivery_note' ? 'selected' : '' }}>Bon de livraison</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fl">Format papier</label>
                        <select name="paper_width" class="form-select">
                            @foreach(['80mm' => 'Ticket 80 mm', '58mm' => 'Ticket 58 mm', 'A4' => 'A4'] as $k => $lbl)
                                <option value="{{ $k }}" {{ old('paper_width', $s->paper_width) === $k ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="fl">Nombre d’exemplaires</label><input type="number" name="print_copies" id="print_copies" class="form-control @error('print_copies') is-invalid @enderror" value="{{ old('print_copies', $s->print_copies) }}" placeholder="1" min="1" max="5">@error('print_copies')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTestPrinter"><i class="fas fa-print me-1"></i> Imprimer un ticket de test</button>
                        <span id="printerTestResult" class="ms-2 small"></span>
                    </div>
                </div>
            </div>

            <div class="b2b-card">
                <h5>Agent d’impression du magasin</h5>
                <div class="sub">L’ERP étant hébergé en ligne, il ne peut pas joindre directement votre imprimante. Un petit programme (l’agent) installé sur un PC du magasin interroge l’ERP toutes les 3 secondes, récupère les tickets et les envoie à l’imprimante. Rien à ouvrir sur votre box.</div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span id="agentDot" style="width:10px;height:10px;border-radius:50%;background:{{ $s->agent_last_seen && $s->agent_last_seen->gt(now()->subSeconds(60)) ? '#16A34A' : '#94A3B8' }};display:inline-block"></span>
                    <span id="agentState" class="small"><b>{{ $s->agent_last_seen && $s->agent_last_seen->gt(now()->subSeconds(60)) ? 'Agent en ligne' : 'Agent hors ligne' }}</b>
                        @if($s->agent_last_seen)<span class="text-muted"> — dernier contact : {{ $s->agent_last_seen->format('d/m/Y H:i:s') }}</span>@else<span class="text-muted"> — jamais connecté</span>@endif</span>
                </div>
                <label class="fl">Jeton de l’agent (secret)</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="agentToken" value="{{ $s->agent_token }}" readonly placeholder="Aucun jeton — cliquez sur « Générer »" style="font-family:monospace;font-size:.82rem">
                    <button type="button" class="btn btn-outline-secondary" id="btnCopyToken" {{ $s->agent_token ? '' : 'disabled' }}><i class="fas fa-copy"></i> Copier</button>
                </div>
                <button type="submit" class="btn btn-outline-danger btn-sm" form="regenTokenForm" data-msg="{{ $s->agent_token ? 'Régénérer le jeton ? L’agent actuel cessera de fonctionner tant que vous n’aurez pas collé le nouveau jeton.' : 'Générer le jeton de l’agent ?' }}" onclick="return confirm(this.dataset.msg);"><i class="fas fa-key me-1"></i> {{ $s->agent_token ? 'Régénérer le jeton' : 'Générer le jeton' }}</button>
                <div class="small text-muted mt-2">Adresse du serveur à mettre dans l’agent : <code>{{ rtrim(url('/'), '/') }}</code></div>

                @if(isset($jobs) && $jobs->count())
                <hr>
                <div class="fl mb-1">Derniers tickets</div>
                <table class="table table-sm small mb-0">
                    @foreach($jobs as $j)
                    <tr>
                        <td>#{{ $j->id }}</td>
                        <td>{{ $j->title ?: $j->type }}</td>
                        <td>{{ $j->created_at ? $j->created_at->format('d/m H:i') : '' }}</td>
                        <td>
                            @if($j->status === 'done')<span class="text-success">✔ imprimé</span>
                            @elseif($j->status === 'failed')<span class="text-danger" title="{{ $j->error }}">✖ échec</span>
                            @elseif($j->status === 'printing')<span class="text-primary">en cours…</span>
                            @else<span class="text-warning">en attente</span>@endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="tab-notif" role="tabpanel">
            <div class="b2b-card">
                <h5>Notifications</h5>
                <div class="sub">Soyez prévenu dès qu’un client passe commande.</div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="notify_new_order" id="notify_new_order" value="1" {{ old('notify_new_order', $s->notify_new_order) ? 'checked' : '' }}><label for="notify_new_order" class="mb-0"><b>Email à chaque nouvelle commande</b><span>Un email récapitulatif est envoyé à l’adresse ci-dessous.</span></label></div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6"><label class="fl">Adresse de notification</label><input type="email" name="notification_email" id="notification_email" class="form-control @error('notification_email') is-invalid @enderror" value="{{ old('notification_email', $s->notification_email) }}" placeholder="commandes@exemple.fr" >@error('notification_email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6 d-flex align-items-end"><div><button type="button" class="btn btn-outline-secondary" id="testMailBtn">Envoyer un e-mail de test</button><div class="small mt-1" id="testMailMsg"></div></div></div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-status" role="tabpanel">
            <div class="b2b-card">
                <h5>Statut du portail</h5>
                <div class="sub">Ouvrez le portail à vos clients quand tout est prêt, ou fermez-le temporairement.</div>
                <div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="portal_enabled" id="portal_enabled" value="1" {{ old('portal_enabled', $s->portal_enabled) ? 'checked' : '' }}><label for="portal_enabled" class="mb-0"><b>Portail ouvert aux clients</b><span>Fermé : les clients voient le message de maintenance. Les accès se gèrent dans la fiche de chaque client.</span></label></div>
                <div class="row g-3 mt-1">
                    <div class="col-12"><label class="fl">Fonctionnalités ouvertes aux clients</label><select name="portal_mode" id="portal_mode" class="form-select">
                        @php $pm = old('portal_mode', $s->portal_mode ?: 'full'); @endphp
                        <option value="full" {{ $pm === 'full' ? 'selected' : '' }}>Portail complet : catalogue, panier, commandes, compte et demandes de devis</option>
                        <option value="quotes" {{ $pm === 'quotes' ? 'selected' : '' }}>Demandes de devis uniquement (catalogue, panier, commandes et compte fermés)</option>
                    </select><div class="form-text">Pratique pour démarrer en douceur : vos clients envoient leurs demandes, vous répondez avec prix et disponibilité. Passez en « complet » quand le catalogue est prêt.</div></div>
                    <div class="col-12"><div class="b2b-switch"><input class="form-check-input" type="checkbox" role="switch" name="portal_show_erp_quotes" id="portal_show_erp_quotes" value="1" {{ old('portal_show_erp_quotes', $s->portal_show_erp_quotes ?? true) ? 'checked' : '' }}><label for="portal_show_erp_quotes">Montrer aux clients les devis créés dans l'ERP (statut « Devis »), avec notification</label></div></div>
                    <div class="col-12"><label class="fl">Message affiché quand le portail est fermé</label><input type="text" name="maintenance_message" id="maintenance_message" class="form-control @error('maintenance_message') is-invalid @enderror" value="{{ old('maintenance_message', $s->maintenance_message) }}" placeholder="Retour très bientôt !" >@error('maintenance_message')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                </div>
            </div>
        </div>

    </div>
    </div>
</div>

<div class="b2b-savebar">
    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Les modifications s’appliquent au portail dès l’enregistrement.</small>
    <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-1"></i> Enregistrer</button>
</div>
</form>
<form id="regenTokenForm" method="POST" action="{{ route('b2b.settings.regenerate_token') }}" style="display:none">@csrf</form>
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
    var $ = function (id) { return document.getElementById(id); };

    function pv() {
        $('pvName').textContent = $('company_name').value || 'Nom du portail';
        $('pvTag').textContent = $('tagline').value;
        $('pvWelcome').textContent = $('welcome_message').value || 'Votre message d’accueil apparaîtra ici.';
        var c = $('primary_color').value;
        if (/^#[0-9A-Fa-f]{6}$/.test(c)) { $('pvBar').style.background = c; $('primary_color_picker').value = c; }
    }
    ['company_name', 'tagline', 'welcome_message', 'primary_color'].forEach(function (id) { $(id).addEventListener('input', pv); });
    $('primary_color_picker').addEventListener('input', function () { $('primary_color').value = this.value.toUpperCase(); pv(); });
    $('logo').addEventListener('change', function () {
        var f = this.files && this.files[0];
        if (!f) return;
        var r = new FileReader();
        r.onload = function (e) { $('pvLogo').src = e.target.result; $('pvLogo').style.display = 'block'; };
        r.readAsDataURL(f);
    });

    var csrf = function () { return document.querySelector('#b2bForm input[name=_token]').value; };
    var hdr  = function () { return { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }; };

    function setAgent(online, last) {
        $('agentDot').style.background = online ? '#16A34A' : '#94A3B8';
        $('agentState').innerHTML = '<b>' + (online ? 'Agent en ligne' : 'Agent hors ligne') + '</b><span class="text-muted"> — ' + (last ? 'dernier contact : ' + last : 'jamais connecté') + '</span>';
    }

    $('btnCopyToken').addEventListener('click', function () {
        var t = $('agentToken'); t.select();
        try { navigator.clipboard.writeText(t.value); } catch (e) { document.execCommand('copy'); }
        var b = this; var old = b.innerHTML; b.innerHTML = '<i class="fas fa-check"></i> Copié'; setTimeout(function () { b.innerHTML = old; }, 1500);
    });

    $('btnTestPrinter').addEventListener('click', function () {
        var out = $('printerTestResult');
        out.className = 'ms-2 small text-muted'; out.textContent = 'Envoi du ticket de test…';
        var body = new FormData();
        body.append('printer_ip', $('printer_ip').value);
        body.append('printer_port', $('printer_port').value || 9100);
        fetch({!! json_encode(route('b2b.settings.test_printer')) !!}, { method: 'POST', credentials: 'same-origin', body: body, headers: hdr() })
          .then(function (r) { return r.json().then(function (j) { return { s: r.status, j: j }; }); })
          .then(function (x) {
              if (x.s === 422) { out.className = 'ms-2 small text-danger'; out.textContent = 'Saisissez une adresse IP valide.'; return; }
              if (!x.j.ok) { out.className = 'ms-2 small text-danger'; out.textContent = '✖ ' + x.j.message; return; }
              out.className = 'ms-2 small text-muted'; out.textContent = x.j.message;
              var tries = 0;
              var timer = setInterval(function () {
                  tries++;
                  fetch({!! json_encode(route('b2b.settings.print_status')) !!} + '?job=' + x.j.job_id, { credentials: 'same-origin', headers: hdr() })
                    .then(function (r) { return r.json(); })
                    .then(function (st) {
                        setAgent(st.agent_online, st.agent_last_seen);
                        if (st.job && st.job.status === 'done') { clearInterval(timer); out.className = 'ms-2 small text-success'; out.textContent = '✔ Ticket imprimé.'; }
                        else if (st.job && st.job.status === 'failed') { clearInterval(timer); out.className = 'ms-2 small text-danger'; out.textContent = '✖ Échec : ' + (st.job.error || 'imprimante injoignable'); }
                        else if (st.job && st.job.status === 'pending' && st.job.error) { out.className = 'ms-2 small text-warning'; out.textContent = 'Nouvelle tentative… (' + st.job.error + ')'; }
                        else if (tries >= 20) { clearInterval(timer); out.className = 'ms-2 small text-warning'; out.textContent = 'Toujours en attente — vérifiez que l’agent tourne au magasin.'; }
                    }).catch(function () {});
              }, 2000);
          }).catch(function () { out.className = 'ms-2 small text-danger'; out.textContent = '✖ Test impossible.'; });
    });

    // ── Simulateur de prix ──
    var simSel = { c: null, i: null };
    function simPick(inputId, listId, infoId, url, key, render) {
        var inp = $(inputId), list = $(listId), t = null;
        inp.addEventListener('input', function () {
            simSel[key] = null; $('simRes').style.display = 'none'; $(infoId).textContent = '';
            clearTimeout(t);
            var q = inp.value.trim();
            if (q.length < 2) { list.style.display = 'none'; return; }
            t = setTimeout(function () {
                fetch(url + '?q=' + encodeURIComponent(q), { credentials: 'same-origin', headers: hdr() })
                  .then(function (r) { return r.json(); })
                  .then(function (rows) {
                      list.innerHTML = '';
                      if (!rows.length) { list.innerHTML = '<div><small>Aucun résultat</small></div>'; list.style.display = 'block'; return; }
                      rows.forEach(function (row) {
                          var d = document.createElement('div'); d.innerHTML = render(row);
                          d.addEventListener('mousedown', function (e) {
                              e.preventDefault(); simSel[key] = row; inp.value = row.name || row.code; list.style.display = 'none';
                              $(infoId).textContent = key === 'c' ? ('Type : ' + (row.type || 'particulier') + ' · Groupe : ' + (row.group || 'aucun')) : ('Réf. ' + row.code);
                              simRun();
                          });
                          list.appendChild(d);
                      });
                      list.style.display = 'block';
                  }).catch(function () {});
            }, 250);
        });
        inp.addEventListener('blur', function () { setTimeout(function () { list.style.display = 'none'; }, 150); });
    }
    function esc(x) { return String(x == null ? '' : x).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
    function eur(v) { return Number(v).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €'; }
    simPick('simCust', 'simCustList', 'simCustInfo', {!! json_encode(route('b2b.pricing.customers')) !!}, 'c', function (r) { return '<b>' + esc(r.name) + '</b> <small>' + esc(r.code) + ' · ' + esc(r.type || 'particulier') + '</small>'; });
    simPick('simItem', 'simItemList', 'simItemInfo', {!! json_encode(route('b2b.pricing.items')) !!}, 'i', function (r) { return '<b>' + esc(r.code) + '</b> ' + esc(r.name) + ' <small>' + eur(r.sale_price) + (r.prix_net ? ' · prix net' : '') + '</small>'; });
    function simRun() {
        if (!simSel.c || !simSel.i) return;
        var body = new FormData(); body.append('customer_id', simSel.c.id); body.append('item_id', simSel.i.id);
        fetch({!! json_encode(route('b2b.pricing.simulate')) !!}, { method: 'POST', credentials: 'same-origin', body: body, headers: hdr() })
          .then(function (r) { return r.json(); })
          .then(function (p) {
              $('simNet').textContent = eur(p.net_ht) + ' HT';
              var b = '';
              if (p.prix_net) b += '<span class="sim-badge" style="background:#E0E7FF;color:#3730A3">Prix net</span>';
              if (p.guard) b += '<span class="sim-badge" style="background:#FEF3C7;color:#92400E">Garde-fou de marge</span>';
              $('simBadges').innerHTML = b;
              $('simList').textContent = eur(p.list_ht);
              $('simRate').textContent = '−' + Number(p.effective_rate).toLocaleString('fr-FR') + ' %';
              $('simTtc').textContent = eur(p.net_ttc);
              $('simCost').textContent = p.cost_net > 0 ? eur(p.cost_net) : '—';
              $('simMargin').textContent = p.margin_percent === null ? '—' : Number(p.margin_percent).toLocaleString('fr-FR') + ' %';
              $('simSteps').innerHTML = p.steps.map(function (x) { return '<li>' + esc(x) + '</li>'; }).join('');
              $('simRes').style.display = 'block';
          }).catch(function () {});
    }
    document.querySelectorAll('#tab-sim input').forEach(function (el) { el.addEventListener('keydown', function (e) { if (e.key === 'Enter') e.preventDefault(); }); });

    @if(session('open_tab'))
    (function () { var btn = document.querySelector('[data-bs-target="#{{ session('open_tab') }}"]'); if (btn && window.bootstrap) new bootstrap.Tab(btn).show(); })();
    @endif

    var bad = document.querySelector('#b2bForm .is-invalid');
    if (bad) {
        var pane = bad.closest('.tab-pane');
        if (pane) {
            var btn = document.querySelector('[data-bs-target="#' + pane.id + '"]');
            if (btn && window.bootstrap) new bootstrap.Tab(btn).show();
        }
    }
})();
</script>
<script>
(function () {
    var b = document.getElementById('testMailBtn'); if (!b) return;
    b.addEventListener('click', function () {
        var m = document.getElementById('testMailMsg'), e = document.getElementById('notification_email').value.trim();
        if (!e) { m.style.color = '#b3261e'; m.textContent = "Saisissez d'abord l'adresse de notification."; return; }
        b.disabled = true; m.style.color = '#6c757d'; m.textContent = 'Envoi en cours…';
        fetch({!! json_encode(route('b2b.settings.test_mail')) !!}, { method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': {!! json_encode(csrf_token()) !!}}, body: JSON.stringify({email: e}) })
          .then(function (r) { return r.json(); }).then(function (j) { b.disabled = false; m.style.color = j.ok ? '#0f7b55' : '#b3261e'; m.textContent = j.message || (j.errors ? Object.values(j.errors)[0][0] : 'Erreur'); })
          .catch(function () { b.disabled = false; m.style.color = '#b3261e'; m.textContent = 'Erreur réseau.'; });
    });
})();
</script>
</body>
</html>

