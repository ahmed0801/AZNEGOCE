<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Créer un bon de livraison</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and Icons -->
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
        /* ============================================================
           TOUT LE CSS ORIGINAL EST CONSERVÉ INTACT
           ============================================================ */
        .card {
            border-radius: 8px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 8px 8px 0 0;
        }
        .card h3 { font-size: 1.6rem; color: #007bff; font-weight: 600; }
        .card-body { padding: 1.5rem; }
        .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-info {
            font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 6px; transition: all 0.3s ease;
        }
        .btn-primary:hover { background-color: #0056b3; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); }
        .btn-success:hover { background-color: #218838; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3); }
        .btn-danger:hover  { background-color: #c82333; box-shadow: 0 4px 10px rgba(200, 35, 51, 0.3); }
        .btn-warning:hover { background-color: #e0a800; box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3); }
        .btn-outline-primary:hover { background-color: #007bff; color: #fff; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); }
        .btn-outline-info:hover    { background-color: #17a2b8; color: #fff; box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3); }
        .table { width: 100%; margin-bottom: 0; background-color: #fff; border-radius: 6px; overflow: hidden; }
        .table th, .table td { text-align: left; vertical-align: middle; padding: 0.5rem; font-size: 0.85rem; }
        .table thead { background: #f8f9fa; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f2f2f2; }
        .table-responsive { max-height: 350px; overflow-y: auto; }
        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 6px; border: 1px solid #ced4da; padding: 0.4rem; font-size: 0.9rem; min-width: 80px;
        }
        .form-control.quantity, .form-control.unit_price_ht, .form-control.remise { width: 100px; font-size: 0.9rem; }
        .form-control.order_date { width: 150px; font-size: 0.85rem; padding: 0.3rem; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.8rem; }
        .form-label { font-weight: 600; color: #343a40; font-size: 0.9rem; }
        #customer_details { background: #f8f9fa; border-radius: 6px; padding: 1rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
        #search_results { background: #eaf3fcff; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-height: 180px; overflow-y: auto; }
        #search_results div:hover { background: #e9ecef; cursor: pointer; }
        .total-display { background: #f8f9fa; border-radius: 6px; padding: 0.8rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .table-text-small th, .table-text-small td, .table-text-small input,
        .table-text-small button, .table-text-small span, .table-text-small svg { font-size: 0.8rem !important; }
        .table-text-small th { font-size: 0.75rem !important; }
        .badge-very-sm { font-size: 0.5rem; padding: 0.1em 0.2em; vertical-align: middle; }
        .modal-md { max-width: 800px; }
        @media (max-width: 768px) {
            .table-responsive { max-height: none; }
            .table th, .table td { font-size: 0.75rem; padding: 0.4rem; }
            .form-control.quantity, .form-control.unit_price_ht, .form-control.remise { width: 80px; font-size: 0.8rem; }
            .form-control.order_date { width: 100%; font-size: 0.8rem; }
            .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-info { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
            .card-body { padding: 1rem; }
            .modal-md { max-width: 90%; }
        }
        .small-text { font-size: 0.95em; }
        #balanceSummary .card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        #balanceSummary .card-title { font-size: 1.1rem; font-weight: bold; }
        .balance-btn { min-width: 100px; }
        .select2-results__option--disabled { color: #999 !important; background-color: #f8f9fa !important; cursor: not-allowed; }
        .select2-container--default .select2-selection--single.vehicle-empty .select2-selection__rendered { color: #999; font-style: italic; }
        #vehicle_group { display: none; }
        #vehicle_id.select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { height: 34px; line-height: 34px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 34px; }
        .select2-results__option--all-customers { font-weight: bold; color: #007bff; }
        #vehicle_id option[value=""] { font-style: italic; color: #6c757d !important; }
        .purchase-price-block { font-size: 0.82rem; line-height: 1.2; }
        .net-price { min-width: 70px; font-weight: 600; color: #28a745 !important; }
        .margin-display { font-weight: 600; }

        /* Champs numériques compacts */
        #lines_table .quantity, #lines_table .unit_price_ht, #lines_table .unit_price_ttc,
        #lines_table .remise, #lines_table .cost-price-input, #lines_table .purchase-discount {
            width: 62px !important; min-width: 62px !important; max-width: 62px !important;
            padding: 0.25rem 0.35rem !important; font-size: 0.80rem !important; text-align: center;
        }
        @media (max-width: 768px) {
            #lines_table .quantity, #lines_table .unit_price_ht, #lines_table .unit_price_ttc,
            #lines_table .remise, #lines_table .cost-price-input, #lines_table .purchase-discount {
                width: 55px !important; min-width: 55px !important; font-size: 0.75rem !important; padding: 0.2rem 0.3rem !important;
            }
        }
        #lines_table td.text-right, #lines_table th.text-center { text-align: center !important; }
        #lines_table th:nth-child(5), #lines_table td:nth-child(5),
        #lines_table th:nth-child(12), #lines_table td:nth-child(12) { width: 70px; min-width: 70px; }
        #lines_table th:nth-child(6), #lines_table td:nth-child(6),
        #lines_table th:nth-child(7), #lines_table td:nth-child(7),
        #lines_table th:nth-child(8), #lines_table td:nth-child(8),
        #lines_table th:nth-child(9), #lines_table td:nth-child(9) {
            width: 70px !important; min-width: 70px !important;
        }
        #lines_table thead th {
            padding: 0.35rem 0.5rem !important;
            font-size: 0.80rem !important;
            font-weight: 600;
            vertical-align: middle;
            line-height: 1.2;
            height: 42px !important;
            position: sticky;
            top: 0;
            z-index: 2;
            background: #1a2b4a !important;
        }
        #lines_table th.py-1, #lines_table td.py-1 { padding-top: 0.35rem !important; padding-bottom: 0.35rem !important; }
        #lines_table thead tr { height: 42px !important; }
        #lines_table thead { border-bottom: 2px solid rgba(255,255,255,0.2); }


        /* ============================================================
           PATCH 1 — BANDEAU CLIENT : une ligne compacte
           Remplace le grand bloc #customer_details
           ============================================================ */
        #customer_details { display: none !important; } /* masqué, remplacé par le bandeau */

        #customer_banner {
            display: none;
            background: #f0f7ff;
            border: 1px solid #b8d4f0;
            border-left: 4px solid #0056b3;
            border-radius: 5px;
            padding: 5px 12px;
            margin-bottom: 10px;
            align-items: center;
            flex-wrap: wrap;
            gap: 0 14px;
            font-size: 0.82rem;
            line-height: 1.8;
        }
        #customer_banner .cb-name  { font-weight: 700; color: #003a80; font-size: 0.88rem; }
        #customer_banner .cb-sep   { color: #aac4e0; margin: 0 2px; }
        #customer_banner .cb-label { color: #666; }
        #customer_banner .cb-val   { font-weight: 600; color: #222; }
        #customer_banner .cb-solde-pos { font-weight: 700; color: #1a7a3a; }
        #customer_banner .cb-solde-neg { font-weight: 700; color: #c0200d; }
        #customer_banner .cb-balance-btn {
            font-size: 0.75rem; padding: 1px 9px; border-radius: 4px;
            border: 1px solid #9bb8d4; background: #fff; color: #185FA5; cursor: pointer;
            transition: all 0.15s; white-space: nowrap;
        }
        #customer_banner .cb-balance-btn:hover { background: #185FA5; color: #fff; }

        /* ============================================================
           PATCH 3 — FILTRES MARQUE & FOURNISSEUR MODERNES
           ============================================================ */
        #filter_supplier_wrap,
        #filter_brand_wrap {
            display: inline-flex;
            align-items: center;
        }
        #filter_supplier_wrap select,
        #filter_brand_wrap select {
            transition: border-color .15s, box-shadow .15s;
        }
        #filter_supplier_wrap select:hover {
            border-color: #c07a00 !important;
            box-shadow: 0 0 0 2px rgba(240,165,0,0.15);
        }
        #filter_supplier_wrap select:focus {
            border-color: #c07a00 !important;
            box-shadow: 0 0 0 3px rgba(240,165,0,0.25);
            outline: none;
        }
        #filter_brand_wrap select:hover {
            border-color: #1a7a3a !important;
            box-shadow: 0 0 0 2px rgba(40,167,69,0.15);
        }
        #filter_brand_wrap select:focus {
            border-color: #1a7a3a !important;
            box-shadow: 0 0 0 3px rgba(40,167,69,0.25);
            outline: none;
        }
        #search_item:focus {
            background: #fff !important;
            border-color: #007bff !important;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.12) !important;
            outline: none;
        }


        /* ============================================================
           PATCH 2 — RÉFÉRENCE & DÉSIGNATION plus lisibles,
           même hauteur de ligne (on ne touche pas aux autres colonnes)
           ============================================================ */

        /* Colonne Référence : monospace gras, bleu, plus grand */
        #lines_table td:nth-child(1) {
            min-width: 0;
            max-width: 130px;
            width: 120px;
        }
        #lines_table td:nth-child(1) .ref-code {
            display: block;
            font-family: 'Courier New', Consolas, monospace;
            font-weight: 900;
            font-size: 1.08rem;
            color: #0040c0;
            letter-spacing: 0.05em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 145px;
            line-height: 1.35;
            background: #eef4ff;
            padding: 2px 6px;
            border-radius: 3px;
            border-left: 3px solid #0040c0;
        }

        /* Colonne Désignation : plus grande, wrap autorisé */
        #lines_table td:nth-child(2) {
            min-width: 160px;
            max-width: 240px;
        }
        #lines_table td:nth-child(2) .des-name {
            display: block;
            font-weight: 700;
            font-size: 0.88rem;        /* ← plus grand que le 0.80 de base */
            color: #111;
            line-height: 1.35;
            white-space: normal;       /* ← autorise le retour à la ligne */
            word-break: break-word;
        }

        /* Ligne de tableau : hauteur min légèrement augmentée pour respirer */
        #lines_table tbody tr { height: auto !important; }
        #lines_table tbody td { padding: 5px 6px !important; vertical-align: middle; }

    </style>
