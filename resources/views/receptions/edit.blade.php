<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Rectifier une réception</title>
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
                            <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
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
                            <li><a href="{{ route('purchase_docs.create') }}"><span class="sub-item"></i> Nouveau document</span></a></li>
<li><a href="{{ route('purchaseprojects.list') }}"><span class="sub-item">Projets d’Achat</span></a></li>
<li><a href="{{ route('purchases.list') }}"><span class="sub-item">Commandes</span></a></li>
<li><a href="{{ route('receptions.index') }}"><span class="sub-item">Réceptions</span></a></li>
<li><a href="{{ route('invoices.list') }}"><span class="sub-item">Factures</span></a></li>
<li><a href="{{ route('returns.list') }}"><span class="sub-item">Retours</span></a></li>
<li><a href="{{ route('notes.list') }}"><span class="sub-item">Avoirs</span></a></li>
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
                            <li><a href="{{ route('admin.golda.index') }}"><span class="sub-item">Import GOLDA</span></a></li>
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
                        <!-- Profil -->
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
                    <div class="card-body">

@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $er)<li>{{ $er }}</li>@endforeach</ul></div>
@endif

<div class="lh-bar">
    <h4 class="lh-title"><i class="fas fa-pen text-warning"></i> Rectifier la réception {{ $reception->numdoc }}</h4>
    <div class="lh-btns"><a href="{{ route('receptions.show', $reception->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Retour</a></div>
</div>

<div class="alert alert-warning" style="font-size:.9rem">
    Le <b>stock est ajusté de la différence</b> à l'enregistrement. Mettre une quantité à <b>0</b> retire la ligne. Un prix modifié met aussi à jour le coût de l'article si cette réception est la plus récente pour lui.
    Impossible de ressortir plus que le stock disponible (déjà vendu ou sorti).
</div>

<form method="POST" action="{{ route('receptions.update', $reception->id) }}" id="editForm" novalidate>
    @csrf @method('PUT')
    <div class="row mb-2">
        <div class="col-lg-3 col-6 mb-2"><label class="form-label mb-1">Fournisseur</label><div class="form-control-plaintext fw-bold">{{ optional($reception->supplier)->name }}</div></div>
        <div class="col-lg-3 col-6 mb-2"><label class="form-label mb-1" for="reception_date">Date de réception</label>
            <input type="date" id="reception_date" name="reception_date" class="form-control order_date" value="{{ old('reception_date', \Carbon\Carbon::parse($reception->reception_date)->format('Y-m-d')) }}"></div>
        <div class="col-lg-3 col-6 mb-2"><label class="form-label mb-1" for="supplier_ref">N° BL / facture fournisseur</label>
            <input type="text" id="supplier_ref" name="supplier_ref" class="form-control" maxlength="60" value="{{ old('supplier_ref', $reception->supplier_ref) }}"></div>
        <div class="col-lg-3 col-6 mb-2"><label class="form-label mb-1">Commande</label><div class="form-control-plaintext fw-bold">{{ optional($reception->purchaseOrder)->numdoc ?? 'Réception directe' }}</div></div>
    </div>

    <div style="overflow-x:auto;">
        <table class="table table-bordered table-text-small" id="ed_table">
            <thead class="table-dark text-white"><tr>
                <th>Référence</th><th>Désignation</th><th class="text-center">Commandé</th><th class="text-center">Reçu (avant)</th>
                <th class="text-center">Nouvelle qté</th><th class="text-center">Prix achat HT</th><th class="text-center">Rem %</th><th class="text-center">TVA %</th><th class="text-end">Total HT</th>
            </tr></thead>
            <tbody>
            @foreach($reception->lines as $l)
                @php($ord = $l->orderLine ? (int) round((float) $l->orderLine->ordered_quantity) : null)
                <tr data-qty0="{{ (int) $l->received_quantity }}">
                    <td><b>{{ $l->article_code }}</b></td>
                    <td>{{ optional($l->item)->name ?? '—' }}</td>
                    <td class="text-center">{{ $ord === null ? 'hors commande' : $ord }}</td>
                    <td class="text-center">{{ (int) $l->received_quantity }}</td>
                    <td><input type="number" min="0" step="1" class="form-control e-qty" name="lines[{{ $l->id }}][qty]" value="{{ old('lines.' . $l->id . '.qty', (int) $l->received_quantity) }}"></td>
                    <td><input type="number" min="0" step="0.01" class="form-control e-pu" name="lines[{{ $l->id }}][pu]" value="{{ old('lines.' . $l->id . '.pu', number_format((float) $l->unit_price_ht, 2, '.', '')) }}"></td>
                    <td><input type="number" min="0" max="100" step="0.01" class="form-control e-rem" name="lines[{{ $l->id }}][remise]" value="{{ old('lines.' . $l->id . '.remise', number_format((float) $l->remise, 2, '.', '')) }}"></td>
                    <td><input type="number" min="0" max="100" step="0.01" class="form-control e-tva" name="lines[{{ $l->id }}][tva]" value="{{ old('lines.' . $l->id . '.tva', (float) $l->tva) }}"></td>
                    <td class="text-end fw-bold e-tot">0,00</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-3"><label class="form-label" for="notes">Notes</label><textarea id="notes" name="notes" class="form-control" rows="2">{{ old('notes', $reception->notes) }}</textarea></div>

    <div class="lh-actionbar">
        <div class="ab-totals"><div>Écart de stock : <b id="ed_delta">0</b> unité(s)</div><div>Total HT : <b id="ed_ht" class="text-danger">0,00</b> €</div></div>
        <div class="ab-btns">
            <a href="{{ route('receptions.show', $reception->id) }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="button" class="btn btn-warning" id="btnSave"><i class="fas fa-save"></i> Enregistrer la rectification</button>
        </div>
    </div>
