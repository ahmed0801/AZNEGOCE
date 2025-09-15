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
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
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
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-credit-card"></i></span><h4 class="text-section">Règlements</h4></li>
                        <li class="nav-item {{ Route::is('payments.index') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i><p>Règlements</p></a>
                        </li>
                                                <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item active"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
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

       <h4>Liste des Clients :

                  <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createItemModal">Nouveau 
           <i class="fas fa-plus-circle ms-2"></i>
          </button>
       </h4>

    <!-- Modal création -->
    <div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('customer.store') }}" method="POST">
    @csrf
    <div class="modal-body row">
        <div class="mb-3 col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 1</label>
            <input type="text" name="phone1" class="form-control" required>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 2</label>
            <input type="text" name="phone2" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control">
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse de livraison</label>
            <input type="text" name="address_delivery" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Pays</label>
            <input type="text" name="country" class="form-control" required>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Matricule fiscale</label>
            <input type="text" name="matfiscal" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Identité bancaire</label>
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
                <option value="">-- Choisir --</option>
                @foreach($tvaGroups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->rate }} %</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Groupe Remise</label>
            <select name="discount_group_id" class="form-control" required>
                <option value="">-- Choisir --</option>
                @foreach($discountGroups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->discount_rate }} %</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Mode de paiement</label>
            <select name="payment_mode_id" class="form-control" required>
                <option value="">-- Choisir --</option>
                @foreach($paymentModes as $mode)
                    <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Condition de paiement</label>
            <select name="payment_term_id" class="form-control" required>
                <option value="">-- Choisir --</option>
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
    <!-- fin modal creation -->

    <!-- Recherche -->
    <div class="mb-2 d-flex justify-content-center">
        <input type="text" id="searchItemInput" class="form-control search-box" placeholder="🔍 Rechercher un client...">
    </div>

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
                        <th>Non.Fact</th>
                        <th>Plafond</th>
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
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->address }} <br>
                          🏴󠁢󠁹󠁭󠁩󠁿{{ $customer->city }}</td>
                            <td>📞 {{ $customer->phone1 }} <br>
                         📧 {{ $customer->email }} </td>
                            <td>{{ $customer->solde }} €</td>
                            <td>--- €</td>
                            <td>
{{ $customer->plafond }} €
</td>

                            <td>
                                <!-- Bouton Modifier -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $customer->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Bouton Véhicules associés -->
<button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewVehiclesModal{{ $customer->id }}">
    <i class="fas fa-car"></i>
