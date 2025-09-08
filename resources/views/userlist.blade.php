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

                                          <li class="nav-item"><a href="/purchaseprojects/list">
              <i class="fas fa-file-alt"></i>
              <p>Projets de Commande</p></a></li>


                                 <li class="nav-item">
              <a href="/returns">
                <i class="fas fa-file-alt"></i>
              <p>Retours Achat</p>
                </a>
              </li>

                                          <li class="nav-item">
              <a href="/invoices">
                <i class="fas fa-money-bill-wave"></i>
              <p>Factures Achat</p>
                </a>
              </li>

                                          <li class="nav-item">
              <a href="/notes">
              <i class="fas fa-reply-all"></i>
              <p>Avoirs Achat</p>
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
              

              <li class="nav-item active">
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

<li class="nav-item">
  <a href="/voice">
    <i class="fas fa-robot"></i>
    <p>NEGOBOT</p>
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

   <h4>📋 Liste des utilisateurs :
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#userModal">
            Nouveau <i class="fas fa-plus-circle ms-2"></i>
        </button>
    </h4>

    
    
    @foreach($users as $user)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                <div>
                    <h6 class="mb-0">
                        <strong>{{ $user->name }}</strong> – {{ $user->email }}
                        <span class="badge bg-info text-dark ms-2">{{ ucfirst($user->role) }}</span>
                    </h6>
                </div>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="togglePermissions({{ $user->id }})">
                        ➕ Détails
                    </button>
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#userModal" onclick="editUser({{ $user }})">
                        ✏️ Modifier
                    </button>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>
            <div id="permissions-{{ $user->id }}" class="card-body d-none bg-light">
                <h6 class="fw-bold mb-3">🔒 Permissions</h6>
                <ul class="list-group">
                    @foreach($user->permissions as $perm)
                        <li class="list-group-item">{{ $perm->label }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal Create/Edit User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Ajouter / Modifier Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <input type="hidden" id="userId" name="user_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-control" id="role" name="role" required>
                              <option value="">Choisir le Role</option>
                                <option value="vendeur">Vendeur</option>
                                <option value="admin">Admin</option>
                                <option value="master">Master</option>
                                <option value="preparateur">Préparateur</option>
                                <option value="livreur">Livreur</option>
                                <option value="comptable">Comptable</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="form-check">
<input class="form-check-input" type="checkbox"
    name="permissions[]"
    value="{{ $permission->id }}"
    id="perm{{ $permission->id }}"
    data-label="{{ strtolower($permission->label) }}">
                                        <label class="form-check-label" for="perm{{ $permission->id }}">{{ $permission->label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>

                <script>
const rolePermissions = {
    vendeur: [
        "vendre", "acheter", "modifier un bl", "faire des règlements",
        "ajuster le stock", "consulter le chiffre d’affaires"
    ],
    admin: [
        "vendre", "acheter", "modifier un bl", "faire des règlements",
        "ajuster le stock", "consulter le chiffre d’affaires",
        "modifier client", "modifier fournisseur", "modifier article",
        "éditer les paramètres de magasin, article et stock",
        "éditer les groupes remises", "éditer le plan comptable"
    ],
    master: "all", // coche tout
    preparateur: [
        "réceptionner", "ajuster le stock", "modifier un article"
    ],
    comptable: [
        "faire des règlements", "consulter le chiffre d’affaires",
        "modifier client", "modifier fournisseur", "modifier article",
        "éditer le plan comptable", "éditer les groupes remises"
    ]
};

document.getElementById("role").addEventListener("change", function () {
    const selectedRole = this.value;
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

    // Réinitialise tout
    checkboxes.forEach(cb => cb.checked = false);

    if (!rolePermissions[selectedRole]) return;

    if (rolePermissions[selectedRole] === "all") {
        checkboxes.forEach(cb => cb.checked = true);
    } else {
        rolePermissions[selectedRole].forEach(label => {
            checkboxes.forEach(cb => {
                if (cb.dataset.label === label.toLowerCase()) {
                    cb.checked = true;
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



<!-- Ajoute select2 si pas déjà inclus -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function toggleLines(id) {
        const section = document.getElementById('lines-' + id);
        section.classList.toggle('d-none');
    }
</script>












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
    function togglePermissions(id) {
        document.getElementById('permissions-' + id).classList.toggle('d-none');
    }

    function editUser(user) {
        document.getElementById('userForm').action = '/users/' + user.id;
        document.getElementById('userId').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('password').value = '';
        document.getElementById('role').value = user.role;

        const perms = user.permissions.map(p => p.id);
        document.querySelectorAll('input[name="permissions[]"]').forEach(chk => {
            chk.checked = perms.includes(parseInt(chk.value));
        });
    }
</script>








  </body>
</html>
