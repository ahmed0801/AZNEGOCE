<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ NEGOCE</title>
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



<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>




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






    /* CSS catalogue button */
    .button-92 {
  --c: #fff;
  /* text color */
  background: linear-gradient(90deg, #0000 33%, #fff5, #0000 67%) var(--_p,100%)/300% no-repeat,
rgb(255, 0, 149);
  /* background color */
  color: #0000;
  border: none;
  transform: perspective(500px) rotateY(calc(20deg*var(--_i,-1)));
  text-shadow: calc(var(--_i,-1)* 0.08em) -.01em 0   var(--c),
    calc(var(--_i,-1)*-0.08em)  .01em 2px #0004;
  outline-offset: .1em;
  transition: 0.3s;
}

.button-92:hover,
.button-92:focus-visible {
  --_p: 0%;
  --_i: 1;
}

.button-92:active {
  text-shadow: none;
  color: var(--c);
  box-shadow: inset 0 0 9e9q #0005;
  transition: 0s;
}

.button-92 {
  font-weight: bold;
  font-size: 1.5rem;
  margin: 0;
  cursor: pointer;
  padding: .1em .3em;
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
            <a href="/" class="logo">
              <img
                src="{{ asset('assets/img/logop.png')}}"
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
              <a href="/purchases/list">
                <i class="fas fa-file-alt"></i>
              <p>Commandes Achat</p>
                </a>
              </li>



              <li class="nav-item">
              <a href="/receptions">
              <i class="fas fa-money-bill-wave"></i>
              <p>Réception</p>
                </a>
              </li>



              <li class="nav-item">
              <a href="/articles">
              <i class="fas fa-money-bill-wave"></i>
              <p>Articles</p>
                </a>
              </li>

                                          <li class="nav-item">
              <a href="/customers">
              <i class="fa fa-user"></i>
              <p>Clients</p>
                </a>
              </li>

                                          <li class="nav-item">
              <a href="/suppliers">
              <i class="fa fa-user"></i>
              <p>Fournisseurs</p>
                </a>
              </li>



              <li class="nav-item">
              <a href="/setting">
              <i class="fas fa-money-bill-wave"></i>
              <p>Paramétres</p>
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
                

 





              <!-- test panier -->
      

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

            <div class="dropdown-title">


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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Prix U.</th>
                                    <th>Qte</th>
                                    <th>Rem%</th>
                                    <th>Total TTC</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach($panier as $itemNo => $details)
        <tr>
            <td>{{ $details['article']['ItemNo'] }} : {{ $details['article']['Desc'] }}</td>
            <!-- Prix unitaire correctement formaté -->
            <td>{{ number_format((float) str_replace(',', '', $details['PrixVenteUnitaire']) * 1.19, 3, '.', ' ') }} TND</td>
            <!-- Quantité affichée -->
            <td><span class="badge badge-info">{{ $details['quantite'] }}</span></td>
            <td><span class="badge badge-secondary">{{ $details['remise'] }} %</span></td>
            <!-- Total par article correctement formaté -->
             @php
             $totalpararticle = number_format((float) str_replace(',', '', $details['PrixVenteUnitaire']) * $details['quantite'], 3, '.', ' ');
             @endphp
             <td>{{ number_format(((float) str_replace(',', '', $totalpararticle) - ((float) str_replace(',', '', $totalpararticle) * $details['remise'] / 100)) * 1.19, 3, '.', ' ') }} TND</td>
             <!-- Bouton de suppression -->
            <td>
                <button class="btn btn-danger btn-sm supprimer-panier" data-item-no="{{ $itemNo }}">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        @php
            // Mise à jour du total global
            $totalpararticleFloat = (float) str_replace(',', '', $totalpararticle);
$total += $totalpararticleFloat - ($totalpararticleFloat * $details['remise'] / 100);
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
<button type="submit" class="btn btn-outline-dark">Génerer Devis</button>

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
          @if (session('success'))
    <div class="alert alert-success">
        {!! session('success') !!}
    </div>
@endif


        @if(session('success_html'))
    <div class="alert alert-success">
        {!! session('success_html') !!}
    </div>
@endif





        
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




            
            <a href="#ceform" class="btn btn-sm btn-outline-success">
    🔍 par véhicule
</a>

  <!-- Bouton Catalogue à droite -->
  <button onclick="window.location.href='#catalogue'" class="button-92 shadow-sm position-absolute end-0" role="button">Catalogue</button>

            <!-- fin catalogue -->

                <!-- Titre centré -->
                <h3 class="fw-bold text-primary text-center">
                    <i class="fas fa-user-circle me-2"></i> Tableau de Bord
                </h3>
                

                
            </div>

            <!-- Message de bienvenue -->
            <div class="text-center mb-4">
                <h6>
                    Bienvenue, 
                    <b class="text-info">{{ Auth::user()->name}}</b>
                    <span>dans votre Espace Commercial <strong>DESTOCK</strong>.</span>
                </h6>
            </div>

            <!-- Section des boutons -->
            <div class="text-center">
@if(Auth::user()->role != 'admin')
                    <a href="/commande" class="btn btn-primary btn-lg shadow-sm">
                        Nouvelle Commande <i class="fas fa-plus-circle ms-2"></i>
                    </a>
@else 

<!-- Bouton pour ouvrir le modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importVendeurModal">
    Créer un Utilisateur
</button>

<!-- Modal -->
<div class="modal fade" id="importVendeurModal" tabindex="-1" aria-labelledby="importVendeurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importVendeurModalLabel">Créer un Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importVendeurForm" action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <!-- Nom -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Exemple : PREMAGROS\Prénom.Nom" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <!-- Code Vendeur -->
                    <div class="mb-3">
                        <label for="codevendeur" class="form-label">Code Vendeur</label>
                        <input type="text" class="form-control" id="codevendeur" name="codevendeur">
                    </div>

                    <!-- Rôle -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-control" id="role" name="role" required>
                        <option value="vendeur">Vendeur</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <!-- Permissions -->
<div class="mb-3">
    <label class="form-label">Autorisations</label>
    <div class="row">
        @foreach(\App\Models\Permission::all() as $permission)
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                        name="permissions[]" value="{{ $permission->id }}"
                        id="permission_{{ $permission->id }}">
                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                        {{ $permission->label }}
                    </label>
                </div>
            </div>
        @endforeach
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

<!-- fin Modal -->


@endif
            </div>
        </div>
    </div>

    <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Liens Système (Navision)</h4>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=31" target="_blank" class="btn btn-info w-100">
                        <i class="fa fa-cube me-1"></i> Liste Articles
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=22" target="_blank" class="btn btn-secondary w-100">
                        <i class="fa fa-users me-1"></i> Liste Clients
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=27" target="_blank" class="btn btn-secondary w-100">
                        <i class="fa fa-truck me-1"></i> Liste Fournisseurs
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=142" target="_blank" class="btn btn-warning w-100">
                        <i class="fa fa-file-text me-1"></i> BL
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=143" target="_blank" class="btn btn-success w-100">
                        <i class="fa fa-file-invoice-dollar me-1"></i> Factures Vente Enregistrées
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=9301" target="_blank" class="btn btn-success w-100">
                        <i class="fa fa-file-invoice-dollar me-1"></i> Créer Factures Vente
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=9308" target="_blank" class="btn btn-danger w-100">
                        <i class="fa fa-file-invoice me-1"></i> Créer Facture Achat
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=146" target="_blank" class="btn btn-danger w-100">
                        <i class="fa fa-file-invoice me-1"></i> Factures Achat Enregistrées
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=9302" target="_blank" class="btn btn-dark w-100">
                        <i class="fa fa-undo me-1"></i> Créer Avoir Vente
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=144" target="_blank" class="btn btn-dark w-100">
                        <i class="fa fa-undo me-1"></i> Liste Avoirs Vente Enregistrées
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=9309" target="_blank" class="btn btn-dark w-100">
                        <i class="fa fa-undo-alt me-1"></i> Créer Avoir Achat
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=147" target="_blank" class="btn btn-dark w-100">
                        <i class="fa fa-undo-alt me-1"></i> Liste Avoirs Achat Enregistrées
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a id="ceform"  href="http://192.168.1.16:8080/BC260/Default?company=TPG&page=16" target="_blank" class="btn btn-link w-100">
                        <i class="fa fa-book me-1"></i> Plan Comptable
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="https://www.boxylab.net/traites/" target="_blank" class="btn btn-info w-100">
                        <i class="fa fa-undo-alt me-1"></i> Génerer Traite
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Formulaire de recherche par véhicule -->
<div class="card mt-5">
    <div class="card-header bg-primary text-white">
        Recherche par véhicule
    </div>
    <div class="card-body">
        <form method="POST" id="searchForm">
            @csrf
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="brand_id" class="form-label">Marque :</label>
                    <select id="brand_id" name="brand_id" class="form-select select3">
                        <option value="">Sélectionner une marque</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="model_id" class="form-label">Modèle :</label>
                    <select id="model_id" name="model_id" class="form-select select3">
                        <option value="">Sélectionner un modèle</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="engine_id" class="form-label">Motorisation :</label>
                    <select id="engine_id" name="engine_id" class="form-select select3">
                        <option value="">Sélectionner une motorisation</option>
                    </select>
                </div>
            </div>
            <button type="button" id="searchButton" class="btn btn-success w-100">Rechercher les catégories</button>
        </form>

        <div id="categoriesResult" class="mt-4"></div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}
.card-title {
    font-size: 1.1rem;
    font-weight: 600;
}
.card-text {
    font-size: 0.9rem;
    color: #666;
}
</style>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Gestion de la sélection de la marque
    $('#brand_id').change(function() {
        var brandId = $(this).val();

        if (brandId) {
            $.ajax({
                url: "{{ route('getModels') }}",
                type: "GET",
                data: { brand_id: brandId },
                success: function(response) {
                    $('#model_id').empty();
                    $('#model_id').append('<option value="">Sélectionner un modèle</option>');
                    $.each(response, function(index, model) {
                        $('#model_id').append('<option value="' + model.id + '">' + model.name + '</option>');
                    });
                },
                error: function() {
                    alert('Erreur lors du chargement des modèles.');
                }
            });
        } else {
            $('#model_id').empty();
            $('#model_id').append('<option value="">Sélectionner un modèle</option>');
        }
    });

    $('#model_id').change(function() {
        var modelId = $(this).val();

        if (modelId) {
            $.ajax({
                url: "{{ route('getEngines') }}",
                type: "GET",
                data: { model_id: modelId },
                success: function(response) {
                    $('#engine_id').empty();
                    $('#engine_id').append('<option value="">Sélectionner une motorisation</option>');
                    $.each(response, function(index, engine) {
                        $('#engine_id').append('<option value="' + engine.id + '" data-linking-target-id="' + engine.linkageTargetId + '">' + engine.description + '</option>');
                    });
                },
                error: function() {
                    alert('Erreur lors du chargement des motorisations.');
                }
            });
        } else {
            $('#engine_id').empty();
            $('#engine_id').append('<option value="">Sélectionner une motorisation</option>');
        }
    });

// Afficher le linkingTargetId dans la console lors de la sélection d'une motorisation
$('#engine_id').change(function() {
    var selectedOption = $(this).find('option:selected');
    var linkingTargetId = selectedOption.data('linking-target-id');
    console.log("Linkage Target ID sélectionné: ", linkingTargetId);
});


    // Recherche des catégories
$('#searchButton').click(function() {
    var brandId = $('#brand_id').val();
    var modelId = $('#model_id').val();
    var engineId = $('#engine_id').val();

    if (brandId && modelId && engineId) {
        // Récupérer le linkingTargetId de l'option sélectionnée
        var selectedOption = $('#engine_id').find('option:selected');
        var linkingTargetId = selectedOption.data('linking-target-id');  // Récupérer le linking-target-id de l'option sélectionnée

        $.ajax({
            url: "{{ route('getCategories') }}",
            type: "GET",
            data: {
                brand_id: brandId,
                model_id: modelId,
                engine_id: engineId,
                linking_target_id: linkingTargetId  // Inclure le linkingTargetId correct dans la requête
            },
            success: function(response) {
    $('#categoriesResult').empty();

    if (response.length > 0) {
        let row = $('<div class="row g-3"></div>'); // g-3 = espace entre colonnes Bootstrap

        $.each(response, function(index, category) {
            let col = $('<div class="col-md-4"></div>');
            let card = $(`
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">${category.assemblyGroupName}</h5>
                        <p class="card-text">${category.count} article(s)</p>
                        <button class="btn btn-primary w-100 viewArticlesButton" 
                                data-category-id="${category.assemblyGroupNodeId}" 
                                data-engine-id="${engineId}" 
                                data-linking-target-id="${linkingTargetId}">
                            Voir les articles
                        </button>
                    </div>
                </div>
            `);
            col.append(card);
            row.append(col);
        });

        $('#categoriesResult').append(row);
    } else {
        $('#categoriesResult').append('<p>Aucune catégorie trouvée.</p>');
    }
}
,
            error: function() {
                alert('Erreur lors de la récupération des catégories.');
            }
        });
    } else {
        alert('Veuillez sélectionner une marque, un modèle et une motorisation.');
    }
});


$(document).on('click', '.viewArticlesButton', function(e) {
    e.preventDefault();
    var categoryId = $(this).data('category-id');
    var linkingTargetId = $(this).data('linking-target-id');
    var assemblyGroupNodeId = categoryId;

    var url = '{{ route("persoget") }}' + '?assemblyGroupNodeId=' + assemblyGroupNodeId + '&linkingTargetId=' + linkingTargetId + '&category_id=' + categoryId;

    // Popup centré
    var width = 1000;
    var height = 600;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;

    window.open(url, 'popupWindow', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',scrollbars=yes');
});


});

</script>

            
           
           
          </div>
        </div>





           


   









<!-- Essai Catalogue -->
<div class="container" id="catalogue">
<div class="text-center my-4">
  <h2 class="fw-bold text-uppercase text-dark">Catalogue</h2>
  <div class="mx-auto mt-2" style="width: 100px; height: 4px; background-color: #007bff;"></div>
</div>

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
            <p class="card-category mt-2">Système d'essuie-glaces</p>
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
<!-- fin catalogue -->








            
           
           
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















  </body>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select3').select2({
            placeholder: "-- Sélectionner une option --",
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            theme: "classic"
        });
    });
</script>

</html>
