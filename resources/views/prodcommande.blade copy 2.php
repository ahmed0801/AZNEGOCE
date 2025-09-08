<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PREMA-Vendeur</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />


<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>


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
    width: 940px; /* Adjust the width as needed */
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
    /* text-align: center; */
    vertical-align: middle;
}

.table th {
    background-color: #f8f9fa;
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




    .filter-container {
        margin-right: 15px; /* Ajuster l'espacement entre les filtres */
    }



    

</style>
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="{{ asset('assets/img/logop.png')}}"
                alt="navbar brand"-9
                class="navbar-brand"
                height="55"
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
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              

              <li class="nav-item">
                <a href="/dashboard">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-item active">
                <a  href="/commande">
                  <i class="fas fa-shopping-cart"></i>
                  <p>Nouvelle Commande</p>
                </a>
              </li>

              <li class="nav-item">
                <a  href="/orders">
                <i class="fas fa-file-alt"></i>
                <p>Mes Commandes</p>
                </a>
              </li>

              <li class="nav-item">
                <a  href="">
                  <i class="fas fa-shopping-cart"></i>
                  <p>Devis</p>
                </a>
              </li>


              <li class="nav-item">
  <a href="/tecdoc">
    <i class="fas fa-cogs"></i> 
    <p>TecDoc</p>
  </a>
</li>    


                <!-- Lien de déconnexion -->
  <li class="nav-item">
        <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <p>Déconnexion</p>
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
                  src="assets/img/logop.png"
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
<li class="nav-item topbar-icon dropdown hidden-caret">
    <a
        class="nav-link dropdown-toggle"
        href="#"
        id="panierDropdown"
        role="button"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
    >
        <i class="fas fa-shopping-basket"></i>
        <span class="notification">{{ count(session('panier', [])) }}</span>
    </a>
    <ul class="dropdown-menu notif-box animated fadeIn panier-dropdown" aria-labelledby="panierDropdown">
        <li>
            <div class="dropdown-title">Précommande</div>
        </li>
        <li>
            <div class="notif-scroll scrollbar-outer">
                <div class="notif-center">
                    @php
                        $panier = session('panier', []);
                        $total = 0;  // Initialiser le total
                    @endphp
                    @if(count($panier) > 0)
                        <!-- Tableau du panier -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Prix U.</th>
                                    <th>Qte</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach($panier as $itemNo => $details)
        <tr>
            <td>{{ $details['article']['ItemNo'] }} : {{ $details['article']['Desc'] }}</td>
            <!-- Prix unitaire correctement formaté -->
            <td>{{ number_format((float) str_replace(',', '', $details['article']['Price']), 3, '.', ' ') }} TND</td>
            <!-- Quantité affichée -->
            <td><span class="badge badge-info">{{ $details['quantite'] }}</span></td>
            <!-- Total par article correctement formaté -->
            <td>{{ number_format((float) str_replace(',', '', $details['article']['Price']) * $details['quantite'], 3, '.', ' ') }} TND</td>
            <!-- Bouton de suppression -->
            <td>
                <button class="btn btn-danger btn-sm supprimer-panier" data-item-no="{{ $itemNo }}">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        @php
            // Mise à jour du total global
            $total += (float) str_replace(',', '', $details['article']['Price']) * $details['quantite'];
        @endphp
    @endforeach
</tbody>
                        </table>
                        <!-- Message de montant estimé -->
                        <div class="mt-2">
                        @php
            $discountPercent = session('selectedClient')['CustomerDiscPercent'] ?? 0;
            $totalWithDiscount = $total - ($total * $discountPercent / 100);
        @endphp

                            <strong>➤ Total HT : </strong>{{ number_format($totalWithDiscount, 3) }} TND
                            @if (!empty(session('selectedClient')) && session('selectedClient')['CustomerDiscPercent'] != 0)
                            <strong> / Remise Appliqué : </strong> - {{ session('selectedClient')['CustomerDiscPercent'] }} %
                            @endif
                        </div>
                        <div class="mt-2 text-muted">
                            <!-- <small>Ce montant est estimé et non final, avant vérification du stock.</small> -->
                        </div>
                    @else
                        <p class="text-center mt-2">Votre panier est vide.</p>
                    @endif
                </div>
            </div>
        </li>
            <li class="dropdown-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('panier.vider') }}" method="POST" class="d-inline">
                        @csrf

                        <button type="submit" class="btn btn-danger">Vider le panier</button>
                    </form>
                    
<!-- champ commentaire -->
<input type="text" name="comment" id="comment" class="form-control w-25" 
           placeholder="Commentaire ✎" autocomplete="off">
    <!-- fin champs commentaire -->

                    <!-- Bouton de validation du panier -->
                    <form action="{{ route('panier.valider') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="comment" id="hidden_comment">

                        <button type="submit" class="btn btn-success">Valider le panier</button>
                    </form>
                </div>

                <script>
    document.querySelector('form[action="{{ route('panier.valider') }}"]').addEventListener('submit', function() {
        document.getElementById('hidden_comment').value = document.getElementById('comment').value;
    });
</script>

            </li>
    </ul>
</li>
<!-- fin test panier -->

                
                

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
            <!-- <div class="page-header">
              <h3 class="fw-bold mb-3">Commande</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Créer</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Commande</a>
                </li>
              </ul>
            </div> -->



            @if (1 == " ")
            <div class="alert alert-danger text-center">
        Commande non autorisée. Contactez notre équipe financière au 54 882 278 || 56 017 015 <i class="fas fa-phone-square"></i>
    </div>
    <div class="d-flex justify-content-center mt-3">
        <a href="{{ url('/invoices') }}" class="btn btn-info">
            Consulter mes factures
        </a>
    </div>
        @else

            <div class="container mt-4">
        {{-- Affichage des messages d'erreur --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif



        <div class="col-md-12 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📦 <b>Précommande</b> - ( Optimisée par TecDoc 🔍)</h5>
            <img src="{{ asset('assets/img/tecdoclogo.png') }}" alt="TecDoc Logo" style="height: 40px;">
        </div>

        <!-- Select Client -->
        <div class="card p-3 shadow-sm rounded" style="background: rgba(108, 117, 125, 0.1); border: 1px solid rgba(108, 117, 125, 0.3);">
        <div class="form-group mb-3">
    <label for="client" class="fw-bold fs-5">Sélectionnez le client</label>
    <select name="client" id="client" class="form-control select2" style="width: 100%;">
        <option value="" {{ empty($selectedClient) ? 'selected' : '' }}>-- Choisir un client --</option>
        @foreach($clients as $client)
            <option value="{{ $client['CustomerNo'] }}" 
                    data-circuit="{{ $client['CodeCircuit1'] }}" 
                    data-remise="{{ $client['CustomerDiscPercent'] }}" 
                    data-autorise="{{ $client['CreditAutorise'] }}"
                    data-TotalRisque="{{ $client['TotalRisque'] }}"
                    data-PhoneNo="{{ $client['PhoneNo'] }}"
                    data-Plafond="{{ $client['Plafond'] }}" 
                    data-LastOrderDate="{{ $client['LastOrderDate'] }}" 
                    data-NbCdeVentesOuvertes="{{ $client['NbCdeVentesOuvertes'] }}"
                    data-Paiement="{{ $client['PaymentTermsCode'] }}"
                    data-MatFiscale="{{ $client['MatFiscale'] }}"
                    {{ $selectedClient == $client['CustomerNo'] ? 'selected' : '' }}>
                {{ $client['CustomerNo'] }} - {{ $client['CustomerName'] }} - {{ $client['PhoneNo'] }}
                @if($client['Blocked'] != ' ') 🔴 @else 🟢 @endif
            </option>
        @endforeach
    </select>
</div>


    <div class="row">
        <div class="col-md-3">
            <label class="form-label text-muted">Circuit</label>
            <input type="text" id="circuit" class="form-control" disabled placeholder="Circuit">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted">Remise %</label>
            <input type="text" id="remise" class="form-control" disabled placeholder="Remise %">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted">Autorisé</label>
            <input type="text" id="autorise" class="form-control" disabled placeholder="Autorisé">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted">Date derniére commande</label>
            <input type="text" id="LastOrderDate" class="form-control" disabled placeholder="Date derniére commande">
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-3">
            <label class="form-label text-muted">Adresse</label>
            <input type="text" id="Adresse" class="form-control" disabled placeholder="Adresse">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted">Code TVA</label>
            <input type="text" id="VATCode" class="form-control" disabled placeholder="Code TVA">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted">Mat. Fiscale</label>
            <input type="text" id="MatFiscale" class="form-control" disabled placeholder="MatFiscale">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted">Nbr Commandes Ouvertes</label>
            <input type="text" id="NbCdeVentesOuvertes" class="form-control" disabled placeholder="Nbr Commandes Ouvertes">
        </div>
    </div>


    <div class="row mt-3">
    <div class="col-md-3">
            <label class="form-label text-muted">N° Tel</label>
            <input type="text" id="PhoneNo" class="form-control" disabled placeholder="N° Tel">
        </div>

        <div class="col-md-3">
            <label class="form-label text-muted">Condition de paiement</label>
            <input type="text" id="Paiement" class="form-control" disabled placeholder="Condition de paiement">
        </div>
        
        <div class="col-md-3">
            <label class="form-label text-muted">Risque</label>
            <input type="text" id="TotalRisque" class="form-control" disabled placeholder="Risque">
        </div>
        
        <div class="col-md-3">
            <label class="form-label text-muted">Plafond</label>
            <input type="text" id="Plafond" class="form-control" disabled placeholder="Plafond">
        </div>
    </div>
</div>




        

        <!-- Recherche par Fournisseur -->
        <!-- Bloc Recherche Fournisseur -->
        <div class="card shadow-sm mb-5">
        <div class="card-body" style="padding-top: 2px; padding-bottom: 5px; margin-top: 0; margin-bottom: 0;">
        <!-- <h5 class="fw-bold text-muted mb-3">Filtres</h5> -->
        <form method="GET" action="/arrivage">
            <div class="row g-3 align-items-end">
                <div class="col-md-3" id="searchresultat">
                <label for="Fournisseur">Fournisseur 1</label>
                    <select name="vendor1" id="vendor1" class="form-control select3">
                        <option value="">-- Choisir un fournisseur 1 --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor['VendorNo'] }}">{{ $vendor['VendorNo'] }} - {{ $vendor['VendorName'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                <label for="Fournisseur">Fournisseur 2</label>
                    <select name="vendor2" id="vendor2" class="form-control select3">
                        <option value="">-- Choisir un fournisseur 2 --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor['VendorNo'] }}">{{ $vendor['VendorNo'] }} - {{ $vendor['VendorName'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                <label for="Fournisseur">Fournisseur 3</label>
                    <select name="vendor3" id="vendor3" class="form-control select3">
                        <option value="">-- Choisir un fournisseur 3 --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor['VendorNo'] }}">{{ $vendor['VendorNo'] }} - {{ $vendor['VendorName'] }}</option>
                        @endforeach
                    </select>
                </div>
                        <!-- Bouton de filtre -->


                <div class="col-md-2 d-flex align-items-end">
                <button id="filtrerBtn" type="submit" class="btn btn-primary w-100">Filtrer (F7)</button>
                </div>
            </div>
        </form>

        <script>
