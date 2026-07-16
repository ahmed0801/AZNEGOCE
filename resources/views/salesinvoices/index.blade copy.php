<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Liste des Factures de Vente</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
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
        .form-select-sm { width: auto; display: inline-block; }
        .badge { font-size: 0.85rem; }
        .filter-box { border: 1px solid #dcdcdc; border-radius: 6px; padding: 6px 8px !important; background: #f2f1f1ff; }
        .bg-gradient-primary { background: linear-gradient(135deg, #007bff, #6610f2) !important; }
        .modal-content { border-radius: 15px; overflow: hidden; }
        .card.shadow-sm { transition: transform 0.2s; }
    </style>
</head>
<body>
    <div class="wrapper">
       <!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="/" class="logo">
                <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="70" />
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
                <li class="nav-item"><a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#ventes" aria-expanded="false"><i class="fas fa-shopping-cart"></i><p>Ventes</p><span class="caret"></span></a>
                    <div class="collapse" id="ventes">
                        <ul class="nav nav-collapse">
                            <li><a href="/sales/delivery/create"><span class="sub-item">Nouvelle Commande</span></a></li>
                            <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
                            <li><a href="/delivery_notes/list"><span class="sub-item">Bons de Livraison</span></a></li>
                            <li><a href="/delivery_notes/returns/list"><span class="sub-item">Retours Vente</span></a></li>
                            <li><a href="/salesinvoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/salesnotes/list"><span class="sub-item">Avoirs</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#achats" aria-expanded="false"><i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span></a>
                    <div class="collapse" id="achats">
                        <ul class="nav nav-collapse">
                            <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                            <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d'Achat</span></a></li>
                            <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                            <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#compta" aria-expanded="false"><i class="fas fa-balance-scale"></i><p>Comptabilité</p><span class="caret"></span></a>
                    <div class="collapse" id="compta">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('generalaccounts.index') }}"><span class="sub-item">Plan Comptable</span></a></li>
                            <li><a href="{{ route('payments.index') }}"><span class="sub-item">Règlements</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#stock" aria-expanded="false"><i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span></a>
                    <div class="collapse" id="stock">
                        <ul class="nav nav-collapse">
                            <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                            <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                            <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#referentiel" aria-expanded="false"><i class="fas fa-users"></i><p>Référentiel</p><span class="caret"></span></a>
                    <div class="collapse" id="referentiel">
                        <ul class="nav nav-collapse">
                            <li><a href="/customers"><span class="sub-item">Clients</span></a></li>
                            <li><a href="/suppliers"><span class="sub-item">Fournisseurs</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#parametres" aria-expanded="false"><i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span></a>
                    <div class="collapse" id="parametres">
                        <ul class="nav nav-collapse">
                            <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#outils" aria-expanded="false"><i class="fab fa-skyatlas"></i><p>Outils</p><span class="caret"></span></a>
                    <div class="collapse" id="outils">
                        <ul class="nav nav-collapse">
                            <li><a href="/analytics"><span class="sub-item">Analytics</span></a></li>
                            <li><a href="/tecdoc"><span class="sub-item">TecDoc</span></a></li>
                            <li><a href="/voice"><span class="sub-item">NEGOBOT</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="/contact"><i class="fas fa-headset"></i><p>Assistance</p></a></li>
                <li class="nav-item">
                    <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display: none;">@csrf</form>
                </li>
            </ul>
        </div>
    </div>
</div>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="/" class="logo"><img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="20" /></a>
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
            
           <!-- BOUTON CONSULTER LA TOURNÉE -->
<li class="nav-item me-3">
    <a href="https://tournee.destockpa.fr" 
       target="_blank"
       class="btn btn-primary btn-round d-flex align-items-center gap-2 shadow-sm"
       style="font-weight: 600; padding: 8px 18px; font-size: 1rem;">
        <i class="fas fa-truck-loading"></i>
        <span>Consulter la Tournée</span>
    </a>
</li>

            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false"><i class="fas fa-layer-group"></i></a>
                <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header"><span class="title mb-1">Actions Rapides</span></div>
                    <div class="quick-actions-scroll scrollbar-outer">
                        <div class="quick-actions-items">
                            <div class="row m-0">
                                <a class="col-6 col-md-4 p-0" href="/articles"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fas fa-sitemap"></i></div><span class="text">Articles</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/customers"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-users"></i></div><span class="text">Clients</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/suppliers"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-user-tag"></i></div><span class="text">Fournisseurs</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/delivery_notes/list"><div class="quick-actions-item"><div class="avatar-item bg-danger rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Ventes</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/salesinvoices"><div class="quick-actions-item"><div class="avatar-item bg-warning rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Ventes</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/generalaccounts"><div class="quick-actions-item"><div class="avatar-item bg-info rounded-circle"><i class="fas fa-money-check-alt"></i></div><span class="text">Plan Comptable</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/purchases/list"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Achats</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/invoices"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Achats</span></div></a>
                                <a class="col-6 col-md-4 p-0" href="/paymentlist"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-credit-card"></i></div><span class="text">Paiements</span></div></a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <!-- Le reste de ton code (profil utilisateur) reste identique -->
            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm"><img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" /></div>
                    <span class="profile-username"><span class="fw-bold">{{ Auth::user()->name }}</span></span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg"><img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" /></div>
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
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                    <div class="container mt-4">
                        <h4>📋 Liste des factures de vente :
                            <a href="{{ route('salesinvoices.create_grouped') }}" class="btn btn-outline-success btn-round ms-2">
                                <span style="cursor:pointer;" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="left" title="À quoi sert cette fonction ?" data-bs-content="Ce bouton permet de collecter tous les BL et retours non facturés d'un client afin de générer une seule facture groupée.">
                                    Nouvelle Facture Groupée <i class="fas fa-plus-circle ms-2"></i>
                                </span>
                            </a>
                            <a href="{{ route('sales.delivery.create') }}" class="btn btn-outline-success btn-round ms-2">
                                Nouvelle Commande <i class="fas fa-plus-circle ms-2"></i>
                            </a>
                            <a href="/salesnotes/create" class="btn btn-outline-danger btn-round ms-2">
                                Nouvel Avoir Vente <i class="fas fa-plus-circle ms-2"></i>
                            </a>
                            <a href="{{ route('delivery_notes.returns.free.create') }}" class="btn btn-outline-danger btn-round ms-2">
                                Créer Retour Libre <i class="fas fa-plus-circle ms-2"></i>
                            </a>
                        </h4>

                        <div class="filter-box mb-2 p-2">
                            <form method="GET" action="{{ route('salesinvoices.index') }}" class="d-flex flex-wrap align-items-end gap-2">
                                <select name="customer_id" class="form-select form-select-sm select2" style="width: 140px;">
                                    <option value="">Client (Tous)</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="numdoc" class="form-control form-control-sm" style="width: 75px;" placeholder="N° facture" value="{{ request('numdoc') }}">
                                <select name="vendeur" class="form-select form-select-sm" style="width: 100px;">
                                    <option value="">Vendeur (Tous)</option>
                                    @foreach($vendeurs as $v)
                                        <option value="{{ $v }}" {{ request('vendeur') == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <div class="col-md-1 col-sm-6">
                                    <label class="form-label small fw-bold">Véhicule</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fas fa-car"></i></span>
                                        <input type="text" name="search_vehicle" class="form-control" placeholder="Immat/Marque..." value="{{ request('search_vehicle') }}">
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6">
                                    <label class="form-label small fw-bold">Article</label>
                                    <input type="text" name="search_article" class="form-control form-control-sm" placeholder="Réf ou Description" value="{{ request('search_article') }}">
                                </div>
                                <select name="status" class="form-select form-select-sm" style="width: 75px;">
                                    <option value="">Statut (Tous)</option>
                                    <option value="brouillon" {{ request('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="validée" {{ request('status') == 'validée' ? 'selected' : '' }}>Validée</option>
                                </select>
                                <select name="paid" class="form-select form-select-sm" style="width: 75px;">
                                    <option value="">Payé (Tous)</option>
                                    <option value="1" {{ request('paid') == '1' ? 'selected' : '' }}>Payé</option>
                                    <option value="0" {{ request('paid') == '0' ? 'selected' : '' }}>Non payé</option>
                                </select>
                                <input type="date" name="date_from" class="form-control form-control-sm" style="width: 90px;" value="{{ request('date_from') }}">
                                <span class="mx-0">à</span>
                                <input type="date" name="date_to" class="form-control form-control-sm" style="width: 90px;" value="{{ request('date_to') }}">
                                <button id="btnFilter" type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fas fa-filter me-1"></i> Filtrer (F8)
                                </button>
                                <a id="btnReset" href="{{ route('salesinvoices.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                    <i class="fas fa-undo me-1"></i> Réinitialiser (F9)
                                </a>
                                <button type="submit" name="action" value="export" formaction="{{ route('salesinvoices.export') }}" class="btn btn-outline-success btn-sm px-3">
                                    <i class="fas fa-file-excel me-1"></i> EXCEL
                                </button>
                                <a href="{{ route('salesinvoices.export.comptable', request()->query()) }}" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fas fa-file-invoice me-1"></i> EXPORTATION COMPTABLE
                                </a>
                            </form>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>

                        @foreach ($invoices as $invoice)
                            <div class="card mb-4 shadow-sm border-0">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                    <div>
                                        <h6 class="mb-0">
                                            <strong>Facture N° : {{ $invoice->numdoc }}
                                                @forelse ($invoice->creditNotes as $credit)
                                                    <a href="/salesnotes/list" style="color:red; font-weight: bold;"> ⤺ Avoir : {{ $credit->numdoc }}</a>
                                                @empty
                                                @endforelse
                                            </strong> –
                                            &#x1F482;{{ $invoice->customer->name ?? 'N/A' }}
                                            <span class="text-muted small">({{ $invoice->numclient ?? 'N/A' }})</span>
                                            <span class="text-muted small">- 📆 <b>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</b></span>
                                            <a href="{{ config('services.tournee.url', 'http://127.0.0.1:8001') }}/suivi/{{ $invoice->numdoc }}"
                                               target="_blank"
                                               class="btn btn-sm"
                                               title="Suivi tournée de cette facture"
                                               style="background:#dcfce7;border:1px solid #86efac;color:#166534;font-weight:600;border-radius:6px;">
                                                <i class="fas fa-truck-loading me-1"></i> Suivi
                                                <i class="fas fa-external-link-alt ms-1" style="font-size:0.65rem;opacity:0.7;"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#smartViewModal{{ $invoice->id }}">
                                                <i class="fas fa-brain me-1"></i> Smart View
                                            </button>
                                            <span class="badge badge-secondary ml-1" style="font-size: 0.75em;">
                                                &#128338; Créée le {{ $invoice->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </h6>
                                        @if($invoice->status === 'brouillon')
                                            <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                                        @else
                                            <span class="badge bg-success">{{ ucfirst($invoice->status) }}</span>
                                        @endif
                                        <span class="badge rounded-pill text-bg-secondary">Total TTC : {{ number_format($invoice->total_ttc, 2, ',', ' ') }} €</span>
                                        @if($invoice->status != 'brouillon')
                                            @if($invoice->paid)
                                                <span class="badge bg-success">Payé</span>
                                            @else
                                                <span class="badge bg-danger">Non payé ({{ number_format($invoice->getRemainingBalanceAttribute(), 2, ',', ' ') }} €)</span>
                                            @endif
                                        @endif
                                        <span class="text-muted small">&#8594; type: {{ ucfirst($invoice->type ?? 'N/A') }}</span>
                                        @if($invoice->type === 'direct' && $invoice->deliveryNotes()->exists())
                                            @php $firstDeliveryNote = $invoice->deliveryNotes->first(); @endphp
                                            @if($firstDeliveryNote)
                                                <span class="badge rounded-pill text-bg-light"><i class="fas fa-user-tie"></i> Vendeur : {{ $firstDeliveryNote->vendeur }}</span>
                                            @endif
                                        @endif
                                        @if($invoice->due_date != $invoice->invoice_date)
                                            <span class="badge rounded-pill text-bg-light">
                                                <i class="far fa-calendar-times"></i> Echéance : {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $invoice->id }})">
                                            ➕ Détails
                                        </button>
                                        <a href="{{ route('salesinvoices.export_single', $invoice->id) }}" class="btn btn-xs btn-outline-success">
                                            EXCEL <i class="fas fa-file-excel"></i>
                                        </a>
                                        <a href="{{ route('salesinvoices.print', $invoice->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                            PDF <i class="fas fa-print"></i>
                                        </a>

                                        @if($invoice->status === 'validée' && !$invoice->paid)
                                            <a href="#" data-toggle="modal" data-target="#makePaymentModal{{ $invoice->id }}" class="btn btn-xs btn-outline-danger">
                                                Régler <i class="fas fa-credit-card"></i>
                                            </a>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if($invoice->status === 'brouillon')
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.edit', $invoice->id) }}"><i class="fas fa-edit"></i> Modifier</a>
                                                @endif
                                                @if($invoice->status === 'validée' && !$invoice->paid)
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#makePaymentModal{{ $invoice->id }}"><i class="fas fa-credit-card"></i> Faire un règlement</a>
                                                @endif
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#sendEmailModal{{ $invoice->id }}"><i class="fas fa-envelope"></i> Envoyer par mail</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#orderReadyModal-{{ $invoice->id }}"><i class="fas fa-bell"></i> Notification Retrait</a>
                                                @if($invoice->type === 'direct' && $invoice->deliveryNotes()->exists())
                                                    @foreach($invoice->deliveryNotes as $deliveryNote)
                                                        <a class="dropdown-item" href="{{ route('delivery_notes.edit', $deliveryNote->id) }}"><i class="fas fa-eye"></i> Bon de livraison #{{ $deliveryNote->numdoc }}</a>
                                                    @endforeach
                                                @endif
                                                @if($invoice->status === 'validée')
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printduplicata', $invoice->id) }}" target="_blank"><i class="fas fa-print"></i> imp. DUPLICATA</a>
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printsansref', $invoice->id) }}" target="_blank"><i class="fas fa-print"></i> imp. Sans Réf.</a>
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printsansrem', $invoice->id) }}" target="_blank"><i class="fas fa-print"></i> imp. Sans Rémise</a>
                                                    <a class="dropdown-item" href="{{ route('salesinvoices.printsans2', $invoice->id) }}" target="_blank"><i class="fas fa-print"></i> imp. Sans Réf & Rém</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Smart View Modal --}}
                                <div class="modal fade" id="smartViewModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-gradient-primary text-white">
                                                <h5 class="modal-title"><i class="fas fa-brain me-2"></i> Smart View – Facture {{ $invoice->numdoc }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                @php
                                                    $blLines = collect();
                                                    if ($invoice->deliveryNotes()->exists()) {
                                                        foreach ($invoice->deliveryNotes as $dn) { $blLines = $blLines->merge($dn->lines); }
                                                    } else { $blLines = $invoice->lines; }
                                                    $totalCostNet = 0; $totalSaleNet = 0; $hasPurchaseData = false;
                                                    foreach ($blLines as $line) {
                                                        $qty = $line->delivered_quantity ?? $line->quantity ?? 1;
                                                        $saleNet = $qty * ($line->unit_price_ht ?? 0) * (1 - ($line->remise ?? 0) / 100);
                                                        $totalSaleNet += $saleNet;
                                                        if (isset($line->unit_coast) && $line->unit_coast > 0) {
                                                            $costNet = $qty * $line->unit_coast * (1 - ($line->discount_coast ?? 0) / 100);
                                                            $totalCostNet += $costNet; $hasPurchaseData = true;
                                                        }
                                                    }
                                                    $netMargin = $totalSaleNet - $totalCostNet;
                                                    $marginRate = $totalCostNet > 0 ? round(($netMargin / $totalCostNet) * 100, 1) : ($hasPurchaseData ? 0 : null);
                                                    $colorClass = $marginRate >= 40 ? 'text-success' : ($marginRate >= 20 ? 'text-warning' : 'text-danger');
                                                @endphp
                                                <div class="row text-center mb-4">
                                                    <div class="col-md-3"><div class="card border-0 shadow-sm bg-light h-100"><div class="card-body py-3"><i class="fas fa-receipt fa-2x text-primary mb-2"></i><h6>CA TTC</h6><h4 class="text-primary fw-bold">{{ number_format($invoice->total_ttc, 2, ',', ' ') }} €</h4></div></div></div>
                                                    <div class="col-md-3"><div class="card border-0 shadow-sm bg-light h-100"><div class="card-body py-3"><i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i><h6>Coût d'achat net</h6><h4 class="text-warning fw-bold">@if($hasPurchaseData){{ number_format($totalCostNet, 2, ',', ' ') }} €@else<span class="text-muted fs-6">Non renseigné</span>@endif</h4></div></div></div>
                                                    <div class="col-md-3"><div class="card border-0 shadow-sm bg-light h-100"><div class="card-body py-3"><i class="fas fa-chart-line fa-2x text-success mb-2"></i><h6>Marge nette</h6><h4 class="text-success fw-bold">@if($hasPurchaseData){{ number_format($netMargin, 2, ',', ' ') }} €@else<span class="text-muted fs-6">Non calculable</span>@endif</h4></div></div></div>
                                                    <div class="col-md-3"><div class="card border-0 shadow-sm bg-light h-100"><div class="card-body py-3"><i class="fas fa-percentage fa-2x text-info mb-2"></i><h6>Taux marge</h6><h4 class="fw-bold {{ $colorClass }}">@if($hasPurchaseData){{ $marginRate }}%@else<span class="text-muted fs-6">—</span>@endif</h4></div></div></div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover align-middle">
                                                        <thead class="table-light text-center">
                                                            <tr><th>Article</th><th>Fournisseur</th><th>Qté</th><th>PU HT</th><th>Achat net unit.</th><th>Marge ligne</th><th>Taux</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($blLines as $line)
                                                                @php
                                                                    $qty = $line->delivered_quantity ?? $line->quantity ?? 1;
                                                                    $salePriceHt = $line->unit_price_ht ?? 0; $remise = $line->remise ?? 0;
                                                                    $saleNetLine = $qty * $salePriceHt * (1 - $remise / 100);
                                                                    $hasCost = isset($line->unit_coast) && $line->unit_coast > 0;
                                                                    $costNetUnit = $hasCost ? $line->unit_coast * (1 - ($line->discount_coast ?? 0) / 100) : null;
                                                                    $costNetLine = $hasCost ? $qty * $costNetUnit : null;
                                                                    $lineMargin = $hasCost ? $saleNetLine - $costNetLine : null;
                                                                    $lineRate = $hasCost && $costNetLine > 0 ? round(($lineMargin / $costNetLine) * 100, 1) : null;
                                                                    $rateColor = $lineRate >= 40 ? 'text-success' : ($lineRate >= 20 ? 'text-warning' : 'text-danger');
                                                                    $supplierName = $line->supplier->name ?? ($hasCost ? 'Non précisé' : '—');
                                                                @endphp
                                                                <tr>
                                                                    <td><small><strong>{{ $line->article_code ?? 'Divers' }}</strong><br>{{ $line->item->name ?? ($line->description ?? 'Article divers') }}</small></td>
                                                                    <td class="text-center"><span class="badge bg-secondary">{{ $supplierName }}</span></td>
                                                                    <td class="text-center">{{ $qty }}</td>
                                                                    <td class="text-end"><small>{{ number_format($salePriceHt, 2, ',', ' ') }} €</small></td>
                                                                    <td class="text-end">@if($hasCost)<small>{{ number_format($costNetUnit, 2, ',', ' ') }} €</small>@else<small class="text-muted">—</small>@endif</td>
                                                                    <td class="text-end fw-bold {{ $lineMargin !== null && $lineMargin >= 0 ? 'text-success' : 'text-danger' }}">@if($hasCost){{ number_format($lineMargin, 2, ',', ' ') }} €@else—@endif</td>
                                                                    <td class="text-center fw-bold">@if($hasCost)<span class="{{ $rateColor }}">{{ $lineRate }}%</span>@else—@endif</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ════ DÉTAILS LIGNES ════ --}}
                                <div id="lines-{{ $invoice->id }}" class="card-body d-none bg-light">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fa fa-solid fa-car"></i> :
                                        {{ $invoice->vehicle ? ($invoice->vehicle->license_plate . ' (' . $invoice->vehicle->brand_name . ' ' . $invoice->vehicle->model_name . ')') : '-' }}
                                        @if($invoice->notes)<p>Note : {{ $invoice->notes }}</p>@endif
                                    </h6>
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Code Article</th>
                                                <th>Désignation</th>
                                                <th>Qté</th>
                                                <th>PU HT</th>
                                                <th>Remise (%)</th>
                                                <th>TVA (%)</th>
                                                <th>Total Ligne</th>
                                                {{-- ══ NOUVEAU : colonne tournée ══ --}}
                                                <th style="width:40px;" title="Ajouter à la tournée">🚗</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice->lines as $line)
                                                <tr>
                                                    <td>{{ $line->article_code ?? '-' }}</td>
                                                    <td>{{ $line->item->name ?? $line->description ?? '-' }}</td>
                                                    <td class="text-center">{{ number_format($line->quantity, 0, ',', ' ') }}</td>
                                                    <td class="text-end">{{ number_format($line->unit_price_ht, 2, ',', ' ') }} €</td>
                                                    <td class="text-end">{{ number_format($line->remise ?? 0, 2, ',', ' ') }}%</td>
                                                    <td class="text-end">{{ number_format($invoice->tva_rate ?? 0, 2, ',', ' ') }}%</td>
                                                    <td class="text-end">{{ number_format($line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                                    {{-- ══ NOUVEAU : bouton tournée ══ --}}
                                                    <td class="text-center p-1">
    <button type="button"
            class="btn btn-tournee btn-sm btn-outline-primary"
            style="min-width: 38px; font-size: 0.95rem;"
            data-invoice-id="{{ $invoice->id }}"
            data-invoice-numdoc="{{ $invoice->numdoc }}"
            data-line-id="{{ $line->id }}"
            data-article-code="{{ $line->article_code ?? '-' }}"
            data-article-name="{{ $line->item->name ?? $line->description ?? $line->article_code }}"
            data-quantity="{{ $line->quantity }}"
            data-supplier-id="{{ $line->supplier_id ?? '' }}"
            title="Ajouter à la tournée">
        <i class="fas fa-truck"></i>
    </button>
    <span id="tournee-statut-{{ $line->id }}" class="d-block mt-1" style="font-size:0.65rem;"></span>
</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-end mt-3">
                                        @if($invoice->payments->count() > 0)
                                            @foreach($invoice->payments as $payment)
                                                <span class="badge text-bg-info">{{ $payment->payment_mode }} : {{ number_format(abs($payment->amount), 2, ',', ' ') }} €</span>
                                            @endforeach
                                        @else
                                            <span class="badge text-bg-danger">Aucun encaissement lié à cette facture</span>
                                        @endif
                                        <div class="p-3 bg-white border rounded d-inline-block">
                                            <strong>Total HT :</strong> {{ number_format($invoice->total_ht, 2, ',', ' ') }} €<br>
                                            <strong>Total TTC :</strong> {{ number_format($invoice->total_ttc, 2, ',', ' ') }} €
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Make Payment Modal --}}
                            <div class="modal fade" id="makePaymentModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Faire un règlement pour {{ $invoice->numdoc }}</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('salesinvoices.make_payment', $invoice->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="print_after" id="print_after{{ $invoice->id }}" value="0">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Montant (€)</label>
                                                    <input type="number" step="0.01" class="form-control" id="amount{{ $invoice->id }}" name="amount" max="{{ $invoice->getRemainingBalanceAttribute() }}" value="{{ abs($invoice->getRemainingBalanceAttribute()) }}" required>
                                                    <small>Reste à payer : {{ number_format($invoice->getRemainingBalanceAttribute(), 2, ',', ' ') }} €</small>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('amount{{ $invoice->id }}').value = '{{ abs($invoice->getRemainingBalanceAttribute()) }}'">Lettrer</button>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Date de paiement</label>
                                                    <input type="date" class="form-control" name="payment_date" value="{{ now()->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Mode de paiement</label>
                                                    <select class="form-control" name="payment_mode" required>
                                                        <option value="">Sélectionner le mode de paiement</option>
                                                        @foreach(\App\Models\PaymentMode::where('type', 'encaissement')->get() as $mode)
                                                            <option value="{{ $mode->name }}">{{ $mode->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Référence (optionnel)</label>
                                                    <input type="text" class="form-control" name="reference">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Notes (optionnel)</label>
                                                    <textarea class="form-control" name="notes"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-success" onclick="document.getElementById('print_after{{ $invoice->id }}').value = 1;">Enregistrer et Imprimer</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Send Email --}}
                            <div class="modal fade" id="sendEmailModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('salesinvoices.sendEmail', $invoice->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">📧 Envoyer la facture {{ $invoice->numdoc }}</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group mb-2">
                                                    <label>Email client</label>
                                                    <input type="email" name="emails[]" class="form-control" value="{{ $invoice->customer->email ?? '' }}" required>
                                                </div>
                                                <div id="extraEmails{{ $invoice->id }}"></div>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addEmailField({{ $invoice->id }})">+ Ajouter un autre destinataire</button>
                                                <div class="form-group mt-3">
                                                    <label>Message</label>
                                                    <textarea name="message" class="form-control" rows="4">{{ \App\Models\EmailMessage::first()->messagefacturevente ?? 'Veuillez trouver ci-joint votre facture.' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Envoyer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Notification Retrait --}}
                            <div class="modal fade" id="orderReadyModal-{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('salesinvoices.sendOrderReadyEmail', $invoice->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Notifier le client</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Email du client</label>
                                                    <input type="email" class="form-control" name="email" value="{{ $invoice->customer->email ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Envoyer la notification</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.</div>
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
        // ── CSRF pour toutes les requêtes jQuery/fetch ──
        var _csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (_csrfToken) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': _csrfToken.getAttribute('content') } });
        }

        $(document).ready(function () {
            $('.select2').select2({ width: '17%' });
        });

        function toggleLines(id) {
            var section = document.getElementById('lines-' + id);
            section.classList.toggle('d-none');
            // Charger les statuts tournée si on ouvre
            if (!section.classList.contains('d-none')) {
                loadTourneeStatuts(id, 'facture_vente');
            }
        }

        function loadTourneeStatuts(sourceId, sourceType) {
            fetch('/tournee/lines/' + sourceId + '?source_type=' + sourceType)
                .then(function(r) { return r.json(); })
                .then(function(lines) {
                    var colorMap = {
                        'en_attente': 'secondary', 'assigné': 'primary',
                        'en_route': 'info', 'recupere': 'success',
                        'au_magasin': 'dark', 'probleme': 'danger'
                    };
                    lines.forEach(function(l) {
                        var span = document.getElementById('tournee-statut-' + l.source_line_id);
                        var btn  = span ? span.previousElementSibling : null;
                        if (span) {
                            span.innerHTML = '<span class="badge badge-' + (colorMap[l.statut] || 'secondary') + '">'
                                + l.statut_label + '</span>';
                        }
                        if (btn && btn.classList.contains('btn-tournee')) {
                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-warning');
                            btn.title = 'Déjà en tournée — ' + l.statut_label + (l.chauffeur ? ' (' + l.chauffeur + ')' : '');
                        }
                    });
                })
                .catch(function() {}); // silencieux si tournée indisponible
        }

        function addEmailField(id) {
            var container = document.getElementById('extraEmails' + id);
            var input = document.createElement('input');
            input.type = 'email';
            input.name = 'emails[]';
            input.placeholder = 'Autre email';
            input.classList.add('form-control', 'mt-2');
            container.appendChild(input);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'F8') { e.preventDefault(); document.getElementById('btnFilter').click(); }
            if (e.key === 'F9') { e.preventDefault(); document.getElementById('btnReset').click(); }
        });
    </script>

    @if(session('print_url'))
    <script>window.open("{{ session('print_url') }}", "_blank");</script>
    @endif

    {{-- ════════════════════════════════════════════════════════════
         MODAL TOURNÉE + JS
         ════════════════════════════════════════════════════════════ --}}

    {{-- ═══ MODAL TOURNÉE ════════════════════════════════════════ --}}
    <div class="modal fade" id="tourneeModal" tabindex="-1" aria-labelledby="tourneeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header text-white" style="background:linear-gradient(135deg,#1a2b4a,#2d4a8a);">
                <h5 class="modal-title mb-0" id="tourneeModalLabel">
                    <i class="fas fa-route me-2"></i> Ajouter à la tournée
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                    <div class="alert alert-light border mb-3 py-2 px-3">
                        <div>
                            <strong id="t-article-code"
                                    style="font-family:monospace; font-size:1rem; color:#0040c0;
                                           background:#eef4ff; padding:2px 8px; border-radius:3px;
                                           border-left:3px solid #0040c0;"></strong>
                        </div>
                        <div id="t-article-name" class="text-muted" style="font-size:0.82rem; margin-top:3px;"></div>
                        <div style="font-size:0.78rem; margin-top:3px;">
                            Facture : <strong id="t-numdoc"></strong>
                            &nbsp;|&nbsp; Qté : <strong id="t-qty"></strong>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12">
    <label class="form-label mb-1" style="font-size:0.82rem; font-weight:700;">
        <i class="fas fa-industry me-1 text-warning"></i>
        Fournisseur <span class="text-danger">*</span>
    </label>
    <select id="t-supplier" class="form-select form-select-sm select2" required style="width: 100%;">
        <option value="">-- Choisir le fournisseur --</option>
        @foreach(\App\Models\Supplier::where('has_b2b', true)->orderBy('name')->get() as $supplier)
            <option value="{{ $supplier->id }}">
                {{ $supplier->name }}
                @if($supplier->city) — {{ $supplier->city }} @endif
            </option>
        @endforeach
    </select>