</head>
<body>
<div class="wrapper sidebar_minimize">

    <!-- ═══════════ SIDEBAR ORIGINALE (data-toggle Bootstrap 4) ═══════════ -->
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
                        <a data-toggle="collapse" href="#ventes" aria-expanded="false"><i class="fas fa-shopping-cart"></i><p>Ventes</p><span class="caret"></span></a>
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
                        <a data-toggle="collapse" href="#achats" aria-expanded="false"><i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span></a>
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
                        <a data-toggle="collapse" href="#compta" aria-expanded="false"><i class="fas fa-balance-scale"></i><p>Comptabilité</p><span class="caret"></span></a>
                        <div class="collapse" id="compta">
                            <ul class="nav nav-collapse">
                                <li><a href="{{ route('generalaccounts.index') }}"><span class="sub-item">Plan Comptable</span></a></li>
                                <li><a href="{{ route('payments.index') }}"><span class="sub-item">Règlements</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="collapse" href="#stock" aria-expanded="false"><i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span></a>
                        <div class="collapse" id="stock">
                            <ul class="nav nav-collapse">
                                <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                                <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                                <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="collapse" href="#referentiel" aria-expanded="false"><i class="fas fa-users"></i><p>Référentiel</p><span class="caret"></span></a>
                        <div class="collapse" id="referentiel">
                            <ul class="nav nav-collapse">
                                <li><a href="/customers"><span class="sub-item">Clients</span></a></li>
                                <li><a href="/suppliers"><span class="sub-item">Fournisseurs</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="collapse" href="#parametres" aria-expanded="false"><i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span></a>
                        <div class="collapse" id="parametres">
                            <ul class="nav nav-collapse">
                                <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="collapse" href="#outils" aria-expanded="false"><i class="fab fa-skyatlas"></i><p>Outils</p><span class="caret"></span></a>
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
    <!-- End Sidebar -->

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
                        <!-- Quick Actions -->
                        <li class="nav-item topbar-icon dropdown hidden-caret">
                            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false"><i class="fas fa-layer-group"></i></a>
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
                        <!-- Profil -->
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
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
                                        <form action="{{ route('logout.admin') }}" method="POST" style="display:inline;">
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
                    <div class="alert alert-danger">{!! session('error') !!}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Créer un Document de Vente</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sales.delivery.store') }}" method="POST" id="salesForm">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4 col-12 mb-2">
                                    <div class="btn-group btn-group-sm ms-2 shadow-sm" role="group">
                                        <button type="button" id="openCreateCustomerModal" class="btn btn-success btn-round">
                                            <i class="fas fa-user-plus me-1"></i> Nouveau Client
                                        </button>
                                        <a href="/newcustomer"
                                           onclick="window.open(this.href,'popupWindow','width=1200,height=700,scrollbars=yes'); return false;"
                                           class="btn btn-outline-primary btn-round">
                                            <i class="fas fa-users me-1"></i> Liste des Clients
                                        </a>
                                    </div>
                                    <hr>
                                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                                        <option value="" disabled selected>Sélectionner un client</option>
                                        <option value="%%%" data-select2-id="all-customers">Récupérer tous les clients</option>
                                    </select>
                                    <input type="hidden" name="tva_rate" id="tva_rate" value="0">
                                </div>

                                <div class="col-md-5 col-12 mb-3" id="vehicle_group" style="display:none;">
                                    <label class="form-label fw-semibold text-dark">
                                        Associer un véhicule
                                        <span class="text-muted fw-normal fs-sm">(Automatique via une plaque d'immat.)</span>
                                    </label>
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="plate_search" class="form-control" placeholder="Ex: AB-123-CD" autocomplete="off">
                                        <button type="button" id="searchByPlateBtn" class="btn btn-outline-primary">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                            Rechercher plaque
                                        </button>
                                    </div>
                                    <select name="vehicle_id" id="vehicle_id" class="form-select select2-vehicle" data-placeholder="Sélectionner un véhicule...">
                                        <option value="">Aucun véhicule</option>
                                    </select>
                                    <div class="btn-group mr-2 mt-1" role="group">
                                        <button type="button" class="btn btn-success btn-sm" id="openVehicleModal">
                                            <i class="fas fa-plus fa-fw"></i> <span class="d-none d-md-inline">Nouveau (TECDOC)</span> <i class="fas fa-car"></i>
                                        </button>
                                        <a href="javascript:void(0)" id="loadCatalogBtn" class="btn btn-primary btn-sm" disabled>
                                            <i class="fas fa-book-open fa-fw"></i> <span class="d-none d-lg-inline">Catalogue</span>
                                        </a>
                                        <a href="javascript:void(0)" id="viewHistoryBtn" class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-history fa-fw"></i> <span class="d-none d-lg-inline">Historique Vehicule</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12 mb-2">
                                    <input type="hidden" name="order_date" id="order_date" value="{{ now()->format('Y-m-d') }}" class="form-control order_date" required>
                                </div>
                            </div>

                            {{-- ═══ PATCH 1 : BANDEAU CLIENT UNE LIGNE (remplace #customer_details) ═══ --}}
                            <div id="customer_banner" style="display:none;">
                                <span class="cb-name" id="cb_name">—</span>
                                <span class="cb-sep">|</span>
                                <span><span class="cb-label">Type : </span><span class="cb-val" id="cb_type">—</span></span>
                                <span class="cb-sep">|</span>
                                <span><span class="cb-label">TVA : </span><span class="cb-val" id="cb_tva">—</span>%</span>
                                <span class="cb-sep">|</span>
                                <span>
                                    <button type="button" class="cb-balance-btn" id="balanceBtn" data-customer-id="" data-customer-name="" disabled>
                                        <i class="fas fa-balance-scale" style="font-size:0.72rem;"></i>
                                        Solde : <span id="cb_solde" class="cb-solde-pos">0,00 €</span>
                                    </button>
                                </span>
                                <span class="cb-sep">|</span>
                                <span style="font-size:0.78rem; color:#555;">
                                    <i class="fas fa-envelope" style="font-size:0.70rem;"></i> <span id="cb_email">—</span>
                                    &nbsp;<i class="fas fa-phone" style="font-size:0.70rem;"></i> <span id="cb_phone">—</span>
                                </span>
                            </div>

                            {{-- Ancien #customer_details conservé pour compatibilité JS mais masqué --}}
                            <div class="mb-3" id="customer_details" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Client:</strong> <span id="customer_code"></span> - <span id="customer_name"></span> / <strong>Type:</strong> <span id="customer_type"></span> &#8594; <strong>TVA:</strong> <span id="customer_tva"></span>%</p>
                                        <p><strong>Email:</strong> <span id="customer_email"></span> &#8594; <strong>Téléphones :</strong> <span id="customer_phone1"></span> / <span id="customer_phone2"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Adresse:</strong> <span id="customer_address"></span></p>
                                        <p>
                                            <strong>Solde:</strong>
                                            <button type="button" class="btn btn-outline-info btn-sm balance-btn" id="balanceBtnOld" data-customer-id="" data-customer-name="" disabled>
                                                <i class="fas fa-balance-scale me-1"></i> <span id="customer_balance">0,00 €</span>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- RECHERCHE ARTICLE --}}
                            <div class="mb-2">
                                {{-- LIGNE UNIQUE : input 50% + filtres + boutons côte à côte --}}
                                <div class="d-flex align-items-center gap-2 flex-wrap">

                                    {{-- INPUT environ 50% --}}
                                    <div style="flex: 0 0 46%; min-width: 220px; position:relative;">
                                        <i class="fas fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#90c0e8; font-size:0.82rem; pointer-events:none;"></i>
                                        <input type="text" id="search_item"
                                               class="form-control"
                                               placeholder="Référence, désignation... (min. 4 car.)"
                                               style="padding-left:30px; background:#e8f4fd; border:1.5px solid #90c0e8; border-radius:6px; font-size:0.88rem; transition:all 0.2s; height:34px;">
                                    </div>

                                    {{-- FILTRES MODERNES cachés par défaut --}}
                                    <div id="filter_supplier_wrap" style="display:none;">
                                        <div style="position:relative; display:inline-block;">
                                            <span style="position:absolute;left:7px;top:50%;transform:translateY(-50%);font-size:0.70rem;pointer-events:none;">🏭</span>
                                            <select id="filter_supplier_brand"
                                                style="padding-left:22px; height:34px; font-size:0.78rem; border:1.5px solid #f0a500; border-radius:5px; background:#fffbee; color:#6b4800; min-width:130px; cursor:pointer; outline:none; appearance:none; -webkit-appearance:none;">
                                                <option value="">Fournisseur (tous)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="filter_brand_wrap" style="display:none;">
                                        <div style="position:relative; display:inline-block;">
                                            <span style="position:absolute;left:7px;top:50%;transform:translateY(-50%);font-size:0.70rem;pointer-events:none;">🏷</span>
                                            <select id="filter_brand"
                                                style="padding-left:22px; height:34px; font-size:0.78rem; border:1.5px solid #28a745; border-radius:5px; background:#f0fff4; color:#155724; min-width:120px; cursor:pointer; outline:none; appearance:none; -webkit-appearance:none;">
                                                <option value="">Marque (toutes)</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- BOUTONS sur la même ligne --}}
                                    <div class="d-flex gap-1 flex-wrap" style="margin-left:auto;">
                                        <button type="button" id="add_divers_item" class="btn btn-primary btn-sm btn-round" style="height:34px;">
                                            <i class="fas fa-plus me-1"></i> Créer un article
                                        </button>
                                        <button type="button" id="add_divers_item_consigne" data-type="consigne" class="btn btn-dark btn-sm btn-round" style="height:34px;">
                                            <i class="fas fa-plus me-1"></i> Consigne
                                        </button>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" style="height:34px;">
                                                <i class="fas fa-external-link-alt me-1"></i> Externe
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a class="dropdown-item" href="https://destockpiecesauto.autodata.fr/databox.php#vehicule/immat" target="_blank">DataBox</a></li>
                                                <li><a class="dropdown-item" href="https://aznegoce.inoshop.net/search" target="_blank">AZ</a></li>
                                                <li><a class="dropdown-item" href="https://apcat.eu/" target="_blank">AP</a></li>
                                                <li><a class="dropdown-item" href="https://ottogo.inoshop.net/search" target="_blank">OttoGo</a></li>
                                                <li><a class="dropdown-item" href="https://ksdistribpro.fr/index.php" target="_blank">KS Distrib</a></li>
                                                <li><a class="dropdown-item" href="https://mymga.fr/" target="_blank">MyMGA</a></li>
                                                <li><a class="dropdown-item" href="https://formule1.acrgroup.fr/CCDISP.HTM" target="_blank">Formule 1</a></li>
                                                <li><a class="dropdown-item" href="https://www.idlp.fr/" target="_blank">IDLP</a></li>
                                                <li><a class="dropdown-item" href="https://shopgroupeidlp.fr:5083/CCDISP.HTM" target="_blank">Shop IDLP</a></li>
                                                <li><a class="dropdown-item" href="http://siteweb.cal92.fr/" target="_blank">CAL92</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div id="search_results" class="mt-1"></div>
                            </div>


                            <!-- Modal Détails Article -->
                            <div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg rounded-3">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Détails de l'article</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered table-striped mb-0"><tbody id="itemDetailsBody"></tbody></table>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TABLEAU DES LIGNES --}}
                            <div class="mb-3">
                                <div style="overflow-x:auto;"><div id="lines_table_wrap" style="max-height:420px; overflow-y:auto;">
                                    <table class="table table-striped table-bordered table-text-small" id="lines_table">
                                        <thead class="table-dark text-white">
                                            <tr style="height:42px;">
                                                <th class="py-1">Référence</th>
                                                <th class="py-1">Désignation</th>
                                                <th class="py-1 text-center">
                                                    <div class="small fw-bold">Prix Achat HT</div>
                                                    <div class="text-info" style="font-size:0.63rem; line-height:1;">Remise → Prix Net → Marge</div>
                                                </th>
                                                <th class="py-1 text-center">Stock</th>
                                                <th class="py-1 text-center">Qté</th>
                                                <th class="py-1 text-center">PU HT</th>
                                                <th class="py-1 text-center">PU TTC</th>
                                                <th class="py-1 text-center">Rem %</th>
                                                <th class="py-1 text-center">Total HT</th>
                                                <th class="py-1 text-center">Total TTC</th>
                                                <th class="py-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lines_body"></tbody>
                                    </table>
                                </div></div>{{-- fin lines_table_wrap + overflow-x --}}
                                <div class="total-display mt-2 text-end">
                                    <h6 class="mb-1">Total HT : <span id="total_ht_global" class="text-danger fw-bold">0,00</span> €</h6>
                                    <h5 class="mb-0">Total TTC : <span id="total_ttc_global" class="text-success fw-bold">0,00</span> €</h5>
                                </div>
                                <a href="/articles"
                                   onclick="window.open(this.href,'popupWindow','width=1000,height=700,scrollbars=yes'); return false;"
                                   type="button" class="btn btn-outline-success btn-sm btn-round ms-2">⟰ Liste des Articles</a>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes / Commentaire</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de livraison, etc."></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="action" id="validateBlBtn" value="validate" class="btn btn-primary px-3 ms-2">✔️ Valider BL (Clients En Compte)</button>
                                <button type="submit" name="action" value="save_commande" class="btn btn-danger px-3 ms-2">📝 Editer Commande Vente</button>
                                <button type="submit" name="action" value="save_draft" class="btn btn-warning px-3 ms-2">📝 Editer Devis</button>
                                <button type="submit" name="action" value="validate_and_invoice" class="btn btn-success px-3 ms-2">📄 Valider et Facturer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Details Modal -->
        <div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="stockDetailsModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <h6><i class="fas fa-warehouse me-1"></i> Quantité actuelle par magasin :</h6>
                        <table class="table table-bordered table-sm" id="stockTable">
                            <thead class="table-light"><tr><th>Magasin</th><th>Quantité</th><th>Dernière mise à jour</th></tr></thead>
                            <tbody id="stockTableBody"></tbody>
                        </table>
                        <hr>
                        <h6><i class="fas fa-exchange-alt me-1"></i> Mouvements de stock récents :</h6>
                        <table class="table table-bordered table-sm" id="movementTable">
                            <thead class="table-light"><tr><th>Type</th><th>QTE</th><th>Magasin</th><th>Prix.HT</th><th>Source</th><th>Référence</th></tr></thead>
                            <tbody id="movementTableBody"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accounting Modal -->
        <div class="modal fade accounting-modal" id="accountingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accountingModalLabel">Historique des écritures comptables</h5>
                        <button type="button" class="btn btn-outline-info btn-sm ms-2" onclick="showBalance()">
                            <i class="fas fa-balance-scale me-1"></i> Balance
                        </button>
                        <button type="button" class="close ms-auto" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="balanceSummary" class="card mb-3" style="display:none;">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Balance Comptable</h6>
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light"><tr><th>Total Débits</th><th>Total Crédits</th><th>Solde Net</th></tr></thead>
                                    <tbody><tr><td id="debits">0,00 €</td><td id="credits">0,00 €</td><td id="balance">0,00 €</td></tr></tbody>
                                </table>
                            </div>
                        </div>
                        <form id="accountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                            <select name="type" class="form-select form-select-sm" style="width:200px;">
                                <option value="">Type (Tous)</option>
                                <option value="Factures">Factures</option>
                                <option value="Avoirs">Avoirs</option>
                                <option value="Règlements">Règlements</option>
                            </select>
                            <input type="date" name="start_date" class="form-control form-control-sm" style="width:150px;">
                            <input type="date" name="end_date" class="form-control form-control-sm" style="width:150px;">
                            <button type="submit" class="btn btn-outline-primary btn-sm px-3"><i class="fas fa-filter me-1"></i> Filtrer</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAccountingFilter()"><i class="fas fa-undo me-1"></i> Réinitialiser</button>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover accounting-table">
                                <thead class="table-dark"><tr><th>Type</th><th>Num Document / Lettrage</th><th>Date</th><th>Montant TTC</th><th>Statut</th></tr></thead>
                                <tbody id="accountingEntries"><tr><td colspan="5" class="text-center">Chargement...</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
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
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
window.suppliersList = @json($suppliersForSelect2);
console.log('Fournisseurs chargés :', window.suppliersList);
</script>

