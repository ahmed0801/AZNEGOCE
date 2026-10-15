<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Modifier une commande de vente</title>
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
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .card { border-radius: 8px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card-header { background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 8px 8px 0 0; }
        .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-outline-primary, .btn-outline-info {
            font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 6px; transition: all 0.3s ease;
        }
        .table { width: 100%; margin-bottom: 0; background-color: #fff; border-radius: 6px; overflow: hidden; }
        .table th, .table td { padding: 0.5rem; font-size: 0.85rem; vertical-align: middle; }
        .table thead { background: #f8f9fa; position: sticky; top: 0; z-index: 10; }
        .table-responsive { max-height: 350px; overflow-y: auto; }
        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 6px; border: 1px solid #ced4da; padding: 0.4rem; font-size: 0.9rem;
        }
        #customer_details { background: #f8f9fa; border-radius: 6px; padding: 1rem; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05); }
        #search_results { background: #eaf3fc; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); max-height: 180px; overflow-y: auto; }
        .total-display { background: #f8f9fa; border-radius: 6px; padding: 0.8rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .table-text-small th, .table-text-small td, .table-text-small input { font-size: 0.8rem !important; }
        .purchase-price-block { font-size: 0.82rem; line-height: 1.2; }
        .net-price { min-width: 70px; font-weight: 600; color: #28a745 !important; }
        .margin-display { font-weight: 600; }
        #lines_table .quantity, #lines_table .unit_price_ht, #lines_table .unit_price_ttc,
        #lines_table .remise, #lines_table .cost-price-input, #lines_table .purchase-discount {
            width: 62px !important; min-width: 62px !important; max-width: 62px !important;
            padding: 0.25rem 0.35rem !important; font-size: 0.80rem !important; text-align: center;
        }
        @media (max-width: 768px) {
            #lines_table .quantity, #lines_table .unit_price_ht, #lines_table .unit_price_ttc,
            #lines_table .remise, #lines_table .cost-price-input, #lines_table .purchase-discount {
                width: 55px !important; font-size: 0.75rem !important;
            }
        }
        #lines_table thead th { padding: 0.35rem 0.5rem !important; font-size: 0.80rem !important; height: 42px !important; }
        #vehicle_group { display: none; }

        #customer_banner{display:flex;flex-wrap:wrap;align-items:center;gap:0 14px;font-size:.82rem;line-height:1.8;background:#f0f7ff;border:1px solid #b8d4f0;border-left:4px solid #0056b3;border-radius:5px;padding:5px 12px;margin-bottom:10px}
        #customer_banner .cb-name{font-weight:700;color:#003a80;font-size:.88rem}
        #customer_banner .cb-sep{color:#aac4e0}
        #customer_banner .cb-label{color:#666}
        #customer_banner .cb-val{font-weight:600;color:#222}
        .badge-very-sm{font-size:.6rem;padding:.15em .4em;vertical-align:middle}
        .btn-xs{font-size:.7rem;line-height:1.2}
        #lines_table td:nth-child(1){min-width:0;max-width:140px;width:125px}
        #lines_table .ref-code{display:block;font-family:'Courier New',Consolas,monospace;font-weight:900;font-size:1.05rem;color:#0040c0;letter-spacing:.04em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:105px;line-height:1.35;background:#eef4ff;padding:2px 6px;border-radius:3px;border-left:3px solid #0040c0}
        #lines_table td:nth-child(2){min-width:160px;max-width:240px}
        #lines_table td:nth-child(2) .des-name{display:block;font-weight:700;font-size:.88rem;color:#111;line-height:1.35;white-space:normal;word-break:break-word}
        #lines_table tbody td{padding:5px 6px!important;vertical-align:middle}
        #lines_table thead th{position:sticky;top:0;z-index:2;background:#1a2b4a!important}
        #lines_table th.text-center,#lines_table td.text-right{text-align:center!important}
        #vehicle_group{display:none}
        .form-control.quantity,.form-control.unit_price_ht,.form-control.remise{font-size:.9rem}
        /* ============================================================
           REFONTE PAGE CRÉATION — en-tête, recherche, tableau, barre d'actions
           ============================================================ */
        .lh-bar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
        .lh-title{margin:0;font-weight:700;display:flex;align-items:center;gap:.5rem}
        .lh-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-btns .btn,.lh-filters .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:0 1rem;font-size:.88rem;font-weight:600;border-radius:8px;white-space:nowrap;margin:0}
        .lh-btns .dropdown{position:relative}
        .lh-filters{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;margin-bottom:.5rem}
        .lh-filters .lh-field{flex:1 1 150px;min-width:130px;position:relative}
        .lh-filters .lh-wide{flex:2 1 260px}
        .lh-filters .lh-field i.lh-ico{position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;z-index:2}
        .lh-filters .form-control{height:38px;border-radius:8px;font-size:.88rem}
        .lh-filters #search_item{padding-left:2.1rem;background:#e8f4fd;border:1.5px solid #90c0e8}
        .lh-filters #search_item:focus{background:#fff;border-color:#007bff;box-shadow:0 0 0 3px rgba(0,123,255,.12);outline:none}
        .lh-filters .select2-container{width:100%!important}
        .lh-filters .select2-container--default .select2-selection--single{height:38px;border-radius:8px;display:flex;align-items:center}
        .lh-filters .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px;font-size:.88rem;padding-left:.6rem}
        .lh-filters .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px}
        .lh-check{display:inline-flex;align-items:center;gap:.4rem;margin:0;height:38px;font-size:.88rem;font-weight:600;cursor:pointer;user-select:none}
        .lh-check input{width:16px;height:16px;margin:0}
        .sr-hint{font-size:.74rem;color:#6b7280;margin:0 0 .35rem .2rem}
        #search_results{max-height:300px}
        #search_results:empty{display:none}
        #search_results .sr-item{display:block;padding:.45rem .7rem;border-bottom:1px solid #dbe7f5;cursor:pointer;line-height:1.5}
        #search_results .sr-item:hover,#search_results .sr-item.active{background:#dbeafe}
        #search_results .sr-item.active{outline:2px solid #3b82f6;outline-offset:-2px}
        #search_results .sr-empty:hover{background:transparent;cursor:default}
        #search_results .sr-main,#search_results .sr-sub{display:block}
        #search_results .sr-sub{font-size:.8rem;margin-top:.15rem}
        #search_results .sr-price{font-weight:700;color:#0f5132}
        #search_results .sr-name{font-weight:600}
        #lines_table th:nth-child(3),#lines_table td:nth-child(3){min-width:285px}
        #lines_table .pp-block{font-size:.8rem}
        #lines_table .pp-r1,#lines_table .pp-r2{display:flex;align-items:center;gap:6px}
        #lines_table .pp-r2{margin-top:4px}
        #lines_table .pp-cost{width:auto;flex:0 0 auto;flex-wrap:nowrap}
        #lines_table .pp-disc{width:auto;flex:0 0 auto;flex-wrap:nowrap}
        #lines_table .pp-r1 .input-group-text,#lines_table .pp-disc .input-group-text{padding:0 .4rem;font-size:.75rem}
        #lines_table .pp-r2 .select2-container{flex:1 1 auto;min-width:110px;max-width:150px}
        #lines_table .pp-r2 .select2-container--default .select2-selection--single{height:30px;line-height:28px}
        #lines_table .pp-r2 .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:28px;font-size:.75rem}
        #lines_table .pp-r2 .select2-container--default .select2-selection--single .select2-selection__arrow{height:28px}
        #lines_table .pp-margin{white-space:nowrap;font-size:.78rem;color:#6b7280}
        #lines_table .net-price{min-width:64px;white-space:nowrap}

        /* Ligne Client / Véhicule / Date */
        .top-row .form-label{font-size:.82rem}
        .top-row .select2-container--default .select2-selection--single{height:38px;border-radius:8px;display:flex;align-items:center}
        .top-row .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px;padding-left:.6rem}
        .top-row .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px}
        .top-row .form-control.order_date{width:100%;height:38px!important;min-height:38px;border-radius:8px;font-size:.88rem;padding:0 .6rem;line-height:36px}
        .vg-row{display:flex;align-items:center;gap:.5rem;flex-wrap:nowrap}
        .vg-plate{flex:0 0 auto;width:178px;flex-wrap:nowrap}
        .vg-plate .form-control{min-width:0;height:38px;border-radius:8px 0 0 8px;border:2px solid #1f2937;font-weight:800;letter-spacing:.08em;text-transform:uppercase;text-align:center;font-size:.9rem;padding:0 .4rem;background:#f9fafb}
        .vg-plate .form-control::placeholder{font-weight:500;letter-spacing:.03em;color:#9ca3af}
        .vg-plate .btn{height:38px;width:40px;padding:0;border-radius:0 8px 8px 0;display:inline-flex;align-items:center;justify-content:center;gap:.25rem}
        .vg-row #vehicle_id{flex:1 1 160px;min-width:0;height:38px;border-radius:8px}
        .vg-row .select2-container{flex:1 1 160px;min-width:0;width:auto!important}
        .vg-actions{display:flex;flex:0 0 auto;gap:.35rem;flex-wrap:nowrap}
        .vg-actions .btn{height:38px;min-width:42px;padding:0 .7rem;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;gap:.3rem;margin:0}
        .vg-actions a.btn[disabled]{opacity:.45;pointer-events:none}
        @media (max-width:991px){.vg-row{flex-wrap:wrap}.vg-row .select2-container,.vg-row #vehicle_id{flex:1 1 100%}}
        /* Liste résultats : sélection claire */
        #search_results{position:relative}
        #search_results .sr-item{position:relative;border-left:5px solid transparent;padding-right:6rem}
        #search_results .sr-item:hover{background:#eef5ff}
        #search_results .sr-item.active{background:#cfe3ff;border-left-color:#0d6efd;outline:none}
        #search_results .sr-item.active::after{content:"\21B5  Entrée";position:absolute;right:.7rem;top:.5rem;font-size:.72rem;font-weight:700;color:#fff;background:#0d6efd;border-radius:6px;padding:2px 8px}
        #lines_table th:nth-child(6),#lines_table td:nth-child(6),#lines_table th:nth-child(7),#lines_table td:nth-child(7){width:92px!important;min-width:92px!important}
        #lines_table .unit_price_ht,#lines_table .unit_price_ttc{width:80px!important;min-width:80px!important;max-width:80px!important}
        .lh-actionbar{position:sticky;bottom:0;z-index:20;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-top:.75rem;padding:.7rem 1rem;background:#fff;border:1px solid #dbe3ee;border-radius:10px;box-shadow:0 -4px 14px rgba(0,0,0,.08)}
        .lh-actionbar .ab-totals{display:flex;gap:1.5rem;align-items:baseline;font-size:1.05rem}
        .lh-actionbar .ab-totals b{font-size:1.25rem}
        .lh-actionbar .ab-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-actionbar .ab-btns .btn{height:42px;min-width:150px;display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:0 1rem;font-size:.9rem;font-weight:600;border-radius:8px;margin:0;white-space:nowrap}
        .lh-actionbar .ab-btns .btn small{font-weight:500;opacity:.85}
        @media (max-width:768px){.lh-actionbar .ab-btns .btn{flex:1 1 45%;min-width:0}}



        .modal-md { max-width: 1000px; }
    </style>
</head>
<body>
    <div class="wrapper sidebar_minimize">
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
                            <a data-bs-toggle="collapse" href="#ventes" aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i><p>Ventes</p><span class="caret"></span>
                            </a>
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
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#parametres" aria-expanded="false">
                                <i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="parametres">
                                <ul class="nav nav-collapse">
                                    <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                                    <li><a href="{{ route('admin.golda.index') }}"><span class="sub-item">Import GOLDA</span></a></li>
                                </ul>
                            </div>
                        </li>
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
                        <li class="nav-item"><a href="/contact"><i class="fas fa-headset"></i><p>Assistance</p></a></li>
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <i class="fas fa-layer-group"></i>
                                </a>
                                <div class="dropdown-menu quick-actions animated fadeIn">
                                    <div class="quick-actions-header">
                                        <span class="title mb-1">Actions Rapides</span>
                                    </div>
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
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
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
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramètres</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('logout.admin') }}" method="POST" style="display: inline;">@csrf
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
                    @if(session('error'))<div class="alert alert-danger">{!! session('error') !!}</div>@endif

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="lh-bar">
                                <h4 class="lh-title"><i class="fas fa-edit text-primary"></i> Modifier la commande de vente <span class="badge bg-primary ms-1">#{{ $order->numdoc }}</span></h4>
                                <div class="lh-btns">
                                    <button type="button" id="openCreateCustomerModal" class="btn btn-success">
                                        <i class="fas fa-user-plus"></i> Nouveau client
                                    </button>
                                    <a href="/newcustomer"
                                       onclick="window.open(this.href,'popupWindow','width=1200,height=700,scrollbars=yes'); return false;"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-users"></i> Liste des clients
                                    </a>
                                    <a href="/articles"
                                       onclick="window.open(this.href,'popupWindow','width=1000,height=700,scrollbars=yes'); return false;"
                                       class="btn btn-outline-success">
                                        <i class="fas fa-boxes"></i> Liste des articles
                                    </a>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-external-link-alt"></i> Externe
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="https://destockpiecesauto.autodata.fr/databox.php#vehicule/immat" target="_blank" rel="noopener">DataBox</a></li>
                                            <li><a class="dropdown-item" href="https://aznegoce.inoshop.net/search" target="_blank" rel="noopener">AZ</a></li>
                                            <li><a class="dropdown-item" href="https://apcat.eu/" target="_blank" rel="noopener">AP</a></li>
                                            <li><a class="dropdown-item" href="https://ottogo.inoshop.net/search" target="_blank" rel="noopener">OttoGo</a></li>
                                            <li><a class="dropdown-item" href="https://ksdistribpro.fr/index.php" target="_blank" rel="noopener">KS Distrib</a></li>
                                            <li><a class="dropdown-item" href="https://mymga.fr/" target="_blank" rel="noopener">MyMGA</a></li>
                                            <li><a class="dropdown-item" href="https://formule1.acrgroup.fr/CCDISP.HTM" target="_blank" rel="noopener">Formule 1</a></li>
                                            <li><a class="dropdown-item" href="https://www.idlp.fr/" target="_blank" rel="noopener">IDLP</a></li>
                                            <li><a class="dropdown-item" href="https://shopgroupeidlp.fr:5083/CCDISP.HTM" target="_blank" rel="noopener">Shop IDLP</a></li>
                                            <li><a class="dropdown-item" href="http://siteweb.cal92.fr/" target="_blank" rel="noopener">CAL92</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('sales.update', $order->numdoc) }}" method="POST" id="salesForm" novalidate>
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $order->id }}">

                                <div class="row top-row mb-2">
                                    <div class="col-lg-4 col-12 mb-2">
                                        <label class="form-label mb-1">Client</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            <option value="" disabled>Sélectionner un client</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                        data-tva="{{ $customer->tvaGroup->rate ?? 0 }}"
                                                        data-type="{{ $customer->type }}"
                                                        data-name="{{ $customer->name }}"
                                                        data-email="{{ $customer->email }}"
                                                        data-phone="{{ $customer->phone1 }}"
                                                        data-solde="{{ $customer->solde }}"
                                                        {{ $customer->id == $order->customer_id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tva_rate" id="tva_rate" value="{{ $order->tva_rate }}">
                                    </div>

                                    <div class="col-lg-6 col-12 mb-2" id="vehicle_group">
                                        <label class="form-label mb-1">
                                            Véhicule
                                            <span class="text-muted fw-normal fs-sm">(automatique via la plaque d'immatriculation)</span>
                                        </label>
                                        <div class="vg-row">
                                            <div class="input-group vg-plate">
                                                <input type="text" id="plate_search" class="form-control" placeholder="AB-123-CD" autocomplete="off" maxlength="12" title="Plaque d'immatriculation">
                                                <button type="button" id="searchByPlateBtn" class="btn btn-outline-primary" title="Rechercher la plaque">
                                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <select name="vehicle_id" id="vehicle_id" class="form-select select2-vehicle">
                                                <option value="">Aucun véhicule</option>
                                            </select>
                                            <div class="vg-actions">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVehicleInlineModal" title="Nouveau véhicule (TECDOC)">
                                                    <i class="fas fa-plus"></i><i class="fas fa-car"></i>
                                                </button>
                                                <a href="javascript:void(0)" id="loadCatalogBtn" class="btn btn-primary" disabled title="Catalogue du véhicule">
                                                    <i class="fas fa-book-open"></i>
                                                </a>
                                                <a href="javascript:void(0)" id="viewHistoryBtn" class="btn btn-secondary" disabled title="Historique du véhicule">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-12 mb-2">
                                        <label class="form-label mb-1">Date</label>
                                        <input type="date" name="order_date" id="order_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control order_date" required>
                                    </div>
                                </div>

                                <div id="customer_banner">
                                    <span class="cb-name" id="cb_name">{{ $order->customer->name ?? '—' }}</span>
                                    <span class="cb-sep">|</span>
                                    <span><span class="cb-label">Type : </span><span class="cb-val" id="cb_type">{{ $order->customer->type ?? '—' }}</span></span>
                                    <span class="cb-sep">|</span>
                                    <span><span class="cb-label">TVA : </span><span class="cb-val" id="cb_tva">{{ $order->tva_rate }}</span>%</span>
                                    <span class="cb-sep">|</span>
                                    <span><span class="cb-label">Solde : </span><span class="cb-val" id="cb_solde">{{ number_format($order->customer->solde ?? 0, 2, ',', ' ') }} €</span></span>
                                    <span class="cb-sep">|</span>
                                    <span style="font-size:0.78rem; color:#555;">
                                        <i class="fas fa-envelope" style="font-size:0.70rem;"></i> <span id="cb_email">{{ $order->customer->email ?? '—' }}</span>
                                        &nbsp;<i class="fas fa-phone" style="font-size:0.70rem;"></i> <span id="cb_phone">{{ $order->customer->phone1 ?? '—' }}</span>
                                    </span>
                                </div>

{{-- RECHERCHE ARTICLE --}}
                            <div class="mb-2">
                                <div class="lh-filters">
                                    <div class="lh-field lh-wide">
                                        <i class="fas fa-search lh-ico"></i>
                                        <input type="text" id="search_item" class="form-control"
                                               placeholder="Référence, désignation... (min. 4 car.)" autocomplete="off">
                                    </div>
                                    <button type="button" id="add_divers_item" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer un article
                                    </button>
                                    <button type="button" id="add_divers_item_consigne" data-type="consigne" class="btn btn-dark">
                                        <i class="fas fa-plus"></i> Consigne
                                    </button>
                                </div>
                                <div class="lh-filters">
                                    <div class="lh-field">
                                        <select id="filter_category" class="form-control">
                                            <option value="">Famille</option>
                                            @foreach(($categories ?? []) as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="lh-field">
                                        <select id="filter_subcategory" class="form-control">
                                            <option value="">Sous-famille</option>
                                        </select>
                                    </div>
                                    <div class="lh-field">
                                        <select id="filter_brand" class="form-control">
                                            <option value="">Marque</option>
                                        </select>
                                    </div>
                                    <div class="lh-field">
                                        <select id="filter_supplier_brand" class="form-control">
                                            <option value="">Fournisseur</option>
                                        </select>
                                    </div>
                                    <label class="lh-check"><input type="checkbox" id="filter_stock"> En stock</label>
                                    <button type="button" id="filter_reset" class="btn btn-outline-secondary" title="Réinitialiser les filtres">
                                        <i class="fas fa-undo"></i> Réinitialiser
                                    </button>
                                </div>
                                <div class="sr-hint">↑ ↓ naviguer &nbsp;·&nbsp; Entrée ajouter &nbsp;·&nbsp; Échap fermer</div>
                                <div id="search_results"></div>
                            </div>


                            
                                <div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-labelledby="itemDetailsLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="itemDetailsLabel">Détails de l’article</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered table-striped mb-0">
                                                    <tbody id="itemDetailsBody"></tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div style="overflow-x:auto;"><div id="lines_table_wrap" style="max-height:420px; overflow-y:auto;">
                                        <table class="table table-striped table-bordered table-text-small" id="lines_table">
                                            <thead class="table-dark">
                                                <tr style="height: 42px;">
                                                    <th class="py-1">Référence</th>
                                                    <th class="py-1">Désignation</th>
                                                    <th class="py-1 text-center">
                                                        <div class="small fw-bold">Achat HT</div>
                                                        <div class="text-info" style="font-size:0.63rem; line-height:1;">Remise → Net · Marge</div>
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
                                            <tbody id="lines_body">
                                                @foreach ($order->lines as $index => $line)
                                                    @php
                                                        $item = \App\Models\Item::where('code', $line->article_code)->first();
                                                        $costPrice = $line->unit_coast ?? ($item->cost_price ?? 0);
                                                        $supplierId = $line->supplier_id ?? ($item->supplier_id ?? null);
                                                        $purchaseDiscount = $line->discount_coast ?? 0;
                                                        $stock = $item->stock_quantity ?? 0;
                                                        $location = $item->location ?? '-';
                                                        $isActive = $item->is_active ?? false;
                                                        $lineName = $item ? $item->name : ($line->item_name ?? '-');
                                                    @endphp
                                                    <tr data-line-id="{{ $index }}">
                                                        <td>
                                                            <div style="display:flex;align-items:center;gap:3px;">
                                                                <span class="ref-code" title="{{ $line->article_code }}">{{ $line->article_code }}</span>
                                                                <button type="button" class="btn btn-xs btn-outline-secondary copy-line-code px-1 py-0" data-code="{{ $line->article_code }}" title="Copier la référence" style="flex-shrink:0;font-size:0.65rem;padding:1px 4px;border-radius:3px;"><i class="fas fa-copy"></i></button>
                                                            </div>
                                                            <span class="badge bg-{{ $isActive ? 'success' : 'danger' }} badge-very-sm">{{ $isActive ? 'actif' : 'bloqué' }}</span>
                                                            <input type="hidden" name="lines[{{ $index }}][line_id]" value="{{ $line->id }}">
                                                            <input type="hidden" name="lines[{{ $index }}][article_code]" value="{{ $line->article_code }}">
                                                        </td>
                                                        <td><span class="des-name">{{ $lineName }}</span></td>
                                                        <td class="p-1">
                                                            <div class="purchase-price-block pp-block">
                                                                <div class="pp-r1">
                                                                    <div class="input-group input-group-sm pp-cost"><span class="input-group-text">€</span>
                                                                        <input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input"
                                                                               value="{{ number_format($costPrice, 2, '.', '') }}" name="lines[{{ $index }}][unit_coast]">
                                                                    </div>
                                                                    <div class="input-group input-group-sm pp-disc">
                                                                        <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm text-end purchase-discount"
                                                                               value="{{ $purchaseDiscount }}" name="lines[{{ $index }}][discount_coast]">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                    <span class="text-muted small">→</span><span class="fw-bold text-success net-price">0,00 €</span>
                                                                </div>
                                                                <div class="pp-r2">
                                                                    <select class="form-select form-select-sm supplier-select" name="lines[{{ $index }}][supplier_id]" data-selected="{{ $supplierId }}"><option value="">Fournisseur</option></select>
                                                                    <span class="pp-margin">Marge <span class="margin-display fw-bold">0%</span> <small>(<span class="margin-euro">0,00 €</span>)</small></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-primary btn-sm stock-details-btn"
                                                                    data-code="{{ $line->article_code }}" data-name="{{ $lineName }}">{{ $stock }}</button><br>
                                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $location }}</small>
                                                        </td>
                                                        <td><input type="number" name="lines[{{ $index }}][ordered_quantity]" class="form-control quantity" value="{{ $line->ordered_quantity }}" min="0"></td>
                                                        <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ht]" class="form-control unit_price_ht" value="{{ number_format($line->unit_price_ht, 2, '.', '') }}"></td>
                                                        <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ttc]" class="form-control unit_price_ttc" value="{{ number_format($line->unit_price_ttc ?? $line->unit_price_ht * (1 + $order->tva_rate/100), 2, '.', '') }}"></td>
                                                        <td><input type="number" name="lines[{{ $index }}][remise]" class="form-control remise" value="{{ $line->remise }}" min="0" max="100" step="0.01"></td>
                                                        <td class="text-right total_ht">{{ number_format($line->total_ligne_ht, 2, ',', ' ') }}</td>
                                                        <td class="text-right total_ttc">{{ number_format($line->total_ligne_ttc, 2, ',', ' ') }}</td>
                                                        <td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes / Commentaire</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="2">{{ $order->notes }}</textarea>
                                </div>

                                <div class="lh-actionbar">
                                    <div class="ab-totals">
                                        <div>Total HT : <b id="total_ht_global" class="text-danger">{{ number_format($order->total_ht, 2, ',', ' ') }}</b> €</div>
                                        <div>Total TTC : <b id="total_ttc_global" class="text-success">{{ number_format($order->total_ttc, 2, ',', ' ') }}</b> €</div>
                                    </div>
                                    <div class="ab-btns">
                                        <button type="submit" name="action" value="save" class="btn btn-warning js-submit-btn"><i class="fas fa-save"></i> Enregistrer</button>
                                        <button type="submit" name="action" value="validate" class="btn btn-primary js-submit-btn"><i class="fas fa-check"></i> Enregistrer et valider BL</button>
                                        <button type="submit" name="action" value="validate_and_invoice" class="btn btn-success js-submit-btn"><i class="fas fa-file-invoice-dollar"></i> Enregistrer et facturer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-labelledby="stockDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="stockDetailsModalLabel"></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                <thead class="table-light"><tr><th>Type</th><th>QTE</th><th>Magasin</th>
                                <th>PU HT net<br><small class="fw-normal text-muted">après remise</small></th><th>PU TTC net<br><small class="fw-normal text-muted">après remise</small></th>
                                <th>Source</th><th>Référence</th></tr></thead>
                                <tbody id="movementTableBody"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addVehicleInlineModal" tabindex="-1" aria-labelledby="addVehicleInlineModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addVehicleInlineModalLabel"><i class="fas fa-car"></i> Associer un nouveau véhicule</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Créer et sélectionner</button>
                            </div>
                        </form>
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

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.suppliersList = @json($suppliersForSelect2);
        window.categoriesList = @json(isset($categories) ? $categories : []);
        window.subcategoriesList = @json(isset($subcategories) ? $subcategories : []);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
