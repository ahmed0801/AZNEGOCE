<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>TPG</title>
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
    width: 890px; /* Adjust the width as needed */
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






/* Style pour le bouton de retour en haut */
#backToTop {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: rgba(0, 123, 255, 0.7); /* Couleur de fond bleue avec opacité */
    color: white;
    border: none;
    border-radius: 5px; /* Bord arrondi */
    padding: 8px 12px; /* Réduction de la taille du bouton avec un peu d'espace pour le texte */
    font-size: 14px; /* Taille du texte */
    cursor: pointer;
    display: none; /* Caché par défaut */
    z-index: 9999; /* Toujours au premier plan */
    transition: background-color 0.3s ease; /* Transition pour un effet doux lors du survol */
}

#backToTop:hover {
    background-color: rgba(0, 86, 179, 0.7); /* Plus sombre au survol */
}









/* Overlay pour l'animation de chargement */
.loading-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Fond semi-transparent */
    display: none; /* Cacher par défaut */
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Toujours au-dessus du contenu */
    
}

/* Conteneur du spinner */
.spinner-container {
    text-align: center;
    color: white;
    font-size: 20px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    animation: spin 1s linear infinite; /* Animation du spinner */
}

/* Animation du spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Texte de l'animation */
.loading-text {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
}

/* Ajoute cette classe */
.loading-logo {
    max-width: 120px;
    height: auto;
    animation: pulse 2s infinite ease-in-out;
}

/* Ajoute une animation douce */
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
}



.panier-table th,
.panier-table td {
    font-size: 10px !important;        /* Texte très petit */
    padding: 2px 4px !important;      /* Moins d'espacement */
    line-height: 1.1;
    white-space: nowrap;
    vertical-align: middle;
}

.panier-table th {
    background-color: #f0f0f0;
    font-weight: bold;
    text-align: center;
}

.panier-table .badge {
    font-size: 8px;
    padding: 2px 4px;
}

.panier-table .btn-sm {
    padding: 1px 5px;
    font-size: 9px;
}

