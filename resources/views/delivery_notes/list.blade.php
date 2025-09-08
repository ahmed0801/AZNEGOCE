<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Liste des Bons de Livraison</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        #panierDropdown + .dropdown-menu { width: 900px; min-width: 350px; padding: 10px; border-radius: 8px; }
        .panier-dropdown { width: 100%; min-width: 350px; }
        .panier-dropdown .notif-item { padding: 10px; margin-bottom: 5px; border-bottom: 1px solid #ddd; }
        .dropdown-title { font-weight: bold; margin-bottom: 10px; }
        .notif-scroll { padding: 10px; }
        .notif-center { padding: 5px 0; }
        .dropdown-footer { padding: 10px; border-top: 1px solid #ddd; }
        .table { width: 100%; margin-bottom: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f2f2f2; }
        .btn-sm { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
        .text-muted { font-size: 0.85rem; }
        .text-center { text-align: center; }
        .card { border-radius: 12px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card h3 { font-size: 1.8rem; color: #007bff; margin-bottom: 1rem; font-weight: 700; }
        .card h6 { font-size: 1rem; color: #6c757d; }
        .card-body { padding: 2rem; }
        .card .text-info { color: #17a2b8 !important; }
        .btn-primary { font-size: 1.1rem; padding: 1rem 1.5rem; border-radius: 8px; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #0056b3; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); }
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
                        <li class="nav-item active"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
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
                                                <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>

                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
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
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramétres</a>
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
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <h4>📋 Liste des bons de livraison :
                        <a href="{{ route('sales.delivery.create') }}" class="btn btn-sm btn-success">
                            Nouveau BL <i class="fas fa-plus-circle ms-2"></i>
                        </a>
                        <a href="{{ route('salesinvoices.create_grouped') }}" class="btn btn-sm btn-success">Facture groupée <i class="fas fa-file-invoice ms-2"></i></a>
                    </h4>

                    <form method="GET" action="{{ route('delivery_notes.list') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                        <select name="numclient" class="form-select form-select-sm select2" style="width: 150px;">
                            <option value="">Client (Tous)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->code }}" {{ request('numclient') == $customer->code ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status" class="form-select form-select-sm" style="width: 170px;">
                            <option value="">Statut BL (Tous)</option>
                            <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="expédié" {{ request('status') == 'expédié' ? 'selected' : '' }}>Expédié</option>
                            <option value="annulé" {{ request('status') == 'annulé' ? 'selected' : '' }}>Annulé</option>

                        </select>

                        <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" placeholder="Date début" value="{{ request('date_from') }}">
                        <span>à</span>
                        <input type="date" name="date_to" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ request('date_to') }}">

                        <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fas fa-filter me-1"></i> Filtrer
                        </button>

                        <a href="{{ route('delivery_notes.list') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-undo me-1"></i> Réinitialiser
                        </a>
                    </form>

                    @foreach ($deliveryNotes as $deliveryNote)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                <div>
                                    <h6 class="mb-0">
                                        <strong>BL N° : {{ $deliveryNote->numdoc }}</strong>
                                        (&#x1F482;{{ $deliveryNote->numclient }} – {{ $deliveryNote->customer->name?? 'Client inconnu'}} )
                                        <span class="text-muted small">- 📆{{ \Carbon\Carbon::parse($deliveryNote->delivery_date)->format('d/m/Y') }}</span>
                                    </h6>
     
 <span class="badge bg-{{ $deliveryNote->status === 'en_cours' ? 'warning' : ($deliveryNote->status === 'expédié' ? 'success' : 'danger') }}">
                                           BL {{ ucfirst($deliveryNote->status) }}
  </span>  
  <span class="badge bg-{{ $deliveryNote->status_livraison === 'non_livré' ? 'warning' : ($deliveryNote->status_livraison === 'livré' ? 'success' : 'danger') }}">
                                           {{ ucfirst($deliveryNote->status_livraison) }}
                                        </span> 

                                                                      <span class="badge bg-info">@if($deliveryNote->salesOrder) CMD: {{ $deliveryNote->salesOrder->numdoc ?? '-' }} 
                                                                         @endif</span>

                                                                                              <span class="text-muted small">
                                     @if($deliveryNote->invoiced)
                            ☑Facturé
                            @endif
                            @if($deliveryNote->salesReturns()->exists())
                                ↪︎ {{ count($deliveryNote->salesReturns) }} Retour(s) associé(s)
                            @endif

                           

                        </span>


                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $deliveryNote->id }})">
                                        ➕ Détails
                                    </button>
                                    <a href="{{ route('delivery_notes.export_single', $deliveryNote->id) }}" class="btn btn-xs btn-outline-success">
                                        EXCEL <i class="fas fa-file-excel"></i>
                                    </a>
                                    <a href="{{ route('delivery_notes.print_single', $deliveryNote->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                        PDF <i class="fas fa-print"></i>
                                    </a>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($deliveryNote->status === 'en_cours')
                                                <a class="dropdown-item" href="{{ route('delivery_notes.edit', $deliveryNote->id) }}">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <form action="{{ route('delivery_notes.validate', $deliveryNote->id) }}" method="POST" onsubmit="return confirm('Valider cette expédition ?')" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check"></i> Valider l'expédition
                                                    </button>
                                                </form>



                                                <form action="{{ route('delivery_notes.cancel', $deliveryNote->id) }}" method="POST" onsubmit="return confirm('Annuler cette expédition ?')" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-times"></i> Annuler l'expédition
                                                    </button>
                                                </form>
                                            @endif

                                            @if($deliveryNote->status_livraison === 'non_livré')
                                                                                            <form action="{{ route('delivery_notes.ship', $deliveryNote->id) }}" method="POST" onsubmit="return confirm('Valider cette expédition ?')" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check"></i> Confirmer la livraison
                                                    </button>
                                                </form>
                                                @endif


                                                @if($deliveryNote->status === 'expédié' || $deliveryNote->status_livraison === 'livré')
                                        <a class="dropdown-item" href="{{ route('delivery_notes.salesreturns.create', $deliveryNote->id) }}">
                                        <i class="fas fa-undo"></i> Créer un retour
                                    </a>


@endif

@if($deliveryNote->status === 'expédié')
                                                 <a class="dropdown-item" href="#" data-toggle="modal" data-target="#commentModal" 
                                                   onclick="setCommentForm('{{ route('delivery_notes.shipping_note', $deliveryNote->id) }}', {{ $deliveryNote->id }})">
                                                    <i class="fas fa-truck"></i> Bordereau d'envoi
                                                </a>
                                           
                                            

                                @if($deliveryNote->salesReturns()->exists())
                                    @foreach($deliveryNote->salesReturns as $return)
                                        <a class="dropdown-item" href="{{ route('delivery_notes.salesreturns.show', $return->id) }}">
                                            <i class="fas fa-eye"></i> Retour #{{ $return->numdoc }} ({{ ucfirst($return->type) }})
                                        </a>
                                    @endforeach
                                @endif

                                @if(!$deliveryNote->invoiced)
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.create_direct', $deliveryNote->id) }}">
                                                        <i class="fas fa-file-invoice"></i> Créer facture directe
                                                    </a>
                                                @endif

 @endif

                                        </div>
                                    </div>

                                    
                                </div>
                            </div>

                            <div id="lines-{{ $deliveryNote->id }}" class="card-body d-none bg-light">
                                @if($deliveryNote->notes)
                                <h6 class="fw-bold mb-3">🧾 Note : {{$deliveryNote->notes}}</h6>
                                @endif
                                <!-- <h6 class="fw-bold mb-3">🧾 Lignes du bon de livraison</h6> -->
                                <table class="table table-sm table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Code Article</th>
                                            <th>Désignation</th>
                                            <th>Qté Livrée</th>
                                            <th>PU HT</th>
                                            <th>Remise (%)</th>
                                            <th>Total Ligne</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deliveryNote->lines as $line)
                                            <tr>
                                                <td>{{ $line->article_code }}</td>
                                                <td>{{ $line->item->name ?? '-' }}</td>
                                                <td class="text-center">{{ $line->delivered_quantity }}</td>
                                                <td class="text-end">{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                                <td class="text-end">{{ $line->remise }}%</td>
                                                <td class="text-end">{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="text-end mt-3">
                                    <div class="p-3 bg-white border rounded d-inline-block">
                                        <strong>Total HT :</strong> {{ number_format($deliveryNote->total_ht, 2, ',', ' ') }} €<br>
                                        <strong>Total TTC :</strong> {{ number_format($deliveryNote->total_ttc, 2, ',', ' ') }} €
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



<!-- Modal pour le commentaire -->
            <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentModalLabel">Ajouter un commentaire</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="commentForm" method="POST" target="_blank">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="comment">Commentaire (facultatif)</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Saisissez un commentaire (optionnel)"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Générer PDF</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- fin commentaire -->


            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.</div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });
        });

        function toggleLines(id) {
            const section = document.getElementById('lines-' + id);
            section.classList.toggle('d-none');
        }

         function setCommentForm(url, id) {
            document.getElementById('commentForm').action = url;
                        document.getElementById('comment').value = ''; // Réinitialiser le champ de commentaire

        }
    </script>
</body>
</html>