function esc(s) {
    return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}
function discountFor(rates, type) {
    type = (type || '').toLowerCase();
    if (type.indexOf('professionnel') !== -1) return rates.pro;
    if (type.indexOf('jobber') !== -1) return rates.jobber;
    return rates.general;
}

var dirty = false, submitting = false;
var lineCount = $('#lines_body tr').length;

$('#customer_id').select2({ width: '100%' });
$('#vehicle_id').select2({ width: '100%', placeholder: 'Aucun véhicule', allowClear: true });

// Client courant (lu depuis les data-* de l'option sélectionnée)
function custData() {
    var $o = $('#customer_id option:selected');
    if (!$o.length || !$o.val()) return null;
    return {
        id: $o.val(), name: $o.attr('data-name') || $o.text(), type: ($o.attr('data-type') || '').toLowerCase().trim(),
        tva: parseFloat($o.attr('data-tva')) || 0, solde: parseFloat($o.attr('data-solde')) || 0,
        email: $o.attr('data-email') || '', phone: $o.attr('data-phone') || ''
    };
}
function fillBanner(cd) {
    $('#cb_name').text((cd.name || '—').toUpperCase());
    $('#cb_type').text(cd.type || '—');
    $('#cb_tva').text(cd.tva);
    $('#cb_solde').text(formatFrench(cd.solde) + ' €').css('color', cd.solde >= 0 ? '#1a7a3a' : '#c0200d');
    $('#cb_email').text(cd.email || '—');
    $('#cb_phone').text(cd.phone || '—');
}

