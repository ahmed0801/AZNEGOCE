<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />
<!-- jQuery + Bootstrap JS (v4) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />






    <style>
#panierDropdown + .dropdown-menu {
    width: 900px; /* Adjust the width as needed */
    min-width: 350px; /* Ensure a minimum width */
    padding: 10px; /* Add padding to create space inside the dropdown */
    border-radius: 8px; /* Optional: Rounded corners for a cleaner look */
}

.panier-dropdown {
    width: 100%; /* Use full width of the parent container */
    min-width: 350px; /* Ensure minimum width */
}

.panier-dropdown .notif-item {
    padding: 10px; /* Add padding between items */
    margin-bottom: 5px; /* Space between items */
    border-bottom: 1px solid #ddd; /* Optional: Border between items */
}

.dropdown-title {
    font-weight: bold;
    margin-bottom: 10px; /* Space below the title */
}

.notif-scroll {
    padding: 10px; /* Add padding inside the scrollable area */
}

.notif-center {
    padding: 5px 0; /* Space around each notification */
}

.dropdown-footer {
    padding: 10px;
    border-top: 1px solid #ddd; /* Optional: Border to separate the footer */
}

.table {
    width: 100%;
    margin-bottom: 0;
}

.table th, .table td {
    text-align: center;
    vertical-align: middle;
}



.table-striped tbody tr:nth-child(odd) {
    background-color: #f2f2f2;
}

.btn-sm {
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
}

.text-muted {
    font-size: 0.85rem;
}

.text-center {
    text-align: center;
}