</div>
                        <div class="col-md-6">
                            <label class="form-label mb-1" style="font-size:0.82rem; font-weight:700;">
                                <i class="fas fa-user me-1 text-info"></i>
                                Chauffeur <small class="text-muted">(optionnel)</small>
                            </label>
                            <select id="t-chauffeur" class="form-select form-select-sm">
                                <option value="" selected>⏳ À affecter (dispatcher assignera)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1" style="font-size:0.82rem; font-weight:700;">
                                <i class="fas fa-calendar me-1 text-danger"></i>
                                Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="t-date" class="form-control form-control-sm"
       value="{{ today()->format('Y-m-d') }}" required>

                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1" style="font-size:0.82rem; font-weight:700;">
                                <i class="fas fa-clock me-1 text-success"></i>
                                Créneau <span class="text-danger">*</span>
                            </label>
                            
                            {{-- APRÈS --}}
<div id="slot-loading" class="text-muted" style="font-size:0.8rem;">
    <i class="fas fa-spinner fa-spin me-1"></i> Chargement des créneaux...
</div>
<div id="slot-closed" class="alert alert-danger py-2 px-3" style="display:none;font-size:0.82rem;">
    <i class="fas fa-ban me-1"></i>
    <strong id="slot-closed-msg"></strong>
</div>
<div id="slot-options" class="d-flex flex-wrap gap-2 mt-1" style="display:none;"></div>
<div id="slot-warning" class="alert alert-warning py-1 px-2 mt-2" style="display:none;font-size:0.75rem;">
    <i class="fas fa-exclamation-triangle me-1"></i>
    <strong>Créneau modifié</strong> — vous prenez la responsabilité de ce changement.
