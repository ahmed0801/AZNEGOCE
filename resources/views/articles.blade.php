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
                        <li class="nav-item"><a href="/sales/delivery/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Devis & Précommandes</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/salesnotes/list"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                      <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-credit-card"></i></span><h4 class="text-section">Comptabilité</h4></li>
                                                <li class="nav-item {{ Route::is('generalaccounts.index') ? 'active' : '' }}">
                            <a href="{{ route('generalaccounts.index') }}"><i class="fas fa-book"></i><p>Comptes Généraux</p></a>
                        </li>
                                                <li class="nav-item {{ Route::is('payments.index') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i><p>Règlements</p></a>
                        </li>
                                                <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item active"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
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

        <h4>Articles :                         
          <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createItemModal">Nouveau 
           <i class="fas fa-plus-circle ms-2"></i>
          </button>
</h4>


<!-- Modal -->
<div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="createItemModalLabel">Créer un Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <form id="createItemForm" action="{{ route('items.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Code -->
                        <div class="mb-3 col-md-4">
                            <label for="code" class="form-label">Code article</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>

                        <!-- Nom -->
                        <div class="mb-3 col-md-8">
                            <label for="name" class="form-label">Désignation</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-3 col-md-6">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">-- Choisir --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Marque -->
                        <div class="mb-3 col-md-6">
                            <label for="brand_id" class="form-label">Marque</label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                <option value="">-- Choisir --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Unité -->
                        <div class="mb-3 col-md-6">
                            <label for="unit_id" class="form-label">Unité</label>
                            <select class="form-control" id="unit_id" name="unit_id">
                                <option value="">-- Choisir --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->label ?? $unit->code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Groupe TVA -->
                        <div class="mb-3 col-md-6">
                            <label for="tva_group_id" class="form-label">TVA</label>
                            <select class="form-control" id="tva_group_id" name="tva_group_id">
                                <option value="">-- Choisir --</option>
                                @foreach($tvaGroups as $tva)
                                    <option value="{{ $tva->id }}">{{ $tva->name }} ({{ $tva->rate }}%)</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prix d'achat -->
                        <div class="mb-3 col-md-3">
                            <label for="cost_price" class="form-label">Prix d’achat</label>
                            <input type="number" step="0.01" class="form-control" name="cost_price" value="0.00">
                        </div>

                        <!-- Marge (%) -->
<div class="mb-3 col-md-2">
    <label for="margin" class="form-label">Marge (%)</label>
    <input type="number" step="0.01" id="margin" class="form-control" placeholder="EX : 20" style="font-style: italic; background-color: #f0f0f0;" autocomplete="off">
</div>


                        <!-- Prix de vente -->
                        <div class="mb-3 col-md-3">
                            <label for="sale_price" class="form-label">Prix de vente</label>
                            <input type="number" step="0.01" class="form-control" name="sale_price" value="0.00">
                        </div>

                        <!-- Code-barres -->
                        <div class="mb-3 col-md-4">
                            <label for="barcode" class="form-label">Code-barres</label>
                            <input type="text" class="form-control" name="barcode" id="barcode">
                        </div>

                        <!-- Stock min/max -->
                        <div class="mb-3 col-md-3">
                            <label for="stock_min" class="form-label">Stock minimum</label>
                            <input type="number" class="form-control" name="stock_min" value="0">
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="stock_max" class="form-label">Stock maximum</label>
                            <input type="number" class="form-control" name="stock_max" value="0">
                        </div>

<!-- Code Fournisseur -->
<div class="mb-3 col-md-6">
    <label for="codefournisseur" class="form-label">Code Fournisseur</label>
    <select name="codefournisseur" id="codefournisseur" class="form-select">
        <option value="">-- Choisir --</option>
        @foreach(\App\Models\Supplier::all() as $supplier)
            <option value="{{ $supplier->code }}">{{ $supplier->code }} - {{ $supplier->name }}</option>
        @endforeach
    </select>
</div>


                        <!-- Magasin -->
<div class="mb-3 col-md-6">
    <label for="store_id" class="form-label">Magasin</label>
    <select name="store_id" class="form-control">
        <option value="">-- Choisir --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}">{{ $store->name }}</option>
        @endforeach
    </select>
</div>

<!-- Emplacement -->
<div class="mb-3 col-md-6">
    <label for="location" class="form-label">Emplacement</label>
    <input type="text" class="form-control" name="location" placeholder="Ex : Rayon A2 - Étage 1">
</div>



                        <!-- Description -->
                        <div class="mb-3 col-12">
                            <label for="description" class="form-label">Description technique</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
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
</div>