// Recalcul de toutes les lignes quand le client change (TVA + remise selon le type)
function recalcAllLines() {
    var cd = custData();
    var type = cd ? cd.type : '';
    var tva = parseFloat($('#tva_rate').val()) || 0;
    var n = 0;
    $('#lines_body tr').each(function () {
        var $r = $(this), rates = $r.data('rates');
        if (rates && !$r.data('remiseManual')) $r.find('.remise').val(discountFor(rates, type).toFixed(2));
        $r.data('last-modified', 'ht');
        updateLineTotals($r, tva);
        updatePurchaseMargin($r);
        n++;
    });
    updateGlobalTotals();
    return n;
}

// === VÉHICULES / CATALOGUE / HISTORIQUE ===
function updateCatalogButton() {
    var customerId = $('#customer_id').val(), vehicleId = $('#vehicle_id').val(), $btn = $('#loadCatalogBtn');
    if (customerId && vehicleId) {
        var href = '/customers/' + customerId + '/vehicles/' + vehicleId + '/catalog';
        $btn.attr('href', href).removeAttr('disabled').off('click').on('click', function (e) { e.preventDefault(); window.open(href, 'popupWindow', 'width=1000,height=700,scrollbars=yes'); });
    } else {
        $btn.removeAttr('href').attr('disabled', 'disabled');
    }
}
function updateHistoryButton() {
    var vehicleId = $('#vehicle_id').val(), customerId = $('#customer_id').val(), $btn = $('#viewHistoryBtn');
    if (vehicleId && customerId) {
        $btn.removeAttr('disabled').off('click').on('click', function (e) { e.preventDefault(); window.open('/vehicles/' + vehicleId + '/history', 'popupWindow', 'width=1000,height=700,scrollbars=yes'); });
    } else {
        $btn.attr('disabled', 'disabled');
    }
}
function loadVehicles(customerId, selectedId) {
    $('#vehicle_group').show();
    $.get('/customers/' + customerId + '/vehicles', function (data) {
        var $v = $('#vehicle_id');
        $v.empty().append('<option value="">Aucun véhicule</option>');
        data.forEach(function (v) {
            var text = v.license_plate + ' (' + v.brand_name + ' ' + v.model_name + ' - ' + v.engine_description + ')';
            $v.append(new Option(text, v.id, false, selectedId && v.id == selectedId));
        });
        if (selectedId) $v.val(selectedId);
        $v.trigger('change');
    }).fail(function () { console.error('Erreur lors du chargement des véhicules du client'); });
}
$('#vehicle_id').on('change', function () { updateCatalogButton(); updateHistoryButton(); });

