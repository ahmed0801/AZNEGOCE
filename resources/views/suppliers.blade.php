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

       <h4>Liste des Fournisseurs :

                  <button type="submit" class="btn btn-outline-success btn-round ms-2" data-bs-toggle="modal" data-bs-target="#createItemModal">Nouveau Fournisseur 
           <i class="fas fa-plus-circle ms-2"></i>
          </button>

          <button type="submit" class="btn btn-outline-secondary btn-round ms-2" data-bs-toggle="modal" data-bs-target="#allAccountingModal">
                                Ecritures Comptables Achat <i class="fas fa-balance-scale me-1"></i>
                            </button>

       </h4>

    <!-- Modal création -->
    <div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('supplier.store') }}" method="POST">
    @csrf
    <div class="modal-body row">
        <div class="mb-3 col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 1 : Standard</label>
            <input type="text" name="phone1" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 2 : Commercial</label>
            <input type="text" name="phone2" class="form-control">
        </div>


        <!-- === CHAMP B2B === -->
<div class="mb-3 col-md-6">
    <label class="form-label d-block">
        <i class="fas fa-shopping-cart text-success"></i> Plateforme B2B (site marchand)
    </label>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="has_b2b" value="1" id="has_b2b_create">
        <label class="form-check-label fw-bold text-success" for="has_b2b_create">
            Ce fournisseur a un site B2B (Destock, AZ, OttoGo, etc.)
        </label>
    </div>
    <small class="text-muted">
        Si coché, ce fournisseur apparaîtra dans le select "Fournisseur" lors de la création de commande
    </small>
</div>

<!-- Optionnel : URL du site B2B -->
<div class="mb-3 col-md-6">
    <label class="form-label">URL du site B2B (facultatif)</label>
    <input type="url" name="b2b_url" class="form-control" placeholder="https://destockpiecesauto.autodata.fr">
</div>


        

        <div class="mb-3 col-md-4">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control">
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse</label>
            <input type="text" name="address" class="form-control">
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse de livraison</label>
            <input type="text" name="address_delivery" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Pays</label>
            <input type="text" name="country" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">SIRET</label>
            <input type="text" name="matfiscal" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">IBAN</label>
            <input type="text" name="bank_no" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Solde</label>
            <input type="number" step="0.01" name="solde" class="form-control" value="0" readonly>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Plafond</label>
            <input type="number" step="0.01" name="plafond" class="form-control" value="0">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Risque</label>
            <input type="number" name="risque" class="form-control">
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">TVA</label>
            <select name="tva_group_id" class="form-control" required>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($tvaGroups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->rate }} %</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Groupe Remise</label required>
            <select name="discount_group_id" class="form-control">
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($discountGroups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->discount_rate }} %</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Mode de paiement</label>
            <select name="payment_mode_id" class="form-control">
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($paymentModes as $mode)
                    <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Condition de paiement</label>
            <select name="payment_term_id" class="form-control">
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($paymentTerms as $term)
                    <option value="{{ $term->id }}">{{ $term->label }} : {{ $term->days }} Jours</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-success">Créer</button>
    </div>
</form>

            </div>
        </div>
    </div>


    





    <!-- New Modal for All Accounting Entries -->
                        <div class="modal fade" id="allAccountingModal" tabindex="-1" aria-labelledby="allAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allAccountingModalLabel">Ecritures Comptables Achats</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des Achats</h6>
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
                                                <option value="">Fournisseur (Tous)</option>
                                                @foreach($suppliers as $customer)
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
                                                        <th>Fourn.</th>
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
    <form method="GET" action="{{ route('supplier.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
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
        <select name="city" class="form-select form-select-sm" style="width: 150px;">
            <option value="">Ville (Toutes)</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                    {{ $city }}
                </option>
            @endforeach
        </select>
        
        <!-- Solde min -->
        <input type="number" step="0.01" name="min_solde" class="form-control form-control-sm" 
               style="width: 120px;" placeholder="Solde min" value="{{ request('min_solde') }}">
        <span class="mx-1 text-muted">à</span>
        
        <!-- Solde max -->
        <input type="number" step="0.01" name="max_solde" class="form-control form-control-sm" 
               style="width: 120px;" placeholder="Solde max" value="{{ request('max_solde') }}">
        
        <!-- Actions -->
        <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
            <i class="fas fa-filter me-1"></i> Filtrer
        </button>
        
        <button type="submit" name="action" value="export" 
                formaction="{{ route('suppliers.export') . '?' . http_build_query(request()->query()) }}" 
                class="btn btn-outline-success btn-sm px-3" target="_blank">
            <i class="fas fa-file-excel me-1"></i> EXCEL
        </button>
        
        <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-undo me-1"></i> Réinitialiser
        </a>
    </form>
