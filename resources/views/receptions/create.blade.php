<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Réception d'achat</title>
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
    <h4 class="lh-title"><i class="fas fa-dolly text-primary"></i> Réception de la commande {{ $order->numdoc }}</h4>
    <div class="lh-btns">
        <a href="{{ route('purchases.list') }}" class="btn btn-outline-secondary"><i class="fas fa-list"></i> Commandes d'achat</a>
        <a href="{{ route('receptions.index') }}" class="btn btn-outline-primary"><i class="fas fa-clipboard-check"></i> Réceptions</a>
    </div>
</div>

<form action="{{ route('receptions.store') }}" method="POST" id="recForm" novalidate>
    @csrf
    <input type="hidden" name="purchase_order_id" value="{{ $order->id }}">
    <input type="hidden" name="action" id="form_action" value="">

    <div class="row top-row mb-2">
        <div class="col-lg-3 col-12 mb-2">
            <label class="form-label mb-1">Fournisseur</label>
            <div class="form-control-plaintext fw-bold">{{ $order->supplier->code ?? '' }} — {{ $order->supplier->name ?? '—' }}</div>
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="reception_date">Date de réception</label>
            <input type="date" name="reception_date" id="reception_date" value="{{ old('reception_date', now()->format('Y-m-d')) }}" class="form-control order_date">
        </div>
        <div class="col-lg-3 col-6 mb-2">
            <label class="form-label mb-1" for="store_id">Magasin</label>
            @if($stores->count() > 1)
                <select name="store_id" id="store_id" class="form-control js-s2">
                    @foreach($stores as $st)
                        <option value="{{ $st->id }}" {{ (string) old('store_id', $order->store_id) === (string) $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="store_id" id="store_id" value="{{ optional($stores->first())->id }}">
                <div class="form-control-plaintext fw-bold">{{ optional($stores->first())->name ?? '—' }}</div>
            @endif
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="supplier_ref">N° BL / facture fournisseur</label>
            <input type="text" name="supplier_ref" id="supplier_ref" value="{{ old('supplier_ref') }}" class="form-control" maxlength="60" autocomplete="off" placeholder="facultatif">
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="due_date">Échéance facture</label>
            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="form-control order_date">
        </div>
    </div>

    {{-- ═══ ZONE DE SCAN : douchette / PDA (clavier) ou caméra du smartphone ═══ --}}
    <div class="rc-scan" id="rc_scan">
        <div class="rc-scan-row">
            <div class="rc-scan-field">
                <i class="fas fa-barcode"></i>
                <input type="text" id="scan_input" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" inputmode="none"
                       placeholder="Scannez un code ou une référence">
            </div>
            <div class="rc-scan-qty" title="Quantité appliquée au prochain scan (revient à 1 ensuite)">
                <span>×</span><input type="number" id="scan_qty" min="1" step="1" value="1">
            </div>
            <button type="button" class="btn btn-primary rc-btn" id="btn_camera"><i class="fas fa-camera"></i> Caméra</button>
            <button type="button" class="btn btn-outline-secondary rc-btn" id="btn_keyboard" title="Afficher / masquer le clavier du téléphone ou du PDA"><i class="fas fa-keyboard"></i></button>
            <button type="button" class="btn btn-outline-secondary rc-btn" id="btn_sound" title="Son et vibration"><i class="fas fa-volume-up"></i></button>
        </div>
        <div id="scan_banner" class="rc-banner rc-banner-idle"><i class="fas fa-info-circle"></i> Scannez un article : chaque scan ajoute 1 (ou la quantité « × »). Un code hors commande vous sera proposé en ajout.</div>
        <div class="rc-progress"><div class="rc-progress-bar" id="rc_progress_bar"></div></div>
        <div class="rc-progress-txt"><span id="rc_progress_txt">0 / 0</span></div>
    </div>

    <div class="mb-3">
        <div style="overflow-x:auto;">
            <table class="table table-bordered table-text-small" id="rc_table">
                <thead class="table-dark text-white">
                    <tr style="height:42px;">
                        <th class="py-1">Article</th>
                        <th class="py-1 text-center">Commandé</th>
                        <th class="py-1 text-center">Déjà reçu</th>
                        <th class="py-1 text-center">Reçu maintenant</th>
                        <th class="py-1 text-center">Reste</th>
                        <th class="py-1 text-center">Prix achat HT</th>
                        <th class="py-1 text-center">Rem %</th>
                        <th class="py-1 text-center">TVA %</th>
                        <th class="py-1 text-center">Total HT</th>
                    </tr>
                </thead>
                <tbody id="rc_body"></tbody>
            </table>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <button type="button" class="btn btn-outline-success btn-sm" id="btn_all"><i class="fas fa-check-double"></i> Tout recevoir (le reste)</button>
            <button type="button" class="btn btn-outline-danger btn-sm" id="btn_zero"><i class="fas fa-undo"></i> Remettre à zéro</button>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="notes">Notes / Commentaire</label>
        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Colis abîmé, manquant, remarque pour la facturation…">{{ old('notes') }}</textarea>
    </div>

    <div class="lh-actionbar">
        <div class="ab-totals">
            <div>Unités : <b id="tot_units">0</b></div>
            <div>Total HT : <b id="tot_ht" class="text-danger">0,00</b> €</div>
            <div>Total TTC : <b id="tot_ttc" class="text-success">0,00</b> €</div>
        </div>
        <div class="ab-btns">
            <button type="button" class="btn btn-primary js-act" data-action="receive"><i class="fas fa-dolly"></i> Valider la réception</button>
            <button type="button" class="btn btn-success js-act" data-action="receive_invoice"><i class="fas fa-file-invoice-dollar"></i> Réceptionner et facturer</button>
        </div>
    </div>
</form>

{{-- ═══ SCANNER CAMÉRA (smartphone) ═══ --}}
<div id="cam_overlay" class="cam-overlay" style="display:none;">
    <div class="cam-top">
        <b><i class="fas fa-camera"></i> Scanner caméra</b>
        <div>
            <button type="button" id="cam_torch" class="btn btn-sm btn-outline-light" style="display:none;"><i class="fas fa-bolt"></i></button>
            <button type="button" id="cam_close" class="btn btn-sm btn-light"><i class="fas fa-times"></i> Fermer</button>
        </div>
    </div>
    <div class="cam-stage">
        <video id="cam_video" playsinline muted autoplay></video>
        <div id="cam_reader" style="display:none;width:100%;"></div>
        <div class="cam-aim"></div>
    </div>
    <div id="cam_banner" class="rc-banner rc-banner-idle" style="margin:.6rem;">Placez le code-barres dans le cadre. Le scan est continu.</div>
</div>

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

    var LINES    = @json($lines);
    var OLD      = @json(old('lines', []));
    var LOOKUP   = @json(route('receptions.lookup'));
    var SUPPLIER = @json($order->supplier_id);
    var DRAFT    = 'aznegoce_reception_draft_v1_' + @json($order->id);

    var model = [];          // { k, olid, code, name, barcode, ordered, received, pu, remise, tva, extra, qty }
    var seq = 0, submitting = false, soundOn = true, camStream = null, camTimer = null, camBusy = false, torchOn = false;

    /* ───────── utilitaires ───────── */
    function esc(s) { return String(s === null || s === undefined ? '' : s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
    function r2(x) { return Math.round((x + Number.EPSILON) * 100) / 100; }
    function num(v) { var n = parseFloat(String(v === undefined || v === null ? '' : v).replace(',', '.')); return isNaN(n) ? 0 : n; }
    function int(v) { var n = Math.floor(num(v)); return n < 0 ? 0 : n; }
    function fr(n) { return r2(n).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    function lsGet(k) { try { return window.localStorage.getItem(k); } catch (e) { return null; } }
    function lsSet(k, v) { try { window.localStorage.setItem(k, v); } catch (e) {} }
    function lsDel(k) { try { window.localStorage.removeItem(k); } catch (e) {} }
    function byK(k) { for (var i = 0; i < model.length; i++) if (model[i].k === k) return model[i]; return null; }
    function norm(s) { return String(s || '').trim().toLowerCase(); }

    /* ───────── son + vibration ───────── */
    var actx = null;
    function tone(freq, ms, when) {
        if (!soundOn) return;
        try {
            actx = actx || new (window.AudioContext || window.webkitAudioContext)();
            var o = actx.createOscillator(), g = actx.createGain(), t = actx.currentTime + (when || 0);
            o.type = 'square'; o.frequency.value = freq; g.gain.value = 0.08;
            o.connect(g); g.connect(actx.destination); o.start(t); o.stop(t + ms / 1000);
        } catch (e) {}
    }
    function vib(p) {
        try { if (soundOn && navigator.vibrate && (!navigator.userActivation || navigator.userActivation.hasBeenActive)) navigator.vibrate(p); } catch (e) {}
    }
    function feedback(kind) {
        if (kind === 'ok')   { tone(1100, 90); vib(40); }
        if (kind === 'over') { tone(700, 90); tone(700, 90, 0.14); vib([60, 40, 60]); }
        if (kind === 'err')  { tone(180, 380); vib(300); }
    }
    function banner(kind, html) {
        var cls = 'rc-banner rc-banner-' + kind;
        $('#scan_banner, #cam_banner').attr('class', cls).html(html);
        $('#cam_banner').css('margin', '.6rem');
        feedback(kind);
    }

    /* ───────── modèle -> lignes ───────── */
    function rowHtml(l) {
        var n = 'lines[' + l.k + ']';
        return '<tr data-k="' + l.k + '" class="rc-row' + (l.extra ? ' rc-extra' : '') + '">' +
            '<td class="rc-art" data-label="Article"><span class="rc-code">' + esc(l.code) + '</span>' + (l.extra ? '<span class="rc-badge rc-badge-extra">hors commande</span>' : '') + '<span class="rc-badge rc-badge-over" style="display:none">surplus</span><div class="rc-name">' + esc(l.name) + '</div>' +
              '<input type="hidden" name="' + n + '[article_code]" value="' + esc(l.code) + '">' +
              '<input type="hidden" name="' + n + '[order_line_id]" value="' + (l.olid || '') + '">' +
              '<input type="hidden" name="' + n + '[_name]" value="' + esc(l.name) + '">' +
              '<input type="hidden" name="' + n + '[_barcode]" value="' + esc(l.barcode || '') + '"></td>' +
            '<td class="text-center" data-label="Commandé">' + (l.extra ? '—' : l.ordered) + '</td>' +
            '<td class="text-center" data-label="Déjà reçu">' + (l.extra ? '—' : l.received) + '</td>' +
            '<td class="rc-qtycell" data-label="Reçu maintenant"><div class="rc-qty">' +
              '<button type="button" class="btn btn-outline-secondary rc-minus">−</button>' +
              '<input type="number" min="0" step="1" inputmode="numeric" class="form-control rq" name="' + n + '[received_quantity]" value="' + l.qty + '">' +
              '<button type="button" class="btn btn-outline-primary rc-plus">+</button>' +
              (l.extra ? '' : '<button type="button" class="btn btn-outline-success rc-all" title="Recevoir tout le reste">Reste</button>') + '</div></td>' +
            '<td class="text-center rc-rest" data-label="Reste"></td>' +
            '<td data-label="Prix achat HT"><input type="number" min="0" step="0.01" class="form-control pu" name="' + n + '[unit_price_ht]" value="' + num(l.pu).toFixed(2) + '"></td>' +
            '<td data-label="Rem %"><input type="number" min="0" max="100" step="0.01" class="form-control rem" name="' + n + '[remise]" value="' + num(l.remise).toFixed(2) + '"></td>' +
            '<td data-label="TVA %"><input type="number" min="0" max="100" step="0.01" class="form-control tva" name="' + n + '[tva]" value="' + esc(l.tva) + '"></td>' +
            '<td class="text-center fw-bold rc-tot" data-label="Total HT">0,00</td>' +
            '</tr>';
    }

    function addModel(o) {
        var l = { k: ++seq, olid: o.olid || null, code: o.code, name: o.name, barcode: o.barcode || '', ordered: o.ordered || 0, received: o.received || 0,
                  pu: o.pu || 0, remise: o.remise || 0, tva: o.tva === undefined ? 0 : o.tva, extra: !!o.extra, qty: o.qty || 0, origPu: o.pu || 0, origRem: o.remise || 0 };
        model.push(l);
        $('#rc_body').append(rowHtml(l));
        refreshRow(l);
        return l;
    }

    function rowOf(l) { return $('#rc_body tr[data-k="' + l.k + '"]'); }

    function refreshRow(l) {
        var $tr = rowOf(l);
        $tr.find('.rq').val(l.qty);
        var rest = l.extra ? 0 : Math.max(0, l.ordered - l.received - l.qty);
        var over = l.extra ? 0 : Math.max(0, l.received + l.qty - l.ordered);
        $tr.find('.rc-rest').text(l.extra ? '—' : rest);
        $tr.find('.rc-badge-over').toggle(over > 0).text('surplus +' + over);
        $tr.removeClass('rc-zero rc-part rc-done rc-over');
        if (l.qty <= 0) $tr.addClass('rc-zero');
        else if (over > 0) $tr.addClass('rc-over');
        else if (l.extra || rest === 0) $tr.addClass('rc-done');
        else $tr.addClass('rc-part');
        var net = num(l.pu) * (1 - num(l.remise) / 100);
        $tr.find('.rc-tot').text(fr(l.qty * net));
    }

    function totals() {
        var units = 0, ht = 0, ttc = 0, expected = 0, got = 0;
        model.forEach(function (l) {
            var net = num(l.pu) * (1 - num(l.remise) / 100), lht = r2(l.qty * net);
            units += l.qty; ht += lht; ttc += r2(lht * (1 + num(l.tva) / 100));
            if (!l.extra) { expected += Math.max(0, l.ordered - l.received); got += Math.min(l.qty, Math.max(0, l.ordered - l.received)); }
        });
        $('#tot_units').text(units); $('#tot_ht').text(fr(ht)); $('#tot_ttc').text(fr(ttc));
        var pct = expected > 0 ? Math.min(100, Math.round(got / expected * 100)) : 100;
        $('#rc_progress_bar').css('width', pct + '%');
        $('#rc_progress_txt').text(got + ' / ' + expected + ' unités attendues (' + pct + ' %)');
        return { units: units, ht: r2(ht), ttc: r2(ttc) };
    }

    function setQty(l, q) { l.qty = Math.max(0, int(q)); refreshRow(l); totals(); scheduleSave(); }

    /* ───────── scan ───────── */
    function findLine(code) {
        var c = norm(code); if (!c) return null;
        for (var i = 0; i < model.length; i++) { if (norm(model[i].code) === c || (model[i].barcode && norm(model[i].barcode) === c)) return model[i]; }
        return null;
    }

    function flash($tr) {
        $tr.removeClass('rc-flash'); void $tr[0].offsetWidth; $tr.addClass('rc-flash');
        var el = $tr[0]; if (el && el.scrollIntoView && !$('#cam_overlay').is(':visible')) el.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }

    function applyScan(l, mult) {
        l.qty += mult; refreshRow(l); totals(); scheduleSave(); flash(rowOf(l));
        var over = l.extra ? 0 : Math.max(0, l.received + l.qty - l.ordered);
        var label = '<b>' + esc(l.code) + '</b> ' + esc(l.name);
        if (over > 0) banner('over', '<i class="fas fa-exclamation-triangle"></i> Surplus : ' + label + ' — ' + (l.received + l.qty) + ' / ' + l.ordered + ' (+' + over + ')');
        else if (l.extra) banner('ok', '<i class="fas fa-check-circle"></i> ' + label + ' — hors commande : ' + l.qty);
        else banner('ok', '<i class="fas fa-check-circle"></i> ' + label + ' — ' + (l.received + l.qty) + ' / ' + l.ordered);
    }

    // Caméra : un code qui reste dans le cadre n'est compté qu'UNE fois. Pour recompter, il doit sortir du cadre
    // (plus de détection pendant 0,9 s) puis revenir ; pour plusieurs unités, utiliser le champ « × ».
    var lastScan = { code: '', seen: 0 };
    function processScan(raw, mult, fromCamera) {
        var code = $.trim(raw); if (!code) return;
        mult = Math.max(1, int(mult) || 1);
        if (fromCamera) {
            var now = Date.now(), same = lastScan.code === code && now - lastScan.seen < 900;
            lastScan = { code: code, seen: now };
            if (same) return;
        }
        var l = findLine(code);
        if (l) { applyScan(l, mult); return; }

        $.getJSON(LOOKUP, { code: code, supplier_id: SUPPLIER }).done(function (r) {
            if (!r || !r.found) { banner('err', '<i class="fas fa-times-circle"></i> Code inconnu : <b>' + esc(code) + '</b>'); return; }
            var again = findLine(r.code);     // le code-barres peut correspondre à une ligne déjà présente (autre code saisi)
            if (again) { if (r.barcode && !again.barcode) again.barcode = r.barcode; applyScan(again, mult); return; }
            if (fromCamera) closeCamera();
            Swal.fire({
                icon: 'question', title: 'Article hors commande',
                html: '<b>' + esc(r.code) + '</b><br>' + esc(r.name) + '<br><small>Cet article n\'est pas sur la commande. L\'ajouter à cette réception (' + mult + ' unité(s)) ?</small>',
                showCancelButton: true, confirmButtonText: 'Ajouter', cancelButtonText: 'Ignorer'
            }).then(function (res) {
                if (res.isConfirmed) {
                    var nl = addModel({ code: r.code, name: r.name, barcode: r.barcode, pu: r.pu, remise: r.remise, tva: r.tva, extra: true, qty: 0 });
                    applyScan(nl, mult); totals();
                } else { banner('idle', 'Scan ignoré : ' + esc(r.code)); }
                focusScan();
            });
        }).fail(function () { banner('err', '<i class="fas fa-wifi"></i> Réseau indisponible : impossible de vérifier ' + esc(code)); });
    }

    function focusScan() { setTimeout(function () { var el = document.getElementById('scan_input'); if (el && !$('.swal2-container').length && !$('#cam_overlay').is(':visible')) el.focus(); }, 60); }

    $('#scan_input').on('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== 'Tab') return;
        var v = this.value; if (!$.trim(v)) { if (e.key === 'Enter') e.preventDefault(); return; }
        e.preventDefault();
        this.value = '';
        var m = int($('#scan_qty').val()) || 1; $('#scan_qty').val(1);
        processScan(v, m, false);
    });
    $('#scan_qty').on('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); focusScan(); } });
    $('#scan_qty').on('focus', function () { this.select(); });

    // Clavier virtuel : masqué par défaut (PDA / douchette), affichable pour taper à la main
    var kbOn = lsGet('aznegoce_rc_kb') === '1';
    function applyKb() { $('#scan_input').attr('inputmode', kbOn ? 'text' : 'none'); $('#btn_keyboard').toggleClass('btn-secondary', kbOn).toggleClass('btn-outline-secondary', !kbOn); }
    $('#btn_keyboard').on('click', function () { kbOn = !kbOn; lsSet('aznegoce_rc_kb', kbOn ? '1' : '0'); applyKb(); var el = document.getElementById('scan_input'); el.blur(); el.focus(); });
    applyKb();
    soundOn = lsGet('aznegoce_rc_sound') !== '0';
    function applySound() { $('#btn_sound i').attr('class', 'fas ' + (soundOn ? 'fa-volume-up' : 'fa-volume-mute')); }
    $('#btn_sound').on('click', function () { soundOn = !soundOn; lsSet('aznegoce_rc_sound', soundOn ? '1' : '0'); applySound(); if (soundOn) feedback('ok'); focusScan(); });
    applySound();

    /* ───────── caméra (smartphone) ───────── */
    function closeCamera() {
        clearTimeout(camTimer); camTimer = null; camBusy = false;
        if (camStream) { camStream.getTracks().forEach(function (t) { try { t.stop(); } catch (e) {} }); camStream = null; }
        if (window.__h5q) { try { window.__h5q.stop().then(function () { window.__h5q.clear(); }).catch(function () {}); } catch (e) {} window.__h5q = null; }
        $('#cam_overlay').hide(); $('#cam_reader').hide().empty(); $('#cam_video').show();
        focusScan();
    }

    function openCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            Swal.fire('Caméra indisponible', 'Le navigateur n\'autorise pas la caméra. Elle exige une page en HTTPS ; sinon utilisez la douchette ou saisissez le code.', 'warning'); return;
        }
        $('#cam_overlay').css('display', 'flex');
        var hasNative = ('BarcodeDetector' in window);
        if (hasNative) { startNative(); } else { startFallback(); }
    }

    function startNative() {
        var detector;
        try { detector = new window.BarcodeDetector({ formats: ['ean_13', 'ean_8', 'code_128', 'code_39', 'upc_a', 'upc_e', 'itf', 'qr_code', 'data_matrix'] }); }
        catch (e) { detector = new window.BarcodeDetector(); }
        navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false }).then(function (stream) {
            camStream = stream;
            var v = document.getElementById('cam_video'); v.srcObject = stream; v.play && v.play().catch(function () {});
            var track = stream.getVideoTracks()[0], caps = (track && track.getCapabilities) ? track.getCapabilities() : {};
            $('#cam_torch').toggle(!!caps.torch);
            (function loop() {
                if (!camStream) return;
                if (!camBusy && v.readyState >= 2) {
                    camBusy = true;
                    detector.detect(v).then(function (codes) {
                        if (codes && codes.length) { processScan(codes[0].rawValue, 1, true); }
                    }).catch(function () {}).then(function () { camBusy = false; });
                }
                camTimer = setTimeout(loop, 140);
            })();
        }).catch(function (e) { closeCamera(); Swal.fire('Caméra refusée', 'Autorisez l\'accès à la caméra dans le navigateur puis réessayez.', 'warning'); });
    }

    function startFallback() {      // iPhone / Safari / Firefox : bibliothèque html5-qrcode chargée à la demande
        function go() {
            $('#cam_video').hide(); $('#cam_reader').show();
            var h = new window.Html5Qrcode('cam_reader'); window.__h5q = h;
            h.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 260, height: 160 } }, function (text) { processScan(text, 1, true); }, function () {})
             .catch(function () { closeCamera(); Swal.fire('Caméra refusée', 'Autorisez l\'accès à la caméra dans le navigateur puis réessayez.', 'warning'); });
        }
        if (window.Html5Qrcode) { go(); return; }
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js';
        s.onload = go;
        s.onerror = function () { closeCamera(); Swal.fire('Scanner indisponible', 'Impossible de charger le lecteur de codes-barres (connexion). Utilisez la douchette ou saisissez le code.', 'error'); };
        document.head.appendChild(s);
    }

    $('#btn_camera').on('click', openCamera);
    $('#cam_close').on('click', closeCamera);
    $('#cam_torch').on('click', function () {
        if (!camStream) return; var track = camStream.getVideoTracks()[0]; torchOn = !torchOn;
        try { track.applyConstraints({ advanced: [{ torch: torchOn }] }); } catch (e) {}
    });
    $(document).on('keydown', function (e) { if (e.key === 'Escape' && $('#cam_overlay').is(':visible')) closeCamera(); });

    /* ───────── saisie manuelle ───────── */
    $(document).on('click', '.rc-plus', function () { var l = byK(+$(this).closest('tr').data('k')); setQty(l, l.qty + 1); });
    $(document).on('click', '.rc-minus', function () { var l = byK(+$(this).closest('tr').data('k')); setQty(l, l.qty - 1); });
    $(document).on('click', '.rc-all', function () { var l = byK(+$(this).closest('tr').data('k')); setQty(l, Math.max(0, l.ordered - l.received)); });
    $(document).on('input change', '.rq', function () { var l = byK(+$(this).closest('tr').data('k')); l.qty = int(this.value); refreshRow(l); totals(); scheduleSave(); });
    $(document).on('input change', '.pu, .rem, .tva', function () {
        var l = byK(+$(this).closest('tr').data('k'));
        l.pu = num($(this).closest('tr').find('.pu').val()); l.remise = num($(this).closest('tr').find('.rem').val()); l.tva = num($(this).closest('tr').find('.tva').val());
        refreshRow(l); totals(); scheduleSave();
    });
    $(document).on('focus click', '#rc_table input[type=number]', function () { this.select(); });
    $('#btn_all').on('click', function () { model.forEach(function (l) { if (!l.extra) { l.qty = Math.max(0, l.ordered - l.received); refreshRow(l); } }); totals(); scheduleSave(); });
    $('#btn_zero').on('click', function () {
        Swal.fire({ icon: 'warning', title: 'Remettre à zéro ?', text: 'Toutes les quantités saisies ou scannées seront effacées.', showCancelButton: true, confirmButtonText: 'Remettre à zéro', cancelButtonText: 'Annuler' })
            .then(function (r) { if (!r.isConfirmed) return; model = model.filter(function (l) { return !l.extra; }); $('#rc_body tr.rc-extra').remove(); model.forEach(function (l) { l.qty = 0; refreshRow(l); }); totals(); lsDel(DRAFT); focusScan(); });
    });
    $('#recForm').on('keydown', function (e) { if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'BUTTON') { e.preventDefault(); if ($(e.target).hasClass('rq')) focusScan(); } });
    $('#recForm').on('submit', function (e) { e.preventDefault(); });

    /* ───────── validation + envoi ───────── */
    var CONF = {
        receive:         { title: 'Valider la réception ?', ok: 'Valider la réception', color: '#0d6efd', text: 'Le stock est augmenté immédiatement et le coût des articles est mis à jour (dernier prix d\'achat).' },
        receive_invoice: { title: 'Réceptionner et facturer ?', ok: 'Réceptionner et facturer', color: '#198754', text: 'Le stock est augmenté, la facture d\'achat est validée et le solde du fournisseur augmente du total TTC.' }
    };

    $(document).on('click', '.js-act', function () {
        if (submitting) return;
        var action = $(this).data('action'), cfg = CONF[action], t = totals();
        if (t.units <= 0) { Swal.fire({ icon: 'warning', title: 'Rien à enregistrer', text: 'Saisissez ou scannez au moins une quantité reçue.' }); return; }
        var bad = null;
        model.forEach(function (l) { if (!bad && l.qty > 0 && (num(l.pu) < 0 || l.remise < 0 || l.remise > 100 || l.tva < 0 || l.tva > 100)) bad = l; });
        if (bad) { Swal.fire({ icon: 'warning', title: 'Valeur invalide', text: 'Vérifiez prix, remise (0–100) et TVA (0–100) de ' + bad.code + '.' }); return; }

        var surplus = [], missing = 0, extras = 0, lines = 0;
        model.forEach(function (l) {
            if (l.qty > 0) lines++;
            if (l.extra) { if (l.qty > 0) extras++; return; }
            var over = Math.max(0, l.received + l.qty - l.ordered); if (over > 0) surplus.push(l.code + ' (+' + over + ')');
            missing += Math.max(0, l.ordered - l.received - l.qty);
        });
        var extra = '<div style="margin-top:.6rem;font-size:.92rem;text-align:left">' + lines + ' ligne(s) · <b>' + t.units + ' unité(s)</b> · Total HT ' + fr(t.ht) + ' € · <b>TTC ' + fr(t.ttc) + ' €</b>' +
            (missing > 0 ? '<br><span style="color:#92400e">Il restera <b>' + missing + '</b> unité(s) à recevoir : la commande restera « partielle ».</span>' : '<br><span style="color:#065f46">Commande entièrement reçue.</span>') +
            (surplus.length ? '<br><span style="color:#c2410c">Surplus : ' + esc(surplus.join(', ')) + '</span>' : '') +
            (extras ? '<br><span style="color:#0d6efd">' + extras + ' article(s) hors commande.</span>' : '') + '</div>';
        Swal.fire({ icon: 'question', title: cfg.title, html: esc(cfg.text) + extra, showCancelButton: true, confirmButtonText: cfg.ok, cancelButtonText: 'Annuler', confirmButtonColor: cfg.color })
            .then(function (res) {
                if (!res.isConfirmed) { focusScan(); return; }
                submitting = true; $('#form_action').val(action); $('.js-act').prop('disabled', true);
                clearTimeout(saveTimer); lsDel(DRAFT);
                document.getElementById('recForm').submit();
            });
    });
    window.addEventListener('pageshow', function (ev) { if (ev.persisted) { submitting = false; $('.js-act').prop('disabled', false); } });

    /* ───────── brouillon local (scan interrompu, batterie, changement de page) ───────── */
    var saveTimer = null;
    function scheduleSave() { clearTimeout(saveTimer); saveTimer = setTimeout(saveDraft, 500); }
    function saveDraft() {
        if (submitting) return;
        var d = { t: Date.now(), ref: $('#supplier_ref').val(), notes: $('#notes').val(), lines: [] };
        model.forEach(function (l) { if (l.qty > 0 || l.extra) d.lines.push({ code: l.code, qty: l.qty, extra: l.extra, name: l.name, barcode: l.barcode, pu: l.pu, remise: l.remise, tva: l.tva }); });
        if (!d.lines.length) { lsDel(DRAFT); return; }
        lsSet(DRAFT, JSON.stringify(d));
    }
    $('#supplier_ref, #notes').on('input', scheduleSave);

    /* ───────── démarrage ───────── */
    $(function () {
        LINES.forEach(function (o) {
            addModel({ olid: o.order_line_id, code: o.code, name: o.name, barcode: o.barcode, ordered: o.ordered, received: o.received, pu: o.pu, remise: o.remise, tva: o.tva, qty: 0 });
        });

        var restored = 0, oldArr = [];
        if (OLD && typeof OLD === 'object') Object.keys(OLD).forEach(function (k) { oldArr.push(OLD[k]); });
        if (oldArr.length) {            // retour d'une erreur serveur : on remet ce qui était saisi
            oldArr.forEach(function (o) {
                var q = int(o.received_quantity); if (q <= 0) return;
                var l = findLine(o.article_code);
                if (!l) l = addModel({ code: o.article_code, name: o._name || o.article_code, barcode: o._barcode, pu: num(o.unit_price_ht), remise: num(o.remise), tva: num(o.tva), extra: true, qty: 0 });
                l.qty = q; l.pu = num(o.unit_price_ht); l.remise = num(o.remise); l.tva = num(o.tva); refreshRow(l); restored += q;
                var $r = rowOf(l); $r.find('.pu').val(l.pu.toFixed(2)); $r.find('.rem').val(l.remise.toFixed(2)); $r.find('.tva').val(l.tva);
            });
        } else {
            var raw = lsGet(DRAFT), d = null; try { d = raw ? JSON.parse(raw) : null; } catch (e) { d = null; }
            if (d && d.lines && Date.now() - d.t < 86400000) {
                d.lines.forEach(function (o) {
                    var l = findLine(o.code);
                    if (!l && o.extra) l = addModel({ code: o.code, name: o.name, barcode: o.barcode, pu: o.pu, remise: o.remise, tva: o.tva, extra: true, qty: 0 });
                    if (!l) return;
                    l.qty = int(o.qty); if (o.pu !== undefined) { l.pu = num(o.pu); l.remise = num(o.remise); l.tva = num(o.tva); }
                    refreshRow(l); var $r = rowOf(l); $r.find('.pu').val(l.pu.toFixed(2)); $r.find('.rem').val(l.remise.toFixed(2)); $r.find('.tva').val(l.tva); restored += l.qty;
                });
                if (!$('#supplier_ref').val()) $('#supplier_ref').val(d.ref || ''); if (!$('#notes').val()) $('#notes').val(d.notes || '');
            }
        }
        totals();
        if (restored > 0) { banner('over', '<i class="fas fa-history"></i> Saisie en cours reprise (' + restored + ' unité(s)). « Remettre à zéro » pour repartir de rien.'); }
        focusScan();
    });
})();
</script>

@include('partials.select2_init')
</body>
</html>
