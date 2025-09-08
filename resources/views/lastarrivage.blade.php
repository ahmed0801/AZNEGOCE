<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PREMA B2B</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />




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
              
              <li class="nav-item active">
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
             



            
<!-- test 3 arrivages  -->
<nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">


<li class="nav-item topbar-icon dropdown hidden-caret">
<a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="fa fa-bell"></i>
<span class="notification-arrivages fw-bold text-dark">3 Derniers Arrivages</span>
</a>
<ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
<li>
<div class="dropdown-title">Les 3 derniers arrivages</div>
</li>
<li>
<div class="notif-scroll scrollbar-outer">
    <div class="notif-center">
        @foreach ($arrivages as $arrivage)
        <a href="{{ route('lastarrivage', ['id' => $arrivage->id]) }}">
        <div class="notif-icon notif-primary">
                    <i class="fa fa-shipping-fast"></i>
                </div>
                <div class="notif-content">
                    <span class="block">{{ $arrivage->title }}</span>
                    <span class="block">Le {{ $arrivage->created_at->format('d/m/Y') }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
</li>
</ul>
</li>




  </nav>
<!-- fin test 3 arrivages  -->








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
                        <!-- Message de montant estimé -->
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
                        src="{{ asset('assets/img/avatar.png') }}"
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
                              src="{{ asset('assets/img/avatar.png')}}"
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



            @if (session('user')['Blocked'] != " ")
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



      




        

        {{-- Tableau des articles --}}
        <div class="col-md-12">
            <div class="card">
            <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Consultez les Article du  {{ $lastArrivage->title }}</h4>
            <!-- filtre  -->




            




            <!-- fin filtre -->
        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if(isset($items) && count($items) > 0)
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Réference</th>
                                        <th>Description</th>
                                        <th>Prix</th>
                                        <!-- <th>Fournisseur</th> -->
                                        <th>Dispo</th>
                                        <th>Ajouter Au Panier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $item['ItemNo'] ?? 'Non disponible' }}</td>
                                            <td>{{ $item['Desc'] ?? 'Non disponible' }}</td>
                                            <td>{{ $item['Price'] ?? 'Non disponible' }}</td>
                                            <!-- <td>{{ isset($item['VendorName']) ? trim($item['VendorName']) : 'Non disponible' }}</td> -->
                                            <td>
                                            @if ($item['Stock']>0)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
  <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
</svg>
@if (session('user')['CustomerNo']=='CL000028')
                                            {{ $item['Stock'] }}
                                            @endif
                                              @else 
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-patch-exclamation-fill" viewBox="0 0 16 16">
  <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
</svg>
                                              @endif
                                            </td>
                                            <td>
                                              @if ($item['Stock']>0)
    <div class="input-group" style="max-width: 200px; margin: auto;">
        <input type="number" id="quantite-{{ $item['ItemNo'] }}" min="1" 
            class="form-control" placeholder="Quantité" style="width: 50px;">
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
                            <!-- <p>Aucun article trouvé pour cette recherche.</p> -->
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
                    alert('Article ajouté au panier');
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
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalHT = 0;
        const discountPercent = {{ session('user')['CustomerDiscPercent'] ?? 0 }}; // Récupérer la remise depuis la session

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
                <strong>Total HT : </strong>${totalAvecRemise.toFixed(3)} TND
                ${discountPercent !== 0 ? `<strong> / Remise Appliquée : </strong> - ${discountPercent} %` : ''}
            </div>
            <div class="mt-2 text-muted">
                <small>Ce montant est estimé et non final, avant vérification du stock.</small>
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
        const stockFilter = filterStock.value;
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

  </body>
</html>
