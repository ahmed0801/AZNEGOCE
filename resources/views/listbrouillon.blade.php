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

              <li class="nav-item active">
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

        <h4>Mes Brouillon :</h4>

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
</style>

<!-- fin recherche dans le tableau -->


    @if (!empty($entetedevis))
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                <th>Num Devis</th>
                <th>Num Client</th>
                <th>Nom Client</th>
                <th>Date</th>
                <th>Action</th>
                <th>Imprimer</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($entetedevis as $devis)
                <tr>
                    <td>BR-{{ $devis->id }}</td>
                    <td>{{ $devis->user_id ?? 'N/A' }}</td>
                    <td>{{ $devis->CustomerName ?? 'N/A' }}</td>
                    <td>{{ $devis->created_at->format('d/m/Y') }}</td>
                    <td>
                    <a href="{{ route('brouillon.show', $devis->id) }}" class="btn btn-info btn-sm" title="Voir le devis">
    Voir <i class="fas fa-eye"></i>
</a>

<a href="{{ route('devis.loadPanier', $devis->id) }}" 
   class="btn btn-secondary btn-sm text-white" 
   title="Charger dans le panier">
   <i class="fas fa-upload"></i> Charger au Panier
</a>

                    </td>

                    <td>
    <!-- Bouton PDF principal -->
    <a href="{{ route('devis.exportPdf', $devis->id) }}"
       class="btn btn-success btn-sm text-white"
       title="Télécharger PDF" target="_blank">
       <i class="fas fa-file-pdf"></i> PDF
    </a>

    <!-- Dropdown pour les autres formats -->
    <div class="btn-group">
        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Autres</span> <i class="fas fas fa-cog"></i>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('devis.exportPdfsansref', $devis->id) }}" target="_blank">
                <i class="fas fa-file-pdf"></i> Sans Référence
            </a>
            <a class="dropdown-item" href="{{ route('devis.exportPdfsansremise', $devis->id) }}" target="_blank">
                <i class="fas fa-file-pdf"></i> Sans Remise
            </a>
            <a class="dropdown-item" href="{{ route('devis.exportPdfsans2', $devis->id) }}" target="_blank">
                <i class="fas fa-file-pdf"></i> Sans Réf & Remise
            </a>
        </div>
    </div>
</td>



                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-center text-muted">Aucun Devis disponible.</p>
    @endif
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
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>





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
</html>
