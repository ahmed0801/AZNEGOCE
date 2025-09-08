
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ NEGOCE - Planification des Tournées</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .filter-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: 20px;
        }
        .filter-group .form-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-group .form-control,
        .filter-group .select2-container--classic .select2-selection--single {
            height: 38px;
            font-size: 0.9rem;
            border-radius: 5px;
        }
        .table th, .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .badge-type {
            margin-right: 5px;
            font-size: 0.8rem;
        }
        .document-link {
            cursor: pointer;
            color: #007bff;
            text-decoration: none;
        }
        .document-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .address-tooltip {
            cursor: pointer;
            position: relative;
        }
        .address-tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #343a40;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.8rem;
        }
        .address-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .btn-action {
            margin-right: 5px;
            transition: background-color 0.2s;
        }
        .btn-action:hover {
            opacity: 0.9;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .modal-body .table {
            margin-top: 15px;
        }
        @media (max-width: 768px) {
            .filter-group {
                flex-direction: column;
            }
            .filter-group .form-group {
                min-width: 100%;
            }
            .table {
                font-size: 0.85rem;
            }
            .btn-action {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
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
                        <li class="nav-item"><a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
                        @if(Auth::user()->role !='livreur') 
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/commande"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/avoirs"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
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
                        @endif
                        <li class="nav-item active"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        @if(Auth::user()->role !='livreur') 
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-robot"></i></span><h4 class="text-section">Autres</h4></li>
                        <li class="nav-item"><a href="/voice"><i class="fas fa-robot"></i><p>NEGOBOT</p></a></li>
                        @endif
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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Planification des Tournées</h3>
                            <h6 class="op-7 mb-2">Gestion des tournées des chauffeurs</h6>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="{{ route('planification.tournee.create') }}" class="btn btn-primary btn-round btn-action">
                                <i class="fas fa-plus"></i> Nouvelle Tournée
                            </a>
                            <a href="/planification-tournee/planning-chauffeur" class="btn btn-secondary btn-round btn-action">
                                <i class="fas fa-file-alt"></i> Ma Journée
                            </a>
                            <a href="{{ route('planification.tournee.rapport') }}" class="btn btn-info btn-round btn-action">
                                <i class="fas fa-file-alt"></i> Rapport Complet
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Liste des Tournées</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('planification.tournee.index') }}" class="filter-group mb-4">
                                <div class="form-group">
                                    <label for="date_debut">Date Début</label>
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                </div>
                                <div class="form-group">
                                    <label for="date_fin">Date Fin</label>
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                </div>
                                <div class="form-group">
                                    <label for="chauffeur_id">Chauffeur</label>
                                    <select name="chauffeur_id" id="chauffeur_id" class="form-control select2">
                                        <option value="">Tous les chauffeurs</option>
                                        @foreach ($chauffeurs as $chauffeur)
                                            <option value="{{ $chauffeur->id }}" {{ request('chauffeur_id') == $chauffeur->id ? 'selected' : '' }}>{{ $chauffeur->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group align-self-end">
                                    <button type="submit" class="btn btn-primary btn-action">Filtrer</button>
                                    <a href="{{ route('planification.tournee.index') }}" class="btn btn-secondary btn-action">Réinitialiser</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0" id="tourneesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="display: none;">Updated At</th>
                                            <th>Chauffeur</th>
                                            <th>Date et Heure</th>
                                            <th>Documents</th>
                                            <th>Adresses</th>
                                            <th>Statut</th>
                                            <th>Validée</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($planifications as $planification)
                                            <tr>
                                                <td style="display: none;">{{ $planification->updated_at->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ $planification->utilisateur->name ?? 'N/A' }}</td>
                                                <td>{{ $planification->datetime_planifie->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    @if ($planification->commandesAchats->isNotEmpty() || $planification->bonsLivraisons->isNotEmpty())
                                                        <a href="#" class="document-link" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $planification->id }}">Voir les documents ({{ $planification->commandesAchats->count() + $planification->bonsLivraisons->count() }})</a>
                                                    @else
                                                        Aucun document
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($planification->commandesAchats->isNotEmpty() || $planification->bonsLivraisons->isNotEmpty())
                                                        <div class="address-tooltip">
                                                            Adresses ({{ $planification->commandesAchats->count() + $planification->bonsLivraisons->count() }})
                                                            <span class="tooltip-text">
                                                                @foreach ($planification->commandesAchats as $ca)
                                                                    {{ $ca->supplier->address_delivery ?? $ca->supplier->address ?? 'Adresse inconnue' }}<br>
                                                                @endforeach
                                                                @foreach ($planification->bonsLivraisons as $bl)
                                                                    {{ $bl->customer->address_delivery ?? $bl->customer->address ?? 'Adresse inconnue' }}<br>
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    @else
                                                        Aucune adresse
                                                    @endif
                                                </td>
                                                <td><span class="badge status-badge bg-{{ $planification->statut === 'planifié' ? 'primary' : ($planification->statut === 'terminé' ? 'success' : 'warning') }}">{{ ucfirst($planification->statut) }}</span></td>
                                                <td><span class="badge status-badge bg-{{ $planification->isValidee() ? 'success' : 'secondary' }}">{{ $planification->isValidee() ? 'Oui' : 'Non' }}</span></td>
                                                <td>{{ Str::limit($planification->notes ?? 'N/A', 50) }}</td>
                                                <td>
                                                    @if (!$planification->isValidee())
                                                    @if(auth::user()->id == $planification->user_id or auth::user()->role !='livreur')
                                                        <a href="{{ route('planification.tournee.edit', $planification->id) }}" class="btn btn-sm btn-warning btn-action" title="Éditer"><i class="fas fa-edit"></i></a>
                                                        @endif
                                                        @if(auth::user()->role !='livreur')
                                                        <form action="{{ route('planification.tournee.destroy', $planification->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette tournée ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger btn-action" title="Supprimer"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                        @endif
                                                    @endif
                                                    <a href="{{ route('planification.tournee.rapport') }}?planification_tournee_id={{ $planification->id }}" class="btn btn-sm btn-info btn-action" title="Voir le rapport"><i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modals for Document Details -->
                    @foreach ($planifications as $planification)
                        <div class="modal fade" id="detailsModal-{{ $planification->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-{{ $planification->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailsModalLabel-{{ $planification->id }}">Détails des Documents - Tournée {{ $planification->datetime_planifie->format('d/m/Y H:i') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($planification->commandesAchats->isNotEmpty() || $planification->bonsLivraisons->isNotEmpty())
                                            @foreach ($planification->commandesAchats as $ca)
                                                <div>
                                                    <span class="badge badge-primary badge-type">Commande Achat</span>
                                                    <strong>{{ $ca->numdoc }} ({{ $ca->status }})</strong>
                                                </div>
                                                @if ($ca->lines->isNotEmpty())
                                                    <table class="table table-bordered mt-2">
                                                        <thead>
                                                            <tr>
                                                                <th>Code Article</th>
                                                                <th>Nom Article</th>
                                                                <th>Qté à Recevoir</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ca->lines as $line)
                                                                <tr>
                                                                    <td>{{ $line->article_code }}</td>
                                                                    <td>{{ $line->item->name ?? 'Non disponible' }}</td>
                                                                    <td>{{ $line->ordered_quantity }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            @endforeach
                                            @foreach ($planification->bonsLivraisons as $bl)
                                                <div>
                                                    <span class="badge badge-success badge-type">Bon de Livraison</span>
                                                    <strong>{{ $bl->numdoc }} ({{ $bl->status }})</strong>
                                                </div>
                                                @if ($bl->lines->isNotEmpty())
                                                    <table class="table table-bordered mt-2">
                                                        <thead>
                                                            <tr>
                                                                <th>Code Article</th>
                                                                <th>Nom Article</th>
                                                                <th>Qté à Livrer</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($bl->lines as $line)
                                                                <tr>
                                                                    <td>{{ $line->article_code }}</td>
                                                                    <td>{{ $line->item->name ?? 'Non disponible' }}</td>
                                                                    <td>{{ $line->delivered_quantity }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            @endforeach
                                        @else
                                            <p>Aucun document associé.</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.</div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "-- Sélectionner un chauffeur --",
                allowClear: true,
                width: '100%',
                theme: "classic"
            });

            $('#tourneesTable').DataTable({
                responsive: true,
                language: {
                    url: "{{ asset('assets/js/i18n/fr-FR.json') }}"
                },
                order: [[0, 'desc']], // Sort by hidden updated_at column
                columnDefs: [
                    { targets: [0], visible: false }, // Hide updated_at column
                    { targets: [3, 4, 6, 7], orderable: false } // Disable sorting for Documents, Adresses, Notes, Actions
                ],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                dom: '<"top"f>rt<"bottom"lip><"clear">', // Add search bar at top
            });
        });
    </script>
</body>
</html>
