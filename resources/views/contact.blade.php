<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Comptes Généraux</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

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
            width: 900px;
            min-width: 350px;
            padding: 10px;
            border-radius: 8px;
        }

        .panier-dropdown {
            width: 100%;
            min-width: 350px;
        }

        .panier-dropdown .notif-item {
            padding: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .notif-scroll {
            padding: 10px;
        }

        .notif-center {
            padding: 5px 0;
        }

        .dropdown-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
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

        .search-box {
            max-width: 400px;
            height: 35px;
            padding: 5px 12px;
            border: 2px solid #007bff;
            border-radius: 20px;
            font-size: 14px;
            transition: 0.3s ease-in-out;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .search-box:focus {
            border-color: #0056b3;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.5);
        }

        .table-text-small th,
        .table-text-small td,
        .table-text-small input,
        .table-text-small button,
        .table-text-small span,
        .table-text-small svg {
            font-size: 11px !important;
        }

        .table-text-small th {
            font-size: 10px !important;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
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
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                </li>

                <!-- Ventes -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#ventes" aria-expanded="false">
                        <i class="fas fa-shopping-cart"></i><p>Ventes</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="ventes">
                        <ul class="nav nav-collapse">
                            <li><a href="/sales/delivery/create"><span class="sub-item">Nouvelle Commande</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Devis & Précommandes</span></a></li>
                            <li><a href="/delivery_notes/list"><span class="sub-item">Bons de Livraison</span></a></li>
                            <li><a href="/delivery_notes/returns/list"><span class="sub-item">Retours Vente</span></a></li>
                            <li><a href="/salesinvoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/salesnotes/list"><span class="sub-item">Avoirs</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Achats -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#achats" aria-expanded="false">
                        <i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="achats">
                        <ul class="nav nav-collapse">
                            <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                            <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d’Achat</span></a></li>
                            <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                            <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Comptabilité -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#compta" aria-expanded="false">
                        <i class="fas fa-balance-scale"></i><p>Comptabilité</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="compta">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('generalaccounts.index') }}"><span class="sub-item">Plan Comptable</span></a></li>
                            <li><a href="{{ route('payments.index') }}"><span class="sub-item">Règlements</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Stock -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#stock" aria-expanded="false">
                        <i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="stock">
                        <ul class="nav nav-collapse">
                            <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                            <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                            <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Référentiel -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#referentiel" aria-expanded="false">
                        <i class="fas fa-users"></i><p>Référentiel</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="referentiel">
                        <ul class="nav nav-collapse">
                            <li><a href="/customers"><span class="sub-item">Clients</span></a></li>
                            <li><a href="/suppliers"><span class="sub-item">Fournisseurs</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Paramètres -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#parametres" aria-expanded="false">
                        <i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="parametres">
                        <ul class="nav nav-collapse">
                            <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Outils -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#outils" aria-expanded="false">
                        <i class="fab fa-skyatlas"></i><p>Outils</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="outils">
                        <ul class="nav nav-collapse">
                            <li><a href="/analytics"><span class="sub-item">Analytics</span></a></li>
                            <li><a href="/tecdoc"><span class="sub-item">TecDoc</span></a></li>
                            <li><a href="/voice"><span class="sub-item">NEGOBOT</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Assistance -->
<li class="nav-item">
    <a href="/contact">
        <i class="fas fa-headset"></i>
        <p>Assistance</p>
    </a>
</li>


                <!-- Déconnexion -->
                <li class="nav-item">
                    <a href="{{ route('logout.admin') }}" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                        </div>
                        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                    </div>
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">


                        <!-- test quick action  -->
<li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Actions Rapides</span>
                      <!-- <span class="subtitle op-7">Liens Utiles</span> -->
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">

                                                  <a class="col-6 col-md-4 p-0" href="/articles">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-sitemap"></i>
                              </div>
                              <span class="text">Articles</span>
                            </div>
                          </a>

                                                                            <a class="col-6 col-md-4 p-0" href="/customers">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-users"></i>
                              </div>
                              <span class="text">Clients</span>
                            </div>
                          </a>


                                                                                                      <a class="col-6 col-md-4 p-0" href="/suppliers">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-user-tag"></i>
                              </div>
                              <span class="text">Fournisseurs</span>
                            </div>
                          </a>



                          <a class="col-6 col-md-4 p-0" href="/delivery_notes/list">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="fa fa-cart-plus"></i>
                              </div>
                              <span class="text">Commandes Ventes</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/salesinvoices">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Factures Ventes</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/generalaccounts">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-money-check-alt"></i>
                              </div>
                              <span class="text">Plan Comptable</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/purchases/list">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fa fa-cart-plus"></i>
                              </div>
                              <span class="text">Commandes Achats</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="/invoices">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Factures Achats</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/paymentlist">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Paiements</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                        <!-- fin test quick action  -->

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramètres</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
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
            </div>



                      <div class="container">
                <div class="page-inner">






<div class="container mt-5">
    <div class="card shadow-lg">
        
        <div class="card-header bg-dark text-white">
            <h4><i class="fas fa-life-ring"></i> Assistance & Support</h4>
        </div>
        <div class="card-body">

            {{-- Message de succès --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Formulaire --}}

            <h4><i class="fas fa-life-ring"></i> Lancer un Ticket</h4>


            <form action="{{ route('contact.send') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', auth()->user()->name ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email', auth()->user()->email ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Objet</label>
                    <input type="text" name="subject" class="form-control" required value="{{ old('subject') }}">
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Votre message</label>
                    <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </form>

            {{-- Historique des tickets --}}
            @if(!empty($tickets) && count($tickets) > 0)
                <h5 class="mt-4"><i class="fas fa-clipboard-list"></i> Vos tickets récents</h5>
                  <table class="table table-striped table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->name }}</td>
                        <td>{{ $ticket->email }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td style="max-width: 250px; word-wrap: break-word;">
                            {{ $ticket->message }}
                        </td>
                        <td>
                            {{-- Affichage badge statut --}}
@if($ticket->status == 'Ouvert')
    <span class="badge bg-warning">Ouvert</span>
@elseif($ticket->status == 'En cours')
    <span class="badge bg-info">En cours</span>
@elseif($ticket->status == 'Clôturé')
    <span class="badge bg-success">Clôturé</span>
@endif

                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            {{-- Formulaire changement de statut --}}
<form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST">
    @csrf
    @method('PUT')
    <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
        <option value="Ouvert" {{ $ticket->status == 'Ouvert' ? 'selected' : '' }}>Ouvert</option>
        <option value="En cours" {{ $ticket->status == 'En cours' ? 'selected' : '' }}>En cours</option>
        <option value="Clôturé" {{ $ticket->status == 'Clôturé' ? 'selected' : '' }}>Clôturé</option>
    </select>
</form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Aucun ticket trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            @else
                <p class="text-muted">Aucun ticket pour l’instant.</p>
            @endif

        </div>
    </div>
</div>
    </div>
</div>



        <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        © AZ NEGOCE. All Rights Reserved.
                    </div>
                    <div>
                        by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>    