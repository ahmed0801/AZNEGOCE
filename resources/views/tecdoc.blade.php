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
    /* background-color: #f8f9fa; */
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




.viewArticlesButton {
    margin-bottom: 10px;
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
                        <li class="nav-item"><a href="/reglement-client"><i class="fas fa-credit-card"></i><p>Règlement Client</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                        <li class="nav-item"><a href="/reglement-fournisseur"><i class="fas fa-credit-card"></i><p>Règlement Fournisseur</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item active"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
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

    
        <div class="container mt-5">
        <h1 class="mb-4 d-flex align-items-center justify-content-center">
    <img src="assets/img/tecdoc.png" alt="TecDoc Logo" class="me-2" height="70"> <!-- Logo TecDoc -->
    <!-- Recherche Avancée -->
</h1>
       
        <form action="{{ route('tecdoc.search') }}" method="GET">
            <div class="mb-3">
                <label for="reference" class="form-label">Tapez n'importe quelle Référence</label>
                <input type="text" class="form-control" name="reference" id="reference" required>
            </div>
            <button type="submit" class="btn btn-sm btn-primary px-3 py-1">Rechercher</button>
            </form>

        @if(isset($articles))
            <h2 class="mt-4">Résultats pour : <strong>{{ $reference }}</strong></h2>
            @if($articles)
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Numéro d'article</th>
                                <th>Nom de l'article</th>
                                <th>Marque</th>
                                <th>Références OE</th>
                                <th>Action</th>
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
                <strong>{{ $oen->brandName }}</strong> - {{ $oen->oeNumber }}
                <form action="{{ route('items.search') }}" method="POST" target="_blank" style="display:inline;">
                    @csrf
                    <input type="hidden" name="itemFilter" value="{{ $oen->oeNumber }}">
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">🔍</button>
                </form>
            </li>
        @endforeach
    </ul>
@else
    <span class="text-muted">Aucune référence OE disponible</span>
@endif

                                    </td>
                                    <td>
                                    <form action="{{ route('items.search') }}" method="POST" target="_blank">
    @csrf
    <input type="hidden" name="itemFilter" value="{{ $article->directArticle->articleNo }}">
    <button type="submit">Vérifier dans stock</button>
</form>



                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
            @else
            <div class="alert alert-warning mt-2 py-1 px-2 small">Aucun résultat trouvé.</div>
            @endif

     
    

    <div id="ceform" class="card mt-5">
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