</button>



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
                        <label for="brand_id_{{ $customer->id }}" class="form-label">Marque :</label>
                        <select id="brand_id_{{ $customer->id }}" name="brand_id" class="form-control" required>
                            <option value="">Sélectionner une marque</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="brand_name" id="brand_name_{{ $customer->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="model_id_{{ $customer->id }}" class="form-label">Modèle :</label>
                        <select id="model_id_{{ $customer->id }}" name="model_id" class="form-control" required>
                            <option value="">Sélectionner un modèle</option>
                        </select>
                        <input type="hidden" name="model_name" id="model_name_{{ $customer->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="engine_id_{{ $customer->id }}" class="form-label">Motorisation :</label>
                        <select id="engine_id_{{ $customer->id }}" name="engine_id" class="form-control" required>
                            <option value="">Sélectionner une motorisation</option>
                        </select>
                        <input type="hidden" name="engine_description" id="engine_description_{{ $customer->id }}">
                        <input type="hidden" name="linkage_target_id" id="linkage_target_id_{{ $customer->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="license_plate_{{ $customer->id }}" class="form-label">Immatriculation :</label>
                        <input type="text" id="license_plate_{{ $customer->id }}" name="license_plate" class="form-control" required>
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
<!-- Script for Vehicle Modal -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                // Ensure vehicles modal doesn't open automatically
                                const vehiclesModal = document.getElementById('viewVehiclesModal{{ $customer->id }}');
                                vehiclesModal.classList.remove('show');
                                vehiclesModal.style.display = 'none';

                                // Handle brand selection
                                document.getElementById('brand_id_{{ $customer->id }}').addEventListener('change', function () {
                                    const brandId = this.value;
                                    const brandName = this.options[this.selectedIndex].dataset.name;
                                    document.getElementById('brand_name_{{ $customer->id }}').value = brandName;
                                    if (brandId) {
                                        fetch("{{ route('getModels') }}?brand_id=" + brandId)
                                            .then(response => response.json())
                                            .then(data => {
                                                const modelSelect = document.getElementById('model_id_{{ $customer->id }}');
                                                modelSelect.innerHTML = '<option value="">Sélectionner un modèle</option>';
                                                data.forEach(model => {
                                                    modelSelect.innerHTML += `<option value="${model.id}" data-name="${model.name}">${model.name}</option>`;
                                                });
                                            })
                                            .catch(error => {
                                                console.error('Error fetching models:', error);
                                                alert('Erreur lors du chargement des modèles.');
                                            });
                                    } else {
                                        document.getElementById('model_id_{{ $customer->id }}').innerHTML = '<option value="">Sélectionner un modèle</option>';
                                    }
                                    document.getElementById('engine_id_{{ $customer->id }}').innerHTML = '<option value="">Sélectionner une motorisation</option>';
                                });

                                // Handle model selection
                                document.getElementById('model_id_{{ $customer->id }}').addEventListener('change', function () {
                                    const modelId = this.value;
                                    const modelName = this.options[this.selectedIndex].dataset.name;
                                    document.getElementById('model_name_{{ $customer->id }}').value = modelName;
                                    if (modelId) {
                                        fetch("{{ route('getEngines') }}?model_id=" + modelId)
                                            .then(response => response.json())
                                            .then(data => {
                                                const engineSelect = document.getElementById('engine_id_{{ $customer->id }}');
                                                engineSelect.innerHTML = '<option value="">Sélectionner une motorisation</option>';
                                                data.forEach(engine => {
                                                    engineSelect.innerHTML += `<option value="${engine.id}" data-description="${engine.description}" data-linking-target-id="${engine.linkageTargetId}">${engine.description}</option>`;
                                                });
                                            })
                                            .catch(error => {
                                                console.error('Error fetching engines:', error);
                                                alert('Erreur lors du chargement des motorisations.');
                                            });
                                    } else {
                                        document.getElementById('engine_id_{{ $customer->id }}').innerHTML = '<option value="">Sélectionner une motorisation</option>';
                                    }
                                });

                                // Handle engine selection
                                document.getElementById('engine_id_{{ $customer->id }}').addEventListener('change', function () {
                                    const engineDescription = this.options[this.selectedIndex].dataset.description;
                                    const linkageTargetId = this.options[this.selectedIndex].dataset.linkingTargetId;
                                    document.getElementById('engine_description_{{ $customer->id }}').value = engineDescription;
                                    document.getElementById('linkage_target_id_{{ $customer->id }}').value = linkageTargetId;
                                });

                                // Clear vehicle modal fields when closed
                                document.getElementById('addVehicleModal{{ $customer->id }}').addEventListener('hidden.bs.modal', function () {
                                    document.getElementById('vehicleForm{{ $customer->id }}').reset();
                                    document.getElementById('model_id_{{ $customer->id }}').innerHTML = '<option value="">Sélectionner un modèle</option>';
                                    document.getElementById('engine_id_{{ $customer->id }}').innerHTML = '<option value="">Sélectionner une motorisation</option>';
                                    document.getElementById('brand_name_{{ $customer->id }}').value = '';
                                    document.getElementById('model_name_{{ $customer->id }}').value = '';
                                    document.getElementById('engine_description_{{ $customer->id }}').value = '';
                                    document.getElementById('linkage_target_id_{{ $customer->id }}').value = '';
                                });
                            });
                        </script>

















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

                        <!-- Modal Modifier -->
                        <div class="modal fade" id="editItemModal{{ $customer->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $customer->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                           <div class="modal-header">
                <h5 class="modal-title">Client :  {{ $customer->code }} - {{ $customer->name }}</h5>
                <button type="button" class="btn btn-outline-primary btn-sm ms-2" id="editBtn{{ $customer->id }}">
                <i class="fas fa-edit"></i> Modifier
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>


                                    <form action="{{ route('customer.update', $customer->id) }}" method="POST">
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
            <label class="form-label">Téléphone 1</label>
            <input type="text" name="phone1" class="form-control" value="{{ $customer->phone1 }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 2</label>
            <input type="text" name="phone2" class="form-control" value="{{ $customer->phone2 }}" disabled>
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
            <label class="form-label">Matricule fiscale</label>
            <input type="text" name="matfiscal" class="form-control" value="{{ $customer->matfiscal }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Identité bancaire</label>
            <input type="text" name="bank_no" class="form-control" value="{{ $customer->bank_no }}" disabled>
        </div>

<div class="mb-3 col-md-4">
    <label class="form-label">Solde</label>
    <input type="number" step="0.01" name="solde" class="form-control"
           value="{{ old('solde', $customer->balance ?? 0) }}" readonly>
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
                <option value="">-- Choisir --</option>
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
                <option value="">-- Choisir --</option>
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
                <option value="">-- Choisir --</option>
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
                <option value="">-- Choisir --</option>
                @foreach($paymentTerms as $term)
                    <option value="{{ $term->id }}" {{ $customer->payment_term_id == $term->id ? 'selected' : '' }}>
                        {{ $term->label }} : {{ $term->days }} Jours
                    </option>
                @endforeach
            </select>
        </div>


        
<div class="mb-3 col-md-6">
    <label class="form-label d-block">Statut Client</label>

    <!-- Hidden input toujours envoyé -->
    <input type="hidden" name="blocked" value="0">

    <!-- Switch visible -->
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="blockedSwitch{{ $customer->id }}" name="blocked" value="1"
               {{ $customer->blocked ? 'checked' : '' }} onchange="toggleBlockedLabel({{ $customer->id }})" disabled>
        <label class="form-check-label fw-bold" id="blockedLabel{{ $customer->id }}" for="blockedSwitch{{ $customer->id }}">
            {{ $customer->blocked ? 'Client Bloqué 🚫' : 'Client Actif ✅' }}
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
        label.innerText = 'Client Bloqué 🚫';
    } else {
        label.innerText = 'Client Actif ✅';
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
        .badge-very-sm {
    font-size: 0.7rem;
    padding: 0.15em 0.3em;
    vertical-align: middle;
}
</style>




                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted text-center">Aucun client trouvé.</p>
    @endif

</div>

<!-- JS Recherche -->
<script>
    document.getElementById("searchItemInput").addEventListener("keyup", function () {
        const input = this.value.toLowerCase();
        document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
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
















  </body>
</html>