</div>

<!-- Recherche rapide (garder l'ancienne) -->
<!-- <div class="mb-2 d-flex justify-content-center">
    <input type="text" id="searchItemInput" class="form-control search-box" placeholder="🔍 Rechercher un fournisseur...">
</div> -->






<!-- KPI Fournisseurs – Vue direction -->
<div class="row mb-4 g-3">
    <!-- 1. Total Fournisseurs (simple, sans véhicules) -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card shadow-sm border-0 h-100 bg-gradient-primary text-white">
            <div class="card-body text-center py-4">
                <i class="fas fa-truck fa-3x mb-3 opacity-75"></i>
                <h6 class="fw-bold mb-1">TOTAL FOURNISSEURS : {{ number_format($totalSuppliers, 0, ',', ' ') }}</h6>
                <!-- <h2 class="display-5 fw-bold mb-0">{{ number_format($totalSuppliers, 0, ',', ' ') }}</h2> -->
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
                        <h4 class="fw-bold text-success mb-0">{{ number_format($activeSuppliers, 0, ',', ' ') }}</h4>
                    </div>
                    <div class="vr mx-2"></div>
                    <div>
                        <i class="fas fa-ban fa-2x text-danger"></i>
                        <h6 class="small mb-1">Inactifs</h6>
                        <h4 class="fw-bold text-danger mb-0">{{ number_format($inactiveSuppliers, 0, ',', ' ') }}</h4>
                    </div>
                </div>
                <small class="text-muted">
                    Taux d'activité : <strong>{{ $totalSuppliers > 0 ? round(($activeSuppliers / $totalSuppliers) * 100, 1) : 0 }}%</strong>
                </small>
            </div>
        </div>
    </div>

    <!-- 3. Fournisseurs non soldés (solde ≠ 0) – carte plus large car KPI clé -->
    <div class="col-xl-6 col-lg-8 col-md-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                <h6 class="fw-bold mb-1">FOURNISSEURS NON SOLDÉS</h6>
                <h2 class="display-5 fw-bold text-warning mb-2">{{ number_format($suppliersNonSoldes, 0, ',', ' ') }}</h2>
                <div class="d-flex justify-content-center gap-5 mb-3">
                    <div>
                        <span class="text-danger fw-bold fs-4">{{ number_format($suppliersNousDoivent) }}</span>
                        <small class="d-block text-danger">nous doivent</small>
                    </div>
                    <div class="vr bg-dark opacity-25"></div>
                    <div>
                        <span class="text-success fw-bold fs-4">{{ number_format($suppliersOnDoit) }}</span>
                        <small class="d-block text-success">on doit</small>
                    </div>
                </div>
                <h6 class="text-muted mb-1">Solde global fournisseurs</h6>
                <h4 class="{{ $totalSoldeSuppliers >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                    {{ number_format($totalSoldeSuppliers, 2, ',', ' ') }} €
                </h4>
            </div>
        </div>
    </div>
</div>




    @if ($suppliers->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-text-small" id="itemsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Adresse & Ville</th>
                        <th>Contact</th>
                        <th>B2B</th>
                        <th>Solde</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $customer)
                        <tr>
                            <td>🧑‍💼{{ $customer->code }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->address }} <br>
                          🏴󠁢󠁹󠁭󠁩󠁿{{ $customer->city }}</td>
                            <td>📞 Standard : {{ $customer->phone1 }} <br>
                            📞 Commercial : {{ $customer->phone2 }} <br>
                         📧 {{ $customer->email }} </td>

                         <td>
    @if($customer->has_b2b)
        <span class="badge bg-success">Oui</span>
    @else
        <span class="badge bg-secondary">Non</span>
    @endif