.panier-table td button i {
    font-size: 10px;
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
                <i class="fas fa-file-invoice-dollar"></i>
                <p>Mes BL</p>
                </a>
              </li>

              <li class="nav-item">
                <a  href="/listdevis">
                <i class="fas fa-file-alt"></i>
                  <p>Mes Devis</p>
                </a>
              </li>

              <li class="nav-item">
              <a href="/listbrouillon">
              <i class="fas fa-reply-all"></i>
              <p>Brouillons</p>
                </a>
              </li>

              <li class="nav-item">
              <a href="/invoices">
              <i class="fas fa-money-bill-wave"></i>
              <p>Mes Factures</p>
                </a>
              </li>

              <li class="nav-item">
              <a href="/avoirs">
              <i class="fas fa-reply-all"></i>
              <p>Mes Avoirs</p>
                </a>
              </li>
            

              <li class="nav-item">
              <a href="/receptions">
              <i class="fas fa-money-bill-wave"></i>
              <p>Réception</p>
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
               
               

            
@php
    $panier = session('panier', []);
    $totalHT = 0;
    foreach($panier as $item) {
        $puHT = (float) str_replace(',', '', $item['PrixVenteUnitaire']);
        $remise = (float) $item['remise'];
        $quantite = (int) $item['quantite'];
        $totalHT += $puHT * $quantite * (1 - $remise / 100);
    }
    $totalTTC = $totalHT * 1.19;
@endphp


    <!-- Total TTC à gauche -->
    <span id="badge-total-ttc" 
      class="badge rounded-pill bg-gradient bg-success text-light shadow-sm d-flex align-items-center"
      style="font-size: 0.85rem; padding: 0.5em 0.75em;">
   <strong class="ms-1">{{ number_format($totalTTC, 3, '.', ' ') }}  TND</strong>
</span>


<!-- test panier -->
<li class="nav-item topbar-icon dropdown hidden-caret">


    <!-- Bouton Panier -->
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

            <div class="dropdown-title">
<!-- Bouton Agrandir -->
<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#panierModal">
    🛒 Agrandir le panier
</button>

            </div>
            
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
                        <table class="table table-striped panier-table">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>P U.HT</th>
                                    <th>P U.TTC</th>
                                    <th>Rem%</th>
                                    <th>Qte</th>
                                    <th>Total TTC</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach($panier as $itemNo => $details)
    @php
        $puHT = (float) str_replace(',', '', $details['PrixVenteUnitaire']);
        $remise = (float) $details['remise'];
        $quantite = (int) $details['quantite'];
        $puHTRemise = $puHT - ($puHT * $remise / 100);
        $puTTCAvecRemise = $puHTRemise * 1.19;
        $totalHT = $puHT * $quantite;
        $totalTTCAvecRemise = ($totalHT - ($totalHT * $remise / 100)) * 1.19;
    @endphp
    <tr>
        <td>{{ $details['article']['ItemNo'] }} : {{ $details['article']['Desc'] }}</td>
        <td>{{ number_format($puHTRemise, 3, '.', ' ') }} TND</td>
        <td>{{ number_format($puTTCAvecRemise, 3, '.', ' ') }} TND</td>
        <td>{{ $remise }} %</td>
        <td>{{ $quantite }}</td>
        <td>{{ number_format($totalTTCAvecRemise, 3, '.', ' ') }} TND</td>
        <td>
            <button class="btn btn-danger btn-sm supprimer-panier" data-item-no="{{ $itemNo }}">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
    @php
        $total += $totalHT - ($totalHT * $remise / 100);
    @endphp
@endforeach
</tbody>

                        </table>
                        <!-- Message de montant estimé -->
                        <div class="mt-2">
                        @php
            $totalWithDiscount = $total;
            $totalTTC = $totalWithDiscount * 1.19;
        @endphp

        🧮 <strong style="color: #2b6cb0;"> ➤ Total HT : </strong>{{ number_format($totalWithDiscount, 3) }} TND 
        💵 <strong style="color: #38a169;"> ➤ Total TTC : </strong>{{ number_format($totalTTC, 3) }} TND
 

                           
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
<!-- <a href="/createdevis" class="btn btn-outline-dark">Génerer Devis</a> -->

<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
<a href="/createbrouillon" class="btn btn-outline-primary">Brouillon</a>
  <a href="/createdevis" class="btn btn-outline-dark">Devis</a>
  <a href="/createdemain" class="btn btn-outline-primary">Demain</a>

</div>

    <!-- fin champs commentaire -->

                    <!-- Bouton de validation du panier -->
                    <form action="{{ route('panier.valider') }}" method="POST" class="d-inline">
                        @csrf

                        <button type="submit" class="btn btn-success">Valider le panier</button>
                    </form>
                </div>

                

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

        @if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif



        <div class="col-md-12 mb-4">
    <div class="card">
        <!-- <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📦 <b>Comptoir TPG </b></h5>
            <img src="{{ asset('assets/img/tecdoclogo.png') }}" alt="TecDoc Logo" style="height: 40px;">
        </div> -->


        <!-- Select Client -->
        <div class="card p-3 shadow-sm rounded" style="background: rgba(108, 117, 125, 0.1); border: 1px solid rgba(108, 117, 125, 0.3);">
        <div class="form-group mb-3">
    <!-- <label for="client" class="fw-bold fs-5">Sélectionner un Client - </label> -->
    <!-- <a class="btn btn-outline-primary btn-sm" href="/commande/refresh-clients">Actualiser Les Clients</a> -->
    
    
    <a class="btn btn-nouveau-client"
   data-bs-toggle="modal" data-bs-target="#createCustomerModal">
   <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
     <path d="M8 9a3 3 0 1 0-3-3 3 3 0 0 0 3 3Zm4-3a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm-4 4c-2.33 0-7 1.17-7 3.5V15a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-.5C13 11.17 8.33 10 8 10Zm6-1v2h2v1h-2v2h-1v-2h-2v-1h2v-2h1Z"/>
   </svg>
   Nouveau Client
</a>
&nbsp;
    <a class="btn btn-outline-primary btn-sm" href="/commande/refresh-clients">Actualiser Les Clients</a>
    &nbsp;
    <a class="btn btn-outline-danger btn-sm" href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=142" target="_blank">Annuler & Modifier BL</a>

<hr>
<style>
.btn-nouveau-client {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #6c5ce7, #00cec9);
    color: white;
    border: none;
    border-radius: 30px;
    padding: 8px 18px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-nouveau-client:hover {
    background: linear-gradient(135deg, #5a4ed7, #00b4b0);
    transform: scale(1.03);
    box-shadow: 0 6px 14px rgba(0,0,0,0.15);
    color: white;
}
</style>





<!-- modal  -->
 <!-- Modal -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form action="{{ route('clients.create') }}" method="POST" id="createcustomer">
  @csrf
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="createCustomerModalLabel">
          <i class="fas fa-id-card"></i> Ajouter un nouveau client
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label for="customerName" class="form-label">Nom du client</label>
              <input type="text" class="form-control" name="customerName" id="customerName" required>
            </div>

            <div class="col-md-6">
              <label for="adresse" class="form-label">Adresse</label>
              <input type="text" class="form-control" name="adresse" id="adresse" required>
            </div>

            <div class="col-md-6">
              <label for="matFiscale" class="form-label">Matricule fiscale</label>
              <input type="text" class="form-control" name="matFiscale" id="matFiscale">
            </div>

            <div class="col-md-6">
              <label for="city" class="form-label">Ville</label>
              <input type="text" class="form-control" name="city" id="city">
            </div>

            <div class="col-md-6">
              <label for="phoneNo" class="form-label">Téléphone</label>
              <input type="text" class="form-control" name="phoneNo" id="phoneNo" required>
            </div>

            <div class="col-md-6">
              <label for="customerPostingGroup" class="form-label">Customer Posting Group</label>
              <select class="form-select" name="customerPostingGroup" id="customerPostingGroup">
                <option value="LOC">LOC</option>
                <!-- Ajoutez d'autres options si nécessaire -->
              </select>
            </div>

            <div class="col-md-6">
              <label for="paymentTermsCode" class="form-label">Conditions de Paiement</label>
              <select class="form-select" name="paymentTermsCode" id="paymentTermsCode">
                <option value="CR ESP">CR ESP</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="countryRegionCode" class="form-label">Pays / Région</label>
              <select class="form-select" name="countryRegionCode" id="countryRegionCode">
                <option value="ARI">ARI</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="genBusPostingGroup" class="form-label">Gen. Bus. Posting Group</label>
              <select class="form-select" name="genBusPostingGroup" id="genBusPostingGroup">
                <option value="LOC">LOC</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="grpComptaTVAClt" class="form-label">Groupe TVA Client</label>
              <select class="form-select" name="grpComptaTVAClt" id="grpComptaTVAClt">
                <option value="ASSUJ">ASSUJ</option>
              </select>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Enregistrer
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- fin modal -->


    <select name="client" id="client" class="form-control select2" style="width: 100%;">
        <option value="" {{ empty($selectedClient) ? 'selected' : '' }}>-- Choisir un client --</option>
        @foreach($clients as $client)
            <option value="{{ $client['CustomerNo'] }}" 
                    data-remise="{{ $client['CustomerDiscPercent'] }}" 
                    data-PhoneNo="{{ $client['PhoneNo'] }}"
                    data-LastOrderDate="{{ $client['LastOrderDate'] }}" 
                    data-Paiement="{{ $client['PaymentTermsCode'] }}"
                    data-MatFiscale="{{ $client['MatFiscale'] }}"
                    data-Adresse="{{ $client['Adresse'] }}"
                    {{ $selectedClient == $client['CustomerNo'] ? 'selected' : '' }}>

                    🧑‍💼  {{ $client['CustomerNo'] }}    -    {{ $client['CustomerName'] }}        📞 {{ $client['PhoneNo'] }}          📍 Adresse : {{ $client['Adresse'] }}           🧾 MF : {{ $client['MatFiscale'] }}

            </option>
        @endforeach
    </select>
</div>



    <div class="row">

        

        <div class="col-md-4">
            <label class="form-label text-muted">Adresse</label>
            <input type="text" id="Adresse" class="form-control" disabled placeholder="Adresse">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted">N° Tel</label>
            <input type="text" id="PhoneNo" class="form-control" disabled placeholder="N° Tel">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted">Mat. Fiscale</label>
            <input type="text" id="MatFiscale" class="form-control" disabled placeholder="MatFiscale">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted">Date derniére commande</label>
            <input type="text" id="LastOrderDate" class="form-control" disabled placeholder="Date derniére commande">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted">Cond. paiement</label>
            <input type="text" id="Paiement" class="form-control" disabled placeholder="Condition de paiement">
        </div>

    </div>




   
</div>




        

        <!-- Recherche par Fournisseur -->
        <!-- Bloc Recherche Fournisseur -->

        <div class="card shadow-sm mb-1">


        <div class="card-body" style="padding-top: 2px; padding-bottom: 5px; margin-top: 0; margin-bottom: 0;">
        
<!-- recherche fournisseur -->

    <!-- Recherche par Article -->
<!-- Modal de Chargement -->
<div id="loadingModal" class="loading-modal">
    <br>
    <div class="spinner-container">
        <img src="{{ asset('assets/img/logop.png') }}" alt="Logo" class="loading-logo mb-4">
        <br>
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="loading-text mt-3">Recherche en cours avec TecDoc...</p>
    </div>
</div>



<!-- Recherche par Article -->
<form action="{{ route('items.searchtest') }}" method="POST" id="searchForm">
    @csrf
    <div class="row mt-3">
        <!-- Référence -->
        <div class="col-md-3">
            <label for="itemFilter">Réference</label>
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
            @if(isset($articles))
                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#tecdocModal">Voir le résultat TecDoc</a>
            @endif
        </div>
    </div>
</form>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('searchForm');

    if (searchForm) {
        const inputs = searchForm.querySelectorAll('input[type="text"]');

        function clearOtherInputs(currentInput) {
            inputs.forEach(input => {
                if (input !== currentInput) {
                    input.value = '';
                    input.setCustomValidity("");
                }
            });
        }

        inputs.forEach(input => {
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
    }
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
                // Utiliser les noms de fournisseurs bruts sans mapping
                $distinctVendors = collect($items)
                    ->pluck('VendorName')
                    ->filter()
                    ->map(fn($vendor) => trim($vendor))
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

                  <div class="mb-1 d-flex justify-content-center d-none">
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

/* fin taille tableau */



/* Masquer les flèches pour les inputs de type number */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield; /* Pour Firefox */
}

/* Optionnel : ajuster l'apparence des inputs pour qu'ils soient cohérents */
input[type="number"] {
    appearance: none;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    font-size: 14px;
    width: 100px; /* Ajustez selon vos besoins */
}

/* Ajuster les inputs spécifiques si nécessaire */
.prixv-input, .prixvttc-input, .remise-input, .quantite-input {
    width: 100px; /* Ajustez la largeur pour prixv et prixvttc */
}

.remise-input {
    width: 65px; /* Garder la largeur spécifique pour remise */
}

.quantite-input {
    width: 50px; /* Garder la largeur spécifique pour quantité */
}
</style>

<!-- fin recherche dans le tableau -->


                    <div class="table-responsive">
                        @if(isset($items) && count($items) > 0)
                        <table id="basic-datatables" class="display table table-hover table-bordered table-text-small">
                <thead class="thead-dark">
                    <tr>
                        <th data-column="ItemNo" class="sortable">Référence ↕️ <span class="sort-icon"></span></th>
                        <th data-column="Desc" class="sortable">Description ↕️ <span class="sort-icon"></span></th>
                        <th data-column="Price" class="sortable">Prix R. ↕️ <span class="sort-icon"></span></th>
                        <th data-column="Stock" class="sortable">Stk ↕️ <span class="sort-icon"></span></th>
                        <th>QTE</th>
                        <th>Prix V.HT</th>
                        <th>Rem%</th>
                        <th>Total TTC</th>
                        
                        <th>Ajouter</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr data-vendor="{{ $item['VendorName'] ?? '' }}">
                            <td>
                                {{ $item['ItemNo'] ?? 'Non disponible' }}
                                @if(isset($item['ItemNo']))
                                    <a href="https://premagros.tn/items/search?itemFilter={{ urlencode($item['ItemNo']) }}"
                                       target="_blank"
                                       title="Rechercher à PREMAGROS"
                                       style="margin-left: 5px; color: #2c7a7b;">
                                        <i class="fas fa-search"></i>
                                    </a>
                                @endif
                                <br>
                                <small class="text-muted" style="font-size: 9px;">
                                    📍EMPL : {{ $item['Emplacement'] ?? 'Empl. vide' }}
                                </small>
                            </td>
                            <td>
                                {{ $item['Desc'] ?? 'Non disponible' }}
                                @if(isset($item['Desc']))
                                    <a href="https://premagros.tn/items/search?descriptionFilter={{ urlencode($item['Desc']) }}"
                                       target="_blank"
                                       title="Rechercher a PREMAGROS"
                                       style="margin-left: 5px; color: #2c7a7b;">
                                        <i class="fas fa-search"></i>
                                    </a>
                                @endif
                                <br>
                                <small class="text-muted" style="font-size: 9px;">
                                    📍FOUR: {{ $item['VendorName'] ?? 'vide' }}
                                </small>
                            </td>
                            <td style="line-height: 1;">
                                <!-- {{ $item['Price'] ?? 'Non disponible' }} <span style="font-size: 11px; color: #666;">HT</span> -->
                                <a href="#" onclick="openItemHistoryPopup('{{ urlencode($item['ItemNo']) }}')" 
                                   title="Historique de l'article" style="margin-left: 5px; color: orange;">
                                    <i class="fas fa-question-circle"></i>
                                </a>
                                <br>
                                <span style="font-size: 11px; color: #2c7a7b;">
                                    {{ number_format(floatval(str_replace(',', '.', preg_replace('/[^0-9.,]/', '', $item['Unit Cost'] ?? 0))) * 1.19, 3, ',', ' ') }} TTC
                                </span>
                            </td>
                            <td>
                                @if ($item['Stock'] > 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                                    </svg>
                                    {{ $item['Stock'] }}
                                @else 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-patch-exclamation-fill" viewBox="0 0 16 16">
                                        <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                    </svg>
                                    {{ $item['Stock'] }}
                                @endif
                            </td>

                            <td>
                            <input type="number" id="quantite-{{ $item['ItemNo'] }}" min="1" 
                            class="form-control quantite-input" placeholder="Quantité" style="width: 40px;" 
                            oninput="updateTTC('{{ $item['ItemNo'] }}')">
                            <div class="text-muted" style="font-size: 10px; margin-top: 2px;">
                                    QTE
                                </div>
                            </td>

                            <td>
                                @php
                                    $priceHT = floatval(str_replace(',', '.', preg_replace('/[^0-9.,]/', '', $item['Price'] ?? 0)));
                                    $unitCost = floatval($item['Unit Cost'] ?? 0);
                                    $marge = $priceHT - $unitCost;
                                @endphp
                                <input type="number" step="0.001" 
                                       id="prixv-{{ $item['ItemNo'] }}"
                                       class="form-control prixv-input" 
                                       placeholder="Prix V."
                                       value="{{$item['Price']}}"
                                       style="width: 100px; margin-left: 5px; font-size: 14px;"
                                       oninput="updateTTC('{{ $item['ItemNo'] }}')">
                                       <div id="marge-display-{{ $item['ItemNo'] }}" class="text-muted" style="font-size: 11px; margin-top: 2px;">
    📊 Marge U : {{ number_format($marge, 3, ',', ' ') }}
</div>

                                <input type="hidden" id="unitCost_{{ $item['ItemNo'] }}" value="{{ $unitCost }}">
                            </td>
                            <td>
                                <input type="number" step="0.001" 
                                       id="remise-{{ $item['ItemNo'] }}"
                                       class="form-control remise-input" 
                                       placeholder="%" 
                                       value=""
                                       style="width: 55px; font-size: 14px;"
                                       oninput="updateTTC('{{ $item['ItemNo'] }}')">

                                       <div class="text-muted" style="font-size: 11px; margin-top: 2px;">
                                    REMISE
                                </div>

                            </td>
                            <td>
                                <input type="number" step="0.001" 
                                       id="prixvttc-{{ $item['ItemNo'] }}"
                                       class="form-control prixvttc-input" 
                                       placeholder="Prix V. TTC" 
                                       value="{{ round($item['Price'] * 1.19, 3) }}"
style="width: 100px; margin-left: 5px; font-size: 14px; text-align: right; font-weight: bold; background-color: #f8f9fa; border: 1px solid #ced4da;"                                       oninput="updateHT('{{ $item['ItemNo'] }}')" readonly>
<div id="marge-total-{{ $item['ItemNo'] }}" class="text-muted" style="font-size: 11px; margin-top: 2px;">
    📊 Marge Total : {{ number_format($marge, 3, ',', ' ') }}
</div>

                            </td>
                            
                            <td>
                                    <div class="input-group" style="max-width: 200px; margin: auto;">
                                        
                                        <button class="btn btn-secondary btn-sm ms-1 ajouter-panier" data-article="{{ json_encode($item) }}">
                                            <span class="btn-label">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                        </button>
                                    </div>
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
            © TPG. All Rights Reserved.
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
            const prixV = document.getElementById(`prixv-${article.ItemNo}`).value || article.Price;
            console.log("Prix envoyé:", prixV);

            const remise = document.getElementById(`remise-${article.ItemNo}`).value || 0;


            fetch('{{ route("panier.ajouter") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ article, quantite, prixV, remise })
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


    notificationBadge.textContent = Object.keys(panier).length;
    panierDropdown.innerHTML = '';

    if (Object.keys(panier).length > 0) {
        let html = `
            <table class="table table-striped panier-table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>P U.HT</th>
                        <th>P U.TTC</th>
                        <th>Rem%</th>
                        <th>Qte</th>
                        <th>Total TTC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalHTGlobal = 0;

        for (const [itemNo, details] of Object.entries(panier)) {
            const puHT = parseFloat(details.PrixVenteUnitaire?.toString().replace(',', '.')) || 0;
            const remise = parseFloat(details.remise) || 0;
            const quantite = parseInt(details.quantite, 10) || 0;

            const puHTRemise = puHT - (puHT * remise / 100);
            const puTTCAvecRemise = puHTRemise * 1.19;

            const totalHT = puHT * quantite;
            const totalTTCAvecRemise = (totalHT - (totalHT * remise / 100)) * 1.19;

            totalHTGlobal += totalHT - (totalHT * remise / 100); // pour total global HT remisé

            html += `
                <tr>
                    <td>${details.article.ItemNo} : ${details.article.Desc}</td>
                    <td>${puHTRemise.toFixed(3)} TND</td>
                    <td>${puTTCAvecRemise.toFixed(3)} TND</td>
                    <td>${remise.toFixed(2)} %</td>
                    <td>${quantite}</td>
                    <td>${totalTTCAvecRemise.toFixed(3)} TND</td>
                    <td>
                        <button class="btn btn-danger btn-sm supprimer-panier" data-item-no="${itemNo}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        const totalTTCGlobal = totalHTGlobal * 1.19;

        html += `
                </tbody>
            </table>
            <div class="mt-2">
                🧮 <strong style="color: #2b6cb0;"> ➤ Total HT : </strong>${totalHTGlobal.toFixed(3)} TND
                💵 <strong style="color: #38a169;"> ➤ Total TTC : </strong>${totalTTCGlobal.toFixed(3)} TND
            </div>
        `;
        const ttcBadge = document.getElementById('badge-total-ttc');
if (ttcBadge) {
    ttcBadge.textContent = `${totalTTCGlobal.toFixed(3)} TND`;
}

        panierDropdown.innerHTML = html;
  panierFooter.style.display = 'block';

        // Mettre à jour les inputs de remise dans le tableau des articles
        for (const [itemNo, details] of Object.entries(panier)) {
            const remiseInput = document.getElementById(`remise-${itemNo}`);
            if (remiseInput) {
                remiseInput.value = details.remise || 0;
            }
        }

        attachEventListeners();
    } else {
        panierDropdown.innerHTML = '<p class="text-center mt-2">Votre panier est vide.</p>';
        panierFooter.style.display = 'none';
    }
}


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
                    mettreAJourPanier(data.panier);
                }
            });
        });
    });
}


