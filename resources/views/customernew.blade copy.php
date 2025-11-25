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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- Select2 JS -->

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

<div class="d-flex justify-content-between align-items-center">
  <h4 class="mb-0">Liste des Clients:</h4>
    <button type="submit" class="btn btn-outline-success btn-round ms-2" data-bs-toggle="modal" data-bs-target="#createItemModal">
      Nouveau <i class="fas fa-plus-circle ms-2"></i>
    </button>
  <div>


    <button type="button" class="btn btn-secondary btn-sm ms-2" onclick="window.close()">
      Quitter et continuer <i class="fas fa-sign-out-alt ms-2"></i>
    </button>
  </div>
</div>

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
        <div class="mb-3 col-md-4">
            <label class="form-label">Nom</label>
            <input type="text" name="name" class="form-control" required>
        </div>

                <div class="mb-3 col-md-3">
    <label class="form-label">Type de client</label>
    <select name="type" class="form-control" required>
        <option value="particulier" selected>Particulier</option>
        <option value="jobber">Jobber</option>
        <option value="professionnel">Professionnel</option>
    </select>
</div>



        <div class="mb-3 col-md-5">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 1</label>
            <input type="text" name="phone1" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Téléphone 2</label>
            <input type="text" name="phone2" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control" value="Paris">
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse</label>
            <input type="text" name="address" class="form-control">
        </div>

        <div class="mb-3 col-md-6">
            <label class="form-label">Adresse de livraison</label>
            <input type="text" name="address_delivery" class="form-control">
        </div>

        <div class="mb-3 col-md-2">
            <label class="form-label">Pays</label>
            <input type="text" name="country" class="form-control" value="France">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">SIRET</label>
            <input type="text" name="matfiscal" class="form-control">
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Identité bancaire</label>
            <input type="text" name="bank_no" class="form-control">
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">Solde</label>
            <input type="number" step="0.01" name="solde" class="form-control" value="0" readonly>
        </div>

        <div class="mb-3 col-md-2">
            <label class="form-label">Plafond</label>
            <input type="number" step="0.01" name="plafond" class="form-control" value="0">
        </div>

        <div class="mb-3 col-md-2">
            <label class="form-label">Risque</label>
            <input type="number" name="risque" class="form-control">
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">TVA</label>
            <select name="tva_group_id" class="form-control" required>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($tvaGroups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->rate }} %</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">Groupe Remise</label>
            <select name="discount_group_id" class="form-control" required>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($discountGroups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->discount_rate }} %</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">Mode de paiement</label>
            <select name="payment_mode_id" class="form-control" required>
                <!-- <option value="">-- Choisir --</option> -->
                @foreach($paymentModes as $mode)
                    <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 col-md-3">
            <label class="form-label">Condition de paiement</label>
            <select name="payment_term_id" class="form-control" required>
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
    <!-- fin modal creation -->


    



   <!-- Filtres -->
<div class="mb-4">
    <form method="GET" action="{{ route('customer.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
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
        <select name="city" class="form-select form-select-sm" style="width: 160px;">
            <option value="">Ville (Toutes)</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                    {{ $city }}
                </option>
            @endforeach
        </select>
        
        <!-- Solde min -->
        <input type="number" step="0.01" name="min_solde" class="form-control form-control-sm" 
               style="width: 110px;" placeholder="Solde min" value="{{ request('min_solde') }}">
        <span class="mx-1 text-muted">à</span>
        
        <!-- Solde max -->
        <input type="number" step="0.01" name="max_solde" class="form-control form-control-sm" 
               style="width: 110px;" placeholder="Solde max" value="{{ request('max_solde') }}">
        
        <!-- Actions -->
        <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
            <i class="fas fa-filter me-1"></i> Filtrer
        </button>
        
        <button type="submit" name="action" value="export" 
                formaction="{{ route('customers.export') . '?' . http_build_query(request()->query()) }}" 
                class="btn btn-outline-success btn-sm px-3" target="_blank">
            <i class="fas fa-file-excel me-1"></i> EXCEL
        </button>
        
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-undo me-1"></i> Réinitialiser
        </a>
    </form>
