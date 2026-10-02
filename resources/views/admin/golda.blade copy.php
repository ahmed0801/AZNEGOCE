<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Import Tarifs GOLDA</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; }
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <style>
        .golda-header {
            background: linear-gradient(135deg, #1E2D4A, #2D4A8A);
            border-radius: 16px; padding: 24px 28px; color: white; margin-bottom: 24px;
        }
        .golda-header h3 { font-weight: 800; margin: 0; font-size: 1.3rem; }
        .golda-header small { opacity: .65; font-size: .8rem; }

        .marque-card {
            border-radius: 14px; border: 1.5px solid #E2E8F0 !important;
            background: white; transition: box-shadow .15s, border-color .15s;
        }
        .marque-card:hover { box-shadow: 0 4px 20px rgba(30,45,74,.1); border-color: #93C5FD !important; }
        .marque-card.inactive { opacity: .55; background: #F8FAFF; }
        .mc-header {
            background: #F8FAFF; border-bottom: 1.5px solid #E2E8F0;
            border-radius: 13px 13px 0 0; padding: 11px 15px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-switch .slider { position: absolute; inset: 0; background: #CBD5E1; border-radius: 22px; transition: .2s; cursor: pointer; }
        .toggle-switch input:checked + .slider { background: #10B981; }
        .toggle-switch .slider::before { content: ''; position: absolute; width: 16px; height: 16px; left: 3px; top: 3px; background: white; border-radius: 50%; transition: .2s; }
        .toggle-switch input:checked + .slider::before { transform: translateX(18px); }

        .rule-pill {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .67rem; font-weight: 700; padding: 2px 8px;
            border-radius: 8px; cursor: pointer; user-select: none; transition: all .15s;
        }
        .rule-pill.on  { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .rule-pill.off { background: #F1F5F9; color: #94A3B8; border: 1px solid #E2E8F0; }

        .log-box {
            background: #0f1923; color: #7fff8c; border-radius: 10px; padding: 14px;
            font-family: monospace; font-size: .72rem; max-height: 300px;
            overflow-y: auto; line-height: 1.7; white-space: pre-wrap;
        }
        .progress-track { background: #E2E8F0; border-radius: 20px; height: 6px; overflow: hidden; }
        .progress-fill  { height: 100%; background: linear-gradient(90deg,#3B82F6,#10B981); border-radius: 20px; transition: width .4s; }

        .filter-btn {
            border-radius: 20px; padding: 4px 14px; font-size: .78rem; font-weight: 600;
            border: 1.5px solid #E2E8F0; background: white; color: #6B7A99;
            cursor: pointer; transition: all .15s;
        }
        .filter-btn.active { background: #1E2D4A; color: white; border-color: #1E2D4A; }

        .stat-mini { text-align: center; padding: 12px 16px; border-radius: 12px; }
        .stat-mini h4 { font-size: 1.5rem; font-weight: 800; margin: 0; }
        .stat-mini small { font-size: .7rem; color: #6B7A99; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- SIDEBAR --}}
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
                        <div class="collapse" id="ventes"><ul class="nav nav-collapse">
                            <li><a href="/sales/delivery/create"><span class="sub-item">Nouvelle Commande</span></a></li>
                            <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
                            <li><a href="/delivery_notes/list"><span class="sub-item">Bons de Livraison</span></a></li>
                            <li><a href="/delivery_notes/returns/list"><span class="sub-item">Retours Vente</span></a></li>
                            <li><a href="/salesinvoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/salesnotes/list"><span class="sub-item">Avoirs</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#achats" aria-expanded="false"><i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span></a>
                        <div class="collapse" id="achats"><ul class="nav nav-collapse">
                            <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                            <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d'Achat</span></a></li>
                            <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                            <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#compta" aria-expanded="false"><i class="fas fa-balance-scale"></i><p>Comptabilité</p><span class="caret"></span></a>
                        <div class="collapse" id="compta"><ul class="nav nav-collapse">
                            <li><a href="{{ route('generalaccounts.index') }}"><span class="sub-item">Plan Comptable</span></a></li>
                            <li><a href="{{ route('payments.index') }}"><span class="sub-item">Règlements</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#stock" aria-expanded="false"><i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span></a>
                        <div class="collapse" id="stock"><ul class="nav nav-collapse">
                            <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                            <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                            <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#referentiel" aria-expanded="false"><i class="fas fa-users"></i><p>Référentiel</p><span class="caret"></span></a>
                        <div class="collapse" id="referentiel"><ul class="nav nav-collapse">
                            <li><a href="/customers"><span class="sub-item">Clients</span></a></li>
                            <li><a href="/suppliers"><span class="sub-item">Fournisseurs</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#parametres" aria-expanded="false"><i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span></a>
                        <div class="collapse show" id="parametres"><ul class="nav nav-collapse">
                            <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                            <li><a href="{{ route('admin.golda.index') }}"><span class="sub-item active">Import GOLDA</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#outils" aria-expanded="false"><i class="fab fa-skyatlas"></i><p>Outils</p><span class="caret"></span></a>
                        <div class="collapse" id="outils"><ul class="nav nav-collapse">
                            <li><a href="/analytics"><span class="sub-item">Analytics</span></a></li>
                            <li><a href="/tecdoc"><span class="sub-item">TecDoc</span></a></li>
                            <li><a href="/voice"><span class="sub-item">NEGOBOT</span></a></li>
                        </ul></div>
                    </li>
                    <li class="nav-item"><a href="/contact"><i class="fas fa-headset"></i><p>Assistance</p></a></li>
                    <li class="nav-item">
                        <a href="{{ route('logout.admin') }}" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display:none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- END SIDEBAR --}}

    <div class="main-panel">

        {{-- NAVBAR --}}
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
                        <li class="nav-item topbar-icon dropdown hidden-caret">
                            <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false"><i class="fas fa-layer-group"></i></a>
                            <div class="dropdown-menu quick-actions animated fadeIn">
                                <div class="quick-actions-header"><span class="title mb-1">Actions Rapides</span></div>
                                <div class="quick-actions-scroll scrollbar-outer"><div class="quick-actions-items"><div class="row m-0">
                                    <a class="col-6 col-md-4 p-0" href="/articles"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fas fa-sitemap"></i></div><span class="text">Articles</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/customers"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-users"></i></div><span class="text">Clients</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/suppliers"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-user-tag"></i></div><span class="text">Fournisseurs</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/salesinvoices"><div class="quick-actions-item"><div class="avatar-item bg-warning rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Ventes</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/purchases/list"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Commandes Achats</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/invoices"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures Achats</span></div></a>
                                </div></div></div>
                            </div>
                        </li>
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm"><img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" /></div>
                                <span class="profile-username"><span class="fw-bold">{{ Auth::user()->name }}</span></span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li><div class="user-box">
                                        <div class="avatar-lg"><img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" /></div>
                                        <div class="u-text">
                                            <h4>{{ Auth::user()->name }}</h4>
                                            <p class="text-muted">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div></li>
                                    <li><div class="dropdown-divider"></div>
                                        <form action="{{ route('logout.admin') }}" method="POST" style="display:inline;">@csrf
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
        {{-- END NAVBAR --}}

        <div class="container">
            <div class="page-inner">

                {{-- HEADER --}}
                <div class="golda-header">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h3><i class="fas fa-cloud-download-alt me-2"></i>Import Tarifs GOLDA</h3>
                            <small>
                                Gestion des marques, règles et mises à jour de prix depuis le FTP GOLDA
                                @if($lastImport) &nbsp;—&nbsp; Dernier import : <strong>{{ $lastImport }}</strong>@endif
                            </small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button onclick="syncMarques()" class="btn btn-outline-light btn-sm btn-round" id="btn-sync"
                                    title="Charge uniquement la liste des marques (rapide — un seul fichier)">
                                <i class="fas fa-sync-alt me-1"></i> Charger les marques
                            </button>
                            <button onclick="lancerTout(true)" class="btn btn-sm btn-round"
                                    style="background:rgba(255,255,255,.15);color:white;border:1.5px solid rgba(255,255,255,.3);" id="btn-dryrun">
                                <i class="fas fa-eye me-1"></i> Simulation
                            </button>
                            <button onclick="lancerTout(false)" class="btn btn-light btn-sm btn-round px-4 fw-bold"
                                    style="color:#1E2D4A;" id="btn-tout">
                                <i class="fas fa-play me-1"></i> Tout importer
                            </button>
                        </div>
                    </div>
                </div>

                {{-- STATS --}}
                <div class="row mb-4">
                    <div class="col-6 col-md-3 mb-2">
                        <div class="stat-mini" style="background:#EFF6FF;border:1.5px solid #BFDBFE;">
                            <h4 style="color:#1D4ED8;">{{ $marques->count() }}</h4>
                            <small>Marques configurées</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="stat-mini" style="background:#ECFDF5;border:1.5px solid #A7F3D0;">
                            <h4 style="color:#065F46;">{{ $marques->where('active', true)->count() }}</h4>
                            <small>Marques actives</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="stat-mini" style="background:#FFF7ED;border:1.5px solid #FED7AA;">
                            <h4 style="color:#9A3412;">{{ number_format(\App\Models\Item::where('is_active', true)->count()) }}</h4>
                            <small>Articles actifs total</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="stat-mini" style="background:#F5F3FF;border:1.5px solid #DDD6FE;">
                            <h4 style="color:#5B21B6;">{{ $marques->whereNotNull('last_imported_at')->count() }}</h4>
                            <small>Déjà importées</small>
                        </div>
                    </div>
                </div>

                {{-- PROGRESSION --}}
                <div id="progress-block" style="display:none;" class="mb-4">
                    <div class="card shadow-sm border-0" style="border-radius:14px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span style="font-weight:700;color:#1E2D4A;">
                                    <i class="fas fa-spinner fa-spin me-2 text-primary"></i>
                                    <span id="progress-label">Import en cours...</span>
                                </span>
                                <span id="progress-pct" style="font-size:.82rem;color:#6B7A99;"></span>
                            </div>
                            <div class="progress-track mb-3">
                                <div class="progress-fill" id="progress-bar" style="width:0%;"></div>
                            </div>
                            <div class="log-box" id="live-log"></div>
                        </div>
                    </div>
                </div>

                {{-- FILTRES --}}
                @if($marques->count() > 0)
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span style="font-size:.78rem;font-weight:700;color:#6B7A99;">Afficher :</span>
                    <button class="filter-btn active" onclick="filtrer(this,'all')">Toutes ({{ $marques->count() }})</button>
                    <button class="filter-btn" onclick="filtrer(this,'active')">Actives ({{ $marques->where('active', true)->count() }})</button>
                    <button class="filter-btn" onclick="filtrer(this,'inactive')">Inactives ({{ $marques->where('active', false)->count() }})</button>
                    <div class="ms-auto" style="min-width:220px;">
                        <input type="text" id="search-marque" class="form-control form-control-sm"
                               placeholder="🔍 Rechercher une marque..."
                               style="border-radius:20px;border:1.5px solid #E2E8F0;font-size:.78rem;"
                               oninput="rechercherMarque(this.value)">
                    </div>
                </div>
                @endif

                {{-- GRILLE --}}
                @if($marques->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-cloud-download-alt" style="font-size:3.5rem;color:#CBD5E1;"></i>
                    <p class="mt-3 fw-bold text-muted">Aucune marque configurée</p>
                    <p class="text-muted" style="font-size:.82rem;">
                        Cliquez sur "Charger les marques" pour récupérer la liste depuis GOLDA.<br>
                        <strong>Rapide</strong> — seul le fichier index est téléchargé.
                    </p>
                    <button onclick="syncMarques()" class="btn btn-primary btn-round mt-2">
                        <i class="fas fa-sync-alt me-1"></i> Charger les marques
                    </button>
                </div>
                @else
                <div class="row" id="marques-grid">
                    @foreach($marques as $m)
                    @php
                        // Slug utilisé UNIQUEMENT pour les id HTML
                        // Le vrai code_marque est stocké dans data-code et passé au JS
                        $slug = Str::slug($m->code_marque);
                    @endphp
                    <div class="col-xl-4 col-lg-6 col-12 mb-3 marque-item"
                         id="mc-{{ $slug }}"
                         data-slug="{{ $slug }}"
                         data-code="{{ $m->code_marque }}"
                         data-nom="{{ strtolower($m->nom_marque) }}"
                         data-active="{{ $m->active ? '1' : '0' }}">
                        <div class="marque-card {{ $m->active ? '' : 'inactive' }}">

                            <div class="mc-header">
                                <div>
                                    <span style="font-weight:800;color:#1E2D4A;font-size:.95rem;">{{ $m->nom_marque }}</span>
                                    <code style="font-size:.68rem;color:#3B82F6;background:#EFF6FF;padding:1px 6px;border-radius:4px;margin-left:6px;">
                                        {{ $m->prefixe_tarif ?? $m->code_marque }}
                                    </code>
                                </div>
                                <label class="toggle-switch ms-2">
                                    <input type="checkbox" {{ $m->active ? 'checked' : '' }}
                                           onchange="toggleActive('{{ $m->code_marque }}', '{{ $slug }}', this.checked)">
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="card-body" style="padding:14px;">

                                {{-- Brand + Fournisseur --}}
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label style="font-size:.63rem;font-weight:700;color:#6B7A99;text-transform:uppercase;display:block;margin-bottom:4px;">Brand liée</label>
                                        <select class="form-select form-select-sm" style="font-size:.75rem;border-radius:8px;"
                                                onchange="saveSetting('{{ $m->code_marque }}', 'brand_id', this.value)">
                                            <option value="">— Aucune —</option>
                                            @foreach($brands as $b)
                                                <option value="{{ $b->id }}" {{ $m->brand_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label style="font-size:.63rem;font-weight:700;color:#6B7A99;text-transform:uppercase;display:block;margin-bottom:4px;">Fournisseur</label>
                                        @php
                                            $suggested = $suppliers->first(function($s) use ($m) {
                                                return strtolower(trim($s->name)) === strtolower(trim($m->nom_marque))
                                                    || strpos(strtolower($s->name), strtolower($m->nom_marque)) !== false
                                                    || strpos(strtolower($m->nom_marque), strtolower($s->name)) !== false;
                                            });
                                        @endphp
                                        <select class="form-select form-select-sm" style="font-size:.75rem;border-radius:8px;"
                                                onchange="saveSetting('{{ $m->code_marque }}', 'supplier_code', this.value)">
                                            <option value="">— Non lié —</option>
                                            @if($suggested && !$m->supplier_code)
                                                <option value="{{ $suggested->code }}" style="background:#FFFBEB;color:#92400E;font-weight:700;">
                                                    ⭐ {{ $suggested->name }} (suggéré)
                                                </option>
                                            @endif
                                            @foreach($suppliers as $s)
                                                @if(!$suggested || $s->code !== $suggested->code || $m->supplier_code)
                                                <option value="{{ $s->code }}" {{ $m->supplier_code == $s->code ? 'selected' : '' }}>{{ $s->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if($suggested && !$m->supplier_code)
                                        <small style="color:#92400E;font-size:.6rem;display:block;margin-top:2px;">
                                            <i class="fas fa-lightbulb me-1"></i> Fournisseur détecté
                                        </small>
                                        @endif
                                    </div>
                                </div>

                                {{-- Règles --}}
                                <div style="font-size:.63rem;font-weight:700;color:#6B7A99;text-transform:uppercase;margin-bottom:6px;">Règles d'importation</div>
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @php
                                        $rules = [
                                            ['key' => 'update_cost_price',   'label' => 'Prix achat',           'icon' => 'fa-tag'],
                                            ['key' => 'update_sale_price',   'label' => 'Prix vente',           'icon' => 'fa-euro-sign'],
                                            ['key' => 'update_description',  'label' => 'Description',          'icon' => 'fa-font'],
                                            ['key' => 'update_barcode',      'label' => 'Code EAN',             'icon' => 'fa-barcode'],
                                            ['key' => 'update_dimensions',   'label' => 'Dimensions',           'icon' => 'fa-ruler'],
                                            ['key' => 'create_new_items',    'label' => 'Créer nouveaux',       'icon' => 'fa-plus-circle'],
                                            ['key' => 'deactivate_removed',  'label' => 'Désactiver supprimés', 'icon' => 'fa-trash'],
                                        ];
                                    @endphp
                                    @foreach($rules as $rule)
                                    <span class="rule-pill {{ $m->{$rule['key']} ? 'on' : 'off' }}"
                                          id="pill-{{ $slug }}-{{ $rule['key'] }}"
                                          onclick="toggleRule('{{ $m->code_marque }}', '{{ $slug }}', '{{ $rule['key'] }}', this)">
                                        <i class="fas {{ $rule['icon'] }}"></i> {{ $rule['label'] }}
                                    </span>
                                    @endforeach
                                </div>

                                {{-- Marge --}}
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <label style="font-size:.63rem;font-weight:700;color:#6B7A99;white-space:nowrap;">Marge spécifique (%)</label>
                                    <input type="number" min="0" max="200" step="0.5"
                                           class="form-control form-control-sm"
                                           style="width:90px;border-radius:8px;font-size:.78rem;"
                                           placeholder="Auto"
                                           value="{{ $m->margin_override ?? '' }}"
                                           onchange="saveSetting('{{ $m->code_marque }}', 'margin_override', this.value)"
                                           title="Vide = marge par famille">
                                    <small style="color:#9CA3AF;font-size:.63rem;">Vide = par famille</small>
                                </div>

                                {{-- Dernier import --}}
                                <div class="last-stats-box" id="stats-{{ $slug }}"
                                     style="background:#F8FAFF;border-radius:8px;padding:8px 10px;font-size:.72rem;color:#6B7A99;margin-bottom:10px;
                                            {{ $m->last_imported_at ? '' : 'display:none;' }}">
                                    <i class="fas fa-clock me-1"></i>
                                    Dernier import :
                                    <strong id="stats-date-{{ $slug }}">
                                        {{ $m->last_imported_at ? $m->last_imported_at->format('d/m/Y H:i') : '' }}
                                    </strong>
                                    &nbsp;—&nbsp;
                                    <span id="stats-count-{{ $slug }}" style="color:#065F46;font-weight:700;">
                                        {{ number_format($m->last_import_count) }} traités
                                    </span>
                                    @if($m->last_deactivated_count > 0)
                                    &nbsp;·&nbsp;
                                    <span id="stats-deact-{{ $slug }}" style="color:#9A3412;">
                                        {{ $m->last_deactivated_count }} désactivés
                                    </span>
                                    @endif
                                </div>

                                {{-- Boutons --}}
                                <div class="d-flex gap-2">
                                    <button type="button"
                                            class="btn btn-sm btn-round"
                                            style="flex:1;background:#EFF6FF;color:#1D4ED8;border:1.5px solid #BFDBFE;font-weight:700;font-size:.75rem;"
                                            onclick="lancerMarque('{{ $m->code_marque }}', '{{ $slug }}', '{{ addslashes($m->nom_marque) }}', true, this)">
                                        <i class="fas fa-eye me-1"></i> Simuler
                                    </button>
                                    <button type="button"
                                            class="btn btn-primary btn-sm btn-round"
                                            style="flex:2;font-weight:700;font-size:.75rem;"
                                            id="btn-import-{{ $slug }}"
                                            onclick="lancerMarque('{{ $m->code_marque }}', '{{ $slug }}', '{{ addslashes($m->nom_marque) }}', false, this)"
                                            {{ $m->active ? '' : 'disabled' }}>
                                        <i class="fas fa-play me-1"></i> Lancer l'import
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                <div>by <a target="_blank" href="#">AZ NEGOCE</a>.</div>
            </div>
        </footer>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
<script>
var CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ════════════════════════════════════════════════════════════════
// HELPER : slug JS identique à Str::slug() de Laravel
// Utilisé pour retrouver les éléments DOM par leur id
// ════════════════════════════════════════════════════════════════
function slugify(str) {
    return (str || '')
        .toString()
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9\-]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}

// ════════════════════════════════════════════════════════════════
// SYNC MARQUES FTP
// ════════════════════════════════════════════════════════════════
function syncMarques() {
    var btn = document.getElementById('btn-sync');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Chargement...';
    btn.disabled = true;

    fetch('{{ route("admin.golda.sync") }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            showToast('✅ ' + d.created + ' nouvelles marques · ' + d.existing + ' déjà configurées', 'success');
            setTimeout(function() { location.reload(); }, 1200);
        } else {
            showToast('❌ ' + d.message, 'danger');
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Charger les marques';
            btn.disabled = false;
        }
    })
    .catch(function(err) {
        showToast('❌ Erreur réseau : ' + err.message, 'danger');
        btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Charger les marques';
        btn.disabled = false;
    });
}

// ════════════════════════════════════════════════════════════════
// TOGGLE ACTIF / INACTIF
// code  = vrai code_marque (envoyé au serveur)
// slug  = id HTML (utilisé pour getElementById)
// ════════════════════════════════════════════════════════════════
function toggleActive(code, slug, val) {
    fetch('{{ route("admin.golda.settings") }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ code_marque: code, active: val })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            var card = document.getElementById('mc-' + slug);
            if (card) {
                card.dataset.active = val ? '1' : '0';
                var mc = card.querySelector('.marque-card');
                if (mc) mc.classList.toggle('inactive', !val);
            }
            var importBtn = document.getElementById('btn-import-' + slug);
            if (importBtn) importBtn.disabled = !val;
            showToast((val ? '✅ Activée : ' : '⏸ Désactivée : ') + code, val ? 'success' : 'secondary');
        }
    });
}

// ════════════════════════════════════════════════════════════════
// TOGGLE UNE RÈGLE
// ════════════════════════════════════════════════════════════════
function toggleRule(code, slug, key, pill) {
    var newVal = !pill.classList.contains('on');
    fetch('{{ route("admin.golda.settings") }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ code_marque: code, [key]: newVal })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            pill.classList.toggle('on', newVal);
            pill.classList.toggle('off', !newVal);
        }
    });
}

// ════════════════════════════════════════════════════════════════
// SAVE UN CHAMP
// ════════════════════════════════════════════════════════════════
function saveSetting(code, key, val) {
    fetch('{{ route("admin.golda.settings") }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ code_marque: code, [key]: (val === '' ? null : val) })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { if (d.success) showToast('✅ Sauvegardé', 'success'); });
}

// ════════════════════════════════════════════════════════════════
// LANCER TOUT
// ════════════════════════════════════════════════════════════════
function lancerTout(dryRun) {
    var msg = dryRun
        ? 'Lancer la SIMULATION pour toutes les marques actives ?'
        : 'Lancer l\'import complet pour toutes les marques actives ?\nCela peut prendre plusieurs minutes.';
    if (!confirm(msg)) return;
    var btn = dryRun ? document.getElementById('btn-dryrun') : document.getElementById('btn-tout');
    if (btn) btn.disabled = true;
    lancerImport(null, null, 'Toutes les marques actives', dryRun, btn);
}

// ════════════════════════════════════════════════════════════════
// LANCER UNE MARQUE
// code = vrai code_marque  |  slug = id HTML  |  nom = label affiché
// ════════════════════════════════════════════════════════════════
function lancerMarque(code, slug, nom, dryRun, btn) {
    if (!confirm((dryRun ? 'Simuler' : 'Importer') + ' : ' + nom + ' ?')) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>';
    lancerImport(code, slug, nom, dryRun, btn);
}

// ════════════════════════════════════════════════════════════════
// LOGIQUE COMMUNE D'IMPORT
// ════════════════════════════════════════════════════════════════
function lancerImport(code, slug, nom, dryRun, btnEl) {
    var progBlock = document.getElementById('progress-block');
    var logEl     = document.getElementById('live-log');

    progBlock.style.display = 'block';
    logEl.textContent = '';
    document.getElementById('progress-label').textContent = (dryRun ? '[SIMULATION] ' : '') + nom;
    animateProgress();
    progBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch('{{ route("admin.golda.run") }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ code_marque: code, dry_run: dryRun })
    })
    .then(function(r) {
        // Capturer les erreurs HTTP (500, 419, etc.)
        if (!r.ok) {
            return r.text().then(function(t) {
                throw new Error('HTTP ' + r.status + ' — ' + t.substring(0, 300));
            });
        }
        return r.json();
    })
    .then(function(d) {
        stopProgress();
        if (d.success) {
            logEl.textContent = d.output || '✅ Import terminé';
            logEl.scrollTop = logEl.scrollHeight;

            // ── Màj stats dans les cartes ──────────────────────
            if (d.marques) {
                d.marques.forEach(function(m) {
                    // Recalculer le slug côté JS pour retrouver le bon élément
                    var s = slugify(m.code_marque);
                    var statsBox   = document.getElementById('stats-' + s);
                    var statsDate  = document.getElementById('stats-date-' + s);
                    var statsCount = document.getElementById('stats-count-' + s);
                    if (statsBox && m.last_imported_at) {
                        statsBox.style.display = '';
                        if (statsDate)  statsDate.textContent  = m.last_imported_at;
                        if (statsCount) statsCount.textContent = m.last_import_count + ' traités';
                    }
                });
            }

            // ── Réactiver les boutons ──────────────────────────
            if (btnEl) {
                btnEl.disabled = false;
                var isDry = (btnEl.textContent.indexOf('Simuler') !== -1);
                btnEl.innerHTML = isDry
                    ? '<i class="fas fa-eye me-1"></i> Simuler'
                    : '<i class="fas fa-play me-1"></i> Lancer l\'import';
            }
            document.getElementById('btn-tout').disabled = false;
            document.getElementById('btn-dryrun').disabled = false;

            showToast(dryRun ? '🔍 Simulation terminée' : '✅ Import terminé', 'success');
        } else {
            logEl.textContent = '❌ ' + (d.message || 'Erreur inconnue');
            if (btnEl) {
                btnEl.disabled = false;
                btnEl.innerHTML = '<i class="fas fa-play me-1"></i> Relancer';
            }
            document.getElementById('btn-tout').disabled = false;
            document.getElementById('btn-dryrun').disabled = false;
            showToast('❌ ' + (d.message || 'Erreur'), 'danger');
        }
    })
    .catch(function(err) {
        stopProgress();
        logEl.textContent = '❌ ' + err.message;
        if (btnEl) {
            btnEl.disabled = false;
            btnEl.innerHTML = '<i class="fas fa-play me-1"></i> Relancer';
        }
        document.getElementById('btn-tout').disabled = false;
        document.getElementById('btn-dryrun').disabled = false;
        showToast('❌ ' + err.message, 'danger');
    });
}

// ════════════════════════════════════════════════════════════════
// FILTRES
// ════════════════════════════════════════════════════════════════
function filtrer(btn, mode) {
    document.querySelectorAll('.filter-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.marque-item').forEach(function(el) {
        var isActive = el.dataset.active === '1';
        el.style.display = (mode === 'all' || (mode === 'active' && isActive) || (mode === 'inactive' && !isActive)) ? '' : 'none';
    });
}

function rechercherMarque(val) {
    var q = val.toLowerCase();
    document.querySelectorAll('.marque-item').forEach(function(el) {
        el.style.display = (!q || el.dataset.nom.indexOf(q) !== -1) ? '' : 'none';
    });
}

// ════════════════════════════════════════════════════════════════
// PROGRESS BAR
// ════════════════════════════════════════════════════════════════
var progInterval = null;
function animateProgress() {
    var val = 0;
    var bar = document.getElementById('progress-bar');
    var pct = document.getElementById('progress-pct');
    bar.style.width = '0%';
    progInterval = setInterval(function() {
        if (val < 88) {
            val += Math.random() * 2.5;
            bar.style.width = Math.min(val, 88) + '%';
            pct.textContent  = Math.round(val) + '%';
        }
    }, 500);
}
function stopProgress() {
    clearInterval(progInterval);
    document.getElementById('progress-bar').style.width = '100%';
    document.getElementById('progress-pct').textContent = '100%';
}

// ════════════════════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════════════════════
function showToast(msg, type) {
    var t = document.createElement('div');
    t.style.cssText = 'position:fixed;top:16px;right:16px;z-index:9999;min-width:260px;';
    t.innerHTML = '<div class="alert alert-' + type + ' shadow mb-0 py-2 px-3" style="font-size:.82rem;border-radius:10px;">' + msg + '</div>';
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 3500);
}
</script>
</body>
</html>