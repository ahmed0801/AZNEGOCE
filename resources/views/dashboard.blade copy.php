<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PREMA B2B</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

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











.custom-notifications-container {
    position: fixed;
    top: 70px; /* Positionner plus bas */
    right: 0;
    z-index: 9999;
    width: 300px;
}

.custom-notification {
    background-color: #28a745; /* Vert pour la notification */
    color: white;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateX(100%);
    animation: custom-slideIn 1s forwards;
    position: relative;
}

.custom-notification p {
    margin: 0;
}

@keyframes custom-slideIn {
    0% {
        opacity: 0;
        transform: translateX(100%);
    }
    50% {
        opacity: 1;
        transform: translateX(-10%);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}



/* Style du bouton de fermeture */
.close-btn {
    position: absolute;
    top: 5px;
    right: 10px;
    background: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}




    /* Animation clignotante */
    /* Animation clignotante */
    .blinking {
        animation: blink-animation 1s infinite;
    }

    @keyframes blink-animation {
        0%, 100% {
            background-color: #dc3545; /* Rouge (danger) */
            color: #fff; /* Texte blanc */
            border-color: #dc3545; /* Bordure rouge */
        }
        50% {
            background-color: #fff; /* Blanc */
            color: #dc3545; /* Texte rouge */
            border-color: #dc3545; /* Bordure rouge */
        }
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
                src="assets/img/logop.png"
                alt="navbar brand"-9
                class="navbar-brand"
                height="40"
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
              

              <li class="nav-item active">
                <a href="/dashboard">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-item">
                <a  href="/commande">
                  <i class="fas fa-shopping-cart"></i>
                  <p>Nouvelle Commande</p>
                </a>
              </li>
             <li class="nav-item">
                <a href="/orders">
                  <i class="fas fa-file-alt"></i>
                  <p>Mes Commandes</p>
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
              <a href="/arrivage">
              <i class="fas fa-box"></i>
              <p>Dernier Arrivage</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="/contact">
                  <i class="icon-earphones-alt"></i>
                  <p>Contactez-Nous</p>
                </a>
              </li>

              
  <!-- Lien de déconnexion -->
  <li class="nav-item">
        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <p>Déconnexion</p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
            <div class="dropdown-title">Votre Panier</div>
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
                                    <th>Quantité</th>
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
                        <!-- Message de montant approximatif -->
                        <div class="mt-2">
                        @php
            $discountPercent = session('user')['CustomerDiscPercent'] ?? 0;
            $totalWithDiscount = $total - ($total * $discountPercent / 100);
        @endphp

                            <strong>Total HT : </strong>{{ number_format($totalWithDiscount, 3) }} TND
                            @if (session('user')['CustomerDiscPercent'] != 0)
                            <strong> / Remise Appliqué : </strong> - {{ session('user')['CustomerDiscPercent'] }} %
                            @endif
                        </div>
                        <div class="mt-2 text-muted">
                            <small>Ce montant est estimé et non final, avant vérification du stock.</small>
                        </div>
                    @else
                        <p class="text-center mt-2">Votre panier est vide.</p>
                    @endif
                </div>
            </div>
        </li>
        @if(count($panier) > 0)
            <li class="dropdown-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('panier.vider') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Vider le panier</button>
                    </form>
                    <!-- Bouton de validation du panier -->
                    <form action="{{ route('panier.valider') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Valider le panier</button>
                    </form>
                    <a href="/commande" class="btn btn-secondary">Ajouter Des Articles</a>
                </div>
            </li>
        @endif
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
                        src="assets/img/avatar.png"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <!-- <span class="op-7">Hi,</span> -->
                      <span class="fw-bold">{{ session('user')['CustomerName'] }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="assets/img/avatar.png"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <!-- <h4>{{ session('user')['CustomerName'] }}</h4> -->
                            <p class="text-muted">{{ session('user')['CustomerNo'] }}</p>
                            <a
                              href="/passwordform"
                              class="btn btn-xs btn-secondary btn-sm"
                              >Modifier le Mot de Passe</a
                            >
 
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <!-- <a class="dropdown-item" href="#">My Profile</a> -->
                        <!-- <a class="dropdown-item" href="#">My Balance</a> -->
                        <!-- <div class="dropdown-divider"></div> -->

    <!-- Formulaire de déconnexion -->
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
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

        @if(session('success_html'))
    <div class="alert alert-success">
        {!! session('success_html') !!}
    </div>
@endif


<!-- debut notification  -->
@if ($notification)
<div id="custom-notifications" class="custom-notifications-container">
    <div class="custom-notification">
        <!-- Icône de notification -->
        <i class="fas fa-bell"></i>
        
        <!-- Message de la notification -->
        <p>{{ $notification->notif }}</p>
        
        <!-- Icône de fermeture -->
        <button class="close-btn" onclick="closeNotification(this)">
            <i class="fas fa-times"></i> <!-- Icône de fermeture -->
        </button>
    </div>
</div>

@endif

<!-- fin notification -->


        
<div class="container mt-4">
    {{-- Affichage des messages d'erreur --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <!-- En-tête avec bouton et titre alignés -->
            <div class="position-relative mb-4">
                <!-- Bouton à gauche -->
                <a href="/arrivage" 
   class="btn btn-md shadow-sm position-absolute start-0 blinking">
   {{ $arrivages->first()->title ?? 'Voir Dernier Arrivage' }} <i class="fas fa-shipping-fast ms-1"></i>
</a>

                <!-- Titre centré -->
                <h3 class="fw-bold text-primary text-center">
                    <i class="fas fa-user-circle me-2"></i> Tableau de Bord
                </h3>
            </div>

            <!-- Message de bienvenue -->
            <div class="text-center mb-4">
                <h6>
                    Bienvenue, 
                    <b class="text-info">{{ session('user')['CustomerName'] }}</b>
                    <span>dans votre espace client <strong>PREMA GROS</strong>.</span>
                </h6>
            </div>

            <!-- Section des boutons -->
            <div class="text-center">
                @if (session('user')['Blocked'] != " ")
                    <div class="alert alert-danger p-3">
                        Commande non autorisée. Contactez notre équipe financière au 
                        <strong>54 882 278 || 56 017 015</strong> 
                        <i class="fas fa-phone-square"></i>
                        <br>
                    </div>
                    <a href="{{ url('/invoices') }}" class="btn btn-info">
                        Consulter mes factures
                    </a>
                @else
                    <a href="/commande" class="btn btn-primary btn-lg shadow-sm">
                        Nouvelle Commande <i class="fas fa-plus-circle ms-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>



            
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-spinner"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Commandes En Cours</p>
                          <h4 class="card-title">{{ session('user')['NbCdeVentesOuvertes'] }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
             
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-donate"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Condition Paiement</p>
                          <h4 class="card-title">{{ session('user')['PaymentTermsCode'] }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-secondary bubble-shadow-small"
                        >
                          <i class="fas fa-cart-plus"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Total Achat ce Mois</p>
                          <h4 class="card-title">{{ session('user')['Montant achat'] }} </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


@if (session('user')['CustomerDiscPercent'] > 0)
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                  <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="fas fa-hand-holding-usd"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Remise Client</p>
                          <h4 class="card-title">- {{ session('user')['CustomerDiscPercent'] }} %</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
@endif


            </div>





     <!-- essai dash -->
<div class="row">
  <div class="col-md-3">
    <div class="card card-secondary">
      <div class="card-body skew-shadow">
        <h1>{{ session('user')['Montant en cours'] }}</h1>
        <h5 class="op-8">Montant En Cours</h5>
        <div class="pull-right">
        <h5 class="fw-bold op-8">TND</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-secondary bg-secondary-gradient">
      <div class="card-body bubble-shadow">
        <h1>{{ session('user')['CreditAutorise'] }}</h1>
        <h5 class="op-8">Crédit Autorisé (Total)</h5>
        <div class="pull-right">
        <h5 class="fw-bold op-8">TND</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card card-secondary bg-secondary-gradient">
      <div class="card-body curves-shadow">
        <h1>{{ session('user')['CreditAutoriseMois'] }}</h1>
        <h5 class="op-8">Crédit Autorisé (Ce Mois)</h5>
        <div class="pull-right">
          <h5 class="fw-bold op-8">TND</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- Carrousel des derniers arrivages -->
  <div class="col-md-3">
    <div class="card card-secondary">
      <!-- <div class="card-body"> -->
        <!-- <h5 class="fw-bold text-center mb-3">Dernièrs Arrivages</h5> -->
        <div id="arrivagesCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            @foreach ($arrivages as $index => $arrivage)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <img
                src="{{ asset('storage/' . $arrivage->image) }}"
                class="d-block w-100"
                alt="{{ $arrivage->title }}"
                style="height: 150px; object-fit: cover; border-radius: 5px;"
              />
              <div class="carousel-caption d-none d-md-block">
                <h6 class="bg-dark text-white rounded p-1">{{ $arrivage->title }}</h6>
              </div>
            </div>
            @endforeach
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#arrivagesCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#arrivagesCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
          </button>
        </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- fin essai dash -->



            <div class="row">

 <!-- test arrivage  -->
 @foreach ($arrivages as $arrivage)

 <div class="col-md-4">
                <div class="card card-post card-round">
                  <img
                    class="card-img-top"
                    src="{{ asset('storage/' . $arrivage->image) }}" height="280px" alt="{{ $arrivage->title }}"
                    alt="Card image cap"
                  />
                  <div class="card-body">
                    <div class="separator-solid"></div>
                    <p class="card-category text-info mb-1">
                      <a href="#">News</a>
                    </p>
                    <h3 class="card-title">
                      <a href="#"> {{ $arrivage->title }} </a>
                    </h3>
                    <p class="card-text">
                    {{ $arrivage->description }}
                    </p>
                  </div>
                </div>
              </div>
              @endforeach


          





            </div>
 <!-- fin test arrivage  -->
            
           
           
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
                    alert('Article ajouté au panier');
                    location.reload(); // Recharge la page pour actualiser le panier
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
                    location.reload();
                }
            });
        });
    });
});

    </script>








<script>
function closeNotification(button) {
    // Trouver le conteneur de notification parent du bouton
    var notification = button.closest('.custom-notification');
    
    // Désactiver l'animation de fermeture en appliquant une transition instantanée
    notification.style.transition = 'none'; // Supprimer toute transition
    
    // Supprimer immédiatement la notification
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';

    // Supprimer la notification après la fermeture (sans délai d'animation)
    setTimeout(function() {
        notification.remove();
    }, 0); // Pas de délai avant suppression
}

window.onload = function() {
    let notifications = document.querySelectorAll('.custom-notification');
    let delay = 0;

    notifications.forEach(function(notification, index) {
        setTimeout(function() {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, delay);
        delay += 1000; // Décale l'affichage de chaque notification

        // Fermeture automatique après 10 secondes si la notification n'a pas été fermée manuellement
        setTimeout(function() {
            closeNotification(notification);
        }, 3000); // 10000 ms = 10 secondes
    });
};
</script>






  </body>
</html>