var currentVehicleId = {{ $order->vehicle_id ?? 'null' }};   // véhicule actuel de la commande
if ($('#customer_id').val()) {
    var cd0 = custData();
    if (cd0) { $('#tva_rate').val(cd0.tva); fillBanner(cd0); }
    loadVehicles($('#customer_id').val(), currentVehicleId);
}

// Changement de client : TVA, remises, bandeau, véhicules
$('#customer_id').on('change', function () {
    var cd = custData();
    if (!cd) { $('#tva_rate').val(0); return; }
    $('#tva_rate').val(cd.tva);
    fillBanner(cd);
    $('#vehicle_id').empty().append('<option value="">Aucun véhicule</option>').trigger('change');
    loadVehicles(cd.id, null);
    var n = recalcAllLines();
    if (n > 0) { Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'TVA et remises recalculées pour ce client', showConfirmButton: false, timer: 3000 }); }
    setTimeout(function () { $('#search_item').trigger('focus'); }, 200);
});
$(document).on('select2:select select2:clear', function () { dirty = true; });

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


// === RECHERCHE ARTICLE ===
var CATS = {}, SUBS = {};
(window.categoriesList || []).forEach(function (c) { CATS[String(c.id)] = c.name; });
(window.subcategoriesList || []).forEach(function (s) { SUBS[String(s.id)] = s.name; });

function initFilterSelect($el, ph) {
    $el.select2({ width: '100%', placeholder: ph, allowClear: true, language: { noResults: function () { return 'Aucun résultat'; } } });
}
initFilterSelect($('#filter_category'), 'Famille');
initFilterSelect($('#filter_subcategory'), 'Sous-famille');
initFilterSelect($('#filter_brand'), 'Marque');
initFilterSelect($('#filter_supplier_brand'), 'Fournisseur');

function rebuildSubs(catId) {
    var $s = $('#filter_subcategory');
    $s.empty().append($('<option>').val('').text('Sous-famille'));
    (window.subcategoriesList || []).forEach(function (x) {
        if (catId && String(x.category_id) !== String(catId)) return;
        $s.append($('<option>').val(x.id).text((x.code ? x.code + ' – ' : '') + x.name));
    });
    $s.val('').trigger('change.select2');
}
rebuildSubs('');

var searchXhr = null, searchSeq = 0, searchTimer = null, searchTimerPending = false, pendingEnter = false;
function setSearchBusy(on) {
    $('#search_item').closest('.lh-field').find('i.lh-ico').toggleClass('fa-search', !on).toggleClass('fa-spinner fa-spin', on);
}
var lastResults = [], hasResults = false, activeIdx = -1;

function addFacetOptions($sel, vals) {
    var cur = $sel.val() || '', have = {}, added = false;
    $sel.find('option').each(function () { have[this.value] = 1; });
    vals.forEach(function (v) {
        if (v && !have[v]) { $sel.append($('<option>').val(v).text(v)); have[v] = 1; added = true; }
    });
    if (added) $sel.val(cur).trigger('change.select2');
}
function resetFacets() {
    $('#filter_brand, #filter_supplier_brand').each(function () {
        $(this).find('option:not(:first)').remove();
        $(this).val('').trigger('change.select2');
    });
}
function resetResults() {
    if (searchXhr) { searchXhr.abort(); searchXhr = null; }
    searchSeq++;
    clearTimeout(searchTimer); searchTimerPending = false;
    lastResults = []; hasResults = false; activeIdx = -1; pendingEnter = false;
    setSearchBusy(false);
    $('#search_results').empty();
}
function searchParams() {
    return {
        query: ($('#search_item').val() || '').trim(),
        category_id: $('#filter_category').val() || '',
        subcategory_id: $('#filter_subcategory').val() || ''
    };
}
function scheduleSearch(immediate) {
    clearTimeout(searchTimer);
    searchTimerPending = true;
    searchTimer = setTimeout(runSearch, immediate ? 0 : 300);
}
function runSearch() {
    searchTimerPending = false;
    var p = searchParams();
    if (searchXhr) { searchXhr.abort(); searchXhr = null; }
    if (p.query.length < 4 && !p.category_id && !p.subcategory_id) {
        resetResults(); resetFacets(); return;
    }
    var seq = ++searchSeq;
    setSearchBusy(true);
    if (!$('#search_results .sr-item').length) $('#search_results').html('<div class="sr-empty p-2 text-muted">Recherche…</div>');
    searchXhr = $.ajax({
        url: '{{ route("sales.items.search") }}', method: 'GET', data: p,
        success: function (data) {
            if (seq !== searchSeq) return;
            lastResults = Array.isArray(data) ? data : [];
            hasResults = true;
            addFacetOptions($('#filter_supplier_brand'), lastResults.map(function (i) { return i.supplier; }));
            addFacetOptions($('#filter_brand'), lastResults.map(function (i) { return i.brand; }));
            renderResults();
        },
        error: function (xhr, status) {
            if (status === 'abort' || seq !== searchSeq) return;
            $('#search_results').html('<div class="sr-empty p-2 text-danger">Erreur de recherche, réessayez.</div>');
        },
        complete: function () { if (seq === searchSeq) { searchXhr = null; setSearchBusy(false); } }
    });
}
function renderResults() {
    if (!hasResults) return;
    var brandF = $('#filter_brand').val() || '', supF = $('#filter_supplier_brand').val() || '';
    var onlyStock = $('#filter_stock').is(':checked');
    var $r = $('#search_results').empty();
    activeIdx = -1;
    var shown = 0;
    lastResults.forEach(function (it, i) {
        if (brandF && it.brand !== brandF) return;
        if (supF && it.supplier !== supF) return;
        var stock = parseFloat(it.stock_quantity) || 0;
        if (onlyStock && !(stock > 0)) return;
        shown++;
        var brandBadge = it.brand ? '<span class="badge text-bg-primary ms-1" style="font-size:0.7rem;">🏷 ' + esc(it.brand) + '</span>' : '';
        var famName = it.category_id ? (CATS[String(it.category_id)] || '') : '';
        var subName = it.subcategory_id ? (SUBS[String(it.subcategory_id)] || '') : '';
        var famBadge = (famName || subName) ? '<span class="badge text-bg-light ms-1" style="font-size:0.7rem;">' + esc(famName) + (subName ? ' › ' + esc(subName) : '') + '</span>' : '';
        var detailsBtn = '<button type="button" class="btn btn-xs btn-outline-primary voir-details px-2 py-1" style="font-size:0.75rem;" data-idx="' + i + '"><i class="fas fa-eye me-1"></i> Détails Article</button> ';
        var stockHtml = stock > 0
            ? detailsBtn + '🟢 ' + esc(it.stock_quantity) + ' En Stock'
            : detailsBtn + '🔴 Disponible auprès de <span class="badge text-bg-secondary">' + esc(it.supplier || '-') + '</span> au prix de <span class="badge text-bg-success">' + esc(it.cost_price) + ' € HT</span>';
        $r.append(
            '<div class="sr-item" data-idx="' + i + '">' +
              '<span class="sr-main"><span class="badge rounded-pill text-bg-light"><b>' + esc(it.code) + '</b>' +
              '<button type="button" class="btn btn-xs btn-outline-secondary copy-code px-1 py-0" data-code="' + esc(it.code) + '" title="Copier"><i class="fas fa-copy"></i></button></span>' +
              brandBadge + famBadge + ' &#8660; <span class="sr-name">' + esc(it.name) + '</span> : <span class="sr-price">' + esc(it.sale_price) + ' € HT</span></span>' +
              '<span class="sr-sub">' + stockHtml + '</span>' +
            '</div>'
        );
    });
    if (!shown) { $r.append('<div class="sr-empty p-2 text-muted">Aucun article trouvé.</div>'); pendingEnter = false; return; }
    // le premier résultat est toujours présélectionné : ce qui est surligné est ce que « Entrée » ajoute
    activeIdx = 0; markActive($r.find('.sr-item'), true);
    if (pendingEnter) { pendingEnter = false; if (shown === 1) $r.find('.sr-item').first().trigger('click'); }
}

$('#search_item').on('input', function () { scheduleSearch(false); });
$('#filter_category').on('change', function () { rebuildSubs($(this).val() || ''); scheduleSearch(true); });
$('#filter_subcategory').on('change', function () { scheduleSearch(true); });
$('#filter_brand, #filter_supplier_brand, #filter_stock').on('change', renderResults);
$('#filter_reset').on('click', function () {
    $('#filter_category').val('').trigger('change.select2');
    rebuildSubs('');
    $('#filter_brand, #filter_supplier_brand').val('').trigger('change.select2');
    $('#filter_stock').prop('checked', false);
    scheduleSearch(true);
});

// Navigation clavier dans la recherche
function markActive($items, noScroll) {
    $items.removeClass('active');
    if (activeIdx >= 0 && activeIdx < $items.length) {
        var el = $items.eq(activeIdx).addClass('active')[0];
        if (!noScroll && el.scrollIntoView) el.scrollIntoView({ block: 'nearest' });
    }
}
$(document).on('mousemove', '#search_results .sr-item', function () {
    var $items = $('#search_results .sr-item'), i = $items.index(this);
    if (i !== activeIdx) { activeIdx = i; markActive($items, true); }
});
$('#search_item').on('keydown', function (e) {
    var $items = $('#search_results .sr-item');
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        if (!$items.length) return;
        e.preventDefault();
        activeIdx += (e.key === 'ArrowDown') ? 1 : -1;
        if (activeIdx >= $items.length) activeIdx = 0;
        if (activeIdx < 0) activeIdx = $items.length - 1;
        markActive($items);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (searchTimerPending || searchXhr) { pendingEnter = true; scheduleSearch(true); return; }   // saisie rapide / scanner : on attend les résultats
        if (!$items.length) {
            if (($('#search_item').val() || '').trim().length >= 4) { pendingEnter = true; scheduleSearch(true); }
            return;
        }
        $items.eq(activeIdx >= 0 ? activeIdx : 0).trigger('click');
    } else if (e.key === 'Escape') {
        resetResults();
    }
});

