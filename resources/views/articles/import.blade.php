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
                            <i class="fas fa-download"></i> Modèle Excel
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

                {{-- ── GUIDE D'IMPORTATION ── --}}
                <div class="step-card" id="guideCard">
                    <div class="step-header" style="cursor:pointer;" onclick="toggleGuide()">
                        <div class="step-num" style="background:#6c757d;">
                            <i class="fas fa-question" style="font-size:.75rem;"></i>
                        </div>
                        <span class="step-title">Guide d'importation &mdash; comment &ccedil;a marche ?</span>
                        <i class="fas fa-chevron-down" id="guideChevron" style="margin-left:auto; color:var(--muted); transition:transform .2s;"></i>
                    </div>
                    <div class="step-body" id="guideBody" style="display:none;">

                        {{-- Étapes visuelles --}}
                        <div style="display:-webkit-box; display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem;">

                            <div style="flex:1; min-width:200px; background:#f8f9fa; border:1.5px solid var(--border); border-radius:6px; padding:1rem; text-align:center;">
                                <div style="font-size:1.8rem; margin-bottom:.5rem;">⬇️</div>
                                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:.9rem; margin-bottom:.4rem;">1. T&eacute;l&eacute;charger le mod&egrave;le</div>
                                <div style="font-size:.78rem; color:var(--muted);">Cliquez sur <strong>"T&eacute;l&eacute;charger le Mod&egrave;le Excel"</strong> pour obtenir le fichier pr&ecirc;t &agrave; remplir avec toutes les colonnes dans le bon ordre.</div>
                            </div>

                            <div style="flex:1; min-width:200px; background:#f8f9fa; border:1.5px solid var(--border); border-radius:6px; padding:1rem; text-align:center;">
                                <div style="font-size:1.8rem; margin-bottom:.5rem;">✏️</div>
                                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:.9rem; margin-bottom:.4rem;">2. Remplir le fichier</div>
                                <div style="font-size:.78rem; color:var(--muted);">Ouvrez le fichier dans Excel. Ajoutez vos articles ligne par ligne. La premi&egrave;re ligne (ent&ecirc;tes) <strong>ne doit pas &ecirc;tre modifi&eacute;e</strong>. La colonne <strong>code</strong> est obligatoire.</div>
                            </div>

                            <div style="flex:1; min-width:200px; background:#f8f9fa; border:1.5px solid var(--border); border-radius:6px; padding:1rem; text-align:center;">
                                <div style="font-size:1.8rem; margin-bottom:.5rem;">📂</div>
                                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:.9rem; margin-bottom:.4rem;">3. Choisir le fichier</div>
                                <div style="font-size:.78rem; color:var(--muted);">Glissez votre fichier rempli dans la zone ci-dessus ou cliquez pour le s&eacute;lectionner. Un aper&ccedil;u s'affiche automatiquement.</div>
                            </div>

                            <div style="flex:1; min-width:200px; background:#f8f9fa; border:1.5px solid var(--border); border-radius:6px; padding:1rem; text-align:center;">
                                <div style="font-size:1.8rem; margin-bottom:.5rem;">⬆️</div>
                                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:.9rem; margin-bottom:.4rem;">4. Importer</div>
                                <div style="font-size:.78rem; color:var(--muted);">V&eacute;rifiez l'aper&ccedil;u, choisissez vos options puis cliquez sur <strong>"Importer"</strong>. Les nouveaux articles sont cr&eacute;&eacute;s, les existants mis &agrave; jour.</div>
                            </div>
                        </div>

                        {{-- Règles importantes --}}
                        <div style="background:#fff8f0; border:1.5px solid #f0c080; border-radius:6px; padding:.9rem 1.1rem; margin-bottom:1.2rem;">
                            <p style="font-weight:700; font-size:.82rem; color:#7a4a00; margin:0 0 .5rem;">
                                <i class="fas fa-exclamation-triangle"></i> &nbsp;R&egrave;gles importantes
                            </p>
                            <ul style="margin:0; padding-left:1.2rem; font-size:.8rem; color:#5a3a00; line-height:1.7;">
                                <li>La colonne <strong>code</strong> est <strong>obligatoire</strong> pour chaque ligne &mdash; c'est l'identifiant unique de l'article.</li>
                                <li>Si un article avec ce code <strong>existe d&eacute;j&agrave;</strong> dans la base, il sera <strong>mis &agrave; jour</strong> (ou ignor&eacute; selon l'option choisie).</li>
                                <li>Si le code n'existe <strong>pas encore</strong>, l'article sera <strong>cr&eacute;&eacute;</strong> &mdash; le champ <strong>nom</strong> devient alors obligatoire.</li>
                                <li>Les cellules <strong>vides</strong> ne modifient pas les donn&eacute;es existantes lors d'une mise &agrave; jour &mdash; seules les colonnes remplies sont &eacute;crites.</li>
                                <li>N'ajoutez pas de colonnes suppl&eacute;mentaires et ne changez pas le nom des ent&ecirc;tes.</li>
                            </ul>
                        </div>

                        {{-- Traduction des colonnes --}}
                        <p style="font-family:'Syne',sans-serif; font-weight:700; font-size:.88rem; margin-bottom:.65rem;">
                            <i class="fas fa-language"></i> &nbsp;Traduction des colonnes du fichier Excel
                        </p>
                        <div style="overflow-x:auto; border:1.5px solid var(--border); border-radius:6px;">
                            <table style="width:100%; border-collapse:collapse; font-size:.78rem;">
                                <thead>
                                    <tr style="background:var(--ink); color:#fff;">
                                        <th style="padding:.5rem .8rem; text-align:left; white-space:nowrap;">Colonne Excel</th>
                                        <th style="padding:.5rem .8rem; text-align:left; white-space:nowrap;">Signification</th>
                                        <th style="padding:.5rem .8rem; text-align:left; white-space:nowrap;">Exemple</th>
                                        <th style="padding:.5rem .8rem; text-align:left; white-space:nowrap;">Obligatoire ?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $guide = [
                                        ['col'=>'code',           'label'=>'Code article (r&eacute;f&eacute;rence interne)',     'ex'=>'57044',             'req'=>true],
                                        ['col'=>'name',           'label'=>'Nom / d&eacute;signation de l\'article',             'ex'=>'SUPPORT MOTEUR',    'req'=>'&agrave; la cr&eacute;ation'],
                                        ['col'=>'description',    'label'=>'Description d&eacute;taill&eacute;e (optionnelle)',   'ex'=>'Support avant gauche', 'req'=>false],
                                        ['col'=>'category',       'label'=>'Cat&eacute;gorie de l\'article',                     'ex'=>'LIAISON AU SOL',    'req'=>false],
                                        ['col'=>'brand',          'label'=>'Marque / fabricant',                                 'ex'=>'MULTIMARQUES',      'req'=>false],
                                        ['col'=>'unit',           'label'=>'Unit&eacute; de vente',                              'ex'=>'PIECE',             'req'=>false],
                                        ['col'=>'barcode',        'label'=>'Code-barres EAN',                                   'ex'=>'8435108604300',     'req'=>false],
                                        ['col'=>'cost_price',     'label'=>'<strong>Prix d\'achat</strong> (HT, chez le fournisseur)', 'ex'=>'141.35',   'req'=>false],
                                        ['col'=>'remise_achat',   'label'=>'Remise achat fournisseur principal (%)',             'ex'=>'5.00',              'req'=>false],
                                        ['col'=>'sale_price',     'label'=>'<strong>Prix de vente</strong> (HT, au client)',    'ex'=>'183.76',            'req'=>false],
                                        ['col'=>'tva_group',      'label'=>'Groupe de TVA applicable',                          'ex'=>'ASSUJ (20.00%)',    'req'=>false],
                                        ['col'=>'stock_min',      'label'=>'Seuil de stock minimum (alerte)',                   'ex'=>'2',                 'req'=>false],
                                        ['col'=>'stock_max',      'label'=>'Capacit&eacute; maximale en stock',                 'ex'=>'50',                'req'=>false],
                                        ['col'=>'store',          'label'=>'D&eacute;p&ocirc;t / magasin de stockage',          'ex'=>'MAGASIN CENTRALE',  'req'=>false],
                                        ['col'=>'location',       'label'=>'Emplacement dans le d&eacute;p&ocirc;t',            'ex'=>'A-12-3',            'req'=>false],
                                        ['col'=>'is_active',      'label'=>'Article actif ? (1 = oui, 0 = non)',                'ex'=>'1',                 'req'=>false],
                                        ['col'=>'supplier',       'label'=>'<strong>Nom du fournisseur principal</strong>',     'ex'=>'Exa - EXADIS',      'req'=>false],
                                        ['col'=>'supplier_2',     'label'=>'Nom du 2&egrave;me fournisseur (optionnel)',         'ex'=>'LKQ FRANCE',        'req'=>false],
                                        ['col'=>'cost_price_2',   'label'=>'Prix d\'achat chez le fournisseur 2',               'ex'=>'138.00',            'req'=>false],
                                        ['col'=>'remise_achat_2', 'label'=>'Remise achat fournisseur 2 (%)',                    'ex'=>'3.00',              'req'=>false],
                                        ['col'=>'supplier_3',     'label'=>'Nom du 3&egrave;me fournisseur (optionnel)',         'ex'=>'AD PARTS',          'req'=>false],
                                        ['col'=>'cost_price_3',   'label'=>'Prix d\'achat chez le fournisseur 3',               'ex'=>'145.00',            'req'=>false],
                                        ['col'=>'remise_achat_3', 'label'=>'Remise achat fournisseur 3 (%)',                    'ex'=>'0.00',              'req'=>false],
                                        ['col'=>'discount_group', 'label'=>'Groupe de remise client',                           'ex'=>'R0',                'req'=>false],
                                        ['col'=>'Poids',          'label'=>'Poids de l\'article (en kg)',                       'ex'=>'1.25',              'req'=>false],
                                        ['col'=>'Hauteur',        'label'=>'Hauteur (en cm)',                                   'ex'=>'15',                'req'=>false],
                                        ['col'=>'Longueur',       'label'=>'Longueur (en cm)',                                  'ex'=>'30',                'req'=>false],
                                        ['col'=>'Largeur',        'label'=>'Largeur (en cm)',                                   'ex'=>'10',                'req'=>false],
                                        ['col'=>'Ref_TecDoc',     'label'=>'R&eacute;f&eacute;rence TecDoc (catalogue pi&egrave;ces)', 'ex'=>'TEC123456', 'req'=>false],
                                        ['col'=>'Code_pays',      'label'=>'Code pays d\'origine',                             'ex'=>'FR',                'req'=>false],
                                        ['col'=>'Code_douane',    'label'=>'Code douanier (nomenclature)',                      'ex'=>'87089900',          'req'=>false],
                                    ];
                                    @endphp
                                    @foreach($guide as $i => $g)
                                    <tr style="background: {{ $i % 2 === 0 ? '#fff' : '#fafaf8' }};">
                                        <td style="padding:.4rem .8rem; font-family:'IBM Plex Mono',monospace; font-weight:600; color:var(--accent); white-space:nowrap;">{{ $g['col'] }}</td>
                                        <td style="padding:.4rem .8rem;">{!! $g['label'] !!}</td>
                                        <td style="padding:.4rem .8rem; color:var(--muted); font-family:'IBM Plex Mono',monospace;">{{ $g['ex'] }}</td>
                                        <td style="padding:.4rem .8rem; text-align:center;">
                                            @if($g['req'] === true)
                                                <span style="background:#fdecea; color:var(--err); font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:3px;">
                                                    <i class="fas fa-asterisk"></i> Oui
                                                </span>
                                            @elseif($g['req'] !== false)
                                                <span style="background:#fff8e1; color:#c07a10; font-size:.7rem; font-weight:700; padding:2px 7px; border-radius:3px;">
                                                    {{ $g['req'] }}
                                                </span>
                                            @else
                                                <span style="background:#eee; color:#888; font-size:.7rem; padding:2px 7px; border-radius:3px;">Non</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                            {{-- Colonnes générales --}}
                            <div class="col-grid" id="colGrid">
                                @php
                                $generalCols = [
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
                                @foreach($generalCols as $c)
                                <label class="col-chip checked" id="chip_{{ $c['key'] }}">
                                    <input type="checkbox" name="selected_columns[]" value="{{ $c['key'] }}" checked
                                           onchange="updateChip(this, '{{ $c['key'] }}')">
                                    <i class="fas {{ $c['icon'] }}"></i> {{ $c['label'] }}
                                </label>
                                @endforeach
                            </div>

                            {{-- Groupe Fournisseur 1 --}}
                            <div class="supplier-group-header" style="background:#dce6f1; border-radius:4px; padding:.35rem .75rem; margin-top:.9rem; display:-webkit-box; display:flex; -webkit-box-align:center; align-items:center; gap:.5rem; font-size:.75rem; font-weight:700; color:#1a3a5c;">
                                <i class="fas fa-truck"></i> Fournisseur principal
                                <button type="button" onclick="toggleGroup('grp1')" style="margin-left:auto; font-size:.68rem; border:1px solid #9bb8d4; border-radius:3px; padding:1px 8px; background:none; cursor:pointer; color:#1a3a5c;">
                                    tout cocher / d&eacute;cocher
                                </button>
                            </div>
                            <div class="col-grid" id="grp1" style="margin-top:.4rem;">
                                <label class="col-chip checked" id="chip_supplier">
                                    <input type="checkbox" name="selected_columns[]" value="supplier" checked onchange="updateChip(this,'supplier')">
                                    <i class="fas fa-truck"></i> Fournisseur 1
                                </label>
                                <label class="col-chip checked" id="chip_remise_achat">
                                    <input type="checkbox" name="selected_columns[]" value="remise_achat" checked onchange="updateChip(this,'remise_achat')">
                                    <i class="fas fa-percent"></i> Remise achat %
                                </label>
                            </div>

                            {{-- Groupe Fournisseur 2 --}}
                            <div class="supplier-group-header" style="background:#e2efda; border-radius:4px; padding:.35rem .75rem; margin-top:.75rem; display:-webkit-box; display:flex; -webkit-box-align:center; align-items:center; gap:.5rem; font-size:.75rem; font-weight:700; color:#1a3a1a;">
                                <i class="fas fa-truck"></i> Fournisseur 2 <span style="font-weight:400; opacity:.7;">(optionnel)</span>
                                <button type="button" onclick="toggleGroup('grp2')" style="margin-left:auto; font-size:.68rem; border:1px solid #9bc49b; border-radius:3px; padding:1px 8px; background:none; cursor:pointer; color:#1a3a1a;">
                                    tout cocher / d&eacute;cocher
                                </button>
                            </div>
                            <div class="col-grid" id="grp2" style="margin-top:.4rem;">
                                <label class="col-chip checked" id="chip_supplier_2">
                                    <input type="checkbox" name="selected_columns[]" value="supplier_2" checked onchange="updateChip(this,'supplier_2')">
                                    <i class="fas fa-truck"></i> Fournisseur 2
                                </label>
                                <label class="col-chip checked" id="chip_cost_price_2">
                                    <input type="checkbox" name="selected_columns[]" value="cost_price_2" checked onchange="updateChip(this,'cost_price_2')">
                                    <i class="fas fa-euro-sign"></i> Prix achat Fourn.2
                                </label>
                                <label class="col-chip checked" id="chip_remise_achat_2">
                                    <input type="checkbox" name="selected_columns[]" value="remise_achat_2" checked onchange="updateChip(this,'remise_achat_2')">
                                    <i class="fas fa-percent"></i> Remise Fourn.2 %
                                </label>
                            </div>

                            {{-- Groupe Fournisseur 3 --}}
                            <div class="supplier-group-header" style="background:#fce4d6; border-radius:4px; padding:.35rem .75rem; margin-top:.75rem; display:-webkit-box; display:flex; -webkit-box-align:center; align-items:center; gap:.5rem; font-size:.75rem; font-weight:700; color:#5c2a1a;">
                                <i class="fas fa-truck"></i> Fournisseur 3 <span style="font-weight:400; opacity:.7;">(optionnel)</span>
                                <button type="button" onclick="toggleGroup('grp3')" style="margin-left:auto; font-size:.68rem; border:1px solid #d4a090; border-radius:3px; padding:1px 8px; background:none; cursor:pointer; color:#5c2a1a;">
                                    tout cocher / d&eacute;cocher
                                </button>
                            </div>
                            <div class="col-grid" id="grp3" style="margin-top:.4rem;">
                                <label class="col-chip checked" id="chip_supplier_3">
                                    <input type="checkbox" name="selected_columns[]" value="supplier_3" checked onchange="updateChip(this,'supplier_3')">
                                    <i class="fas fa-truck"></i> Fournisseur 3
                                </label>
                                <label class="col-chip checked" id="chip_cost_price_3">
                                    <input type="checkbox" name="selected_columns[]" value="cost_price_3" checked onchange="updateChip(this,'cost_price_3')">
                                    <i class="fas fa-euro-sign"></i> Prix achat Fourn.3
                                </label>
                                <label class="col-chip checked" id="chip_remise_achat_3">
                                    <input type="checkbox" name="selected_columns[]" value="remise_achat_3" checked onchange="updateChip(this,'remise_achat_3')">
                                    <i class="fas fa-percent"></i> Remise Fourn.3 %
                                </label>
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
// ── Guide toggle ──
function toggleGuide() {
    var body     = document.getElementById('guideBody');
    var chevron  = document.getElementById('guideChevron');
    var isOpen   = body.style.display !== 'none';
    body.style.display    = isOpen ? 'none' : 'block';
    chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}

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
    var cbs = document.querySelectorAll('#columnSelector input[type=checkbox]');
    for (var i = 0; i < cbs.length; i++) { cbs[i].checked = true; updateChip(cbs[i], cbs[i].value); }
}
function uncheckAll() {
    var cbs = document.querySelectorAll('#columnSelector input[type=checkbox]');
    for (var i = 0; i < cbs.length; i++) { cbs[i].checked = false; updateChip(cbs[i], cbs[i].value); }
}
function toggleGroup(gridId) {
    var cbs = document.querySelectorAll('#' + gridId + ' input[type=checkbox]');
    // si tous cochés → décocher, sinon → tout cocher
    var allChecked = true;
    for (var i = 0; i < cbs.length; i++) { if (!cbs[i].checked) { allChecked = false; break; } }
    for (var j = 0; j < cbs.length; j++) { cbs[j].checked = !allChecked; updateChip(cbs[j], cbs[j].value); }
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