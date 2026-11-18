<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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

<title>AZ ERP — Statistiques de vente</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style>
.st{--ink:#1e2d4a;--mut:#64748b;--line:#e8edf5;--blue:#2563eb}
.st-head{background:linear-gradient(135deg,#1E2D4A 0%,#2D4A8A 100%);color:#fff;border-radius:14px;padding:16px 22px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;box-shadow:0 8px 28px rgba(30,45,74,.16)}
.st-head h3{margin:0;font-weight:800;font-size:1.25rem}
.st-head small{opacity:.7;font-size:.78rem}
.st-filters{background:#fff;border:1px solid var(--line);border-radius:12px;padding:.6rem .7rem;margin-bottom:14px;box-shadow:0 1px 3px rgba(30,45,74,.05)}
.st-row{display:flex;flex-wrap:wrap;gap:.45rem;align-items:center}
.st-row+.st-row{margin-top:.5rem;padding-top:.5rem;border-top:1px dashed var(--line)}
.st-chips{display:flex;gap:.25rem;flex-wrap:wrap;flex:1 1 auto}
.st-chips button{border:1px solid #dbe3f0;background:#f8fafc;color:#475569;border-radius:20px;font-size:.72rem;font-weight:600;padding:3px 11px;cursor:pointer}
.st-chips button:hover,.st-chips button.on{background:#e8f0ff;border-color:#93c5fd;color:#1d4ed8}
.st-dates{display:flex;align-items:center;gap:.35rem;color:#94a3b8}
.st-dates input{width:150px}
.st-filters .form-control,.st-filters .btn{height:34px;font-size:.82rem;border-radius:8px}
.st-filters .btn{display:inline-flex;align-items:center;gap:.4rem;padding:0 .85rem;font-weight:600;margin:0}
.st-f{flex:1 1 150px;min-width:140px}.st-f2{flex:1.3 1 170px}
.st-btns{display:flex;gap:.35rem}
.st-filters .select2-container{width:100%!important}
.st-filters .select2-container--default .select2-selection--single{height:34px;border-radius:8px;border:1px solid #ced4da;display:flex;align-items:center}
.st-filters .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:32px;font-size:.82rem}
.st-filters .select2-container--default .select2-selection--single .select2-selection__arrow{height:32px}
.st-kpis{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px;margin-bottom:12px}
.st-kpi{background:#fff;border:1px solid var(--line);border-left:4px solid var(--c);border-radius:12px;padding:10px 12px;display:flex;flex-direction:column;gap:2px;box-shadow:0 1px 3px rgba(30,45,74,.05)}
.st-kpi>span:first-child{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--mut)}
.st-kpi b{font-size:1.3rem;font-weight:800;color:var(--ink);line-height:1.2}
.st-kpi em{font-style:normal;font-size:.7rem;color:var(--mut)}
.k-blue{--c:#2563eb}.k-green{--c:#16a34a}.k-violet{--c:#7c3aed}.k-orange{--c:#f59e0b}.k-teal{--c:#0d9488}.k-red{--c:#dc2626}
.st-d{display:inline-block;font-size:.68rem;font-weight:700;border-radius:20px;padding:0 7px;align-self:flex-start}
.st-dg{background:#dcfce7;color:#166534}.st-db{background:#fee2e2;color:#991b1b}.st-d0{background:#f1f5f9;color:#64748b}
.st-insights{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
.st-ins{flex:1 1 300px;background:#fff;border:1px solid var(--line);border-left:4px solid #94a3b8;border-radius:10px;padding:7px 12px;font-size:.78rem;color:#334155}
.i-good{border-left-color:#16a34a;background:#f4fbf6}.i-bad{border-left-color:#dc2626;background:#fef6f6}.i-warn{border-left-color:#f59e0b;background:#fffaf0}.i-info{border-left-color:#3b82f6}
.st-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:10px;margin-bottom:12px}
.g-4{grid-column:span 4}.g-6{grid-column:span 6}.g-8{grid-column:span 8}
.st-card{background:#fff;border:1px solid var(--line);border-radius:12px;padding:12px 14px;box-shadow:0 1px 3px rgba(30,45,74,.05)}
.st-ct{font-size:.8rem;font-weight:700;color:var(--ink);margin-bottom:8px}
.st-ct small{font-weight:500;color:var(--mut);margin-left:4px}
.st-cv{position:relative;height:270px}.st-cv-s{height:230px}
.st-tabs-card{padding:0;overflow:hidden}
.st-tabs{display:flex;flex-wrap:wrap;gap:2px;padding:8px 10px 0;border-bottom:1px solid var(--line);background:#f8fafc;list-style:none;margin:0}
.st-tabs button{border:0;background:transparent;padding:8px 14px;font-size:.8rem;font-weight:600;color:var(--mut);border-radius:8px 8px 0 0;border-bottom:2px solid transparent;cursor:pointer}
.st-tabs button:hover{color:var(--blue)}
.st-tabs button.active{color:var(--blue);background:#fff;border-bottom-color:var(--blue)}
.st-badge-r{background:#dc2626;color:#fff;border-radius:10px;font-size:.62rem;padding:1px 6px;margin-left:3px}
.tab-content{padding:12px 14px}
.st-tools{display:flex;gap:10px;align-items:center;margin-bottom:8px;flex-wrap:wrap}
.st-tools .st-q{max-width:260px;height:32px;font-size:.8rem;border-radius:8px}
.st-tools small{color:var(--mut)}
.st-table{width:100%;border-collapse:collapse;font-size:.78rem}
.st-table th{position:sticky;top:0;background:#f1f5f9;color:#475569;font-size:.66rem;text-transform:uppercase;letter-spacing:.04em;padding:6px 8px;text-align:left;white-space:nowrap;cursor:pointer;user-select:none;border-bottom:1px solid #dbe3f0}
.st-table th.num,.st-table td.num{text-align:right;font-variant-numeric:tabular-nums;white-space:nowrap}
.st-table th.asc::after{content:' ▲';color:var(--blue)}.st-table th.desc::after{content:' ▼';color:var(--blue)}
.st-table td{padding:5px 8px;border-bottom:1px solid #f0f3f8;vertical-align:middle}
.st-table tbody tr:hover{background:#f8fbff}
.st-sub{font-size:.68rem;color:var(--mut);max-width:340px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.st-bar{background:linear-gradient(90deg,rgba(37,99,235,.14) var(--w),transparent 0)}
.st-bar-r{background:linear-gradient(90deg,rgba(220,38,38,.13) var(--w),transparent 0)}
.st-neg{color:#dc2626;font-weight:700}
.st-compact td,.st-compact th{padding:4px 6px}
.abc{display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:6px;font-size:.7rem;font-weight:800}
.abc-A{background:#c6efce;color:#166534}.abc-B{background:#ffeb9c;color:#8a5a00}.abc-C{background:#e7e6e6;color:#475569}
.st-abc{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.st-abc-c{display:flex;gap:12px;align-items:center;border-radius:10px;padding:10px 14px;font-size:.78rem;border:1px solid var(--line)}
.st-abc-c .abc{width:34px;height:34px;font-size:1rem;flex:0 0 34px}
.abc-bg-A{background:#f4fbf6}.abc-bg-B{background:#fffaf0}.abc-bg-C{background:#f8fafc}
.st-ok{color:#166534;background:#f0fdf4;border-radius:8px;padding:10px 12px;font-size:.8rem}
.st-loading{padding:40px;text-align:center;color:#94a3b8;font-size:.85rem}
.st-err{padding:30px;text-align:center;color:#991b1b;font-size:.85rem}
.st-note{font-size:.7rem;color:var(--mut);margin:8px 2px 0}
.st-empty{text-align:center;padding:60px 20px;color:#94a3b8;background:#fff;border:1px dashed #cbd5e1;border-radius:14px}
.st-empty i{font-size:2.5rem;margin-bottom:10px}
@media (max-width:1199px){.st-kpis{grid-template-columns:repeat(3,minmax(0,1fr))}.g-4,.g-8,.g-6{grid-column:span 12}.st-abc{grid-template-columns:1fr}}
@media (max-width:575px){.st-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media print{
  .sidebar,.main-header,.st-noprint,.footer,.st-tabs{display:none!important}
  .main-panel{width:100%!important;margin:0!important;float:none!important}
  .container{max-width:100%!important;padding:0!important}
  .st-head{background:#1E2D4A!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
  .tab-content>.tab-pane{display:block!important;opacity:1!important;margin-bottom:14px}
  .tab-content>.tab-pane::before{content:attr(data-title);display:block;font-weight:800;font-size:.95rem;margin:10px 0 4px;color:#1e2d4a}
  .st-card,.st-kpi{break-inside:avoid;box-shadow:none}
  .st-kpi,.st-bar,.st-bar-r,.abc,.st-ins,.st-abc-c{-webkit-print-color-adjust:exact;print-color-adjust:exact}
  .st-cv{height:220px}
  .table-responsive{max-height:none!important;overflow:visible!important}
  @page{size:A4 landscape;margin:10mm}
}
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
                        <div class="collapse show" id="ventes"><ul class="nav nav-collapse">
                            <li><a href="/sales/delivery/create"><span class="sub-item">Nouvelle Commande</span></a></li>
                            <li><a href="/devislist"><span class="sub-item">Devis</span></a></li>
                            <li><a href="/sales"><span class="sub-item">Commandes Ventes</span></a></li>
                            <li><a href="/delivery_notes/list"><span class="sub-item">Bons de Livraison</span></a></li>
                            <li><a href="/delivery_notes/returns/list"><span class="sub-item">Retours Vente</span></a></li>
                            <li><a href="/salesinvoices"><span class="sub-item">Factures</span></a></li>
                            <li><a href="/salesnotes/list"><span class="sub-item">Avoirs</span></a></li>
                            <li><a href="{{ route('sales.stats') }}"><span class="sub-item active">Statistiques ventes</span></a></li>
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
                        <div class="collapse" id="parametres"><ul class="nav nav-collapse">
                            <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                            <li><a href="{{ route('admin.golda.index') }}"><span class="sub-item">Import GOLDA</span></a></li>
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
            <div class="page-inner st">

@php
    $fm  = function ($v, $d = 2) { return number_format((float) $v, $d, ',', ' '); };
    $eur = function ($v, $d = 0) { return number_format((float) $v, $d, ',', ' ') . ' €'; };
    $fr  = function ($s) { return $s ? date('d/m/Y', strtotime($s)) : '—'; };
    $cur = $data['kpis']['cur']; $prev = $data['kpis']['prev']; $dl = $data['kpis']['delta']; $per = $data['period'];
    // badge d'évolution : $good = sens favorable (true : hausse = bien)
    $delta = function ($v, $suffix = ' %', $good = true) {
        if ($v === null) return '<span class="st-d st-d0">—</span>';
        $up = $v > 0; $flat = abs($v) < 0.05;
        $cls = $flat ? 'st-d0' : (($up === $good) ? 'st-dg' : 'st-db');
        return '<span class="st-d ' . $cls . '">' . ($flat ? '=' : ($up ? '▲' : '▼')) . ' ' . number_format(abs($v), 1, ',', ' ') . $suffix . '</span>';
    };
    $num = function ($v, $dec = 2, $extra = '') use ($fm) {
        if ($v === null || $v === '') return '<td class="num text-muted" data-v="-1">—</td>';
        return '<td class="num ' . $extra . '" data-v="' . $v . '">' . $fm($v, $dec) . '</td>';
    };
    $hasSales = $cur['ca'] > 0;
    $qs = http_build_query(array_filter($f, function ($v) { return $v !== null && $v !== ''; }));
    // données graphiques
    $ser = $data['series'];
    $topCat = array_slice($data['groups']['category'], 0, 6);
    $otherCa = 0; foreach (array_slice($data['groups']['category'], 6) as $g) $otherCa += $g['ca'];
    $catLabels = array_map(function ($g) { return $g['label']; }, $topCat); $catVals = array_map(function ($g) { return $g['ca']; }, $topCat);
    if ($otherCa > 0) { $catLabels[] = 'Autres'; $catVals[] = round($otherCa, 2); }
    $brandTop = array_slice($data['groups']['brand'], 0, 8); $supTop = array_slice($data['groups']['supplier'], 0, 8);
    // courbe de Pareto (échantillonnée)
    $pareto = []; $na = count($data['articles']); $step = max(1, (int) ceil($na / 120));
    foreach ($data['articles'] as $i => $a) { if ($i % $step === 0 || $i === $na - 1) $pareto[] = [round(($i + 1) / $na * 100, 1), $a['cum']]; }
@endphp

                {{-- ══ EN-TÊTE ═══════════════════════════════════════ --}}
                <div class="st-head">
                    <div>
                        <h3><i class="fas fa-chart-line me-2"></i> Statistiques de vente</h3>
                        <small>{{ $fr($per['from']) }} → {{ $fr($per['to']) }} · {{ $per['days'] }} jours · comparée à {{ $fr($per['prev_from']) }} → {{ $fr($per['prev_to']) }} · calculé à {{ $data['generated_at'] }}</small>
                    </div>
                    <div class="d-flex gap-2 flex-wrap st-noprint">
                        <a class="btn btn-sm btn-outline-light" href="{{ route('sales.stats') }}?{{ $qs }}&refresh=1" title="Recalculer (les résultats sont gardés 10 min en cache)"><i class="fas fa-sync-alt me-1"></i> Actualiser</a>
                        <button type="button" class="btn btn-sm btn-light" onclick="stPrint()"><i class="fas fa-print me-1"></i> Imprimer / PDF</button>
                        <a class="btn btn-sm btn-success" href="{{ route('sales.stats.export') }}?{{ $qs }}"><i class="fas fa-file-excel me-1"></i> Exporter Excel</a>
                    </div>
                </div>

                {{-- ══ FILTRES ═══════════════════════════════════════ --}}
                <form method="GET" action="{{ route('sales.stats') }}" id="stForm" class="st-filters st-noprint">
                    <div class="st-row">
                        <div class="st-chips" id="stPresets">
                            <button type="button" data-p="month">Ce mois</button>
                            <button type="button" data-p="lastmonth">Mois dernier</button>
                            <button type="button" data-p="30">30 j</button>
                            <button type="button" data-p="90">3 mois</button>
                            <button type="button" data-p="180">6 mois</button>
                            <button type="button" data-p="year">Cette année</button>
                            <button type="button" data-p="365">12 mois</button>
                        </div>
                        <div class="st-dates">
                            <input type="date" name="date_from" class="form-control" value="{{ $f['date_from'] }}">
                            <span>→</span>
                            <input type="date" name="date_to" class="form-control" value="{{ $f['date_to'] }}">
                        </div>
                    </div>
                    <div class="st-row">
                        <div class="st-f"><select name="brand_id" class="form-select st-sel"><option value="">Marques (Toutes)</option>
                            @foreach($brands as $b)<option value="{{ $b->id }}" {{ (string) $f['brand_id'] === (string) $b->id ? 'selected' : '' }}>{{ $b->name }}</option>@endforeach</select></div>
                        <div class="st-f"><select name="category_id" id="stCat" class="form-select st-sel"><option value="">Familles (Toutes)</option>
                            @foreach($categories as $c)<option value="{{ $c->id }}" {{ (string) $f['category_id'] === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
                        <div class="st-f"><select name="subcategory_id" id="stSub" class="form-select st-sel"><option value="">Sous-familles (Toutes)</option>
                            @foreach($subcategories as $s)<option value="{{ $s->id }}" data-category="{{ $s->category_id }}" {{ (string) $f['subcategory_id'] === (string) $s->id ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach</select></div>
                        <div class="st-f"><select name="codefournisseur" class="form-select st-sel"><option value="">Fournisseurs (Tous)</option>
                            @foreach($suppliers as $s)<option value="{{ $s->code }}" {{ (string) $f['codefournisseur'] === (string) $s->code ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach</select></div>
                        <div class="st-f"><select name="vendeur" class="form-select st-sel"><option value="">Vendeurs (Tous)</option>
                            @foreach($vendeurs as $v)<option value="{{ $v }}" {{ (string) $f['vendeur'] === (string) $v ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
                        <div class="st-f st-f2"><input type="text" name="client" class="form-control" placeholder="Client (code ou nom)" value="{{ $f['client'] }}"></div>
                        <div class="st-f st-f2"><input type="text" name="search" class="form-control" placeholder="Article (réf. ou désignation)" value="{{ $f['search'] }}"></div>
                        <div class="st-btns">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Appliquer</button>
                            <a href="{{ route('sales.stats') }}" class="btn btn-outline-secondary" title="Réinitialiser"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>

@if(!$hasSales)
                <div class="st-empty"><i class="fas fa-chart-bar"></i><h5>Aucune vente sur cette période</h5><p>Élargissez la période ou retirez des filtres.</p></div>
@else
                {{-- ══ KPI ═══════════════════════════════════════════ --}}
                <div class="st-kpis">
                    <div class="st-kpi k-blue"><span>Chiffre d'affaires HT</span><b>{{ $eur($cur['ca']) }}</b>{!! $delta($dl['ca']) !!}<em>{{ $eur($cur['per_day']) }} / jour</em></div>
                    <div class="st-kpi k-green"><span>Marge estimée</span><b>{{ $eur($cur['margin']) }}</b>{!! $delta($dl['margin']) !!}<em>{{ $cur['margin_pc'] === null ? '—' : $fm($cur['margin_pc'], 1) . ' % de taux' }}@if($dl['margin_pc'] !== null) ({{ ($dl['margin_pc'] > 0 ? '+' : '') . $fm($dl['margin_pc'], 1) }} pts)@endif</em></div>
                    <div class="st-kpi k-violet"><span>Bons de livraison</span><b>{{ $fm($cur['docs'], 0) }}</b>{!! $delta($dl['docs']) !!}<em>panier moyen {{ $eur($cur['basket']) }} {!! $delta($dl['basket']) !!}</em></div>
                    <div class="st-kpi k-orange"><span>Quantités vendues</span><b>{{ $fm($cur['qty'], 0) }}</b>{!! $delta($dl['qty']) !!}<em>{{ $fm($cur['articles'], 0) }} références différentes</em></div>
                    <div class="st-kpi k-teal"><span>Clients actifs</span><b>{{ $fm($cur['clients'], 0) }}</b>{!! $delta($dl['clients']) !!}<em>{{ $cur['clients'] ? $eur($cur['ca'] / $cur['clients']) : '—' }} / client</em></div>
                    <div class="st-kpi k-red"><span>Stock dormant</span><b id="kpiDormV">…</b><span class="st-d st-d0" id="kpiDormN">calcul en cours</span><em>immobilisé au coût net</em></div>
                </div>

                {{-- ══ ENSEIGNEMENTS ═════════════════════════════════ --}}
                <div class="st-insights">
                    @foreach($data['insights'] as $ins)
                    <div class="st-ins i-{{ $ins['type'] }}">{{ $ins['text'] }}</div>
                    @endforeach
                </div>

                {{-- ══ GRAPHIQUES ════════════════════════════════════ --}}
                <div class="st-grid">
                    <div class="st-card g-8"><div class="st-ct">Évolution du chiffre d'affaires et de la marge <small>par {{ ['day' => 'jour', 'week' => 'semaine', 'month' => 'mois'][$ser['granularity']] }}</small></div><div class="st-cv"><canvas id="chEvol"></canvas></div></div>
                    <div class="st-card g-4"><div class="st-ct">Répartition par famille</div><div class="st-cv"><canvas id="chCat"></canvas></div></div>
                    <div class="st-card g-4"><div class="st-ct">Top marques <small>CA HT</small></div><div class="st-cv st-cv-s"><canvas id="chBrand"></canvas></div></div>
                    <div class="st-card g-4"><div class="st-ct">Top fournisseurs <small>CA HT</small></div><div class="st-cv st-cv-s"><canvas id="chSup"></canvas></div></div>
                    <div class="st-card g-4"><div class="st-ct">Ventes par jour de la semaine</div><div class="st-cv st-cv-s"><canvas id="chDow"></canvas></div></div>
                </div>

                {{-- ══ ONGLETS ═══════════════════════════════════════ --}}
                <div class="st-card st-tabs-card">
                    <ul class="nav st-tabs st-noprint" role="tablist">
                        <li><button class="active" data-bs-toggle="tab" data-bs-target="#t-art" type="button">Top articles</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-cat" type="button">Familles</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-sub" type="button">Sous-familles</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-brand" type="button">Marques</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-sup" type="button">Fournisseurs</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-abc" type="button">Rotation &amp; ABC @if(count($data['alerts']))<span class="st-badge-r">{{ count($data['alerts']) }}</span>@endif</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-dorm" type="button">Dormants</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-cli" type="button">Clients</button></li>
                        <li><button data-bs-toggle="tab" data-bs-target="#t-ven" type="button">Vendeurs</button></li>
                    </ul>
                    <div class="tab-content">

                        {{-- TOP ARTICLES --}}
                        <div class="tab-pane fade show active" id="t-art" data-title="Top articles">
                            <div class="st-tools st-noprint"><input type="text" class="form-control st-q" placeholder="Filtrer le tableau…"><small>{{ min(100, count($data['articles'])) }} premiers sur {{ count($data['articles']) }} articles vendus · l'export Excel en contient jusqu'à 3 000</small></div>
                            <div class="table-responsive"><table class="st-table">
                                <thead><tr><th>#</th><th>Article</th><th>Marque</th><th>Famille</th><th>ABC</th><th class="num">Qté</th><th class="num">CA HT</th><th class="num">Part</th><th class="num">Marge %</th><th class="num">Clients</th><th>Dernière vente</th><th class="num">Stock</th><th class="num" title="Jours de vente couverts par le stock">Couv. (j)</th></tr></thead>
                                <tbody>
                                @foreach(array_slice($data['articles'], 0, 100) as $i => $a)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td><b>{{ $a['code'] }}</b><div class="st-sub">{{ $a['name'] }}</div></td>
                                    <td>{{ $a['brand'] }}</td><td>{{ $a['category'] }}</td>
                                    <td><span class="abc abc-{{ $a['class'] }}">{{ $a['class'] }}</span></td>
                                    {!! $num($a['qty'], 0) !!}
                                    <td class="num st-bar" style="--w:{{ min(100, $a['share'] * 4) }}%" data-v="{{ $a['ca'] }}"><b>{{ $fm($a['ca']) }}</b></td>
                                    {!! $num($a['share'], 1) !!}
                                    {!! $num($a['margin_pc'], 1) !!}
                                    {!! $num($a['clients'], 0) !!}
                                    <td data-v="{{ $a['last_sale'] }}">{{ $fr($a['last_sale']) }}</td>
                                    <td class="num {{ $a['stock'] <= 0 ? 'st-neg' : '' }}" data-v="{{ $a['stock'] }}">{{ $fm($a['stock'], 0) }}</td>
                                    <td class="num {{ ($a['coverage'] !== null && $a['coverage'] < 15) ? 'st-neg' : '' }}" data-v="{{ $a['coverage'] === null ? -1 : $a['coverage'] }}">{{ $a['coverage'] === null ? '—' : $a['coverage'] }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table></div>
                        </div>

                        {{-- GROUPES --}}
                        @foreach([['t-cat', 'category', 'Famille'], ['t-sub', 'subcategory', 'Sous-famille'], ['t-brand', 'brand', 'Marque'], ['t-sup', 'supplier', 'Fournisseur']] as $tab)
                        <div class="tab-pane fade" id="{{ $tab[0] }}" data-title="{{ $tab[2] }}s">
                            <div class="st-tools st-noprint"><input type="text" class="form-control st-q" placeholder="Filtrer le tableau…"><small>{{ count($data['groups'][$tab[1]]) }} lignes</small></div>
                            <div class="table-responsive"><table class="st-table">
                                <thead><tr><th>{{ $tab[2] }}</th><th class="num">CA HT</th><th class="num">Part</th><th class="num">Qté</th><th class="num">Réf. vendues</th><th class="num" title="Références vendues / références actives au catalogue">% catalogue vendu</th><th class="num">Marge</th><th class="num">Marge %</th><th class="num">Valeur stock</th><th class="num" title="Jours de vente couverts par le stock">Couv. (j)</th></tr></thead>
                                <tbody>
                                @foreach($data['groups'][$tab[1]] as $g)
                                <tr>
                                    <td><b>{{ $g['label'] }}</b></td>
                                    <td class="num st-bar" style="--w:{{ min(100, $g['share']) }}%" data-v="{{ $g['ca'] }}"><b>{{ $fm($g['ca']) }}</b></td>
                                    {!! $num($g['share'], 1) !!}
                                    {!! $num($g['qty'], 0) !!}
                                    <td class="num" data-v="{{ $g['sold'] }}">{{ $g['sold'] }} <span class="text-muted">/ {{ $g['catalog'] }}</span></td>
                                    {!! $num($g['sold_pc'], 1) !!}
                                    {!! $num($g['margin'], 0) !!}
                                    {!! $num($g['margin_pc'], 1) !!}
                                    {!! $num($g['stock_val'], 0) !!}
                                    {!! $num($g['coverage'], 0) !!}
                                </tr>
                                @endforeach
                                </tbody>
                            </table></div>
                        </div>
                        @endforeach

                        {{-- ROTATION & ABC --}}
                        <div class="tab-pane fade" id="t-abc" data-title="Rotation & ABC">
                            <div class="st-abc">
                                @foreach(['A' => '80 % du CA', 'B' => '15 % suivants', 'C' => '5 % restants'] as $k => $lab)
                                <div class="st-abc-c abc-bg-{{ $k }}"><div class="abc abc-{{ $k }}">{{ $k }}</div><div><b>{{ $data['abc'][$k]['n'] }}</b> articles <span class="text-muted">({{ $fm($data['abc'][$k]['n_pc'], 1) }} % des réf.)</span><br><b>{{ $eur($data['abc'][$k]['ca']) }}</b> · {{ $fm($data['abc'][$k]['ca_pc'], 1) }} % du CA</div></div>
                                @endforeach
                            </div>
                            <div class="st-grid" style="margin-top:12px">
                                <div class="st-card g-6"><div class="st-ct">Courbe de Pareto <small>% du CA cumulé selon le % d'articles</small></div><div class="st-cv st-cv-s"><canvas id="chPareto"></canvas></div></div>
                                <div class="st-card g-6">
                                    <div class="st-ct">À réapprovisionner en priorité <small>articles A/B en rupture ou &lt; 15 jours de couverture</small></div>
                                    @if(count($data['alerts']))
                                    <div class="table-responsive" style="max-height:260px;overflow:auto"><table class="st-table st-compact">
                                        <thead><tr><th>Article</th><th>ABC</th><th class="num">Vendu</th><th class="num">Stock</th><th class="num">Couv.</th><th class="num" title="Quantité pour couvrir 30 jours">À cmd (30 j)</th></tr></thead>
                                        <tbody>
                                        @foreach($data['alerts'] as $a)
                                        <tr><td><b>{{ $a['code'] }}</b><div class="st-sub">{{ $a['name'] }}</div></td><td><span class="abc abc-{{ $a['class'] }}">{{ $a['class'] }}</span></td>
                                            {!! $num($a['qty'], 0) !!}<td class="num st-neg" data-v="{{ $a['stock'] }}">{{ $fm($a['stock'], 0) }}</td>{!! $num($a['coverage'], 0) !!}<td class="num" data-v="{{ $a['suggest'] }}"><b>{{ $a['suggest'] }}</b></td></tr>
                                        @endforeach
                                        </tbody></table></div>
                                    @else
                                    <div class="st-ok"><i class="fas fa-check-circle"></i> Aucun article A/B en risque de rupture.</div>
                                    @endif
                                </div>
                            </div>
                            <div class="st-note">Rotation = quantités vendues annualisées / stock actuel. Couverture = jours de vente que le stock actuel permet d'assurer au rythme de la période. Les colonnes « Rotation » et « Couverture » détaillées par article figurent dans l'export Excel.</div>
                        </div>

                        {{-- ARTICLES DORMANTS (chargé à la demande) --}}
                        <div class="tab-pane fade" id="t-dorm" data-title="Articles dormants">
                            <div class="st-lazy" data-section="dormants"><div class="st-loading"><i class="fas fa-spinner fa-spin"></i> Chargement…</div></div>
                        </div>

                        {{-- CLIENTS (chargé à la demande) --}}
                        <div class="tab-pane fade" id="t-cli" data-title="Clients">
                            <div class="st-lazy" data-section="clients"><div class="st-loading"><i class="fas fa-spinner fa-spin"></i> Chargement…</div></div>
                        </div>

                        {{-- VENDEURS (chargé à la demande) --}}
                        <div class="tab-pane fade" id="t-ven" data-title="Vendeurs">
                            <div class="st-lazy" data-section="vendeurs"><div class="st-loading"><i class="fas fa-spinner fa-spin"></i> Chargement…</div></div>
                        </div>

                    </div>
                </div>
                <div class="st-note">CA = quantité livrée × prix HT × (1 − remise), d'après les bons de livraison (annulés et brouillons exclus). Marge = CA − coût d'achat net actuel de l'article ; les articles sans coût sont exclus du calcul de marge.</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    // ── Filtres : select2 + sous-familles dépendantes de la famille ─────────
    var $cat = $('#stCat'), $sub = $('#stSub');
    var allSubs = $sub.find('option').map(function () { return { v: this.value, t: this.text, c: $(this).data('category') }; }).get();
    var selSub = $sub.val();
    function fillSubs() {
        var c = $cat.val(), cur = $sub.val();
        $sub.empty();
        allSubs.forEach(function (o) { if (!o.v || !c || String(o.c) === String(c)) $sub.append(new Option(o.t, o.v, false, o.v === cur)); });
        $sub.trigger('change.select2');
    }
    $('.st-sel').select2({ width: '100%', allowClear: true, placeholder: function () { return $(this).find('option:first').text(); } });
    $cat.on('change', fillSubs);
    fillSubs(); $sub.val(selSub).trigger('change.select2');

    // ── Raccourcis de période ──────────────────────────────────────────────
    function iso(d) { var m = d.getMonth() + 1, j = d.getDate(); return d.getFullYear() + '-' + (m < 10 ? '0' : '') + m + '-' + (j < 10 ? '0' : '') + j; }
    $('#stPresets button').on('click', function () {
        var p = $(this).data('p'), e = new Date(), s = new Date();
        if (p === 'month') s = new Date(e.getFullYear(), e.getMonth(), 1);
        else if (p === 'lastmonth') { s = new Date(e.getFullYear(), e.getMonth() - 1, 1); e = new Date(e.getFullYear(), e.getMonth(), 0); }
        else if (p === 'year') s = new Date(e.getFullYear(), 0, 1);
        else s.setDate(e.getDate() - parseInt(p, 10) + 1);
        $('[name=date_from]').val(iso(s)); $('[name=date_to]').val(iso(e));
        $('#stForm').submit();
    });

    // ── Tableaux : tri par colonne + filtre texte (tableaux chargés après coup inclus) ──
    function initTables(root) {
        $(root).find('.st-table').each(function () {
            var $t = $(this);
            $t.find('th').each(function (i) {
                $(this).on('click', function () {
                    var asc = !$(this).hasClass('asc');
                    $t.find('th').removeClass('asc desc'); $(this).addClass(asc ? 'asc' : 'desc');
                    var rows = $t.find('tbody tr').get();
                    rows.sort(function (a, b) {
                        var x = $(a).children().eq(i), y = $(b).children().eq(i);
                        var vx = x.attr('data-v'), vy = y.attr('data-v');
                        if (vx === undefined) { vx = x.text().trim().toLowerCase(); vy = y.text().trim().toLowerCase(); }
                        var nx = parseFloat(vx), ny = parseFloat(vy);
                        var r = (!isNaN(nx) && !isNaN(ny) && /^-?[\d.]+$/.test(vx)) ? nx - ny : String(vx).localeCompare(String(vy), 'fr');
                        return asc ? r : -r;
                    });
                    $.each(rows, function (_, r) { $t.find('tbody').append(r); });
                });
            });
        });
    }
    initTables(document);
    $(document).on('input', '.st-q', function () {
        var q = this.value.toLowerCase(), $t = $(this).closest('.tab-pane').find('.st-table');
        $t.find('tbody tr').each(function () { $(this).toggle(!q || $(this).text().toLowerCase().indexOf(q) !== -1); });
    });

    // ── Sections lourdes chargées à la demande (dormants, clients, vendeurs) ──
    var SECTION_URL = {!! json_encode(url('/sales/stats/section')) !!} + '/', QS = {!! json_encode($qs) !!};
    var loaded = {};
    function loadSection(name) {
        if (loaded[name]) return loaded[name];
        var $box = $('.st-lazy[data-section=' + name + ']');
        loaded[name] = fetch(SECTION_URL + name + '?' + QS, { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
            .then(function (d) {
                $box.html(d.html); initTables($box);
                if (d.kpi) { $('#kpiDormV').text(d.kpi.value); $('#kpiDormN').text(d.kpi.count); }
                (d.insights || []).forEach(function (i) { $('.st-insights').append($('<div class="st-ins"></div>').addClass('i-' + i.type).text(i.text)); });
                return d;
            })
            .catch(function (e) {
                loaded[name] = null;
                $box.html('<div class="st-err"><i class="fas fa-exclamation-triangle"></i> Chargement impossible (' + e.message + '). <a href="#" class="st-retry" data-s="' + name + '">Réessayer</a></div>');
                if (name === 'dormants') { $('#kpiDormV').text('—'); $('#kpiDormN').text('indisponible'); }
            });
        return loaded[name];
    }
    $(document).on('click', '.st-retry', function (e) { e.preventDefault(); loadSection($(this).data('s')); });
    $('button[data-bs-toggle=tab]').on('shown.bs.tab', function (e) {
        var t = $(e.target).data('bs-target') || '', m = { '#t-dorm': 'dormants', '#t-cli': 'clients', '#t-ven': 'vendeurs' };
        if (m[t]) loadSection(m[t]);
    });
    // Chargement en arrière-plan, l'un après l'autre, sans bloquer l'affichage
    @if($hasSales)
    setTimeout(function () { loadSection('dormants').then(function () { return loadSection('clients'); }).then(function () { return loadSection('vendeurs'); }); }, 300);
    @else
    $('#kpiDormV').text('—');
    @endif
    window.stPrint = function () {
        Promise.all([loadSection('dormants'), loadSection('clients'), loadSection('vendeurs')]).then(function () {
            if (window.stDrawPareto) window.stDrawPareto();
            setTimeout(function () { window.print(); }, 150);
        });
    };

    // ── Graphiques ─────────────────────────────────────────────────────────
@if($hasSales)
    if (!window.Chart || !document.getElementById('chEvol')) return;
    Chart.defaults.font.family = "'Public Sans', system-ui, sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#64748b';
    var PAL = ['#2563eb', '#0ea5e9', '#14b8a6', '#8b5cf6', '#f59e0b', '#ec4899', '#94a3b8'];
    var fmt = function (v) { return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(v) + ' €'; };
    var short = function (v) { return Math.abs(v) >= 1000 ? (v / 1000).toLocaleString('fr-FR', { maximumFractionDigits: 1 }) + ' k' : v; };
    var grid = { color: '#eef2f7' };

    var S = {!! json_encode($ser, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!};
    new Chart(document.getElementById('chEvol'), {
        data: { labels: S.labels, datasets: [
            { type: 'bar', label: 'CA HT', data: S.ca, backgroundColor: '#2563eb', borderRadius: 4, order: 2 },
            { type: 'line', label: 'Marge', data: S.margin, borderColor: '#16a34a', backgroundColor: '#16a34a', tension: .3, pointRadius: 2, borderWidth: 2, order: 1 }
        ] },
        options: { maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } }, tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ' : ' + fmt(c.parsed.y); } } } },
            scales: { x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 14 } }, y: { grid: grid, ticks: { callback: short } } } }
    });

    new Chart(document.getElementById('chCat'), {
        type: 'doughnut',
        data: { labels: {!! json_encode($catLabels, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}, datasets: [{ data: {!! json_encode($catVals) !!}, backgroundColor: PAL, borderWidth: 2, borderColor: '#fff' }] },
        options: { maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
            tooltip: { callbacks: { label: function (c) { var t = c.dataset.data.reduce(function (a, b) { return a + b; }, 0); return ' ' + c.label + ' : ' + fmt(c.parsed) + ' (' + (c.parsed / t * 100).toFixed(1).replace('.', ',') + ' %)'; } } } } }
    });

    function hbar(id, labels, vals, color) {
        new Chart(document.getElementById(id), {
            type: 'bar', data: { labels: labels, datasets: [{ data: vals, backgroundColor: color, borderRadius: 4 }] },
            options: { indexAxis: 'y', maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return ' ' + fmt(c.parsed.x); } } } },
                scales: { x: { grid: grid, ticks: { callback: short } }, y: { grid: { display: false }, ticks: { autoSkip: false, callback: function (v) { var l = this.getLabelForValue(v); return l.length > 18 ? l.slice(0, 17) + '…' : l; } } } } }
        });
    }
    hbar('chBrand', {!! json_encode(array_map(function ($g) { return $g['label']; }, $brandTop), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}, {!! json_encode(array_map(function ($g) { return $g['ca']; }, $brandTop)) !!}, '#0ea5e9');
    hbar('chSup', {!! json_encode(array_map(function ($g) { return $g['label']; }, $supTop), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}, {!! json_encode(array_map(function ($g) { return $g['ca']; }, $supTop)) !!}, '#8b5cf6');

    var D = {!! json_encode($data['weekday'], JSON_UNESCAPED_UNICODE) !!}, mx = Math.max.apply(null, D.ca);
    new Chart(document.getElementById('chDow'), {
        type: 'bar', data: { labels: D.labels, datasets: [{ data: D.ca, backgroundColor: D.ca.map(function (v) { return v === mx ? '#2563eb' : '#bfd3f8'; }), borderRadius: 4 }] },
        options: { maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return ' ' + fmt(c.parsed.y); } } } }, scales: { x: { grid: { display: false } }, y: { grid: grid, ticks: { callback: short } } } }
    });

    // Pareto : dessiné au premier affichage de l'onglet (canvas masqué = taille 0)
    var paretoDone = false;
    function drawPareto() {
        if (paretoDone || !document.getElementById('chPareto')) return; paretoDone = true;
        var P = {!! json_encode($pareto) !!};
        new Chart(document.getElementById('chPareto'), {
            type: 'line',
            data: { datasets: [
                { label: '% du CA cumulé', data: P.map(function (p) { return { x: p[0], y: p[1] }; }), borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,.12)', fill: true, pointRadius: 0, borderWidth: 2, tension: .2 },
                { label: '80 %', data: [{ x: 0, y: 80 }, { x: 100, y: 80 }], borderColor: '#f59e0b', borderDash: [5, 4], pointRadius: 0, borderWidth: 1.5 }
            ] },
            options: { maintainAspectRatio: false, parsing: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return ' ' + c.parsed.y.toFixed(1).replace('.', ',') + ' % du CA pour ' + c.parsed.x.toFixed(1).replace('.', ',') + ' % des articles'; } } } },
                scales: { x: { type: 'linear', min: 0, max: 100, grid: { display: false }, ticks: { callback: function (v) { return v + ' %'; } }, title: { display: true, text: "% des articles (classés par CA)" } }, y: { min: 0, max: 100, grid: grid, ticks: { callback: function (v) { return v + ' %'; } } } } }
        });
    }
    window.stDrawPareto = drawPareto;
    $('button[data-bs-target="#t-abc"]').on('shown.bs.tab', drawPareto);
    window.addEventListener('beforeprint', function () { drawPareto(); });
@endif
})();
</script>

</body>
</html>
