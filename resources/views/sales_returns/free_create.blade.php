<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Créer un Retour Vente Libre</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
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
        .card { border-radius: 12px; background: linear-gradient(135deg, #ffffff, #f8f9fa); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-header.bg-primary { background: linear-gradient(135deg, #007bff, #0056b3) !important; color: white; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f8f9fa; }
        .btn-lg { padding: 0.75rem 1.5rem; font-size: 1.1rem; }
        .form-label { font-weight: 500; }
        .total-display { font-size: 1.4rem; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper sidebar_minimize">
        <!-- Sidebar (exactement comme ta page avoir) -->
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
                        <li class="nav-item">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>

                        @if(Auth::user()->role != 'livreur')
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
                        @endif

                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#stock" aria-expanded="false">
                                <i class="fas fa-warehouse"></i><p>Stock</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="stock">
                                <ul class="nav nav-collapse">
                                    @if(Auth::user()->role != 'livreur')
                                    <li><a href="/receptions"><span class="sub-item">Réceptions</span></a></li>
                                    <li><a href="/articles"><span class="sub-item">Articles</span></a></li>
                                    @endif
                                    <li><a href="/planification-tournee"><span class="sub-item">Suivi Livraisons</span></a></li>
                                </ul>
                            </div>
                        </li>

                        @if(Auth::user()->role != 'livreur')
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

                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#parametres" aria-expanded="false">
                                <i class="fas fa-cogs"></i><p>Paramètres</p><span class="caret"></span>
                            </a>
                            <div class="collapse" id="parametres">
                                <ul class="nav nav-collapse">
                                    <li><a href="/setting"><span class="sub-item">Configuration</span></a></li>
                                </ul>
                            </div>
                        </li>

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

                        <li class="nav-item">
                            <a href="/contact">
                                <i class="fas fa-headset"></i>
                                <p>Assistance</p>
                            </a>
                        </li>
                        @endif

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

        <!-- Main Panel -->
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
                            <!-- Quick Actions -->
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <i class="fas fa-layer-group"></i>
                                </a>
                                <div class="dropdown-menu quick-actions animated fadeIn">
                                    <div class="quick-actions-header">
                                        <span class="title mb-1">Actions Rapides</span>
                                    </div>
                                    <div class="quick-actions-scroll scrollbar-outer">
                                        <div class="quick-actions-items">
                                            <div class="row m-0">
                                                <a class="col-6 col-md-4 p-0" href="/articles">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-success rounded-circle">
                                                            <i class="fas fa-sitemap"></i>
                                                        </div>
                                                        <span class="text">Articles</span>
                                                    </div>
                                                </a>
                                                <!-- ... le reste de tes quick actions ... -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Profil -->
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

            <!-- Contenu principal -->
            <!-- Contenu principal -->
            <div class="container">
                <div class="page-inner">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white border-start border-4 border-dark">
                            <h6 class="mb-1"><i class="fas fa-undo-alt me-2"></i> Créer un Retour Vente Libre (Non Facturé)</h6>
                            <!-- <h6 class="mb-0">Saisie manuelle - Non lié à un bon de livraison ou facture</h6> -->
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('delivery_notes.returns.free.store') }}">
                                @csrf

                                <div class="row mb-4 g-3">
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold fs-5">Client <span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customer_id" class="form-control form-control-lg select2" required>
                                            <option value="">← Choisir un client</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}" data-tva="{{ $tvaRates[$c->id] ?? 20 }}">
                                                    {{ $c->name }} ({{ $c->code ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold fs-5">Date du retour <span class="text-danger">*</span></label>
                                        <input type="date" name="return_date" class="form-control form-control-lg" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold fs-5">TVA appliquée</label>
                                        <div class="input-group input-group-lg">
                                            <input type="text" id="tva_display" class="form-control" value="20.00" readonly>
                                            <span class="input-group-text">%</span>
                                            <input type="hidden" name="tva_rate" id="tva_rate" value="20">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <button type="button" id="addLine" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plus-circle me-2"></i>Ajouter une ligne
                                    </button>
                                </div>

                                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-list-ul me-2"></i>Lignes du retour</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="linesTable">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th>Réf. Article</th>
                                                <th>Désignation</th>
                                                <th>Quantité</th>
                                                <th>P.U. HT</th>
                                                <th>Remise %</th>
                                                <th>Total HT</th>
                                                <th>Total TTC</th> <!-- ← Nouvelle colonne -->
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="row mt-5 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Notes / Motif du retour</label>
                                        <textarea name="notes" class="form-control" rows="4" placeholder="Raison du retour, observations, conditions particulières..."></textarea>
                                    </div>

                                    <div class="col-md-6 text-end">
                                        <div class="p-4 bg-light border rounded shadow-sm">
                                            <h4 class="mb-2">Total HT : <span id="totalHT" class="total-display text-success">0,00 €</span></h4>
                                            <h3 class="mb-0">Total TTC : <span id="totalTTC" class="total-display text-primary">0,00 €</span></h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-5">
                                    <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                        <i class="fas fa-save me-2"></i>Créer le retour libre
                                    </button>
                                    <a href="/delivery_notes/returns/list" class="btn btn-outline-secondary btn-lg px-5 py-3 ms-3">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
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

    <!-- Scripts -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function () {
        $('.select2').select2({ width: '100%' });

        let lineIndex = 0;

        $('#customer_id').on('change', function () {
            const tva = parseFloat($(this).find('option:selected').data('tva')) || 20;
            $('#tva_display').val(tva.toFixed(2));
            $('#tva_rate').val(tva);
            recalculate();
        });

        $('#addLine').on('click', function () {
            const tva = parseFloat($('#tva_rate').val()) || 20;
            const row = `
                <tr data-index="${lineIndex}">
                    <td><input type="text" name="lines[${lineIndex}][article_code]" class="form-control" placeholder="Réf article (ex: P12345)" required></td>
                    <td><input type="text" name="lines[${lineIndex}][description]" class="form-control" placeholder="Désignation complète" required></td>
                    <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty text-center" value="1" min="0.01" step="0.01" required></td>
                    <td><input type="number" name="lines[${lineIndex}][unit_price_ht]" class="form-control pu text-end" value="0.00" min="0" step="0.01" required></td>
                    <td><input type="number" name="lines[${lineIndex}][remise]" class="form-control remise text-center" value="0" min="0" max="100" step="0.1"></td>
                    <td class="text-end fw-bold total_ht">0,00 €</td>
                    <td class="text-end fw-bold total_ttc">0,00 €</td> <!-- Nouvelle colonne TTC ligne -->
                    <td><button type="button" class="btn btn-danger btn-sm remove-line">×</button></td>
                </tr>`;

            $('#linesTable tbody').append(row);
            lineIndex++;
            recalculate();
        });

        $('#linesTable').on('click', '.remove-line', function () {
            $(this).closest('tr').remove();
            recalculate();
        });

        $('#linesTable').on('input', '.qty, .pu, .remise', recalculate);

        function recalculate() {
            let totalHT = 0;
            let totalTTC = 0;
            const tvaRate = parseFloat($('#tva_rate').val()) / 100 || 0.20;

            $('#linesTable tbody tr').each(function () {
                const qty    = parseFloat($(this).find('.qty').val())    || 0;
                const pu     = parseFloat($(this).find('.pu').val())     || 0;
                const remise = parseFloat($(this).find('.remise').val()) || 0;

                const lineHT = qty * pu * (1 - remise / 100);
                const lineTTC = lineHT * (1 + tvaRate);

                totalHT += lineHT;
                totalTTC += lineTTC;

                $(this).find('.total_ht').text(lineHT.toFixed(2).replace('.', ',') + ' €');
                $(this).find('.total_ttc').text(lineTTC.toFixed(2).replace('.', ',') + ' €');
            });

            $('#totalHT').text(totalHT.toFixed(2).replace('.', ',') + ' €');
            $('#totalTTC').text(totalTTC.toFixed(2).replace('.', ',') + ' €');
        }

        recalculate();
    });
    </script>
</body>
</html>