$(document).on('click', '.copy-code', function (e) {
    e.stopPropagation();
    var code = $(this).data('code'), btn = $(this), orig = btn.html();
    try {
        navigator.clipboard.writeText(String(code)).then(function () {
            btn.html('<i class="fas fa-check text-success"></i>').prop('disabled', true);
            setTimeout(function () { btn.html(orig).prop('disabled', false); }, 1000);
        });
    } catch (err) {}
});
$(document).on('click', '.copy-line-code', function (e) {
    e.stopPropagation();
    try { navigator.clipboard.writeText(String($(this).data('code'))); } catch (err) {}
});

// Détails article
$(document).on('click', '.voir-details', function (e) {
    e.stopPropagation();
    var item = lastResults[parseInt($(this).attr('data-idx'), 10)];
    if (!item) return;
    function r(label, val) { return '<tr><th>' + label + '</th><td>' + esc(val === null || val === undefined || val === '' ? '-' : val) + '</td></tr>'; }
    var html = r('Code', item.code) + r('Nom', item.name) + r('Prix Achat', (item.cost_price || '-') + ' € HT') + r('Prix Vente', (item.sale_price || '-') + ' € HT') +
        r('Stock', item.stock_quantity || 0) + r('Famille', CATS[String(item.category_id)] || '-') + r('Sous-famille', SUBS[String(item.subcategory_id)] || '-') +
        r('Poids', item.Poids) + r('Hauteur', item.Hauteur) + r('Longueur', item.Longueur) + r('Largeur', item.Largeur) +
        r('Réf TecDoc', item.Ref_TecDoc) + r('Code Pays', item.Code_pays) + r('Code Douane', item.Code_douane) +
        r('Fournisseur', item.supplier) + r('Marque', item.brand) + r('Emplacement', item.location) + r('État', item.is_active ? '✅ Actif' : '❌ Inactif');
    $('#itemDetailsBody').html(html);
    $('#itemDetailsModal').modal('show');
});

// === SELECT2 FOURNISSEURS ===
function initSupplierSelect($select, supplierId) {
    $select.select2({ width: '100%', placeholder: 'Fournisseur', allowClear: true, data: window.suppliersList });
    if (supplierId) $select.val(supplierId).trigger('change');
}

function purchaseCellHtml(i, cost, pdisc, isConsigne) {
    return '<div class="purchase-price-block pp-block' + (isConsigne ? ' d-none' : '') + '">' +
        '<div class="pp-r1">' +
            '<div class="input-group input-group-sm pp-cost"><span class="input-group-text">€</span>' +
            '<input type="number" step="0.01" class="form-control form-control-sm text-end cost-price-input" value="' + cost + '" name="lines[' + i + '][unit_coast]"></div>' +
            '<div class="input-group input-group-sm pp-disc"><input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm text-end purchase-discount" value="' + pdisc + '" name="lines[' + i + '][discount_coast]"><span class="input-group-text">%</span></div>' +
            '<span class="text-muted small">→</span><span class="fw-bold text-success net-price">0,00 €</span>' +
        '</div>' +
        '<div class="pp-r2"><select class="form-select form-select-sm supplier-select" name="lines[' + i + '][supplier_id]"' + (isConsigne ? ' disabled' : '') + '><option value="">Fournisseur</option></select>' +
            '<span class="pp-margin">Marge <span class="margin-display fw-bold">0%</span> <small>(<span class="margin-euro">0,00 €</span>)</small></span></div>' +
    '</div>';
}

// ═══════════════════════════════════════════════════════════════
// AJOUT D'UNE LIGNE ARTICLE (clic, Entrée clavier ou restauration brouillon)
// ═══════════════════════════════════════════════════════════════
function addItemLine(item, opts) {
    opts = opts || {};
    var restore = !!opts.restore, v = opts.values || null;
    var customerId = $('#customer_id').val();
    var tvaRate, customerType = '';
    if (restore) {
        tvaRate = parseFloat($('#tva_rate').val()) || 0;
        var cdr = custData();
        customerType = (cdr && cdr.type) ? String(cdr.type).toLowerCase() : '';
    } else {
        if (!customerId || customerId === '%%%') { Swal.fire('Erreur', 'Veuillez sélectionner un client valide.', 'error'); return; }
        var cd = custData();
        customerType = (cd && cd.type) ? String(cd.type).toLowerCase() : '';
        tvaRate = parseFloat(cd && cd.tva ? cd.tva : 0);
        if (tvaRate === 0 && cd && cd.tva == null) { Swal.fire('Erreur', 'Taux TVA non défini.', 'error'); return; }
    }

    var code      = item.code;
    var name      = item.name;
    var price     = parseFloat(item.sale_price)  || 0;
    var costPrice = parseFloat(item.cost_price)  || 0;
    var stock     = parseFloat(item.stock_quantity) || 0;
    var location  = item.location || '-';
    var rates = {
        general: parseFloat(item.discount_rate || 20) || 0,
        jobber:  parseFloat(item.discount_rate_jobber || 0) || 0,
        pro:     parseFloat(item.discount_rate_professionnel || 0) || 0
    };
    var appliedDiscount = discountFor(rates, customerType);
    var purchaseDiscount = parseFloat(item.remise_achat) || 0;
    var unitPriceHt  = price.toFixed(2);
    var unitPriceTtc = (price * (1 + tvaRate / 100)).toFixed(2);
    var i = lineCount;

    var row =
        '<tr data-line-id="' + i + '">' +
        '<td>' +
            '<div style="display:flex;align-items:center;gap:3px;">' +
                '<span class="ref-code" title="' + esc(code) + '">' + esc(code) + '</span>' +
                '<button type="button" class="btn btn-xs btn-outline-secondary copy-line-code px-1 py-0" data-code="' + esc(code) + '" title="Copier la référence" style="flex-shrink:0;font-size:0.65rem;padding:1px 4px;border-radius:3px;"><i class="fas fa-copy"></i></button>' +
            '</div>' +
            '<input type="hidden" name="lines[' + i + '][article_code]" value="' + esc(code) + '">' +
        '</td>' +
        '<td><span class="des-name">' + esc(name) + '</span></td>' +
        '<td class="p-1">' + purchaseCellHtml(i, costPrice.toFixed(2), purchaseDiscount.toFixed(2), false) + '</td>' +
        '<td>' +
            '<button type="button" class="btn btn-outline-primary btn-sm stock-details-btn" data-toggle="modal" data-target="#stockDetailsModal" data-code="' + esc(code) + '" data-name="' + esc(name) + '">' + stock.toFixed(0) + '</button>' +
            '<br><small class="text-muted" style="font-size:0.7rem;">' + esc(location) + '</small>' +
        '</td>' +
        '<td><input type="number" name="lines[' + i + '][ordered_quantity]" class="form-control quantity" value="1" min="0"></td>' +
        '<td><input type="number" inputmode="decimal" name="lines[' + i + '][unit_price_ht]" class="form-control unit_price_ht" value="' + unitPriceHt + '" step="0.01"></td>' +
        '<td><input type="number" inputmode="decimal" name="lines[' + i + '][unit_price_ttc]" class="form-control unit_price_ttc" value="' + unitPriceTtc + '" step="0.01"></td>' +
        '<td><input type="number" name="lines[' + i + '][remise]" class="form-control remise" value="' + appliedDiscount.toFixed(2) + '" min="0" max="100" step="0.01"></td>' +
        '<td class="text-right total_ht">0,00</td>' +
        '<td class="text-right total_ttc">0,00</td>' +
        '<td><button type="button" class="btn btn-outline-danger btn-sm remove_line"><i class="fas fa-trash-alt"></i></button></td>' +
        '</tr>';

    lineCount++;
    $('#lines_body').append(row);
    var $newRow = $('#lines_body tr:last');
    $newRow.data('rates', rates).data('draftItem', item);
    initSupplierSelect($newRow.find('.supplier-select'), v ? (v.supplierId || '') : (item.supplier_id || ''));
    if (v) {
        $newRow.find('.cost-price-input').val(v.cost);
        $newRow.find('.purchase-discount').val(v.pdisc);
        $newRow.find('.quantity').val(v.qty);
        $newRow.find('.unit_price_ht').val(v.puHt);
        $newRow.find('.unit_price_ttc').val(v.puTtc);
        $newRow.find('.remise').val(v.remise);
        if (v.manual) $newRow.data('remiseManual', true);
        $newRow.data('last-modified', v.last || 'ht');
    }
    updatePurchaseMargin($newRow);
    updateLineTotals($newRow, tvaRate);
    updateGlobalTotals();

    if (!restore) {
        $('#search_item').val('');
        resetResults(); resetFacets();
        var q = $newRow.find('.quantity');
        q.trigger('focus'); if (q[0]) q[0].select();
        dirty = true;
    }
}

$(document).on('click', '#search_results .sr-item', function () {
    var item = lastResults[parseInt($(this).attr('data-idx'), 10)];
    if (item) addItemLine(item);
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
                $('#movementTableBody').append('<tr><td colspan="7" class="text-center text-danger">Erreur: ' + data.error + '</td></tr>');
                return;
            }
            if (data.stocks && data.stocks.length > 0) {
                data.stocks.forEach(function (s) { $('#stockTableBody').append('<tr><td>' + (s.store_name||'-') + '</td><td>' + s.quantity + '</td><td>' + (s.updated_at||'-') + '</td></tr>'); });
            } else { $('#stockTableBody').append('<tr><td colspan="3" class="text-center text-muted">Aucun stock trouvé</td></tr>'); }
            if (data.movements && data.movements.length > 0) {
                data.movements.forEach(function (m) {
                    var cp = parseFloat(m.cost_price), cpf = isNaN(cp) ? '-' : cp.toFixed(2) + ' €';
var cpt = parseFloat(m.cost_price_ttc), cptf = isNaN(cpt) ? '-' : '<b>' + cpt.toFixed(2) + ' €</b><br><small class="text-muted">TVA ' + (parseFloat(m.tva_rate) || 0) + ' %</small>';
                    $('#movementTableBody').append('<tr><td><span class="badge bg-' + (m.quantity >= 0 ? 'success' : 'danger') + '">' + (m.type||'?') + '</span><br>' + (m.created_at||'-') + '</td><td>' + (m.quantity||0) + '</td><td>' + (m.store_name||'-') + '</td><td>' + cpf + '</td><td>' + cptf + '</td><td>' + (m.supplier_name||'-') + '</td><td>' + (m.reference||'-') + '</td></tr>');
                });
            } else { $('#movementTableBody').append('<tr><td colspan="7" class="text-center text-muted">Aucun mouvement trouvé</td></tr>'); }
        },
        error: function (xhr) {
            var msg = xhr.status === 404 ? 'Article non trouvé' : 'Erreur serveur: ' + xhr.status;
            $('#stockTableBody').append('<tr><td colspan="3" class="text-center text-danger">' + msg + '</td></tr>');
            $('#movementTableBody').append('<tr><td colspan="7" class="text-center text-danger">' + msg + '</td></tr>');
        }
    });
    $('#stockDetailsModal').modal('show');
});

