<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Nouveau retour fournisseur</title>
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

        /* ============================================================
           RÉCEPTION — scan + lignes (mobile / PDA friendly)
           ============================================================ */
        .rc-scan{position:sticky;top:0;z-index:30;background:#fff;border:1px solid #dbe3ee;border-radius:10px;padding:.6rem;margin-bottom:.8rem;box-shadow:0 4px 12px rgba(0,0,0,.06)}
        .rc-scan-row{display:flex;gap:.45rem;align-items:center}
        .rc-scan-field{position:relative;flex:1 1 auto;min-width:0}
        .rc-scan-field i{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#6b7280;pointer-events:none}
        #scan_input{width:100%;height:48px;border-radius:10px;border:2px solid #0d6efd;background:#eef5ff;padding:0 .8rem 0 2.5rem;font-size:1.05rem;font-weight:600;outline:none}
        #scan_input:focus{background:#fff;box-shadow:0 0 0 4px rgba(13,110,253,.18)}
        .rc-scan-qty{display:flex;align-items:center;gap:.2rem;font-weight:800;color:#374151}
        .rc-scan-qty input{width:64px;height:48px;border-radius:10px;border:2px solid #9ca3af;text-align:center;font-size:1.05rem;font-weight:700}
        .rc-btn{height:48px;min-width:48px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;margin:0}
        .rc-banner{margin-top:.5rem;padding:.55rem .8rem;border-radius:8px;font-size:.95rem;font-weight:600;display:flex;align-items:center;gap:.5rem;transition:background .15s}
        .rc-banner-idle{background:#f1f5f9;color:#475569;font-weight:500;font-size:.85rem}
        .rc-banner-ok{background:#d1fae5;color:#065f46}
        .rc-banner-over{background:#fef3c7;color:#92400e}
        .rc-banner-err{background:#fee2e2;color:#991b1b}
        .rc-progress{height:8px;background:#e5e7eb;border-radius:6px;overflow:hidden;margin-top:.5rem}
        .rc-progress-bar{height:100%;width:0;background:#22c55e;transition:width .25s}
        .rc-progress-txt{font-size:.78rem;color:#6b7280;margin-top:.15rem;text-align:right}
        #rc_table .rc-code{font-family:'Courier New',Consolas,monospace;font-weight:900;font-size:1rem;color:#0040c0;letter-spacing:.04em}
        #rc_table .rc-name{font-weight:700;font-size:.88rem;line-height:1.3}
        #rc_table td{vertical-align:middle;padding:5px 6px!important}
        #rc_table .rc-qty{display:flex;align-items:center;gap:4px;justify-content:center}
        #rc_table .rc-qty input{width:64px;height:38px;text-align:center;font-weight:800;font-size:1rem;border-radius:8px}
        #rc_table .rc-qty .btn{height:38px;width:38px;padding:0;border-radius:8px;font-weight:800;font-size:1.1rem;line-height:1}
        #rc_table .rc-qty .rc-all{width:auto;padding:0 .5rem;font-size:.75rem;font-weight:700}
        #rc_table .pu,#rc_table .rem,#rc_table .tva{height:32px;padding:0 .3rem;border-radius:6px;width:84px}
        #rc_table .rem,#rc_table .tva{width:66px}
        #rc_table tr.rc-zero td{background:#fff}
        #rc_table tr.rc-done td{background:#ecfdf5}
        #rc_table tr.rc-part td{background:#fffbeb}
        #rc_table tr.rc-over td{background:#fff7ed}
        #rc_table tr.rc-extra td:first-child{border-left:4px solid #0d6efd}
        #rc_table tr.rc-flash td{animation:rcflash .9s ease}
        @keyframes rcflash{0%{background:#bfdbfe}100%{background:transparent}}
        .rc-badge{display:inline-block;font-size:.66rem;padding:.1em .45em;border-radius:4px;margin-left:4px;vertical-align:middle}
        .rc-badge-extra{background:#0d6efd;color:#fff}
        .rc-badge-over{background:#f97316;color:#fff}
        .rc-badge-diff{background:#fff3cd;color:#7a5b00;border:1px solid #ffe08a}
        .form-control-plaintext{min-height:38px;display:flex;align-items:center;padding:0 .2rem}
        .lh-actionbar .ab-btns .btn{min-width:190px}
        .lh-actionbar .ab-totals{flex-wrap:wrap;gap:.4rem 1.4rem}

        /* Caméra */
        .cam-overlay{position:fixed;inset:0;z-index:2000;background:#0b1220;display:flex;flex-direction:column}
        .cam-top{display:flex;justify-content:space-between;align-items:center;color:#fff;padding:.7rem .9rem}
        .cam-stage{position:relative;flex:1 1 auto;display:flex;align-items:center;justify-content:center;overflow:hidden}
        .cam-stage video{width:100%;height:100%;object-fit:cover}
        .cam-aim{position:absolute;width:78%;max-width:420px;height:34%;border:3px solid rgba(255,255,255,.85);border-radius:14px;box-shadow:0 0 0 9999px rgba(0,0,0,.35);pointer-events:none}

        /* Mobile : chaque ligne devient une carte */
        #rc_table .rc-qty .rc-all{white-space:nowrap}
        @media (max-width:768px){
            .rc-scan{top:0;margin-left:-.4rem;margin-right:-.4rem}
            .rc-scan-row{flex-wrap:wrap}
            .rc-scan-field{flex:1 1 100%}
            .rc-scan-row .rc-btn{flex:1 1 0}
            .rc-scan-row #btn_camera{flex:2 1 0}
            #rc_table .rc-qty .rc-all{width:auto;min-width:64px;padding:0 .4rem}
            #rc_table thead{display:none}
            #rc_table,#rc_table tbody,#rc_table tr,#rc_table td{display:block;width:100%}
            #rc_table tr{border:1px solid #dbe3ee;border-radius:12px;margin-bottom:.6rem;padding:.4rem .5rem;overflow:hidden}
            #rc_table td{display:flex;justify-content:space-between;align-items:center;border:0!important;padding:.2rem 0!important}
            #rc_table td::before{content:attr(data-label);font-size:.72rem;color:#6b7280;margin-right:.6rem;white-space:nowrap}
            #rc_table td.rc-art{display:block;padding-bottom:.35rem!important}
            #rc_table td.rc-art::before{content:none}
            #rc_table td.rc-qtycell{justify-content:center}
            #rc_table td.rc-qtycell::before{content:none}
            #rc_table .rc-qty input{width:84px;height:46px;font-size:1.2rem}
            #rc_table .rc-qty .btn{height:46px;width:46px}
            .lh-actionbar .ab-btns .btn{flex:1 1 100%}
            .lh-actionbar .ab-totals{font-size:.95rem}
        }
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
    <h4 class="lh-title"><i class="fas fa-undo-alt text-primary"></i> Nouveau retour fournisseur</h4>
    <div class="lh-btns">
        <a href="{{ route('returns.list') }}" class="btn btn-outline-secondary"><i class="fas fa-list"></i> Retours d'achat</a>
        <a href="{{ route('purchases.list') }}" class="btn btn-outline-secondary"><i class="fas fa-clipboard-list"></i> Commandes</a>
        <a href="{{ route('notes.list') }}" class="btn btn-outline-danger"><i class="fas fa-file-invoice"></i> Avoirs</a>
    </div>
</div>

@if(session('error'))<div class="alert alert-danger">{!! session('error') !!}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $er)<li>{{ $er }}</li>@endforeach</ul></div>@endif

<form method="POST" id="retForm" action="#" novalidate>
    @csrf
    <input type="hidden" name="type" id="ret_type" value="partiel">

    <div class="row top-row mb-2">
        <div class="col-lg-4 col-12 mb-2">
            <label class="form-label mb-1" for="supplier_id">Fournisseur</label>
            <select id="supplier_id" class="form-control">
                <option value="" disabled {{ $supplierId ? '' : 'selected' }}>Sélectionner un fournisseur</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ (string) $supplierId === (string) $s->id ? 'selected' : '' }}>{{ $s->code }} — {{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-12 mb-2">
            <label class="form-label mb-1" for="order_id">Commande d'origine</label>
            <select id="order_id" class="form-control" disabled><option value="">Choisissez d'abord un fournisseur</option></select>
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="return_date">Date du retour</label>
            <input type="date" name="return_date" id="return_date" value="{{ old('return_date', now()->format('Y-m-d')) }}" class="form-control order_date">
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1">Magasin de sortie</label>
            <div class="form-control-plaintext fw-bold" id="ret_store">—</div>
        </div>
    </div>

    <div id="ret_info" class="alert alert-light border" style="display:none;font-size:.88rem"></div>

    <div id="ret_lines_wrap" style="display:none">
        <div class="lh-filters">
            <div class="lh-field lh-wide"><i class="fas fa-search lh-ico"></i>
                <input type="text" id="ret_filter" class="form-control" placeholder="Filtrer les lignes : référence ou désignation…" autocomplete="off"></div>
            <button type="button" class="btn btn-outline-primary" id="ret_all"><i class="fas fa-check-double"></i> Tout retourner</button>
            <button type="button" class="btn btn-outline-secondary" id="ret_none"><i class="fas fa-times"></i> Tout décocher</button>
        </div>

        <div style="overflow-x:auto;">
            <table class="table table-striped table-bordered table-text-small" id="ret_table">
                <thead class="table-dark text-white">
                    <tr>
                        <th style="width:34px"></th><th>Référence</th><th>Désignation</th>
                        <th class="text-center">Stock actuel</th><th class="text-center">Reçu</th><th class="text-center">Déjà retourné</th><th class="text-center">Retournable</th>
                        <th class="text-center" style="width:150px">Qté à retourner</th><th class="text-end">PU net HT</th><th class="text-end">Total HT</th><th class="text-center">Stock après</th>
                    </tr>
                </thead>
                <tbody id="ret_body"></tbody>
            </table>
        </div>

        <div class="mb-3">
            <label class="form-label" for="notes">Motif / commentaire</label>
            <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Ex : marchandise défectueuse, erreur de livraison…">{{ old('notes') }}</textarea>
        </div>

        <div class="lh-actionbar">
            <div class="ab-totals">
                <div>Unités : <b id="t_units">0</b></div>
                <div>Total HT : <b id="t_ht" class="text-danger">0,00</b> €</div>
                <div>Total TTC : <b id="t_ttc" class="text-success">0,00</b> €</div>
            </div>
            <div class="ab-btns">
                <button type="button" class="btn btn-danger" id="ret_submit" disabled><i class="fas fa-undo-alt"></i> Créer le retour</button>
            </div>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function () {
    'use strict';
    var ORDERS_URL = @json(route('purchases.return.orders'));
    var LINES_URL = @json(url('/purchase-returns/orders'));
    var STORE_URL = @json(url('/purchases'));
    var PRESET_ORDER = @json($orderId);
    var $sup = $('#supplier_id'), $ord = $('#order_id'), cur = null;

    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
    function r2(x) { return Math.round((x + Number.EPSILON) * 100) / 100; }
    function num(v) { var n = parseFloat(String(v == null ? '' : v).replace(',', '.')); return isNaN(n) ? 0 : n; }
    function fr(n) { return r2(n).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    function q(n) { return (Math.round(n * 100) / 100).toLocaleString('fr-FR'); }

    $sup.select2({ width: '100%', placeholder: 'Sélectionner un fournisseur', language: { noResults: function () { return 'Aucun résultat'; } } });
    $ord.select2({ width: '100%', placeholder: 'Choisir la commande', language: { noResults: function () { return 'Aucune commande avec du retournable'; } } });

    function resetLines() { cur = null; $('#ret_body').empty(); $('#ret_lines_wrap, #ret_info').hide(); $('#ret_store').text('—'); totals(); }

    function loadOrders(selectId) {
        resetLines();
        var sid = $sup.val();
        $ord.prop('disabled', true).html('<option value="">Chargement…</option>').trigger('change.select2');
        if (!sid) { return; }
        $.getJSON(ORDERS_URL, { supplier_id: sid }, function (res) {
            var o = res.orders || [];
            var h = '<option value="">' + (o.length ? 'Choisir la commande (' + o.length + ')' : 'Aucune commande avec du retournable') + '</option>';
            o.forEach(function (x) { h += '<option value="' + x.id + '">' + esc(x.numdoc) + ' · ' + esc(x.date) + ' · ' + q(x.returnable) + ' retournable(s)</option>'; });
            $ord.html(h).prop('disabled', !o.length).val(selectId && o.some(function (x) { return String(x.id) === String(selectId); }) ? String(selectId) : '').trigger('change.select2');
            if ($ord.val()) $ord.trigger('change');
        }).fail(function () { $ord.html('<option value="">Erreur de chargement</option>').trigger('change.select2'); });
    }

    function rowHtml(l, i) {
        var dis = l.max <= 0 ? ' disabled' : '';
        return '<tr data-code="' + esc(l.code) + '" data-max="' + l.max + '" data-pu="' + (l.pu * (1 - l.remise / 100)) + '" data-tva="' + l.tva + '" data-stock="' + l.stock + '"' + (l.max <= 0 ? ' class="text-muted"' : '') + '>' +
            '<td class="text-center"><input type="checkbox" class="ret-sel" name="lines[' + esc(l.code) + '][selected]" value="1"' + dis + '></td>' +
            '<td><b>' + esc(l.code) + '</b><input type="hidden" name="lines[' + esc(l.code) + '][article_code]" value="' + esc(l.code) + '"></td>' +
            '<td>' + esc(l.name || '—') + '</td>' +
            '<td class="text-center">' + q(l.stock) + '</td><td class="text-center">' + q(l.received) + '</td><td class="text-center">' + q(l.returned) + '</td>' +
            '<td class="text-center"><b>' + q(l.max) + '</b></td>' +
            '<td><div class="input-group input-group-sm"><input type="number" min="0" max="' + l.max + '" step="1" class="form-control ret-q text-center" name="lines[' + esc(l.code) + '][returned_quantity]" value="0"' + dis + '>' +
            '<button type="button" class="btn btn-outline-secondary ret-max" title="Tout le retournable"' + dis + '>max</button></div></td>' +
            '<td class="text-end">' + fr(l.pu * (1 - l.remise / 100)) + '</td><td class="text-end ret-ht">0,00</td><td class="text-center ret-after">' + q(l.stock) + '</td></tr>';
    }

    function loadLines(id) {
        resetLines(); if (!id) return;
        $.getJSON(LINES_URL + '/' + id + '/lines', function (res) {
            cur = res.order; var lines = res.lines || [];
            $('#ret_body').html(lines.map(rowHtml).join(''));
            $('#retForm').attr('action', STORE_URL + '/' + id + '/return');
            var any = lines.some(function (l) { return l.max > 0; });
            $('#ret_info').html('Commande <b>' + esc(cur.numdoc) + '</b> · ' + (cur.reception_status === 'recue' ? 'entièrement reçue' : (cur.reception_status === 'partielle' ? 'reçue partiellement' : 'en attente de réception')) +
                ' — seule la marchandise <b>reçue</b> et pas encore retournée peut être retournée. Le stock diminue à la validation du retour.').show();
            $('#ret_store').text('Magasin de la commande');
            $('#ret_lines_wrap').toggle(any);
            if (!any) $('#ret_info').append('<br><span class="text-danger">Rien à retourner sur cette commande.</span>');
            totals();
        }).fail(function (x) { Swal.fire('Erreur', (x.responseJSON && x.responseJSON.message) || 'Chargement impossible.', 'error'); });
    }

    function recalcRow($tr) {
        var on = $tr.find('.ret-sel').is(':checked'), max = num($tr.data('max'));
        var qv = on ? Math.max(0, Math.min(max, num($tr.find('.ret-q').val()))) : 0;
        if (on && num($tr.find('.ret-q').val()) !== qv) $tr.find('.ret-q').val(qv);
        var ht = r2(qv * num($tr.data('pu')));
        $tr.find('.ret-ht').text(fr(ht)).data('v', ht).data('q', qv).data('ttc', r2(ht * (1 + num($tr.data('tva')) / 100)));
        var after = num($tr.data('stock')) - qv;
        $tr.find('.ret-after').text(q(after)).toggleClass('text-danger fw-bold', after < 0);
        $tr.toggleClass('table-warning', on && qv > 0);
    }

    function totals() {
        var u = 0, ht = 0, ttc = 0, neg = 0;
        $('#ret_body tr').each(function () { var $r = $(this); u += num($r.find('.ret-ht').data('q')); ht += num($r.find('.ret-ht').data('v')); ttc += num($r.find('.ret-ht').data('ttc')); if (num($r.data('stock')) - num($r.find('.ret-ht').data('q')) < 0) neg++; });
        $('#t_units').text(q(u)); $('#t_ht').text(fr(ht)); $('#t_ttc').text(fr(ttc));
        $('#ret_submit').prop('disabled', u <= 0);
        return { u: u, ht: ht, ttc: ttc, neg: neg };
    }

    $(document).on('change', '.ret-sel', function () {
        var $tr = $(this).closest('tr'); if (this.checked && num($tr.find('.ret-q').val()) <= 0) $tr.find('.ret-q').val(num($tr.data('max')));
        recalcRow($tr); totals();
    });
    $(document).on('input change', '.ret-q', function () { var $tr = $(this).closest('tr'); $tr.find('.ret-sel').prop('checked', num(this.value) > 0); recalcRow($tr); totals(); });
    $(document).on('click', '.ret-max', function () { var $tr = $(this).closest('tr'); $tr.find('.ret-sel').prop('checked', true); $tr.find('.ret-q').val(num($tr.data('max'))); recalcRow($tr); totals(); });
    $('#ret_all').on('click', function () { $('#ret_body tr').each(function () { var $tr = $(this); if (num($tr.data('max')) > 0) { $tr.find('.ret-sel').prop('checked', true); $tr.find('.ret-q').val(num($tr.data('max'))); recalcRow($tr); } }); totals(); });
    $('#ret_none').on('click', function () { $('#ret_body tr').each(function () { var $tr = $(this); $tr.find('.ret-sel').prop('checked', false); $tr.find('.ret-q').val(0); recalcRow($tr); }); totals(); });
    $('#ret_filter').on('input', function () {
        var t = this.value.toLowerCase().trim();
        $('#ret_body tr').each(function () { $(this).toggle(!t || $(this).text().toLowerCase().indexOf(t) !== -1); });
    });

    $sup.on('change', function () { loadOrders(null); });
    $ord.on('change', function () { loadLines($ord.val()); });

    $('#ret_submit').on('click', function () {
        var t = totals(); if (t.u <= 0 || !cur) return;
        // « total » si tout le retournable est retourné
        var all = true; $('#ret_body tr').each(function () { var m = num($(this).data('max')); if (m > 0 && num($(this).find('.ret-ht').data('q')) !== m) all = false; });
        $('#ret_type').val(all ? 'total' : 'partiel');
        var warn = t.neg ? '<div style="color:#b91c1c;margin-top:.5rem;font-size:.88rem"><b>' + t.neg + ' article(s)</b> dépassent le stock actuel : le retour sera refusé tant que le stock est insuffisant.</div>' : '';
        Swal.fire({
            icon: 'question', title: 'Créer le retour fournisseur ?',
            html: 'Commande <b>' + esc(cur.numdoc) + '</b><br>' + q(t.u) + ' unité(s) · Total HT ' + fr(t.ht) + ' € · <b>TTC ' + fr(t.ttc) + ' €</b><br><span style="font-size:.85rem;color:#6b7280">Le stock est diminué immédiatement. Le retour pourra ensuite être facturé (déduit d\'une facture groupée) ou donner lieu à un avoir.</span>' + warn,
            showCancelButton: true, confirmButtonText: 'Créer le retour', cancelButtonText: 'Annuler', confirmButtonColor: '#dc3545'
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $('#ret_submit').prop('disabled', true);
            // seules les lignes cochées partent
            $('#ret_body tr').each(function () { if (!$(this).find('.ret-sel').is(':checked')) $(this).find('input').prop('disabled', true); });
            document.getElementById('retForm').submit();
        });
    });

    $(function () { if ($sup.val()) loadOrders(PRESET_ORDER); });
})();
</script>

@include('partials.select2_init')
</body>
</html>