</div>

                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1" style="font-size:0.82rem; font-weight:700;">Quantité</label>
                            <input type="number" id="t-quantity" class="form-control form-control-sm" min="1" value="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1" style="font-size:0.82rem; font-weight:700;">Note (optionnel)</label>
                            <textarea id="t-notes" class="form-control form-control-sm" rows="2"
                                      placeholder="Ex: demander au comptoir, pièce urgente..."></textarea>
                        </div>
                    </div>

                    <div id="t-existing-lines" class="mt-3" style="display:none;">
                        <hr class="my-2">
                        <small class="fw-bold text-muted" style="font-size:0.75rem;">
                            <i class="fas fa-list me-1"></i>Déjà en tournée pour cette facture :
                        </small>
                        <div id="t-existing-content" class="mt-1"></div>
                    </div>

                    <div id="t-error" class="alert alert-danger mt-2 py-2" style="display:none; font-size:0.82rem;"></div>
                </div>
                <div class="modal-footer py-2">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
    <button type="button" id="t-submit" class="btn btn-warning btn-sm px-4">
        <i class="fas fa-route me-1"></i> Ajouter à la tournée
    </button>
</div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var currentLine = {};

        // Charger les chauffeurs au démarrage
        function loadChauffeurs() {
            fetch('{{ route("tournee.chauffeurs") }}')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var sel = document.getElementById('t-chauffeur');
                    sel.innerHTML = '<option value="">⏳ À affecter (dispatcher assignera)</option>';
                    data.forEach(function (c) {
                        sel.innerHTML += '<option value="' + c.id + '">'
                            + c.name + (c.phone ? ' — ' + c.phone : '') + '</option>';
                    });
                })
                .catch(function () {
                    document.getElementById('t-chauffeur').innerHTML =
                        '<option value="">Serveur tournée indisponible</option>';
                });
        }
        loadChauffeurs();


        // ── Créneaux dynamiques depuis l'API ─────────────────────