<script>
$(document).ready(function () {

// === ARRONDISSEMENT & FORMATAGE ===
function round(number, decimals) {
    decimals = (decimals === undefined) ? 2 : decimals;
    var factor = Math.pow(10, decimals);
    return Math.round((number * factor) + Number.EPSILON) / factor;
}
function formatFrench(number, decimals) {
    decimals = (decimals === undefined) ? 2 : decimals;
    return round(number, decimals).toLocaleString('fr-FR', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}

// === SELECT2 CLIENT ===
$('#customer_id').select2({
    width: '100%',
    placeholder: 'Rechercher un client',
    allowClear: true,
    minimumInputLength: 0,
    ajax: {
        url: '{{ route("customers.search") }}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            var query = params.term || '';
            if ($('#customer_id').val() === '%%%') query = '%%%';
            return { query: query };
        },
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return { id:item.id, text:item.text, tva:item.tva, solde:item.solde, code:item.code, name:item.name, email:item.email, phone1:item.phone1, phone2:item.phone2, address:item.address, address_delivery:item.address_delivery, city:item.city, country:item.country, type:item.type, blocked:item.blocked, disabled:item.disabled };
                })
            };
        },
        cache: true
    },
    templateResult: function (data) {
        if (!data.id) return data.text;
        return $('<span' + (data.disabled ? ' class="select2-results__option--disabled"' : '') + (data.id === '%%%' ? ' class="select2-results__option--all-customers"' : '') + '>' + data.text + (data.blocked && data.id !== '%%%' ? ' <span class="badge bg-danger badge-very-sm">&#x1F512;</span>' : '') + '</span>');
    },
    templateSelection: function (data) {
        if (!data.id) return data.text || 'Sélectionner un client';
        return $('<span>' + (data.text || data.name) + (data.blocked && data.id !== '%%%' ? ' <span class="badge bg-danger badge-very-sm">&#x1F512;</span>' : '') + '</span>');
    }
});