document.addEventListener("keydown", function(event) {
    if (event.key === "F7") {
        event.preventDefault(); // Empêcher le comportement par défaut de F8
        document.getElementById("filtrerBtn").click(); // Clic sur le bouton spécifique
    }
});
</script>



<hr>


    <!-- Recherche par Article -->
<form action="{{ route('items.search') }}" method="POST">
    @csrf
    <div class="row mt-3">
        <!-- Référence -->
        <div class="col-md-3">
            <label for="itemFilter">Réf. fournisseur</label>
            <input type="text" name="itemFilter" id="itemFilter" class="form-control" 
                   placeholder=" " minlength="3" maxlength="20" autocomplete="off">
        </div>

        <!-- Désignation -->
        <div class="col-md-3">
            <label for="descriptionFilter">Description</label>
            <input type="text" name="descriptionFilter" id="descriptionFilter" class="form-control" 
                   placeholder=" " minlength="3" maxlength="100" autocomplete="off">
        </div>

        <!-- Référence Origine -->
        <div class="col-md-3">
            <label for="originReferenceFilter">Réf. origine</label>
            <input type="text" name="originReferenceFilter" id="originReferenceFilter" class="form-control" 
                   placeholder=" " minlength="3" maxlength="20" autocomplete="off">
        </div>

        <!-- Bouton de recherche -->
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" type="submit">Entrée ↩</button>
        </div>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-3">
        <div>
        <a class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#catalogueModal">Catalogue 📖</a>
            @if(isset($articles))
                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#tecdocModal">Voir le résultat TecDoc</a>
            @endif
        </div>
    </div>
