<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP – Import Articles</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    {{-- ✅ FONTS & ICONS : identique à l'ancienne vue --}}
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

    {{-- ✅ CSS : identique à l'ancienne vue --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    {{-- Google Fonts pour le style de la page import --}}
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink:    #0d0d0d;
            --paper:  #f5f4ef;
            --accent: #e84c1e;
            --muted:  #8a8880;
            --border: #d4d2ca;
            --card:   #ffffff;
            --new:    #1a8a4a;
            --upd:    #c07a10;
            --err:    #c0200d;
            --new-bg: #edf7f1;
            --upd-bg: #fdf4e3;
            --err-bg: #fdecea;
        }

        /* ── HEADER STRIP ── */
        .import-header {
            background: var(--ink);
            color: #fff;
            padding: 1.1rem 2rem;
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            gap: 1rem;
            border-bottom: 3px solid var(--accent);
        }
        .import-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .import-header .badge-tag {
            background: var(--accent);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .back-btn {
            margin-left: auto;
            background: transparent;
            border: 1.5px solid rgba(255,255,255,.3);
            color: #fff;
            padding: 5px 14px;
            border-radius: 4px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: border-color .2s;
        }
        .back-btn:hover { border-color: #fff; color: #fff; text-decoration: none; }

        /* ── MAIN LAYOUT ── */
        .import-body { max-width: 1060px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }

        /* ── STEP CARDS ── */
        .step-card {
            background: var(--card);
            border: 1.5px solid var(--border);
            border-radius: 6px;
            margin-bottom: 1.4rem;
            overflow: hidden;
        }
        .step-header {
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            gap: .75rem;
            padding: .9rem 1.3rem;
            border-bottom: 1.5px solid var(--border);
            background: #fafaf8;
        }
        .step-num {
            width: 28px; height: 28px;
            background: var(--ink);
            color: #fff;
            border-radius: 50%;
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: .85rem;
            font-weight: 800;
            flex-shrink: 0;
        }
        .step-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; }
        .step-body { padding: 1.3rem; }

        /* ── FILE ZONE ── */
        .file-zone {
            border: 2px dashed var(--border);
            border-radius: 6px;
            padding: 2.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafaf8;
            position: relative;
        }
        .file-zone:hover, .file-zone.dragover { border-color: var(--accent); background: #fff8f6; }
        .file-zone input[type=file] {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .file-icon { font-size: 2.2rem; margin-bottom: .5rem; }
        .file-zone p { margin: 0; color: var(--muted); font-size: .85rem; }
        .file-zone strong { color: var(--ink); }
        #fileNameDisplay { margin-top: .75rem; font-size: .82rem; color: var(--accent); font-weight: 600; display: none; }

        /* ── OPTIONS ROW ── */
        .options-row {
            display: -webkit-box;
            display: flex;
            gap: 1rem;
            -webkit-box-align: end;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 1.2rem;
        }
        .opt-group label.opt-label {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; color: var(--muted); display: block; margin-bottom: .3rem;
        }
        .radio-group { display: -webkit-box; display: flex; gap: .5rem; }
        .radio-btn {
            border: 1.5px solid var(--border);
            border-radius: 4px;
            padding: .3rem .75rem;
            font-size: .8rem;
            cursor: pointer;
            transition: all .15s;
            user-select: none;
        }
        .radio-btn.active { border-color: var(--ink); background: var(--ink); color: #fff; }

        /* ── COLUMN SELECTOR ── */
        #columnSelector { display: none; }
        .col-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: .5rem;
        }
        .col-chip {
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            gap: .5rem;
            padding: .45rem .7rem;
            border: 1.5px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            transition: all .15s;
            font-size: .8rem;
            user-select: none;
            margin: 0;
        }
        .col-chip input[type=checkbox] { width: 15px; height: 15px; cursor: pointer; margin: 0; }
        .col-chip:hover { border-color: #bbb; background: #fafaf8; }
        .col-chip.checked { border-color: var(--accent); background: #fff4f1; color: var(--accent); font-weight: 600; }
        .col-chip i { width: 14px; text-align: center; }

        .chip-actions { display: -webkit-box; display: flex; gap: .5rem; margin-bottom: .75rem; }
        .chip-action-btn {
            font-size: .72rem; padding: .2rem .6rem;
            border: 1.5px solid var(--border); border-radius: 3px;
            background: none; cursor: pointer; transition: all .15s;
        }
        .chip-action-btn:hover { border-color: var(--ink); background: var(--ink); color: #fff; }

        /* ── PREVIEW TABLE ── */
        #previewArea { display: none; margin-top: 1rem; }
        .preview-stats { display: -webkit-box; display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .pstat {
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            gap: .35rem;
            padding: .3rem .7rem; border-radius: 4px; font-size: .78rem; font-weight: 700;
        }
        .pstat.new  { background: var(--new-bg); color: var(--new); }
        .pstat.upd  { background: var(--upd-bg); color: var(--upd); }
        .pstat.err  { background: var(--err-bg); color: var(--err); }
        .pstat.tot  { background: #eee; color: var(--ink); }

        .preview-table-wrap { overflow-x: auto; border: 1.5px solid var(--border); border-radius: 6px; }
        .preview-table { width: 100%; border-collapse: collapse; font-size: .76rem; }
        .preview-table th {
            background: var(--ink); color: #fff;
            padding: .5rem .75rem; text-align: left;
            white-space: nowrap; font-weight: 600;
        }
        .preview-table td { padding: .4rem .75rem; border-bottom: 1px solid #eee; white-space: nowrap; vertical-align: middle; }
        .preview-table tr:last-child td { border-bottom: none; }
        .preview-table tr:hover td { background: #fafaf8; }

        .status-badge {
            display: -webkit-inline-box;
            display: inline-flex;
            -webkit-box-align: center;
            align-items: center;
            gap: .3rem;
            padding: .15rem .5rem; border-radius: 3px; font-size: .7rem; font-weight: 700; white-space: nowrap;
        }
        .status-badge.new  { background: var(--new-bg); color: var(--new); }
        .status-badge.upd  { background: var(--upd-bg); color: var(--upd); }
        .status-badge.err  { background: var(--err-bg); color: var(--err); }

        /* ── SUBMIT BTN ── */
        .submit-btn {
            background: var(--accent); color: #fff; border: none;
            padding: .75rem 2rem; border-radius: 5px;
            font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: all .2s;
            display: -webkit-inline-box;
            display: inline-flex;
            -webkit-box-align: center;
            align-items: center;
            gap: .5rem;
        }
        .submit-btn:hover { background: #c73a10; }
        .submit-btn:disabled { background: #ccc; cursor: not-allowed; }

        /* ── ALERT ── */
        .az-alert { padding: .9rem 1.1rem; border-radius: 5px; margin-bottom: 1rem; font-size: .87rem; border-left: 4px solid; }
        .az-alert.success { background: var(--new-bg); border-color: var(--new); color: #0f5c30; }
        .az-alert.error   { background: var(--err-bg); border-color: var(--err); color: #7a1010; }

        /* ── DOWNLOAD BTN ── */
        .dl-btn {
            display: -webkit-inline-box;
            display: inline-flex;
            -webkit-box-align: center;
            align-items: center;
            gap: .5rem;
            padding: .5rem 1.1rem; border: 1.5px solid var(--border); border-radius: 4px;
            background: #fff; font-size: .82rem; color: var(--ink); text-decoration: none; transition: all .15s;
        }
        .dl-btn:hover { border-color: var(--ink); background: var(--ink); color: #fff; text-decoration: none; }

        /* ── ERRORS LIST ── */
        .errors-list { background: var(--err-bg); border: 1.5px solid #f0b8b3; border-radius: 5px; padding: .75rem 1rem; margin-top: .75rem; }
        .errors-list p { margin: 0 0 .4rem; font-weight: 700; color: var(--err); font-size: .83rem; }
        .errors-list ul { margin: 0; padding-left: 1.2rem; font-size: .8rem; color: #7a1010; }

        .more-rows { text-align: center; padding: .6rem; background: #fafaf8; color: var(--muted); font-size: .75rem; border-top: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="wrapper sidebar_minimize">

    <!-- ✅ SIDEBAR : identique à l'ancienne vue -->
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <div class="logo-header" data-background-color="dark">
                <a href="/" class="logo">
                    <img src="{{ asset('assets/img/logop.png') }}" alt="navbar brand" class="navbar-brand" height="40" />
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

                    <li class="nav-item">
                        <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="collapse" href="#ventes" aria-expanded="false">
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
                        <a data-toggle="collapse" href="#achats" aria-expanded="false">
                            <i class="fas fa-shopping-bag"></i><p>Achats</p><span class="caret"></span>
                        </a>
                        <div class="collapse" id="achats">
                            <ul class="nav nav-collapse">
                                <li><a href="/purchases/list"><span class="sub-item">Commandes</span></a></li>
                                <li><a href="/purchaseprojects/list"><span class="sub-item">Projets d'Achat</span></a></li>
                                <li><a href="/returns"><span class="sub-item">Retours</span></a></li>
                                <li><a href="/invoices"><span class="sub-item">Factures</span></a></li>
                                <li><a href="/notes"><span class="sub-item">Avoirs</span></a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="collapse" href="#compta" aria-expanded="false">
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
                        <a data-toggle="collapse" href="#stock" aria-expanded="false">
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
                        <a data-toggle="collapse" href="#referentiel" aria-expanded="false">
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
                        <a data-toggle="collapse" href="#parametres" aria-expanded="false">
                            <i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span>
                        </a>
                        <div class="collapse" id="parametres">
                            <ul class="nav nav-collapse">
                                <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a data-toggle="collapse" href="#outils" aria-expanded="false">
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

                    <li class="nav-item">
                        <a href="/contact">
                            <i class="fas fa-headset"></i><p>Assistance</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('logout.admin') }}" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->

    <div class="main-panel">

        <!-- ✅ NAVBAR DU HAUT : colle ici ta navbar exacte depuis ta page Articles -->
        <div class="main-header">
            <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                        {{-- Colle ici le contenu exact de ta navbar (Quick Actions, Profil, Panier, etc.) --}}
                    </ul>
                </div>
            </nav>
        </div>

        <!-- ── IMPORT HEADER BARRE ── -->
        <div class="import-header">
            <div class="step-num" style="background:var(--accent)">
                <i class="fas fa-upload" style="font-size:.75rem;"></i>
            </div>
            <h1>Import Articles</h1>
            <span class="badge-tag">Excel</span>
            <a href="/articles" class="back-btn">
                <i class="fas fa-arrow-left"></i> Articles
            </a>
        </div>

        <div class="import-body">

            @if(session('success'))
                <div class="az-alert success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="az-alert error">
                    <i class="fas fa-times-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form id="importForm" action="{{ route('articles.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ── ÉTAPE 1 : Fichier ── --}}
                <div class="step-card">
                    <div class="step-header">
                        <div class="step-num">1</div>
                        <span class="step-title">Choisir le fichier Excel</span>
                        <a href="{{ route('articles.import.template') }}" class="dl-btn" style="margin-left:auto;">
                            <i class="fas fa-download"></i> télecharger le Modèle Excel
                        </a>
                        <a href="/articles" class="dl-btn" style="margin-left:auto;">
                            ←  Aller a la page articles
                        </a>

                    </div>
                    <div class="step-body">
                        <div class="file-zone" id="fileZone">
                            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" required>
                            <div class="file-icon">
                                <i class="fas fa-file-excel" style="color:#1a8a4a;"></i>
                            </div>
                            <p><strong>Glissez votre fichier</strong> ou cliquez pour parcourir</p>
                            <p>.xlsx &middot; .xls &middot; .csv &middot; max 10 Mo</p>
                            <div id="fileNameDisplay">
                                <i class="fas fa-file"></i> <span id="fileNameText"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── ÉTAPE 2 : Options ── --}}
                <div class="step-card">
                    <div class="step-header">
                        <div class="step-num">2</div>
                        <span class="step-title">Options d'importation</span>
                    </div>
                    <div class="step-body">
                        <div class="options-row">

                            <div class="opt-group">
                                <label class="opt-label">Articles existants</label>
                                <div class="radio-group">
                                    <div class="radio-btn active" data-val="update" onclick="setMode(this,'update_mode')">
                                        <i class="fas fa-pencil-alt"></i> Mettre &agrave; jour
                                    </div>
                                    <div class="radio-btn" data-val="skip" onclick="setMode(this,'update_mode')">
                                        <i class="fas fa-forward"></i> Ignorer
                                    </div>
                                </div>
                                <input type="hidden" name="update_mode" id="update_mode" value="update">
                            </div>

                            <div class="opt-group">
                                <label class="opt-label">Colonnes &agrave; traiter</label>
                                <div class="radio-group">
                                    <div class="radio-btn active" data-val="all" onclick="setColMode(this,'all')">
                                        <i class="fas fa-check-double"></i> Toutes
                                    </div>
                                    <div class="radio-btn" data-val="selected" onclick="setColMode(this,'selected')">
                                        <i class="fas fa-sliders-h"></i> S&eacute;lection
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="columnSelector">
                            <div class="chip-actions">
                                <button type="button" class="chip-action-btn" onclick="checkAll()">
                                    <i class="fas fa-check"></i> Tout cocher
                                </button>
                                <button type="button" class="chip-action-btn" onclick="uncheckAll()">
                                    <i class="fas fa-times"></i> Tout d&eacute;cocher
                                </button>
                            </div>
                            <div class="col-grid" id="colGrid">
                                @php
                                $colDefs = [
                                    ['key'=>'name',          'label'=>'Nom',           'icon'=>'fa-tag'],
                                    ['key'=>'description',   'label'=>'Description',   'icon'=>'fa-align-left'],
                                    ['key'=>'category',      'label'=>'Catégorie',     'icon'=>'fa-folder'],
                                    ['key'=>'brand',         'label'=>'Marque',        'icon'=>'fa-bookmark'],
                                    ['key'=>'unit',          'label'=>'Unité',         'icon'=>'fa-ruler'],
                                    ['key'=>'barcode',       'label'=>'Code-barre',    'icon'=>'fa-barcode'],
                                    ['key'=>'cost_price',    'label'=>'Prix achat',    'icon'=>'fa-euro-sign'],
                                    ['key'=>'sale_price',    'label'=>'Prix vente',    'icon'=>'fa-tag'],
                                    ['key'=>'tva_group',     'label'=>'Groupe TVA',    'icon'=>'fa-percent'],
                                    ['key'=>'stock_min',     'label'=>'Stock min',     'icon'=>'fa-arrow-down'],
                                    ['key'=>'stock_max',     'label'=>'Stock max',     'icon'=>'fa-arrow-up'],
                                    ['key'=>'store',         'label'=>'Magasin',       'icon'=>'fa-store'],
                                    ['key'=>'location',      'label'=>'Emplacement',   'icon'=>'fa-map-marker-alt'],
                                    ['key'=>'is_active',     'label'=>'Actif',         'icon'=>'fa-toggle-on'],
                                    ['key'=>'supplier',      'label'=>'Fournisseur',   'icon'=>'fa-truck'],
                                    ['key'=>'discount_group','label'=>'Groupe remise', 'icon'=>'fa-percent'],
                                    ['key'=>'poids',         'label'=>'Poids',         'icon'=>'fa-weight'],
                                    ['key'=>'hauteur',       'label'=>'Hauteur',       'icon'=>'fa-arrows-alt-v'],
                                    ['key'=>'longueur',      'label'=>'Longueur',      'icon'=>'fa-arrows-alt-h'],
                                    ['key'=>'largeur',       'label'=>'Largeur',       'icon'=>'fa-arrows-alt-h'],
                                    ['key'=>'ref_tecdoc',    'label'=>'Réf TecDoc',    'icon'=>'fa-wrench'],
                                    ['key'=>'code_pays',     'label'=>'Code pays',     'icon'=>'fa-globe'],
                                    ['key'=>'code_douane',   'label'=>'Code douane',   'icon'=>'fa-box'],
                                ];
                                @endphp
                                @foreach($colDefs as $c)
                                <label class="col-chip checked" id="chip_{{ $c['key'] }}">
                                    <input type="checkbox" name="selected_columns[]" value="{{ $c['key'] }}" checked
                                           onchange="updateChip(this, '{{ $c['key'] }}')">
                                    <i class="fas {{ $c['icon'] }}"></i>
                                    {{ $c['label'] }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── ÉTAPE 3 : Aperçu ── --}}
                <div class="step-card">
                    <div class="step-header">
                        <div class="step-num">3</div>
                        <span class="step-title">Aper&ccedil;u des donn&eacute;es</span>
                        <span id="previewLoading" style="display:none; margin-left:auto; font-size:.8rem; color:var(--muted);">
                            <i class="fas fa-spinner fa-spin"></i> Analyse en cours&hellip;
                        </span>
                    </div>
                    <div class="step-body" style="padding-top:.5rem">
                        <div id="previewArea">
                            <div class="preview-stats" id="previewStats"></div>
                            <div class="preview-table-wrap">
                                <table class="preview-table">
                                    <thead id="previewHead"></thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
                                <div class="more-rows" id="moreRows" style="display:none"></div>
                            </div>
                            <div id="previewErrors"></div>
                        </div>
                        <p id="previewPlaceholder" style="color:var(--muted); font-size:.85rem; margin:0;">
                            <i class="fas fa-info-circle"></i>
                            S&eacute;lectionnez un fichier pour voir l'aper&ccedil;u.
                        </p>
                    </div>
                </div>

                {{-- ── SUBMIT ── --}}
                <div style="display:-webkit-box; display:flex; -webkit-box-align:center; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <button type="submit" class="submit-btn" id="submitBtn" disabled>
                        <i class="fas fa-upload" id="submitIcon"></i>
                        <span id="submitLabel">Importer</span>
                    </button>
                    <span style="font-size:.78rem; color:var(--muted);" id="submitHint">
                        <i class="fas fa-info-circle"></i>
                        Chargez un fichier valide pour activer l'import.
                    </span>
                </div>

            </form>
        </div>

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <div class="copyright">&copy; AZ NEGOCE. All Rights Reserved.</div>
                <div>by <a target="_blank" href="#">AZ NEGOCE</a>.</div>
            </div>
        </footer>

    </div>
</div>

{{-- ✅ SCRIPTS : même ordre exact que l'ancienne vue --}}
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

<script>
// ── Radio buttons ──
function setMode(el, inputId) {
    var group = el.parentNode.querySelectorAll('.radio-btn');
    for (var i = 0; i < group.length; i++) group[i].classList.remove('active');
    el.classList.add('active');
    document.getElementById(inputId).value = el.getAttribute('data-val');
}

function setColMode(el, mode) {
    var group = el.parentNode.querySelectorAll('.radio-btn');
    for (var i = 0; i < group.length; i++) group[i].classList.remove('active');
    el.classList.add('active');
    document.getElementById('columnSelector').style.display = (mode === 'selected') ? 'block' : 'none';
}

function checkAll() {
    var cbs = document.querySelectorAll('#colGrid input[type=checkbox]');
    for (var i = 0; i < cbs.length; i++) { cbs[i].checked = true; updateChip(cbs[i], cbs[i].value); }
}
function uncheckAll() {
    var cbs = document.querySelectorAll('#colGrid input[type=checkbox]');
    for (var i = 0; i < cbs.length; i++) { cbs[i].checked = false; updateChip(cbs[i], cbs[i].value); }
}
function updateChip(cb, key) {
    var chip = document.getElementById('chip_' + key);
    if (!chip) return;
    if (cb.checked) chip.classList.add('checked');
    else chip.classList.remove('checked');
}

// ── File upload ──
var fileInput  = document.getElementById('fileInput');
var fileZone   = document.getElementById('fileZone');
var submitBtn  = document.getElementById('submitBtn');

fileZone.addEventListener('dragover', function(e) {
    e.preventDefault(); fileZone.classList.add('dragover');
});
fileZone.addEventListener('dragleave', function() {
    fileZone.classList.remove('dragover');
});
fileZone.addEventListener('drop', function(e) {
    e.preventDefault(); fileZone.classList.remove('dragover');
    if (e.dataTransfer.files[0]) {
        fileInput.files = e.dataTransfer.files;
        handleFile(e.dataTransfer.files[0]);
    }
});
fileInput.addEventListener('change', function() {
    if (this.files[0]) handleFile(this.files[0]);
});

function handleFile(file) {
    var display = document.getElementById('fileNameDisplay');
    document.getElementById('fileNameText').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
    display.style.display = 'block';
    loadPreview(file);
}

function loadPreview(file) {
    var loading = document.getElementById('previewLoading');
    var area    = document.getElementById('previewArea');
    var pholder = document.getElementById('previewPlaceholder');

    loading.style.display = 'inline-flex';
    area.style.display    = 'none';
    pholder.style.display = 'none';
    submitBtn.disabled    = true;

    var fd = new FormData();
    fd.append('file', file);
    fd.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("articles.import.preview") }}', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            loading.style.display = 'none';
            if (data.error) {
                pholder.innerHTML = '<i class="fas fa-times-circle" style="color:var(--err)"></i> ' + data.error;
                pholder.style.display = 'block';
                return;
            }
            renderPreview(data);
            submitBtn.disabled = false;
            document.getElementById('submitHint').innerHTML =
                '<i class="fas fa-info-circle"></i> ' + data.total_rows + ' ligne(s) d&eacute;tect&eacute;e(s) dans le fichier.';
        })
        .catch(function() {
            loading.style.display = 'none';
            pholder.innerHTML = '<i class="fas fa-times-circle" style="color:var(--err)"></i> Erreur de chargement. V&eacute;rifiez le format du fichier.';
            pholder.style.display = 'block';
        });
}

function renderPreview(data) {
    var rows    = data.preview || [];
    var headers = data.headers || [];
    var total   = data.total_rows || rows.length;
    var i;

    var nNew = 0, nUpd = 0, nErr = 0;
    for (i = 0; i < rows.length; i++) {
        if (rows[i].status === 'new')    nNew++;
        if (rows[i].status === 'update') nUpd++;
        if (rows[i].status === 'error')  nErr++;
    }

    document.getElementById('previewStats').innerHTML =
        '<div class="pstat tot"><i class="fas fa-list"></i> ' + total + ' ligne(s)</div>' +
        '<div class="pstat new"><i class="fas fa-plus-circle"></i> ' + nNew + ' nouveau(x)</div>' +
        '<div class="pstat upd"><i class="fas fa-edit"></i> ' + nUpd + ' mise(s) &agrave; jour</div>' +
        (nErr ? '<div class="pstat err"><i class="fas fa-exclamation-circle"></i> ' + nErr + ' erreur(s)</div>' : '');

    var displayCols = headers.length
        ? ['status'].concat(headers.filter(function(h) { return h !== 'status'; }).slice(0, 12))
        : ['status', 'code', 'name', 'brand', 'category', 'cost_price', 'sale_price'];

    var headHtml = '<tr>';
    for (i = 0; i < displayCols.length; i++) {
        headHtml += '<th>' + displayCols[i].replace(/_/g, ' ') + '</th>';
    }
    headHtml += '</tr>';
    document.getElementById('previewHead').innerHTML = headHtml;

    var bodyHtml = '';
    for (var r = 0; r < rows.length; r++) {
        var row = rows[r];
        var st  = row.status;
        var label = st === 'new'
            ? '<i class="fas fa-plus-circle"></i> Nouveau'
            : (st === 'update'
                ? '<i class="fas fa-edit"></i> Mise &agrave; jour'
                : '<i class="fas fa-times-circle"></i> Erreur');
        bodyHtml += '<tr>';
        for (var c = 0; c < displayCols.length; c++) {
            if (displayCols[c] === 'status') {
                bodyHtml += '<td><span class="status-badge ' + st + '">' + label + '</span></td>';
            } else {
                var v = (row[displayCols[c]] !== undefined && row[displayCols[c]] !== null)
                    ? String(row[displayCols[c]]).substring(0, 40) : '';
                bodyHtml += '<td>' + v + '</td>';
            }
        }
        bodyHtml += '</tr>';
    }
    document.getElementById('previewBody').innerHTML = bodyHtml;

    var more = document.getElementById('moreRows');
    if (total > rows.length) {
        more.style.display = 'block';
        more.innerHTML = '<i class="fas fa-ellipsis-h"></i> ' + (total - rows.length) + ' ligne(s) suppl&eacute;mentaire(s) non affich&eacute;es';
    } else {
        more.style.display = 'none';
    }

    var errDiv = document.getElementById('previewErrors');
    if (data.errors && data.errors.length) {
        var errHtml = '<div class="errors-list"><p><i class="fas fa-exclamation-triangle"></i> Probl&egrave;mes d&eacute;tect&eacute;s :</p><ul>';
        for (var e = 0; e < data.errors.length; e++) {
            errHtml += '<li>' + data.errors[e] + '</li>';
        }
        errHtml += '</ul></div>';
        errDiv.innerHTML = errHtml;
    } else {
        errDiv.innerHTML = '';
    }

    document.getElementById('previewArea').style.display = 'block';
}

// ── Submit spinner ──
document.getElementById('importForm').addEventListener('submit', function() {
    submitBtn.disabled = true;
    document.getElementById('submitIcon').className = 'fas fa-spinner fa-spin';
    document.getElementById('submitLabel').textContent = 'Importation en cours\u2026';
});
</script>
</body>
</html>