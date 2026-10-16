<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Réceptions d'achat</title>
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

        .rl-det td{background:#f8fafc!important;padding:.5rem 1rem!important}
        .rl-det table{margin:0;background:#fff}.rl-det th{font-size:.72rem;background:#eef2f7;color:#374151}
        .rl-tg{cursor:pointer;color:#6b7280;border:0;background:none;padding:0 .25rem}.rl-tg:hover{color:#0d6efd}
        .rl-tg i{transition:transform .15s}.rl-tg.open i{transform:rotate(90deg)}
        .rl-ret{background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:.5rem .7rem;margin-top:.5rem;text-align:left}
        .rl-ret label{font-size:.83rem;margin:0;cursor:pointer}
        .rl-kpis{display:flex;flex-wrap:wrap;gap:.7rem;margin-bottom:.9rem}
        .rl-kpi{display:flex;flex-direction:column;min-width:200px;padding:.6rem .9rem;border:1px solid #dbe3ee;border-radius:10px;background:#fff;color:#111;text-decoration:none}
        .rl-kpi span{font-size:.78rem;color:#6b7280}.rl-kpi b{font-size:1.5rem;line-height:1.2}.rl-kpi small{color:#6b7280}
        .rl-kpi:hover{border-color:#0d6efd;text-decoration:none}
        #rec_list tr.rl-cancelled td{color:#9ca3af;text-decoration:line-through}
        #rec_list tr.rl-cancelled td:last-child,#rec_list tr.rl-cancelled td:nth-child(10){text-decoration:none}
        .pagination{margin:0}
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
                            <li><a href="{{ route('purchase_docs.create') }}"><span class="sub-item"><i class="fas fa-plus-circle"></i> Nouveau document</span></a></li>
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
    <h4 class="lh-title"><i class="fas fa-clipboard-check text-primary"></i> Réceptions d'achat</h4>
    <div class="lh-btns">
        <a href="{{ route('purchase_docs.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nouveau document d'achat</a>
        <a href="{{ route('purchases.list') }}" class="btn btn-outline-secondary"><i class="fas fa-list"></i> Commandes d'achat</a>
        <a href="{{ route('invoices.list') }}" class="btn btn-outline-success"><i class="fas fa-file-invoice-dollar"></i> Factures d'achat</a>
    </div>
</div>

<div class="rl-kpis">
    <a class="rl-kpi" href="{{ route('receptions.index', ['state' => 'a_facturer']) }}"><span>À facturer</span><b>{{ $kpi['a_facturer'] }}</b><small>{{ number_format($kpi['a_facturer_ht'], 2, ',', ' ') }} € HT</small></a>
    <a class="rl-kpi" href="{{ route('returns.list') }}"><span>Retours à facturer</span><b>{{ $kpi['retours_a_facturer'] }}</b><small>− {{ number_format($kpi['retours_a_facturer_ht'], 2, ',', ' ') }} € HT (déduits à la facturation)</small></a>
    <a class="rl-kpi" href="{{ route('purchases.list') }}"><span>Commandes à recevoir</span><b>{{ $kpi['a_recevoir'] }}</b><small>en attente ou partielles</small></a>
</div>

<form method="GET" action="{{ route('receptions.index') }}" class="lh-filters" id="filterForm">
    <div class="lh-field lh-wide"><i class="fas fa-search lh-ico"></i>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="N° réception, BL, commande, fournisseur, référence article…" style="padding-left:2.1rem"></div>
    <div class="lh-field">
        <select name="supplier_id" class="form-control" onchange="this.form.submit()">
            <option value="">Tous les fournisseurs</option>
            @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ (string) request('supplier_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->code }} — {{ $s->name }}</option>@endforeach
        </select></div>
    <div class="lh-field">
        <select name="state" class="form-control" onchange="this.form.submit()">
            <option value="">Tous les états</option>
            <option value="a_facturer" {{ request('state') === 'a_facturer' ? 'selected' : '' }}>À facturer</option>
            <option value="facturee" {{ request('state') === 'facturee' ? 'selected' : '' }}>Facturées</option>
            <option value="annulee" {{ request('state') === 'annulee' ? 'selected' : '' }}>Annulées</option>
        </select></div>
    <div class="lh-field" style="flex:0 1 150px"><input type="date" name="from" value="{{ request('from') }}" class="form-control" title="Du"></div>
    <div class="lh-field" style="flex:0 1 150px"><input type="date" name="to" value="{{ request('to') }}" class="form-control" title="Au"></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
    <a href="{{ route('receptions.index') }}" class="btn btn-outline-secondary"><i class="fas fa-undo"></i> Réinitialiser</a>
</form>

<form method="POST" action="{{ route('receptions.invoice') }}" id="invoiceForm">
    @csrf
    <div style="overflow-x:auto;">
        <table class="table table-striped table-bordered table-text-small" id="rec_list">
            <thead class="table-dark text-white">
                <tr>
                    <th style="width:34px"></th>
                    <th style="width:26px"></th>
                    <th>N° réception</th><th>Date</th><th>Fournisseur</th><th>Commande</th><th>N° BL</th>
                    <th class="text-center">Lignes</th><th class="text-center">Unités</th><th class="text-end">Total HT</th><th class="text-center">État</th><th style="width:170px"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($receptions as $r)
                @php($hist = strpos((string) $r->numdoc, 'REC-H') === 0)
                @php($cancelled = $r->status === 'annulée')
                @php($canInvoice = !$r->invoiced && !$cancelled && !$hist)
                @php($recTtc = $r->lines->sum(function ($l) { return (float) $l->total_ligne_ht * (1 + (float) $l->tva / 100); }))
                <tr class="{{ $cancelled ? 'rl-cancelled' : '' }}" data-rec="{{ $r->id }}">
                    <td class="text-center">@if($canInvoice)<input type="checkbox" class="rl-check" name="ids[]" value="{{ $r->id }}" data-supplier="{{ $r->supplier_id }}" data-ht="{{ (float) $r->total_ht }}" data-ttc="{{ round($recTtc, 2) }}" data-num="{{ $r->numdoc }}">@endif</td>
                    <td class="text-center"><button type="button" class="rl-tg" data-target="#det{{ $r->id }}" title="Voir les lignes sans quitter la page"><i class="fas fa-chevron-right"></i></button></td>
                    <td><a href="{{ route('receptions.show', $r->id) }}" class="fw-bold">{{ $r->numdoc ?: ('#' . $r->id) }}</a></td>
                    <td>{{ \Carbon\Carbon::parse($r->reception_date)->format('d/m/Y') }}</td>
                    <td>{{ optional($r->supplier)->name ?? '—' }}</td>
                    <td>@if($r->purchaseOrder)<a href="{{ route('purchases.edit', $r->purchaseOrder->id) }}">{{ $r->purchaseOrder->numdoc }}</a>@else <span class="text-muted">directe</span>@endif</td>
                    <td>{{ $r->supplier_ref ?: '—' }}</td>
                    <td class="text-center">{{ $r->lines_count }}</td>
                    <td class="text-center">{{ (int) $r->total_qty }}</td>
                    <td class="text-end">{{ $hist ? '—' : number_format((float) $r->total_ht, 2, ',', ' ') . ' €' }}</td>
                    <td class="text-center">
                        @if($cancelled)<span class="badge bg-secondary">Annulée</span>
                        @elseif($hist)<span class="badge bg-light text-dark border" title="Ancien circuit : stock déjà pris en compte">Historique</span>
                        @elseif($r->invoiced)<span class="badge bg-success">Facturée</span>
                        @else<span class="badge bg-warning text-dark">À facturer</span>@endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('receptions.show', $r->id) }}" class="btn btn-sm btn-outline-primary" title="Voir"><i class="fas fa-eye"></i></a>
                        @if(!$hist && !$cancelled && !$r->invoiced)<a href="{{ route('receptions.edit', $r->id) }}" class="btn btn-sm btn-outline-warning" title="Rectifier"><i class="fas fa-pen"></i></a>@endif
                        <a href="{{ route('receptions.pdf', $r->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="PDF"><i class="fas fa-file-pdf"></i></a>
                    </td>
                </tr>
                <tr class="rl-det" id="det{{ $r->id }}" style="display:none">
                    <td colspan="12">
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Référence</th><th>Désignation</th><th class="text-center">Qté reçue</th><th class="text-end">PU HT</th><th class="text-center">Rem %</th><th class="text-center">TVA %</th><th class="text-end">Total HT</th></tr></thead>
                            <tbody>
                            @foreach($r->lines as $l)
                                <tr><td><b>{{ $l->article_code }}</b></td><td>{{ optional($l->item)->name ?? '—' }}</td><td class="text-center">{{ (float) $l->received_quantity + 0 }}</td>
                                    <td class="text-end">{{ number_format((float) $l->unit_price_ht, 2, ',', ' ') }}</td><td class="text-center">{{ (float) $l->remise + 0 }}</td><td class="text-center">{{ (float) $l->tva + 0 }}</td>
                                    <td class="text-end">{{ number_format((float) $l->total_ligne_ht, 2, ',', ' ') }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-muted" style="font-size:.78rem">@if($r->store)Magasin : {{ $r->store->name }} · @endif @if($r->notes){{ $r->notes }}@endif
                            <a href="{{ route('receptions.show', $r->id) }}" class="ms-2">Ouvrir la fiche complète</a></div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="12" class="text-center text-muted py-4">Aucune réception.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2">
        <div>{{ $receptions->links() }}</div>
    </div>

    <div class="lh-actionbar" id="invoiceBar" style="display:none;">
        <div class="ab-totals"><div><b id="sel_count">0</b> réception(s) sélectionnée(s)</div><div>Total HT : <b id="sel_ht" class="text-danger">0,00</b> €</div></div>
        <div class="ab-btns">
            <input type="hidden" name="supplier_ref" id="inv_ref"><input type="hidden" name="invoice_date" id="inv_date"><input type="hidden" name="due_date" id="inv_due">
            <button type="button" class="btn btn-success" id="btnInvoice"><i class="fas fa-file-invoice-dollar"></i> Facturer la sélection</button>
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
    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
    function fr(n) { return (Math.round(n * 100) / 100).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function refresh() {
        var $c = $('.rl-check:checked'), ht = 0, suppliers = {};
        $c.each(function () { ht += parseFloat($(this).data('ht')) || 0; suppliers[$(this).data('supplier')] = 1; });
        // On ne mélange pas les fournisseurs : dès qu'un est coché, les autres sont désactivés
        var first = $c.length ? $c.first().data('supplier') : null;
        $('.rl-check').each(function () { $(this).prop('disabled', first !== null && $(this).data('supplier') !== first && !this.checked); });
        $('#sel_count').text($c.length); $('#sel_ht').text(fr(ht));
        $('#invoiceBar').toggle($c.length > 0);
    }
    $(document).on('change', '.rl-check', refresh);
    $(document).on('click', '.rl-tg', function () {
        var $b = $(this).toggleClass('open'), $d = $($b.data('target'));
        $d.toggle($b.hasClass('open'));
    });
    var RETURNS = @json($returnsBySupplier);

    $('#btnInvoice').on('click', function () {
        var $sel = $('.rl-check:checked'), n = $sel.length; if (!n) return;
        var supplier = $sel.first().data('supplier'), recHt = 0, recTtc = 0;
        $sel.each(function () { recHt += parseFloat($(this).data('ht')) || 0; recTtc += parseFloat($(this).data('ttc')) || 0; });
        var rets = RETURNS[supplier] || [];
        var today = new Date().toISOString().slice(0, 10);

        var retHtml = '';
        if (rets.length) {
            retHtml = '<div class="rl-ret"><b style="font-size:.85rem"><i class="fas fa-undo text-warning"></i> Retours non facturés de ce fournisseur — à déduire de la facture</b>';
            rets.forEach(function (r) {
                var det = r.lines.map(function (l) { return esc(l.code) + ' × ' + l.qty; }).join(', ');
                retHtml += '<div class="form-check" style="margin-top:.3rem"><input class="form-check-input sw-ret" type="checkbox" checked id="sr' + r.id + '" value="' + r.id + '" data-ht="' + r.ht + '" data-ttc="' + r.ttc + '">' +
                           '<label class="form-check-label" for="sr' + r.id + '"><b>' + esc(r.numdoc) + '</b> · ' + esc(r.date) + (r.order ? ' · ' + esc(r.order) : '') + ' · <span class="text-danger">−' + fr(r.ht) + ' € HT</span><br><small class="text-muted">' + det + '</small></label></div>';
            });
            retHtml += '</div>';
        }
        var netHtml = '<div id="sw_net" style="margin-top:.7rem;padding:.5rem .7rem;background:#f1f5f9;border-radius:8px;font-size:.88rem;text-align:left"></div>';

        function updateNet() {
            var rh = 0, rt = 0;
            $('.sw-ret:checked').each(function () { rh += parseFloat($(this).data('ht')) || 0; rt += parseFloat($(this).data('ttc')) || 0; });
            var ht = recHt - rh, ttc = recTtc - rt;
            $('#sw_net').html('Réceptions : <b>' + fr(recHt) + '</b> € HT' + (rh ? ' &nbsp;−&nbsp; Retours : <b class="text-danger">' + fr(rh) + '</b> € HT' : '') +
                '<br>Net à facturer : <b>' + fr(ht) + ' € HT</b> · <b class="text-success">' + fr(ttc) + ' € TTC</b>' + (ttc < 0 ? '<br><span class="text-danger">Total négatif : désélectionnez des retours ou faites un avoir.</span>' : ''));
            Swal.getConfirmButton().disabled = ttc < 0;
        }

        Swal.fire({
            icon: 'question', title: 'Facturer ' + n + ' réception(s)',
            html: '<div style="text-align:left;font-size:.9rem">' +
                  '<label>N° facture du fournisseur</label><input id="sw_ref" class="swal2-input" style="margin:.2rem 0 .6rem;width:100%" placeholder="facultatif">' +
                  '<label>Date de facture</label><input id="sw_date" type="date" class="swal2-input" style="margin:.2rem 0 .6rem;width:100%" value="' + today + '">' +
                  '<label>Échéance</label><input id="sw_due" type="date" class="swal2-input" style="margin:.2rem 0;width:100%"></div>' + retHtml + netHtml +
                  '<div style="margin-top:.6rem;font-size:.82rem;color:#6b7280">La facture est validée : le solde du fournisseur augmente du net TTC.</div>',
            showCancelButton: true, confirmButtonText: 'Créer la facture', cancelButtonText: 'Annuler', confirmButtonColor: '#198754', focusConfirm: false,
            didOpen: function () { updateNet(); $(document).on('change.swret', '.sw-ret', updateNet); },
            willClose: function () { $(document).off('change.swret'); },
            preConfirm: function () {
                return { ref: document.getElementById('sw_ref').value, date: document.getElementById('sw_date').value, due: document.getElementById('sw_due').value,
                         rets: $('.sw-ret:checked').map(function () { return this.value; }).get() };
            }
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $('#inv_ref').val(r.value.ref); $('#inv_date').val(r.value.date); $('#inv_due').val(r.value.due);
            $('#invoiceForm input[name="return_ids[]"]').remove();
            r.value.rets.forEach(function (id) { $('<input type="hidden" name="return_ids[]">').val(id).appendTo('#invoiceForm'); });
            $('#btnInvoice').prop('disabled', true);
            document.getElementById('invoiceForm').submit();
        });
    });
    refresh();
})();
</script>

@include('partials.select2_init')
</body>
</html>