</form>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    function clearOtherInputs(currentInput) {
        document.querySelectorAll('input[type="text"]').forEach(input => {
            if (input !== currentInput) {
                input.value = '';
                input.setCustomValidity(""); // Réinitialiser la validation
            }
        });
    }

    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.addEventListener('focus', function () {
            clearOtherInputs(this);
        });

        input.addEventListener('input', function () {
            if (this.value.length > 0 && this.value.length < 3) {
                this.setCustomValidity("Minimum 3 caractères requis.");
            } else {
                this.setCustomValidity("");
            }
        });

        input.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                this.setCustomValidity("");
            }
        });
    });
});

</script>





    </div>





</div>


       
    </div>
</div>
















        

        <div class="col-md-12">
            <div class="card">
            <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Résultats du : {{ $itemFilter }} {{ $descriptionFilter }} {{ $originReferenceFilter }}</h4>
            <!-- filtre  -->


            



            <div class="d-flex">
    <!-- Filtre par Stock -->
<!-- Filtre par Stock -->
<div class="filter-container mr-3">
    <!-- Switch Bootstrap -->
    <div class="form-check form-switch" style="display: inline-block; font-size: 1.2rem; line-height: 1.5;">
        <input class="form-check-input" type="checkbox" role="switch" id="filterStock" />
        <label class="form-check-label" for="filterStock" style="font-weight: bold; color: #0d47a1; padding-left: 1px;">
            <i class="fas fa-warehouse" style="font-size: 1.5rem; color: #2196f3;"></i> 
            <b>En Stock</b>
        </label>
    </div>