// === ARTICLE DIVERS ===
function addDiversLine(isConsigne, v) {
    var i = lineCount;
    lineCount++;
    v = v || {};
    var rowHtml =
        '<tr class="divers-line" data-line-id="divers_' + i + '" data-is-consigne="' + (isConsigne ? '1' : '0') + '">' +
        '<td>' +
            '<input type="text" name="lines[' + i + '][article_code]" class="form-control form-control-sm bg-light ref-code" style="font-family:\'Courier New\',monospace; font-weight:800; font-size:0.93rem; color:#003a80;" value="' + (isConsigne ? 'CONSIGNE' : esc(v.code || '')) + '" placeholder="Réf" ' + (isConsigne ? 'readonly' : '') + '>' +
            '<input type="hidden" name="lines[' + i + '][is_new_item]" value="1">' +
        '</td>' +
        '<td>' +
            '<input type="text" name="lines[' + i + '][item_name]" class="form-control form-control-sm bg-light des-name" style="font-weight:700; font-size:0.88rem; color:#111;" value="' + (isConsigne ? 'CONSIGNE' : esc(v.name || '')) + '" placeholder="Désignation" ' + (isConsigne ? 'readonly' : '') + '>' +
        '</td>' +
        '<td class="p-1">' +
            purchaseCellHtml(i, '0.00', '0', isConsigne) +
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
    var $r = $('#lines_body tr:last');
    initSupplierSelect($r.find('.supplier-select'), v.supplierId || '');
    if (v.cost !== undefined) {
        $r.find('.cost-price-input').val(v.cost);
        $r.find('.purchase-discount').val(v.pdisc);
        $r.find('.quantity').val(v.qty);
        $r.find('.unit_price_ht').val(v.puHt);
        $r.find('.unit_price_ttc').val(v.puTtc);
        $r.find('.remise').val(v.remise);
        $r.data('last-modified', v.last || 'ht');
        updatePurchaseMargin($r);
        updateLineTotals($r, parseFloat($('#tva_rate').val()) || 0);
    }
    updateGlobalTotals();
    return $r;
}
$(document).on('click', '#add_divers_item, #add_divers_item_consigne', function () {
    var $r = addDiversLine($(this).data('type') === 'consigne');
    var f = $r.find(this.id === 'add_divers_item' ? '.ref-code' : '.unit_price_ht').first();
    f.trigger('focus');
    dirty = true;
});


            const $modal = $('#addVehicleInlineModal');
            const $form = $('#quickVehicleForm');
            function initTecdocSelect2() {
                $('#quick_brand_id, #quick_model_id, #quick_engine_id').select2('destroy');
                $('#quick_brand_id').select2({ width: '100%', dropdownParent: $modal });
                $('#quick_model_id').select2({ width: '100%', dropdownParent: $modal });
                $('#quick_engine_id').select2({ width: '100%', dropdownParent: $modal });
            }
            $modal.on('shown.bs.modal', function () {
                $form[0].reset();
                $('#quick_model_id, #quick_engine_id').empty().prop('disabled', true);
                initTecdocSelect2();
            });
            $(document).on('change', '#quick_brand_id', function () {
                let id = $(this).val();
                $('#quick_model_id').empty().append('<option>Chargement...</option>').prop('disabled', true);
                if (id) $.get('{{ route("getModels") }}', { brand_id: id }, data => {
                    $('#quick_model_id').empty().append('<option>Choisir un modèle</option>');
                    data.forEach(m => $('#quick_model_id').append(`<option value="${m.id}" data-name="${m.name}">${m.name}</option>`));
                    $('#quick_model_id').prop('disabled', false);
                });
            });
            $(document).on('change', '#quick_model_id', function () {
                let id = $(this).val();
                $('#quick_engine_id').empty().append('<option>Chargement...</option>').prop('disabled', true);
                if (id) $.get('{{ route("getEngines") }}', { model_id: id }, data => {
                    $('#quick_engine_id').empty().append('<option>Choisir une motorisation</option>');
                    data.forEach(e => $('#quick_engine_id').append(`<option value="${e.id}" data-description="${e.description}" data-linking-target-id="${e.linkageTargetId}">${e.description}</option>`));
                    $('#quick_engine_id').prop('disabled', false);
                });
            });
            $(document).on('change', '#quick_engine_id', function () {
                let $opt = $(this).find('option:selected');
                $('#quick_engine_description').val($opt.data('description'));
                $('#quick_linkage_target_id').val($opt.data('linking-target-id') || $(this).val());
            });
            $form.on('submit', function (e) {
                e.preventDefault();
                let customerId = $('#customer_id').val();
                if (!customerId) return Swal.fire('Erreur', 'Sélectionnez un client', 'error');
                let data = {
                    _token: csrfToken,
                    license_plate: $('#quick_license_plate').val().toUpperCase().trim(),
                    brand_id: $('#quick_brand_id').val(),
                    brand_name: $('#quick_brand_id option:selected').text(),
                    model_id: $('#quick_model_id').val(),
                    model_name: $('#quick_model_id option:selected').data('name') || $('#quick_model_id option:selected').text(),
                    engine_id: $('#quick_engine_id').val(),
                    engine_description: $('#quick_engine_description').val() || $('#quick_engine_id option:selected').text(),
                    linkage_target_id: $('#quick_linkage_target_id').val()
                };
                $.post(`/customers/${customerId}/vehicles/quick-store`, data, res => {
                    if (res.success) {
                        let text = `${data.license_plate} (${data.brand_name} ${data.model_name} - ${data.engine_description})`;
                        $('#vehicle_id').append(new Option(text, res.vehicle.id, true, true)).trigger('change');
                        $modal.modal('hide');
                        updateCatalogButton();
                        updateHistoryButton();
                        Swal.fire('Succès', 'Véhicule créé et sélectionné !', 'success');
                    }
                });
            });

            $('#searchByPlateBtn').on('click', async function () {
    var btn = $(this);
    var input = $('#plate_search');
    var plate = input.val().trim().toUpperCase().replace(/[^A-Z0-9]/g, '');

    if (!plate || plate.length < 4) {
        return Swal.fire('Plaque invalide', 'Veuillez entrer une plaque correcte', 'warning');
    }

    btn.prop('disabled', true).find('.spinner-border').removeClass('d-none');
    input.prop('disabled', true);

    try {
        var customerId = $('#customer_id').val();
        if (!customerId) {
            throw new Error('Veuillez d\'abord sélectionner un client');
        }

        // === APPEL API EXTERNE (identique à la page create) ===
        var brand = 'INCONNUE', model = '', engine = '';
        try {
            var resp = await fetch('https://api.apiplaqueimmatriculation.com/plaque?immatriculation=' + plate + '&token=acce455746476e1d0679a0aa1c4ae93f&pays=FR');
            var json = await resp.json();
            if (json && json.data && !json.data.erreur) {
                brand  = json.data.marque  || 'INCONNUE';
                model  = json.data.modele  || '';
                engine = json.data.sra_commercial || json.data.code_moteur || '';
            }
        } catch (e) {
            console.warn('API plaque indisponible, on continue sans les données véhicule');
        }

        // === APPEL BACKEND ===
        var query = new URLSearchParams({
            license_plate:     plate,
            brand_name:        brand,
            model_name:        model,
            engine_description: engine
        });

        var result = await $.ajax({
            url:     '/customers/' + customerId + '/vehicles/from-plate?' + query,
            method:  'GET',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });

        if (result.success) {
            // Ajout dans le select véhicule + sélection automatique
            var option = new Option(result.vehicle.text, result.vehicle.id, true, true);
            $('#vehicle_id').append(option).trigger('change');
            updateCatalogButton();
            updateHistoryButton();
            Swal.fire({
                icon: 'success', title: 'Véhicule ajouté !',
                text: result.vehicle.text,
                timer: 2500, toast: true, position: 'top-end',
                showConfirmButton: false
            });
            input.val('');
        }

    } catch (err) {
        Swal.fire('Erreur', err.message || 'Impossible d\'ajouter le véhicule', 'error');
    } finally {
        btn.prop('disabled', false).find('.spinner-border').addClass('d-none');
        input.prop('disabled', false);
    }
});


// === LIGNES EXISTANTES : fournisseurs, marges, totaux ===
$('.supplier-select').each(function () {
    var $select = $(this), supplierId = $select.attr('data-selected') || null;
    $select.select2({ width: '100%', placeholder: 'Fournisseur', allowClear: true, data: window.suppliersList });
    if (supplierId) $select.val(supplierId).trigger('change');
});
$('#lines_body tr').each(function () {
    updatePurchaseMargin($(this));
    updateLineTotals($(this), $('#tva_rate').val());
});
updateGlobalTotals();