var tvaRates = @json($tvaRates);
var lineCount = 0;
var accountingEntriesCache = {};

$('#vehicle_group').hide();

// === TOTAUX GLOBAUX ===
function updateGlobalTotals() {
    var totalHtGlobal = 0, totalTtcGlobal = 0;
    $('#lines_body tr').each(function () {
        var ht  = parseFloat($(this).find('.total_ht').text().replace(/[^\d,\.-]/g,'').replace(',','.'))  || 0;
        var ttc = parseFloat($(this).find('.total_ttc').text().replace(/[^\d,\.-]/g,'').replace(',','.')) || 0;
        totalHtGlobal  += round(ht);
        totalTtcGlobal += round(ttc);
    });
    totalHtGlobal  = round(totalHtGlobal,  2);
    totalTtcGlobal = round(totalTtcGlobal, 2);
    $('#total_ht_global').text(formatFrench(totalHtGlobal));
    $('#total_ttc_global').text(formatFrench(totalTtcGlobal));
}

// === CATALOGUE / HISTORIQUE ===
function updateCatalogButton() {
    var customerId = $('#customer_id').val();
    var vehicleId  = $('#vehicle_id').val();
    var $btn = $('#loadCatalogBtn');
    if (customerId && vehicleId && !$('#vehicle_id').prop('disabled') && customerId !== '%%%') {
        $btn.attr('href', '/customers/' + customerId + '/vehicles/' + vehicleId + '/catalog').removeAttr('disabled').off('click').on('click', function (e) { e.preventDefault(); window.open(this.href, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); return false; });
    } else {
        $btn.removeAttr('href').attr('disabled', 'disabled');
    }
}
function updateHistoryButton() {
    var vehicleId  = $('#vehicle_id').val();
    var customerId = $('#customer_id').val();
    var $btn = $('#viewHistoryBtn');
    if (vehicleId && customerId && customerId !== '%%%') {
        $btn.removeAttr('disabled').off('click').on('click', function (e) { e.preventDefault(); window.open('/vehicles/' + vehicleId + '/history', 'popupWindow', 'width=1000,height=700,scrollbars=yes'); return false; });
    } else {
        $btn.attr('disabled', 'disabled');
    }
}

// === CHANGEMENT CLIENT → BANDEAU COMPACT ===
$('#customer_id').on('change', function () {
    var customerId   = $(this).val();
    var selectedData = $(this).select2('data')[0];

    // BL interdit particulier
    var customerType = (selectedData && selectedData.type) ? selectedData.type.toLowerCase().trim() : '';
    var $validateBtn = $('#validateBlBtn');
    if (customerType === 'particulier') {
        $validateBtn.prop('disabled', true).removeClass('btn-primary').addClass('btn-primary').attr('title', 'Non autorisé pour les clients Particuliers');
        $validateBtn.html('✔️ Valider BL <span class="badge bg-light text-dark ms-1">Interdit pour les Particuliers</span>');
    } else {
        $validateBtn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary').removeAttr('title');
        $validateBtn.html('✔️ Valider BL (Clients En Compte)');
    }

    if (customerId && selectedData && customerId !== '%%%') {
        var tvaRate = parseFloat(selectedData.tva  || 0);
        var solde   = parseFloat(selectedData.solde || 0);

        // ── PATCH 1 : alimentation du bandeau compact ──
        $('#cb_name').text(selectedData.name  || selectedData.text || '—');
        $('#cb_type').text(selectedData.type  || '—');
        $('#cb_tva').text(tvaRate.toFixed(2));
        $('#cb_email').text(selectedData.email  || '—');
        $('#cb_phone').text((selectedData.phone1 || '—') + (selectedData.phone2 ? ' / ' + selectedData.phone2 : ''));
        var $soldeSpan = $('#cb_solde');
        $soldeSpan.text(solde.toFixed(2).replace('.', ',') + ' €').removeClass('cb-solde-pos cb-solde-neg').addClass(solde >= 0 ? 'cb-solde-pos' : 'cb-solde-neg');
        $('#balanceBtn').attr('data-customer-id', customerId).attr('data-customer-name', selectedData.name || '').removeAttr('disabled');
        $('#customer_banner').css('display', 'flex');

        // ancien bloc (masqué mais IDs toujours remplis pour compatibilité)
        $('#customer_name').text(selectedData.name || 'N/A');
        $('#customer_code').text(selectedData.code || 'N/A');
        $('#customer_tva').text(tvaRate.toFixed(2));
        $('#customer_email').text(selectedData.email || 'N/A');
        $('#customer_phone1').text(selectedData.phone1 || 'N/A');
        $('#customer_phone2').text(selectedData.phone2 || 'N/A');
        $('#customer_address').text(selectedData.address || 'N/A');
        $('#customer_type').text(selectedData.type || 'N/A');
        $('#tva_rate').val(tvaRate);

        // Véhicules
        $('#vehicle_group').show();
        var $vehicleSelect = $('#vehicle_id');
        $vehicleSelect.empty().append('<option value="">Aucun véhicule</option>');
        $vehicleSelect.prop('disabled', false).addClass('vehicle-empty');
        $.ajax({
            url: '/customers/' + customerId + '/vehicles', method: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    data.forEach(function (vehicle) {
                        $vehicleSelect.append('<option value="' + vehicle.id + '">' + vehicle.license_plate + ' (' + vehicle.brand_name + ' ' + vehicle.model_name + ' - ' + vehicle.engine_description + ')</option>');
                    });
                } else {
                    $vehicleSelect.append('<option value="" disabled>(Aucun véhicule enregistré)</option>');
                }
                $vehicleSelect.prop('disabled', false).removeClass('vehicle-empty');
                $vehicleSelect.select2({ width: '100%', placeholder: 'Aucun véhicule', allowClear: true });
                updateCatalogButton();
                updateHistoryButton();
            },
            error: function () {
                Swal.fire('Erreur', 'Impossible de charger les véhicules.', 'error');
                updateCatalogButton(); updateHistoryButton();
            }
        });

        if (tvaRate === 0 && selectedData.tva == null) {
            Swal.fire('Erreur', 'Taux TVA non défini pour ce client.', 'error');
        }
    } else {
        $('#balanceBtn').attr('disabled', 'disabled').removeAttr('data-customer-id').removeAttr('data-customer-name');
        $('#cb_solde').text('0,00 €').removeClass('cb-solde-pos cb-solde-neg').addClass('cb-solde-pos');
        $('#customer_banner').hide();
        $('#customer_details').hide();
        $('#tva_rate').val(0);
        $('#vehicle_group').hide();
        $('#vehicle_id').empty().append('<option value="" disabled selected>Aucun véhicule disponible</option>').addClass('vehicle-empty');
        $('#vehicle_id').select2({ width: '100%', placeholder: 'Aucun véhicule', allowClear: true });
        updateCatalogButton(); updateHistoryButton();
    }

    $('#lines_body tr').each(function () { updateGlobalTotals(); });
    updateGlobalTotals();
});

$('#vehicle_id').on('change', function () {
    if ($(this).val()) $(this).removeClass('vehicle-empty');
    else $(this).addClass('vehicle-empty');
    updateCatalogButton(); updateHistoryButton();
});