</script>




<!-- filtre stock  -->
<script>
$(document).ready(function () {
    const filterStock = $('#filterStock');
    const filterVendor = $('#filterVendor');
    const searchInput = $('#searchInput');
    const table = $('#basic-datatables');
    const tbody = table.find('tbody');

    // Fonction pour appliquer tous les filtres
    function applyFilters() {
        const vendorFilter = filterVendor.val();
        const searchValue = searchInput.val().toLowerCase();

        const stockFilterEnabled = $('#filterStock').is(':checked');

$('#basic-datatables tbody tr').each(function () {
    const row = $(this);
    const stockText = row.find('td:eq(3)').text().trim(); // colonne Stock
    const stockValue = parseFloat(stockText.replace(',', '.')) || 0;

    let showRow = true;

    if (stockFilterEnabled && stockValue <= 0) {
        showRow = false;
    }

    if (showRow) {
        row.show();
    } else {
        row.hide();
    }
});

    }

    // Gestion du tri
    $('.sortable').on('click', function () {
        const column = $(this).data('column');
        let order = $(this).data('order') || 'desc';
        const rows = tbody.find('tr').toArray();
        const isNumeric = ['Price', 'Stock'].includes(column);
        const isText = ['ItemNo', 'Desc'].includes(column); // VendorName supprimé

        // Réinitialiser les icônes des autres colonnes
        $('.sortable').not(this).data('order', '').find('.sort-icon').html('');
        $(this).data('order', order === 'asc' ? 'desc' : 'asc');
        $(this).find('.sort-icon').html(order === 'asc' ? ' 🔼' : ' 🔽');

        rows.sort((a, b) => {
            let aValue = $(a).find('td').eq($(this).index()).text().trim();
            let bValue = $(b).find('td').eq($(this).index()).text().trim();

            if (isNumeric) {
                aValue = parseFloat(aValue.replace(',', '')) || 0;
                bValue = parseFloat(bValue.replace(',', '')) || 0;
                return order === 'asc' ? aValue - bValue : bValue - aValue;
            }

            if (isText) {
                return order === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            }

            return 0;
        });

        tbody.append(rows);
        applyFilters(); // Réappliquer les filtres après le tri
    });

    // Écouter les changements des filtres et de la recherche
    filterStock.on('change', applyFilters);
    filterVendor.on('change', applyFilters);
    searchInput.on('keyup', applyFilters);

    // Appliquer les filtres au chargement initial
    applyFilters();
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












<!-- Bouton de retour en haut -->
<button id="backToTop" class="back-to-top">↑ Rechercher</button>

<script>
// Montre le bouton lorsqu'on descend de la page
window.onscroll = function() {
    var button = document.getElementById("backToTop");
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        button.style.display = "block"; // Affiche le bouton
    } else {
        button.style.display = "none"; // Cache le bouton
    }
};

// Fonction pour remonter en haut de la page au clic du bouton
document.getElementById("backToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

    </script>


<script>
$(document).ready(function() {
    // Soumettre le formulaire
    $('#searchForm').submit(function(event) {
        // Afficher l'overlay et spinner
        $('#loadingModal').show();

        // Désactiver le bouton de soumission pour éviter plusieurs clics
        $('button[type="submit"]').prop('disabled', true);

        // Toujours afficher le même message
        $('.loading-text').text("Recherche en cours avec TecDoc...");

        // Laisse le formulaire se soumettre normalement (pas de timeout ni re-submit)
        // Donc pas besoin de `event.preventDefault()` ici
    });

    // Cacher le modal après chargement
    $(window).on('load', function() {
        $('#loadingModal').hide();
        $('button[type="submit"]').prop('disabled', false);
    });
});
</script>



<script>
$(document).ready(function() {
    // Soumettre le formulaire
    $('#createcustomer').submit(function(event) {
        // Afficher l'overlay et spinner
        $('#loadingModal').show();

        // Désactiver le bouton de soumission pour éviter plusieurs clics
        $('button[type="submit"]').prop('disabled', true);

        // Toujours afficher le même message
        $('.loading-text').text("enregistrement en cours ...");

        // Laisse le formulaire se soumettre normalement (pas de timeout ni re-submit)
        // Donc pas besoin de `event.preventDefault()` ici
    });

    // Cacher le modal après chargement
    $(window).on('load', function() {
        $('#loadingModal').hide();
        $('button[type="submit"]').prop('disabled', false);
    });
});
</script>


















<!-- Modale Panier Agrandi -->
<div class="modal fade" id="panierModal" tabindex="-1" aria-labelledby="panierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="panierModalLabel">🛒 Détails du Panier - </h5>
                <!-- Bouton d'exportation PDF -->
                 <button id="exportPdf" class="btn btn-warning btn-sm">Exporter en PDF</button>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>        </div>
      <div class="modal-body">
        <!-- Contenu du panier dynamique copié ici -->
        <div id="panier-modal-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('panierModal');
    const panierModalContent = document.getElementById('panier-modal-content');

    // Utiliser Bootstrap event (si tu utilises Bootstrap 5)
    modal.addEventListener('show.bs.modal', function () {
        const notifContent = document.querySelector('.panier-dropdown .notif-center').innerHTML;
        panierModalContent.innerHTML = notifContent;

        // Attacher les événements après avoir mis le contenu
        attachEventListeners();
    });
});

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
                    

                    // Optionnel : Mettre à jour l'aperçu du panier
                    // alert('Article supprimé du panier');

                    mettreAJourPanier(data.panier);

                    // Fermer la modale
                    const modalElement = bootstrap.Modal.getInstance(document.getElementById('panierModal'));
                    modalElement.hide();
                    
                }
            });
        });
    });
}
</script>









