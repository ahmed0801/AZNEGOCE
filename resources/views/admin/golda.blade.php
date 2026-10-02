<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP — Import Tarifs GOLDA</title>
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





    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style>
    /* Intégration Select2 dans le design */
    .select2-container--default .select2-selection--single {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        height: 31px;
        font-size: .75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
        font-size: .75rem;
        color: #1E2D4A;
        padding-left: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 29px;
    }
    .select2-container--default .select2-results__option--highlighted {
        background: #3B82F6;
    }
    .select2-dropdown {
        border-radius: 10px;
        border: 1.5px solid #E2E8F0;
        box-shadow: 0 8px 24px rgba(30,45,74,.12);
        font-size: .78rem;
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: 6px;
        border: 1.5px solid #E2E8F0;
        font-size: .78rem;
        padding: 5px 10px;
    }
    .select2-container { width: 100% !important; }
</style>




    <style>
        /* ══ HEADER ══════════════════════════════════════════════ */
        .golda-header {
            background: linear-gradient(135deg, #1E2D4A 0%, #2D4A8A 100%);
            border-radius: 16px; padding: 26px 30px; color: white; margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(30,45,74,.18);
        }
        .golda-header h3 { font-weight: 800; margin: 0; font-size: 1.4rem; letter-spacing:-.01em; }
        .golda-header small { opacity: .6; font-size: .8rem; }

        /* ══ STATS CARDS ════════════════════════════════════════ */
        .stat-mini {
            text-align: center; padding: 14px 16px; border-radius: 14px;
            transition: transform .15s;
        }
        .stat-mini:hover { transform: translateY(-2px); }
        .stat-mini h4 { font-size: 1.7rem; font-weight: 800; margin: 0 0 2px; }
        .stat-mini small { font-size: .7rem; color: #6B7A99; font-weight: 600; text-transform:uppercase; letter-spacing:.04em; }

        /* ══ BLOC IMPORT EN COURS ═══════════════════════════════ */
        #progress-block { position: sticky; top: 0; z-index: 50; }
        .import-card {
            background: white; border-radius: 16px;
            box-shadow: 0 4px 24px rgba(30,45,74,.12);
            border: 1.5px solid #E2E8F0; overflow: hidden;
        }
        .import-card-header {
            background: linear-gradient(135deg, #0F172A, #1E3A8A);
            padding: 14px 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .import-card-header .title {
            color: white; font-weight: 700; font-size: .9rem;
            display: flex; align-items: center; gap: 10px;
        }
        .import-card-header .actions { display: flex; gap: 8px; align-items: center; }
        .spinner-dot {
            width: 10px; height: 10px; border-radius: 50%; background: #60EF90;
            animation: pulse-dot 1.2s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }

        .progress-track { background: #E2E8F0; border-radius: 20px; height: 7px; overflow: hidden; }
        .progress-fill  {
            height: 100%; border-radius: 20px;
            background: linear-gradient(90deg, #3B82F6, #10B981, #3B82F6);
            background-size: 200% 100%;
            animation: progress-shimmer 2s linear infinite;
            transition: width .5s ease;
        }
        @keyframes progress-shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .progress-fill.done {
            background: #10B981; animation: none;
        }

        /* ══ LOG BOX ════════════════════════════════════════════ */
        .log-box {
            background: #0A1628; color: #7FFF8C;
            border-radius: 0 0 14px 14px; padding: 14px 16px;
            font-family: 'Courier New', monospace; font-size: .72rem;
            max-height: 260px; overflow-y: auto; line-height: 1.8;
            white-space: pre-wrap; border-top: 1px solid #1E3A5A;
        }
        .log-box .log-error   { color: #FCA5A5; }
        .log-box .log-success { color: #6EE7B7; }
        .log-box .log-warn    { color: #FCD34D; }

        /* Alerte "ne pas fermer" */
        .import-warning {
            background: linear-gradient(90deg, #FFF7ED, #FFFBEB);
            border: 1.5px solid #FED7AA; border-radius: 10px;
            padding: 10px 16px; font-size: .78rem; color: #92400E;
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
        }
        .import-warning strong { font-weight: 700; }

        /* ══ MARQUE CARDS ═══════════════════════════════════════ */
        .marque-card {
            border-radius: 16px; border: 1.5px solid #E8EFF8 !important;
            background: white; transition: box-shadow .2s, border-color .2s, transform .15s;
            height: 100%;
        }
        .marque-card:hover { box-shadow: 0 6px 28px rgba(30,45,74,.1); border-color: #93C5FD !important; transform: translateY(-1px); }
        .marque-card.inactive { opacity: .5; background: #F8FAFF; }
        .marque-card.importing { border-color: #3B82F6 !important; box-shadow: 0 0 0 3px rgba(59,130,246,.15) !important; }
        .marque-card.success-import { border-color: #10B981 !important; }
        .marque-card.error-import   { border-color: #EF4444 !important; }

        .mc-header {
            background: linear-gradient(135deg, #F8FAFF, #F0F4FF);
            border-bottom: 1.5px solid #E8EFF8; border-radius: 14px 14px 0 0;
            padding: 12px 15px; display: flex; justify-content: space-between; align-items: center;
        }
        .mc-brand-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: .7rem; font-weight: 700; color: #1E2D4A;
        }
        .mc-prefix {
            font-size: .66rem; color: #3B82F6; background: #EFF6FF;
            padding: 1px 6px; border-radius: 4px; font-family: monospace; font-weight: 700;
        }

        /* Toggle switch */
        .toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-switch .slider { position: absolute; inset: 0; background: #CBD5E1; border-radius: 22px; transition: .2s; cursor: pointer; }
        .toggle-switch input:checked + .slider { background: #10B981; }
        .toggle-switch .slider::before { content: ''; position: absolute; width: 16px; height: 16px; left: 3px; top: 3px; background: white; border-radius: 50%; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
        .toggle-switch input:checked + .slider::before { transform: translateX(18px); }

        /* Rule pills */
        .rule-pill {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .65rem; font-weight: 700; padding: 3px 8px;
            border-radius: 8px; cursor: pointer; user-select: none; transition: all .15s;
        }
        .rule-pill.on  { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .rule-pill.off { background: #F1F5F9; color: #94A3B8; border: 1px solid #E2E8F0; }
        .rule-pill:hover { transform: translateY(-1px); }

        /* Boutons action */
        .btn-simulate {
            flex: 1; background: #EFF6FF; color: #1D4ED8;
            border: 1.5px solid #BFDBFE; border-radius: 9px;
            font-weight: 700; font-size: .75rem; padding: 8px 10px;
            cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 5px;
        }
        .btn-simulate:hover { background: #DBEAFE; }
        .btn-run {
            flex: 2; background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white; border: none; border-radius: 9px;
            font-weight: 700; font-size: .75rem; padding: 8px 10px;
            cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 5px;
            box-shadow: 0 2px 8px rgba(59,130,246,.3);
        }
        .btn-run:hover { background: linear-gradient(135deg, #2563EB, #1D4ED8); box-shadow: 0 4px 14px rgba(59,130,246,.4); }
        .btn-run:disabled { background: #94A3B8; box-shadow: none; cursor: not-allowed; }

        /* Dernière import badge */
        .last-import-badge {
            background: #F0FDF4; border: 1px solid #BBF7D0;
            border-radius: 8px; padding: 6px 10px;
            font-size: .7rem; color: #166534; margin-bottom: 10px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ══ FILTRES ════════════════════════════════════════════ */
        .filter-btn {
            border-radius: 20px; padding: 5px 14px; font-size: .78rem; font-weight: 600;
            border: 1.5px solid #E2E8F0; background: white; color: #6B7A99;
            cursor: pointer; transition: all .15s;
        }
        .filter-btn:hover { border-color: #93C5FD; color: #1D4ED8; }
        .filter-btn.active { background: #1E2D4A; color: white; border-color: #1E2D4A; box-shadow: 0 2px 8px rgba(30,45,74,.2); }

        /* ══ BOUTON ANNULER ══════════════════════════════════════ */
        .btn-cancel-import {
            background: rgba(239,68,68,.15); color: #FCA5A5;
            border: 1.5px solid rgba(239,68,68,.3); border-radius: 8px;
            padding: 6px 14px; font-size: .78rem; font-weight: 700;
            cursor: pointer; transition: all .15s; display: flex; align-items: center; gap: 6px;
        }
        .btn-cancel-import:hover { background: rgba(239,68,68,.25); color: white; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ══ SIDEBAR ══════════════════════════════════════════════ --}}
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <div class="logo-header" data-background-color="dark">
                <a href="/" class="logo"><img src="{{ asset('assets/img/logop.png') }}" alt="logo" class="navbar-brand" height="70" /></a>
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

    <div class="main-panel">

        {{-- ══ NAVBAR ════════════════════════════════════════════ --}}
        <div class="main-header">
            <div class="main-header-logo">
                <div class="logo-header" data-background-color="dark">
                    <a href="/" class="logo"><img src="{{ asset('assets/img/logop.png') }}" alt="logo" class="navbar-brand" height="20" /></a>
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
                            <a class="nav-link" data-bs-toggle="dropdown" href="#"><i class="fas fa-layer-group"></i></a>
                            <div class="dropdown-menu quick-actions animated fadeIn">
                                <div class="quick-actions-header"><span class="title mb-1">Actions Rapides</span></div>
                                <div class="quick-actions-scroll scrollbar-outer"><div class="quick-actions-items"><div class="row m-0">
                                    <a class="col-6 col-md-4 p-0" href="/articles"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fas fa-sitemap"></i></div><span class="text">Articles</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/customers"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-users"></i></div><span class="text">Clients</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/suppliers"><div class="quick-actions-item"><div class="avatar-item bg-secondary rounded-circle"><i class="fas fa-user-tag"></i></div><span class="text">Fournisseurs</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/salesinvoices"><div class="quick-actions-item"><div class="avatar-item bg-warning rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Factures</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/purchases/list"><div class="quick-actions-item"><div class="avatar-item bg-success rounded-circle"><i class="fa fa-cart-plus"></i></div><span class="text">Cmd Achats</span></div></a>
                                    <a class="col-6 col-md-4 p-0" href="/invoices"><div class="quick-actions-item"><div class="avatar-item bg-primary rounded-circle"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Fact Achats</span></div></a>
                                </div></div></div>
                            </div>
                        </li>
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">
                                <div class="avatar-sm"><img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" /></div>
                                <span class="profile-username"><span class="fw-bold">{{ Auth::user()->name }}</span></span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li><div class="user-box">
                                        <div class="avatar-lg"><img src="{{ asset('assets/img/avatar.png') }}" alt="" class="avatar-img rounded" /></div>
                                        <div class="u-text"><h4>{{ Auth::user()->name }}</h4><p class="text-muted">{{ Auth::user()->email }}</p></div>
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

        <div class="container">
            <div class="page-inner">

                {{-- ══ HEADER ═════════════════════════════════════ --}}
                <div class="golda-header">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h3>
                                <i class="fas fa-cloud-download-alt me-2" style="color:#60A5FA;"></i>
                                Import Tarifs GOLDA
                            </h3>
                            <small>
                                Gestion des marques, règles d'importation et mises à jour de prix depuis le FTP GOLDA
                                @if($lastImport)
                                    &nbsp;·&nbsp; <i class="fas fa-check-circle" style="color:#6EE7B7;"></i>
                                    Dernier import : <strong>{{ $lastImport }}</strong>
                                @endif
                            </small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            <button onclick="syncMarques()" class="btn btn-outline-light btn-sm btn-round" id="btn-sync"
                                    title="Télécharge uniquement infos_tarifs.csv (rapide)">
                                <i class="fas fa-sync-alt me-1"></i> Charger les marques
                            </button>
                            <button onclick="lancerTout(true)" class="btn btn-sm btn-round"
                                    style="background:rgba(255,255,255,.12);color:white;border:1.5px solid rgba(255,255,255,.25);" id="btn-dryrun">
                                <i class="fas fa-eye me-1"></i> Simulation
                            </button>
                            <button onclick="lancerTout(false)"
                                    class="btn btn-sm btn-round px-4"
                                    style="background:white;color:#1E2D4A;font-weight:800;box-shadow:0 2px 8px rgba(0,0,0,.15);"
                                    id="btn-tout">
                                <i class="fas fa-play me-1" style="color:#10B981;"></i> Tout importer
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══ STATS ════════════════════════════════════════ --}}
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
                            <small>Articles actifs</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="stat-mini" style="background:#F5F3FF;border:1.5px solid #DDD6FE;">
                            <h4 style="color:#5B21B6;">{{ $marques->whereNotNull('last_imported_at')->count() }}</h4>
                            <small>Déjà importées</small>
                        </div>
                    </div>
                </div>

                {{-- ══ BLOC PROGRESSION ════════════════════════════ --}}
                <div id="progress-block" style="display:none;" class="mb-4">
                    <div class="import-card">

                        {{-- Header --}}
                        <div class="import-card-header">
                            <div class="title">
                                <div class="spinner-dot" id="import-dot"></div>
                                <span id="progress-label">Import en cours...</span>
                            </div>
                            <div class="actions">
                                <span id="progress-pct" style="color:rgba(255,255,255,.6);font-size:.78rem;font-weight:700;"></span>
                                {{-- Ouvrir dashboard dans nouvel onglet --}}
                                <a href="/dashboard" target="_blank"
                                   style="background:rgba(255,255,255,.12);color:white;border:1px solid rgba(255,255,255,.2);
                                          border-radius:7px;padding:5px 12px;font-size:.75rem;font-weight:600;
                                          text-decoration:none;display:flex;align-items:center;gap:5px;"
                                   title="Continuer à travailler pendant l'import">
                                    <i class="fas fa-external-link-alt"></i> Dashboard
                                </a>
                                {{-- Annuler --}}
                                <button class="btn-cancel-import" id="btn-cancel" onclick="annulerImport()">
                                    <i class="fas fa-times-circle"></i> Annuler
                                </button>
                            </div>
                        </div>

                        {{-- Alerte : ne pas fermer --}}
                        <div style="padding:10px 16px;background:#FFFBEB;border-bottom:1px solid #FDE68A;">
                            <div class="import-warning">
                                <div>
                                    <i class="fas fa-exclamation-triangle me-2" style="color:#F59E0B;"></i>
                                    <strong>⚠️ Ne fermez pas cette page</strong> — l'import tourne en arrière-plan.
                                    <span style="opacity:.75;">Vous pouvez ouvrir le dashboard dans un nouvel onglet pendant ce temps.</span>
                                </div>
                                <a href="/dashboard" target="_blank"
                                   class="btn btn-sm btn-round"
                                   style="background:#FEF3C7;color:#92400E;border:1.5px solid #FDE68A;font-weight:700;font-size:.75rem;white-space:nowrap;">
                                    <i class="fas fa-external-link-alt me-1"></i> Ouvrir le dashboard
                                </a>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div style="padding:12px 16px 10px;background:white;">
                            <div class="progress-track">
                                <div class="progress-fill" id="progress-bar" style="width:0%;"></div>
                            </div>
                        </div>

                        {{-- Log --}}
                        <div class="log-box" id="live-log">En attente du démarrage...</div>
                    </div>
                </div>

                {{-- ══ FILTRES ══════════════════════════════════════ --}}
                @if($marques->count() > 0)
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span style="font-size:.75rem;font-weight:700;color:#6B7A99;">Afficher :</span>
                    <button class="filter-btn active" onclick="filtrer(this,'all')">
                        Toutes <span style="opacity:.6;">({{ $marques->count() }})</span>
                    </button>
                    <button class="filter-btn" onclick="filtrer(this,'active')">
                        Actives <span style="opacity:.6;">({{ $marques->where('active', true)->count() }})</span>
                    </button>
                    <button class="filter-btn" onclick="filtrer(this,'inactive')">
                        Inactives <span style="opacity:.6;">({{ $marques->where('active', false)->count() }})</span>
                    </button>
                    <div class="ms-auto" style="min-width:230px;">
                        <div style="position:relative;">
                            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:.75rem;"></i>
                            <input type="text" id="search-marque" class="form-control form-control-sm"
                                   placeholder="Rechercher une marque..."
                                   style="border-radius:20px;border:1.5px solid #E2E8F0;font-size:.78rem;padding-left:32px;"
                                   oninput="rechercherMarque(this.value)">
                        </div>
                    </div>
                </div>
                @endif

                {{-- ══ GRILLE DES MARQUES ══════════════════════════ --}}
                @if($marques->isEmpty())
                <div class="text-center py-5">
                    <div style="width:80px;height:80px;border-radius:50%;background:#EFF6FF;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-cloud-download-alt" style="font-size:2rem;color:#93C5FD;"></i>
                    </div>
                    <h5 class="fw-bold text-muted">Aucune marque configurée</h5>
                    <p class="text-muted" style="font-size:.82rem;max-width:380px;margin:0 auto 20px;">
                        Cliquez sur "Charger les marques" pour récupérer la liste depuis le FTP GOLDA.<br>
                        <strong>Rapide</strong> — seul le fichier index est téléchargé.
                    </p>
                    <button onclick="syncMarques()" class="btn btn-primary btn-round px-4">
                        <i class="fas fa-sync-alt me-2"></i> Charger les marques depuis le FTP
                    </button>
                </div>
                @else
                <div class="row" id="marques-grid">
                    @foreach($marques as $m)
                    @php $slug = Str::slug($m->code_marque); @endphp
                    <div class="col-xl-4 col-lg-6 col-12 mb-3 marque-item"
                         id="mc-{{ $slug }}"
                         data-slug="{{ $slug }}"
                         data-code="{{ $m->code_marque }}"
                         data-nom="{{ strtolower($m->nom_marque) }}"
                         data-active="{{ $m->active ? '1' : '0' }}">
                        <div class="marque-card {{ $m->active ? '' : 'inactive' }}">

                            {{-- Header --}}
                            <div class="mc-header">
                                <div>
                                    <div class="mc-brand-badge">
                                        <i class="fas fa-tag" style="color:#3B82F6;font-size:.7rem;"></i>
                                        <span style="font-weight:800;color:#1E2D4A;font-size:.92rem;">{{ $m->nom_marque }}</span>
                                        <span class="mc-prefix">{{ $m->prefixe_tarif ?? $m->code_marque }}</span>
                                    </div>
                                    @if($m->active)
                                        <small style="color:#10B981;font-size:.62rem;font-weight:700;">● Active</small>
                                    @else
                                        <small style="color:#94A3B8;font-size:.62rem;">○ Inactive</small>
                                    @endif
                                </div>
                                <label class="toggle-switch ms-2" title="{{ $m->active ? 'Désactiver' : 'Activer' }}">
                                    <input type="checkbox" {{ $m->active ? 'checked' : '' }}
                                           onchange="toggleActive('{{ $m->code_marque }}', '{{ $slug }}', this.checked)">
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div style="padding:14px;">

                                {{-- Brand + Fournisseur --}}
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label style="font-size:.62rem;font-weight:700;color:#6B7A99;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px;">
                                            <i class="fas fa-trademark me-1"></i> Brand
                                        </label>
                                        <select class="form-select form-select-sm" style="font-size:.75rem;border-radius:8px;"
                                                onchange="saveSetting('{{ $m->code_marque }}', 'brand_id', this.value)">
                                            <option value="">— Aucune —</option>
                                            @foreach($brands as $b)
                                                <option value="{{ $b->id }}" {{ $m->brand_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label style="font-size:.62rem;font-weight:700;color:#6B7A99;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px;">
                                            <i class="fas fa-building me-1"></i> Fournisseur
                                        </label>
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
                                                    ⭐ {{ $suggested->name }}
                                                </option>
                                            @endif
                                            @foreach($suppliers as $s)
                                                @if(!$suggested || $s->code !== $suggested->code || $m->supplier_code)
                                                <option value="{{ $s->code }}" {{ $m->supplier_code == $s->code ? 'selected' : '' }}>{{ $s->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if($suggested && !$m->supplier_code)
                                        <small style="color:#D97706;font-size:.6rem;display:block;margin-top:2px;">
                                            <i class="fas fa-lightbulb"></i> Correspondance détectée
                                        </small>
                                        @endif
                                    </div>
                                </div>

                                {{-- Règles d'import --}}
                                <div style="font-size:.62rem;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">
                                    Règles d'importation
                                </div>
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @php
                                        $rules = [
                                            ['key'=>'update_cost_price',  'label'=>'Prix achat',         'icon'=>'fa-tag'],
                                            ['key'=>'update_sale_price',  'label'=>'Prix vente',         'icon'=>'fa-euro-sign'],
                                            ['key'=>'update_description', 'label'=>'Description',        'icon'=>'fa-font'],
                                            ['key'=>'update_barcode',     'label'=>'Code EAN',           'icon'=>'fa-barcode'],
                                            ['key'=>'update_dimensions',  'label'=>'Dimensions',         'icon'=>'fa-ruler'],
                                            ['key'=>'create_new_items',   'label'=>'Créer nouveaux',     'icon'=>'fa-plus-circle'],
                                            ['key'=>'deactivate_removed', 'label'=>'Désactiver supprimés','icon'=>'fa-trash'],
                                        ];
                                    @endphp
                                    @foreach($rules as $rule)
                                    <span class="rule-pill {{ $m->{$rule['key']} ? 'on' : 'off' }}"
                                          id="pill-{{ $slug }}-{{ $rule['key'] }}"
                                          onclick="toggleRule('{{ $m->code_marque }}', '{{ $slug }}', '{{ $rule['key'] }}', this)"
                                          title="{{ $m->{$rule['key']} ? 'Cliquer pour désactiver' : 'Cliquer pour activer' }}">
                                        <i class="fas {{ $rule['icon'] }}"></i> {{ $rule['label'] }}
                                    </span>
                                    @endforeach
                                </div>

                                {{-- Marge override --}}
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <label style="font-size:.62rem;font-weight:700;color:#6B7A99;white-space:nowrap;">
                                        <i class="fas fa-percent me-1"></i> Marge spécifique
                                    </label>
                                    <input type="number" min="0" max="200" step="0.5"
                                           class="form-control form-control-sm"
                                           style="width:80px;border-radius:8px;font-size:.78rem;"
                                           placeholder="Auto"
                                           value="{{ $m->margin_override ?? '' }}"
                                           onchange="saveSetting('{{ $m->code_marque }}', 'margin_override', this.value)"
                                           title="Laisser vide = marge par famille de produit">
                                    <small style="color:#9CA3AF;font-size:.62rem;">Vide = par famille</small>
                                </div>

                                {{-- Dernier import --}}
                                <div id="stats-{{ $slug }}"
                                     class="last-import-badge"
                                     style="{{ $m->last_imported_at ? '' : 'display:none;' }}">
                                    <i class="fas fa-check-circle" style="color:#10B981;"></i>
                                    <span>
                                        Dernier import :
                                        <strong id="stats-date-{{ $slug }}">
                                            {{ $m->last_imported_at ? $m->last_imported_at->format('d/m/Y H:i') : '' }}
                                        </strong>
                                        &nbsp;·&nbsp;
                                        <span id="stats-count-{{ $slug }}" style="font-weight:700;">
                                            {{ number_format($m->last_import_count) }} traités
                                        </span>
                                        @if($m->last_deactivated_count > 0)
                                        &nbsp;·&nbsp;
                                        <span id="stats-deact-{{ $slug }}" style="color:#DC2626;">
                                            {{ $m->last_deactivated_count }} désactivés
                                        </span>
                                        @endif
                                    </span>
                                </div>

                                {{-- Boutons --}}
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn-simulate"
                                            id="btn-simulate-{{ $slug }}"
                                            onclick="lancerMarque('{{ $m->code_marque }}', '{{ $slug }}', '{{ addslashes($m->nom_marque) }}', true, this)">
                                        <i class="fas fa-eye"></i> Simuler
                                    </button>
                                    <button type="button" class="btn-run"
                                            id="btn-import-{{ $slug }}"
                                            onclick="lancerMarque('{{ $m->code_marque }}', '{{ $slug }}', '{{ addslashes($m->nom_marque) }}', false, this)"
                                            {{ $m->active ? '' : 'disabled' }}>
                                        <i class="fas fa-play"></i> Lancer l'import
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
                <div>by <a href="#" target="_blank">AZ NEGOCE</a>.</div>
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
var CSRF        = document.querySelector('meta[name="csrf-token"]').content;
var currentXHR  = null; // Référence à l'import en cours (pour annulation)
var importRunning = false;

// ════════════════════════════════════════════════════════════════
// SLUGIFY — identique à Str::slug() de Laravel
// ════════════════════════════════════════════════════════════════
function slugify(str) {
    return (str || '').toString().toLowerCase()
        .replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '-')
        .replace(/-+/g, '-').replace(/^-|-$/g, '');
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
            showToast('✅ ' + d.created + ' nouvelles · ' + d.existing + ' existantes', 'success');
            setTimeout(function() { location.reload(); }, 1200);
        } else {
            showToast('❌ ' + d.message, 'danger');
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Charger les marques';
            btn.disabled = false;
        }
    })
    .catch(function(err) {
        showToast('❌ Erreur : ' + err.message, 'danger');
        btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Charger les marques';
        btn.disabled = false;
    });
}

// ════════════════════════════════════════════════════════════════
// TOGGLE ACTIF / INACTIF
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
                // Màj badge statut
                var badge = mc ? mc.querySelector('small') : null;
                if (badge) {
                    badge.style.color   = val ? '#10B981' : '#94A3B8';
                    badge.textContent   = val ? '● Active' : '○ Inactive';
                }
            }
            var btn = document.getElementById('btn-import-' + slug);
            if (btn) btn.disabled = !val;
            showToast((val ? '✅ ' : '⏸ ') + code + (val ? ' activée' : ' désactivée'), val ? 'success' : 'secondary');
        }
    });
}

// ════════════════════════════════════════════════════════════════
// TOGGLE RÈGLE
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
    if (importRunning) { showToast('⚠️ Un import est déjà en cours', 'warning'); return; }
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
// ════════════════════════════════════════════════════════════════
function lancerMarque(code, slug, nom, dryRun, btn) {
    if (importRunning) { showToast('⚠️ Un import est déjà en cours', 'warning'); return; }
    if (!confirm((dryRun ? 'Simuler' : 'Importer') + ' : ' + nom + ' ?')) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    // Marquer la carte
    var card = document.getElementById('mc-' + slug);
    if (card) card.querySelector('.marque-card').classList.add('importing');
    lancerImport(code, slug, nom, dryRun, btn);
}

// ════════════════════════════════════════════════════════════════
// LOGIQUE COMMUNE D'IMPORT
// ════════════════════════════════════════════════════════════════
function lancerImport(code, slug, nom, dryRun, btnEl) {
    importRunning = true;

    var progBlock = document.getElementById('progress-block');
    var logEl     = document.getElementById('live-log');
    var cancelBtn = document.getElementById('btn-cancel');

    progBlock.style.display = 'block';
    cancelBtn.style.display = 'flex';
    logEl.textContent = '⏳ Connexion au FTP GOLDA...';
    document.getElementById('progress-label').textContent = (dryRun ? '🔍 [SIMULATION] ' : '🚀 ') + nom;
    document.getElementById('import-dot').style.background = '#60EF90';
    animateProgress();
    progBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Utiliser XMLHttpRequest pour pouvoir annuler
    currentXHR = new XMLHttpRequest();
    currentXHR.open('POST', '{{ route("admin.golda.run") }}', true);
    currentXHR.setRequestHeader('Content-Type', 'application/json');
    currentXHR.setRequestHeader('X-CSRF-TOKEN', CSRF);
    currentXHR.timeout = 600000; // 10 min

    currentXHR.onload = function() {
        importRunning = false;
        stopProgress();

        if (currentXHR.status === 200) {
            var d;
            try { d = JSON.parse(currentXHR.responseText); }
            catch(e) { d = { success: false, message: 'Réponse invalide du serveur' }; }

            if (d.success) {
                // Colorer le log avec des icônes
                logEl.textContent = d.output || '✅ Import terminé';
                logEl.scrollTop = logEl.scrollHeight;

                // Màj badges statut des cartes
                if (d.marques) {
                    d.marques.forEach(function(m) {
                        var s = slugify(m.code_marque);
                        var statsBox  = document.getElementById('stats-' + s);
                        var statsDate = document.getElementById('stats-date-' + s);
                        var statsCount= document.getElementById('stats-count-' + s);
                        if (statsBox && m.last_imported_at) {
                            statsBox.style.display = '';
                            if (statsDate)  statsDate.textContent  = m.last_imported_at;
                            if (statsCount) statsCount.textContent = m.last_import_count + ' traités';
                        }
                    });
                }

                // Retirer classe importing + mettre success
                if (slug) {
                    var card = document.getElementById('mc-' + slug);
                    if (card) {
                        var mc = card.querySelector('.marque-card');
                        if (mc) { mc.classList.remove('importing'); mc.classList.add('success-import'); }
                    }
                }

                document.getElementById('import-dot').style.background = '#10B981';
                document.getElementById('import-dot').style.animation = 'none';
                document.getElementById('progress-label').textContent = '✅ ' + nom + ' — Import terminé';
                showToast(dryRun ? '🔍 Simulation terminée' : '✅ Import terminé avec succès', 'success');
            } else {
                logEl.textContent = '❌ ' + (d.message || 'Erreur inconnue');
                document.getElementById('import-dot').style.background = '#EF4444';
                if (slug) {
                    var card = document.getElementById('mc-' + slug);
                    if (card) {
                        var mc = card.querySelector('.marque-card');
                        if (mc) { mc.classList.remove('importing'); mc.classList.add('error-import'); }
                    }
                }
                showToast('❌ ' + (d.message || 'Erreur'), 'danger');
            }
        } else {
            logEl.textContent = '❌ HTTP ' + currentXHR.status + ' — ' + currentXHR.responseText.substring(0, 200);
            showToast('❌ Erreur serveur HTTP ' + currentXHR.status, 'danger');
        }

        // Réactiver boutons
        reactiverBoutons(btnEl, slug);
        cancelBtn.style.display = 'none';
    };

    currentXHR.onerror = function() {
        importRunning = false;
        stopProgress();
        logEl.textContent = '❌ Erreur réseau — vérifiez la connexion';
        showToast('❌ Erreur réseau', 'danger');
        reactiverBoutons(btnEl, slug);
        cancelBtn.style.display = 'none';
    };

    currentXHR.ontimeout = function() {
        importRunning = false;
        stopProgress();
        logEl.textContent = '⏱️ Timeout — l\'import a dépassé 10 minutes';
        showToast('⏱️ Timeout', 'warning');
        reactiverBoutons(btnEl, slug);
        cancelBtn.style.display = 'none';
    };

    currentXHR.send(JSON.stringify({ code_marque: code, dry_run: dryRun }));
}

// ════════════════════════════════════════════════════════════════
// ANNULER L'IMPORT EN COURS
// ════════════════════════════════════════════════════════════════
function annulerImport() {
    if (!currentXHR || !importRunning) return;
    if (!confirm('Annuler l\'import en cours ?\n\nAttention : les articles déjà traités resteront enregistrés.')) return;

    currentXHR.abort();
    importRunning = false;
    stopProgress();

    var logEl = document.getElementById('live-log');
    logEl.textContent += '\n\n⛔ Import annulé par l\'utilisateur.';
    document.getElementById('import-dot').style.background = '#F59E0B';
    document.getElementById('import-dot').style.animation = 'none';
    document.getElementById('progress-label').textContent = '⛔ Import annulé';
    document.getElementById('btn-cancel').style.display = 'none';

    showToast('⛔ Import annulé — les données déjà traitées sont conservées', 'warning');

    // Réactiver tous les boutons
    document.getElementById('btn-tout').disabled = false;
    document.getElementById('btn-dryrun').disabled = false;
    // Retirer les classes importing
    document.querySelectorAll('.marque-card.importing').forEach(function(mc) {
        mc.classList.remove('importing');
    });
}

function reactiverBoutons(btnEl, slug) {
    if (btnEl) {
        btnEl.disabled = false;
        var isDry = btnEl.id && btnEl.id.indexOf('simulate') !== -1;
        btnEl.innerHTML = isDry
            ? '<i class="fas fa-eye"></i> Simuler'
            : '<i class="fas fa-play"></i> Lancer l\'import';
    }
    if (slug) {
        var card = document.getElementById('mc-' + slug);
        if (card) card.querySelector('.marque-card').classList.remove('importing');
    }
    document.getElementById('btn-tout').disabled = false;
    document.getElementById('btn-dryrun').disabled = false;
}

// ════════════════════════════════════════════════════════════════
// FILTRES
// ════════════════════════════════════════════════════════════════
function filtrer(btn, mode) {
    document.querySelectorAll('.filter-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.marque-item').forEach(function(el) {
        var isActive = el.dataset.active === '1';
        el.style.display = (mode==='all' || (mode==='active'&&isActive) || (mode==='inactive'&&!isActive)) ? '' : 'none';
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
    bar.classList.remove('done');
    bar.style.width = '0%';
    progInterval = setInterval(function() {
        if (val < 90) {
            val += Math.random() * 1.8;
            bar.style.width = Math.min(val, 90) + '%';
            pct.textContent = Math.round(val) + '%';
        }
    }, 600);
}
function stopProgress() {
    clearInterval(progInterval);
    var bar = document.getElementById('progress-bar');
    bar.classList.add('done');
    bar.style.width = '100%';
    document.getElementById('progress-pct').textContent = '100%';
}

// ════════════════════════════════════════════════════════════════
// ALERTE FERMETURE PAGE
// ════════════════════════════════════════════════════════════════
window.addEventListener('beforeunload', function(e) {
    if (importRunning) {
        e.preventDefault();
        e.returnValue = 'Un import est en cours. Êtes-vous sûr de vouloir quitter ?';
        return e.returnValue;
    }
});

// ════════════════════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════════════════════
function showToast(msg, type) {
    var t = document.createElement('div');
    t.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;min-width:280px;animation:fadeInRight .3s ease;';
    t.innerHTML = '<div class="alert alert-' + type + ' shadow mb-0 py-2 px-3 d-flex align-items-center gap-2" style="font-size:.82rem;border-radius:12px;">' + msg + '</div>';
    document.body.appendChild(t);
    setTimeout(function() {
        t.style.opacity = '0'; t.style.transition = 'opacity .3s';
        setTimeout(function() { if(t.parentNode) t.remove(); }, 300);
    }, 3500);
}
</script>










<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
// ════════════════════════════════════════════════════════════════
// SELECT2 — Brand et Fournisseur
// ════════════════════════════════════════════════════════════════
$(document).ready(function() {

    // Initialiser tous les selects Brand
    $('select[onchange*="brand_id"]').each(function() {
        var code = $(this).attr('onchange').match(/'([^']+)'/)[1];
        $(this).select2({
            placeholder: '— Aucune —',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucune brand trouvée'; },
                searching: function() { return 'Recherche...'; }
            }
        }).on('change', function() {
            saveSetting(code, 'brand_id', $(this).val() || null);
        });
        // Retirer le onchange natif pour éviter double appel
        $(this).removeAttr('onchange');
    });

    // Initialiser tous les selects Fournisseur
    $('select[onchange*="supplier_code"]').each(function() {
        var code = $(this).attr('onchange').match(/'([^']+)'/)[1];
        $(this).select2({
            placeholder: '— Non lié —',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucun fournisseur trouvé'; },
                searching: function() { return 'Recherche...'; }
            }
        }).on('change', function() {
            saveSetting(code, 'supplier_code', $(this).val() || null);
        });
        $(this).removeAttr('onchange');
    });

});
</script>

</body>
</html>