// === RECHERCHE ARTICLE ===
var searchTimeout;
$('#search_item').on('input', function () {
    clearTimeout(searchTimeout);
    var query = $(this).val();
    if (query.length > 3) {
        $('#filter_supplier_wrap').fadeIn(200);
        $('#filter_brand_wrap').fadeIn(200);
        searchTimeout = setTimeout(function () {
            var filterSupplier = $('#filter_supplier_brand').val();
            var filterBrand    = $('#filter_brand').val();
            $.ajax({
                url: '{{ route("sales.items.search") }}', method: 'GET', data: { query: query },
                success: function (data) {
                    var currentSupplier = $('#filter_supplier_brand').val();
                    var existingSuppliers = new Set(['']);
                    $('#filter_supplier_brand option').each(function () { existingSuppliers.add($(this).val()); });
                    var currentBrand = $('#filter_brand').val();
                    var existingBrands = new Set(['']);
                    $('#filter_brand option').each(function () { existingBrands.add($(this).val()); });
                    data.forEach(function (item) {
                        if (item.supplier && !existingSuppliers.has(item.supplier)) { $('#filter_supplier_brand').append('<option value="' + item.supplier + '">🏭 ' + item.supplier + '</option>'); existingSuppliers.add(item.supplier); }
                        if (item.brand    && !existingBrands.has(item.brand))       { $('#filter_brand').append('<option value="' + item.brand + '">🏷 ' + item.brand + '</option>'); existingBrands.add(item.brand); }
                    });
                    $('#filter_supplier_brand').val(currentSupplier);
                    $('#filter_brand').val(currentBrand);
                    var filteredData = data.filter(function (item) {
                        return (!filterSupplier || item.supplier === filterSupplier) && (!filterBrand || item.brand === filterBrand);
                    });
                    var results = $('#search_results').empty();
                    if (filteredData.length === 0) { results.append('<div class="p-2 text-muted">Aucun article trouvé.</div>'); return; }
                    filteredData.forEach(function (item) {
                        var brandBadge = item.brand ? '<span class="badge text-bg-primary ms-1" style="font-size:0.7rem;">🏷 ' + item.brand + '</span>' : '';
                        var stockHtml = item.stock_quantity > 0
                            ? '<br><button class="btn btn-xs btn-outline-primary voir-details px-2 py-1" style="font-size:0.75rem;" data-item=\'' + JSON.stringify(item) + '\'><i class="fas fa-eye me-1"></i> Détails Article</button> 🟢 ' + item.stock_quantity + ' En Stock'
                            : '<br><button class="btn btn-xs btn-outline-primary voir-details px-2 py-1" style="font-size:0.75rem;" data-item=\'' + JSON.stringify(item) + '\'><i class="fas fa-eye me-1"></i> Détails Article</button> 🔴 Disponible auprès de <span class="badge text-bg-secondary">' + item.supplier + '</span> au prix de <span class="badge text-bg-success">' + item.cost_price + ' € HT</span>';
                        results.append(
                            '<div class="p-2 border-b cursor-pointer"' +
                            ' data-code="' + item.code + '"' +
                            ' data-name="' + item.name + '"' +
                            ' data-price="' + item.sale_price + '"' +
                            ' data-cost-price="' + item.cost_price + '"' +
                            ' data-stock="' + (item.stock_quantity || 0) + '"' +
                            ' data-location="' + (item.location || '') + '"' +
                            ' data-discount-rate="' + (item.discount_rate || 20) + '"' +
                            ' data-discount-rate-jobber="' + (item.discount_rate_jobber || 0) + '"' +
                            ' data-discount-rate-professionnel="' + (item.discount_rate_professionnel || 0) + '"' +
                            ' data-is-active="' + item.is_active + '"' +
                            ' data-supplier-id="' + (item.supplier_id || '') + '">' +
                            '<span class="badge rounded-pill text-bg-light"><b>' + item.code + '</b>' +
                            '<button class="btn btn-xs btn-outline-secondary copy-code px-1 py-0" data-code="' + item.code + '" title="Copier"><i class="fas fa-copy"></i></button></span>' +
                            ' ' + brandBadge + ' &#8660; ' + item.name + ' : ' + item.sale_price + ' € HT' +
                            stockHtml + '</div><hr class="my-1">'
                        );
                    });
                    $('.copy-code').off('click').on('click', function (e) {
                        e.stopPropagation();
                        var code = $(this).data('code');
                        navigator.clipboard.writeText(code).then(function () {
                            var btn = $(this);
                            var orig = btn.html();
                            btn.html('<i class="fas fa-check text-success"></i>').prop('disabled', true);
                            setTimeout(function () { btn.html(orig).prop('disabled', false); }, 1000);
                        }.bind(this));
                    });
                }
            });
        }, 300);
    } else {
        $('#filter_supplier_wrap').fadeOut(150); $('#filter_supplier_brand').val('').find('option:not(:first)').remove();
        $('#filter_brand_wrap').fadeOut(150); $('#filter_brand').val('').find('option:not(:first)').remove();
        $('#search_results').empty();
    }
});
$('#filter_supplier_brand, #filter_brand').on('change', function () { $('#search_item').trigger('input'); });

// Détails article
$(document).on('click', '.voir-details', function (e) {
    e.stopPropagation();
    var item = JSON.parse($(this).attr('data-item'));
    var html = '<tr><th>Code</th><td>' + (item.code||'-') + '</td></tr><tr><th>Nom</th><td>' + (item.name||'-') + '</td></tr><tr><th>Prix Achat</th><td>' + (item.cost_price||'-') + ' € HT</td></tr><tr><th>Prix Vente</th><td>' + (item.sale_price||'-') + ' € HT</td></tr><tr><th>Stock</th><td>' + (item.stock_quantity||0) + '</td></tr><tr><th>Poids</th><td>' + (item.Poids||'-') + '</td></tr><tr><th>Hauteur</th><td>' + (item.Hauteur||'-') + '</td></tr><tr><th>Longueur</th><td>' + (item.Longueur||'-') + '</td></tr><tr><th>Largeur</th><td>' + (item.Largeur||'-') + '</td></tr><tr><th>Réf TecDoc</th><td>' + (item.Ref_TecDoc||'-') + '</td></tr><tr><th>Code Pays</th><td>' + (item.Code_pays||'-') + '</td></tr><tr><th>Code Douane</th><td>' + (item.Code_douane||'-') + '</td></tr><tr><th>Fournisseur</th><td>' + (item.supplier||'-') + '</td></tr><tr><th>Marque</th><td>' + (item.brand||'-') + '</td></tr><tr><th>Emplacement</th><td>' + (item.location||'-') + '</td></tr><tr><th>État</th><td>' + (item.is_active ? '✅ Actif' : '❌ Inactif') + '</td></tr>';
    $('#itemDetailsBody').html(html);
    $('#itemDetailsModal').modal('show');
});

// === SELECT2 FOURNISSEURS ===
function initSupplierSelect($select, supplierId) {
    $select.select2({ width: '100%', placeholder: 'Fournisseur', allowClear: true, data: window.suppliersList });
    if (supplierId) $select.val(supplierId).trigger('change');
}

// ═══════════════════════════════════════════════════════════════
// PATCH 2 — AJOUT LIGNE : référence et désignation avec classes
// Les nouvelles classes .ref-code et .des-name déclenchent le CSS
// ═══════════════════════════════════════════════════════════════
$(document).on('click', '#search_results div', function () {
    var customerId = $('#customer_id').val();
    if (!customerId || customerId === '%%%') { Swal.fire('Erreur', 'Veuillez sélectionner un client valide.', 'error'); return; }
    var selectedData = $('#customer_id').select2('data')[0];
    var customerType = (selectedData && selectedData.type) ? selectedData.type.toLowerCase() : '';
    var tvaRate = parseFloat(selectedData && selectedData.tva ? selectedData.tva : 0);
    if (tvaRate === 0 && selectedData && selectedData.tva == null) { Swal.fire('Erreur', 'Taux TVA non défini.', 'error'); return; }

    var code       = $(this).data('code');
    var name       = $(this).data('name');
    var price      = parseFloat($(this).data('price'))      || 0;
    var costPrice  = parseFloat($(this).data('cost-price')) || 0;
    var stock      = parseFloat($(this).data('stock'))      || 0;
    var location   = $(this).data('location') || '-';
    var isActive   = $(this).data('is-active') ? 1 : 0;
    var rateGeneral = parseFloat($(this).data('discount-rate'))               || 0;
    var rateJobber  = parseFloat($(this).data('discount-rate-jobber'))        || 0;
    var ratePro     = parseFloat($(this).data('discount-rate-professionnel')) || 0;
    var appliedDiscount = rateGeneral;
    if (customerType.indexOf('professionnel') !== -1) appliedDiscount = ratePro;
    else if (customerType.indexOf('jobber') !== -1)   appliedDiscount = rateJobber;

    var unitPriceHt  = price.toFixed(2);
    var unitPriceTtc = (price * (1 + tvaRate / 100)).toFixed(2);

    var row =
        '<tr data-line-id="' + lineCount + '">' +
        // ── RÉFÉRENCE : classe .ref-code pour le CSS ──
        '<td>' +
            '<div style="display:flex;align-items:center;gap:3px;">' +
                '<span class="ref-code" title="' + code + '">' + code + '</span>' +
                '<button type="button" class="btn btn-xs btn-outline-secondary copy-line-code px-1 py-0" data-code="' + code + '" title="Copier la référence" style="flex-shrink:0;font-size:0.65rem;padding:1px 4px;border-radius:3px;"><i class="fas fa-copy"></i></button>' +
            '</div>' +
            '<input type="hidden" name="lines[' + lineCount + '][article_code]" value="' + code + '">' +
        '</td>' +
        // ── DÉSIGNATION : classe .des-name pour le CSS ──
        '<td><span class="des-name">' + name + '</span></td>' +
        // ── BLOC PRIX ACHAT (identique à l'original) ──
        '<td class="p-1">' +
            '<div class="purchase-price-block">' +
                '<div class="d-flex gap-1 align-items-center mb-1">' +
                    '<div class="input-group input-group-sm flex-fill">' +
                        '<span class="input-group-text">€</span>' +
                        '<input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input" value="' + costPrice.toFixed(2) + '" name="lines[' + lineCount + '][unit_coast]">' +
                    '</div>' +
                    '<select class="form-select form-select-sm supplier-select" name="lines[' + lineCount + '][supplier_id]"><option value="">Fournisseur</option></select>' +
                '</div>' +
                '<div class="d-flex gap-1 align-items-center justify-content-between">' +
                    '<div class="input-group input-group-sm" style="width:105px;">' +
                        '<input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm text-end purchase-discount" value="0" name="lines[' + lineCount + '][discount_coast]">' +
                        '<span class="input-group-text">%</span>' +
                    '</div>' +
                    '<span class="text-muted small">→</span>' +
                    '<span class="fw-bold text-success net-price">0,00 €</span>' +
                '</div>' +
                '<small class="text-muted d-block text-end mt-1">Marge nette : <span class="margin-display text-primary fw-bold">0%</span> (<span class="margin-euro text-primary">0,00 €</span>)</small>' +
            '</div>' +
        '</td>' +
        '<td>' +
            '<button type="button" class="btn btn-outline-primary btn-sm stock-details-btn" data-toggle="modal" data-target="#stockDetailsModal" data-code="' + code + '" data-name="' + name + '">' + stock.toFixed(0) + '</button>' +
            '<br><small class="text-muted" style="font-size:0.7rem;">' + location + '</small>' +
        '</td>' +
        '<td><input type="number" name="lines[' + lineCount + '][ordered_quantity]" class="form-control quantity" value="1" min="0"></td>' +
        '<td><input type="number" inputmode="decimal" name="lines[' + lineCount + '][unit_price_ht]" class="form-control unit_price_ht" value="' + unitPriceHt + '" step="0.01"></td>' +
        '<td><input type="number" inputmode="decimal" name="lines[' + lineCount + '][unit_price_ttc]" class="form-control unit_price_ttc" value="' + unitPriceTtc + '" step="0.01"></td>' +
        '<td><input type="number" name="lines[' + lineCount + '][remise]" class="form-control remise" value="' + appliedDiscount.toFixed(2) + '" min="0" max="100" step="0.01"></td>' +
        '<td class="text-right total_ht">0,00</td>' +
        '<td class="text-right total_ttc">0,00</td>' +
        '<td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>' +
        '</tr>';

    var supplierId = $(this).data('supplier-id') || '';
    $('#lines_body').append(row);
    var $newRow = $('#lines_body tr:last');
    initSupplierSelect($newRow.find('.supplier-select'), supplierId);
    updatePurchaseMargin($newRow);
    updateLineTotals($newRow, tvaRate);
    $(document).on('click', '.copy-line-code', function (e) {
        e.stopPropagation();
        navigator.clipboard.writeText($(this).data('code'));
    });
    updateLineTotals($('#lines_body tr:last'), tvaRate);
    lineCount++;
    $('#search_item').val('');
    $('#search_results').empty();
    $('#filter_supplier_wrap').fadeOut(150); $('#filter_supplier_brand').val('').find('option:not(:first)').remove();
    $('#filter_brand_wrap').fadeOut(150); $('#filter_brand').val('').find('option:not(:first)').remove();
    updateGlobalTotals();
});