<script>
document.getElementById('exportPdf').addEventListener('click', async function () {
    // Charger les scripts à la demande
    if (!window.html2canvas) {
        await loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
    }
    if (!window.jspdf) {
        await loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const content = document.getElementById('panier-modal-content');

    
    // Ajouter le titre centré
    doc.setFontSize(18);
    doc.text('Commande Vente', 105, 20, null, null, 'center'); // Centré sur la page

    html2canvas(content).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 210;
        const pageHeight = 297;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        let heightLeft = imgHeight;
        let position = 30; // Position du début de l'image après le titre

        // Ajouter l'image du panier
        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        // Ajouter le pied de page avec fond gris
        const footerText = `TRUCK PARTS GROUP s.a.r.l au capital de 50000 DT
        Adresse : 123 Avenue de l'Union Maghreb Arabe, La Soukra 2036 ARIANA
        MF : 1551291ZAM000 RC : B0321242018 | RIB : 904101700853071 | IBAN : TN5914904904101700853
        Tél. : +216 71 863 212 | Fax : +216 71 863 214 | SWIFT : BHBKTNTT`;

        // Dessiner le rectangle gris pour le pied de page
        const footerHeight = 20; // Ajuster la hauteur du rectangle du pied de page
        const footerYPosition = pageHeight - footerHeight - 5; // Position plus haute du pied de page

        doc.setFillColor(169, 169, 169); // Gris
        doc.rect(8, footerYPosition, 190, footerHeight, 'F'); // Rectangle avec fond gris

        // Ajouter le texte centré dans le pied de page
        doc.setFontSize(7); // Réduire la taille du texte pour qu'il tienne
        doc.setTextColor(255, 255, 255); // Couleur du texte en blanc
        doc.text(footerText, 105, footerYPosition + 10, null, null, 'center'); // Centré sur la page et positionné

        // Sauvegarder le PDF
        doc.save('panier.pdf');
    });
});