</div>



    

    <!-- Filtre par Fournisseur -->
<!-- Filtre par Fournisseur -->
<div>
    <label for="filterVendor" class="mr-2">
        <i class="fas fa-truck"></i> <b> Fournisseur :</b>
    </label>
    <select id="filterVendor" class="form-control" style="display: inline-block; width: auto; background-color: #e3f2fd; border: 2px solid #2196f3; color: #0d47a1; font-weight: bold;">
        <option value="all">Tout</option>
        @php
            $vendorMapping = [
              'SL RAD TH' => 'Radiateur Thaïlande',
                'AO SAFETY CO. LTD' => 'Accessoire Thaïlande',
                'EXEDY EURO' => 'EXEDY',
                'EK KC' => 'Origine Thaïlande',
                'EXEDY INDE USD' => 'EXEDY',
                'EL' => 'KIA/HYUNDAI',
                'ALPHA INNOVATION CO.,LTD' => 'Accessoire Thaïlande',
                'VANDAPAC' => 'Accessoire Thaïlande',
                'CARRYBOY' => 'Accessoire Thaïlande',
                'AEROKLAS' => 'Accessoire Thaïlande',
                'ISUZU BLEU' => 'ISUZU JAPAN',
                'MAZDA SINGAPOUR' => 'MAZDA JAPAN',
                'GAP N THAILAND' => 'GAP Thaïlande',
                'AUTO GLOBAL INTERNATIONAL SDN BHD' => 'FPI',
                'EK KC 2 PD' => 'Origine Thaïlande',
                'NE USD!!!!!!!!' => 'NE',
                'NE USD' => 'NE',
                'SMART STANDARD TRADERS' => 'CHINE DIVERS',
                'TRUCK SPARE PARTS' => 'Origine Thaïlande',
                'STDP' => 'DAYKO',
                'MAJIMPEX' => 'WD-40',
                'KIA MOTORS' => 'KIA/HYUNDAI',
                'FORTUNE PARTS AFRICA' => 'Origine Thaïlande',
                'GSP AUTOMOTIVE GROUP WENZHOU CO., LTD.' => 'GSP',
                'SOCIETE 2A AUTO PIECE' => 'Origine Thaïlande',
            ];

            // Transformation directe des noms des fournisseurs
            $distinctVendors = collect($items)
                ->pluck('VendorName')
                ->filter()
                ->map(fn($vendor) => trim($vendor)) // Nettoyage initial
                ->map(fn($vendor) => $vendorMapping[$vendor] ?? $vendor) // Appliquer le mapping
                ->unique()
                ->sort();
        @endphp

        @foreach ($distinctVendors as $vendorName)
            <option value="{{ $vendorName }}">{{ $vendorName }}</option>
        @endforeach
    </select>
</div>


</div>



            <!-- fin filtre -->
        </div>
                <div class="card-body">

                
                  <!-- recherche dans le tableau -->
                  <div class="mb-1 d-flex justify-content-center">
    <input type="text" id="searchInput" class="form-control search-box" placeholder="🔍 Rechercher dans ce tableau...">
</div>

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
</style>

<!-- fin recherche dans le tableau -->


                    <div class="table-responsive">
                        @if(isset($items) && count($items) > 0)
                        <table id="basic-datatables" class="display table table-hover table-bordered">
                        <thead class="thead-dark">
    <tr>
        <th data-column="ItemNo" class="sortable">Référence ↕️ <span class="sort-icon"></span></th>
        <th data-column="Desc" class="sortable">Description ↕️ <span class="sort-icon"></span></th>
        <th data-column="Price" class="sortable">Prix ↕️ <span class="sort-icon"></span></th>
        <th data-column="VendorName" class="sortable">Fournisseur ↕️ <span class="sort-icon"></span></th>
        <th data-column="Stock" class="sortable">Stock ↕️ <span class="sort-icon"></span></th>
        <th>Ajouter Au Panier</th>
    </tr>
</thead>

    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item['ItemNo'] ?? 'Non disponible' }}</td>
                <td>{{ $item['Desc'] ?? 'Non disponible' }}</td>
                <td>{{ $item['Price'] ?? 'Non disponible' }}</td>
                <td>
                    @php
                        $vendorName = trim($item['VendorName'] ?? '');
                        $displayName = $vendorMapping[$vendorName] ?? $vendorName ?: 'Non disponible';
                    @endphp
                    {{ $displayName }}
                </td>
                <td>
                    @if ($item['Stock']>0)
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                    </svg>
                    {{ $item['Stock'] }}
                    @else 
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-patch-exclamation-fill" viewBox="0 0 16 16">
                        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    @endif
                </td>
                <td>
                    @if ($item['Stock'] > 0)
                    <div class="input-group" style="max-width: 200px; margin: auto;">
                        <input type="number" id="quantite-{{ $item['ItemNo'] }}" min="1" 
                            class="form-control quantite-input" placeholder="Quantité" style="width: 50px;">
                        <button class="btn btn-secondary ml-2 ajouter-panier" data-article="{{ json_encode($item) }}">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                        </button>
                    </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

                        @else
                            <p>Aucun article trouvé pour cette recherche.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



    @endif
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
            © PREMA GROS. All Rights Reserved.
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
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

