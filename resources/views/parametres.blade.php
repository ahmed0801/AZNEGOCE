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
                    <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                </div>
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/avoirs"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
                        <li class="nav-item"><a href="/reglement-client"><i class="fas fa-credit-card"></i><p>Règlement Client</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                        <li class="nav-item"><a href="/reglement-fournisseur"><i class="fas fa-credit-card"></i><p>Règlement Fournisseur</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item active"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-robot"></i></span><h4 class="text-section">Autres</h4></li>
                        <li class="nav-item"><a href="/voice"><i class="fas fa-robot"></i><p>NEGOBOT</p></a></li>
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

            <h3 class="fw-bold mb-4">Paramètres Généraux</h3>

            <!-- Barre de recherche -->
            <div class="mb-4">
                <input type="text" id="settingsSearch" class="form-control search-box" placeholder="Rechercher un paramètre, une section ou un mot-clé...">
                <div class="search-help-links">
                    <a href="#section-company">Société</a>
                    <a href="#section-stock">Stock</a>
                    <a href="#section-purchases">Achats</a>
                    <a href="#section-accounting">Comptabilité</a>
                    <a href="#section-users">Utilisateurs</a>
                </div>
            </div>

            <!-- Section Société -->
            <!-- Section Société -->
            <div class="settings-section" id="section-company" data-section="company">
                <h4 class="section-header company">Société</h4>
                <p class="section-description">Gérez les informations de l'entreprise et les paramètres associés.</p>
                <div class="row">
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="entreprise information logo">
                        <a href="/company-information" class="text-decoration-none">
                            <div class="card card-stats card-info card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-info">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Informations Société</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="souche facture document">
                        <a href="/souches" class="text-decoration-none">
                            <div class="card card-stats card-info card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-info">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Paramètres Souches</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section Stock -->
            <div class="settings-section" id="section-stock" data-section="stock">
                <h4 class="section-header stock">Stock</h4>
                <p class="section-description">Configurez les paramètres liés à la gestion des stocks et des articles.</p>
                <div class="row">
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="magasin dépôt entrepôt">
                        <a href="/magasins" class="text-decoration-none">
                            <div class="card card-stats card-success card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-success">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Paramètres Magasins</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="article catégorie classification">
                        <a href="/categories" class="text-decoration-none">
                            <div class="card card-stats card-success card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-success">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Catégories Articles</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="article unité mesure">
                        <a href="/units" class="text-decoration-none">
                            <div class="card card-stats card-success card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-success">
                                        <i class="fas fa-ruler"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Unités Articles</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="marque véhicule marquee">
                        <a href="/brands" class="text-decoration-none">
                            <div class="card card-stats card-success card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-success">
                                        <i class="fas fa-industry"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Marques</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="stock inventaire gestion">
                        <a href="#" class="text-decoration-none">
                            <div class="card card-stats card-success card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-success">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Paramètres Stock</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <!-- Section Paramètres Achats -->
            <div class="settings-section" id="section-purchases" data-section="purchases">
                <h4 class="section-header purchases">Achats</h4>
                <p class="section-description">Configurez les paramètres liés à la gestion des achats.</p>
                <div class="row">
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="achat commande réception retour">
                        <a href="/purchase-settings" class="text-decoration-none">
                            <div class="card card-stats card-primary card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-primary">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Paramètres Achats</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            

            <!-- Section Comptabilité -->
            <div class="settings-section" id="section-accounting" data-section="accounting">
                <h4 class="section-header accounting">Comptabilité</h4>
                <p class="section-description">Gérez les paramètres financiers et comptables.</p>
                <div class="row">
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="tva taxe fiscal">
                        <a href="/grouptvas" class="text-decoration-none">
                            <div class="card card-stats card-warning card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-warning">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Tva</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="paiement moyen transaction">
                        <a href="/paymentmodes" class="text-decoration-none">
                            <div class="card card-stats card-warning card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-warning">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Moyens de Paiement</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="paiement condition délai">
                        <a href="/paymentterms" class="text-decoration-none">
                            <div class="card card-stats card-warning card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-warning">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Conditions de Paiement</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="comptabilité finance banque">
                        <a href="#" class="text-decoration-none">
                            <div class="card card-stats card-warning card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center warning">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Paramètres Comptables</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section Utilisateurs -->
            <div class="settings-section" id="section-users" data-section="users">
                <h4 class="section-header users">Utilisateurs</h4>
                <p class="section-description">Gérez les utilisateurs et leurs remises.</p>
                <div class="row">
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="utilisateur compte accès">
                        <a href="/users" class="text-decoration-none">
                            <div class="card card-stats card-danger card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-danger">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Paramètres Utilisateurs</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-3 setting-card" data-keywords="remise groupe réduction">
                        <a href="/groupremises" class="text-decoration-none">
                            <div class="card card-stats card-danger card-round text-center">
                                <div class="card-body">
                                    <div class="icon-big text-center text-danger">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <div class="numbers mt-2">
                                        <p class="card-category">Groupes Remises</p>
                                    </div>
                                </div>
                            </div>
                        </a>
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

  <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Script pour la recherche corrigée -->
    <script>
        // Fonction pour normaliser le texte
        function normalizeText(text) {
            if (!text) return '';
            return text
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.setting-card');
            const sections = document.querySelectorAll('.settings-section');
            cards.forEach(card => {
                card.style.display = '';
                card.classList.remove('d-none'); // Enlever toute classe d-none potentielle
            });
            sections.forEach(section => {
                section.style.display = '';
                section.classList.remove('d-none');
            });
            console.log('Initialisation: Toutes les cartes et sections sont visibles');
        });

        // Fonction de recherche
        document.getElementById('settingsSearch').addEventListener('keyup', function() {
            const searchText = normalizeText(this.value);
            const searchTerms = searchText.split(' ').filter(term => term.length > 0);
            const cards = document.querySelectorAll('.setting-card');
            const sections = document.querySelectorAll('.settings-section');

            // Réinitialiser l'affichage
            cards.forEach(card => {
                card.style.display = '';
                card.classList.remove('d-none');
            });
            sections.forEach(section => {
                section.style.display = '';
                section.classList.remove('d-none');
            });
            console.log('Recherche pour:', searchTerms);

            // Si aucun terme, arrêter
            if (searchTerms.length === 0) {
                console.log('Recherche vide, tout afficher');
                return;
            }

            // Filtrer les cartes
            let hasMatches = false;
            cards.forEach(card => {
                const titleElement = card.querySelector('.card-category');
                const title = normalizeText(titleElement ? titleElement.textContent : '');
                const section = card.closest('.settings-section');
                const sectionTitleElement = section.querySelector('.section-header');
                const sectionDescriptionElement = section.querySelector('.section-description');
                const sectionTitle = normalizeText(sectionTitleElement ? sectionTitleElement.textContent : '');
                const sectionDescription = normalizeText(sectionDescriptionElement ? sectionDescriptionElement.textContent : '');
                const keywords = normalizeText(card.dataset.keywords || '');

                // Vérifier les correspondances
                const matches = searchTerms.some(term => 
                    title.includes(term) || 
                    sectionTitle.includes(term) || 
                    sectionDescription.includes(term) || 
                    keywords.includes(term)
                );

                card.style.display = matches ? '' : 'none';
                if (matches) hasMatches = true;

                console.log(`Carte: ${title}, Mots-clés: ${keywords}, Section: ${sectionTitle}, Description: ${sectionDescription}, Correspondance: ${matches}`);
            });

            // Forcer la mise à jour du DOM et vérifier les sections
            setTimeout(() => {
                sections.forEach(section => {
                    // Vérifier les cartes visibles en fonction du style effectif
                    const visibleCards = Array.from(section.querySelectorAll('.setting-card')).filter(card => 
                        card.style.display !== 'none' && !card.classList.contains('d-none')
                    );
                    section.style.display = visibleCards.length > 0 ? '' : 'none';
                    console.log(`Section: ${normalizeText(section.querySelector('.section-header').textContent)}, Cartes visibles: ${visibleCards.length}`);
                });

                if (!hasMatches) {
                    console.log('Aucune correspondance trouvée pour les termes saisis');
                }
            }, 0);
        });
    </script>















  </body>
</html>