function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}
</script>

<script>
function openItemHistoryPopup(itemNoEncoded) {
    const url = `/items/history?itemNo=${itemNoEncoded}`;
    window.open(url, 'Historique', 'width=800,height=600');
}
</script>





<script>
function updateTTC(itemNo) {
    let prixHT = parseFloat(document.getElementById('prixv-' + itemNo).value) || 0;
    let quantite = parseFloat(document.getElementById('quantite-' + itemNo).value) || 1;
    let remise = parseFloat(document.getElementById('remise-' + itemNo).value) || 0;
    let unitCost = parseFloat(document.getElementById('unitCost_' + itemNo).value) || 0;

    // Prix après remise
    let prixHTRemise = prixHT * (1 - remise / 100);
    let prixTotalHT = prixHTRemise * quantite;
    let prixTTC = prixTotalHT * 1.19;

    // Marges
    let margeUnitaire = prixHTRemise - unitCost;
    let margeTotale = (prixHTRemise - unitCost) * quantite;

    // MAJ prix TTC
    document.getElementById('prixvttc-' + itemNo).value = prixTTC.toFixed(3);

    // MAJ marge unitaire et totale
    const margeText = `📊 Marge U : ${margeUnitaire.toFixed(3).replace('.', ',')}`;
    document.getElementById('marge-display-' + itemNo).innerText = margeText;

const margetotaltext = `📊 Marge Total : ${margeTotale.toFixed(3).replace('.', ',')}`;
    document.getElementById('marge-total-' + itemNo).innerText = margetotaltext;
}