var autoSlot = null;

function loadCreneaux(date) {
    var url     = '/tournee/parametres' + (date ? '?date=' + date : '');
    var loading = document.getElementById('slot-loading');
    var closed  = document.getElementById('slot-closed');
    var options = document.getElementById('slot-options');
    var submit  = document.getElementById('t-submit');

    loading.style.display = 'block';
    closed.style.display  = 'none';
    options.style.display = 'none';
    submit.disabled       = false;

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            loading.style.display = 'none';

            // Si plus de créneaux aujourd'hui → basculer sur demain automatiquement
            // Si plus de créneaux aujourd'hui → basculer sur demain et recharger
            if (data.pour_demain) {
                document.getElementById('t-date').value = data.date;
                if (data.message) {
                    var info = document.createElement('div');
                    info.className = 'alert alert-info py-1 px-2 mb-2';
                    info.style.fontSize = '0.78rem';
                    info.innerHTML = '<i class="fas fa-info-circle me-1"></i>' + data.message;
                    document.getElementById('slot-loading').insertAdjacentElement('afterend', info);
                    setTimeout(function() { if(info.parentNode) info.parentNode.removeChild(info); }, 5000);
                }
                loadCreneaux(data.date);
                return;
            }

            if (!data.is_open) {
                closed.style.display = 'block';
                document.getElementById('slot-closed-msg').textContent = data.message || 'Fermé ce jour';
                submit.disabled = true;
                return;
            }

            var creneaux = (data.creneaux_dispo && data.creneaux_dispo.length)
                ? data.creneaux_dispo : data.creneaux;

            if (!creneaux || !creneaux.length) {
                closed.style.display = 'block';
                document.getElementById('slot-closed-msg').textContent = 'Plus de créneau disponible aujourd\'hui';
                submit.disabled = true;
                return;
            }

            autoSlot = data.creneau_suggere;
            options.innerHTML = '';
            var icons = {'9h-11h':'🌅','11h-12h':'🕚','13h-14h':'🌞','15h-16h':'🕒','17h-18h':'🌇'};

            creneaux.forEach(function(c, i) {
                var isDefault = (c.label === autoSlot);
                var div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML =
                    '<input class="form-check-input" type="radio" name="t-slot" id="t-slot-' + i + '" value="' + c.label + '"' + (isDefault ? ' checked' : '') + '>' +
                    '<label class="form-check-label" for="t-slot-' + i + '" style="font-size:0.82rem;">' +
                    (icons[c.label] || '🕐') + ' ' + c.label +
                    (isDefault ? ' <span class="badge bg-success ms-1" style="font-size:0.6rem;">Suggéré</span>' : '') +
                    '</label>';
                options.appendChild(div);
            });

            options.style.display = 'flex';
        })
        .catch(function() {
            loading.style.display = 'none';
            options.style.display = 'flex';
            var defaults = [
                {label:'9h-11h'},{label:'11h-12h'},{label:'13h-14h'},
                {label:'15h-16h'},{label:'17h-18h'}
            ];
            var icons = {'9h-11h':'🌅','11h-12h':'🕚','13h-14h':'🌞','15h-16h':'🕒','17h-18h':'🌇'};
            defaults.forEach(function(c, i) {
                var div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML =
                    '<input class="form-check-input" type="radio" name="t-slot" id="t-slot-' + i + '" value="' + c.label + '"' + (i===0?' checked':'') + '>' +
                    '<label class="form-check-label" for="t-slot-' + i + '" style="font-size:0.82rem;">' + icons[c.label] + ' ' + c.label + '</label>';
                options.appendChild(div);
            });
        });
}