</div>
<!-- Recherche rapide (garder l'ancienne) -->
<!-- <div class="mb-2 d-flex justify-content-center">
    <input type="text" id="searchItemInput" class="form-control search-box" placeholder="🔍 Rechercher un client...">
</div> -->


    @if ($customers->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-text-small" id="itemsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Adresse / Ville</th>
                        <th>Contact</th>
                        <th>Solde</th>
                        <!-- <th>Non.Fact</th> -->
                        <!-- <th>Plafond</th> -->
                        <th>Véhicules</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>🧑‍💼{{ $customer->code }}<br>{{ $customer->name }}
                             
                                  @if ($customer->blocked)
        🔴 
    @else
        🟢 
    @endif

                            </td>

                            <td>
                                                                    @switch($customer->type)
        @case('particulier')
            <span class="badge bg-primary">Particulier</span>
            @break
        @case('jobber')
            <span class="badge bg-warning text-dark">Jobber</span>
            @break
        @case('professionnel')
            <span class="badge bg-success">Professionnel</span>
            @break
    @endswitch
                            </td>

                            <td>
{{ $customer->address }} <br>
                          🏴󠁢󠁹󠁭󠁩󠁿{{ $customer->city }}
                            </td>
                            <td>📞 {{ $customer->phone1 }} <br>
                         📧 {{ $customer->email }} </td>



                             <td>
 <button type="button" class="btn btn-sm btn-outline-primary solde-btn" data-bs-toggle="modal" data-bs-target="#accountingModal{{ $customer->id }}" data-customer-id="{{ $customer->id }}">
                                                        {{ number_format($customer->solde, 2, ',', ' ') }} €
                                                    </button>

</td>
                            <!-- <td>
{{ $customer->plafond }} €
</td> -->

                         <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewVehiclesModal{{ $customer->id }}">
  <i class="fas fa-car"></i> <span class="badge bg-success">{{$customer->vehicles->count()}}</span>
</button>
                         </td>


                            <td>
                                <!-- Bouton Modifier -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $customer->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>











                                 

 <!-- Accounting Entries Modal -->
             <!-- Accounting Entries Modal -->
<div class="modal fade accounting-modal" id="accountingModal{{ $customer->id }}" tabindex="-1" aria-labelledby="accountingModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountingModalLabel{{ $customer->id }}">Ecritures comptables Client : {{ $customer->name }}</h5>
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
                        <label for="license_plate_{{ $customer->id }}" class="form-label">Immatriculation :</label>
                        <input type="text" id="license_plate_{{ $customer->id }}" name="license_plate" class="form-control" required>
                    </div>
                    <!-- Marque -->
<div class="mb-3">
    <label class="form-label">Marque :</label>
    <select id="brand_id_{{ $customer->id }}" name="brand_id" class="form-control select2-brand" style="width: 100%;" required>
        <option value="">Rechercher une marque...</option>
        @foreach($brands as $brand)
            <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
        @endforeach
    </select>
    <input type="hidden" name="brand_name" id="brand_name_{{ $customer->id }}">
</div>



<!-- Modèle -->
<div class="mb-3">
    <label class="form-label">Modèle :</label>
    <select id="model_id_{{ $customer->id }}" name="model_id" class="form-control select2-model" style="width: 100%;" required>
        <option value="">Rechercher un modèle...</option>
    </select>
    <input type="hidden" name="model_name" id="model_name_{{ $customer->id }}">
</div>

<!-- Motorisation -->
<div class="mb-3">
    <label class="form-label">Motorisation :</label>
    <select id="engine_id_{{ $customer->id }}" name="engine_id" class="form-control select2-engine" style="width: 100%;" required>
        <option value="">Rechercher une motorisation...</option>
    </select>
    <input type="hidden" name="engine_description" id="engine_description_{{ $customer->id }}">
    <input type="hidden" name="linkage_target_id" id="linkage_target_id_{{ $customer->id }}">
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
    <label class="form-label">Type de client</label>
    <select name="type" class="form-control" disabled>
        <option value="particulier" {{ $customer->type == 'particulier' ? 'selected' : '' }}>Particulier</option>
        <option value="jobber" {{ $customer->type == 'jobber' ? 'selected' : '' }}>Jobber</option>
        <option value="professionnel" {{ $customer->type == 'professionnel' ? 'selected' : '' }}>Professionnel</option>
    </select>
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
            <label class="form-label">SIRET</label>
            <input type="text" name="matfiscal" class="form-control" value="{{ $customer->matfiscal }}" disabled>
        </div>

        <div class="mb-3 col-md-4">
            <label class="form-label">Identité bancaire</label>
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

        <!-- Pagination avec conservation des filtres -->
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->appends(request()->query())->links() }}
    </div>

    @else
        <p class="text-muted text-center">Aucun client trouvé.</p>
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
    document.getElementById('filterForm').addEventListener('input', function(e) {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            this.submit();
        }, 1000);
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    

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
            fetch(`/customers/${customerId}/accounting-entries`, {
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
        const balance = debits - credits;

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
</script>



<script>
document.addEventListener("DOMContentLoaded", function () {
    // === FONCTION GLOBALE POUR INITIALISER SELECT2 DANS N'IMPORTE QUEL MODAL ===
    function initVehicleSelect2(modal) {
        const customerId = modal.id.replace('addVehicleModal', '');

        const $brand = $(`#brand_id_${customerId}`);
        const $model = $(`#model_id_${customerId}`);
        const $engine = $(`#engine_id_${customerId}`);

        // Détruire si déjà initialisé
        [$brand, $model, $engine].forEach($el => {
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
        });

        // Initialiser Select2
        $brand.select2({
            placeholder: "Rechercher une marque...",
            allowClear: true,
            width: '100%',
            dropdownParent: modal
        });

        $model.select2({
            placeholder: "Rechercher un modèle...",
            allowClear: true,
            width: '100%',
            dropdownParent: modal
        });

        $engine.select2({
            placeholder: "Rechercher une motorisation...",
            allowClear: true,
            width: '100%',
            dropdownParent: modal
        });
    }

    // === ÉCOUTEUR GLOBAL SUR TOUS LES MODALS ===
    $(document).on('shown.bs.modal', '[id^="addVehicleModal"]', function () {
        initVehicleSelect2(this);
    });

    // === MARQUE CHANGE (délégation d'événements) ===
    $(document).on('change', '[id^="brand_id_"]', function () {
        const customerId = this.id.replace('brand_id_', '');
        const brandId = this.value;
        const brandName = $(this).find('option:selected').data('name') || '';
        $(`#brand_name_${customerId}`).val(brandName);

        const $model = $(`#model_id_${customerId}`);
        const $engine = $(`#engine_id_${customerId}`);

        if (brandId) {
            fetch(`{{ route('getModels') }}?brand_id=${brandId}`)
                .then(r => r.json())
                .then(data => {
                    $model.empty().append('<option value="">Rechercher un modèle...</option>');
                    data.forEach(m => {
                        $model.append(`<option value="${m.id}" data-name="${m.name}">${m.name}</option>`);
                    });
                    $model.val('').trigger('change');
                });
        } else {
            $model.empty().append('<option value="">Rechercher un modèle...</option>').val('').trigger('change');
        }
        $engine.empty().append('<option value="">Rechercher une motorisation...</option>').val('').trigger('change');
    });

    // === MODÈLE CHANGE ===
    $(document).on('change', '[id^="model_id_"]', function () {
        const customerId = this.id.replace('model_id_', '');
        const modelId = this.value;
        const modelName = $(this).find('option:selected').data('name') || '';
        $(`#model_name_${customerId}`).val(modelName);

        const $engine = $(`#engine_id_${customerId}`);

        if (modelId) {
            fetch(`{{ route('getEngines') }}?model_id=${modelId}`)
                .then(r => r.json())
                .then(data => {
                    $engine.empty().append('<option value="">Rechercher une motorisation...</option>');
                    data.forEach(e => {
                        $engine.append(
                            `<option value="${e.id}" 
                                     data-description="${e.description}" 
                                     data-linking-target-id="${e.linkageTargetId}">
                                ${e.description}
                             </option>`
                        );
                    });
                    $engine.val('').trigger('change');
                });
        } else {
            $engine.empty().append('<option value="">Rechercher une motorisation...</option>').val('').trigger('change');
        }
    });

    // === MOTORISATION CHANGE ===
    $(document).on('change', '[id^="engine_id_"]', function () {
        const customerId = this.id.replace('engine_id_', '');
        const desc = $(this).find('option:selected').data('description') || '';
        const link = $(this).find('option:selected').data('linking-target-id') || '';
        $(`#engine_description_${customerId}`).val(desc);
        $(`#linkage_target_id_${customerId}`).val(link);
    });

    // === RESET QUAND MODAL FERMÉ ===
    $(document).on('hidden.bs.modal', '[id^="addVehicleModal"]', function () {
        const customerId = this.id.replace('addVehicleModal', '');
        $(`#vehicleForm${customerId}`)[0].reset();
        $(`#model_id_${customerId}, #engine_id_${customerId}`)
            .empty()
            .append('<option value="">Rechercher...</option>')
            .val('').trigger('change');
        $(`#brand_name_${customerId}, #model_name_${customerId}, #engine_description_${customerId}, #linkage_target_id_${customerId}`).val('');
    });
});
</script>



  </body>
</html>
