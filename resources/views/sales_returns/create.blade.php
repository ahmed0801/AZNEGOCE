<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Créer un Retour de Vente - AZ ERP</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
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
        .card { border-radius: 10px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0,0,0,.08); }
        .card-body { padding: 1.5rem; }
        .form-label { font-weight: 600; color: #343a40; font-size: .82rem; margin-bottom: .25rem; }
        .form-control, .form-select { border-radius: 8px; }
        .table th, .table td { vertical-align: middle; font-size: .85rem; }

        .lh-bar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
        .lh-title{margin:0;font-weight:700;display:flex;align-items:center;gap:.5rem}
        .lh-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-btns .btn,.lh-filters .btn{height:38px;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:0 1rem;font-size:.88rem;font-weight:600;border-radius:8px;white-space:nowrap;margin:0}
        .lh-filters{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;margin-bottom:.5rem}
        .lh-filters .lh-field{flex:1 1 150px;min-width:130px;position:relative}
        .lh-filters .lh-wide{flex:2 1 260px}
        .lh-filters .lh-field i.lh-ico{position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;z-index:2}
        .lh-filters .form-control{height:38px;border-radius:8px;font-size:.88rem}
        .lh-filters #item_search{padding-left:2.1rem;background:#e8f4fd;border:1.5px solid #90c0e8}
        .lh-filters #item_search:focus{background:#fff;border-color:#007bff;box-shadow:0 0 0 3px rgba(0,123,255,.12);outline:none}

        .top-row .select2-container--default .select2-selection--single{height:38px;border-radius:8px;display:flex;align-items:center;border:1px solid #ced4da}
        .top-row .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px;padding-left:.6rem}
        .top-row .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px}
        .top-row .form-control{height:38px!important;min-height:38px;line-height:36px;padding:0 .6rem;font-size:.88rem}
        .select2-container--default .select2-selection--multiple{min-height:38px;border-radius:8px;border:1px solid #ced4da}
        .select2-container--default .select2-selection--multiple .select2-selection__choice{background:#e8f1ff;border:1px solid #9ec5fe;border-radius:6px;color:#0a58ca;font-weight:600;margin-top:5px}
        .select2-results__option{padding:8px 10px}

        .st-group{display:flex;gap:.5rem;flex-wrap:wrap}
        .st-btn{flex:1 1 0;min-width:120px;height:38px;border:1.5px solid #cfd8e3;background:#fff;border-radius:8px;font-weight:600;font-size:.84rem;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;color:#374151;cursor:pointer;transition:all .15s;padding:0 .6rem}
        .st-btn:hover{border-color:#0d6efd;color:#0d6efd}
        .st-btn.active{background:#0d6efd;border-color:#0d6efd;color:#fff;box-shadow:0 2px 8px rgba(13,110,253,.25)}

        #customer_banner{display:none;flex-wrap:wrap;align-items:center;gap:0 14px;font-size:.82rem;line-height:1.8;background:#f0f7ff;border:1px solid #b8d4f0;border-left:4px solid #0056b3;border-radius:5px;padding:5px 12px;margin-bottom:10px}
        #customer_banner .cb-name{font-weight:700;color:#003a80;font-size:.88rem}
        #customer_banner .cb-sep{color:#aac4e0}
        #customer_banner .cb-label{color:#666}
        #customer_banner .cb-val{font-weight:600;color:#222}

        .src-panel{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:.8rem 1rem;margin-bottom:1rem}
        .src-hint{font-size:.78rem;color:#6b7280;margin-top:.35rem}
        #item_results{background:#eaf3fc;border-radius:6px;box-shadow:0 2px 4px rgba(0,0,0,.1);max-height:260px;overflow-y:auto;position:relative}
        #item_results:empty{display:none}
        #item_results .sr-item{display:block;padding:.45rem .7rem;border-bottom:1px solid #dbe7f5;cursor:pointer;border-left:5px solid transparent;position:relative;padding-right:6rem}
        #item_results .sr-item:hover{background:#eef5ff}
        #item_results .sr-item.active{background:#cfe3ff;border-left-color:#0d6efd}
        #item_results .sr-item.active::after{content:"\21B5  Entrée";position:absolute;right:.7rem;top:.45rem;font-size:.72rem;font-weight:700;color:#fff;background:#0d6efd;border-radius:6px;padding:2px 8px}
        #item_results .sr-empty{padding:.5rem .7rem;color:#6b7280}

        #lines_wrap{max-height:460px;overflow:auto;border:1px solid #e5e7eb;border-radius:10px}
        #lines_table{margin:0}
        #lines_table thead th{position:sticky;top:0;z-index:2;background:#1a2b4a;color:#fff;font-size:.78rem;font-weight:600;text-align:center;padding:.5rem;white-space:nowrap}
        #lines_table tbody td{padding:.4rem .5rem;vertical-align:middle}
        #lines_table .ref-code{font-family:'Courier New',Consolas,monospace;font-weight:900;font-size:1rem;color:#0040c0;background:#eef4ff;border-left:3px solid #0040c0;padding:1px 6px;border-radius:3px;display:inline-block}
        #lines_table .des-name{display:block;font-weight:600;font-size:.84rem;color:#111;line-height:1.3;margin-top:2px}
        #lines_table input.form-control{height:34px;font-size:.84rem;padding:0 .4rem;text-align:center}
        #lines_table input.q-in{width:84px}
        #lines_table input.p-in{width:96px}
        #lines_table input.r-in{width:76px}
        #lines_table .l-ht,#lines_table .l-ttc{text-align:right;font-weight:700;color:#b42318;white-space:nowrap}
        #lines_table tr.line-off{opacity:.45}
        #lines_table tr.line-off .l-ht,#lines_table tr.line-off .l-ttc{text-decoration:line-through}
        .rest-badge{display:block;font-size:.7rem;color:#6b7280;margin-top:2px;text-align:center}
        .rest-badge.zero{color:#b42318;font-weight:700}
        #lines_empty{padding:1.6rem;text-align:center;color:#6b7280;font-size:.9rem}

        .lh-actionbar{position:sticky;bottom:0;z-index:20;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-top:.75rem;padding:.7rem 1rem;background:#fff;border:1px solid #dbe3ee;border-radius:10px;box-shadow:0 -4px 14px rgba(0,0,0,.08)}
        .lh-actionbar .ab-totals{display:flex;gap:1.5rem;align-items:baseline;font-size:1.05rem;flex-wrap:wrap}
        .lh-actionbar .ab-totals b{font-size:1.25rem;color:#b42318}
        .lh-actionbar .ab-btns{display:flex;flex-wrap:wrap;gap:.5rem}
        .lh-actionbar .ab-btns .btn{height:42px;min-width:150px;display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:0 1rem;font-size:.9rem;font-weight:600;border-radius:8px;margin:0;white-space:nowrap}
        @media (max-width:768px){.lh-actionbar .ab-btns .btn{flex:1 1 45%;min-width:0}}

        .st-btn.active.st-warn{background:#f59e0b;border-color:#f59e0b;box-shadow:0 2px 8px rgba(245,158,11,.3)}
        .type-help{font-size:.82rem;color:#4b5563;margin-top:.5rem}
        #lines_table .chk{width:20px;height:20px;cursor:pointer}
        #lines_table tr.picked{background:#fff8e6}
        #lines_table tr.exhausted{opacity:.45}
        #lines_table input.q-in:disabled{background:#f3f4f6}
        #lines_table td.num{text-align:right;white-space:nowrap}
        #lines_table .l-ht{color:#111}
        .ab-totals b{color:#0d6efd!important}
        .warn-box{background:#fff8e6;border:1px solid #f5d38a;border-left:4px solid #f59e0b;border-radius:6px;padding:.5rem .8rem;font-size:.83rem;margin-top:.75rem}
    </style>
</head>
<body>
    <div class="wrapper sidebar_minimize">
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

                        
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('assets/img/avatar.png') }}" alt="image profile" class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramètres</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
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
            </div>

            @php
                $returnedMap = \App\Models\SalesReturnLine::whereHas('salesReturn', function ($q) use ($deliveryNote) { $q->where('delivery_note_id', $deliveryNote->id); })
                    ->selectRaw('article_code, SUM(returned_quantity) as q')->groupBy('article_code')->pluck('q', 'article_code');
                $oldType = old('type', 'total');
                $anyReturned = $returnedMap->sum() > 0;
                $totalBL = $deliveryNote->lines->sum('total_ligne_ht');
            @endphp
            <div class="container">
                <div class="page-inner">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                        </div>
                    @endif

                    <div class="lh-bar">
                        <h3 class="lh-title"><i class="fas fa-undo-alt text-warning"></i> Nouveau retour vente <span class="badge bg-light text-dark border">BL {{ $deliveryNote->numdoc }}</span></h3>
                        <div class="lh-btns">
                            <a href="{{ route('delivery_notes.list') }}" class="btn btn-outline-secondary"><i class="fas fa-list"></i> Liste des BL</a>
                        </div>
                    </div>

                    <form action="{{ route('delivery_notes.salesreturns.store', $deliveryNote->id) }}" method="POST" id="returnForm" novalidate>
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $deliveryNote->customer->id ?? '' }}">
                        <input type="hidden" name="type" id="returnType" value="{{ $oldType === 'partiel' ? 'partiel' : 'total' }}">

                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3 top-row">
                                    <div class="col-lg-8">
                                        <label class="form-label">Client</label>
                                        <input type="text" class="form-control" value="{{ $deliveryNote->customer->name ?? 'Inconnu' }}" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label" for="return_date">Date du retour</label>
                                        <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror" value="{{ old('return_date', now()->format('Y-m-d')) }}" required>
                                        @error('return_date')<span class="error d-block">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Type de retour</label>
                                    <div class="st-group" id="st_group">
                                        <button type="button" class="st-btn" data-type="total"><i class="fas fa-undo"></i> Retour total</button>
                                        <button type="button" class="st-btn st-warn" data-type="partiel"><i class="fas fa-cut"></i> Retour partiel</button>
                                    </div>
                                    <div class="type-help" id="typeHelp"></div>
                                    @error('type')<span class="error d-block">{{ $message }}</span>@enderror
                                </div>

                                @if($anyReturned)
                                    <div class="warn-box" id="returnedWarn">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        Des retours ont déjà été enregistrés sur ce bon de livraison. Un retour <b>partiel</b> est limité aux quantités restantes ; vérifiez le détail avant un retour <b>total</b>.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <h6 class="fw-bold mb-0"><i class="fas fa-receipt"></i> <span id="tbl_title">Lignes du bon de livraison</span> <span class="badge bg-secondary" id="lines_count">0</span></h6>
                                    <div id="partial_tools" style="display:none">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnAll"><i class="fas fa-check-double"></i> Tout cocher</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnNone"><i class="fas fa-times"></i> Tout décocher</button>
                                    </div>
                                </div>

                                <div id="lines_wrap">
                                    <table class="table table-sm table-bordered mb-0" id="lines_table">
                                        <thead>
                                            <tr>
                                                <th class="col-pick" style="width:46px"></th>
                                                <th style="width:150px">Code</th>
                                                <th>Désignation</th>
                                                <th style="width:90px">Livrée</th>
                                                <th style="width:120px" class="col-qty">Qté retournée</th>
                                                <th style="width:100px">PU HT</th>
                                                <th style="width:80px">Remise</th>
                                                <th style="width:115px">Total HT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($deliveryNote->lines as $line)
                                                @php
                                                    $code = $line->article_code;
                                                    $already = (float) ($returnedMap[$code] ?? 0);
                                                    $maxReturnable = max(0, (float) $line->delivered_quantity - $already);
                                                    $isSel = old("lines.{$code}.selected") == 1;
                                                    $pu = number_format((float) $line->unit_price_ht, 2, '.', '');
                                                    $rem = number_format((float) $line->remise, 2, '.', '');
                                                @endphp
                                                <tr class="line {{ $maxReturnable <= 0 ? 'exhausted' : '' }}" data-pu="{{ $pu }}" data-rem="{{ $rem }}" data-deliv="{{ (float) $line->delivered_quantity }}" data-max="{{ $maxReturnable }}">
                                                    <td class="col-pick text-center">
                                                        <input type="checkbox" name="lines[{{ $code }}][selected]" value="1" class="chk select-line" {{ $isSel ? 'checked' : '' }} {{ $maxReturnable <= 0 ? 'disabled' : '' }}>
                                                        <input type="hidden" name="lines[{{ $code }}][article_code]" value="{{ $code }}" class="h-code">
                                                    </td>
                                                    <td><span class="ref-code">{{ $code }}</span></td>
                                                    <td style="text-align:left"><span class="des-name">{{ $line->item->name ?? '-' }}</span></td>
                                                    <td class="text-center">{{ (float) $line->delivered_quantity }}
                                                        @if($already > 0)<span class="rest-badge {{ $maxReturnable <= 0 ? 'zero' : '' }}">déjà retourné {{ $already + 0 }}</span>@endif
                                                    </td>
                                                    <td class="col-qty">
                                                        <input type="number" name="lines[{{ $code }}][returned_quantity]" class="form-control q-in quantity-input @error("lines.{$code}.returned_quantity") is-invalid @enderror" min="0" max="{{ $maxReturnable }}" step="any" value="{{ old("lines.{$code}.returned_quantity", 0) }}" {{ $isSel ? '' : 'disabled' }}>
                                                        <span class="rest-badge">max {{ $maxReturnable + 0 }}</span>
                                                        @error("lines.{$code}.returned_quantity")<span class="error d-block">{{ $message }}</span>@enderror
                                                    </td>
                                                    <td class="num">{{ number_format((float) $line->unit_price_ht, 2, ',', ' ') }} €</td>
                                                    <td class="num">{{ (float) $line->remise + 0 }} %</td>
                                                    <td class="num l-ht" data-full="{{ number_format((float) $line->total_ligne_ht, 2, '.', '') }}">{{ number_format((float) $line->total_ligne_ht, 2, ',', ' ') }} €</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($deliveryNote->lines->isEmpty())
                                        <div id="lines_empty">Ce bon de livraison ne contient aucune ligne.</div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Notes / motif du retour</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Motif du retour, état des pièces…">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="lh-actionbar">
                            <div class="ab-totals">
                                <span id="ab_label">Retour total HT :</span> <b id="grandTotal">0,00</b> €
                            </div>
                            <div class="ab-btns">
                                <a href="{{ route('delivery_notes.list') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Annuler</a>
                                <button type="button" id="btnSubmit" class="btn btn-primary"><i class="fas fa-undo-alt"></i> Créer le retour</button>
                            </div>
                        </div>
                    </form>
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
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(function () {
        const $form = $('#returnForm'), $rows = $('#lines_table tbody tr.line');
        let submitting = false, dirty = false;

        function esc(v) { return String(v == null ? '' : v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
        function fmt(n) { return (Number(n) || 0).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
        function isTotal() { return $('#returnType').val() !== 'partiel'; }

        function recalc() {
            let sum = 0, n = 0;
            if (isTotal()) {
                $rows.each(function () { sum += parseFloat($(this).find('.l-ht').attr('data-full')) || 0; n++; });
            } else {
                $rows.each(function () {
                    const $r = $(this), on = $r.find('.select-line').is(':checked');
                    const q = parseFloat($r.find('.q-in').val()) || 0;
                    const lht = q * parseFloat($r.attr('data-pu')) * (1 - parseFloat($r.attr('data-rem')) / 100);
                    $r.toggleClass('picked', on);
                    $r.find('.l-ht').text(on ? fmt(lht) + ' €' : fmt(parseFloat($r.find('.l-ht').attr('data-full'))) + ' €');
                    if (on && q > 0) { sum += lht; n++; }
                });
            }
            $('#grandTotal').text(fmt(sum));
            $('#lines_count').text(n);
        }

        function applyType() {
            const t = isTotal();
            $('#st_group .st-btn').removeClass('active').filter('[data-type="' + (t ? 'total' : 'partiel') + '"]').addClass('active');
            $('.col-pick, .col-qty, #partial_tools').toggle(!t);
            $('#partial_tools').css('display', t ? 'none' : 'block');
            $('#tbl_title').text(t ? 'Récapitulatif du retour total' : 'Lignes à retourner');
            $('#ab_label').text(t ? 'Retour total HT :' : 'Retour partiel HT :');
            $('#typeHelp').html(t
                ? '<b class="text-success">Retour total :</b> toutes les lignes du bon de livraison sont retournées. Vérifiez simplement le récapitulatif.'
                : '<b class="text-warning">Retour partiel :</b> cochez les lignes concernées et saisissez la quantité à retourner.');
            // total : cases et quantités désactivées (non envoyées), comme avant
            if (t) { $('.select-line, .q-in').prop('disabled', true); $rows.removeClass('picked'); }
            else {
                $rows.each(function () {
                    const $r = $(this), exhausted = parseFloat($r.attr('data-max')) <= 0;
                    $r.find('.select-line').prop('disabled', exhausted);
                    $r.find('.q-in').prop('disabled', !$r.find('.select-line').is(':checked'));
                });
            }
            recalc();
        }

        $('#st_group').on('click', '.st-btn', function () {
            $('#returnType').val($(this).data('type')); dirty = true; applyType();
        });

        $rows.on('change', '.select-line', function () {
            const $r = $(this).closest('tr'), $q = $r.find('.q-in');
            $q.prop('disabled', !this.checked);
            if (this.checked) { if (!(parseFloat($q.val()) > 0)) $q.val($r.attr('data-max')); $q.trigger('focus').trigger('select'); }
            else $q.val(0);
            dirty = true; recalc();
        });
        $rows.on('input', '.q-in', function () {
            const max = parseFloat($(this).attr('max')) || 0; let v = parseFloat($(this).val());
            if (v > max) $(this).val(max); if (v < 0) $(this).val(0);
            $(this).removeClass('is-invalid'); dirty = true; recalc();
        });
        $('#btnAll').on('click', function () {
            $rows.each(function () { const $c = $(this).find('.select-line'); if (!$c.prop('disabled') && !$c.is(':checked')) $c.prop('checked', true).trigger('change'); });
        });
        $('#btnNone').on('click', function () {
            $rows.each(function () { const $c = $(this).find('.select-line'); if ($c.is(':checked')) $c.prop('checked', false).trigger('change'); });
        });
        $form.find('input[name=return_date], textarea[name=notes]').on('input change', function () { dirty = true; });

        function validate() {
            const errs = [];
            if (!$('#return_date').val()) errs.push('La date du retour est obligatoire.');
            if (!isTotal()) {
                const $on = $rows.find('.select-line:checked');
                if (!$on.length) errs.push('Cochez au moins une ligne à retourner.');
                $on.each(function () {
                    const $r = $(this).closest('tr'), $q = $r.find('.q-in'), q = parseFloat($q.val()) || 0, max = parseFloat($r.attr('data-max'));
                    const code = $r.find('.ref-code').text();
                    if (q <= 0) { errs.push(code + ' : quantité retournée > 0 requise.'); $q.addClass('is-invalid'); }
                    else if (q > max) { errs.push(code + ' : maximum ' + max + '.'); $q.addClass('is-invalid'); }
                });
            } else if (!$rows.length) errs.push('Aucune ligne à retourner.');
            return errs;
        }

        $('#btnSubmit').on('click', function () {
            if (submitting) return;
            const errs = validate();
            if (errs.length) { Swal.fire({ icon: 'error', title: 'À corriger', html: errs.slice(0, 8).map(esc).join('<br>') }); return; }
            const t = isTotal();
            Swal.fire({
                icon: 'question', title: 'Créer ce retour ?',
                html: '<div style="text-align:left">BL : <b>' + esc(@json($deliveryNote->numdoc)) + '</b><br>Type : <b>' + (t ? 'total' : 'partiel') + '</b><br>Lignes : <b>' + $('#lines_count').text() + '</b><br>Montant HT : <b>' + $('#grandTotal').text() + ' €</b></div>',
                showCancelButton: true, confirmButtonText: 'Créer le retour', cancelButtonText: 'Annuler'
            }).then(function (r) {
                if (!r.isConfirmed || submitting) return;
                submitting = true; dirty = false; $('#btnSubmit').prop('disabled', true);
                // les champs désactivés (retour total) ne sont pas envoyés : comportement inchangé
                $form[0].submit();
            });
        });

        $form.on('keydown', function (e) { if (e.key === 'Enter' && !$(e.target).is('textarea, button, a')) e.preventDefault(); });
        $form.on('submit', function (e) { if (!submitting) e.preventDefault(); });
        window.addEventListener('beforeunload', function (e) { if (dirty && !submitting) { e.preventDefault(); e.returnValue = ''; } });

        applyType();
    });
    </script>
</body>
</html>