<form method="GET" action="{{ route('articles.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
    <input type="text" name="search" class="form-control form-control-sm" style="width: 170px;"
           placeholder="🔍 Rechercher..." value="{{ request('search') }}">

    <select name="brand_id" class="form-select form-select-sm" style="width: 120px;">
        <option value="">Marques (Tout)</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>

    <select name="category_id" class="form-select form-select-sm" style="width: 130px;">
        <option value="">Catégories (Tout)</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select name="codefournisseur" class="form-select form-select-sm" style="width: 140px;">
        <option value="">Fournisseurs (Tout)</option>
        @foreach(\App\Models\Supplier::all() as $supplier)
            <option value="{{ $supplier->code }}" {{ request('codefournisseur') == $supplier->code ? 'selected' : '' }}>
                {{ $supplier->code }} - {{ $supplier->name }}
            </option>
        @endforeach
    </select>

    <select name="store_id" class="form-select form-select-sm" style="width: 120px;">
        <option value="">Magasins (Tout)</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                {{ $store->name }}
            </option>
        @endforeach
    </select>

    <select name="is_active" class="form-select form-select-sm" style="width: 110px;">
        <option value="">Statut (Tout)</option>
        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Autorisé</option>
        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Bloqué</option>
    </select>

    <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
        <i class="fas fa-filter me-1"></i> Filtrer
    </button>

    <button type="submit" name="action" value="export" formaction="{{ route('articles.export') }}"
            class="btn btn-outline-success btn-sm px-3">
        <i class="fas fa-file-excel me-1"></i> Exporter
    </button>

    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary btn-sm px-3">
        <i class="fas fa-undo me-1"></i> Réinitialiser
    </a>
</form>



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
        <table class="table table-bordered table-hover table-text-small" id="itemsTable">
            <thead class="table-dark">
                <tr>
                    <th>Réference</th>
                    <th>Désignation</th>
                    <th>Marque</th>
                    <th>Catégorie</th>
                    <th>Prix A.HT</th>
                    <th>Prix V.HT</th>
                     <th>Fournisseur</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
<td>
    {{ $item->code }}<br>
    @if ($item->is_active)
        <span class="badge bg-success badge-very-sm">🟢 actif</span>
    @else
        <span class="badge bg-danger badge-very-sm">🔴 bloqué</span>
    @endif
</td>


                        <td>{{ $item->name }}</td>
                        <td>{{ $item->brand->name ?? '-' }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ number_format($item->cost_price, 2, ',', ' ') }} €</td>
@php
    $marge = $item->sale_price - $item->cost_price;
    $marge_pct = $item->cost_price > 0 ? ($marge / $item->cost_price) * 100 : 0;
@endphp

<td>
    {{ number_format($item->sale_price, 2, ',', ' ') }} € <br>
    <small class="{{ $marge >= 0 ? 'text-success' : 'text-danger' }}">
        {{ $marge >= 0 ? '+' : '' }}{{ number_format($marge, 2, ',', ' ') }} €
        ({{ number_format($marge_pct, 0, ',', ' ') }}%)
    </small>
</td>

                        
<td>
    {{ $item->supplier->name ?? '-' }}
    <br>
    <small class="text-muted">{{ $item->codefournisseur ?? '' }}</small>
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
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr>
              <th>Type</th>
              <!-- <th>Type</th> -->
              <th>QTE</th>
              <th>Magasin</th>
              <th>Prix.HT</th>
              <th>Source</th>
              <th>Référence</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($item->stockMovements()->latest()->limit(20)->get() as $movement)
              <tr>
                <td>
                                    <span class="badge bg-{{ $movement->quantity >= 0 ? 'success' : 'danger' }}">
                    {{ ucfirst($movement->type) }}
                  </span><br>
                  {{ $movement->created_at->format('d/m/Y H:i') }}</td>
                <!-- <td>
                  <span class="badge bg-{{ $movement->quantity >= 0 ? 'success' : 'danger' }}">
                    {{ ucfirst($movement->type) }}
                  </span>
                </td> -->
                <td>{{ $movement->quantity }}</td>
                <td>{{ $movement->store->name ?? '-' }}</td>
        <td>{{ $movement->cost_price ? number_format($movement->cost_price, 2) . ' €' : '-' }}</td>  {{-- Affiche prix coûtant --}}
                <td>{{ $movement->supplier_name ?? '-' }}</td>
                <td>{{ $movement->reference ?? '-' }}</td>
                <td>{{ $movement->note ?? '-' }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Aucun mouvement trouvé</td></tr>
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

                        <td>
                           <!-- Modifier -->
<!-- Modifier -->
<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}" title="Consulter">
    <i class="fas fa-edit"></i>
</button>


                          <!-- Ajuster le stock -->
<button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#adjustStockModal{{ $item->id }}" title="Ajuster le stock">
    <i class="fas fa-cubes"></i>
