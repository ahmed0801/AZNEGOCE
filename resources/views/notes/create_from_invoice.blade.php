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
@include('partials.lh_style')
<style>
.nf-step{display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#0d6efd;color:#fff;font-size:.75rem;margin-right:.4rem}
.nf-hint{font-weight:400;color:#6b7280;font-size:.8rem;margin-left:.5rem}
.nf-cards{display:flex;flex-wrap:wrap;gap:.6rem}
.nf-card{display:flex;align-items:center;gap:.6rem;border:2px solid #e5e7eb;border-radius:10px;padding:.6rem .9rem;cursor:pointer;min-width:260px;background:#fff}
.nf-card:hover{border-color:#9ec5fe;background:#f5f9ff}
.nf-card.on{border-color:#0d6efd;background:#eef5ff}
.nf-card i{color:#9ca3af;font-size:1.1rem}.nf-card.on i{color:#0d6efd}
.nf-card small{display:block;color:#6b7280;font-size:.78rem}
.nf-empty{color:#6b7280;font-style:italic;padding:.4rem 0}
.nf-types{display:flex;flex-wrap:wrap;gap:.6rem}
.nf-type{flex:1 1 280px;display:flex;align-items:flex-start;gap:.6rem;border:2px solid #e5e7eb;border-radius:10px;padding:.7rem .9rem;cursor:pointer;margin:0}
.nf-type:has(input:checked){border-color:#0d6efd;background:#eef5ff}
.nf-type input{margin-top:.3rem}
.nf-type small{display:block;color:#6b7280;font-size:.78rem}
#linesTable tr.nf-group td{background:#eef2f7;font-weight:700;text-align:left;color:#374151}
#linesTable tr.nf-line.off td{opacity:.55}
#linesTable tr.nf-line.off input.form-control{background:#f3f4f6}
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
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="lh-bar">
    <h3 class="lh-title"><i class="fas fa-file-invoice text-primary"></i> Nouvel avoir fournisseur</h3>
    <div class="lh-btns"><a href="{{ route('notes.list') }}" class="btn btn-light border"><i class="fas fa-list"></i> Liste des avoirs</a></div>
</div>

<form action="{{ route('notes.store') }}" method="POST" id="noteForm">
    @csrf
    <input type="hidden" name="type" value="from_invoice">
    <input type="hidden" name="tva_rate" id="tva_rate" value="0">
    <input type="hidden" id="tva_display" value="0">
    <div id="invoiceInputs"></div>

    <div class="pg-card">
        <h6><span class="nf-step">1</span> Fournisseur et date</h6>
        <div class="row top-row">
            <div class="col-md-6 mb-2">
                <label class="form-label">Fournisseur</label>
                <select name="supplier_id" class="form-control select2" required>
                    <option value="">Choisir le fournisseur…</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Date de l’avoir</label>
                <input type="date" name="note_date" class="form-control order_date" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
    </div>

    <div class="pg-card">
        <h6><span class="nf-step">2</span> Sur quelle(s) facture(s) porte l’avoir ?</h6>
        <div id="invoiceCards" class="nf-cards"><div class="nf-empty">Choisissez d’abord un fournisseur : ses factures validées apparaîtront ici.</div></div>
    </div>

    <div class="pg-card">
        <h6><span class="nf-step">3</span> Pourquoi cet avoir ?</h6>
        <div class="nf-types">
            <label class="nf-type"><input type="radio" name="return_goods" value="1" checked>
                <span><i class="fas fa-undo"></i> <b>Je renvoie la marchandise</b><small>Un retour fournisseur est créé à la validation et le stock diminue.</small></span></label>
            <label class="nf-type"><input type="radio" name="return_goods" value="0">
                <span><i class="fas fa-tags"></i> <b>Geste commercial / erreur de prix</b><small>Pas de mouvement de stock : seul le montant dû au fournisseur baisse.</small></span></label>
        </div>
    </div>

    <div class="pg-card" id="linesCard" style="display:none">
        <h6><span class="nf-step">4</span> Que voulez-vous créditer ?
            <span class="nf-hint">Cochez les lignes concernées, ajustez la quantité (ou le prix pour un geste commercial).</span></h6>
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-text-small" id="linesTable">
            <thead><tr>
                <th style="width:36px"><input type="checkbox" id="checkAll" title="Tout cocher"></th>
                <th>Article</th><th style="width:90px">Qté</th><th style="width:110px">PU HT</th><th style="width:80px">Rem.%</th><th style="width:80px">TVA%</th><th style="width:110px">Total HT</th><th style="width:110px">Total TTC</th>
            </tr></thead>
            <tbody id="lines"></tbody>
        </table>
        </div>
    </div>

    <div class="pg-card"><label class="form-label">Notes / Commentaire</label>
        <textarea name="notes" class="form-control" rows="2" placeholder="Motif de l’avoir, n° de dossier fournisseur…"></textarea></div>

    <div class="lh-actionbar">
        <div class="ab-totals"><span>Total HT : <b id="grandTotal" class="text-success">0.00</b> €</span><span>Total TTC : <b id="grandTotalTTC" class="text-danger">0.00</b> €</span></div>
        <div class="ab-btns">
            <a href="{{ route('notes.list') }}" class="btn btn-light border">Annuler</a>
            <button type="submit" name="action" value="save" class="btn btn-primary"><i class="fas fa-save"></i> Brouillon</button>
            <button type="submit" name="action" value="validate" class="btn btn-success"><i class="fas fa-check"></i> Valider l’avoir</button>
        </div>
    </div>
</form>
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



<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script>
let lineIndex = 0;
const fmt = v => (Math.round((v + Number.EPSILON) * 100) / 100).toFixed(2);
let INVOICES = [];
let CHOSEN = {};

$(document).ready(function () {
    $('.select2').select2({ width: '100%' });

    function renderCards() {
        const $c = $('#invoiceCards').empty();
        if (!$('select[name="supplier_id"]').val()) { $c.html('<div class="nf-empty">Choisissez d’abord un fournisseur : ses factures validées apparaîtront ici.</div>'); return; }
        if (!INVOICES.length) { $c.html('<div class="nf-empty">Aucune facture validée avec des lignes encore créditables pour ce fournisseur.</div>'); return; }
        INVOICES.forEach(inv => {
            let tot = 0; inv.lines.forEach(l => tot += l.quantity * l.unit_price_ht * (1 - (l.remise || 0) / 100));
            const d = inv.invoice_date ? new Date(inv.invoice_date).toLocaleDateString('fr-FR') : '';
            $c.append(`<div class="nf-card ${CHOSEN[inv.id] ? 'on' : ''}" data-id="${inv.id}">
                <i class="fas ${CHOSEN[inv.id] ? 'fa-check-circle' : 'fa-circle'}"></i>
                <div><b>Facture ${inv.numdoc}</b><small>${d} · ${inv.lines.length} ligne(s) · ${fmt(tot)} € HT créditables</small></div></div>`);
        });
    }

    function renderLines() {
        $('#lines').empty(); lineIndex = 0;
        const chosen = INVOICES.filter(i => CHOSEN[i.id]);
        $('#invoiceInputs').html(chosen.map(i => `<input type="hidden" name="invoices[]" value="${i.id}">`).join(''));
        $('#linesCard').toggle(chosen.length > 0);
        chosen.forEach(inv => {
            $('#lines').append(`<tr class="nf-group"><td colspan="8"><i class="fas fa-file-invoice"></i> Facture ${inv.numdoc}</td></tr>`);
            inv.lines.forEach(line => {
                const max = Math.abs(line.quantity);
                const tvaL = (line.tva !== undefined && line.tva !== null) ? parseFloat(line.tva) : (parseFloat(inv.tva_rate) || 0);
                $('#lines').append(`
                <tr class="nf-line off">
                    <td class="text-center"><input type="checkbox" class="pick"></td>
                    <td class="text-start">${line.article_code} — ${line.item_name || '-'}
                        <input type="hidden" class="f" data-n="purchase_invoice_id" value="${inv.id}">
                        <input type="hidden" class="f" data-n="article_code" value="${line.article_code}"></td>
                    <td><input type="number" step="any" min="0" max="${max}" class="form-control qty f" data-n="quantity" value="${max}" data-max="${max}"></td>
                    <td><input type="number" step="0.01" class="form-control pu f" data-n="unit_price_ht" value="${line.unit_price_ht}"></td>
                    <td><input type="number" step="0.01" class="form-control remise f" data-n="remise" value="${line.remise || 0}"></td>
                    <td><input type="text" class="form-control tva_ligne f" data-n="tva" value="${tvaL.toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control total" value="0.00" readonly></td>
                    <td><input type="text" class="form-control totalttc" value="0.00" readonly></td>
                </tr>`);
            });
        });
        syncNames(); recalculate();
    }

    // les champs d'une ligne décochée ne sont ni envoyés ni comptés
    function syncNames() {
        let i = 0;
        $('#lines tr.nf-line').each(function () {
            const on = $(this).find('.pick').is(':checked');
            $(this).toggleClass('off', !on);
            $(this).find('.f').each(function () {
                if (on) { $(this).attr('name', `lines[${i}][${$(this).data('n')}]`).prop('disabled', false); }
                else { $(this).removeAttr('name').prop('disabled', true); }
            });
            if (on) i++;
        });
    }

    function recalculate() {
        let totalHT = 0, totalTTC = 0;
        $('#lines tr.nf-line').each(function () {
            const on = $(this).find('.pick').is(':checked');
            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const pu = parseFloat($(this).find('.pu').val()) || 0;
            const remise = parseFloat($(this).find('.remise').val()) || 0;
            const tvaL = parseFloat($(this).find('.tva_ligne').val()) || 0;
            const lineHT = on ? qty * pu * (1 - remise / 100) : 0;
            const lineTTC = lineHT * (1 + tvaL / 100);
            $(this).find('.total').val(fmt(lineHT));
            $(this).find('.totalttc').val(fmt(lineTTC));
            totalHT += lineHT; totalTTC += lineTTC;
        });
        $('#grandTotal').text(fmt(totalHT));
        $('#grandTotalTTC').text(fmt(totalTTC));
        const first = INVOICES.find(i => CHOSEN[i.id]);
        $('#tva_rate').val(first ? (parseFloat(first.tva_rate) || 0) : 0);
    }

    function loadInvoices() {
        CHOSEN = {}; INVOICES = [];
        const sid = $('select[name="supplier_id"]').val();
        renderCards(); renderLines();
        if (!sid) return;
        $('#invoiceCards').html('<div class="nf-empty">Chargement…</div>');
        $.getJSON("{{ route('invoices.search') }}", { supplier_id: sid, status: 'validée' }, function (data) {
            INVOICES = data; renderCards();
            if (INVOICES.length === 1) { CHOSEN[INVOICES[0].id] = true; renderCards(); renderLines(); }
        }).fail(function () { $('#invoiceCards').html('<div class="nf-empty text-danger">Impossible de charger les factures.</div>'); });
    }

    $('select[name="supplier_id"]').on('change', loadInvoices);
    $('#invoiceCards').on('click', '.nf-card', function () {
        const id = $(this).data('id'); CHOSEN[id] = !CHOSEN[id];
        renderCards(); renderLines();
    });
    $('#lines').on('change', '.pick', function () { syncNames(); recalculate(); });
    $('#checkAll').on('change', function () { $('#lines .pick').prop('checked', this.checked); syncNames(); recalculate(); });
    $('#lines').on('input', '.qty, .pu, .remise', function () {
        const $q = $(this).closest('tr').find('.qty');
        const maxQty = parseFloat($q.data('max')) || 0;
        if ((parseFloat($q.val()) || 0) > maxQty) { $q.val(maxQty); alert('La quantité ne peut pas dépasser la quantité encore créditable de la facture.'); }
        if (!$(this).closest('tr').find('.pick').is(':checked')) { $(this).closest('tr').find('.pick').prop('checked', true); syncNames(); }
        recalculate();
    });
    $('#noteForm').on('submit', function (e) {
        const n = $('#lines .pick:checked').length;
        if (!n) { e.preventDefault(); alert('Cochez au moins une ligne à créditer.'); return; }
        let bad = false; $('#lines .pick:checked').each(function () { if (!(parseFloat($(this).closest('tr').find('.qty').val()) > 0)) bad = true; });
        if (bad) { e.preventDefault(); alert('Une ligne cochée a une quantité à zéro.'); }
    });
});
</script>
















  </body>
</html>