.card {
    border-radius: 12px;
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card h3 {
    font-size: 1.8rem;
    color: #007bff;
    margin-bottom: 1rem;
    font-weight: 700;
}

.card h6 {
    font-size: 1rem;
    color: #6c757d;
}

.card-body {
    padding: 2rem;
}

.card .text-info {
    color: #17a2b8 !important;
}



.card {
    border-radius: 12px;
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card h3 {
    font-size: 1.8rem;
    color: #007bff;
    font-weight: 700;
}

.card h6 {
    font-size: 1rem;
    color: #6c757d;
}

.card-body {
    padding: 2rem;
}

.text-info {
    color: #17a2b8 !important;
}

.btn-primary {
    font-size: 1.1rem;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
}








:root {
    --c-navy:   #1E2D4A;
    --c-blue:   #3B82F6;
    --c-mint:   #10B981;
    --c-amber:  #F59E0B;
    --c-red:    #EF4444;
    --c-bg:     #F0F4FF;
    --c-white:  #FFFFFF;
    --c-text:   #1A2B4A;
    --c-sub:    #6B7A99;
    --c-border: #E2E8F0;
    --shadow:   0 8px 32px rgba(30,45,74,0.12);
}
.az-modal .modal-content {
    border:none; border-radius:20px;
    overflow:hidden; box-shadow:var(--shadow);
}
.az-modal .modal-header {
    background:linear-gradient(135deg, var(--c-navy) 0%, #2D4A8A 100%);
    padding:20px 28px 16px; border:none; position:relative;
}
.az-modal .modal-header::after {
    content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
    background:linear-gradient(90deg, var(--c-blue), var(--c-mint), var(--c-amber));
}
.az-modal .modal-title { color:white; font-size:1.05rem; font-weight:700; letter-spacing:0.02em; }
.az-modal .modal-subtitle { color:rgba(255,255,255,0.55); font-size:0.75rem; margin-top:2px; }
.az-modal .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
.az-stepper {
    display:flex; align-items:center;
    padding:18px 28px 0; background:var(--c-bg); gap:0;
}
.az-step { display:flex; align-items:center; flex:1; position:relative; }
.az-step-circle {
    width:32px; height:32px; border-radius:50%;
    background:white; border:2px solid var(--c-border);
    color:var(--c-sub); font-size:0.78rem; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    transition:all 0.3s ease; flex-shrink:0; z-index:1;
}
.az-step.active .az-step-circle {
    background:var(--c-blue); border-color:var(--c-blue); color:white;
    box-shadow:0 0 0 4px rgba(59,130,246,0.2);
}
.az-step.done .az-step-circle { background:var(--c-mint); border-color:var(--c-mint); color:white; }
.az-step-label { font-size:0.72rem; font-weight:600; color:var(--c-sub); margin-left:8px; white-space:nowrap; transition:color 0.3s; }
.az-step.active .az-step-label { color:var(--c-blue); }
.az-step.done .az-step-label   { color:var(--c-mint); }
.az-step-line { flex:1; height:2px; background:var(--c-border); margin:0 10px; transition:background 0.4s; }
.az-step-line.done { background:var(--c-mint); }
.az-step-panel { display:none; animation:fadeSlide 0.3s ease; }
.az-step-panel.active { display:block; }
@keyframes fadeSlide {
    from { opacity:0; transform:translateX(12px); }
    to   { opacity:1; transform:translateX(0); }
}
.az-modal .modal-body { background:var(--c-bg); padding:20px 28px 8px; }
.az-field-group { display:grid; gap:14px; }
.az-field { display:flex; flex-direction:column; gap:5px; }
.az-label { font-size:0.73rem; font-weight:700; color:var(--c-sub); text-transform:uppercase; letter-spacing:0.06em; }
.az-label .required { color:var(--c-red); margin-left:2px; }
.az-input {
    border:1.5px solid var(--c-border); border-radius:8px;
    padding:9px 13px; font-size:0.875rem; color:var(--c-text);
    background:white; transition:border-color 0.2s, box-shadow 0.2s;
    outline:none; width:100%;
}
.az-input:focus { border-color:var(--c-blue); box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
.az-input.is-invalid { border-color:var(--c-red); }
.az-input:disabled { background:#f1f5f9; color:#64748b; cursor:not-allowed; }
.az-select {
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7A99' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 12px center; padding-right:32px;
}
.az-section-title {
    font-size:0.8rem; font-weight:700; color:var(--c-navy);
    letter-spacing:0.08em; text-transform:uppercase;
    display:flex; align-items:center; gap:8px;
    margin-bottom:12px; padding-bottom:8px; border-bottom:1.5px solid var(--c-border);
}
.az-section-title i { color:var(--c-blue); font-size:0.9rem; }
.az-modal .modal-footer {
    background:white; border-top:1.5px solid var(--c-border);
    padding:14px 28px; display:flex; justify-content:space-between; align-items:center;
}
.az-btn-prev {
    background:none; border:1.5px solid var(--c-border); color:var(--c-sub);
    border-radius:8px; padding:8px 18px; font-size:0.82rem; font-weight:600;
    cursor:pointer; transition:all 0.2s;
}
.az-btn-prev:hover { border-color:var(--c-blue); color:var(--c-blue); }
.az-btn-next {
    background:var(--c-blue); border:none; color:white; border-radius:8px;
    padding:8px 22px; font-size:0.82rem; font-weight:700; cursor:pointer;
    transition:all 0.2s; display:flex; align-items:center; gap:6px;
}
.az-btn-next:hover { background:#2563EB; transform:translateY(-1px); }
.az-btn-submit {
    background:linear-gradient(135deg, var(--c-mint), #059669);
    border:none; color:white; border-radius:8px; padding:8px 22px;
    font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.2s;
    display:none; align-items:center; gap:6px;
}
.az-btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,0.3); }
.az-step-counter { font-size:0.75rem; color:var(--c-sub); }
/* Edit */
.az-edit-header {
    background:linear-gradient(135deg, #1a2b4a 0%, #2d4a8a 100%);
    padding:16px 24px; display:flex; align-items:center; justify-content:space-between;
    border-radius:20px 20px 0 0;
}
.az-edit-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
    border-radius:20px; padding:4px 12px; font-size:0.72rem; font-weight:700;
    color:white; letter-spacing:0.05em;
}
.az-edit-title { color:white; font-weight:700; font-size:1rem; margin:0; }
.az-edit-subtitle { color:rgba(255,255,255,0.6); font-size:0.78rem; margin:2px 0 0; }
.az-lock-btn {
    background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.3);
    color:white; border-radius:8px; padding:6px 14px; font-size:0.78rem; font-weight:700;
    cursor:pointer; transition:all 0.25s; display:flex; align-items:center; gap:6px;
}
.az-lock-btn:hover { background:rgba(255,255,255,0.25); }
.az-lock-btn.editing { background:rgba(239,68,68,0.2); border-color:rgba(239,68,68,0.4); color:#FCA5A5; }
.az-tabs {
    display:flex; gap:2px; background:var(--c-bg);
    padding:8px 24px 0; border-bottom:2px solid var(--c-border);
}
.az-tab {
    padding:8px 16px; font-size:0.78rem; font-weight:600; color:var(--c-sub);
    cursor:pointer; border-radius:8px 8px 0 0; border:none; background:none;
    border-bottom:2px solid transparent; margin-bottom:-2px; transition:all 0.2s;
}
.az-tab.active { color:var(--c-blue); background:white; border-bottom-color:var(--c-blue); }
.az-tab-panel { display:none; padding:20px 24px; background:var(--c-bg); }
.az-tab-panel.active { display:block; animation:fadeSlide 0.25s ease; }
.az-solde-card {
    background:linear-gradient(135deg, var(--c-navy), #2D4A8A);
    border-radius:12px; padding:16px 20px; color:white;
    display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;
}
.az-solde-amount { font-size:1.8rem; font-weight:800; }
.az-solde-label  { font-size:0.72rem; opacity:0.7; text-transform:uppercase; letter-spacing:0.08em; }
.az-status-pill {
    display:inline-flex; align-items:center; gap:6px; padding:6px 14px;
    border-radius:20px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s;
}
.az-status-pill.active  { background:#D1FAE5; color:#065F46; }
.az-status-pill.blocked { background:#FEE2E2; color:#991B1B; }
.az-save-btn {
    background:linear-gradient(135deg, var(--c-mint), #059669);
    border:none; color:white; border-radius:8px; padding:8px 20px;
    font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.2s;
    display:none; align-items:center; gap:6px;
}
.az-save-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,0.3); }




.cl-bar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
.cl-bar h4{margin:0;font-weight:700}
.cl-btns{display:flex;flex-wrap:wrap;gap:.5rem}
.cl-btns .btn,.cl-filters .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:0 1rem;font-size:.88rem;font-weight:600;border-radius:8px;white-space:nowrap;margin:0}
.cl-filters{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;margin-bottom:1rem}
.cl-filters .form-control,.cl-filters .form-select{height:38px;border-radius:8px;font-size:.88rem}
.cl-filters .f-search{flex:2 1 260px}
.cl-filters .f-sel{flex:1 1 140px;min-width:130px}
.cl-filters .f-num{flex:0 1 120px;min-width:100px}




#allAccountingFilterForm .select2-container--default .select2-selection--single{height:31px;border-radius:.25rem;display:flex;align-items:center;font-size:.875rem}
#allAccountingFilterForm .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:29px}
#allAccountingFilterForm .select2-container--default .select2-selection--single .select2-selection__arrow{height:29px}
.modal-content .select2-dropdown{z-index:1070}

/* ---- tableau ---- */
#itemsTable{border-collapse:separate;border-spacing:0;width:100%;background:#fff;border:1px solid #E6ECF5;border-radius:14px;overflow:hidden}
/* ⚠ combinateurs ">" : les règles ne touchent QUE les cellules de ce tableau, jamais les fenêtres (modals) ni les tableaux imbriqués */
#itemsTable>thead>tr>th,#itemsTable>tbody>tr>td{text-align:left;vertical-align:middle;border:0;border-bottom:1px solid #EEF2F8;padding:.65rem .6rem}
#itemsTable>tbody>tr>td:not(:last-child){font-size:.86rem;color:#1A2B4A}
#itemsTable>thead>tr>th{background:#F6F8FC;color:#6B7A99;font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-bottom:1px solid #E6ECF5;white-space:nowrap}
#itemsTable>thead>tr>th.t-right,#itemsTable>tbody>tr>td.t-right{text-align:right}
#itemsTable>thead>tr>th.t-center,#itemsTable>tbody>tr>td.t-center{text-align:center}
#itemsTable>tbody>tr{transition:background .12s}
#itemsTable>tbody>tr:hover>td{background:#F8FAFF}
#itemsTable>tbody>tr:last-child>td{border-bottom:0}
#itemsTable>tbody>tr.is-blocked>td:first-child{box-shadow:inset 3px 0 0 #EF4444}
#itemsTable>tbody>tr>td:last-child{min-width:150px}   /* ni white-space ni text-align ici : les modals y sont imbriquées */
#itemsTable .modal{text-align:left;white-space:normal}
#itemsTable .act-btns{white-space:nowrap}
#itemsTable .act-btns .btn{border-radius:8px}

/* client */
.cl-who{display:flex;align-items:center;gap:.6rem;min-width:170px}
.cl-avatar{flex:0 0 38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800;letter-spacing:.02em}
.cl-avatar.t-particulier{background:#E0ECFF;color:#1D4ED8}
.cl-avatar.t-jobber{background:#FEF0D2;color:#B45309}
.cl-avatar.t-professionnel{background:#D6F5E8;color:#047857}
.cl-who-txt{min-width:0}
.cl-name{font-weight:700;color:#1A2B4A;max-width:190px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.cl-meta{display:flex;flex-wrap:wrap;align-items:center;gap:.35rem;margin-top:2px}
.cl-code{font-size:.74rem;color:#8A97B1;font-weight:600}
.cl-pill{display:inline-flex;align-items:center;gap:.25rem;border-radius:999px;padding:1px 8px;font-size:.68rem;font-weight:700}
.cl-pill.t-particulier{background:#E0ECFF;color:#1D4ED8}
.cl-pill.t-jobber{background:#FEF0D2;color:#B45309}
.cl-pill.t-professionnel{background:#D6F5E8;color:#047857}
.cl-pill.st-blocked{background:#FEE2E2;color:#B91C1C}

/* adresse / contact */
.cl-line{max-width:165px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.45}
.cl-line i{width:14px;color:#9AA7C0;font-size:.75rem;margin-right:3px}
.cl-sub{color:#6B7A99;font-size:.8rem}
.cl-empty{color:#B6C0D4;font-size:.8rem}

/* solde : bouton cliquable (ouvre les écritures comptables) */
.cl-solde{display:inline-flex;align-items:center;gap:.45rem;white-space:nowrap;border:1.5px solid transparent;border-radius:999px;padding:4px 12px;font-size:.92rem;font-weight:800;line-height:1.2;cursor:pointer;transition:transform .12s,box-shadow .12s,background .12s}
.cl-solde i{font-size:.72rem;opacity:.7}
.cl-solde:hover{transform:translateY(-1px);box-shadow:0 3px 10px rgba(30,45,74,.14)}
.cl-solde:active{transform:none;box-shadow:none}
.cl-solde.is-due{color:#C2410C;background:#FFF1E6;border-color:#FDBA74}
.cl-solde.is-due:hover{background:#FFE4CC}
.cl-solde.is-credit{color:#1D4ED8;background:#E8F0FF;border-color:#A9C5FF}
.cl-solde.is-credit:hover{background:#D6E5FF}
.cl-solde.is-zero{color:#047857;background:#E6F8F0;border-color:#9FE0C2}
.cl-solde.is-zero:hover{background:#D3F3E5}
.cl-solde-lbl{white-space:nowrap;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-top:4px;padding-right:6px}
.cl-solde-lbl.is-due{color:#EA580C}
.cl-solde-lbl.is-credit{color:#3B82F6}
.cl-solde-lbl.is-zero{color:#10B981}


/* ---- spécifique fournisseurs ---- */
#itemsTable .act-btns{display:flex;flex-direction:column;gap:.3rem;white-space:nowrap}
#itemsTable .act-btns+.act-btns{margin-top:.3rem}
#itemsTable .act-btns .btn{display:inline-flex;align-items:center;justify-content:flex-start;gap:.4rem;font-size:.78rem;font-weight:600;text-align:left}
.cl-avatar.t-supplier{background:#E7E9FF;color:#4338CA}
.cl-pill.st-b2b{background:#D6F5E8;color:#047857}
.cl-pill.st-no{background:#F1F4F9;color:#8A97B1}
.cl-tag{display:inline-block;margin-left:4px;padding:0 6px;border-radius:6px;background:#F1F4F9;color:#6B7A99;font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.03em}
.cl-b2b-link{display:inline-flex;align-items:center;gap:.3rem;margin-top:5px;font-size:.74rem;font-weight:600;color:#1D4ED8;text-decoration:none}
.cl-b2b-link:hover{text-decoration:underline}
.cl-solde.is-open{color:#3730A3;background:#EEF0FF;border-color:#C7CCFF}
.cl-solde.is-open:hover{background:#DFE3FF}
.cl-solde-lbl.is-open{color:#6366F1}
.cl-empty-state{text-align:center;padding:3rem 1rem;color:#8A97B1}
.cl-empty-state i{font-size:2.4rem;color:#C5CEE0;margin-bottom:.8rem}
.cl-empty-state h5{color:#6B7A99;font-weight:700}
/* écrans larges : on réaffiche les étiquettes Standard / Commercial et le texte du lien B2B */
@media (min-width:1500px){
  .cl-line{max-width:230px}
}
/* portables (< 1500 px) : on masque ces détails pour que la colonne Actions reste visible sans défilement */
@media (max-width:1499px){
  #itemsTable>thead>tr>th,#itemsTable>tbody>tr>td{padding:.6rem .45rem}
  .cl-who{min-width:0;gap:.45rem}
  .cl-avatar{flex-basis:32px;height:32px;font-size:.72rem}
  .cl-name{max-width:140px}
  .cl-line{max-width:125px}
  #itemsTable>tbody>tr>td:last-child{min-width:0}
  .cl-tag,.cl-b2b-txt{display:none}
  .cl-b2b-link{margin-top:3px}
}
.az-b2b-box{background:#fff;border:1.5px solid var(--c-border);border-radius:12px;padding:14px 16px}
.az-b2b-box .form-check-label{font-weight:700;color:#047857}
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
                            <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                            <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d’Achat</span></a></li>
                            <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                            <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
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

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="{{ asset('assets/img/logop.png')}}"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">





              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">





                    <!-- test quick action  -->
<li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Actions Rapides</span>
                      <!-- <span class="subtitle op-7">Liens Utiles</span> -->
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">

                                                  <a class="col-6 col-md-4 p-0" href="/articles">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-sitemap"></i>
                              </div>
                              <span class="text">Articles</span>
                            </div>
                          </a>

                                                                            <a class="col-6 col-md-4 p-0" href="/customers">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-users"></i>
                              </div>
                              <span class="text">Clients</span>
                            </div>
                          </a>


                                                                                                      <a class="col-6 col-md-4 p-0" href="/suppliers">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-user-tag"></i>
                              </div>
                              <span class="text">Fournisseurs</span>
                            </div>
                          </a>



                          <a class="col-6 col-md-4 p-0" href="/delivery_notes/list">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="fa fa-cart-plus"></i>
                              </div>
                              <span class="text">Commandes Ventes</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/salesinvoices">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Factures Ventes</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/generalaccounts">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-money-check-alt"></i>
                              </div>
                              <span class="text">Plan Comptable</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/purchases/list">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fa fa-cart-plus"></i>
                              </div>
                              <span class="text">Commandes Achats</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="/invoices">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Factures Achats</span>
                            </div>
                          </a>

                          <a class="col-6 col-md-4 p-0" href="/paymentlist">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Paiements</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                        <!-- fin test quick action  -->
                

 





              <!-- test panier -->
      

         
                
                
                

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="{{ asset('assets/img/avatar.png')}}"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <!-- <span class="op-7">Hi,</span> -->
                      <span class="fw-bold">{{ Auth::user()->name}}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="{{ asset('assets/img/avatar.png')}}"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
<div class="u-text">
                            <h4>{{ Auth::user()->name}}</h4>

                            <p class="text-muted">{{ Auth::user()->email}}</p>
                            <a
                              href="/setting"
                              class="btn btn-xs btn-secondary btn-sm"
                              >Paramétres</a>

                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <!-- <a class="dropdown-item" href="#">My Profile</a> -->
                        <!-- <a class="dropdown-item" href="#">My Balance</a> -->
                        <!-- <div class="dropdown-divider"></div> -->

    <!-- Formulaire de déconnexion -->
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
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
          @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



        <div class="container-fluid mt-4">
        {{-- Affichage des messages d'erreur --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    
        <div class="container-fluid mt-4">

       <div class="cl-bar">
  <h4><i class="fas fa-truck text-primary"></i> Liste des fournisseurs</h4>
  <div class="cl-btns">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createItemModal"><i class="fas fa-plus-circle"></i> Nouveau fournisseur</button>
    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#allAccountingModal"><i class="fas fa-balance-scale"></i> Écritures comptables achats</button>
  </div>
</div>
    <!-- Modal création : 3 étapes -->
    <div class="modal fade az-modal" id="createItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <div class="modal-title">Nouveau fournisseur</div>
                        <div class="modal-subtitle">Identité → Contact &amp; B2B → Finances</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="az-stepper">
                    <div class="az-step active" data-step="1"><div class="az-step-circle">1</div><div class="az-step-label">Identité</div></div>
                    <div class="az-step-line" id="line1"></div>
                    <div class="az-step" data-step="2"><div class="az-step-circle">2</div><div class="az-step-label">Contact &amp; B2B</div></div>
                    <div class="az-step-line" id="line2"></div>
                    <div class="az-step" data-step="3"><div class="az-step-circle">3</div><div class="az-step-label">Finances</div></div>
                </div>

                <form action="{{ route('supplier.store') }}" method="POST" id="createSupplierForm">
                    @csrf
                    <div class="modal-body">

                        {{-- Étape 1 : Identité --}}
                        <div class="az-step-panel active" id="panel-1">
                            <div class="az-section-title"><i class="fas fa-truck"></i> Identité du fournisseur</div>
                            <div class="az-field-group" style="grid-template-columns:1fr;">
                                <div class="az-field">
                                    <label class="az-label">Nom <span class="required">*</span></label>
                                    <input type="text" name="name" class="az-input" required placeholder="Ex : DESTOCK PIÈCES AUTO">
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                <div class="az-field">
                                    <label class="az-label">Adresse</label>
                                    <input type="text" name="address" class="az-input" placeholder="N° et rue">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Adresse de livraison</label>
                                    <input type="text" name="address_delivery" class="az-input" placeholder="Si différente">
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                <div class="az-field">
                                    <label class="az-label">Ville</label>
                                    <input type="text" name="city" class="az-input" placeholder="Paris">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Pays</label>
                                    <input type="text" name="country" class="az-input" value="France">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">SIRET</label>
                                    <input type="text" name="matfiscal" class="az-input" placeholder="14 chiffres">
                                </div>
                            </div>
                        </div>

                        {{-- Étape 2 : Contact & B2B --}}
                        <div class="az-step-panel" id="panel-2">
                            <div class="az-section-title"><i class="fas fa-address-card"></i> Coordonnées</div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;">
                                <div class="az-field">
                                    <label class="az-label">Email</label>
                                    <input type="email" name="email" class="az-input" placeholder="contact@exemple.fr">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Téléphone standard</label>
                                    <input type="text" name="phone1" class="az-input" placeholder="01 00 00 00 00">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Téléphone commercial</label>
                                    <input type="text" name="phone2" class="az-input" placeholder="06 00 00 00 00">
                                </div>
                            </div>

                            <div class="az-section-title" style="margin-top:20px;"><i class="fas fa-shopping-cart"></i> Plateforme B2B</div>
                            <div class="az-b2b-box">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="has_b2b" value="1" id="has_b2b_create">
                                    <label class="form-check-label" for="has_b2b_create">Ce fournisseur a un site B2B (Destock, AZ, OttoGo…)</label>
                                </div>
                                <div class="text-muted" style="font-size:.75rem;margin:4px 0 10px;">S'il est coché, ce fournisseur apparaît dans la liste « Fournisseur » lors de la création d'une commande.</div>
                                <div class="az-field">
                                    <label class="az-label">URL du site B2B (facultatif)</label>
                                    <input type="url" name="b2b_url" class="az-input" placeholder="https://destockpiecesauto.autodata.fr">
                                </div>
                            </div>
                        </div>

                        {{-- Étape 3 : Finances --}}
                        <div class="az-step-panel" id="panel-3">
                            <div class="az-section-title"><i class="fas fa-euro-sign"></i> Paramètres financiers</div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                <div class="az-field">
                                    <label class="az-label">Condition de paiement <span class="required">*</span></label>
                                    <select name="payment_term_id" class="az-input az-select" required>
                                        @foreach($paymentTerms as $term)
                                            <option value="{{ $term->id }}">{{ $term->label }} : {{ $term->days }} jours</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Mode de paiement <span class="required">*</span></label>
                                    <select name="payment_mode_id" class="az-input az-select" required>
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                <div class="az-field">
                                    <label class="az-label">TVA <span class="required">*</span></label>
                                    <select name="tva_group_id" class="az-input az-select" required>
                                        @foreach($tvaGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->rate }} %</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Groupe remise <span class="required">*</span></label>
                                    <select name="discount_group_id" class="az-input az-select" required>
                                        @foreach($discountGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }} : {{ $group->discount_rate }} %</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Plafond (€)</label>
                                    <input type="number" step="0.01" name="plafond" class="az-input" value="0">
                                </div>
                            </div>
                            <div class="az-field-group" style="grid-template-columns:2fr 1fr 1fr;margin-top:14px;">
                                <div class="az-field">
                                    <label class="az-label">IBAN</label>
                                    <input type="text" name="bank_no" class="az-input" placeholder="FR76…">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Risque</label>
                                    <input type="number" name="risque" class="az-input">
                                </div>
                                <div class="az-field">
                                    <label class="az-label">Solde initial (€)</label>
                                    <input type="number" step="0.01" name="solde" class="az-input" value="0" readonly style="background:#f0f4ff;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="az-btn-prev" id="btnPrev" style="display:none;" onclick="stepNav(-1)"><i class="fas fa-arrow-left"></i> Précédent</button>
                            <span class="az-step-counter" id="stepCounter">Étape 1 sur 3</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="az-btn-next" id="btnNext" onclick="stepNav(1)">Suivant <i class="fas fa-arrow-right"></i></button>
                            <button type="submit" class="az-btn-submit" id="btnSubmit" style="display:none;"><i class="fas fa-check"></i> Créer le fournisseur</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- New Modal for All Accounting Entries -->
                        <div class="modal fade" id="allAccountingModal" tabindex="-1" aria-labelledby="allAccountingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="allAccountingModalLabel">Ecritures Comptables Achats</h5>
                                        <button type="button" class="btn btn-secondary btn-round ms-2" onclick="showAllBalance()">
                                            <i class="fas fa-balance-scale me-1"></i> Balance Générale
                                        </button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Balance Summary (Hidden by Default) -->
                                        <div id="allBalanceSummary" class="card mb-3" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Balance Générale des Achats</h6>
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Total Débits</th>
                                                            <th>Total Crédits</th>
                                                            <th>Solde Net</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td id="allDebits">0,00 €</td>
                                                            <td id="allCredits">0,00 €</td>
                                                            <td id="allBalance">0,00 €</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Filter Form -->
                                        <form id="allAccountingFilterForm" class="d-flex flex-wrap gap-2 mb-3">
                                            <select name="type" class="form-select form-select-sm" style="width: 200px;">
                                                <option value="">Type (Tous)</option>
                                                <option value="Factures">Factures</option>
                                                <option value="Avoirs">Avoirs</option>
                                                <option value="Règlements">Règlements</option>
                                            </select>
<input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
<input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
                                            <div style="flex:1 1 240px;min-width:220px">
    <select name="customer_id" id="allCustomerFilter" class="form-select form-select-sm">
        <option value="">Fournisseur (Tous)</option>
        @foreach($suppliers as $s)
            <option value="{{ $s->id }}">{{ $s->name }}{{ $s->code ? ' (' . $s->code . ')' : '' }}</option>
        @endforeach
    </select>
</div>
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fas fa-filter me-1"></i> Filtrer
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAllAccountingFilter()">
                                                <i class="fas fa-undo me-1"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover accounting-table">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Fourn.</th>
                                                        <th>Type</th>
                                                        <th>Num Document / Lettrage</th>
                                                        <th>Date</th>
                                                        <th>Montant TTC</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="allAccountingEntries">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Chargement...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

   <!-- Filtres -->
   <form method="GET" action="{{ route('supplier.index') }}" class="cl-filters">
    <input type="text" name="search" class="form-control f-search" placeholder="🔍 Recherche (nom, code, téléphone, email...)" value="{{ request('search') }}">
    <select name="status" class="form-select f-sel">
        <option value="">Statut (tous)</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Actif</option>
        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>🔴 Bloqué</option>
    </select>
    <select name="city" class="form-select f-sel">
        <option value="">Ville (toutes)</option>
        @foreach($cities as $city)
            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
        @endforeach
    </select>
    <input type="number" step="0.01" name="min_solde" class="form-control f-num" placeholder="Solde min" value="{{ request('min_solde') }}">
    <input type="number" step="0.01" name="max_solde" class="form-control f-num" placeholder="Solde max" value="{{ request('max_solde') }}">
    <button type="submit" name="action" value="filter" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
    <button type="submit" name="action" value="export" formaction="{{ route('suppliers.export') . '?' . http_build_query(request()->query()) }}" formtarget="_blank" class="btn btn-outline-success"><i class="fas fa-file-excel"></i> Excel</button>
    <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary"><i class="fas fa-undo"></i> Réinitialiser</a>
</form>
    @if ($suppliers->count())
        <div class="table-responsive">
            <table class="table cl-table" id="itemsTable">
                <thead>
                    <tr>
                        <th>Fournisseur</th>
                        <th>Adresse</th>
                        <th>Contact</th>
                        <th class="t-center">B2B</th>
                        <th class="t-right">Solde</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        @php
                            $solde    = (float) ($supplier->solde ?? 0);
                            $soldeCls = abs($solde) > 0.004 ? 'is-open' : 'is-zero';
                            $soldeLbl = abs($solde) > 0.004 ? 'Non soldé' : 'Soldé';
                            $words    = preg_split('/\s+/', trim((string) $supplier->name));
                            $initials = mb_strtoupper(mb_substr($words[0] ?? '?', 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
                            $b2bUrl   = preg_match('#^https?://#i', (string) $supplier->b2b_url) ? $supplier->b2b_url : null;
                        @endphp
                        <tr class="{{ $supplier->blocked ? 'is-blocked' : '' }}">
                            {{-- Fournisseur --}}
                            <td>
                                <div class="cl-who">
                                    <span class="cl-avatar t-supplier">{{ $initials }}</span>
                                    <div class="cl-who-txt">
                                        <div class="cl-name" title="{{ $supplier->name }}">{{ $supplier->name }}</div>
                                        <div class="cl-meta">
                                            <span class="cl-code">{{ $supplier->code }}</span>
                                            @if ($supplier->blocked)
                                                <span class="cl-pill st-blocked"><i class="fas fa-ban"></i> Bloqué</span>
                                            @else
                                                <span class="cl-pill t-professionnel"><i class="fas fa-check"></i> Actif</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Adresse --}}
                            <td>
                                @if ($supplier->address || $supplier->city || $supplier->address_delivery)
                                    <div class="cl-line" title="{{ $supplier->address }}">{{ $supplier->address ?: '—' }}</div>
                                    <div class="cl-line cl-sub"><i class="fas fa-map-marker-alt"></i> {{ $supplier->city ?: '—' }}</div>
                                    @if ($supplier->address_delivery)
                                        <div class="cl-line cl-sub" title="Adresse de livraison : {{ $supplier->address_delivery }}"><i class="fas fa-truck"></i> {{ $supplier->address_delivery }}</div>
                                    @endif
                                @else
                                    <span class="cl-empty">—</span>
                                @endif
                            </td>

                            {{-- Contact --}}
                            <td>
                                <div class="cl-line"><i class="fas fa-phone"></i> @if ($supplier->phone1) {{ $supplier->phone1 }}<span class="cl-tag">Standard</span> @else <span class="cl-empty">—</span> @endif</div>
                                <div class="cl-line"><i class="fas fa-phone"></i> @if ($supplier->phone2) {{ $supplier->phone2 }}<span class="cl-tag">Commercial</span> @else <span class="cl-empty">—</span> @endif</div>
                                <div class="cl-line cl-sub" title="{{ $supplier->email }}"><i class="fas fa-envelope"></i> @if ($supplier->email) {{ $supplier->email }} @else <span class="cl-empty">—</span> @endif</div>
                            </td>

                            {{-- Plateforme B2B --}}
                            <td class="t-center">
                                @if ($supplier->has_b2b)
                                    <span class="cl-pill st-b2b"><i class="fas fa-shopping-cart"></i> B2B</span>
                                    @if ($b2bUrl)
                                        <div><a class="cl-b2b-link" href="{{ $b2bUrl }}" target="_blank" rel="noopener" title="Ouvrir le site B2B du fournisseur"><i class="fas fa-external-link-alt"></i><span class="cl-b2b-txt">Ouvrir le site</span></a></div>
                                    @endif
                                @else
                                    <span class="cl-pill st-no">Non</span>
                                @endif
                            </td>

                            {{-- Solde (clic = écritures comptables) --}}
                            <td class="t-right">
                                <button type="button" class="solde-btn cl-solde {{ $soldeCls }}"
                                        data-bs-toggle="modal" data-bs-target="#accountingModal{{ $supplier->id }}"
                                        data-customer-id="{{ $supplier->id }}" title="Cliquer pour voir les écritures comptables de ce fournisseur">
                                    {{ number_format($solde, 2, ',', ' ') }} € <i class="fas fa-list-alt"></i>
                                </button>
                                <div class="cl-solde-lbl {{ $soldeCls }}">{{ $soldeLbl }}</div>
                            </td>

                            {{-- Actions + fenêtres du fournisseur --}}
                            <td>
                                <div class="act-btns">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $supplier->id }}" title="Consulter / Éditer">
                                        <i class="fas fa-edit"></i> Consulter
                                    </button>
                                </div>
                                <div class="act-btns mt-2">
                                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce fournisseur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger w-100" title="Supprimer"><i class="fas fa-trash-alt"></i> Supprimer</button>
                                    </form>
                                </div>

 <!-- Accounting Entries Modal -->
                   <!-- Accounting Entries Modal -->
<div class="modal fade accounting-modal" id="accountingModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="accountingModalLabel{{ $supplier->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountingModalLabel{{ $supplier->id }}">Ecritures comptables Fournisseur : {{ $supplier->name }}</h5>
                <button type="button" class="btn btn-secondary btn-round ms-2 dropdown-toggle" onclick="showBalance({{ $supplier->id }})">
                    <i class="fas fa-balance-scale me-1"></i> Balance
                </button>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Balance Summary (Hidden by Default) -->
                <div id="balanceSummary{{ $supplier->id }}" class="card mb-3" style="display: none;">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Balance Comptable</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Total Débits</th>
                                    <th>Total Crédits</th>
                                    <th>Solde Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="debits{{ $supplier->id }}">0,00 €</td>
                                    <td id="credits{{ $supplier->id }}">0,00 €</td>
                                    <td id="balance{{ $supplier->id }}">0,00 €</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Filter Form -->
                <form id="accountingFilterForm{{ $supplier->id }}" class="d-flex flex-wrap gap-2 mb-3">
                    <select name="type" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Type (Tous)</option>
                        <option value="Factures">Factures</option>
                        <option value="Avoirs">Avoirs</option>
                        <option value="Règlements">Règlements</option>
                    </select>
                    <input type="date" name="start_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date début">
                    <input type="date" name="end_date" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin">
                    <button type="submit" class="btn btn-outline-primary btn-sm px-3">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAccountingFilter({{ $supplier->id }})">
                        <i class="fas fa-undo me-1"></i> Réinitialiser
                    </button>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover accounting-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Type</th>
                                <th>Num Document / Lettrage</th>
                                <th>Date</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody id="accountingEntries{{ $supplier->id }}">
                            <tr>
                                <td colspan="5" class="text-center">Chargement...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

                                {{-- Modal Consulter / Éditer : onglets + verrou --}}
                                <div class="modal fade az-modal" id="editItemModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="az-edit-header">
                                                <div>
                                                    <div class="az-edit-badge">{{ $supplier->code }}</div>
                                                    <h5 class="az-edit-title mt-1">{{ $supplier->name }}</h5>
                                                    <p class="az-edit-subtitle">Fournisseur · {{ $supplier->city ?: '—' }}</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="az-lock-btn" id="lockBtn{{ $supplier->id }}" onclick="toggleEdit({{ $supplier->id }})">
                                                        <i class="fas fa-lock"></i> Modifier
                                                    </button>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                            </div>

                                            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" id="editForm{{ $supplier->id }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="az-tabs">
                                                    <button type="button" class="az-tab active" data-tab="info-{{ $supplier->id }}" onclick="switchTab(this, {{ $supplier->id }})">Informations</button>
                                                    <button type="button" class="az-tab" data-tab="b2b-{{ $supplier->id }}" onclick="switchTab(this, {{ $supplier->id }})">B2B</button>
                                                    <button type="button" class="az-tab" data-tab="finance-{{ $supplier->id }}" onclick="switchTab(this, {{ $supplier->id }})">Finances</button>
                                                    <button type="button" class="az-tab" data-tab="statut-{{ $supplier->id }}" onclick="switchTab(this, {{ $supplier->id }})">Statut</button>
                                                </div>

                                                {{-- Informations --}}
                                                <div class="az-tab-panel active" id="tab-info-{{ $supplier->id }}">
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                                        <div class="az-field"><label class="az-label">Nom</label>
                                                            <input type="text" name="name" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->name }}" required disabled></div>
                                                        <div class="az-field"><label class="az-label">Email</label>
                                                            <input type="email" name="email" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->email }}" disabled></div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                                        <div class="az-field"><label class="az-label">Téléphone standard</label>
                                                            <input type="text" name="phone1" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->phone1 }}" disabled></div>
                                                        <div class="az-field"><label class="az-label">Téléphone commercial</label>
                                                            <input type="text" name="phone2" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->phone2 }}" disabled></div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                                        <div class="az-field"><label class="az-label">Adresse</label>
                                                            <input type="text" name="address" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->address }}" disabled></div>
                                                        <div class="az-field"><label class="az-label">Adresse de livraison</label>
                                                            <input type="text" name="address_delivery" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->address_delivery }}" disabled></div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr 1fr;margin-top:14px;">
                                                        <div class="az-field"><label class="az-label">Ville</label>
                                                            <input type="text" name="city" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->city }}" disabled></div>
                                                        <div class="az-field"><label class="az-label">Pays</label>
                                                            <input type="text" name="country" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->country }}" disabled></div>
                                                        <div class="az-field"><label class="az-label">SIRET</label>
                                                            <input type="text" name="matfiscal" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->matfiscal }}" disabled></div>
                                                        <div class="az-field"><label class="az-label">IBAN</label>
                                                            <input type="text" name="bank_no" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->bank_no }}" disabled></div>
                                                    </div>
                                                </div>

                                                {{-- B2B --}}
                                                <div class="az-tab-panel" id="tab-b2b-{{ $supplier->id }}">
                                                    <div class="az-b2b-box">
                                                        <input type="hidden" name="has_b2b" value="0">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input ef-{{ $supplier->id }}" type="checkbox" name="has_b2b" value="1" id="has_b2b_edit{{ $supplier->id }}" {{ $supplier->has_b2b ? 'checked' : '' }} disabled>
                                                            <label class="form-check-label" for="has_b2b_edit{{ $supplier->id }}">Ce fournisseur a un site B2B</label>
                                                        </div>
                                                        <div class="text-muted" style="font-size:.75rem;margin:4px 0 10px;">S'il est coché, il apparaît dans la liste « Fournisseur » lors de la création d'une commande.</div>
                                                        <div class="az-field">
                                                            <label class="az-label">URL du site B2B</label>
                                                            <input type="url" name="b2b_url" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->b2b_url }}" placeholder="https://…" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Finances --}}
                                                <div class="az-tab-panel" id="tab-finance-{{ $supplier->id }}">
                                                    <div class="az-solde-card">
                                                        <div>
                                                            <div class="az-solde-label">Solde actuel</div>
                                                            <div class="az-solde-amount">{{ number_format($supplier->solde ?? 0, 2, ',', ' ') }} €</div>
                                                        </div>
                                                        <i class="fas fa-wallet" style="font-size:2rem;opacity:0.3;"></i>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                                        <div class="az-field"><label class="az-label">Condition de paiement</label>
                                                            <select name="payment_term_id" class="az-input az-select ef-{{ $supplier->id }}" disabled>
                                                                @foreach($paymentTerms as $term)
                                                                    <option value="{{ $term->id }}" {{ $supplier->payment_term_id == $term->id ? 'selected' : '' }}>{{ $term->label }} : {{ $term->days }} jours</option>
                                                                @endforeach
                                                            </select></div>
                                                        <div class="az-field"><label class="az-label">Mode de paiement</label>
                                                            <select name="payment_mode_id" class="az-input az-select ef-{{ $supplier->id }}" disabled>
                                                                @foreach($paymentModes as $mode)
                                                                    <option value="{{ $mode->id }}" {{ $supplier->payment_mode_id == $mode->id ? 'selected' : '' }}>{{ $mode->name }}</option>
                                                                @endforeach
                                                            </select></div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                                        <div class="az-field"><label class="az-label">TVA</label>
                                                            <select name="tva_group_id" class="az-input az-select ef-{{ $supplier->id }}" disabled>
                                                                @foreach($tvaGroups as $group)
                                                                    <option value="{{ $group->id }}" {{ $supplier->tva_group_id == $group->id ? 'selected' : '' }}>{{ $group->name }} : {{ $group->rate }} %</option>
                                                                @endforeach
                                                            </select></div>
                                                        <div class="az-field"><label class="az-label">Groupe remise</label>
                                                            <select name="discount_group_id" class="az-input az-select ef-{{ $supplier->id }}" disabled>
                                                                @foreach($discountGroups as $group)
                                                                    <option value="{{ $group->id }}" {{ $supplier->discount_group_id == $group->id ? 'selected' : '' }}>{{ $group->name }} : {{ $group->discount_rate }} %</option>
                                                                @endforeach
                                                            </select></div>
                                                        <div class="az-field"><label class="az-label">Plafond (€)</label>
                                                            <input type="number" step="0.01" name="plafond" class="az-input ef-{{ $supplier->id }}" value="{{ old('plafond', $supplier->plafond ?? 0) }}" disabled></div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr;margin-top:14px;">
                                                        <div class="az-field"><label class="az-label">Risque</label>
                                                            <input type="number" name="risque" class="az-input ef-{{ $supplier->id }}" value="{{ $supplier->risque }}" disabled style="max-width:200px;"></div>
                                                    </div>
                                                </div>

                                                {{-- Statut --}}
                                                <div class="az-tab-panel" id="tab-statut-{{ $supplier->id }}">
                                                    <input type="hidden" name="blocked" value="0">
                                                    <div style="background:white;border-radius:12px;padding:20px;border:1.5px solid var(--c-border);">
                                                        <div style="font-weight:700;color:var(--c-navy);margin-bottom:10px;">Statut du fournisseur</div>
                                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                                            <span class="az-status-pill {{ $supplier->blocked ? 'blocked' : 'active' }}" id="statusPill{{ $supplier->id }}" onclick="toggleSupStatus({{ $supplier->id }})">
                                                                {{ $supplier->blocked ? '🚫 Fournisseur bloqué' : '✅ Fournisseur actif' }}
                                                            </span>
                                                            <input type="checkbox" name="blocked" value="1" id="blockedSwitch{{ $supplier->id }}" class="ef-{{ $supplier->id }}" {{ $supplier->blocked ? 'checked' : '' }} disabled style="display:none;">
                                                            <small style="color:var(--c-sub);font-size:.75rem;">Cliquez sur la pastille (en mode modification) pour changer le statut</small>
                                                        </div>
                                                        <div style="margin-top:12px;padding:10px;background:var(--c-bg);border-radius:8px;font-size:.75rem;color:var(--c-sub);line-height:1.6;">
                                                            ⚠️ Le blocage concerne uniquement l'expédition des ventes. La facturation reste possible même si le compte est bloqué.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer" style="justify-content:flex-end;background:white;border-top:1.5px solid var(--c-border);padding:14px 24px;">
                                                    <button type="button" class="az-btn-prev" data-bs-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="az-save-btn" id="saveBtn{{ $supplier->id }}"><i class="fas fa-check"></i> Mettre à jour</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $suppliers->appends(request()->query())->links() }}
        </div>
    @else
        <div class="cl-empty-state">
            <i class="fas fa-truck d-block"></i>
            <h5>Aucun fournisseur trouvé</h5>
            <p>Essayez d'ajuster vos critères de recherche.</p>
            <a href="{{ route('supplier.index') }}" class="btn btn-primary"><i class="fas fa-undo"></i> Réinitialiser les filtres</a>
        </div>
    @endif
</div>


</div>

         
            
           
           
          </div>
        </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <!-- <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul> -->
            </nav>
            <div class="copyright">
            © AZ NEGOCE. All Rights Reserved.
              <!-- <a href="http://www.themekita.com">By AZ NEGOCE</a> -->
            </div>
            <div>
               by
              <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.
            </div>
          </div>
        </footer>
      </div>

      
    </div>
   <!-- Core JS Files -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- Chart JS -->
<script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

<!-- Chart Circle -->
<script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- jQuery Vector Maps -->
<script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

<!-- Kaiadmin JS -->
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    











<script>
document.addEventListener("DOMContentLoaded", function () {
    // Store entries for each customer to avoid refetching
    const accountingEntriesCache = {};

    // Accounting Entries Handler
    document.querySelectorAll('.solde-btn').forEach(button => {
        const modal = document.getElementById(`accountingModal${button.dataset.customerId}`);
        const filterForm = document.getElementById(`accountingFilterForm${button.dataset.customerId}`);

        modal.addEventListener('show.bs.modal', function () {
            const customerId = button.dataset.customerId;
            const tbody = document.getElementById(`accountingEntries${customerId}`);

            // If entries are already cached, apply filters and render
            if (accountingEntriesCache[customerId]) {
                applyFilters(customerId);
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Chargement...</td></tr>';
            fetch(`/suppliers/${customerId}/accounting-entries`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    accountingEntriesCache[customerId] = data.entries || [];
                    applyFilters(customerId);
                })
                .catch(error => {
                    console.error(`Error fetching accounting entries for customer ID ${customerId}:`, error);
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables. Veuillez réessayer plus tard.</td></tr>`;
                });
        });

        // Handle filter form submission
        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                applyFilters(button.dataset.customerId);
            });
        }
    });

    // Reset filter function
    window.resetAccountingFilter = function (customerId) {
        const filterForm = document.getElementById(`accountingFilterForm${customerId}`);
        const balanceSummary = document.getElementById(`balanceSummary${customerId}`);
        if (filterForm) {
            filterForm.reset();
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyFilters(customerId);
        }
    };

    // Show balance summary
    window.showBalance = function (customerId) {
        const balanceSummary = document.getElementById(`balanceSummary${customerId}`);
        balanceSummary.style.display = 'block'; // Show balance summary
        applyFilters(customerId); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyFilters(customerId) {
        const tbody = document.getElementById(`accountingEntries${customerId}`);
        const filterForm = document.getElementById(`accountingFilterForm${customerId}`);
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;

        // Get cached entries
        let entries = accountingEntriesCache[customerId] || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
        if (startDate || endDate) {
            entries = entries.filter(entry => {
                if (!entry.date || entry.date === '-') return false;
                const entryDateParts = entry.date.split('/');
                const entryDate = new Date(`${entryDateParts[2]}-${entryDateParts[1]}-${entryDateParts[0]}`);
                if (startDate && entryDate < startDate) return false;
                if (endDate && entryDate > endDate) return false;
                return true;
            });
        }

        // Calculate balance
        let debits = 0;
        let credits = 0;
        entries.forEach(entry => {
            if (entry.type === 'Facture') {
                debits += parseFloat(entry.amount) || 0;
            } else {
                credits += parseFloat(entry.amount) || 0;
            }
        });
        const balance = debits + credits;

        // Update balance summary
        const debitsElement = document.getElementById(`debits${customerId}`);
        const creditsElement = document.getElementById(`credits${customerId}`);
        const balanceElement = document.getElementById(`balance${customerId}`);
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});





















// balance total

document.addEventListener("DOMContentLoaded", function () {
    // Cache for all accounting entries to avoid refetching
    let allAccountingEntriesCache = [];

    // All Accounting Entries Handler
    const allAccountingModal = document.getElementById('allAccountingModal');
    const allFilterForm = document.getElementById('allAccountingFilterForm');

    if (allAccountingModal) {
        allAccountingModal.addEventListener('show.bs.modal', function () {
            const tbody = document.getElementById('allAccountingEntries');

            // If entries are cached, apply filters and render
            if (allAccountingEntriesCache.length > 0) {
                applyAllFilters();
                return;
            }

            // Fetch entries if not cached
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Chargement...</td></tr>';
            fetch("{{ route('allsupplier.accounting-entries') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    allAccountingEntriesCache = data.entries || [];
                    applyAllFilters();
                })
                .catch(error => {
                    console.error('Error fetching all accounting entries:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erreur: Impossible de charger les écritures comptables.</td></tr>`;
                });
        });
    }

    // Handle filter form submission
    if (allFilterForm) {
        allFilterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // Liste fournisseurs recherchable dans le modal des écritures
    $('#allCustomerFilter').select2({
        width: '100%',
        dropdownParent: $('#allAccountingModal'),
        placeholder: 'Fournisseur (Tous)',
        allowClear: true,
        language: { noResults: function () { return 'Aucun fournisseur trouvé'; } }
    });
    $('#allCustomerFilter').on('change', function () { applyAllFilters(); });

    // Reset filter function
    window.resetAllAccountingFilter = function () {
        const filterForm = document.getElementById('allAccountingFilterForm');
        const balanceSummary = document.getElementById('allBalanceSummary');
        if (filterForm) {
            filterForm.reset();
            $('#allCustomerFilter').val('').trigger('change.select2');
            balanceSummary.style.display = 'none'; // Hide balance when resetting
            applyAllFilters();
        }
    };

    // Show balance summary
    window.showAllBalance = function () {
        const balanceSummary = document.getElementById('allBalanceSummary');
        balanceSummary.style.display = 'block'; // Show balance summary
        applyAllFilters(); // Reapply filters to ensure balance is updated
    };

    // Apply client-side filters and render table
    function applyAllFilters() {
        const tbody = document.getElementById('allAccountingEntries');
        const filterForm = document.getElementById('allAccountingFilterForm');
        const formData = new FormData(filterForm);
        const typeFilter = formData.get('type') || '';
        const startDate = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
        const endDate = formData.get('end_date') ? new Date(formData.get('end_date')) : null;
        const customerIdFilter = formData.get('customer_id') || '';

        // Get cached entries
        let entries = allAccountingEntriesCache || [];

        // Apply type filter
        if (typeFilter) {
            entries = entries.filter(entry => {
                if (typeFilter === 'Factures') return entry.type === 'Facture';
                if (typeFilter === 'Avoirs') return entry.type === 'Avoir';
                if (typeFilter === 'Règlements') return entry.type !== 'Facture' && entry.type !== 'Avoir';
                return true;
            });
        }

        // Apply date filter
        if (startDate || endDate) {
            entries = entries.filter(entry => {
                if (!entry.date || entry.date === '-') return false;
                const entryDateParts = entry.date.split('/');
                const entryDate = new Date(`${entryDateParts[2]}-${entryDateParts[1]}-${entryDateParts[0]}`);
                if (startDate && entryDate < startDate) return false;
                if (endDate && entryDate > endDate) return false;
                return true;
            });
        }

        // Apply customer filter
        if (customerIdFilter) {
            entries = entries.filter(entry => String(entry.customer_id !== undefined ? entry.customer_id : entry.supplier_id) === String(customerIdFilter));
        }

        // Calculate balance
        let debits = 0;
        let credits = 0;
        entries.forEach(entry => {
            if (entry.type === 'Facture') {
                debits += parseFloat(entry.amount) || 0;
            } else {
                credits += parseFloat(entry.amount) || 0;
            }
        });
        const balance = debits - credits;

        // Update balance summary
        const debitsElement = document.getElementById('allDebits');
        const creditsElement = document.getElementById('allCredits');
        const balanceElement = document.getElementById('allBalance');
        if (debitsElement && creditsElement && balanceElement) {
            debitsElement.textContent = debits.toFixed(2).replace('.', ',') + ' €';
            creditsElement.textContent = credits.toFixed(2).replace('.', ',') + ' €';
            balanceElement.textContent = balance.toFixed(2).replace('.', ',') + ' €';
            balanceElement.className = balance >= 0 ? 'text-success' : 'text-danger';
        }

        // Render filtered entries
        tbody.innerHTML = '';
        if (entries.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune écriture comptable trouvée.</td></tr>';
            return;
        }

        entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${entry.customer_name || '-'}</td>
                <td>${entry.type || '-'}</td>
                <td>${entry.numdoc || entry.reference || '-'}</td>
                <td>${entry.date || '-'}</td>
                <td>${(entry.amount !== undefined && entry.amount !== null) ? Number(entry.amount).toFixed(2).replace('.', ',') : '-'} €</td>
                <td>${entry.status || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
});
</script>

<script>
// ════════════════════════════════════════════════════════
// STEPPER — Création fournisseur (3 étapes)
// ════════════════════════════════════════════════════════
var currentStep = 1;
var totalSteps  = 3;

function stepNav(dir) {
    var next = currentStep + dir;
    if (next < 1 || next > totalSteps) return;

    if (dir > 0) {
        var panel = document.getElementById('panel-' + currentStep);
        var valid = true, first = null;
        panel.querySelectorAll('[required]').forEach(function (el) {
            if (!String(el.value).trim()) {
                el.classList.add('is-invalid');
                el.style.borderColor = 'var(--c-red)';
                valid = false; if (!first) first = el;
            } else {
                el.style.borderColor = '';
                el.classList.remove('is-invalid');
            }
        });
        if (!valid) { if (first) first.focus(); return; }
    }

    document.getElementById('panel-' + currentStep).classList.remove('active');
    currentStep = next;
    document.getElementById('panel-' + currentStep).classList.add('active');

    for (var i = 1; i <= totalSteps; i++) {
        var step = document.querySelector('#createItemModal .az-stepper [data-step="' + i + '"]');
        if (!step) continue;
        step.classList.remove('active', 'done');
        if (i < currentStep)        step.classList.add('done');
        else if (i === currentStep) step.classList.add('active');
        step.querySelector('.az-step-circle').innerHTML = i < currentStep ? '<i class="fas fa-check" style="font-size:0.7rem;"></i>' : i;
    }
    document.getElementById('line1').classList.toggle('done', currentStep > 1);
    document.getElementById('line2').classList.toggle('done', currentStep > 2);

    document.getElementById('btnPrev').style.display   = currentStep > 1 ? 'flex' : 'none';
    document.getElementById('btnNext').style.display   = currentStep === totalSteps ? 'none' : 'flex';
    document.getElementById('btnSubmit').style.display = currentStep === totalSteps ? 'flex' : 'none';
    document.getElementById('stepCounter').textContent = 'Étape ' + currentStep + ' sur ' + totalSteps;
}

// Entrée dans un champ des étapes 1-2 = "Suivant" (et non pas envoi prématuré du formulaire)
document.getElementById('createSupplierForm').addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
        if (currentStep < totalSteps) { e.preventDefault(); stepNav(1); }
    }
});

document.getElementById('createItemModal').addEventListener('shown.bs.modal', function () {
    var n = this.querySelector('input[name="name"]'); if (n) n.focus();
});
document.getElementById('createItemModal').addEventListener('hidden.bs.modal', function () {
    currentStep = 1;
    for (var i = 1; i <= totalSteps; i++) {
        document.getElementById('panel-' + i).classList.toggle('active', i === 1);
        var step = document.querySelector('#createItemModal .az-stepper [data-step="' + i + '"]');
        if (!step) continue;
        step.classList.remove('active', 'done');
        if (i === 1) step.classList.add('active');
        step.querySelector('.az-step-circle').textContent = i;
    }
    document.getElementById('line1').classList.remove('done');
    document.getElementById('line2').classList.remove('done');
    document.getElementById('btnPrev').style.display   = 'none';
    document.getElementById('btnNext').style.display   = 'flex';
    document.getElementById('btnSubmit').style.display = 'none';
    document.getElementById('stepCounter').textContent = 'Étape 1 sur 3';
    var f = document.getElementById('createSupplierForm');
    f.reset();
    f.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); el.style.borderColor = ''; });
});

// ════════════════════════════════════════════════════════
// CONSULTER / ÉDITER — onglets + verrou
// ════════════════════════════════════════════════════════
var editState = {};

function switchTab(btn, id) {
    var modal = document.getElementById('editItemModal' + id);
    modal.querySelectorAll('.az-tab').forEach(function (t) { t.classList.remove('active'); });
    modal.querySelectorAll('.az-tab-panel').forEach(function (p) { p.classList.remove('active'); });
    btn.classList.add('active');
    var panel = document.getElementById('tab-' + btn.getAttribute('data-tab'));
    if (panel) panel.classList.add('active');
}

function syncSupStatus(id) {
    var cb = document.getElementById('blockedSwitch' + id), pill = document.getElementById('statusPill' + id);
    if (!cb || !pill) return;
    pill.className = 'az-status-pill ' + (cb.checked ? 'blocked' : 'active');
    pill.textContent = cb.checked ? '🚫 Fournisseur bloqué' : '✅ Fournisseur actif';
}

function toggleSupStatus(id) {
    var cb = document.getElementById('blockedSwitch' + id);
    if (!cb || cb.disabled) return;          // verrouillé : il faut d'abord cliquer sur « Modifier »
    cb.checked = !cb.checked;
    syncSupStatus(id);
}

function toggleEdit(id) {
    var fields  = document.querySelectorAll('.ef-' + id);
    var btn     = document.getElementById('lockBtn' + id);
    var saveBtn = document.getElementById('saveBtn' + id);

    if (!editState[id]) {                                   // déverrouiller
        fields.forEach(function (el) { el.removeAttribute('disabled'); });
        btn.innerHTML = '<i class="fas fa-times"></i> Annuler';
        btn.classList.add('editing');
        if (saveBtn) saveBtn.style.display = 'flex';
        editState[id] = true;
    } else {                                                // annuler : on restaure les valeurs d'origine
        document.getElementById('editForm' + id).reset();
        fields.forEach(function (el) { el.setAttribute('disabled', 'disabled'); });
        syncSupStatus(id);
        btn.innerHTML = '<i class="fas fa-lock"></i> Modifier';
        btn.classList.remove('editing');
        if (saveBtn) saveBtn.style.display = 'none';
        editState[id] = false;
    }
}

// Une fenêtre fermée en cours de modification repasse en lecture seule
document.addEventListener('hidden.bs.modal', function (e) {
    var m = e.target;
    if (m && m.id && m.id.indexOf('editItemModal') === 0) {
        var id = m.id.replace('editItemModal', '');
        if (editState[id]) toggleEdit(id);
    }
});
</script>






  </body>
</html>