</button>

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

                           


                            <!-- Supprimer -->
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet article ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>






                 <!-- Modal Modifier Article -->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Modifier l'article : {{ $item->code }}</h5>
                <button type="button" class="btn btn-outline-primary btn-sm ms-2" id="editBtn{{ $item->id }}">
                <i class="fas fa-edit"></i> Modifier
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row" id="editForm{{ $item->id }}">
                        <div class="mb-3 col-md-8">
                            <label class="form-label">Désignation</label>
                            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required disabled>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="{{ $item->code }}" required disabled>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-select" disabled>
                                <option value="">-- Choisir --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Marque</label>
                            <select name="brand_id" class="form-select" disabled>
                                <option value="">-- Choisir --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $item->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Unité</label>
                            <select name="unit_id" class="form-select" disabled>
                                <option value="">-- Choisir --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">TVA</label>
                            <select class="form-select" name="tva_group_id" disabled>
                                <option value="">-- Choisir --</option>
                                @foreach($tvaGroups as $tva)
                                    <option value="{{ $tva->id }}" {{ $item->tva_group_id == $tva->id ? 'selected' : '' }}>
                                        {{ $tva->name }} ({{ $tva->rate }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-3">
                            <label class="form-label">Prix Achat</label>
                            <input type="number" name="cost_price" step="0.01" class="form-control" value="{{ $item->cost_price }}" readonly>
                        </div>


                        <!-- Marge (%) - Calculée -->
<!-- Marge (%) - Calculée -->
<div class="mb-3 col-md-2">
    <label class="form-label">Marge (%)</label>
    <input 
        type="number" 
        step="0.01" 
        id="edit-margin-{{ $item->id }}" 
        class="form-control" 
        placeholder="Calculée automatiquement"
        style="font-style: italic; background-color: #f0f0f0;" 
        disabled>
</div>

                        

                        <div class="mb-3 col-md-3">
                            <label class="form-label">Prix Vente</label>
                            <input type="number" name="sale_price" step="0.01" class="form-control" value="{{ $item->sale_price }}" disabled>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Code-barres</label>
                            <input type="text" name="barcode" class="form-control" value="{{ $item->barcode }}" disabled>
                        </div>

                        <div class="mb-3 col-md-3">
                            <label class="form-label">Stock Min</label>
                            <input type="number" name="stock_min" class="form-control" value="{{ $item->stock_min }}" disabled>
                        </div>

                        <div class="mb-3 col-md-3">
                            <label class="form-label">Stock Max</label>
                            <input type="number" name="stock_max" class="form-control" value="{{ $item->stock_max }}" disabled>
                        </div>

                        <!-- Code Fournisseur -->
<div class="mb-3 col-md-6">
    <label for="codefournisseur" class="form-label">Fournisseur</label>
    <select name="codefournisseur" id="codefournisseur" class="form-select" disabled>
        <option value="{{ $item->codefournisseur }}">-- Choisir --</option>
        @foreach(\App\Models\Supplier::all() as $supplier)
            <option value="{{ $supplier->code }}" {{ $item->codefournisseur == $supplier->code ? 'selected' : '' }}>{{ $supplier->code }} - {{ $supplier->name }}</option>
        @endforeach
    </select>
</div>


                        <div class="mb-3 col-md-6">
                            <label class="form-label">Magasin</label>
                            <select name="store_id" class="form-select" disabled>
                                <option value="">-- Choisir --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ $item->store_id == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Emplacement</label>
                            <input type="text" name="location" class="form-control" value="{{ $item->location }}" disabled>
                        </div>

                           
<div class="mb-3 col-md-6">
    <label class="form-label d-block">Statut Article</label>

    <!-- Hidden input toujours envoyé -->
    <input type="hidden" name="is_active" value="0">

    <!-- Switch visible -->
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="blockedSwitch{{ $item->id }}" name="is_active" value="1"
               {{ $item->is_active ? 'checked' : '' }} onchange="toggleBlockedLabel({{ $item->id }})" disabled>
        <label class="form-check-label fw-bold" id="blockedLabel{{ $item->id }}" for="blockedSwitch{{ $item->id }}">
            {{ $item->is_active ? 'Vente Autorisée ✅' : 'Vente Bloquée 🚫' }}
        </label>
    </div>

    <small class="form-text text-muted">
        ⚠️ Le blocage concerne uniquement l'expédition des ventes.<br>
        La facturation reste possible même si l'article est bloqué, ce blocage est utile aussi au cours de l'inventaire.
    </small>
</div>
<script>
function toggleBlockedLabel(itemid) {
    const checkbox = document.getElementById('blockedSwitch' + itemid);
    const label = document.getElementById('blockedLabel' + itemid);
    if (checkbox.checked) {
        label.innerText = 'Vente Autorisée ✅';
    } else {
        label.innerText = 'Vente Bloquée 🚫';
    }
}
</script>

                        <div class="mb-3 col-6">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" disabled>{{ $item->description }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script pour activer les champs -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editForm{{ $item->id }}");
    const button = document.getElementById("editBtn{{ $item->id }}");
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





  </body>
</html>