// === ENTRÉE CLAVIER : ne soumet jamais le formulaire ===
$('#plate_search').on('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); $('#searchByPlateBtn').trigger('click'); } });
$('#salesForm').on('keydown', function (e) {
    if (e.key !== 'Enter') return;
    var $t = $(e.target);
    if ($t.is('textarea, button, a, [type=submit], #plate_search')) return;
    e.preventDefault();
    if ($t.is('.quantity, .unit_price_ht, .unit_price_ttc, .remise, .cost-price-input, .purchase-discount')) {
        $('#search_item').trigger('focus');
    }
});
$(document).on('input', '.remise', function () { $(this).closest('tr').data('remiseManual', true); });
$('#salesForm').on('input', function () { dirty = true; });
$(document).on('click', '.remove_line', function () { dirty = true; });
window.addEventListener('beforeunload', function (e) {
    if (dirty && !submitting) { e.preventDefault(); e.returnValue = ''; }
});

// === ENREGISTREMENT : confirmation par bouton + anti double envoi ===
var SUBMIT_CFG = {
    'save':                 { title: 'Enregistrer les modifications ?', ok: 'Enregistrer',                color: '#e0a800' },
    'validate':             { title: 'Enregistrer et valider le BL ?',  ok: 'Enregistrer et valider BL', color: '#0d6efd' },
    'validate_and_invoice': { title: 'Enregistrer et facturer ?',       ok: 'Enregistrer et facturer',   color: '#198754' }
};
function validateForm() {
    var cid = $('#customer_id').val();
    if (!cid) return 'Sélectionnez un client.';
    var $rows = $('#lines_body tr');
    if (!$rows.length) return 'Ajoutez au moins une ligne.';
    var err = '';
    $rows.each(function (idx) {
        var $r = $(this), n = idx + 1;
        var qty = parseFloat(($r.find('.quantity').val() || '').replace(',', '.')) || 0;
        if (qty <= 0) { err = 'Ligne ' + n + ' : la quantité doit être supérieure à 0.'; return false; }
        if ($r.hasClass('divers-line')) {
            if (!$.trim($r.find('.ref-code').val())) { err = 'Ligne ' + n + ' : la référence est obligatoire.'; return false; }
            if (!$.trim($r.find('.des-name').val())) { err = 'Ligne ' + n + ' : la désignation est obligatoire.'; return false; }
        }
    });
    if (!err && !$('#order_date').val()) err = 'La date est obligatoire.';
    return err;
}
function lossLinesCount() {
    var n = 0;
    $('#lines_body tr').each(function () {
        var m = parseFloat($(this).find('.margin-display').text().replace('%', '').replace(',', '.'));
        if (!isNaN(m) && m < 0) n++;
    });
    return n;
}
document.getElementById('salesForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (submitting) return;
    var form = this, btn = e.submitter || null;
    var actionValue = btn ? btn.value : null;
    var cfg = SUBMIT_CFG[actionValue];
    if (!cfg) return;
    var err = validateForm();
    if (err) { Swal.fire({ icon: 'warning', title: 'Document incomplet', text: err }); return; }
    var cd = custData(), loss = lossLinesCount();
    var html = '<div class="text-start" style="font-size:.95rem">' +
        '<div>Client : <b>' + esc(cd ? String(cd.name).toUpperCase() : '') + '</b></div>' +
        '<div>Lignes : <b>' + $('#lines_body tr').length + '</b></div>' +
        '<div>Total HT : <b>' + esc($('#total_ht_global').text()) + ' €</b> &nbsp;·&nbsp; Total TTC : <b>' + esc($('#total_ttc_global').text()) + ' €</b></div>' +
        (loss ? '<div class="text-danger mt-2"><i class="fas fa-exclamation-triangle"></i> ' + loss + ' ligne(s) vendue(s) à perte</div>' : '') +
        '</div>';
    Swal.fire({
        icon: loss ? 'warning' : 'question', title: cfg.title, html: html,
        showCancelButton: true, confirmButtonText: cfg.ok, cancelButtonText: 'Annuler',
        confirmButtonColor: cfg.color, reverseButtons: true, focusCancel: !!loss
    }).then(function (res) {
        if (!res.isConfirmed || submitting) return;
        submitting = true;
        // form.submit() n'envoie pas le bouton cliqué : on transmet « action » (le serveur en dépend)
        $('<input type="hidden" name="action">').val(actionValue).appendTo(form);
        $('.js-submit-btn').prop('disabled', true);
        if (btn) $(btn).html('<span class="spinner-border spinner-border-sm" role="status"></span> Enregistrement…');
        form.submit();
    });
});
window.addEventListener('pageshow', function (ev) {
    if (ev.persisted && submitting) { location.reload(); }
});

        });
    </script>














<style>
#quickCustomerModal .modal-content{border:none;border-radius:16px;overflow:hidden;box-shadow:0 10px 40px rgba(30,45,74,.25)}
#quickCustomerModal .ncm-head{background:linear-gradient(135deg,#1E2D4A 0%,#2D4A8A 100%);color:#fff;padding:16px 22px;display:flex;align-items:center;justify-content:space-between;position:relative}
#quickCustomerModal .ncm-head::after{content:'';position:absolute;left:0;right:0;bottom:0;height:3px;background:linear-gradient(90deg,#3B82F6,#10B981,#F59E0B)}
#quickCustomerModal .ncm-head h5{margin:0;font-size:1rem;font-weight:700;color:#fff}
#quickCustomerModal .ncm-head small{display:block;color:rgba(255,255,255,.6);font-size:.74rem;margin-top:2px}
#quickCustomerModal .ncm-x{background:none;border:0;color:#fff;font-size:1.6rem;line-height:1;opacity:.8;cursor:pointer;padding:0 4px}
#quickCustomerModal .ncm-x:hover{opacity:1}
#quickCustomerModal .modal-body{background:#F0F4FF;padding:18px 22px 6px}
#quickCustomerModal .ncm-sec{font-size:.74rem;font-weight:800;color:#1E2D4A;letter-spacing:.07em;text-transform:uppercase;display:flex;align-items:center;gap:7px;margin:4px 0 10px;padding-bottom:6px;border-bottom:1.5px solid #E2E8F0}
#quickCustomerModal .ncm-sec i{color:#3B82F6}
#quickCustomerModal .ncm-sec:not(:first-child){margin-top:16px}
#quickCustomerModal label{font-size:.7rem;font-weight:700;color:#6B7A99;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px}
#quickCustomerModal .ncm-req{color:#EF4444}
#quickCustomerModal .form-control,#quickCustomerModal .form-select{border:1.5px solid #E2E8F0;border-radius:8px;font-size:.86rem;padding:7px 11px;height:auto}
#quickCustomerModal .form-control:focus,#quickCustomerModal .form-select:focus{border-color:#3B82F6;box-shadow:0 0 0 3px rgba(59,130,246,.13)}
#quickCustomerModal .is-invalid{border-color:#EF4444!important}
#quickCustomerModal .ncm-err{color:#EF4444;font-size:.72rem;margin-top:2px;display:none}
#quickCustomerModal .ncm-hint{font-size:.72rem;color:#6B7A99;margin-top:3px}
#quickCustomerModal .ncm-more{background:none;border:0;color:#3B82F6;font-weight:600;font-size:.8rem;padding:4px 0;cursor:pointer}
#quickCustomerModal .modal-footer{background:#fff;border-top:1.5px solid #E2E8F0;padding:12px 22px;display:flex;justify-content:space-between;align-items:center}
#quickCustomerModal .ncm-cancel{background:none;border:1.5px solid #E2E8F0;color:#6B7A99;border-radius:8px;padding:8px 18px;font-size:.84rem;font-weight:600;cursor:pointer}
#quickCustomerModal .ncm-cancel:hover{border-color:#3B82F6;color:#3B82F6}
#quickCustomerModal .ncm-save{background:linear-gradient(135deg,#10B981,#059669);border:0;color:#fff;border-radius:8px;padding:9px 22px;font-size:.86rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px}
#quickCustomerModal .ncm-save:disabled{opacity:.6;cursor:wait}
#quickCustomerModal .ncm-global{background:#FEE2E2;color:#991B1B;border-radius:8px;padding:8px 12px;font-size:.8rem;margin-bottom:10px;display:none}
</style>