// Détecter si le vendeur change le créneau suggéré
document.addEventListener('change', function(e) {
    if (e.target && e.target.name === 't-slot') {
        var warning = document.getElementById('slot-warning');
        warning.style.display = (e.target.value !== autoSlot) ? 'block' : 'none';
    }
});



        // ====================== INITIALISATION SELECT2 ======================
        function initSupplierSelect2() {
            // Détruire l'instance précédente si elle existe
            if ($('#t-supplier').hasClass('select2-hidden-accessible')) {
                $('#t-supplier').select2('destroy');
            }

            $('#t-supplier').select2({
                width: '100%',
                placeholder: "Rechercher un fournisseur...",
                allowClear: true,
                dropdownParent: $('#tourneeModal'), // Très important pour le modal
                language: {
                    noResults: function() { 
                        return "Aucun fournisseur trouvé"; 
                    }
                }
            });
        }
        // ===================================================================

        // Ouvrir le modal depuis un bouton de ligne
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-tournee');
            if (!btn) return;

            currentLine = {
                invoiceId:   btn.getAttribute('data-invoice-id'),
                numdoc:      btn.getAttribute('data-invoice-numdoc'),
                lineId:      btn.getAttribute('data-line-id'),
                articleCode: btn.getAttribute('data-article-code'),
                articleName: btn.getAttribute('data-article-name'),
                quantity:    btn.getAttribute('data-quantity'),
                supplierId:  btn.getAttribute('data-supplier-id') || '',
            };

            // Mise à jour des champs
            document.getElementById('t-article-code').textContent = currentLine.articleCode;
            document.getElementById('t-article-name').textContent = currentLine.articleName;
            document.getElementById('t-numdoc').textContent       = currentLine.numdoc;
            document.getElementById('t-qty').textContent          = currentLine.quantity;
            document.getElementById('t-quantity').value           = currentLine.quantity;
            document.getElementById('t-error').style.display      = 'none';
            document.getElementById('t-notes').value              = '';

            if (currentLine.supplierId) {
                document.getElementById('t-supplier').value = currentLine.supplierId;
            } else {
                document.getElementById('t-supplier').value = '';
            }

            loadExistingLines(currentLine.invoiceId);

            // Ouvrir le modal
            // Charger les créneaux puis ouvrir le modal