function updateHT(itemNo) {
    // Récupérer les valeurs des inputs
    let prixTTC = parseFloat(document.getElementById('prixvttc-' + itemNo).value) || 0;
    let remise = parseFloat(document.getElementById('remise-' + itemNo).value) || 0;
    let unitCost = parseFloat(document.getElementById('unitCost_' + itemNo).value) || 0;

    // Calculer le prix HT à partir du prix TTC
    let prixHT = prixTTC / 1.19;

    // Calculer le prix HT après remise
    let prixHTApresRemise = prixHT * (1 - remise / 100);

    // Calculer le prix TTC après remise
    let prixTTCApresRemise = prixHTApresRemise * 1.19;

    // Calculer la marge
    let marge = prixHT - unitCost;

    // Mettre à jour l'affichage du prix HT
    document.getElementById('prixv-' + itemNo).value = prixHT.toFixed(3);

    // Mettre à jour l'affichage de la marge
    document.getElementById('marge-display-' + itemNo).innerText = '📊 M: ' + marge.toFixed(3).replace('.', ',') + ' HT';

    // Mettre à jour l'affichage du prix TTC après remise
}







document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner tous les inputs dans le tableau
    const inputs = document.querySelectorAll(
        '#basic-datatables input.prixv-input, #basic-datatables input.prixvttc-input, #basic-datatables input.remise-input, #basic-datatables input.quantite-input'
    );

    inputs.forEach(input => {
        input.addEventListener('keydown', function (event) {
            // Vérifier si la touche est flèche gauche ou droite
            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                event.preventDefault(); // Empêcher le comportement par défaut (par exemple, déplacer le curseur dans l'input)

                // Trouver la ligne (tr) contenant l'input
                const row = this.closest('tr');
                // Trouver tous les inputs dans cette ligne
                const rowInputs = row.querySelectorAll(
                    'input.prixv-input, input.prixvttc-input, input.remise-input, input.quantite-input'
                );

                // Convertir NodeList en tableau pour trouver l'index
                const inputArray = Array.from(rowInputs);
                const currentIndex = inputArray.indexOf(this);

                // Calculer l'index de l'input suivant ou précédent
                let nextIndex;
                if (event.key === 'ArrowRight' && currentIndex < inputArray.length - 1) {
                    nextIndex = currentIndex + 1;
                } else if (event.key === 'ArrowLeft' && currentIndex > 0) {
                    nextIndex = currentIndex - 1;
                }

                // Mettre le focus sur l'input suivant ou précédent
                if (nextIndex !== undefined) {
                    inputArray[nextIndex].focus();
                    inputArray[nextIndex].select(); // Sélectionner le contenu pour faciliter l'édition
                }
            }
        });
    });
});
</script>









  </body>
</html>