// Suppression ligne
$(document).on('click', '.remove_line', function () {
    $(this).closest('tr').remove();
    updateGlobalTotals();
});

// Recalcul sur saisie
$(document).on('input', '.unit_price_ht, .unit_price_ttc, .quantity, .remise', function () {
    var row = $(this).closest('tr');
    var tvaRate = $('#tva_rate').val() || 0;
    if ($(this).hasClass('unit_price_ht'))  row.data('last-modified', 'ht');
    if ($(this).hasClass('unit_price_ttc')) row.data('last-modified', 'ttc');
    updateLineTotals(row, tvaRate);
});

// === MARGE ===
var negativeMarginTimeout = null;
var negativeMarginToastShown = null;

function updatePurchaseMargin(row) {
    if (row.data('is-consigne') == '1') {
        row.find('.net-price').text('—'); row.find('.margin-display').text('—'); row.find('.margin-euro').text('—'); return;
    }
    var costPrice        = parseFloat(row.find('.cost-price-input').val().replace(',','.'))   || 0;
    var purchaseDiscount = parseFloat(row.find('.purchase-discount').val().replace(',','.'))  || 0;
    var saleDiscount     = parseFloat(row.find('.remise').val().replace(',','.'))             || 0;
    var salePriceHt      = parseFloat(row.find('.unit_price_ht').val().replace(',','.'))      || 0;
    var netCost       = round(costPrice * (1 - purchaseDiscount / 100), 4);
    var realSalePrice = round(salePriceHt * (1 - saleDiscount / 100), 4);
    row.find('.net-price').text(netCost.toFixed(2).replace('.',',') + ' €');
    if (realSalePrice > 0 && netCost > 0) {
        var marginPct = ((realSalePrice - netCost) / netCost) * 100;
        var marginEur = realSalePrice - netCost;
        row.find('.margin-display').text(marginPct.toFixed(1) + '%');
        row.find('.margin-euro').text(marginEur.toFixed(2).replace('.',',') + ' €');
        var $m = row.find('.margin-display').removeClass('text-danger text-warning text-success');
        if (marginPct >= 50) $m.addClass('text-success');
        else if (marginPct >= 30) $m.addClass('text-warning');
        else $m.addClass('text-danger');
        if (marginPct < 0) {
            if (negativeMarginTimeout) clearTimeout(negativeMarginTimeout);
            if (negativeMarginToastShown) negativeMarginToastShown.close();
            negativeMarginTimeout = setTimeout(function () {
                var code = row.find('td:nth-child(1) .ref-code').text().trim() || '???';
                var name = row.find('td:nth-child(2) .des-name').text().trim();
                var shortName = name.length > 45 ? name.substring(0,42)+'...' : name;
                negativeMarginToastShown = Swal.fire({
                    toast: true, position: 'top-end', icon: 'warning', title: 'VENTE À PERTE !',
                    html: '<div class="text-start small ms-3"><b>' + code + '</b> – ' + shortName + '<br>Achat net : <b>' + netCost.toFixed(2) + ' €</b> → Vente net HT : <b>' + realSalePrice.toFixed(2) + ' €</b><br><span class="text-danger fw-bold">Perte : ' + Math.abs(marginEur).toFixed(2) + ' € (' + marginPct.toFixed(1) + '%)</span></div>',
                    showConfirmButton: false, timer: 8000, timerProgressBar: true, background: '#fff8e1',
                    didClose: function () { negativeMarginToastShown = null; }
                });
                negativeMarginTimeout = null;
            }, 1700);
        } else {
            if (negativeMarginTimeout) { clearTimeout(negativeMarginTimeout); negativeMarginTimeout = null; }
            if (negativeMarginToastShown) { negativeMarginToastShown.close(); negativeMarginToastShown = null; }
        }
    } else {
        row.find('.margin-display').text('—'); row.find('.margin-euro').text('—');
        if (negativeMarginTimeout) { clearTimeout(negativeMarginTimeout); negativeMarginTimeout = null; }
        if (negativeMarginToastShown) { negativeMarginToastShown.close(); negativeMarginToastShown = null; }
    }
}
$(document).on('input change', '.remise, .cost-price-input, .purchase-discount, .unit_price_ht, .unit_price_ttc', function () { updatePurchaseMargin($(this).closest('tr')); });

// === TOTAUX LIGNE ===
function updateLineTotals(row, tvaRate) {
    tvaRate = parseFloat(tvaRate) || 0;
    var quantity = parseFloat(row.find('.quantity').val().replace(',','.'))    || 0;
    var remise   = parseFloat(row.find('.remise').val().replace(',','.'))      || 0;
    var $puHt    = row.find('.unit_price_ht');
    var $puTtc   = row.find('.unit_price_ttc');
    var puHt     = parseFloat($puHt.val().replace(',','.'))  || 0;
    var puTtc    = parseFloat($puTtc.val().replace(',','.')) || 0;
    var last     = row.data('last-modified') || 'ht';
    if (last === 'ttc' && puTtc > 0) { puHt = round(puTtc / (1 + tvaRate / 100)); $puHt.val(puHt.toFixed(2)); }
    else if (last === 'ht' && puHt > 0) { puTtc = round(puHt * (1 + tvaRate / 100)); $puTtc.val(puTtc.toFixed(2)); }
    var totalHtAvantRemise = round(puHt * quantity);
    var remiseAmount       = round(totalHtAvantRemise * (remise / 100));
    var totalHt            = round(totalHtAvantRemise - remiseAmount);
    var totalTtc           = round(totalHt * (1 + tvaRate / 100));
    row.find('.total_ht').text(formatFrench(round(totalHt, 2)));
    row.find('.total_ttc').text(formatFrench(round(totalTtc, 2)));
    updateGlobalTotals();
}

// === STOCK DETAILS ===
$(document).on('click', '.stock-details-btn', function (event) {
    event.preventDefault(); event.stopPropagation();
    var code = $(this).data('code'), name = $(this).data('name');
    $('#stockDetailsModalLabel').text('Détail du stock – ' + code + ' : ' + name);
    $('#stockTableBody, #movementTableBody').empty();
    $.ajax({
        url: '{{ route("items.stock.details") }}', method: 'GET', data: { code: code },
        success: function (data) {
            if (data.error) {
                $('#stockTableBody').append('<tr><td colspan="3" class="text-center text-danger">Erreur: ' + data.error + '</td></tr>');
                $('#movementTableBody').append('<tr><td colspan="6" class="text-center text-danger">Erreur: ' + data.error + '</td></tr>');
                return;
            }
            if (data.stocks && data.stocks.length > 0) {
                data.stocks.forEach(function (s) { $('#stockTableBody').append('<tr><td>' + (s.store_name||'-') + '</td><td>' + s.quantity + '</td><td>' + (s.updated_at||'-') + '</td></tr>'); });
            } else { $('#stockTableBody').append('<tr><td colspan="3" class="text-center text-muted">Aucun stock trouvé</td></tr>'); }
            if (data.movements && data.movements.length > 0) {
                data.movements.forEach(function (m) {
                    var cp = parseFloat(m.cost_price), cpf = isNaN(cp) ? '-' : cp.toFixed(2) + ' €';
                    $('#movementTableBody').append('<tr><td><span class="badge bg-' + (m.quantity >= 0 ? 'success' : 'danger') + '">' + (m.type||'?') + '</span><br>' + (m.created_at||'-') + '</td><td>' + (m.quantity||0) + '</td><td>' + (m.store_name||'-') + '</td><td>' + cpf + '</td><td>' + (m.supplier_name||'-') + '</td><td>' + (m.reference||'-') + '</td></tr>');
                });
            } else { $('#movementTableBody').append('<tr><td colspan="6" class="text-center text-muted">Aucun mouvement trouvé</td></tr>'); }
        },
        error: function (xhr) {
            var msg = xhr.status === 404 ? 'Article non trouvé' : 'Erreur serveur: ' + xhr.status;
            $('#stockTableBody').append('<tr><td colspan="3" class="text-center text-danger">' + msg + '</td></tr>');
            $('#movementTableBody').append('<tr><td colspan="6" class="text-center text-danger">' + msg + '</td></tr>');
        }
    });
    $('#stockDetailsModal').modal('show');
});