<!-- Kaiadmin JS -->
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>




<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ajouter un article au panier
    document.querySelectorAll('.ajouter-panier').forEach(button => {
        button.addEventListener('click', function () {
            const article = JSON.parse(this.dataset.article);
            const quantite = document.getElementById(`quantite-${article.ItemNo}`).value || 1;

            fetch('{{ route("panier.ajouter") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ article, quantite })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // alert('Article ajouté au panier');
                    mettreAJourPanier(data.panier);
                }
            });
        });
    });

    // Supprimer un article du panier
    document.querySelectorAll('.supprimer-panier').forEach(button => {
        button.addEventListener('click', function () {
            const itemNo = this.dataset.itemNo;

            fetch('{{ route("panier.supprimer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ itemNo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Article supprimé du panier');
                    mettreAJourPanier(data.panier);
                }
            });
        });
    });
});

// Fonction pour mettre à jour dynamiquement le panier
function mettreAJourPanier(panier) {
    const panierDropdown = document.querySelector('.panier-dropdown .notif-center');
    const notificationBadge = document.querySelector('.topbar-icon .notification');
    const panierFooter = document.querySelector('.panier-dropdown .dropdown-footer');

    // Mise à jour du badge du panier
    notificationBadge.textContent = Object.keys(panier).length;

    // Vider le contenu existant
    panierDropdown.innerHTML = '';

    if (Object.keys(panier).length > 0) {
        let html = `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Prix U.</th>
                        <th>Qte</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalHT = 0;
        const discountPercent = {{ session('selectedClient')['CustomerDiscPercent'] ?? 0 }}; // Récupérer la remise depuis la session

        for (const [itemNo, details] of Object.entries(panier)) {
            const prixUnitaire = parseFloat(details.article.Price.replace(',', '')); // Supprimer les virgules pour éviter les erreurs
            const quantite = parseInt(details.quantite, 10); // Convertir la quantité en entier
            const totalParArticle = prixUnitaire * quantite;

            totalHT += totalParArticle; // Calculer le total HT

            html += `
                <tr>
                    <td>${details.article.ItemNo} : ${details.article.Desc}</td>
                    <td>${prixUnitaire.toFixed(3)} TND</td>
                    <td><span class="badge badge-info">${quantite}</span></td>
                    <td>${totalParArticle.toFixed(3)} TND</td>
                    <td>
                        <button class="btn btn-danger btn-sm supprimer-panier" data-item-no="${itemNo}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Appliquer la remise
        const totalAvecRemise = totalHT - (totalHT * discountPercent / 100);

        html += `
                </tbody>
            </table>
            <div class="mt-2">
                <strong>➤ Total HT : </strong>${totalAvecRemise.toFixed(3)} TND
                ${discountPercent !== 0 ? `<strong> / Remise Appliquée : </strong> - ${discountPercent} %` : ''}
            </div>
            <div class="mt-2 text-muted">
                <small>✧ Ce montant est estimé et non final, avant vérification du stock.</small>
            </div>
        `;

        panierDropdown.innerHTML = html;
        panierFooter.style.display = 'block';  // Afficher les boutons
    } else {
        panierDropdown.innerHTML = '<p class="text-center mt-2">Votre panier est vide.</p>';
        panierFooter.style.display = 'none';  // Masquer les boutons lorsque le panier est vide
    }

    // Réattacher les gestionnaires d'événements pour les boutons de suppression
    attachEventListeners();
}

// Fonction pour réattacher les événements après modification du panier
function attachEventListeners() {
    document.querySelectorAll('.supprimer-panier').forEach(button => {
        button.addEventListener('click', function () {
            const itemNo = this.dataset.itemNo;

            fetch('{{ route("panier.supprimer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ itemNo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Article supprimé du panier');
                    mettreAJourPanier(data.panier);
                }
            });
        });
    });
}

</script>