</td>



                             <td>
 <button type="button" class="btn btn-sm btn-outline-primary solde-btn" data-bs-toggle="modal" data-bs-target="#accountingModal{{ $customer->id }}" data-customer-id="{{ $customer->id }}">
                                                        {{ number_format($customer->solde, 2, ',', ' ') }} €
                                                    </button>






                                                                        

 <!-- Accounting Entries Modal -->
                   <!-- Accounting Entries Modal -->
<div class="modal fade accounting-modal" id="accountingModal{{ $customer->id }}" tabindex="-1" aria-labelledby="accountingModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountingModalLabel{{ $customer->id }}">Ecritures comptables Fournisseur : {{ $customer->name }}</h5>
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





</td>


                            <td>
    @if ($customer->blocked)
        <span class="badge bg-danger">🔴 Bloqué</span>
    @else
        <span class="badge bg-success">🟢 Actif</span>
    @endif
</td>

                            <td>
                                <!-- Bouton Modifier -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $customer->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Formulaire suppression -->
                                <form action="{{ route('supplier.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce fournisseur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Modifier -->
                        <div class="modal fade" id="editItemModal{{ $customer->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $customer->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                           <div class="modal-header">
                <h5 class="modal-title">Fournisseur :  {{ $customer->code }} - {{ $customer->name }}</h5>
                <button type="button" class="btn btn-outline-primary btn-sm ms-2" id="editBtn{{ $customer->id }}">
                <i class="fas fa-edit"></i> Modifier
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>


                                    <form action="{{ route('supplier.update', $customer->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div id="editForm{{ $customer->id }}">
    <div class="modal-body row">
        <div class="mb-3 col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required disabled>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $customer->email }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 1 : Stadard</label>
            <input type="text" name="phone1" class="form-control" value="{{ $customer->phone1 }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 2 : Commercial</label>
            <input type="text" name="phone2" class="form-control" value="{{ $customer->phone2 }}" disabled>
        </div>

<!-- === CHAMP B2B (édition) === -->
<div class="mb-3 col-md-6">
    <label class="form-label d-block">
        Plateforme B2B (site marchand)
    </label>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="has_b2b" value="1"
               id="has_b2b_edit{{ $customer->id }}" {{ $customer->has_b2b ? 'checked' : '' }}>
        <label class="form-check-label fw-bold text-success" for="has_b2b_edit{{ $customer->id }}">
            Ce fournisseur a un site B2B
        </label>
    </div>
</div>

<div class="mb-3 col-md-6">
    <label class="form-label">URL du site B2B</label>
    <input type="url" name="b2b_url" class="form-control"
           value="{{ $customer->b2b_url }}" placeholder="https://...">
</div>



        <div class="mb-3 col-md-4">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control" value="{{ $customer->city }}" disabled>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse</label>
            <input type="text" name="address" class="form-control" value="{{ $customer->address }}" disabled>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse de livraison</label>
            <input type="text" name="address_delivery" class="form-control" value="{{ $customer->address_delivery }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Pays</label>
            <input type="text" name="country" class="form-control" value="{{ $customer->country }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">SIRET</label>
            <input type="text" name="matfiscal" class="form-control" value="{{ $customer->matfiscal }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">IBAN</label>
            <input type="text" name="bank_no" class="form-control" value="{{ $customer->bank_no }}" disabled>
        </div>

<div class="mb-3 col-md-4">
    <label class="form-label">Solde :</label>
    <!-- <input type="number" step="0.01" name="solde" class="form-control"
           value="{{ old('solde', $customer->solde ?? 0) }}" readonly> -->
           <h1><button type="button" class="btn btn-outline-dark">{{$customer->solde ?? 0 }} €</button></h1>
</div>

<div class="mb-3 col-md-4">
    <label class="form-label">Plafond</label>
    <input type="number" step="0.01" name="plafond" class="form-control"
           value="{{ old('plafond', $customer->plafond ?? 0) }}" disabled>