// === ARTICLE DIVERS ===
$(document).on('click', '#add_divers_item, #add_divers_item_consigne', function () {
    var tvaRate    = parseFloat($('#tva_rate').val()) || 20;
    var i          = lineCount;
    lineCount++;
    var isConsigne = $(this).data('type') === 'consigne';
    var rowHtml =
        '<tr class="divers-line" data-line-id="divers_' + i + '" data-is-consigne="' + (isConsigne ? '1' : '0') + '">' +
        '<td>' +
            '<input type="text" name="lines[' + i + '][article_code]" class="form-control form-control-sm bg-light ref-code" style="font-family:\'Courier New\',monospace; font-weight:800; font-size:0.93rem; color:#003a80;" value="' + (isConsigne ? 'CONSIGNE' : '') + '" placeholder="Réf" ' + (isConsigne ? 'readonly' : '') + '>' +
            '<input type="hidden" name="lines[' + i + '][is_new_item]" value="1">' +
        '</td>' +
        '<td>' +
            '<input type="text" name="lines[' + i + '][item_name]" class="form-control form-control-sm bg-light des-name" style="font-weight:700; font-size:0.88rem; color:#111;" value="' + (isConsigne ? 'CONSIGNE' : '') + '" placeholder="Désignation" ' + (isConsigne ? 'readonly' : '') + '>' +
        '</td>' +
        '<td class="p-1">' +
            '<div class="purchase-price-block' + (isConsigne ? ' d-none' : '') + '">' +
                '<div class="d-flex gap-1 align-items-center mb-1">' +
                    '<div class="input-group input-group-sm flex-fill"><span class="input-group-text">€</span><input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input" value="0.00" name="lines[' + i + '][unit_coast]"></div>' +
                    '<select class="form-select form-select-sm supplier-select" name="lines[' + i + '][supplier_id]" ' + (isConsigne ? 'disabled' : '') + '><option value="">Fournisseur</option></select>' +
                '</div>' +
                '<div class="d-flex gap-1 align-items-center justify-content-between">' +
                    '<div class="input-group input-group-sm" style="width:105px;"><input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm text-end purchase-discount" value="0" name="lines[' + i + '][discount_coast]"><span class="input-group-text">%</span></div>' +
                    '<span class="text-muted small">→</span><span class="fw-bold text-success net-price">0,00 €</span>' +
                '</div>' +
                '<small class="text-muted d-block text-end mt-1">Marge nette : <span class="margin-display text-primary fw-bold">0%</span> (<span class="margin-euro text-primary">0,00 €</span>)</small>' +
            '</div>' +
            (isConsigne ? '<div class="text-center text-muted small">Consigne — Pas de marge</div>' : '') +
        '</td>' +
        '<td><span class="text-muted">-</span></td>' +
        '<td><input type="number" name="lines[' + i + '][ordered_quantity]" class="form-control quantity" value="1" min="1"></td>' +
        '<td><input type="number" inputmode="decimal" step="0.01" name="lines[' + i + '][unit_price_ht]" class="form-control unit_price_ht" value="0.00"></td>' +
        '<td><input type="number" inputmode="decimal" step="0.01" name="lines[' + i + '][unit_price_ttc]" class="form-control unit_price_ttc" value="0.00"></td>' +
        '<td><input type="number" name="lines[' + i + '][remise]" class="form-control remise" value="0" min="0" max="100"></td>' +
        '<td class="text-right total_ht">0,00</td>' +
        '<td class="text-right total_ttc">0,00</td>' +
        '<td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>' +
        '</tr>';
    $('#lines_body').append(rowHtml);
    initSupplierSelect($('#lines_body tr:last').find('.supplier-select'));
    lineCount++;
    updateGlobalTotals();
});

// === SOUMISSION ===
$('#salesForm').on('submit', function () {
    $('#lines_body tr').each(function () {
        var $r = $(this);
        var puHt  = parseFloat($r.find('.unit_price_ht').val().replace(',','.'))  || 0;
        var puTtc = parseFloat($r.find('.unit_price_ttc').val().replace(',','.')) || 0;
        var qty   = parseFloat($r.find('.quantity').val().replace(',','.'))        || 0;
        var remise = parseFloat($r.find('.remise').val().replace(',','.'))         || 0;
        $r.find('.unit_price_ht').val(puHt.toFixed(2));
        $r.find('.unit_price_ttc').val(puTtc.toFixed(2));
        $r.find('.quantity').val(qty.toFixed(2));
        $r.find('.remise').val(remise.toFixed(2));
    });
});

document.getElementById('salesForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var actionValue = e.submitter ? e.submitter.value : null;
    var messages = { 'validate': 'Vous êtes sûr de valider ?', 'validate_and_invoice': 'Vous êtes sûr de facturer ce bon de livraison ?', 'save_draft': 'Vous êtes sûr d\'enregistrer ce devis ?', 'save_commande': 'Vous êtes sûr d\'enregistrer cette commande vente ?' };
    var confirmMessage = messages[actionValue] || '';
    if (confirmMessage && confirm(confirmMessage)) {
        var routes = { 'validate_and_invoice': '{{ route("sales.delivery.store_and_invoice") }}', 'save_draft': '{{ route("devis.store") }}', 'save_commande': '{{ route("commande.store") }}', 'validate': '{{ route("sales.delivery.store") }}' };
        if (routes[actionValue]) this.action = routes[actionValue];
        this.submit();
    }
});

// Balance
$(document).on('click', '#balanceBtn', function () {
    var customerId   = $(this).data('customer-id');
    var customerName = $(this).data('customer-name');
    if (!customerId || customerId === '%%%') { Swal.fire('Erreur', 'Veuillez sélectionner un client valide.', 'error'); return; }
    $('#accountingModalLabel').text('Historique des écritures comptables - ' + customerName);
    var tbody = $('#accountingEntries');
    tbody.html('<tr><td colspan="5" class="text-center">Chargement...</td></tr>');
    $('#balanceSummary').hide();
    if (accountingEntriesCache[customerId]) { applyFilters(customerId); $('#accountingModal').modal('show'); }
    else {
        $.ajax({
            url: '/customers/' + customerId + '/accounting-entries', method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            success: function (data) { accountingEntriesCache[customerId] = data.entries || []; applyFilters(customerId); $('#accountingModal').modal('show'); },
            error: function () { tbody.html('<tr><td colspan="5" class="text-center text-danger">Erreur chargement</td></tr>'); Swal.fire('Erreur', 'Impossible de charger les écritures.', 'error'); }
        });
    }
});

$('#accountingFilterForm').on('submit', function (e) { e.preventDefault(); var cid = $('#balanceBtn').data('customer-id'); if (cid) applyFilters(cid); });
window.resetAccountingFilter = function () { var cid = $('#balanceBtn').data('customer-id'); $('#accountingFilterForm')[0].reset(); $('#balanceSummary').hide(); if (cid) applyFilters(cid); };
window.showBalance = function () { var cid = $('#balanceBtn').data('customer-id'); if (cid) { $('#balanceSummary').show(); applyFilters(cid); } };

function applyFilters(customerId) {
    var tbody = $('#accountingEntries');
    var fd = new FormData($('#accountingFilterForm')[0]);
    var typeFilter = fd.get('type') || '';
    var startDate  = fd.get('start_date') ? new Date(fd.get('start_date')) : null;
    var endDate    = fd.get('end_date')   ? new Date(fd.get('end_date'))   : null;
    var entries = (accountingEntriesCache[customerId] || []).filter(function (entry) {
        if (typeFilter) { if (typeFilter === 'Factures' && entry.type !== 'Facture') return false; if (typeFilter === 'Avoirs' && entry.type !== 'Avoir') return false; if (typeFilter === 'Règlements' && (entry.type === 'Facture' || entry.type === 'Avoir')) return false; }
        if (startDate || endDate) { if (!entry.date || entry.date === '-') return false; var p = entry.date.split('/'); var ed = new Date(p[2]+'-'+p[1]+'-'+p[0]); if (startDate && ed < startDate) return false; if (endDate && ed > endDate) return false; }
        return true;
    });
    var debits = 0, credits = 0;
    entries.forEach(function (e) { if (e.type === 'Facture') debits += parseFloat(e.amount)||0; else credits += parseFloat(e.amount)||0; });
    var balance = debits - credits;
    $('#debits').text(debits.toFixed(2).replace('.',',') + ' €');
    $('#credits').text(credits.toFixed(2).replace('.',',') + ' €');
    $('#balance').text(balance.toFixed(2).replace('.',',') + ' €').removeClass('text-success text-danger').addClass(balance >= 0 ? 'text-success' : 'text-danger');
    tbody.html('');
    if (entries.length === 0) { tbody.html('<tr><td colspan="5" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>'); return; }
    entries.forEach(function (entry) {
        var row = document.createElement('tr');
        row.innerHTML = '<td>' + (entry.type||'-') + '</td><td>' + (entry.numdoc||entry.reference||'-') + '</td><td>' + (entry.date||'-') + '</td><td>' + (entry.amount !== undefined ? Number(entry.amount).toFixed(2).replace('.',',') : '-') + ' €</td><td>' + (entry.status||'-') + '</td>';
        tbody.append(row);
    });
}