<!-- filtre stock  -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterStock = document.querySelector('#filterStock');
    const filterVendor = document.querySelector('#filterVendor');
    const tableRows = document.querySelectorAll('#basic-datatables tbody tr');

    function applyFilters() {
        const stockFilter = filterStock.checked ? 'inStock' : 'all';  // Utiliser la case cochée pour filtrer
        const vendorFilter = filterVendor.value;

        tableRows.forEach(row => {
            const stockCell = row.querySelector('td:nth-child(5) svg');
            const vendorCell = row.querySelector('td:nth-child(4)');
            const isInStock = stockCell && stockCell.classList.contains('bi-patch-check-fill');
            const vendorName = vendorCell ? vendorCell.textContent.trim() : '';

            const stockMatch = (stockFilter === 'all') || (stockFilter === 'inStock' && isInStock);
            const vendorMatch = (vendorFilter === 'all') || (vendorName === vendorFilter);

            // Afficher ou masquer la ligne
            if (stockMatch && vendorMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Écouter les changements de filtre
    filterStock.addEventListener('change', applyFilters);
    filterVendor.addEventListener('change', applyFilters);
});
</script>
<!-- fin filtre stock -->








  <!-- essai tecdoc resultat -->


<!-- Modal -->
<div class="modal fade" id="tecdocModal" tabindex="-1" aria-labelledby="tecdocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tecdocModalLabel">Résultats TecDoc Pour : {{ $itemFilter }} {{ $descriptionFilter }} {{ $originReferenceFilter }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(isset($articles))
                    @if($articles)
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Numéro d'article</th>
                                        <th>Description</th>
                                        <th>Marque</th>
                                        <th>Références OE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>{{ $article->directArticle->articleNo ?? 'N/A' }}</td>
                                            <td>{{ $article->directArticle->articleName ?? 'Nom inconnu' }}</td>
                                            <td>{{ $article->directArticle->brandName ?? 'Fabricant inconnu' }}</td>
                                            <td>
                                                @if(!empty($article->oenNumbers))
                                                    <ul class="list-unstyled">
                                                        @foreach($article->oenNumbers as $oen)
                                                            <li>
                                                                <strong>{{ $oen->brandName }}</strong> : {{ $oen->oeNumber }}

                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted">Aucune référence OE disponible</span>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning mt-3">Aucun résultat trouvé.</div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- fin essai tecdoc resultat -->





<!-- jQuery & Select2 -->
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "-- Choisir un client --",
        allowClear: true,
        width: '100%',
    });

    $('#client').on('change', function () {
        let client = $(this).val();

        if (client) {
            $.ajax({
                url: "{{ route('client.selectionner') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    client: client
                },
                success: function (response) {
                    if (response.success) {
                        console.log(response.client);
                        $('#circuit').val(response.client.CodeCircuit1);
                        $('#remise').val(response.client.CustomerDiscPercent + ' %');
                        $('#autorise').val(response.client.CreditAutorise);
                        $('#Adresse').val(response.client.Adresse);
                        $('#VATCode').val(response.client.VATCode);
                        $('#TotalRisque').val(response.client.TotalRisque);
                        $('#Plafond').val(response.client.Plafond);
                        $('#PhoneNo').val(response.client.PhoneNo);
                        $('#LastOrderDate').val(response.client.LastOrderDate);
                        $('#NbCdeVentesOuvertes').val(response.client.NbCdeVentesOuvertes);
                        $('#Paiement').val(response.client.PaymentTermsCode);
                        $('#MatFiscale').val(response.client.MatFiscale);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            // Vider les champs si aucun client sélectionné
            $('#circuit').val('');
            $('#remise').val('');
            $('#autorise').val('');
            $('#Adresse').val('');
            $('#VATCode').val('');
        }
    });

    // Auto-sélection lors du chargement de la page
    $('#client').trigger('change');
});





$(document).ready(function () {
    // Initialisation de Select2 pour les fournisseurs (sélecteurs avec la classe .select3)
    $('.select3').select2({
        placeholder: "-- Choisir un fournisseur --",
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        theme: "classic", // Ajoutez un thème si nécessaire
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // Initialisation de Select2 pour les clients si nécessaire
    $('.select2').select2({
        placeholder: "-- Choisir un client --",
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        theme: "classic", // Ajoutez un thème si nécessaire
    });
});

</script>








<!-- Modal catalogue-->
<div class="modal fade" id="catalogueModal" tabindex="-1" aria-labelledby="catalogueModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="catalogueModalLabel">Catalogue</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
        <div class="row">


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="Frein">
    <input type="hidden" name="Catalogue" value="Frein">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/frein.png') }}" alt="Frein" class="catalogue-img">
        <p class="card-category mt-2">Frein</p>
      </div>
    </button>
  </form>
</div>

<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="Moteur">
    <input type="hidden" name="Catalogue" value="Moteur">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/moteur.png') }}" alt="Moteur" class="catalogue-img">
        <p class="card-category mt-2">Moteur</p>
      </div>
    </button>
  </form>
</div>

<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="EMB">
    <input type="hidden" name="Catalogue" value="Embrayage">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/embrayage.png') }}" alt="Embrayage" class="catalogue-img">
        <p class="card-category mt-2">Embrayage</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="COURROI">
    <input type="hidden" name="Catalogue" value="Courroies & Chaines">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/courroie.png') }}" alt="Courroie" class="catalogue-img">
        <p class="card-category mt-2">Courroies & Chaines</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="AMORT">
    <input type="hidden" name="Catalogue" value="Amortissement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/amortissement.png') }}" alt="Amortissement" class="catalogue-img">
        <p class="card-category mt-2">Amortissement</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="SUSP">
    <input type="hidden" name="Catalogue" value="Suspension">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/suspension.png') }}" alt="Suspension" class="catalogue-img">
        <p class="card-category mt-2">Suspension</p>
      </div>
    </button>
  </form>
</div>



<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="FILTRE">
    <input type="hidden" name="Catalogue" value="Filtre">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/filtre.png') }}" alt="Filtre" class="catalogue-img">
        <p class="card-category mt-2">Filtre</p>
      </div>
    </button>
  </form>
</div>




<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ECHAP">
    <input type="hidden" name="Catalogue" value="Echappement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/Echappement.png') }}" alt="Echappement" class="catalogue-img">
        <p class="card-category mt-2">Echappement</p>
      </div>
    </button>
  </form>
</div>




<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="DIR">
    <input type="hidden" name="Catalogue" value="Direction">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/direction.png') }}" alt="Direction" class="catalogue-img">
        <p class="card-category mt-2">Direction</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ALLUM">
    <input type="hidden" name="Catalogue" value="Allumage">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/allumage.png') }}" alt="Allumage" class="catalogue-img">
        <p class="card-category mt-2">Allumage</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="REFROI">
    <input type="hidden" name="Catalogue" value="Refroidissement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/Refroidissement.png') }}" alt="Refroidissement" class="catalogue-img">
        <p class="card-category mt-2">Refroidissement</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="cardan">
    <input type="hidden" name="Catalogue" value="Cardan">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/cardan.png') }}" alt="cardan" class="catalogue-img">
        <p class="card-category mt-2">cardan</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ARBRE">
    <input type="hidden" name="Catalogue" value="Arbres De Transmission">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/arbre.png') }}" alt="Arbres De Transmission" class="catalogue-img">
        <p class="card-category mt-2">Arbres De Transmission</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ROULEMEN">
    <input type="hidden" name="Catalogue" value="Roulement">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/roulement.png') }}" alt="Roulement" class="catalogue-img">
        <p class="card-category mt-2">Roulement</p>
      </div>
    </button>
  </form>
</div>

<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="FIXA">
    <input type="hidden" name="Catalogue" value="Fixation">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/fixation.png') }}" alt="Fixation" class="catalogue-img">
        <p class="card-category mt-2">Fixation</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="VENTI">
    <input type="hidden" name="Catalogue" value="Ventilation">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/ventilation.png') }}" alt="Ventilation" class="catalogue-img">
        <p class="card-category mt-2">Ventilation</p>
      </div>
    </button>
  </form>
</div>



<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="FEU">
    <input type="hidden" name="Catalogue" value="Feux">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/feux.png') }}" alt="Feux" class="catalogue-img">
        <p class="card-category mt-2">Feux</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="retro">
    <input type="hidden" name="Catalogue" value="Rétroviseur">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/retroviseur.png') }}" alt="Rétroviseur" class="catalogue-img">
        <p class="card-category mt-2">Rétroviseur</p>
      </div>
    </button>
  </form>
</div>



<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="ESSUI">
    <input type="hidden" name="Catalogue" value="Système d'essuie-glaces">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/essui.png') }}" alt="Système d'essuie-glaces" class="catalogue-img">
        <p class="card-category mt-2">Essuie-glaces</p>
      </div>
    </button>
  </form>
</div>


<div class="col-sm-6 col-md-3">
  <form method="POST" action="{{ route('cataloguesearch') }}">
    @csrf
    <input type="hidden" name="descriptionFilter" value="Huile">
    <input type="hidden" name="Catalogue" value="Huiles et Fluides">
    <button type="submit" class="card card-stats card-round catalogue-card w-100 border-0 bg-white">
      <div class="card-body text-center">
        <img src="{{ asset('assets/img/huile.png') }}" alt="Huiles" class="catalogue-img">
        <p class="card-category mt-2">Huiles et Fluides</p>
      </div>
    </button>
  </form>
</div>




<!-- Ajoute d'autres catalogues ici de la même manière -->

</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<style>
  .catalogue-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
  }
  .catalogue-card {
    cursor: pointer;
    transition: 0.3s;
  }
  .catalogue-card:hover {
    background-color: #f8f9fa;
  }
</style>
<!-- fin modal catalogue -->





<script> 
$(document).ready(function() {
    $('.ajouter-panier').on('click', function() {
        var button = $(this);
        
        // Change la couleur du bouton en vert
        button.removeClass('btn-secondary').addClass('btn-success');
        
        // Change l'icône
        button.find('i').removeClass('fa-plus').addClass('fa-check');
        
        // Optionnel : désactiver le bouton après le clic
    });
});





$(document).ready(function() {
    // Permet de naviguer entre les inputs de quantités avec les flèches haut et bas
    $('.quantite-input').on('keydown', function(e) {
        var currentInput = $(this);
        var allInputs = $('.quantite-input');  // Sélectionne tous les inputs quantités
        var currentIndex = allInputs.index(currentInput);

        // Si l'utilisateur appuie sur la flèche haut (up)
        if (e.keyCode === 38) {
            // Empêche le comportement par défaut (ne pas changer la valeur)
            e.preventDefault();
            // Déplace le focus vers l'input précédent
            if (currentIndex > 0) {
                allInputs.eq(currentIndex - 1).focus();
            }
        }
        // Si l'utilisateur appuie sur la flèche bas (down)
        else if (e.keyCode === 40) {
            // Empêche le comportement par défaut (ne pas changer la valeur)
            e.preventDefault();
            // Déplace le focus vers l'input suivant
            if (currentIndex < allInputs.length - 1) {
                allInputs.eq(currentIndex + 1).focus();
            }
        }
    });
});








$(document).ready(function() {
    $('input[type="number"]').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Empêche le comportement par défaut (ex. soumission d'un formulaire)

            var row = $(this).closest('tr'); // Trouve la ligne la plus proche (ou autre conteneur)
            var button = row.find('.ajouter-panier'); // Trouve le bouton dans la même ligne

            if (button.length) {
                button.click(); // Simule un clic sur le bouton "Ajouter au Panier"
            }
        }
    });
});




          </script>








<script>
$(document).ready(function () {
    $('.sortable').on('click', function () {
        let table = $('#basic-datatables tbody');
        let rows = table.find('tr').toArray();
        let column = $(this).data('column');
        let order = $(this).data('order') || 'desc';

        // Réinitialiser l'ordre de tri des autres colonnes
        $('.sortable').not(this).data('order', '').find('.sort-icon').html('');

        // Déterminer si le tri est numérique (Price et Stock), sinon c'est textuel
        let isNumeric = ['Price', 'Stock'].includes(column);
        let isText = ['ItemNo', 'Desc', 'VendorName'].includes(column);

        rows.sort((a, b) => {
            let aValue = $(a).find('td').eq($(this).index()).text().trim();
            let bValue = $(b).find('td').eq($(this).index()).text().trim();

            if (isNumeric) {
                // Convertir les valeurs en nombres pour le tri
                aValue = parseFloat(aValue.replace(',', '')) || 0; // Enlever les virgules si elles sont présentes
                bValue = parseFloat(bValue.replace(',', '')) || 0;
                return order === 'asc' ? aValue - bValue : bValue - aValue;
            }

            if (isText) {
                // Trier comme texte en utilisant localeCompare
                return order === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            }

            return 0;  // Pour éviter les erreurs dans le cas d'autres types de colonnes
        });

        // Mettre à jour l'ordre de tri
        $(this).data('order', order === 'asc' ? 'desc' : 'asc');

        // Mise à jour de l'icône de tri uniquement pour la colonne sélectionnée
        $(this).find('.sort-icon').html(order === 'asc' ? ' 🔼' : ' 🔽');

        // Réinsérer les lignes triées
        table.append(rows);
    });
});

</script>




<!-- filtre recherche dans le tableau -->
<script>
$(document).ready(function () {
    function applyFilters() {
        let searchValue = $('#searchInput').val().toLowerCase();
        const stockFilter = $('#filterStock').is(':checked') ? 'inStock' : 'all';
        const vendorFilter = $('#filterVendor').val();

        $('#basic-datatables tbody tr').each(function () {
            let row = $(this);
            let textMatch = row.text().toLowerCase().indexOf(searchValue) > -1;

            let stockCell = row.find('td:nth-child(5) svg');
            let vendorCell = row.find('td:nth-child(4)');
            let isInStock = stockCell.hasClass('bi-patch-check-fill');
            let vendorName = vendorCell.text().trim();

            let stockMatch = (stockFilter === 'all') || (stockFilter === 'inStock' && isInStock);
            let vendorMatch = (vendorFilter === 'all') || (vendorName === vendorFilter);

            // Appliquer tous les filtres
            if (textMatch && stockMatch && vendorMatch) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Appliquer la recherche et les filtres simultanément
    $('#searchInput').on('keyup', applyFilters);
    $('#filterStock').on('change', applyFilters);
    $('#filterVendor').on('change', applyFilters);
});
</script>



@if(isset($scrollTo))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let target = document.getElementById("{{ $scrollTo }}");
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
</script>
@endif



  </body>
</html>