loadCreneaux(document.getElementById('t-date').value);
document.getElementById('t-date').addEventListener('change', function() {
    loadCreneaux(this.value);
});

// Ouvrir le modal
var modal = new bootstrap.Modal(document.getElementById('tourneeModal'));
modal.show();

            // Initialiser Select2 après l'ouverture du modal
            setTimeout(() => {
                initSupplierSelect2();

                // Restaurer la valeur sélectionnée
                if (currentLine.supplierId) {
                    $('#t-supplier').val(currentLine.supplierId).trigger('change');
                }
            }, 350);
        });

        // Charger les lignes existantes
        function loadExistingLines(invoiceId) {
            var container = document.getElementById('t-existing-lines');
            var content   = document.getElementById('t-existing-content');
            content.innerHTML = '<small class="text-muted">Chargement...</small>';
            container.style.display = 'block';

            fetch('/tournee/lines/' + invoiceId)
                .then(function (r) { return r.json(); })
                .then(function (lines) {
                    if (!lines.length) { 
                        container.style.display = 'none'; 
                        return; 
                    }
                    var html = '';
                    lines.forEach(function (l) {
                        html += '<div class="d-flex justify-content-between align-items-center py-1 border-bottom">'
                            + '<span style="font-size:0.75rem;">'
                            + '<strong style="font-family:monospace;">' + l.article_code + '</strong>'
                            + ' — ' + l.slot_label + ' ' + l.date_tournee
                            + ' <span class="badge bg-' + l.statut_color + ' ms-1">' + l.statut_label + '</span>'
                            + (l.chauffeur ? ' 👤 ' + l.chauffeur : '')
                            + '</span>'
                            + '<button class="btn btn-xs btn-outline-danger btn-remove-tournee ms-2"'
                            + ' data-line-id="' + l.id + '"'
                            + ' style="font-size:0.65rem; padding:1px 6px;">✕</button>'
                            + '</div>';
                    });
                    content.innerHTML = html;
                })
                .catch(function () { container.style.display = 'none'; });
        }

        // Supprimer une ligne existante
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-tournee');
            if (!btn) return;
            if (!confirm('Retirer cette ligne de la tournée ?')) return;

            var lineId = btn.getAttribute('data-line-id');
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            fetch('/tournee/lines/' + lineId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf }
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.success) loadExistingLines(currentLine.invoiceId);
            });
        });

        // Soumettre
        document.getElementById('t-submit').addEventListener('click', function () {
            var supplierId  = document.getElementById('t-supplier').value;
            var chauffeurId = document.getElementById('t-chauffeur').value;
            var date        = document.getElementById('t-date').value;
            var slotEl      = document.querySelector('input[name="t-slot"]:checked');
            var slot        = slotEl ? slotEl.value : 'matin';
            var errorDiv    = document.getElementById('t-error');

            errorDiv.style.display = 'none';

            if (!supplierId)  { 
                errorDiv.textContent = 'Veuillez choisir un fournisseur.';  
                errorDiv.style.display = 'block'; 
                return; 
            }
            // chauffeur optionnel — le dispatcher peut l'assigner depuis le planning
            if (!date)        { 
                errorDiv.textContent = 'Veuillez choisir une date.';        
                errorDiv.style.display = 'block'; 
                return; 
            }

            var btn  = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Envoi...';

            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            fetch('{{ route("tournee.store") }}', {
                method: 'POST',
                    credentials: 'same-origin',   // ← ajouter cette ligne
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    invoice_id:      currentLine.invoiceId,
                    invoice_line_id: currentLine.lineId,
                    article_code:    currentLine.articleCode,
                    article_name:    currentLine.articleName,
                    quantity:        document.getElementById('t-quantity').value,
                    supplier_id:     supplierId,
                    chauffeur_id:    (chauffeurId && chauffeurId !== '') ? chauffeurId : null,
                    date_tournee:    date,
                    slot:            slot,
                    notes:           document.getElementById('t-notes').value,
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.success) {
                    var statut = document.getElementById('tournee-statut-' + currentLine.lineId);
                    if (statut) {
                        statut.innerHTML = '<span class="badge bg-warning text-dark" style="font-size:0.6rem;">🚗</span>';
                    }
                    $('#tourneeModal').modal('hide');
                    showToast(d.message, 'success');
                    loadExistingLines(currentLine.invoiceId);
                } else {
                    errorDiv.textContent = d.error || "Erreur lors de l'ajout.";
                    errorDiv.style.display = 'block';
                }
            })
            .catch(function () {
                errorDiv.textContent = 'Erreur réseau. Vérifiez la connexion au serveur tournée.';
                errorDiv.style.display = 'block';
            })
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-route me-1"></i> Ajouter à la tournée';
            });
        });

        // Toast
        function showToast(message, type) {
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;min-width:280px;';
            var icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            toast.innerHTML = '<div class="alert alert-' + type + ' shadow-lg d-flex align-items-center mb-0"'
                + ' style="border-radius:8px;"><i class="fas fa-' + icon + ' me-2"></i>' + message + '</div>';
            document.body.appendChild(toast);
            setTimeout(function () { toast.remove(); }, 4000);
        }

    })();
</script>

</body>
</html>