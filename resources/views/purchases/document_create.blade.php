<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Créer un document d'achat</title>
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
        #ins_strip{border:1px solid #cfe0ff;background:#f5f9ff;border-radius:10px;padding:.6rem .8rem;margin-bottom:.7rem}
        #ins_strip .is-kpis{display:flex;flex-wrap:wrap;gap:.5rem;align-items:stretch}
        #ins_strip .is-k{background:#fff;border:1px solid #dbe3ee;border-radius:8px;padding:.3rem .65rem;min-width:118px}
        #ins_strip .is-k span{display:block;font-size:.68rem;color:#6b7280;text-transform:uppercase;letter-spacing:.02em}
        #ins_strip .is-k b{font-size:1.1rem}
        #ins_strip .is-k.bad{border-color:#fecaca;background:#fff5f5}#ins_strip .is-k.warn{border-color:#fed7aa;background:#fffaf0}
        #ins_strip .is-acts{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.55rem;align-items:center}
        #ins_strip .is-acts .btn{font-size:.8rem}
        #ins_strip .is-par{display:flex;gap:.4rem;align-items:center;font-size:.78rem;color:#4b5563;margin-left:auto}
        #ins_strip .is-par input{width:62px;height:28px;font-size:.8rem;padding:.1rem .3rem}
        .ins-chip{margin-top:.25rem;font-size:.72rem;line-height:1.35;color:#4b5563}
        .ins-chip .st{display:inline-block;padding:.03rem .45rem;border-radius:20px;color:#fff;font-weight:700;font-size:.66rem;margin-right:.3rem}
        .st-rupture{background:#b91c1c}.st-critique{background:#dc2626}.st-a_commander{background:#d97706}.st-ok{background:#16a34a}.st-surstock{background:#2563eb}.st-dormant{background:#6b7280}.st-sans_ventes{background:#9ca3af}
        .ins-chip a.act{cursor:pointer;font-weight:700;margin-left:.35rem;text-decoration:underline dotted}
        .ins-chip .alt{color:#7c3aed}
        .pa-tbl th{font-size:.72rem;white-space:nowrap}.pa-tbl td{font-size:.8rem;vertical-align:middle}
        #pa_overlay{position:fixed;inset:0;z-index:20000;background:rgba(17,24,39,.55);display:none;align-items:flex-start;justify-content:center;padding:4vh 1rem;overflow:auto}
        #pa_overlay.open{display:flex}
        .pa-box{background:#fff;border-radius:14px;width:100%;max-width:900px;box-shadow:0 20px 60px rgba(0,0,0,.35);display:flex;flex-direction:column;max-height:92vh}
        .pa-head{padding:1rem 1.25rem .6rem;border-bottom:1px solid #eef0f4;display:flex;justify-content:space-between;gap:1rem}
        .pa-head h5{margin:0;font-weight:700}.pa-head p{margin:.2rem 0 0;color:#6b7280;font-size:.88rem}
        .pa-x{border:0;background:none;font-size:1.6rem;line-height:1;color:#9ca3af;cursor:pointer}.pa-x:hover{color:#111}
        .pa-body{padding:1rem 1.25rem;overflow:auto;flex:1}
        .pa-foot{padding:.8rem 1.25rem;border-top:1px solid #eef0f4;display:flex;flex-wrap:wrap;gap:.6rem;justify-content:space-between;align-items:center;background:#fafbfc;border-radius:0 0 14px 14px}
        .pa-foot .btn-ok{background:#16a34a;border:0;color:#fff;font-weight:700;padding:.6rem 1.4rem;border-radius:9px}.pa-foot .btn-ok:disabled{background:#a7d7b6;cursor:not-allowed}
        .pa-q{font-weight:700;font-size:.9rem;margin:.1rem 0 .5rem}
        .pa-modes{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:.6rem;margin-bottom:.7rem}
        .pa-mode{border:2px solid #e5e7eb;border-radius:10px;padding:.65rem .8rem;cursor:pointer;display:block;margin:0;transition:.12s}
        .pa-mode:hover{border-color:#93c5fd}
        .pa-mode input{display:none}
        .pa-mode.sel{border-color:#2563eb;background:#eff6ff}
        .pa-mode b{display:block;font-size:.9rem}.pa-mode small{color:#6b7280;font-size:.78rem;line-height:1.3;display:block;margin-top:.15rem}
        .pa-mode .pa-inline{display:inline-flex;align-items:center;gap:.3rem;margin-top:.35rem}.pa-mode .pa-inline input{display:inline-block;width:64px;padding:.15rem .3rem;border:1px solid #cbd5e1;border-radius:6px;text-align:center}
        .pa-round{display:flex;gap:.5rem;align-items:center;font-size:.85rem;color:#4b5563;margin-bottom:.8rem;flex-wrap:wrap}
        .pa-round select{border:1px solid #cbd5e1;border-radius:6px;padding:.2rem .4rem;font-size:.85rem}
        .pa-sum{display:flex;gap:.5rem;flex-wrap:wrap;margin:.2rem 0 .8rem}
        .pa-pill{border-radius:20px;padding:.2rem .7rem;font-size:.8rem;font-weight:700}
        .pa-pill.red{background:#fee2e2;color:#b91c1c}.pa-pill.orange{background:#ffedd5;color:#c2410c}.pa-pill.blue{background:#dbeafe;color:#1d4ed8}.pa-pill.green{background:#dcfce7;color:#15803d}
        .pa-card{display:grid;grid-template-columns:1fr auto;gap:.8rem;border:1px solid #e5e7eb;border-left:5px solid #9ca3af;border-radius:10px;padding:.7rem .85rem;margin-bottom:.55rem;background:#fff}
        .pa-card.perte{border-left-color:#dc2626;background:#fffafa}.pa-card.marge_faible,.pa-card.hausse{border-left-color:#f59e0b}.pa-card.baisse{border-left-color:#3b82f6}.pa-card.ok{border-left-color:#22c55e}
        .pa-card.off{opacity:.55}
        .pa-card h6{margin:0;font-size:.92rem;font-weight:700}.pa-card h6 small{color:#6b7280;font-weight:500}
        .pa-msg{font-size:.84rem;margin:.25rem 0 0;color:#374151;line-height:1.4}
        .pa-msg .tag{font-weight:700}
        .pa-msg .tag.red{color:#b91c1c}.pa-msg .tag.orange{color:#c2410c}.pa-msg .tag.blue{color:#1d4ed8}.pa-msg .tag.green{color:#15803d}
        .pa-nums{display:flex;gap:1rem;flex-wrap:wrap;margin-top:.4rem;font-size:.78rem;color:#6b7280}
        .pa-nums b{color:#111}
        .pa-right{text-align:right;min-width:190px}
        .pa-price{display:flex;align-items:center;gap:.4rem;justify-content:flex-end}
        .pa-price .old{color:#9ca3af;text-decoration:line-through;font-size:.85rem}
        .pa-price input.pa-new{width:96px;text-align:right;font-weight:700;font-size:1rem;border:2px solid #cbd5e1;border-radius:8px;padding:.25rem .4rem}
        .pa-price input.pa-new:focus{border-color:#2563eb;outline:0}
        .pa-gain{font-size:.78rem;margin-top:.2rem}
        .pa-gain.good{color:#15803d}.pa-gain.bad{color:#b91c1c;font-weight:700}
        .pa-sw{display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;margin-top:.35rem;cursor:pointer;color:#374151}
        .pa-sw input{width:17px;height:17px}
        .pa-more{margin-top:.6rem}.pa-more summary{cursor:pointer;color:#6b7280;font-size:.85rem}
        @media (max-width:640px){.pa-card{grid-template-columns:1fr}.pa-right{text-align:left}.pa-price{justify-content:flex-start}}
        .lh-actionbar{position:sticky;bottom:0;z-index:20;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-top:.75rem;padding:.7rem 1rem;background:#fff;border:1px solid #dbe3ee;border-radius:10px;box-shadow:0 -4px 14px rgba(0,0,0,.08)}
        .lh-actionbar .ab-totals{display:flex;gap:1.5rem;align-items:baseline;font-size:1.05rem}
        .lh-actionbar .ab-totals b{font-size:1.25rem}
        .lh-actionbar .ab-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-actionbar .ab-btns .btn{height:42px;min-width:150px;display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:0 1rem;font-size:.9rem;font-weight:600;border-radius:8px;margin:0;white-space:nowrap}
        .lh-actionbar .ab-btns .btn small{font-weight:500;opacity:.85}
        @media (max-width:768px){.lh-actionbar .ab-btns .btn{flex:1 1 45%;min-width:0}}

        /* ============================================================
           PAGE D'ACHAT — tableau des lignes (#doc_lines)
           ============================================================ */
        #doc_lines td:nth-child(1){width:130px;max-width:150px}
        #doc_lines .ref-code{display:block;font-family:'Courier New',Consolas,monospace;font-weight:900;font-size:1.02rem;color:#0040c0;letter-spacing:.04em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:145px;background:#eef4ff;padding:2px 6px;border-radius:3px;border-left:3px solid #0040c0}
        #doc_lines td:nth-child(2){min-width:170px;max-width:260px}
        #doc_lines .des-name{display:block;font-weight:700;font-size:.88rem;color:#111;line-height:1.35;white-space:normal;word-break:break-word}
        #doc_lines tbody td{padding:5px 6px!important;vertical-align:middle}
        #doc_lines .quantity{width:70px!important;min-width:70px;text-align:center}
        #doc_lines .unit_price_ht{width:95px!important;min-width:95px}
        #doc_lines .remise{width:72px!important;min-width:72px}
        #doc_lines .tva-in{width:68px!important;min-width:68px}
        #doc_lines .form-control{height:32px;padding:0 .4rem;border-radius:6px}
        #doc_lines .new-in{min-width:0!important;width:100%!important}
        #doc_lines .pv-in{width:88px!important;min-width:88px}
        #doc_lines .net-price,#doc_lines .row-ht,#doc_lines .row-ttc{white-space:nowrap;font-weight:600}
        #doc_lines .pv-block{font-size:.78rem;line-height:1.5;white-space:nowrap}
        #doc_lines .mg-ok{color:#1a7a3a;font-weight:600}
        #doc_lines .mg-bad{color:#c0200d;font-weight:700}
        #doc_lines tr.row-flash{animation:rowflash 1s ease}
        @keyframes rowflash{0%{background:#ffe9a6}100%{background:transparent}}
        #doc_lines tr.row-loss td{background:#fff1f0!important}
        .badge-new{background:#0d6efd;color:#fff;font-size:.62rem;padding:.15em .45em;border-radius:4px;margin-left:4px;vertical-align:middle}
        .badge-other{background:#fff3cd;color:#7a5b00;font-size:.68rem;padding:.1em .4em;border-radius:4px;border:1px solid #ffe08a}
        .lh-actionbar .ab-btns .btn{min-width:170px}
        .lh-actionbar .ab-totals{flex-wrap:wrap;gap:1rem 1.5rem}
        .form-control-plaintext{min-height:38px;display:flex;align-items:center;padding:0 .2rem}
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
    @php($isEdit = ($mode ?? 'create') !== 'create')
    @php($editOrderId = ($mode ?? 'create') === 'order' ? $editDoc->id : null)
    <h4 class="lh-title"><i class="fas fa-truck-loading text-primary"></i>
        @if(($mode ?? 'create') === 'project') Modifier le projet d'achat <span class="text-muted">{{ $editDoc->numdoc }}</span>
        @elseif(($mode ?? 'create') === 'order') Modifier la commande d'achat <span class="text-muted">{{ $editDoc->numdoc }}</span>
        @else Créer un Document d'Achat @endif
    </h4>
    <div class="lh-btns">
        <a href="/suppliers" onclick="window.open(this.href,'popupWindow','width=1200,height=700,scrollbars=yes'); return false;" class="btn btn-outline-primary">
            <i class="fas fa-user-tag"></i> Fournisseurs
        </a>
        <a href="/articles" onclick="window.open(this.href,'popupWindow','width=1000,height=700,scrollbars=yes'); return false;" class="btn btn-outline-success">
            <i class="fas fa-boxes"></i> Liste des articles
        </a>
        <a href="{{ route('purchases.list') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list"></i> Commandes d'achat
        </a>
    </div>
</div>

<form action="{{ $formAction }}" method="POST" id="purchaseForm" novalidate>
    @csrf
    @if(($formMethod ?? 'POST') !== 'POST') @method($formMethod) @endif
    <input type="hidden" name="action" id="form_action" value="">

    <div class="row top-row mb-2">
        <div class="col-lg-4 col-12 mb-2">
            <label class="form-label mb-1" for="supplier_id">Fournisseur</label>
            <select name="supplier_id" id="supplier_id" class="form-control">
                <option value="" disabled {{ old('supplier_id', optional($editDoc)->supplier_id) ? '' : 'selected' }}>Sélectionner un fournisseur</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}"
                            data-tva="{{ (float) ($s->tvaGroup->rate ?? 0) }}"
                            data-solde="{{ (float) ($s->solde ?? 0) }}"
                            data-code="{{ $s->code }}"
                            {{ (string) old('supplier_id', optional($editDoc)->supplier_id) === (string) $s->id ? 'selected' : '' }}>{{ $s->code }} — {{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="order_date">Date</label>
            <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $editDoc ? \Carbon\Carbon::parse($editDoc->order_date)->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control order_date">
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="store_id">Magasin (réception)</label>
            @if($stores->count() > 1)
                <select name="store_id" id="store_id" class="form-control js-s2">
                    @foreach($stores as $st)
                        <option value="{{ $st->id }}" {{ (string) old('store_id', optional($editDoc)->store_id) === (string) $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="store_id" id="store_id" value="{{ optional($stores->first())->id }}">
                <div class="form-control-plaintext fw-bold">{{ optional($stores->first())->name ?? '—' }}</div>
            @endif
        </div>
        @if(!$isEdit)
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="supplier_ref">N° BL / facture fournisseur</label>
            <input type="text" name="supplier_ref" id="supplier_ref" value="{{ old('supplier_ref') }}" class="form-control" maxlength="60" autocomplete="off" placeholder="facultatif">
        </div>
        <div class="col-lg-2 col-6 mb-2">
            <label class="form-label mb-1" for="due_date">Échéance facture</label>
            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="form-control order_date">
        </div>
        @endif
    </div>

    <div id="customer_banner" style="display:none;">
        <span class="cb-name" id="cb_name">—</span>
        <span class="cb-sep">|</span>
        <span><span class="cb-label">TVA : </span><span class="cb-val" id="cb_tva">—</span></span>
        <span class="cb-sep">|</span>
        <span><span class="cb-label">Solde : </span><span id="cb_solde" class="cb-solde-pos">—</span></span>
    </div>

    <div class="mb-2">
        <div class="lh-filters">
            <div class="lh-field lh-wide">
                <i class="fas fa-search lh-ico"></i>
                <input type="text" id="search_item" class="form-control" placeholder="Référence, désignation, code-barres… (min. 2 car.)" autocomplete="off">
            </div>
            <button type="button" id="add_divers_item" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer un article
            </button>
            <button type="button" id="ins_btn" class="btn btn-outline-primary" title="Ventes, stock, déjà commandé et quantités conseillées pour chaque ligne">
                <i class="fas fa-chart-line"></i> Aide à la décision
            </button>
            <button type="button" id="price_assistant_btn" class="btn btn-outline-warning" title="Propositions de prix de vente à partir des nouveaux coûts">
                <i class="fas fa-tags"></i> Prix de vente <span id="pa_badge" class="badge bg-danger" style="display:none">0</span>
            </button>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="excel_menu_btn">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('purchase_docs.template') }}"><i class="fas fa-download text-success"></i> Télécharger le modèle</a></li>
                    <li><a class="dropdown-item" href="#" id="excel_import_btn"><i class="fas fa-upload text-primary"></i> Importer un fichier rempli</a></li>
                    <li><a class="dropdown-item" href="#" id="excel_export_btn"><i class="fas fa-file-export text-secondary"></i> Exporter les lignes saisies</a></li>
                </ul>
            </div>
            <input type="file" id="excel_file" accept=".xlsx,.xls,.csv" style="display:none">
        </div>
        <div class="sr-hint">↑ ↓ naviguer &nbsp;·&nbsp; Entrée ajouter &nbsp;·&nbsp; Échap fermer &nbsp;·&nbsp; « Créer un article » ajoute une ligne pour un article qui n'existe pas encore</div>
        <div id="search_results"></div>
    </div>

    <div id="ins_strip" style="display:none"></div>

    <div class="mb-3">
        <div style="overflow-x:auto;">
            <div id="doc_lines_wrap" style="max-height:440px; overflow-y:auto;">
                <table class="table table-striped table-bordered table-text-small" id="doc_lines">
                    <thead class="table-dark text-white">
                        <tr style="height:42px;">
                            <th class="py-1">Référence</th>
                            <th class="py-1">Désignation</th>
                            <th class="py-1 text-center">Stock</th>
                            <th class="py-1 text-center">Qté</th>
                            <th class="py-1 text-center">Prix achat HT</th>
                            <th class="py-1 text-center">Rem %</th>
                            <th class="py-1 text-center">Net HT</th>
                            <th class="py-1 text-center">TVA %</th>
                            <th class="py-1 text-center">Total HT</th>
                            <th class="py-1 text-center">Total TTC</th>
                            <th class="py-1 text-center">Prix de vente · Marge</th>
                            <th class="py-1"></th>
                        </tr>
                    </thead>
                    <tbody id="lines_body"></tbody>
                </table>
            </div>
        </div>
        <div id="empty_hint" class="text-muted text-center py-3" style="font-size:.9rem;">Aucune ligne. Recherchez un article ci-dessus ou créez-en un.</div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="notes">Notes / Commentaire</label>
        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Remarques internes, conditions de livraison, etc.">{{ old('notes', optional($editDoc)->notes) }}</textarea>
    </div>

    <div class="lh-actionbar">
        <div class="ab-totals">
            <div>Total HT : <b id="total_ht_global" class="text-danger">0,00</b> €</div>
            <div>TVA : <b id="total_tva_global">0,00</b> €</div>
            <div>Total TTC : <b id="total_ttc_global" class="text-success">0,00</b> €</div>
        </div>
        <div class="ab-btns">
            @if(($mode ?? 'create') === 'create')
            <button type="button" class="btn btn-warning js-act" data-action="project"><i class="fas fa-file-alt"></i> Projet d'achat</button>
            <button type="button" class="btn btn-danger js-act" data-action="order"><i class="fas fa-clipboard-list"></i> Commande achat</button>
            <button type="button" class="btn btn-primary js-act" data-action="reception"><i class="fas fa-dolly"></i> Valider réception</button>
            <button type="button" class="btn btn-success js-act" data-action="receive_invoice"><i class="fas fa-file-invoice-dollar"></i> Réceptionner et facturer</button>
            @elseif($mode === 'project')
            <a href="{{ route('purchaseprojects.list') }}" class="btn btn-outline-secondary">Retour</a>
            <button type="button" class="btn btn-warning js-act" data-action="save"><i class="fas fa-save"></i> Enregistrer</button>
            @if($editDoc->status !== 'validée')<button type="button" class="btn btn-primary js-act" data-action="validate"><i class="fas fa-check"></i> Valider le projet</button>@endif
            <button type="button" class="btn btn-danger js-act" data-action="convert"><i class="fas fa-clipboard-list"></i> Convertir en commande</button>
            @else
            <a href="{{ route('purchases.list') }}" class="btn btn-outline-secondary">Retour</a>
            <button type="button" class="btn btn-warning js-act" data-action="save"><i class="fas fa-save"></i> Enregistrer</button>
            @if($editDoc->status === 'brouillon')<button type="button" class="btn btn-danger js-act" data-action="validate_order"><i class="fas fa-check"></i> Valider la commande</button>@endif
            @endif
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

    var ITEMS_URL = @json(route('purchase_docs.items'));
    var OLD_LINES = @json(old('lines', $prefill ?? []));
    var MODE = @json($mode ?? 'create');
    var EDIT_ORDER_ID = @json($editOrderId);
    var LS_PREF = 'aznegoce_ins_on';
    var DRAFT_KEY = 'aznegoce_purchase_draft_v1';

    var $supplier = $('#supplier_id');
    var lineSeq = 0, lastResults = [], searchTimer = null, searchXhr = null, searchSeq = 0;
    var prevSupplier = '', restoring = false, lossToastAt = 0, submitting = false;

    /* ───────── utilitaires ───────── */
    function esc(s) { return String(s === null || s === undefined ? '' : s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
    function r2(x) { return Math.round((x + Number.EPSILON) * 100) / 100; }
    function num(v) { var n = parseFloat(String(v === undefined || v === null ? '' : v).replace(',', '.')); return isNaN(n) ? 0 : n; }
    function fr(n) { return r2(n).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    function lsGet(k) { try { return window.localStorage.getItem(k); } catch (e) { return null; } }
    function lsSet(k, v) { try { window.localStorage.setItem(k, v); } catch (e) {} }
    function lsDel(k) { try { window.localStorage.removeItem(k); } catch (e) {} }
    function supplierOpt() { return $supplier.find('option:selected'); }
    function supplierId() { return $supplier.val() || ''; }
    function supplierTva() { var o = supplierOpt(); return o.length && supplierId() ? num(o.data('tva')) : 0; }

    /* ───────── fournisseur ───────── */
    $supplier.select2({ width: '100%', placeholder: 'Sélectionner un fournisseur', language: { noResults: function () { return 'Aucun résultat'; } } });

    function showBanner() {
        var id = supplierId();
        if (!id) { $('#customer_banner').hide(); return; }
        var o = supplierOpt(), solde = num(o.data('solde'));
        $('#cb_name').text(o.text());
        var tva = num(o.data('tva'));
        $('#cb_tva').text(tva > 0 ? tva.toString().replace('.', ',') + ' %' : 'Exonéré (0 %)');
        var $s = $('#cb_solde').removeClass('cb-solde-pos cb-solde-neg');
        if (solde > 0.004)       { $s.addClass('cb-solde-neg').text('Nous lui devons ' + fr(solde) + ' €'); }
        else if (solde < -0.004) { $s.addClass('cb-solde-pos').text('Il nous doit ' + fr(-solde) + ' €'); }
        else                     { $s.addClass('cb-solde-pos').text('Soldé'); }
        $('#customer_banner').css('display', 'flex');
    }

    $supplier.on('change', function () {
        var id = supplierId();
        showBanner();
        if (restoring) { prevSupplier = id; return; }
        var hadLines = $('#lines_body tr').length > 0;
        if (prevSupplier && prevSupplier !== id && hadLines) {
            Swal.fire({
                icon: 'question', title: 'Changement de fournisseur',
                text: 'Mettre à jour les prix, remises et TVA des lignes pour ce fournisseur ?',
                showCancelButton: true, confirmButtonText: 'Oui, mettre à jour', cancelButtonText: 'Non, garder les lignes'
            }).then(function (res) { if (res.isConfirmed) refreshLinesForSupplier(); });
        }
        prevSupplier = id;
        scheduleDraftSave();
        setTimeout(function () { $('#search_item').trigger('focus'); }, 150);
    });

    function refreshLinesForSupplier() {
        var codes = [];
        $('#lines_body tr').each(function () { if (!$(this).data('new')) codes.push($(this).data('code')); });
        var tva = supplierTva();
        $('#lines_body tr').each(function () { if ($(this).data('new')) $(this).find('.tva-in').val(tva); });
        if (!codes.length) { recalcAll(); return; }
        $.getJSON(ITEMS_URL, { supplier_id: supplierId(), codes: codes }, function (data) {
            var map = {}; (data || []).forEach(function (it) { map[String(it.code).toLowerCase()] = it; });
            $('#lines_body tr').each(function () {
                var $tr = $(this); if ($tr.data('new')) return;
                var it = map[String($tr.data('code')).toLowerCase()]; if (!it) return;
                $tr.find('.unit_price_ht').val(num(it.cost_price).toFixed(2));
                $tr.find('.remise').val(num(it.remise_achat).toFixed(2));
                $tr.find('.tva-in').val(num(it.tva));
                $tr.data('own', it.own_supplier ? 1 : 0).find('[name$="[_own]"]').val(it.own_supplier ? 1 : 0);
                $tr.find('.badge-other').toggle(!it.own_supplier);
            });
            recalcAll();
        }).fail(function () { Swal.fire('Erreur', 'Impossible de relire les prix.', 'error'); recalcAll(); });
    }

    /* ───────── recherche d'articles ───────── */
    function scheduleSearch() {
        clearTimeout(searchTimer);
        var term = $.trim($('#search_item').val());
        if (term.length < 2) { resetResults(); return; }
        if (!supplierId()) {
            resetResults();
            Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "Choisissez d'abord un fournisseur", showConfirmButton: false, timer: 2200 });
            $supplier.select2('open');
            return;
        }
        searchTimer = setTimeout(runSearch, 220);
    }

    function runSearch() {
        var term = $.trim($('#search_item').val());
        if (term.length < 2) return;
        var seq = ++searchSeq;
        if (searchXhr) { searchXhr.abort(); }
        searchXhr = $.ajax({
            url: ITEMS_URL, data: { query: term, supplier_id: supplierId() }, dataType: 'json',
            success: function (data) { if (seq !== searchSeq) return; lastResults = data || []; renderResults(); },
            error: function (xhr, status) { if (status === 'abort') return; $('#search_results').html('<div class="sr-item sr-empty text-danger">Erreur de recherche, réessayez.</div>'); }
        });
    }

    function resetResults() { lastResults = []; $('#search_results').empty(); }

    function renderResults() {
        var $r = $('#search_results').empty();
        if (!lastResults.length) { $r.append('<div class="sr-item sr-empty text-muted">Aucun article trouvé. Utilisez « Créer un article ».</div>'); return; }
        lastResults.forEach(function (it, i) {
            var net = it.cost_price * (1 - it.remise_achat / 100);
            $r.append(
                '<div class="sr-item" data-i="' + i + '">' +
                  '<span class="sr-main"><b>' + esc(it.code) + '</b> <span class="sr-name">' + esc(it.name) + '</span>' + (it.brand ? ' <small class="text-muted">· ' + esc(it.brand) + '</small>' : '') + '</span>' +
                  '<span class="sr-sub">Stock : <b>' + esc(it.stock_quantity) + '</b> · Achat : ' + fr(it.cost_price) + ' €' + (it.remise_achat > 0 ? ' −' + esc(it.remise_achat) + ' %' : '') +
                  ' → <span class="sr-price">net ' + fr(net) + ' €</span>' +
                  (it.own_supplier ? '' : ' <span class="badge-other">prix d\'un autre fournisseur</span>') +
                  ' · PV ' + fr(it.sale_price) + ' € · TVA ' + esc(it.tva) + ' %</span>' +
                '</div>');
        });
    }

    function markActive($items, idx) {
        $items.removeClass('active');
        if (idx >= 0 && idx < $items.length) {
            var el = $items.eq(idx).addClass('active')[0];
            if (el && el.scrollIntoView) el.scrollIntoView({ block: 'nearest' });
        }
    }

    $('#search_item').on('input', scheduleSearch).on('keydown', function (e) {
        var $items = $('#search_results .sr-item').not('.sr-empty');
        var idx = $items.index($items.filter('.active'));
        if (e.key === 'ArrowDown') { if ($items.length) { e.preventDefault(); markActive($items, Math.min(idx + 1, $items.length - 1)); } }
        else if (e.key === 'ArrowUp') { if ($items.length) { e.preventDefault(); markActive($items, Math.max(idx - 1, 0)); } }
        else if (e.key === 'Enter') {
            e.preventDefault();
            if ($items.length) { pickResult($items.eq(idx >= 0 ? idx : 0).data('i')); }
            else { clearTimeout(searchTimer); runSearch(); }
        }
        else if (e.key === 'Escape') { resetResults(); }
    });
    $(document).on('mousemove', '#search_results .sr-item', function () { if (!$(this).hasClass('sr-empty')) { markActive($('#search_results .sr-item').not('.sr-empty'), $('#search_results .sr-item').not('.sr-empty').index(this)); } });
    $(document).on('click', '#search_results .sr-item', function () { if (!$(this).hasClass('sr-empty')) pickResult($(this).data('i')); });
    $(document).on('click', function (e) { if (!$(e.target).closest('#search_results, #search_item').length) resetResults(); });

    function pickResult(i) {
        var it = lastResults[i]; if (!it) return;
        addItemLine(it);
        $('#search_item').val('');
        resetResults();
    }

    /* ───────── lignes ───────── */
    function lineHtml(k, o) {
        var isNew = !!o.isNew;
        var n = 'lines[' + k + ']';
        var refCell = isNew
            ? '<input type="text" class="form-control new-in ref-in" name="' + n + '[article_code]" value="' + esc(o.code) + '" placeholder="Référence *" maxlength="60" style="text-transform:uppercase"><span class="badge-new">Nouvel article</span>'
            : '<span class="ref-code" title="' + esc(o.code) + '">' + esc(o.code) + '</span><input type="hidden" name="' + n + '[article_code]" value="' + esc(o.code) + '">';
        var desCell = isNew
            ? '<input type="text" class="form-control new-in name-in" name="' + n + '[item_name]" value="' + esc(o.name) + '" placeholder="Désignation *" maxlength="190">'
            : '<span class="des-name">' + esc(o.name) + '</span><input type="hidden" name="' + n + '[item_name]" value="' + esc(o.name) + '">' +
              '<span class="badge-other" style="' + (o.own ? 'display:none' : '') + '">prix d\'un autre fournisseur</span><div class="ins-chip" style="display:none"></div>';
        var pvCell = isNew
            ? '<input type="number" step="0.01" min="0" class="form-control pv-in" name="' + n + '[sale_price]" value="' + num(o.sale).toFixed(2) + '" title="Prix de vente HT"> <span class="mg"></span>'
            : '<div class="pv-block"><span class="pv-txt">PV ' + fr(o.sale) + ' €</span><br><span class="mg"></span></div><input type="hidden" class="pv-hid" name="' + n + '[sale_price]" value="' + num(o.sale).toFixed(2) + '">';
        return '<tr data-new="' + (isNew ? 1 : 0) + '" data-code="' + esc(o.code) + '" data-own="' + (o.own ? 1 : 0) + '">' +
            '<td>' + refCell + '</td>' +
            '<td>' + desCell + '</td>' +
            '<td class="text-center">' + (isNew ? '—' : esc(o.stock)) + '<input type="hidden" name="' + n + '[_stock]" value="' + esc(o.stock) + '"><input type="hidden" name="' + n + '[_own]" value="' + (o.own ? 1 : 0) + '"></td>' +
            '<td><input type="number" min="1" step="1" class="form-control quantity" name="' + n + '[quantity]" value="' + esc(o.qty) + '"></td>' +
            '<td><input type="number" min="0" step="0.01" class="form-control unit_price_ht" name="' + n + '[unit_price_ht]" value="' + num(o.pu).toFixed(2) + '"></td>' +
            '<td><input type="number" min="0" max="100" step="0.01" class="form-control remise" name="' + n + '[remise]" value="' + num(o.remise).toFixed(2) + '"></td>' +
            '<td class="text-center"><span class="net-price">0,00</span></td>' +
            '<td><input type="number" min="0" max="100" step="0.01" class="form-control tva-in" name="' + n + '[tva]" value="' + esc(o.tva) + '"></td>' +
            '<td class="text-center"><span class="row-ht">0,00</span></td>' +
            '<td class="text-center"><span class="row-ttc">0,00</span></td>' +
            '<td>' + pvCell + (isNew ? '<input type="hidden" name="' + n + '[is_new_item]" value="1">' : '') + '</td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove_line" title="Supprimer la ligne"><i class="fas fa-trash"></i></button></td>' +
            '</tr>';
    }

    function appendLine(o) {
        var $tr = $(lineHtml(++lineSeq, o));
        $('#lines_body').append($tr);
        recalcRow($tr);
        updateEmptyHint();
        return $tr;
    }

    function addItemLine(it) {
        var code = String(it.code).toLowerCase();
        var $dup = $('#lines_body tr').filter(function () { return !$(this).data('new') && String($(this).data('code')).toLowerCase() === code; }).first();
        if ($dup.length) {
            var $q = $dup.find('.quantity'); $q.val(Math.max(1, num($q.val()) + 1));
            recalcRow($dup); recalcTotals();
            $dup.addClass('row-flash'); setTimeout(function () { $dup.removeClass('row-flash'); }, 1000);
            $q.trigger('focus').trigger('select');
            return;
        }
        var $tr = appendLine({ code: it.code, name: it.name, stock: it.stock_quantity, qty: 1, pu: it.cost_price, remise: it.remise_achat, tva: it.tva, sale: it.sale_price, own: it.own_supplier, isNew: false });
        recalcTotals();
        scheduleDraftSave(); if (typeof scheduleInsights === 'function') scheduleInsights();
        $tr.find('.quantity').trigger('focus').trigger('select');
        var el = document.getElementById('doc_lines_wrap'); if (el) el.scrollTop = el.scrollHeight;
    }


    /* ───────── Excel : modèle / import / export ───────── */
    var IMPORT_URL = @json(route('purchase_docs.import'));
    var EXPORT_URL = @json(route('purchase_docs.export'));
    var CSRF = @json(csrf_token());

    function currentLines() {
        var out = [];
        $('#lines_body tr').each(function () {
            var $tr = $(this), isNew = !!$tr.data('new');
            out.push({
                code: isNew ? $tr.find('.ref-in').val() : $tr.data('code'),
                name: isNew ? $tr.find('.name-in').val() : $tr.find('.des-name').text(),
                qty: num($tr.find('.quantity').val()), pu: num($tr.find('.unit_price_ht').val()),
                remise: num($tr.find('.remise').val()), tva: num($tr.find('.tva-in').val()),
                sale: isNew ? num($tr.find('.pv-in').val()) : num($tr.find('.pv-hid').val()), isNew: isNew
            });
        });
        return out;
    }

    $('#excel_export_btn').on('click', function (e) {
        e.preventDefault();
        var lines = currentLines();
        if (!lines.length) { Swal.fire('Aucune ligne', "Ajoutez d'abord des lignes, ou téléchargez le modèle vide.", 'info'); return; }
        var $f = $('<form method="POST" style="display:none"></form>').attr('action', EXPORT_URL)
            .append($('<input type="hidden" name="_token">').val(CSRF))
            .append($('<input type="hidden" name="supplier_id">').val(supplierId()))
            .append($('<input type="hidden" name="lines_json">').val(JSON.stringify(lines)));
        $('body').append($f); $f.trigger('submit'); setTimeout(function () { $f.remove(); }, 1500);
    });

    $('#excel_import_btn').on('click', function (e) {
        e.preventDefault();
        if (!supplierId()) {
            Swal.fire({ icon: 'info', title: "Choisissez d'abord un fournisseur", text: "Les prix, remises et TVA vides du fichier sont repris de ce fournisseur." });
            $supplier.select2('open');
            return;
        }
        $('#excel_file').val('').trigger('click');
    });

    function listHtml(title, arr, cls) {
        if (!arr.length) return '';
        var max = 12, li = arr.slice(0, max).map(function (m) { return '<li>' + esc(m) + '</li>'; }).join('');
        if (arr.length > max) li += '<li><i>… et ' + (arr.length - max) + ' autre(s)</i></li>';
        return '<div class="text-start mt-2"><b class="' + cls + '">' + title + ' (' + arr.length + ')</b><ul style="font-size:.85rem;margin:.25rem 0 0 1rem;padding:0">' + li + '</ul></div>';
    }

    function applyImported(lines, replace) {
        if (replace) { $('#lines_body').empty(); }
        lines.forEach(function (o) {
            var code = String(o.code).toLowerCase();
            var $dup = o.isNew ? $() : $('#lines_body tr').filter(function () { return !$(this).data('new') && String($(this).data('code')).toLowerCase() === code; }).first();
            if ($dup.length) {
                var $q = $dup.find('.quantity'); $q.val(num($q.val()) + num(o.qty));
                $dup.find('.unit_price_ht').val(num(o.pu).toFixed(2)); $dup.find('.remise').val(num(o.remise).toFixed(2)); $dup.find('.tva-in').val(num(o.tva));
                recalcRow($dup);
            } else {
                appendLine({ code: o.code, name: o.name, stock: o.stock, qty: o.qty, pu: o.pu, remise: o.remise, tva: o.tva, sale: o.sale, own: o.own, isNew: !!o.isNew });
            }
        });
        updateEmptyHint(); recalcTotals(); scheduleDraftSave();
    }

    $('#excel_file').on('change', function () {
        var f = this.files && this.files[0]; if (!f) return;
        var fd = new FormData(); fd.append('file', f); fd.append('supplier_id', supplierId()); fd.append('_token', CSRF);
        Swal.fire({ title: 'Lecture du fichier…', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });
        $.ajax({ url: IMPORT_URL, method: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
        .done(function (res) {
            var lines = res.lines || [], errs = res.errors || [], warns = res.warnings || [];
            if (!lines.length) {
                Swal.fire({ icon: 'error', title: 'Aucune ligne importable', html: listHtml('Erreurs', errs, 'text-danger') || 'Le fichier ne contient aucune ligne.' });
                return;
            }
            var html = '<div><b>' + lines.length + '</b> ligne(s) prête(s) à être importée(s)' + (errs.length ? ', <b class="text-danger">' + errs.length + '</b> refusée(s)' : '') + '.</div>' + listHtml('Erreurs (lignes ignorées)', errs, 'text-danger') + listHtml('À noter', warns, 'text-warning');
            var hasLines = $('#lines_body tr').length > 0;
            Swal.fire({
                icon: errs.length ? 'warning' : 'success', title: 'Import Excel', html: html,
                showCancelButton: true, showDenyButton: hasLines,
                confirmButtonText: hasLines ? 'Ajouter aux lignes' : 'Importer', denyButtonText: 'Remplacer les lignes', cancelButtonText: 'Annuler'
            }).then(function (r) {
                if (r.isConfirmed) { applyImported(lines, false); }
                else if (r.isDenied) { applyImported(lines, true); }
                else { return; }
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: lines.length + ' ligne(s) importée(s)', showConfirmButton: false, timer: 2500 });
            });
        })
        .fail(function (xhr) {
            var m = (xhr.responseJSON && (xhr.responseJSON.message || (xhr.responseJSON.errors && Object.values(xhr.responseJSON.errors)[0][0]))) || "Import impossible.";
            Swal.fire('Import impossible', m, 'error');
        });
    });



    /* ───────── Aide à la décision (ventes · stock · déjà commandé · quantité conseillée) ───────── */
    var INS_URL = @json(route('purchase_docs.insights'));
    var REP_URL = @json(route('purchase_docs.replenishment'));
    var insOn = lsGet(LS_PREF) !== '0', insData = {}, insMeta = null, insTimer = null, insXhr = null;
    var insPar = { cover: 45, lead: '' };
    var ST_LABEL = { rupture: 'En rupture', critique: 'Bientôt en rupture', a_commander: 'À commander', ok: 'Stock suffisant', surstock: 'Trop de stock', dormant: 'Ne se vend plus', sans_ventes: 'Jamais vendu' };

    function insCodes() {
        var c = []; $('#lines_body tr').each(function () { if (!$(this).data('new')) c.push($(this).data('code')); }); return c;
    }

    function scheduleInsights() {
        clearTimeout(insTimer);
        if (!insOn) return;
        insTimer = setTimeout(loadInsights, 500);
    }

    function loadInsights() {
        var codes = insCodes();
        if (!insOn || !supplierId()) { $('#ins_strip').hide(); $('.ins-chip').hide(); return; }
        if (!codes.length) { insData = {}; renderStrip(); return; }
        if (insXhr) insXhr.abort();
        insXhr = $.ajax({ url: INS_URL, dataType: 'json', data: { supplier_id: supplierId(), codes: codes, cover: insPar.cover, lead: insPar.lead, exclude_order_id: EDIT_ORDER_ID || '' } })
        .done(function (res) {
            insData = {}; Object.keys(res.rows || {}).forEach(function (k) { insData[String(k).toLowerCase()] = res.rows[k]; });
            insMeta = res; renderChips(); renderStrip();
        });
    }

    function chipHtml(r, qty) {
        var sug = r.suggested, h = '<span class="st st-' + r.status + '">' + ST_LABEL[r.status] + '</span>';
        var trend = r.trend === 'up' ? ' <span style="color:#dc2626" title="Les ventes accélèrent">▲</span>' : (r.trend === 'down' ? ' <span style="color:#2563eb" title="Les ventes ralentissent">▼</span>' : '');
        h += 'Stock <b>' + fr0(r.stock) + '</b>' + (r.on_order > 0 ? ' (+' + fr0(r.on_order) + ' en cmd)' : '') + ' · se vend <b>' + fr1(r.monthly) + '</b> par mois' + trend +
             (r.cover_total !== null ? ' · de quoi tenir <b>' + fr0(r.cover_total) + ' jours</b>' : '') + (r.season && Math.abs(r.season - 1) >= 0.15 ? ' · période de forte/faible vente ×' + String(r.season).replace('.', ',') : '');
        if (r.status === 'dormant' || r.status === 'sans_ventes') {
            h += ' · <i>aucune vente sur 12 mois</i><a class="act" data-ins="remove">Retirer</a>';
        } else if (r.status === 'surstock') {
            h += ' · <i>vous avez déjà ' + fr0(r.cover_total) + ' jours de stock : inutile d\'en racheter</i><a class="act" data-ins="remove">Retirer</a>';
        } else if (sug > 0 && Math.round(qty) !== sug) {
            h += ' · je conseille <b>' + sug + '</b><a class="act" data-ins="apply" data-q="' + sug + '">Utiliser ' + sug + '</a>';
        } else if (sug === 0 && qty > 0 && r.status === 'ok') {
            h += ' · <i>vous avez déjà assez de stock</i><a class="act" data-ins="remove">Retirer</a>';
        } else if (sug > 0) { h += ' · <span style="color:#16a34a">✓ quantité conforme à mon conseil</span>'; }
        if (r.alt) h += '<br><span class="alt">Moins cher chez <b>' + esc(r.alt.supplier_code) + '</b> : ' + fr(r.alt.net) + ' € net (−' + fr1(r.alt.gain_pct) + ' %)</span>';
        return h;
    }
    function fr0(n) { return Math.round(n).toLocaleString('fr-FR'); }
    function fr1(n) { return (Math.round(n * 10) / 10).toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 1 }); }

    function renderChips() {
        $('#lines_body tr').each(function () {
            var $tr = $(this), $c = $tr.find('.ins-chip');
            if ($tr.data('new')) { $c.hide(); return; }
            var r = insData[String($tr.data('code')).toLowerCase()];
            if (!r || !insOn) { $c.hide(); return; }
            $c.html(chipHtml(r, num($tr.find('.quantity').val()))).show();
        });
    }

    function renderStrip() {
        var $s = $('#ins_strip');
        if (!insOn || !supplierId() || !insMeta) { $s.hide(); return; }
        var risk = 0, useless = 0, toFix = 0, margin = 0, lines = 0;
        $('#lines_body tr').each(function () {
            var $tr = $(this); if ($tr.data('new')) return; lines++;
            var r = insData[String($tr.data('code')).toLowerCase()]; if (!r) return;
            var q = num($tr.find('.quantity').val());
            if (r.status === 'rupture' || r.status === 'critique') risk++;
            if (r.status === 'dormant' || r.status === 'surstock' || r.status === 'sans_ventes') useless++;
            if (r.suggested > 0 && Math.round(q) !== r.suggested) toFix++;
            if (r.margin_unit !== null) margin += q * r.margin_unit;
        });
        var rem = (insMeta.lead && insMeta.lead.source) ? insMeta.lead : { days: insPar.lead, source: '' };
        var html = '<div class="is-kpis">' +
            '<div class="is-k"><span>Lignes analysées</span><b>' + lines + '</b></div>' +
            '<div class="is-k ' + (risk ? 'bad' : '') + '"><span>Articles en rupture ou presque</span><b>' + risk + '</b></div>' +
            '<div class="is-k ' + (useless ? 'warn' : '') + '"><span>Articles inutiles à commander</span><b>' + useless + '</b></div>' +
            '<div class="is-k ' + (toFix ? 'warn' : '') + '"><span>Quantités à corriger</span><b>' + toFix + '</b></div>' +
            '<div class="is-k"><span>Marge attendue (si tout est vendu)</span><b>' + fr(margin) + ' €</b></div>' +
            '<div class="is-k"><span>Délai de livraison</span><b>' + insMeta.lead.used + ' j</b> <small class="text-muted">' + (insMeta.lead.source === 'historique' ? 'calculé sur ' + insMeta.lead.samples + ' livraison(s)' : 'estimé') + '</small></div>' +
            '</div><div class="is-acts">' +
            '<button type="button" class="btn btn-sm btn-primary" id="ins_apply_all"' + (toFix ? '' : ' disabled') + '><i class="fas fa-magic"></i> Appliquer mes quantités conseillées (' + toFix + ')</button>' +
            '<button type="button" class="btn btn-sm btn-outline-danger" id="ins_remove_useless"' + (useless ? '' : ' disabled') + '><i class="fas fa-trash"></i> Retirer les articles inutiles (' + useless + ')</button>' +
            '<button type="button" class="btn btn-sm btn-outline-success" id="ins_replenish"><i class="fas fa-plus-circle"></i> Articles à réapprovisionner</button>' +
            '<div class="is-par"><label class="mb-0" title="Nombre de jours de ventes que vous voulez avoir en stock après la livraison">Stock voulu pour</label><input id="ins_cover" type="number" min="7" max="365" class="form-control" value="' + insPar.cover + '"> j' +
            '<label class="mb-0 ms-2" title="Temps entre la commande et la livraison">Livraison en</label><input id="ins_lead" type="number" min="0" max="180" class="form-control" value="' + (insPar.lead === '' ? insMeta.lead.used : insPar.lead) + '"> j</div></div>' +
            '<div class="text-muted" style="font-size:.7rem;margin-top:.35rem">Comment je calcule : je regarde vos ventes récentes (surtout le dernier mois, puis les 3 derniers mois, puis l\'année)' + (insMeta.history_days < 120 ? ' — historique de ventes court (' + insMeta.history_days + ' j) : à prendre avec recul' : '') + '. Quantité conseillée = ce que vous vendrez pendant (livraison + jours de stock voulu) + le stock minimum, moins ce que vous avez déjà en stock ou en commande.</div>';
        $s.html(html).show();
    }

    $(document).on('change', '#ins_cover, #ins_lead', function () {
        insPar.cover = Math.max(7, Math.min(365, parseInt($('#ins_cover').val(), 10) || 45));
        var l = $('#ins_lead').val(); insPar.lead = l === '' ? '' : Math.max(0, Math.min(180, parseInt(l, 10) || 0));
        loadInsights();
    });

    function setQty($tr, q) { $tr.find('.quantity').val(q); recalcRow($tr); recalcTotals(); scheduleDraftSave(); renderChips(); renderStrip(); }

    $(document).on('click', '.ins-chip a.act', function () {
        var $tr = $(this).closest('tr');
        if ($(this).data('ins') === 'apply') { setQty($tr, parseInt($(this).data('q'), 10)); }
        else { $tr.remove(); updateEmptyHint(); recalcTotals(); scheduleDraftSave(); renderStrip(); }
    });
    $(document).on('click', '#ins_apply_all', function () {
        var n = 0;
        $('#lines_body tr').each(function () {
            var $tr = $(this); if ($tr.data('new')) return;
            var r = insData[String($tr.data('code')).toLowerCase()];
            if (r && r.suggested > 0 && Math.round(num($tr.find('.quantity').val())) !== r.suggested) { $tr.find('.quantity').val(r.suggested); recalcRow($tr); n++; }
        });
        recalcTotals(); scheduleDraftSave(); renderChips(); renderStrip();
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: n + ' quantité(s) ajustée(s)', showConfirmButton: false, timer: 2200 });
    });
    $(document).on('click', '#ins_remove_useless', function () {
        var list = [];
        $('#lines_body tr').each(function () {
            var $tr = $(this); if ($tr.data('new')) return;
            var r = insData[String($tr.data('code')).toLowerCase()];
            if (r && (r.status === 'dormant' || r.status === 'surstock' || r.status === 'sans_ventes')) list.push({ tr: $tr, r: r });
        });
        if (!list.length) return;
        Swal.fire({ icon: 'warning', title: 'Retirer ' + list.length + ' ligne(s) ?', html: '<div style="text-align:left;font-size:.85rem;max-height:200px;overflow:auto">' + list.map(function (x) { return '• <b>' + esc(x.r.code) + '</b> ' + esc(x.r.name) + ' — ' + ST_LABEL[x.r.status]; }).join('<br>') + '</div>', showCancelButton: true, confirmButtonText: 'Retirer', cancelButtonText: 'Garder' })
        .then(function (res) { if (!res.isConfirmed) return; list.forEach(function (x) { x.tr.remove(); }); updateEmptyHint(); recalcTotals(); scheduleDraftSave(); renderStrip(); });
    });

    $(document).on('click', '#ins_replenish', function () {
        if (!supplierId()) return;
        Swal.fire({ title: 'Recherche des besoins…', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });
        $.ajax({ url: REP_URL, dataType: 'json', data: { supplier_id: supplierId(), cover: insPar.cover, lead: insPar.lead, exclude: insCodes().concat($('#lines_body tr').filter(function () { return $(this).data('new'); }).map(function () { return $(this).data('code'); }).get()), exclude_order_id: EDIT_ORDER_ID || '' } })
        .done(function (res) {
            var rows = res.rows || [];
            if (!rows.length) { Swal.fire({ icon: 'success', title: 'Rien à réapprovisionner', text: 'Aucun article de ce fournisseur n\'est en rupture, bientôt en rupture ou trop bas pour tenir la durée souhaitée.' }); return; }
            var h = '<div style="max-height:380px;overflow:auto"><table class="table table-sm table-bordered pa-tbl mb-0"><thead class="table-dark"><tr><th><input type="checkbox" id="rp_all" checked></th><th>Article</th><th>État</th><th class="text-end">Stock</th><th class="text-end">Ventes/mois</th><th class="text-end">Couvre</th><th class="text-end">Qté</th><th class="text-end">Coût net</th></tr></thead><tbody>';
            rows.forEach(function (r, i) {
                h += '<tr data-i="' + i + '"><td class="text-center"><input type="checkbox" class="rp-chk" checked></td><td><b>' + esc(r.code) + '</b><br><small class="text-muted">' + esc(r.name) + (r.brand ? ' · ' + esc(r.brand) : '') + '</small></td>' +
                     '<td><span class="ins-chip" style="margin:0"><span class="st st-' + r.status + '">' + ST_LABEL[r.status] + '</span></span></td><td class="text-end">' + fr0(r.stock) + (r.on_order ? ' (+' + fr0(r.on_order) + ')' : '') + '</td><td class="text-end">' + fr1(r.monthly) + '</td>' +
                     '<td class="text-end">' + (r.cover_now === null ? '—' : fr0(r.cover_now) + ' j') + '</td><td class="text-end" style="width:90px"><input type="number" min="1" step="1" class="form-control form-control-sm text-end rp-q" value="' + r.suggested + '"></td>' +
                     '<td class="text-end">' + fr(r.cost_price * (1 - r.remise_achat / 100)) + '</td></tr>';
            });
            h += '</tbody></table></div>';
            Swal.fire({
                title: rows.length + ' article(s) à réapprovisionner' + (res.total > rows.length ? ' (sur ' + res.total + ')' : ''), width: 940, html: '<div class="text-start text-muted" style="font-size:.8rem;margin-bottom:.4rem">Articles de ce fournisseur, classés par urgence. Quantités = besoin sur délai + ' + res.cover + ' jours, stock et commandes en cours déduits.</div>' + h,
                showCancelButton: true, confirmButtonText: 'Ajouter la sélection', cancelButtonText: 'Fermer',
                didOpen: function () { $('#rp_all').on('change', function () { $('.rp-chk').prop('checked', this.checked); }); },
                preConfirm: function () { return $('.rp-chk:checked').map(function () { var $t = $(this).closest('tr'); return { i: $t.data('i'), q: Math.max(1, parseInt($t.find('.rp-q').val(), 10) || 1) }; }).get(); }
            }).then(function (r) {
                if (!r.isConfirmed || !r.value.length) return;
                r.value.forEach(function (x) { var it = rows[x.i]; appendLine({ code: it.code, name: it.name, stock: it.stock, qty: x.q, pu: it.cost_price, remise: it.remise_achat, tva: it.tva, sale: it.sale_price, own: it.own_supplier, isNew: false }); });
                recalcTotals(); updateEmptyHint(); scheduleDraftSave(); loadInsights();
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: r.value.length + ' article(s) ajouté(s)', showConfirmButton: false, timer: 2200 });
            });
        })
        .fail(function () { Swal.fire('Erreur', 'Analyse impossible, réessayez.', 'error'); });
    });

    function syncInsBtn() { $('#ins_btn').toggleClass('btn-primary', insOn).toggleClass('btn-outline-primary', !insOn); }
    $('#ins_btn').on('click', function () {
        insOn = !insOn; lsSet(LS_PREF, insOn ? '1' : '0'); syncInsBtn();
        if (insOn) { if (!supplierId()) { Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "Choisissez d'abord un fournisseur", showConfirmButton: false, timer: 2200 }); } loadInsights(); }
        else { $('#ins_strip').hide(); $('.ins-chip').hide(); }
    });
    // recalcul quand le contenu change (ajout/suppression/quantité/fournisseur)
    $(document).on('change', '#lines_body .quantity', function () { renderChips(); renderStrip(); });
    $(document).on('click', '.remove_line', scheduleInsights);
    $supplier.on('change', function () { insMeta = null; insData = {}; scheduleInsights(); });
    $(function () { syncInsBtn(); setTimeout(function () { if (supplierId() && $('#lines_body tr').length) loadInsights(); }, 400); });

    /* ───────── Assistant prix de vente (simple : 3 choix, une carte par article) ───────── */
    var REVIEW_URL = @json(route('purchase_docs.price_review'));
    var APPLY_URL  = @json(route('purchase_docs.apply_prices'));
    var paOpts = { strategy: 'keep', margin: 25, min_margin: 10, round: 'none' };
    var paRowsData = [];

    function netOf($tr) { return num($tr.find('.unit_price_ht').val()) * (1 - Math.min(100, Math.max(0, num($tr.find('.remise').val()))) / 100); }
    function paRows() {
        var out = [];
        $('#lines_body tr').each(function () { var $tr = $(this); if ($tr.data('new')) return; out.push({ code: $tr.data('code'), net: netOf($tr) }); });
        return out;
    }
    function eur(n) { return fr(n) + ' €'; }

    function ensurePaModal() {
        if ($('#pa_overlay').length) return;
        $('body').append(
            '<div id="pa_overlay"><div class="pa-box" role="dialog" aria-modal="true">' +
              '<div class="pa-head"><div><h5><i class="fas fa-tags text-warning"></i> Mettre à jour vos prix de vente</h5>' +
              '<p>Vous avez saisi de nouveaux prix d\'achat. Voici ce que vous gagnez sur chaque article et le prix de vente que nous vous conseillons. <b>Rien ne change tant que vous ne cliquez pas sur « Appliquer ».</b></p></div>' +
              '<button type="button" class="pa-x" id="pa_close" aria-label="Fermer">&times;</button></div>' +
              '<div class="pa-body">' +
                '<div class="pa-q">1. Comment voulez-vous fixer vos prix ?</div>' +
                '<div class="pa-modes">' +
                  '<label class="pa-mode sel" data-m="keep"><input type="radio" name="pa_mode" value="keep" checked><b>Garder mon gain habituel</b><small>Chaque article garde le même pourcentage de gain qu\'avant. Le plus simple.</small></label>' +
                  '<label class="pa-mode" data-m="target"><input type="radio" name="pa_mode" value="target"><b>Viser un gain fixe</b><small>Le même pourcentage pour tous les articles.</small><span class="pa-inline">Gain visé <input type="number" id="pa_margin" min="0" step="1" value="25"> %</span></label>' +
                  '<label class="pa-mode" data-m="category"><input type="radio" name="pa_mode" value="category"><b>Comme le reste du rayon</b><small>On s\'aligne sur le gain moyen des articles de la même catégorie.</small></label>' +
                '</div>' +
                '<div class="pa-round"><span>2. Arrondir les prix :</span><select id="pa_round"><option value="none">Pas d\'arrondi</option><option value="0.05">Aux 5 centimes</option><option value="0.10">Aux 10 centimes</option><option value="1">À l\'euro</option><option value="0.90">Finir par ,90</option></select></div>' +
                '<div class="pa-q">3. Vérifiez et cochez ce que vous voulez changer</div>' +
                '<div id="pa_sum" class="pa-sum"></div><div id="pa_list"></div>' +
              '</div>' +
              '<div class="pa-foot"><span id="pa_count" class="text-muted" style="font-size:.88rem"></span><span><button type="button" class="btn btn-light" id="pa_cancel">Fermer sans rien changer</button> <button type="button" class="btn-ok" id="pa_apply"><i class="fas fa-check"></i> Appliquer</button></span></div>' +
            '</div></div>');
    }
    function paOpen() { ensurePaModal(); $('#pa_overlay').addClass('open'); $('body').css('overflow', 'hidden'); }
    function paClose() { $('#pa_overlay').removeClass('open'); $('body').css('overflow', ''); }
    $(document).on('click', '#pa_close, #pa_cancel', paClose);
    $(document).on('mousedown', '#pa_overlay', function (e) { if (e.target.id === 'pa_overlay') paClose(); });
    $(document).on('keydown', function (e) { if (e.key === 'Escape' && $('#pa_overlay').hasClass('open')) paClose(); });

    function paMessage(r) {
        var gb = r.pv - r.old_net, gn = r.pv - r.new_net;
        var d = r.delta_pct === null ? 0 : r.delta_pct;
        if (r.flag === 'perte') return ['red', 'Vous vendez à perte', 'Acheté ' + eur(r.new_net) + ', vendu ' + eur(r.pv) + ' : vous perdez ' + eur(-gn) + ' à chaque pièce.'];
        if (r.flag === 'marge_faible') return ['orange', 'Gain très faible', 'Il ne vous reste que ' + eur(gn) + ' par pièce (' + fr(r.margin_now) + ' %).'];
        if (r.flag === 'hausse') return ['orange', 'Le prix d\'achat a augmenté de ' + fr(d) + ' %', 'Votre gain par pièce passe de ' + eur(gb) + ' à ' + eur(gn) + ' si vous ne touchez pas au prix de vente.'];
        if (r.flag === 'baisse') return ['blue', 'Le prix d\'achat a baissé de ' + fr(-d) + ' %', 'Votre gain par pièce monte de ' + eur(gb) + ' à ' + eur(gn) + '. Vous pouvez garder cet avantage ou baisser votre prix.'];
        return ['green', 'Rien d\'important à changer', 'Votre gain par pièce reste de ' + eur(gn) + '.'];
    }

    function paCard(r, i) {
        var m = paMessage(r), cls = r.flag || 'ok';
        var checked = r.changed && r.flag ? 'checked' : '';
        return '<div class="pa-card ' + cls + (checked ? '' : ' off') + '" data-i="' + i + '" data-code="' + esc(r.code) + '" data-net="' + r.new_net + '" data-pv="' + r.pv + '">' +
            '<div><h6>' + esc(r.code) + ' <small>— ' + esc(r.name) + '</small></h6>' +
            '<div class="pa-msg"><span class="tag ' + m[0] + '">' + esc(m[1]) + '.</span> ' + esc(m[2]) + '</div>' +
            '<div class="pa-nums"><span>Prix d\'achat : <b>' + eur(r.old_net) + '</b> → <b>' + eur(r.new_net) + '</b></span></div></div>' +
            '<div class="pa-right"><div class="pa-price"><span class="old">' + eur(r.pv) + '</span> →' +
            '<input type="number" step="0.01" min="0.01" class="pa-new" value="' + r.suggested.toFixed(2) + '" aria-label="Nouveau prix de vente"></div>' +
            '<div class="pa-gain"></div>' +
            '<label class="pa-sw"><input type="checkbox" class="pa-chk" ' + checked + '> Changer ce prix</label></div></div>';
    }

    function paUpdateCard($c) {
        var net = num($c.data('net')), v = num($c.find('.pa-new').val()), g = v - net, pct = net > 0 ? g / net * 100 : 0;
        var $g = $c.find('.pa-gain').removeClass('good bad');
        if (g < 0) $g.addClass('bad').text('Vous perdriez ' + eur(-g) + ' par pièce');
        else $g.addClass('good').text('Vous gagnez ' + eur(g) + ' par pièce (' + fr(pct) + ' %)');
        $c.toggleClass('off', !$c.find('.pa-chk').is(':checked'));
    }
    function paCount() {
        var n = $('#pa_list .pa-chk:checked').length;
        $('#pa_apply').prop('disabled', n === 0).html('<i class="fas fa-check"></i> Appliquer ' + (n ? n + ' changement' + (n > 1 ? 's' : '') : ''));
        $('#pa_count').text(n ? n + ' prix de vente seront modifiés' : 'Aucun changement sélectionné');
    }

    function paRender(rows) {
        paRowsData = rows;
        var perte = rows.filter(function (r) { return r.flag === 'perte'; }).length;
        var low = rows.filter(function (r) { return r.flag === 'marge_faible'; }).length;
        var chg = rows.filter(function (r) { return r.flag === 'hausse' || r.flag === 'baisse'; }).length;
        var ok = rows.filter(function (r) { return !r.flag; }).length;
        $('#pa_sum').html(
            (perte ? '<span class="pa-pill red">' + perte + (perte > 1 ? ' articles vendus à perte' : ' article vendu à perte') + '</span>' : '') + (low ? '<span class="pa-pill orange">' + low + (low > 1 ? ' articles à gain très faible' : ' article à gain très faible') + '</span>' : '') +
            (chg ? '<span class="pa-pill blue">' + chg + (chg > 1 ? ' prix d\'achat ont changé' : ' prix d\'achat a changé') + '</span>' : '') + (ok ? '<span class="pa-pill green">' + ok + ' sans souci</span>' : ''));
        var need = rows.filter(function (r) { return r.flag; }), fine = rows.filter(function (r) { return !r.flag; });
        var h = need.length ? need.map(function (r) { return paCard(r, rows.indexOf(r)); }).join('') : '<div class="alert alert-success mb-2">Tout est en ordre : aucun article ne demande de changement de prix.</div>';
        if (fine.length) h += '<details class="pa-more"' + (need.length ? '' : ' open') + '><summary>Voir les ' + fine.length + ' article(s) sans souci</summary><div style="margin-top:.5rem">' + fine.map(function (r) { return paCard(r, rows.indexOf(r)); }).join('') + '</div></details>';
        $('#pa_list').html(h);
        $('#pa_list .pa-card').each(function () { paUpdateCard($(this)); });
        paCount();
    }

    function paLoad() {
        paOpts = { strategy: $('input[name=pa_mode]:checked').val() || 'keep', margin: num($('#pa_margin').val()) || 25, min_margin: 10, round: $('#pa_round').val() || 'none' };
        $('#pa_list').html('<div class="py-4 text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Calcul des prix conseillés…</div>');
        $.ajax({ url: REVIEW_URL, method: 'POST', dataType: 'json', data: { _token: CSRF, supplier_id: supplierId(), lines: paRows(), strategy: paOpts.strategy, margin: paOpts.margin, min_margin: paOpts.min_margin, round: paOpts.round } })
        .done(function (res) { paRender(res.rows || []); })
        .fail(function () { $('#pa_list').html('<div class="alert alert-danger">Le calcul n\'a pas pu aboutir, réessayez.</div>'); });
    }

    $(document).on('change', 'input[name=pa_mode]', function () { $('.pa-mode').removeClass('sel'); $(this).closest('.pa-mode').addClass('sel'); paLoad(); });
    $(document).on('change', '#pa_round', paLoad);
    $(document).on('change', '#pa_margin', function () { if ($('input[name=pa_mode]:checked').val() !== 'target') { $('input[name=pa_mode][value=target]').prop('checked', true).trigger('change'); } else { paLoad(); } });
    $(document).on('input', '.pa-new', function () { var $c = $(this).closest('.pa-card'); $c.find('.pa-chk').prop('checked', true); paUpdateCard($c); paCount(); });
    $(document).on('change', '.pa-chk', function () { paUpdateCard($(this).closest('.pa-card')); paCount(); });

    function setLinePv(code, v) {
        $('#lines_body tr').filter(function () { return !$(this).data('new') && String($(this).data('code')).toLowerCase() === String(code).toLowerCase(); }).each(function () {
            $(this).find('.pv-hid').val(v.toFixed(2)); $(this).find('.pv-txt').text('PV ' + fr(v) + ' €'); recalcRow($(this));
        });
    }

    $(document).on('click', '#pa_apply', function () {
        var changes = [];
        $('#pa_list .pa-chk:checked').each(function () { var $c = $(this).closest('.pa-card'); changes.push({ code: $c.data('code'), new_pv: num($c.find('.pa-new').val()), net: num($c.data('net')) }); });
        if (!changes.length) return;
        $('#pa_apply').prop('disabled', true);
        $.ajax({ url: APPLY_URL, method: 'POST', dataType: 'json', data: { _token: CSRF, changes: changes, strategy: paOpts.strategy } })
        .done(function (res) {
            var done = res.done || [];
            done.forEach(function (d) { setLinePv(d.code, d.new); });
            recalcTotals(); paClose();
            Swal.fire({ icon: 'success', title: done.length + ' prix de vente mis à jour', text: 'Le changement est enregistré dans l\'historique. Vous pouvez encore l\'annuler.', showCancelButton: true, confirmButtonText: 'Parfait', cancelButtonText: 'Annuler ces changements' })
            .then(function (r) {
                if (r.dismiss !== Swal.DismissReason.cancel) return;
                $.ajax({ url: APPLY_URL, method: 'POST', dataType: 'json', data: { _token: CSRF, source: 'annulation', changes: done.map(function (d) { return { code: d.code, new_pv: d.old }; }) } })
                .done(function () { done.forEach(function (d) { setLinePv(d.code, d.old); }); recalcTotals(); Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Anciens prix de vente rétablis', showConfirmButton: false, timer: 2200 }); });
            });
        })
        .fail(function () { $('#pa_apply').prop('disabled', false); Swal.fire('Erreur', 'Les prix n\'ont pas pu être enregistrés.', 'error'); });
    });

    $('#price_assistant_btn').on('click', function () {
        if (!supplierId()) { Swal.fire('Fournisseur', "Choisissez d'abord un fournisseur.", 'info'); return; }
        if (!paRows().length) { Swal.fire('Aucune ligne', "Ajoutez des articles existants : on compare leur nouveau prix d'achat à leur prix de vente.", 'info'); return; }
        paOpen(); paLoad();
    });

    $('#add_divers_item').on('click', function () {
        var $tr = appendLine({ code: '', name: '', stock: 0, qty: 1, pu: 0, remise: 0, tva: supplierTva(), sale: 0, own: true, isNew: true });
        recalcTotals(); scheduleDraftSave();
        $tr.find('.ref-in').trigger('focus');
    });

    $(document).on('click', '.remove_line', function () { $(this).closest('tr').remove(); updateEmptyHint(); recalcTotals(); scheduleDraftSave(); });
    $(document).on('input change', '#lines_body input', function () { var $tr = $(this).closest('tr'); recalcRow($tr); recalcTotals(); scheduleDraftSave(); });
    $(document).on('focus click', '#lines_body input[type=number]', function () { this.select(); });
    $(document).on('input', '.ref-in', function () { this.value = this.value.toUpperCase(); $(this).closest('tr').data('code', this.value); });
    $('#notes, #supplier_ref, #due_date, #order_date, #store_id').on('input change', scheduleDraftSave);

    function updateEmptyHint() { $('#empty_hint').toggle($('#lines_body tr').length === 0); }

    function recalcRow($tr) {
        var qty = Math.max(0, num($tr.find('.quantity').val()));
        var pu = num($tr.find('.unit_price_ht').val()), rem = Math.min(100, Math.max(0, num($tr.find('.remise').val())));
        var tva = num($tr.find('.tva-in').val());
        var net = pu * (1 - rem / 100);
        var ht = r2(qty * net), ttc = r2(ht * (1 + tva / 100));
        $tr.find('.net-price').text(fr(net));
        $tr.find('.row-ht').text(fr(ht)).data('v', ht);
        $tr.find('.row-ttc').text(fr(ttc)).data('v', ttc);
        $tr.data('tvaAmount', r2(ttc - ht));

        var pv = $tr.data('new') ? num($tr.find('.pv-in').val()) : num($tr.find('.pv-hid').val());
        var $mg = $tr.find('.mg').removeClass('mg-ok mg-bad').empty();
        var loss = false;
        if (pv > 0 && net > 0) {
            var m = pv - net, pct = net > 0 ? (m / net) * 100 : 0;
            if (m < 0) { loss = true; $mg.addClass('mg-bad').text('Perte ' + fr(-m) + ' € / u'); }
            else { $mg.addClass('mg-ok').text('Marge ' + fr(m) + ' € (' + Math.round(pct) + ' %)'); }
        } else if (pv <= 0 && !$tr.data('new')) {
            $mg.text('PV non renseigné');
        }
        $tr.toggleClass('row-loss', loss);
        if (loss && !$tr.data('lossWarned') && Date.now() - lossToastAt > 4000) {
            $tr.data('lossWarned', true); lossToastAt = Date.now();
            Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Prix d\'achat net supérieur au prix de vente (' + $tr.data('code') + ')', showConfirmButton: false, timer: 3500 });
        }
        if (!loss) $tr.data('lossWarned', false);
    }

    function recalcAll() { $('#lines_body tr').each(function () { recalcRow($(this)); }); recalcTotals(); }

    function recalcTotals() {
        var ht = 0, ttc = 0;
        $('#lines_body tr').each(function () { ht += num($(this).find('.row-ht').data('v')); ttc += num($(this).find('.row-ttc').data('v')); });
        $('#total_ht_global').text(fr(ht)); $('#total_ttc_global').text(fr(ttc)); $('#total_tva_global').text(fr(ttc - ht));
        var bad = $('#lines_body tr:not([data-new="1"]) .mg-bad').length;
        $('#pa_badge').text(bad).toggle(bad > 0);
        return { ht: r2(ht), ttc: r2(ttc) };
    }

    /* ───────── clavier ───────── */
    $('#purchaseForm').on('keydown', function (e) {
        if (e.key !== 'Enter') return;
        var t = e.target;
        if (t.tagName === 'TEXTAREA' || t.tagName === 'BUTTON') return;
        e.preventDefault();
        if ($(t).hasClass('quantity')) { $('#search_item').trigger('focus'); }
    });
    $('#purchaseForm').on('submit', function (e) { e.preventDefault(); });

    /* ───────── validation + envoi ───────── */
    var CONF = {
        project:         { title: "Enregistrer le projet d'achat ?",   text: "Aucun effet sur le stock ni sur les coûts. Le projet pourra être converti en commande.", ok: "Enregistrer le projet", color: '#f0ad4e' },
        order:           { title: "Créer la commande d'achat ?",       text: "Le stock n'est pas modifié : il entrera lors de la réception.", ok: "Créer la commande", color: '#dc3545' },
        reception:       { title: "Valider la réception ?",            text: "Le stock est augmenté immédiatement et le coût des articles est mis à jour (dernier prix d'achat).", ok: "Valider la réception", color: '#0d6efd' },
        save:            { title: "Enregistrer les modifications ?", text: "Les lignes, quantités et prix sont remplacés par ce que vous voyez à l'écran.", ok: "Enregistrer", color: '#f0ad4e' },
        validate:        { title: "Valider le projet ?",              text: "Le projet est figé en « validé » ; il reste convertible en commande.", ok: "Valider le projet", color: '#0d6efd' },
        convert:         { title: "Convertir en commande d'achat ?",  text: "Les modifications sont enregistrées puis le projet devient une commande validée. Le stock entrera à la réception.", ok: "Convertir en commande", color: '#dc3545' },
        validate_order:  { title: "Valider la commande ?",            text: "La commande est enregistrée avec ces lignes puis validée. Le stock entrera à la réception.", ok: "Valider la commande", color: '#dc3545' },
        receive_invoice: { title: "Réceptionner et facturer ?",        text: "Le stock est augmenté, la facture d'achat est validée et le solde du fournisseur augmente du total TTC.", ok: "Réceptionner et facturer", color: '#198754' }
    };

    function validateForm() {
        if (!supplierId()) return 'Choisissez un fournisseur.';
        var $rows = $('#lines_body tr');
        if (!$rows.length) return 'Ajoutez au moins une ligne au document.';
        var err = null, seen = {};
        $rows.each(function (i) {
            var $tr = $(this), n = i + 1, isNew = !!$tr.data('new');
            var code = $.trim(isNew ? $tr.find('.ref-in').val() : $tr.data('code'));
            if (!code) { err = 'Ligne ' + n + ' : la référence est obligatoire.'; return false; }
            if (isNew && !$.trim($tr.find('.name-in').val())) { err = 'Ligne ' + n + ' (' + code + ') : la désignation du nouvel article est obligatoire.'; return false; }
            var q = num($tr.find('.quantity').val());
            if (q < 1 || Math.abs(q - Math.round(q)) > 1e-9) { err = 'Ligne ' + n + ' (' + code + ') : la quantité doit être un entier supérieur à 0.'; return false; }
            if (num($tr.find('.unit_price_ht').val()) < 0) { err = 'Ligne ' + n + ' (' + code + ') : le prix ne peut pas être négatif.'; return false; }
            var rm = num($tr.find('.remise').val()); if (rm < 0 || rm > 100) { err = 'Ligne ' + n + ' (' + code + ') : remise entre 0 et 100 %.'; return false; }
            var key = code.toLowerCase();
            if (isNew && seen[key]) { err = 'Ligne ' + n + ' : la référence ' + code + ' est saisie deux fois.'; return false; }
            seen[key] = 1;
        });
        return err;
    }

    $(document).on('click', '.js-act', function () {
        if (submitting) return;
        var action = $(this).data('action'), cfg = CONF[action];
        var err = validateForm();
        if (err) { Swal.fire({ icon: 'warning', title: 'Document incomplet', text: err }); return; }
        var t = recalcTotals(), n = $('#lines_body tr').length, newCount = $('#lines_body tr').filter(function () { return $(this).data('new'); }).length;
        var extra = '<div style="margin-top:.6rem;font-size:.92rem"><b>' + esc(supplierOpt().text()) + '</b><br>' + n + ' ligne(s) · Total HT ' + fr(t.ht) + ' € · <b>Total TTC ' + fr(t.ttc) + ' €</b>' +
            (newCount ? '<br><span style="color:#0d6efd">' + newCount + ' nouvel(s) article(s) seront créés dans le catalogue.</span>' : '') + '</div>';
        Swal.fire({
            icon: 'question', title: cfg.title, html: esc(cfg.text) + extra,
            showCancelButton: true, confirmButtonText: cfg.ok, cancelButtonText: 'Annuler', confirmButtonColor: cfg.color
        }).then(function (res) {
            if (!res.isConfirmed) return;
            submitting = true;
            $('#form_action').val(action === 'validate_order' ? 'validate' : action);
            $('.js-act').prop('disabled', true);
            clearTimeout(draftTimer); lsDel(DRAFT_KEY);
            document.getElementById('purchaseForm').submit();
        });
    });

    window.addEventListener('pageshow', function (ev) { if (ev.persisted) { submitting = false; $('.js-act').prop('disabled', false); } });

    /* ───────── brouillon local ───────── */
    var draftTimer = null;
    function collectDraft() {
        var lines = [];
        $('#lines_body tr').each(function () {
            var $tr = $(this), isNew = !!$tr.data('new');
            lines.push({
                isNew: isNew, code: isNew ? $tr.find('.ref-in').val() : $tr.data('code'),
                name: isNew ? $tr.find('.name-in').val() : $tr.find('.des-name').text(),
                stock: $tr.find('[name$="[_stock]"]').val(), own: num($tr.find('[name$="[_own]"]').val()) === 1,
                qty: $tr.find('.quantity').val(), pu: $tr.find('.unit_price_ht').val(), remise: $tr.find('.remise').val(),
                tva: $tr.find('.tva-in').val(), sale: isNew ? $tr.find('.pv-in').val() : $tr.find('.pv-hid').val()
            });
        });
        return { t: Date.now(), supplier: supplierId(), date: $('#order_date').val(), store: $('#store_id').val(), ref: $('#supplier_ref').val(), due: $('#due_date').val(), notes: $('#notes').val(), lines: lines };
    }
    function saveDraft() {
        if (submitting || MODE !== 'create') return;
        var d = collectDraft();
        if (!d.lines.length) { lsDel(DRAFT_KEY); return; }
        lsSet(DRAFT_KEY, JSON.stringify(d));
    }
    function scheduleDraftSave() { clearTimeout(draftTimer); draftTimer = setTimeout(saveDraft, 700); }

    function restoreLines(lines) {
        lines.forEach(function (l) { appendLine(l); });
        recalcAll(); updateEmptyHint();
    }

    function restoreDraft(d) {
        restoring = true;
        if (d.supplier) { $supplier.val(d.supplier).trigger('change.select2').trigger('change'); }
        if (d.date) $('#order_date').val(d.date);
        if (d.store && $('#store_id').length) $('#store_id').val(d.store);
        $('#supplier_ref').val(d.ref || ''); $('#due_date').val(d.due || ''); $('#notes').val(d.notes || '');
        restoreLines(d.lines || []);
        restoring = false; prevSupplier = supplierId();
    }

    /* ───────── démarrage ───────── */
    $(function () {
        showBanner(); prevSupplier = supplierId();
        var oldLines = [];
        if (OLD_LINES && typeof OLD_LINES === 'object') { Object.keys(OLD_LINES).forEach(function (k) { oldLines.push(OLD_LINES[k]); }); }
        if (oldLines.length) {
            // Retour d'une erreur serveur : on remet exactement ce que l'utilisateur avait saisi
            restoreLines(oldLines.map(function (l) {
                return { isNew: String(l.is_new_item || '') === '1', code: l.article_code || '', name: l.item_name || '', stock: l._stock || 0,
                         own: String(l._own === undefined ? 1 : l._own) === '1', qty: l.quantity || 1, pu: l.unit_price_ht || 0, remise: l.remise || 0,
                         tva: l.tva === undefined ? 0 : l.tva, sale: l.sale_price || 0 };
            }));
            return;
        }
        updateEmptyHint();
        var raw = lsGet(DRAFT_KEY), d = null;
        try { d = raw ? JSON.parse(raw) : null; } catch (e) { d = null; }
        if (d && d.lines && d.lines.length && Date.now() - d.t < 86400000) {
            Swal.fire({
                icon: 'question', title: 'Brouillon non enregistré', text: 'Un document en cours (' + d.lines.length + ' ligne(s)) a été retrouvé. Le reprendre ?',
                showCancelButton: true, confirmButtonText: 'Reprendre', cancelButtonText: 'Repartir de zéro'
            }).then(function (res) { if (res.isConfirmed) restoreDraft(d); else lsDel(DRAFT_KEY); });
        }
    });
})();
</script>

@include('partials.select2_init')
</body>
</html>