</form>

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

<!-- Core JS Files (une seule pile jQuery / Bootstrap) -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';
    function num(v) { var n = parseFloat(String(v).replace(',', '.')); return isNaN(n) ? 0 : n; }
    function fr(n) { return (Math.round(n * 100) / 100).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    function recalc() {
        var ht = 0, delta = 0, zero = 0, n = 0;
        $('#ed_table tbody tr').each(function () {
            var $r = $(this), q = Math.max(0, Math.floor(num($r.find('.e-qty').val()))), q0 = parseInt($r.data('qty0'), 10) || 0;
            var net = num($r.find('.e-pu').val()) * (1 - num($r.find('.e-rem').val()) / 100), t = Math.round(q * net * 100) / 100;
            $r.find('.e-tot').text(fr(t)); ht += t; delta += q - q0; n++; if (q === 0) zero++;
            $r.css('background', q !== q0 ? '#fffbeb' : '');
        });
        $('#ed_ht').text(fr(ht)); $('#ed_delta').text((delta > 0 ? '+' : '') + delta);
        return { delta: delta, allZero: zero === n };
    }
    $(document).on('input change', '#ed_table input', recalc);
    $(document).on('focus click', '#ed_table input[type=number]', function () { this.select(); });
    $('#editForm').on('submit', function (e) { e.preventDefault(); });
    $('#btnSave').on('click', function () {
        var s = recalc();
        if (s.allZero) { Swal.fire({ icon: 'warning', title: 'Toutes les quantités sont à 0', text: 'Pour supprimer la réception, utilisez « Annuler la réception » sur sa fiche.' }); return; }
        Swal.fire({ icon: 'question', title: 'Enregistrer la rectification ?', text: 'Le stock sera ajusté de ' + (s.delta > 0 ? '+' : '') + s.delta + ' unité(s).', showCancelButton: true, confirmButtonText: 'Enregistrer', cancelButtonText: 'Annuler', confirmButtonColor: '#f0ad4e' })
            .then(function (r) { if (r.isConfirmed) { $('#btnSave').prop('disabled', true); document.getElementById('editForm').submit(); } });
    });
    recalc();
})();
</script>

</body>
</html>