<div class="modal fade" id="quickCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="ncm-head">
                <div>
                    <h5><i class="fas fa-user-plus me-2"></i>Nouveau client</h5>
                    <small>Le client sera sélectionné automatiquement sur cette commande</small>
                </div>
                <button type="button" class="ncm-x" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Fermer">&times;</button>
            </div>

            <form id="quickCustomerForm" novalidate autocomplete="off">
                <div class="modal-body">
                    <div class="ncm-global" id="ncmGlobal"></div>

                    <div class="ncm-sec"><i class="fas fa-user"></i> Identité</div>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label>Nom complet <span class="ncm-req">*</span></label>
                            <input type="text" name="name" class="form-control" maxlength="255" placeholder="Ex : DUPONT Jean" required>
                            <div class="ncm-err" data-for="name"></div>
                        </div>
                        <div class="col-md-4">
                            <label>Type <span class="ncm-req">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="particulier">Particulier</option>
                                <option value="jobber">Jobber</option>
                                <option value="professionnel">Professionnel</option>
                            </select>
                            <div class="ncm-err" data-for="type"></div>
                        </div>
                        <div class="col-md-12"><div class="ncm-hint" id="ncmTypeHint"></div></div>
                    </div>

                    <div class="ncm-sec"><i class="fas fa-address-card"></i> Contact</div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label>Téléphone</label>
                            <input type="text" name="phone1" class="form-control" maxlength="20" placeholder="06 00 00 00 00">
                            <div class="ncm-err" data-for="phone1"></div>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" maxlength="255" placeholder="contact@exemple.fr">
                            <div class="ncm-err" data-for="email"></div>
                        </div>
                    </div>

                    <div class="ncm-sec"><i class="fas fa-map-marker-alt"></i> Adresse</div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label>Adresse</label>
                            <input type="text" name="address" class="form-control" maxlength="255" placeholder="N° et rue">
                            <div class="ncm-err" data-for="address"></div>
                        </div>
                        <div class="col-md-2">
                            <label>Code postal</label>
                            <input type="text" name="address_delivery" class="form-control" maxlength="255" placeholder="75001" inputmode="numeric">
                            <div class="ncm-err" data-for="address_delivery"></div>
                        </div>
                        <div class="col-md-4">
                            <label>Ville</label>
                            <input type="text" name="city" class="form-control" maxlength="100" placeholder="Paris">
                            <div class="ncm-err" data-for="city"></div>
                        </div>
                    </div>

                    <div class="ncm-sec"><i class="fas fa-euro-sign"></i> Paramètres de vente</div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label>TVA <span class="ncm-req">*</span></label>
                            <select name="tva_group_id" class="form-select" data-opt="tvaGroups" required><option value="">Chargement…</option></select>
                            <div class="ncm-err" data-for="tva_group_id"></div>
                        </div>
                        <div class="col-md-4">
                            <label>Groupe remise <span class="ncm-req">*</span></label>
                            <select name="discount_group_id" class="form-select" data-opt="discountGroups" required><option value="">Chargement…</option></select>
                            <div class="ncm-err" data-for="discount_group_id"></div>
                        </div>
                        <div class="col-md-4">
                            <label>Plafond (€)</label>
                            <input type="number" step="0.01" min="0" name="plafond" class="form-control" value="0">
                            <div class="ncm-err" data-for="plafond"></div>
                        </div>
                        <div class="col-md-6">
                            <label>Condition de paiement <span class="ncm-req">*</span></label>
                            <select name="payment_term_id" class="form-select" data-opt="paymentTerms" required><option value="">Chargement…</option></select>
                            <div class="ncm-err" data-for="payment_term_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label>Mode de paiement <span class="ncm-req">*</span></label>
                            <select name="payment_mode_id" class="form-select" data-opt="paymentModes" required><option value="">Chargement…</option></select>
                            <div class="ncm-err" data-for="payment_mode_id"></div>
                        </div>
                    </div>

                    <button type="button" class="ncm-more" id="ncmMoreBtn"></button>
                    <div id="ncmMore" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-4"><label>SIRET</label><input type="text" name="matfiscal" class="form-control" maxlength="100" placeholder="14 chiffres"><div class="ncm-err" data-for="matfiscal"></div></div>
                            <div class="col-md-5"><label>IBAN</label><input type="text" name="bank_no" class="form-control" maxlength="100" placeholder="FR76…"><div class="ncm-err" data-for="bank_no"></div></div>
                            <div class="col-md-3"><label>Pays</label><input type="text" name="country" class="form-control" maxlength="100" value="France"><div class="ncm-err" data-for="country"></div></div>
                        </div>
                    </div>
                    <div style="height:8px"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="ncm-cancel" data-bs-dismiss="modal" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="ncm-save" id="ncmSave"><i class="fas fa-check"></i> Créer et sélectionner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    var OPTIONS_URL = @json(route('customers.form-options'));
    var STORE_URL   = @json(route('customers.quick-store'));

    var modalEl = document.getElementById('quickCustomerModal');
    var form    = document.getElementById('quickCustomerForm');
    var saveBtn = document.getElementById('ncmSave');
    var globalE = document.getElementById('ncmGlobal');
    var modal   = null;
    var optionsLoaded = false, busy = false;

    function csrf() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    }
    function esc(s) {
        return String(s == null ? '' : s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function getModal() {
        if (!modal) modal = new bootstrap.Modal(modalEl);
        return modal;
    }

    /* ---------- erreurs ---------- */
    function clearErrors() {
        globalE.style.display = 'none'; globalE.textContent = '';
        form.querySelectorAll('.is-invalid').forEach(function (e) { e.classList.remove('is-invalid'); });
        form.querySelectorAll('.ncm-err').forEach(function (e) { e.style.display = 'none'; e.textContent = ''; });
    }
    function setError(name, msg) {
        var el = form.elements[name], box = form.querySelector('.ncm-err[data-for="' + name + '"]');
        if (el) el.classList.add('is-invalid');
        if (box) { box.textContent = msg; box.style.display = 'block'; }
        // si le champ est dans la partie facultative, on l'ouvre
        if (el && el.closest('#ncmMore')) showMore(true);
    }
    function showGlobal(msg) { globalE.textContent = msg; globalE.style.display = 'block'; }

    function showMore(open) {
        document.getElementById('ncmMore').style.display = open ? 'block' : 'none';
        document.getElementById('ncmMoreBtn').innerHTML = open
            ? '<i class="fas fa-chevron-up me-1"></i> Masquer SIRET, IBAN et pays'
            : '<i class="fas fa-plus-circle me-1"></i> Client professionnel ? Ajouter SIRET, IBAN, pays';
    }
    showMore(false);
    document.getElementById('ncmMoreBtn').addEventListener('click', function () {
        showMore(document.getElementById('ncmMore').style.display === 'none');
    });

    /* ---------- listes déroulantes (chargées une seule fois) ---------- */
    function fillSelect(sel, rows, labelFn) {
        sel.innerHTML = '';
        rows.forEach(function (r, i) {
            var o = document.createElement('option');
            o.value = r.id; o.textContent = labelFn(r);
            if (i === 0) o.selected = true;
            sel.appendChild(o);
        });
        if (!rows.length) sel.innerHTML = '<option value="">— aucune —</option>';
    }
    function loadOptions() {
        if (optionsLoaded) return Promise.resolve();
        return fetch(OPTIONS_URL, { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { if (!r.ok) throw new Error('http ' + r.status); return r.json(); })
            .then(function (d) {
                fillSelect(form.elements.tva_group_id,      d.tvaGroups      || [], function (x) { return x.name + ' (' + x.rate + '%)'; });
                fillSelect(form.elements.discount_group_id, d.discountGroups || [], function (x) { return x.name + ' (' + x.discount_rate + '%)'; });
                fillSelect(form.elements.payment_term_id,   d.paymentTerms   || [], function (x) { return x.label; });
                fillSelect(form.elements.payment_mode_id,   d.paymentModes   || [], function (x) { return x.name; });
                optionsLoaded = true;
            });
    }

    /* ---------- aide selon le type ---------- */
    function typeHint() {
        var h = document.getElementById('ncmTypeHint');
        h.innerHTML = form.elements.type.value === 'particulier'
            ? '<i class="fas fa-info-circle"></i> Un particulier ne peut pas avoir de bon de livraison : la vente se fait en facture directe.'
            : '';
    }
    form.elements.type.addEventListener('change', typeHint);

    /* ---------- ouverture ---------- */
    document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('#openCreateCustomerModal') : null;
        if (!b) return;
        e.preventDefault();
        form.reset(); clearErrors(); showMore(false); typeHint();
        getModal().show();
        loadOptions().catch(function () {
            showGlobal('Impossible de charger les listes (TVA, remises, paiements). Fermez puis rouvrez la fenêtre.');
        });
    });
    modalEl.addEventListener('shown.bs.modal', function () { form.elements.name.focus(); });

    /* ---------- sélection du client créé dans la commande ---------- */
    function selectCustomer(c) {
        var $c = $('#customer_id');
        $c.find('option').filter(function () { return String(this.value) === String(c.id); }).remove();
        var opt = new Option(c.text, c.id, true, true);
        $(opt).data('data', c);               // page avec recherche Ajax : conserve tva / solde / type…
        $(opt).attr({                          // page de modification (liste statique) : mêmes infos en data-*
            'data-tva': c.tva, 'data-type': c.type, 'data-name': c.name,
            'data-email': c.email, 'data-phone': c.phone1, 'data-solde': c.solde
        });
        $c.append(opt).trigger('change');     // bandeau client, TVA, recalcul des lignes, véhicules, bouton BL
        $c[0].dispatchEvent(new Event('input', { bubbles: true }));   // marque la page comme modifiée (alerte avant de quitter, brouillon)
    }

    /* ---------- envoi ---------- */
    function send(force) {
        var payload = {};
        new FormData(form).forEach(function (v, k) { payload[k] = v; });
        if (force) payload.force = 1;
        return fetch(STORE_URL, {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify(payload)
        }).then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { return { status: r.status, body: j }; }); });
    }

    function done(c) {
        getModal().hide();
        selectCustomer(c);
        if (window.Swal) Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Client ' + (c.code || '') + ' sélectionné', showConfirmButton: false, timer: 2500 });
    }

    function submit(force) {
        if (busy) return;
        clearErrors();
        var ok = true;
        ['name', 'type', 'tva_group_id', 'discount_group_id', 'payment_term_id', 'payment_mode_id'].forEach(function (n) {
            var el = form.elements[n];
            if (!el || !String(el.value).trim()) { setError(n, 'Champ obligatoire'); ok = false; }
        });
        if (!ok) { var f = form.querySelector('.is-invalid'); if (f) f.focus(); return; }

        busy = true; saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création…';
        send(force).then(function (res) {
            var b = res.body || {};
            if (res.status === 200 && b.customer) { done(b.customer); return; }

            if (res.status === 409 && b.duplicate) {                       // client similaire déjà présent
                var list = (b.similar || []).map(function (s) {
                    return '<li><b>' + esc(s.code) + '</b> — ' + esc(s.name) + (s.phone1 ? ' · ' + esc(s.phone1) : '') + '</li>';
                }).join('');
                Swal.fire({
                    icon: 'warning', title: 'Client similaire existant',
                    html: '<ul style="text-align:left;margin:0 0 8px 18px;">' + list + '</ul>Voulez-vous utiliser le client existant ou créer un nouveau client ?',
                    showDenyButton: true, showCancelButton: true,
                    confirmButtonText: 'Utiliser l\'existant', denyButtonText: 'Créer quand même', cancelButtonText: 'Retour'
                }).then(function (r) {
                    if (r.isConfirmed && b.similar && b.similar[0]) { done(b.similar[0].customer); }
                    else if (r.isDenied) { busy = false; submit(true); }
                });
                return;
            }
            if (res.status === 422 && b.errors) {
                Object.keys(b.errors).forEach(function (k) { setError(k, b.errors[k][0]); });
                if (b.message && !Object.keys(b.errors).length) showGlobal(b.message);
                return;
            }
            if (res.status === 419 || res.status === 401) { showGlobal('Votre session a expiré. Rechargez la page (le brouillon de la commande est conservé).'); return; }
            showGlobal(b.message || 'Erreur lors de la création du client.');
        }).catch(function () {
            showGlobal('Erreur réseau. Vérifiez la connexion et réessayez.');
        }).then(function () {
            busy = false; saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-check"></i> Créer et sélectionner';
        });
    }
    form.addEventListener('submit', function (e) { e.preventDefault(); submit(false); });
})();
</script>



</body>
</html>