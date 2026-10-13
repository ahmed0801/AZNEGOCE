<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Créer un Avoir</title>
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

            <div class="container">
                <div class="page-inner">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                        </div>
                    @endif

                    <div class="lh-bar">
                        <h3 class="lh-title"><i class="fas fa-file-invoice-dollar text-danger"></i> Nouvel avoir vente</h3>
                        <div class="lh-btns">
                            <a href="{{ route('salesnotes.list') }}" class="btn btn-outline-secondary"><i class="fas fa-list"></i> Liste des avoirs</a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('salesnotes.store_sales_note') }}" id="noteForm" novalidate>
                        @csrf
                        <input type="hidden" name="tva_rate" id="tva_rate" value="0">
                        <input type="hidden" name="numclient" id="numclient">
                        <input type="hidden" name="source_type" id="source_type" value="">

                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3 top-row">
                                    <div class="col-lg-6">
                                        <label class="form-label" for="customer_id">Client</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            <option value="">Sélectionner un client</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                        data-tva-rate="{{ $tvaRates[$customer->id] ?? 0 }}"
                                                        data-code="{{ $customer->code ?? 'N/A' }}"
                                                        data-solde="{{ number_format((float) ($customer->solde ?? 0), 2, '.', '') }}">{{ $customer->name }} ({{ $customer->code ?? 'N/A' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <label class="form-label">TVA %</label>
                                        <input type="text" id="tva_display" class="form-control" value="0.00" readonly>
                                    </div>
                                    <div class="col-6 col-lg-4">
                                        <label class="form-label" for="note_date">Date de l'avoir</label>
                                        <input type="date" name="note_date" id="note_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div id="customer_banner" class="mt-3"></div>

                                <div class="mt-3">
                                    <label class="form-label">Origine de l'avoir</label>
                                    <div class="st-group" id="st_group">
                                        <button type="button" class="st-btn" data-type="return"><i class="fas fa-undo"></i> Retour (non facturé)</button>
                                        <button type="button" class="st-btn" data-type="invoice"><i class="fas fa-file-invoice"></i> Facture validée</button>
                                        <button type="button" class="st-btn" data-type="free"><i class="fas fa-pen"></i> Avoir libre</button>
                                    </div>
                                </div>

                                <div class="src-panel mt-3" id="source_documents_group" style="display:none">
                                    <label class="form-label" for="source_ids" id="src_label">Documents</label>
                                    <select name="source_ids[]" id="source_ids" class="form-control select2-documents" multiple></select>
                                    <div class="src-hint" id="src_hint">Choisissez d'abord un client, puis un ou plusieurs documents : leurs lignes seront ajoutées ci-dessous.</div>
                                </div>

                                <div class="src-panel mt-3" id="free_lines_actions" style="display:none">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="src-hint mb-0"><i class="fas fa-exclamation-triangle text-warning"></i> Avoir libre : l'article saisi est créé / mis à jour dans la fiche articles (désignation et prix de vente).</div>
                                        <button type="button" id="add_free_line" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus"></i> Ajouter une ligne</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-receipt"></i> Lignes de l'avoir <span class="badge bg-secondary" id="lines_count">0</span></h6>
                                <div id="lines_wrap">
                                    <table class="table table-sm table-bordered mb-0" id="lines_table">
                                        <thead>
                                            <tr>
                                                <th style="width:120px">Document</th>
                                                <th>Article</th>
                                                <th style="width:110px">Qté</th>
                                                <th style="width:115px">PU HT</th>
                                                <th style="width:95px">Remise %</th>
                                                <th style="width:70px">TVA %</th>
                                                <th style="width:110px">Total HT</th>
                                                <th style="width:110px">Total TTC</th>
                                                <th style="width:46px"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lines"></tbody>
                                    </table>
                                    <div id="lines_empty">Aucune ligne. Choisissez une origine pour commencer.</div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Notes / commentaire</label>
                                    <textarea name="notes" class="form-control" rows="2" maxlength="500" placeholder="Motif de l'avoir, remarques internes…"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="lh-actionbar">
                            <div class="ab-totals">
                                <span>Avoir HT : <b id="grandTotal">0,00</b> €</span>
                                <span>Avoir TTC : <b id="grandTotalTTC">0,00</b> €</span>
                            </div>
                            <div class="ab-btns">
                                <a href="{{ route('salesnotes.list') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Annuler</a>
                                <button type="button" id="btnSave" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer brouillon</button>
                                <button type="button" id="btnValidate" class="btn btn-success"><i class="fas fa-check-circle"></i> Valider l'avoir</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(function () {
        const tvaMap = @json($tvaRates ?? []);
        const URL_DOCS  = @json(route('salesnotes.source.documents'));
        const URL_LINES = @json(route('salesnotes.source.lines'));
        let lineIndex = 0, submitting = false, dirty = false, prevCustomer = '', linesXhr = null;

        const $form = $('#noteForm'), $lines = $('#lines'), $src = $('#source_ids');

        function esc(v) {
            return String(v == null ? '' : v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }
        function fmt(n) { return (Number(n) || 0).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
        function getTVA() { return parseFloat(tvaMap[parseInt($('#customer_id').val(), 10) || 0]) || 0; }
        function curType() { return $('#source_type').val(); }

        $('#customer_id').select2({ width: '100%' });
        $src.select2({
            width: '100%', allowClear: true, minimumInputLength: 0,
            placeholder: 'Sélectionner des documents',
            ajax: {
                url: URL_DOCS, dataType: 'json', delay: 300, cache: true,
                data: function (p) { return { term: p.term || '', source_type: curType() || '', customer_id: $('#customer_id').val() || '' }; },
                processResults: function (d) {
                    return { results: (d.documents || []).map(function (x) { return { id: x.id, text: x.numdoc + ' — ' + (x.customer_name || 'N/A') }; }) };
                }
            }
        });

        /* ---------- Totaux ---------- */
        function recalc() {
            const tva = getTVA();
            let ht = 0, n = 0;
            $lines.find('tr.line').each(function () {
                const $r = $(this);
                const off = $r.hasClass('line-off');
                const q = parseFloat($r.find('.q-in').val()) || 0;
                const p = parseFloat($r.find('.p-in').val()) || 0;
                const d = parseFloat($r.find('.r-in').val()) || 0;
                const lht = q * p * (1 - d / 100);
                $r.find('.tva_ligne').val(tva.toFixed(2));
                $r.find('.l-ht').text(fmt(lht));
                $r.find('.l-ttc').text(fmt(lht * (1 + tva / 100)));
                if (!off) { ht += lht; n++; }
            });
            $('#grandTotal').text(fmt(ht));
            $('#grandTotalTTC').text(fmt(ht * (1 + tva / 100)));
            $('#tva_rate').val(tva);
            $('#tva_display').val(tva.toFixed(2));
            $('#lines_count').text(n);
            $('#lines_empty').toggle($lines.find('tr.line').length === 0);
        }

        function emptyMsg() {
            const t = curType();
            let m = 'Choisissez une origine pour commencer.';
            if (t === 'free') m = 'Cliquez sur « Ajouter une ligne ».';
            else if (t) m = 'Sélectionnez un ou plusieurs documents pour charger leurs lignes.';
            $('#lines_empty').text(m);
        }

        /* ---------- Client ---------- */
        function banner() {
            const $o = $('#customer_id option:selected');
            if (!$o.val()) { $('#customer_banner').hide().empty(); return; }
            const solde = parseFloat($o.attr('data-solde')) || 0;
            $('#customer_banner').css('display', 'flex').html(
                '<span class="cb-name"><i class="fas fa-user"></i> ' + esc($o.text()) + '</span><span class="cb-sep">|</span>' +
                '<span><span class="cb-label">TVA :</span> <span class="cb-val">' + esc(getTVA().toFixed(2)) + ' %</span></span><span class="cb-sep">|</span>' +
                '<span><span class="cb-label">Solde actuel :</span> <span class="cb-val">' + fmt(solde) + ' €</span></span>');
        }
        function resetSources() {
            if (linesXhr) { linesXhr.abort(); linesXhr = null; }
            $src.val(null).trigger('change.select2');
            $lines.empty(); lineIndex = 0; recalc(); emptyMsg();
        }
        function applyCustomer() {
            const $o = $('#customer_id option:selected');
            $('#numclient').val($o.attr('data-code') || '');
            prevCustomer = $('#customer_id').val() || '';
            banner(); recalc();
        }
        $('#customer_id').on('change', function () {
            const nv = $(this).val() || '';
            if (nv === prevCustomer) return;
            const hasLines = $lines.find('tr.line').length > 0;
            if (!hasLines) { if (curType() !== 'free') resetSources(); applyCustomer(); return; }
            const $self = $(this);
            Swal.fire({
                icon: 'warning', title: 'Changer de client ?',
                text: curType() === 'free' ? 'Le taux de TVA va être recalculé pour toutes les lignes.' : 'Les lignes chargées seront effacées (les documents appartiennent au client précédent).',
                showCancelButton: true, confirmButtonText: 'Changer', cancelButtonText: 'Annuler'
            }).then(function (r) {
                if (r.isConfirmed) { if (curType() !== 'free') resetSources(); applyCustomer(); dirty = true; }
                else { $self.val(prevCustomer).trigger('change.select2'); }
            });
        });

        /* ---------- Type de source ---------- */
        function setType(type) {
            $('#source_type').val(type);
            $('#st_group .st-btn').removeClass('active').filter('[data-type="' + type + '"]').addClass('active');
            $lines.empty(); lineIndex = 0;
            if (linesXhr) { linesXhr.abort(); linesXhr = null; }
            $src.val(null).trigger('change.select2');
            if (type === 'free') { $('#source_documents_group').hide(); $('#free_lines_actions').show(); }
            else {
                $('#free_lines_actions').hide(); $('#source_documents_group').show();
                $('#src_label').text(type === 'return' ? 'Retours à créditer (non facturés)' : 'Factures à créditer (validées)');
            }
            recalc(); emptyMsg();
        }
        $('#st_group').on('click', '.st-btn', function () {
            const type = $(this).data('type');
            if (type === curType()) return;
            if ($lines.find('tr.line').length) {
                Swal.fire({ icon: 'warning', title: 'Changer d\'origine ?', text: 'Les lignes en cours seront effacées.', showCancelButton: true, confirmButtonText: 'Continuer', cancelButtonText: 'Annuler' })
                    .then(function (r) { if (r.isConfirmed) { setType(type); dirty = true; } });
            } else setType(type);
        });

        /* ---------- Lignes issues de documents ---------- */
        function rowSource(l, i, tva) {
            const qty = Math.abs(parseFloat(l.quantity)) || 1;
            return '<tr class="line" data-key="' + esc(l.source_id + '|' + l.article_code) + '" data-max="' + qty + '">' +
                '<td><span class="badge bg-light text-dark border">' + esc(l.source_numdoc || 'N/A') + '</span>' +
                '<input type="hidden" name="lines[' + i + '][source_id]" value="' + esc(l.source_id) + '"></td>' +
                '<td><span class="ref-code">' + esc(l.article_code) + '</span><span class="des-name">' + esc(l.description || '') + '</span>' +
                '<input type="hidden" name="lines[' + i + '][article_code]" value="' + esc(l.article_code) + '"></td>' +
                '<td><input type="number" name="lines[' + i + '][quantity]" class="form-control q-in" value="' + qty + '" min="0.01" max="' + qty + '" step="0.01">' +
                '<span class="rest-badge">max ' + qty + '</span></td>' +
                '<td><input type="number" step="0.01" min="0" name="lines[' + i + '][unit_price_ht]" class="form-control p-in" value="' + (parseFloat(l.unit_price_ht) || 0) + '"></td>' +
                '<td><input type="number" step="0.01" min="0" max="100" name="lines[' + i + '][remise]" class="form-control r-in" value="' + (parseFloat(l.remise) || 0) + '"></td>' +
                '<td><input type="text" name="lines[' + i + '][tva]" class="form-control tva_ligne" value="' + tva.toFixed(2) + '" readonly style="width:64px"></td>' +
                '<td class="l-ht">0,00</td><td class="l-ttc">0,00</td>' +
                '<td><button type="button" class="btn btn-outline-danger btn-sm rm" title="Retirer la ligne">&times;</button></td></tr>';
        }

        $src.on('select2:select select2:unselect select2:clear', function () { setTimeout(loadLines, 0); });

        function loadLines() {
            const ids = $src.val() || [], type = curType();
            if (linesXhr) { linesXhr.abort(); linesXhr = null; }
            // mémorise les saisies actuelles pour ne pas les perdre
            const keep = {};
            $lines.find('tr.line').each(function () {
                const $r = $(this);
                keep[$r.attr('data-key')] = { q: $r.find('.q-in').val(), p: $r.find('.p-in').val(), r: $r.find('.r-in').val() };
            });
            const removed = {};
            $lines.find('tr.line-off').each(function () { removed[$(this).attr('data-key')] = true; });
            if (!type || type === 'free' || !ids.length) { $lines.empty(); lineIndex = 0; recalc(); emptyMsg(); return; }
            $('#lines_empty').text('Chargement des lignes…').show();
            linesXhr = $.ajax({ url: URL_LINES, type: 'GET', dataType: 'json', data: { source_type: type, source_ids: ids },
                success: function (d) {
                    const tva = getTVA();
                    $lines.empty(); lineIndex = 0;
                    (d.lines || []).forEach(function (l) {
                        $lines.append(rowSource(l, lineIndex, tva));
                        const $r = $lines.find('tr.line').last(), k = $r.attr('data-key');
                        if (keep[k]) {
                            $r.find('.q-in').val(keep[k].q); $r.find('.p-in').val(keep[k].p); $r.find('.r-in').val(keep[k].r);
                        }
                        lineIndex++;
                    });
                    recalc(); emptyMsg(); dirty = true;
                },
                error: function (xhr, st) {
                    if (st === 'abort') return;
                    $lines.empty(); recalc();
                    $('#lines_empty').html('<span class="text-danger">Erreur de chargement des lignes.</span>').show();
                }
            });
        }

        /* ---------- Ligne libre ---------- */
        $('#add_free_line').on('click', function () {
            const i = lineIndex++, tva = getTVA();
            $lines.append('<tr class="line" data-key="free' + i + '">' +
                '<td><span class="badge bg-warning text-dark">Libre</span></td>' +
                '<td><input type="text" name="lines[' + i + '][article_code]" class="form-control mb-1 f-code" placeholder="Réf. article" style="text-align:left">' +
                '<input type="text" name="lines[' + i + '][description]" class="form-control f-desc" placeholder="Désignation" style="text-align:left"></td>' +
                '<td><input type="number" name="lines[' + i + '][quantity]" class="form-control q-in" value="1" min="0.01" step="0.01"></td>' +
                '<td><input type="number" step="0.01" min="0" name="lines[' + i + '][unit_price_ht]" class="form-control p-in" value="0"></td>' +
                '<td><input type="number" step="0.01" min="0" max="100" name="lines[' + i + '][remise]" class="form-control r-in" value="0"></td>' +
                '<td><input type="text" name="lines[' + i + '][tva]" class="form-control tva_ligne" value="' + tva.toFixed(2) + '" readonly style="width:64px"></td>' +
                '<td class="l-ht">0,00</td><td class="l-ttc">0,00</td>' +
                '<td><button type="button" class="btn btn-outline-danger btn-sm rm" title="Retirer la ligne">&times;</button></td></tr>');
            recalc(); dirty = true;
            $lines.find('tr.line').last().find('.f-code').focus();
        });

        $lines.on('input', '.q-in, .p-in, .r-in', function () {
            const $r = $(this).closest('tr'), max = parseFloat($r.attr('data-max'));
            if ($(this).hasClass('q-in') && max && parseFloat($(this).val()) > max) $(this).val(max);
            dirty = true; recalc();
        });
        $lines.on('click', '.rm', function () {
            const $r = $(this).closest('tr');
            if (curType() === 'free') $r.remove();
            else { $r.remove(); }
            dirty = true; recalc(); if (!$lines.find('tr.line').length) emptyMsg();
        });
        $('#note_date, textarea[name=notes]').on('input change', function () { dirty = true; });

        /* ---------- Contrôles + envoi ---------- */
        function validate() {
            const errs = [];
            if (!$('#customer_id').val()) errs.push('Sélectionnez un client.');
            if (!$('#note_date').val()) errs.push('La date est obligatoire.');
            const type = curType();
            if (!type) errs.push('Choisissez l\'origine de l\'avoir.');
            else if (type !== 'free' && !($src.val() || []).length) errs.push('Sélectionnez au moins un document.');
            const $rows = $lines.find('tr.line');
            if (type && !$rows.length) errs.push('Aucune ligne dans l\'avoir.');
            $rows.each(function (idx) {
                const $r = $(this), n = idx + 1, q = parseFloat($r.find('.q-in').val()) || 0, p = parseFloat($r.find('.p-in').val()) || 0, d = parseFloat($r.find('.r-in').val()) || 0, max = parseFloat($r.attr('data-max'));
                if (q <= 0) errs.push('Ligne ' + n + ' : quantité > 0 requise.');
                if (max && q > max) errs.push('Ligne ' + n + ' : quantité supérieure à celle du document (' + max + ').');
                if (p <= 0) errs.push('Ligne ' + n + ' : prix unitaire > 0 requis.');
                if (d < 0 || d > 100) errs.push('Ligne ' + n + ' : remise entre 0 et 100.');
                if (type === 'free') {
                    if (!$.trim($r.find('.f-code').val())) errs.push('Ligne ' + n + ' : référence obligatoire.');
                    if (!$.trim($r.find('.f-desc').val())) errs.push('Ligne ' + n + ' : désignation obligatoire.');
                }
            });
            return errs;
        }

        function send(action) {
            if (submitting) return;
            const errs = validate();
            if (errs.length) {
                Swal.fire({ icon: 'error', title: 'À corriger', html: errs.slice(0, 8).map(esc).join('<br>') + (errs.length > 8 ? '<br>…' : '') });
                return;
            }
            const isV = action === 'validate';
            const summary = '<div style="text-align:left">Client : <b>' + esc($('#customer_id option:selected').text()) + '</b><br>' +
                'Lignes : <b>' + $lines.find('tr.line').length + '</b><br>Total HT : <b>' + $('#grandTotal').text() + ' €</b> — TTC : <b>' + $('#grandTotalTTC').text() + ' €</b>' +
                (isV ? '<hr style="margin:8px 0"><small>La validation <b>diminue le solde du client</b> du montant TTC' +
                    (curType() === 'invoice' ? ', <b>remet les articles en stock</b>' : '') +
                    (curType() === 'return' ? ' et marque les retours comme facturés' : '') + '. Elle est définitive.</small>' : '') + '</div>';
            Swal.fire({
                icon: isV ? 'question' : 'info', title: isV ? 'Valider cet avoir ?' : 'Enregistrer en brouillon ?', html: summary,
                showCancelButton: true, confirmButtonText: isV ? 'Oui, valider' : 'Enregistrer', cancelButtonText: 'Annuler',
                confirmButtonColor: isV ? '#198754' : '#0d6efd'
            }).then(function (r) {
                if (!r.isConfirmed || submitting) return;
                submitting = true; dirty = false;
                $('#btnSave, #btnValidate').prop('disabled', true);
                $form.find('input[name=action]').remove();
                $('<input>', { type: 'hidden', name: 'action', value: action }).appendTo($form);
                $form[0].submit();
            });
        }
        $('#btnSave').on('click', function () { send('save'); });
        $('#btnValidate').on('click', function () { send('validate'); });

        // Entrée ne soumet jamais le formulaire
        $form.on('keydown', function (e) {
            if (e.key === 'Enter' && !$(e.target).is('textarea, button, a')) e.preventDefault();
        });
        $form.on('submit', function (e) { if (!submitting) e.preventDefault(); });
        window.addEventListener('beforeunload', function (e) { if (dirty && !submitting) { e.preventDefault(); e.returnValue = ''; } });

        applyCustomer(); emptyMsg(); recalc();
    });
    </script>
</body>
</html>