</div>


        <div class="mb-3 col-md-4">
            <label class="form-label">Risque</label>
            <input type="number" name="risque" class="form-control" value="{{ $customer->risque }}" disabled>
        </div>







        <div class="mb-3 col-md-6">
            <label class="form-label">TVA</label>
            <select name="tva_group_id" class="form-control" disabled>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($tvaGroups as $group)
                    <option value="{{ $group->id }}" {{ $customer->tva_group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->name }} : {{ $group->rate }} %
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Groupe Remise</label>
            <select name="discount_group_id" class="form-control" disabled>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($discountGroups as $group)
                    <option value="{{ $group->id }}" {{ $customer->discount_group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->name }} : {{ $group->discount_rate }} %
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Mode de paiement</label>
            <select name="payment_mode_id" class="form-control" disabled>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($paymentModes as $mode)
                    <option value="{{ $mode->id }}" {{ $customer->payment_mode_id == $mode->id ? 'selected' : '' }}>
                        {{ $mode->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Condition de paiement</label>
            <select name="payment_term_id" class="form-control" disabled>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($paymentTerms as $term)
                    <option value="{{ $term->id }}" {{ $customer->payment_term_id == $term->id ? 'selected' : '' }}>
                        {{ $term->label }} : {{ $term->days }} Jours
                    </option>
                @endforeach
            </select>
        </div>


        
<div class="mb-3 col-md-6">
    <label class="form-label d-block">Statut Fournisseur</label>

    <!-- Hidden input toujours envoyé -->
    <input type="hidden" name="blocked" value="0">

    <!-- Switch visible -->
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="blockedSwitch{{ $customer->id }}" name="blocked" value="1"
               {{ $customer->blocked ? 'checked' : '' }} onchange="toggleBlockedLabel({{ $customer->id }})" disabled>
        <label class="form-check-label fw-bold" id="blockedLabel{{ $customer->id }}" for="blockedSwitch{{ $customer->id }}">
            {{ $customer->blocked ? 'Fournisseur Bloqué 🚫' : 'Fournisseur Actif ✅' }}
        </label>
    </div>

    <small class="form-text text-muted">
        ⚠️ Le blocage concerne uniquement l'expédition des ventes.<br>
        La facturation reste possible même si le client est bloqué.
    </small>
</div>
<script>
function toggleBlockedLabel(customerId) {
    const checkbox = document.getElementById('blockedSwitch' + customerId);
    const label = document.getElementById('blockedLabel' + customerId);
    if (checkbox.checked) {
        label.innerText = 'Fournisseur Bloqué 🚫';
    } else {
        label.innerText = 'Fournisseur Actif ✅';
    }
}
</script>






    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </div>
    </div>
</form>

                                </div>
                            </div>
                        </div>





<!-- Script pour activer les champs -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editForm{{ $customer->id }}");
    const button = document.getElementById("editBtn{{ $customer->id }}");
    let isEditable = false;

    // Sauvegarde des valeurs initiales
    const originalValues = {};
    form.querySelectorAll("input, select, textarea").forEach(el => {
        originalValues[el.name] = el.value;
    });

    button.addEventListener("click", function () {
        const fields = form.querySelectorAll("input, select, textarea");

        if (!isEditable) {
            fields.forEach(el => el.removeAttribute("disabled"));
            button.innerHTML = `<i class="fas fa-times-circle"></i> Annuler modification`;
            button.classList.remove("btn-outline-primary");
            button.classList.add("btn-outline-danger");
            isEditable = true;
        } else {
            fields.forEach(el => {
                el.setAttribute("disabled", true);
                if (originalValues.hasOwnProperty(el.name)) {
                    el.value = originalValues[el.name];
                }
            });
            button.innerHTML = `<i class="fas fa-edit"></i> Modifier`;
            button.classList.remove("btn-outline-danger");
            button.classList.add("btn-outline-primary");
            isEditable = false;
        }
    });
});
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


        <!-- Pagination avec conservation des filtres -->
    <div class="d-flex justify-content-center mt-3">
        {{ $suppliers->appends(request()->query())->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Aucun fournisseur trouvé</h5>
        <p class="text-muted">Essayez d'ajuster vos critères de recherche</p>
        <a href="{{ route('supplier.index') }}" class="btn btn-primary">Réinitialiser les filtres</a>
    </div>
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
    document.querySelectorAll('form.d-flex.flex-wrap.align-items-end input, form.d-flex.flex-wrap.align-items-end select').forEach(field => {
        field.addEventListener('input', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 1000);
        });
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
            fetch(`/suppliers/${customerId}/accounting-entries`, {
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
        const balance = debits + credits;

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
            fetch("{{ route('allsupplier.accounting-entries') }}", {
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






  </body>
</html>