// Sélection auto texte
$(document).on('focus click', '.unit_price_ht, .unit_price_ttc', function () { this.select(); });

}); // end ready
</script>

<!-- Modal Véhicule Rapide -->
<div class="modal fade" id="addVehicleInlineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-car"></i> Associer un nouveau véhicule</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="quickVehicleForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Immatriculation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="quick_license_plate" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Marque <span class="text-danger">*</span></label>
                            <select id="quick_brand_id" class="form-control select2-brand" style="width:100%;" required>
                                <option value="">Rechercher une marque...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="quick_brand_name">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Modèle <span class="text-danger">*</span></label>
                            <select id="quick_model_id" class="form-control select2-model" style="width:100%;" required disabled>
                                <option value="">D'abord choisir une marque</option>
                            </select>
                            <input type="hidden" id="quick_model_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motorisation <span class="text-danger">*</span></label>
                            <select id="quick_engine_id" class="form-control select2-engine" style="width:100%;" required disabled>
                                <option value="">D'abord choisir un modèle</option>
                            </select>
                            <input type="hidden" id="quick_engine_description">
                            <input type="hidden" id="quick_linkage_target_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Créer et sélectionner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$(document).ready(function () {
    var $modal = $('#addVehicleInlineModal');
    var $form  = $('#quickVehicleForm');

    // ── Bouton TecDoc : ouvre le modal via JS (plus fiable que data-toggle) ──
    $('#openVehicleModal').on('click', function () {
        $modal.modal('show');
    });


    function initTecdocSelect2() {
        ['#quick_brand_id','#quick_model_id','#quick_engine_id'].forEach(function (s) { if ($(s).hasClass('select2-hidden-accessible')) $(s).select2('destroy'); });
        $('#quick_brand_id').select2({ width:'100%', placeholder:'Rechercher une marque...', dropdownParent:$modal });
        $('#quick_model_id').select2({ width:'100%', placeholder:'Choisir un modèle', allowClear:true, dropdownParent:$modal });
        $('#quick_engine_id').select2({ width:'100%', placeholder:'Choisir une motorisation', allowClear:true, dropdownParent:$modal });
    }

    $modal.on('shown.bs.modal', function () {
        $form[0].reset();
        $('#quick_brand_name,#quick_model_name,#quick_engine_description,#quick_linkage_target_id').val('');
        $('#quick_model_id,#quick_engine_id').empty().append('<option value="">...</option>').prop('disabled',true);
        initTecdocSelect2();
        $('#quick_license_plate').focus();
    });

    $(document).on('change','#quick_brand_id', function () {
        var brandId = $(this).val();
        $('#quick_brand_name').val($(this).find('option:selected').text());
        var $model = $('#quick_model_id').empty().append('<option value="">Chargement...</option>').prop('disabled',true);
        $('#quick_engine_id').empty().append('<option value="">...</option>').prop('disabled',true);
        if (!brandId) return;
        $.get('{{ route("getModels") }}', { brand_id:brandId }, function (data) {
            $model.empty().append('<option value="">Choisir un modèle</option>');
            data.forEach(function (m) { $model.append('<option value="'+m.id+'" data-name="'+m.name+'">'+m.name+'</option>'); });
            $model.prop('disabled',false);
        });
    });

    $(document).on('change','#quick_model_id', function () {
        var modelId = $(this).val();
        $('#quick_model_name').val($(this).find('option:selected').data('name') || $(this).find('option:selected').text());
        var $engine = $('#quick_engine_id').empty().append('<option value="">Chargement...</option>').prop('disabled',true);
        if (!modelId) return;
        $.get('{{ route("getEngines") }}', { model_id:modelId }, function (data) {
            $engine.empty().append('<option value="">Choisir une motorisation</option>');
            data.forEach(function (e) { $engine.append('<option value="'+e.id+'" data-description="'+e.description+'" data-linking-target-id="'+e.linkageTargetId+'">'+e.description+'</option>'); });
            $engine.prop('disabled',false);
        });
    });

    $(document).on('change','#quick_engine_id', function () {
        var $opt = $(this).find('option:selected');
        $('#quick_engine_description').val($opt.data('description') || $opt.text());
        $('#quick_linkage_target_id').val($opt.data('linking-target-id') || $(this).val());
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        var customerId = $('#customer_id').val();
        if (!customerId || customerId === '%%%') { Swal.fire('Erreur','Sélectionnez un client d\'abord','error'); return; }
        var formData = { _token:csrfToken, license_plate:$.trim($('#quick_license_plate').val()).toUpperCase(), brand_id:$('#quick_brand_id').val(), brand_name:$('#quick_brand_id').find('option:selected').text()||$('#quick_brand_name').val(), model_id:$('#quick_model_id').val(), model_name:$('#quick_model_id').find('option:selected').data('name')||$('#quick_model_id').find('option:selected').text()||$('#quick_model_name').val(), engine_id:$('#quick_engine_id').val(), engine_description:$('#quick_engine_description').val()||$('#quick_engine_id').find('option:selected').text(), linkage_target_id:$('#quick_linkage_target_id').val()||$('#quick_engine_id').val() };
        var required = ['license_plate','brand_id','brand_name','model_id','model_name','engine_id','engine_description','linkage_target_id'];
        for (var f = 0; f < required.length; f++) { if (!formData[required[f]]) { Swal.fire('Champ manquant','Remplissez : ' + required[f].replace(/_/g,' '),'warning'); return; } }
        $.ajax({
            url:'/customers/'+customerId+'/vehicles/quick-store', method:'POST', data:formData,
            success:function(res) { if(res.success) { var text=formData.license_plate+' ('+formData.brand_name+' '+formData.model_name+' - '+formData.engine_description+')'; $('#vehicle_id').append(new Option(text,res.vehicle.id,true,true)).trigger('change'); $modal.modal('hide'); Swal.fire('Succès','Véhicule créé et sélectionné !','success'); } else { Swal.fire('Erreur',res.message||'Erreur inconnue','error'); } },
            error:function(xhr) { var msg='Erreur<br><ul>'; var errors=xhr.responseJSON&&xhr.responseJSON.errors; if(errors){for(var f in errors){msg+='<li>'+f+' : '+errors[f].join(', ')+'</li>';}}else{msg=(xhr.responseJSON&&xhr.responseJSON.message)||'Erreur inconnue';} Swal.fire('Erreur validation',msg,'error'); }
        });
    });

    $('#searchByPlateBtn').on('click', async function () {
        var btn=$('#searchByPlateBtn'), input=$('#plate_search');
        var plate=input.val().trim().toUpperCase().replace(/[^A-Z0-9]/g,'');
        if(!plate||plate.length<4){return Swal.fire('Plaque invalide','Veuillez entrer une plaque correcte','warning');}
        btn.prop('disabled',true).find('.spinner-border').removeClass('d-none');
        input.prop('disabled',true);
        try {
            var customerId=$('#customer_id').val();
            if(!customerId||customerId==='%%%'){throw new Error('Veuillez d\'abord sélectionner un client');}
            var brand='INCONNUE',model='',engine='';
            try { var resp=await fetch('https://api.apiplaqueimmatriculation.com/plaque?immatriculation='+plate+'&token=acce455746476e1d0679a0aa1c4ae93f&pays=FR'); var json=await resp.json(); if(json&&json.data&&!json.data.erreur){brand=json.data.marque||'INCONNUE';model=json.data.modele||'';engine=json.data.sra_commercial||json.data.code_moteur||'';} } catch(e){}
            var query=new URLSearchParams({license_plate:plate,brand_name:brand,model_name:model,engine_description:engine});
            var result=await $.ajax({url:'/customers/'+customerId+'/vehicles/from-plate?'+query,method:'GET',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}});
            if(result.success){$('#vehicle_id').append(new Option(result.vehicle.text,result.vehicle.id,true,true)).trigger('change');Swal.fire({icon:'success',title:'Véhicule ajouté !',text:result.vehicle.text,timer:2500,toast:true,position:'top-end'});input.val('');}
        } catch(err){Swal.fire('Erreur',err.message||'Impossible d\'ajouter le véhicule','error');}
        finally{btn.prop('disabled',false).find('.spinner-border').addClass('d-none');input.prop('disabled',false);}
    });
});
</script>

<script>
$(document).on('click', '#openCreateCustomerModal', function () {
    var popup = window.open('/newcustomer', 'createCustomerPopup', 'width=1200,height=700,scrollbars=yes,resizable=yes');
    var check = setInterval(function () {
        try {
            if (popup.document && popup.document.querySelector('#createItemModal')) {
                clearInterval(check);
                var el = popup.document.querySelector('#createItemModal');
                var m  = new popup.bootstrap.Modal(el);
                m.show();
                setTimeout(function () { var ni = popup.document.querySelector('input[name="name"]'); if (ni) ni.focus(); }, 500);
            }
        } catch (e) {}
    }, 100);
});
</script>

</body>
</html>