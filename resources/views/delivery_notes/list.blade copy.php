<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Liste des Bons de Livraison</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    
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
        #panierDropdown + .dropdown-menu { width: 900px; min-width: 350px; padding: 10px; border-radius: 8px; }
        .panier-dropdown { width: 100%; min-width: 350px; }
        .panier-dropdown .notif-item { padding: 10px; margin-bottom: 5px; border-bottom: 1px solid #ddd; }
        .dropdown-title { font-weight: bold; margin-bottom: 10px; }
        .notif-scroll { padding: 10px; }
        .notif-center { padding: 5px 0; }
        .dropdown-footer { padding: 10px; border-top: 1px solid #ddd; }
        .table { width: 100%; margin-bottom: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f2f2f2; }
        .btn-sm { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
        .text-muted { font-size: 0.85rem; }
        .text-center { text-align: center; }
        .card { border-radius: 12px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card h3 { font-size: 1.8rem; color: #007bff; margin-bottom: 1rem; font-weight: 700; }
        .card h6 { font-size: 1rem; color: #6c757d; }
        .card-body { padding: 2rem; }
        .card .text-info { color: #17a2b8 !important; }
        .btn-primary { font-size: 1.1rem; padding: 1rem 1.5rem; border-radius: 8px; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #0056b3; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); }


.filter-box {
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    padding: 6px 8px !important;
    background: #f2f1f1ff;
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
                    <div class="logo-header" data-background-color="dark">
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                        </div>
                        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                    </div>
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
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


                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramétres</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
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
            </div>

            <div class="container">
                <div class="page-inner">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <h4>📋 Liste des bons de livraison :
                        <a href="{{ route('sales.delivery.create') }}" class="btn btn-outline-success btn-round ms-2">
                            Nouvelle Commande <i class="fas fa-plus-circle ms-2"></i>
                        </a>
                        
<a href="{{ route('salesinvoices.create_grouped') }}"
   class="btn btn-outline-success btn-round ms-2">


    <!-- Icône d’information -->
<span 
    style="cursor: pointer;"
    data-bs-toggle="popover"
    data-bs-trigger="hover focus"
    data-bs-placement="right"
    title="À quoi sert cette fonction ?"
    data-bs-content="Ce bouton permet de collecter tous les BL et retours non facturés d’un client afin de générer une seule facture groupée. Très utile pour les factures de fin de mois des garages, professionnels ou clients réguliers.">

    Nouvelle Facture Groupée <i class="fas fa-plus-circle ms-2"></i>

</span>


</a>
                    </h4>


                    

                    <div class="filter-box mb-2 p-2">
    <form method="GET"
          action="{{ route('delivery_notes.list') }}"
          class="d-flex flex-wrap align-items-end gap-2">

        {{-- Client --}}
        <select name="numclient"
                class="form-select form-select-sm select2"
                style="width: 140px;">
            <option value="">Client (Tous)</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->code }}"
                    {{ request('numclient') == $customer->code ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>
        

        {{-- Vendeur --}}
        <select name="vendeur"
                class="form-select form-select-sm"
                style="width: 100px;">
            <option value="">Vendeurs (Tous)</option>
            @foreach($vendeurs as $vendeur)
                <option value="{{ $vendeur }}"
                    {{ request('vendeur') == $vendeur ? 'selected' : '' }}>
                    {{ $vendeur }}
                </option>
            @endforeach
        </select>



        <!-- Facturé / Non facturé -->
        <div class="col-md-1 col-sm-6">
            <label class="form-label small fw-bold">Facturation</label>
            <select name="facture_status" class="form-select form-select-sm">
                <option value="">Tous</option>
                <option value="facture"     {{ request('facture_status') == 'facture'     ? 'selected' : '' }}>Facturés</option>
                <option value="non_facture" {{ request('facture_status') == 'non_facture' ? 'selected' : '' }}>Non facturés</option>
            </select>
        </div>

        <!-- Numéro BL -->
        <div class="col-md-1 col-sm-6">
            <label class="form-label small fw-bold">N° BL</label>
            <input type="text" name="search_bl" class="form-control form-control-sm"
                   placeholder="Ex: BL-2026-..." value="{{ request('search_bl') }}">
        </div>



        <!-- Dans la row g-2 du formulaire filtre -->
<div class="col-md-1 col-sm-6">
    <label class="form-label small fw-bold">Véhicule</label>
    <input type="text" name="search_vehicle" class="form-control form-control-sm"
           placeholder="Immat/Marque/Modèle" 
           value="{{ request('search_vehicle') }}">
</div>



        <!-- Article (code ou nom) -->
        <div class="col-md-1 col-sm-6">
            <label class="form-label small fw-bold">Article</label>
            <input type="text" name="search_article" class="form-control form-control-sm"
                   placeholder="Réf ou Desc..." value="{{ request('search_article') }}">
        </div>



        {{-- Statut BL --}}
        <select name="status"
                class="form-select form-select-sm"
                style="width: 100px;">
            <option value="">Statut BL</option>
            <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
            <option value="expédié" {{ request('status') == 'expédié' ? 'selected' : '' }}>Expédié</option>
            <option value="annulé" {{ request('status') == 'annulé' ? 'selected' : '' }}>Annulé</option>
        </select>

        {{-- Dates --}}
        <input type="date"
               name="date_from"
               class="form-control form-control-sm"
               style="width: 90px;"
               value="{{ request('date_from') }}">

        <span class="mx-0">à</span>

        <input type="date"
               name="date_to"
               class="form-control form-control-sm"
               style="width: 90px;"
               value="{{ request('date_to') }}">

        {{-- Boutons --}}
        <button type="submit"
                class="btn btn-outline-primary btn-sm px-3">
            <i class="fas fa-filter me-1"></i> Filtrer
        </button>

        <a href="{{ route('delivery_notes.list') }}"
           class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-undo me-1"></i> Réinitialiser
        </a>

    </form>
</div>



                                                                                                    <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $deliveryNotes->appends(request()->query())->links() }}
</div>


                    @foreach ($deliveryNotes as $deliveryNote)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                <div>
                                    <h6 class="mb-0">
                                        <strong>BL N° : {{ $deliveryNote->numdoc }}</strong>
                                        (&#x1F482;{{ $deliveryNote->numclient }} – {{ $deliveryNote->customer->name?? 'Client inconnu'}} )
                                        <span class="text-muted small">- 📆{{ \Carbon\Carbon::parse($deliveryNote->delivery_date)->format('d/m/Y') }}</span>
                                    </h6>
     
 <span class="badge bg-{{ $deliveryNote->status === 'en_cours' ? 'warning' : ($deliveryNote->status === 'expédié' ? 'success' : 'danger') }}">
                                           BL {{ ucfirst($deliveryNote->status) }}
  </span>  
  <span class="badge bg-{{ $deliveryNote->status_livraison === 'non_livré' ? 'warning' : ($deliveryNote->status_livraison === 'livré' ? 'success' : 'danger') }}">
                                           {{ ucfirst($deliveryNote->status_livraison) }}
                                        </span> 

                                                                      <span class="badge bg-info">@if($deliveryNote->salesOrder) CMD: {{ $deliveryNote->salesOrder->numdoc ?? '-' }} 
                                                                         @endif</span>

     <span class="badge rounded-pill text-bg-light"><i class="fas fa-user-tie"></i> Vendeur :  {{ $deliveryNote->vendeur}}</span>




                                                                                              <span class="text-muted small">
                                     @if($deliveryNote->invoiced)
                            ☑Facturé
                            @endif
                            @if($deliveryNote->salesReturns()->exists())
                                ↪︎ {{ count($deliveryNote->salesReturns) }} Retour(s) associé(s)
                            @endif

                           

                        </span>


                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $deliveryNote->id }})">
                                        ➕ Détails
                                    </button>
                                    <a href="{{ route('delivery_notes.export_single', $deliveryNote->id) }}" class="btn btn-xs btn-outline-success">
                                        EXCEL <i class="fas fa-file-excel"></i>
                                    </a>
                                    <a href="{{ route('delivery_notes.print_single', $deliveryNote->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                        PDF <i class="fas fa-print"></i>
                                    </a>

                                    @if($deliveryNote->status === 'en_cours' && $deliveryNote->status_livraison === 'non_livré')
    <form action="{{ route('delivery_notes.validate', $deliveryNote->id) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Valider cette expédition ?')">
        @csrf
        <button type="submit" class="btn btn-xs btn-outline-success">
            ✅ Valider l'exp.
        </button>
    </form>
@endif



                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($deliveryNote->status === 'en_cours')
                                                <a class="dropdown-item" href="{{ route('delivery_notes.edit', $deliveryNote->id) }}">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <form action="{{ route('delivery_notes.validate', $deliveryNote->id) }}" method="POST" onsubmit="return confirm('Valider cette expédition ?')" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check"></i> Valider l'expédition
                                                    </button>
                                                </form>



                                                <form action="{{ route('delivery_notes.cancel', $deliveryNote->id) }}" method="POST" onsubmit="return confirm('Annuler cette expédition ?')" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-times"></i> Annuler l'expédition
                                                    </button>
                                                </form>
                                            @endif

                                            @if($deliveryNote->status_livraison === 'non_livré')
                                                                                            <form action="{{ route('delivery_notes.ship', $deliveryNote->id) }}" method="POST" onsubmit="return confirm('Valider cette expédition ?')" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check"></i> Confirmer la livraison
                                                    </button>
                                                </form>
                                                @endif


                                                @if($deliveryNote->status === 'expédié' || $deliveryNote->status_livraison === 'livré')
                                        <a class="dropdown-item" href="{{ route('delivery_notes.salesreturns.create', $deliveryNote->id) }}">
                                        <i class="fas fa-undo"></i> Créer un retour
                                    </a>


@endif

@if($deliveryNote->status === 'expédié')
                                                 <a class="dropdown-item" href="#" data-toggle="modal" data-target="#commentModal" 
                                                   onclick="setCommentForm('{{ route('delivery_notes.shipping_note', $deliveryNote->id) }}', {{ $deliveryNote->id }})">
                                                    <i class="fas fa-truck"></i> Bordereau d'envoi
                                                </a>
                                           
                                            

                                @if($deliveryNote->salesReturns()->exists())
                                    @foreach($deliveryNote->salesReturns as $return)
                                        <a class="dropdown-item" href="{{ route('delivery_notes.salesreturns.show', $return->id) }}">
                                            <i class="fas fa-eye"></i> Retour #{{ $return->numdoc }} ({{ ucfirst($return->type) }})
                                        </a>
                                    @endforeach
                                @endif

                                 @endif
                                 
                                @if(!$deliveryNote->invoiced)
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.create_direct', $deliveryNote->id) }}">
                                                        <i class="fas fa-file-invoice"></i> Créer facture directe
                                                    </a>
                                                @endif



                                        </div>
                                    </div>

                                    
                                </div>
                            </div>

                            <div id="lines-{{ $deliveryNote->id }}" class="card-body d-none bg-light">
                             <h6 class="fw-bold mb-3"><i class="fa fa-solid fa-car"></i> : {{ $deliveryNote->vehicle ? ($deliveryNote->vehicle->license_plate . ' (' . $deliveryNote->vehicle->brand_name . ' ' . $deliveryNote->vehicle->model_name . ')') : '-' }}</h6>

                                @if($deliveryNote->notes)
                                <h6 class="fw-bold mb-3">🧾 Note : {{$deliveryNote->notes}}</h6>
                                @endif
                                <!-- <h6 class="fw-bold mb-3">🧾 Lignes du bon de livraison</h6> -->
                                <table class="table table-sm table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Code Article</th>
                                            <th>Désignation</th>
                                            <th>Qté Livrée</th>
                                            <th>PU HT</th>
                                            <th>Remise (%)</th>
                                            <th>Total Ligne</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deliveryNote->lines as $line)
                                            <tr>
                                                <td>{{ $line->article_code }}</td>
                                                <td>{{ $line->item->name ?? '-' }}</td>
                                                <td class="text-center">{{ $line->delivered_quantity }}</td>
                                                <td class="text-end">{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                                <td class="text-end">{{ $line->remise }}%</td>
                                                <td class="text-end">{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="text-end mt-3">
                                    <div class="p-3 bg-white border rounded d-inline-block">
                                        <strong>Total HT :</strong> {{ number_format($deliveryNote->total_ht, 2, ',', ' ') }} €<br>
                                        <strong>Total TTC :</strong> {{ number_format($deliveryNote->total_ttc, 2, ',', ' ') }} €
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                                                                                <!-- Pagination avec conservation des filtres -->
<div class="d-flex justify-content-center mt-3">
    {{ $deliveryNotes->appends(request()->query())->links() }}
</div>


            </div>



<!-- Modal pour le commentaire -->
            <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentModalLabel">Ajouter un commentaire</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="commentForm" method="POST" target="_blank">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="comment">Commentaire (facultatif)</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Saisissez un commentaire (optionnel)"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Générer PDF</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- fin commentaire -->


            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.</div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '15%' });
        });

        function toggleLines(id) {
            const section = document.getElementById('lines-' + id);
            section.classList.toggle('d-none');
        }

         function setCommentForm(url, id) {
            document.getElementById('commentForm').action = url;
                        document.getElementById('comment').value = ''; // Réinitialiser le champ de commentaire

        }
    </script>
</body>
</html>