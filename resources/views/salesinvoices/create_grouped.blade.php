<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <!-- jQuery + Bootstrap JS (v4) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        #panierDropdown + .dropdown-menu {
            width: 900px;
            min-width: 350px;
            padding: 10px;
            border-radius: 8px;
        }
        .panier-dropdown {
            width: 100%;
            min-width: 350px;
        }
        .panier-dropdown .notif-item {
            padding: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .dropdown-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .notif-scroll {
            padding: 10px;
        }
        .notif-center {
            padding: 5px 0;
        }
        .dropdown-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
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
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
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
                    <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
                </div>
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item"><a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item active"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/avoirs"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
                        <li class="nav-item"><a href="/reglement-client"><i class="fas fa-credit-card"></i><p>Règlement Client</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                        <li class="nav-item"><a href="/reglement-fournisseur"><i class="fas fa-credit-card"></i><p>Règlement Fournisseur</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-warehouse"></i></span><h4 class="text-section">Stock</h4></li>
                        <li class="nav-item"><a href="/receptions"><i class="fas fa-truck-loading"></i><p>Réceptions</p></a></li>
                        <li class="nav-item"><a href="/articles"><i class="fas fa-cubes"></i><p>Articles</p></a></li>
                        <li class="nav-item"><a href="/planification-tournee"><i class="fas fa-truck"></i><p>Suivi Livraisons</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fa fa-users"></i></span><h4 class="text-section">Référentiel</h4></li>
                        <li class="nav-item"><a href="/customers"><i class="fa fa-user"></i><p>Clients</p></a></li>
                        <li class="nav-item"><a href="/suppliers"><i class="fa fa-user-tie"></i><p>Fournisseurs</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-cogs"></i></span><h4 class="text-section">Paramètres</h4></li>
                        <li class="nav-item"><a href="/setting"><i class="fas fa-sliders-h"></i><p>Paramètres</p></a></li>
                        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-database"></i><p>TecDoc</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-robot"></i></span><h4 class="text-section">Autres</h4></li>
                        <li class="nav-item"><a href="/voice"><i class="fas fa-robot"></i><p>NEGOBOT</p></a></li>
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Créer une Facture Groupée</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('salesinvoices.store_grouped') }}">
                                @csrf
                                <input type="hidden" name="tva_rate" id="tva_rate" value="0">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Client</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            <option value="">Sélectionnez un client</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" data-code="{{ $customer->code }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">TVA %</label>
                                        <input type="text" id="tva_display" class="form-control" readonly value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date de Facture</label>
                                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bons de Livraison et Retours</label>
                                    <select name="documents[]" id="documents" class="form-control select2-documents" multiple required></select>
                                </div>
                                <h6 class="mt-4 mb-2">Lignes Sélectionnées</h6>
                                <table class="table table-bordered" id="lines-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document</th>
                                            <th>Article</th>
                                            <th>Qté</th>
                                            <th>PU HT</th>
                                            <th>Remise %</th>
                                            <th>TVA %</th>
                                            <th>Total HT</th>
                                            <th>Total TTC</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lines"></tbody>
                                </table>
                                <div class="row align-items-center mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de facturation, etc."></textarea>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="p-3 bg-light border rounded shadow-sm">
                                            <h5 class="mb-1">Total HT : <span id="grandTotal" class="text-success fw-bold">0.00</span> €</h5>
                                            <h6 class="mb-0">Total TTC : <span id="grandTotalTTC" class="text-danger fw-bold">0.00</span> €</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="action" value="save" class="btn btn-primary px-4">✅ Enregistrer Brouillon</button>
                                    <button type="submit" name="action" value="validate" class="btn btn-success px-4 ms-2">✔️ Valider la Facture</button>
                                    <a href="{{ route('salesinvoices.index') }}" class="btn btn-danger px-4 ms-2">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        © AZ NEGOCE. All Rights Reserved.
                    </div>
                    <div>
                        by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Core JS Files -->
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
    <script>
        let lineIndex = 0;
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });
            $('.select2-documents').select2({
                ajax: {
                    url: "{{ route('sales.orders.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        const customer = $('#customer_id').find(':selected');
                        return {
                            term: params.term || '',
                            customer_id: customer.val() || '',
                            customer_code: customer.data('code') || ''
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: `${item.type}_${item.id}`,
                                text: `${item.type === 'delivery' ? 'BL' : 'Ret'} #${item.numdoc} - ${item.customer_name} (${new Date(item.order_date).toLocaleDateString()})`,
                                type: item.type,
                                lines: item.lines,
                                tva_rate: item.tva_rate
                            }))
                        };
                    },
                    cache: true
                },
                placeholder: 'Sélectionner des bons de livraison ou retours',
                minimumInputLength: 0,
                width: '100%'
            });
            function getTVA() {
                const selectedItems = $('.select2-documents').select2('data');
                if (selectedItems.length === 0) return 0;
                const tvaRates = selectedItems.map(item => parseFloat(item.tva_rate));
                if (new Set(tvaRates).size > 1) {
                    alert('Erreur : Les documents sélectionnés ont des taux de TVA différents.');
                    $('.select2-documents').val(null).trigger('change');
                    $('#lines').empty();
                    return 0;
                }
                return tvaRates[0] || 0;
            }
            function recalculate() {
                let totalHT = 0;
                const tva = getTVA();
                $('#lines tr').each(function () {
                    const qty = parseFloat($(this).find('.qty').val()) || 0;
                    const pu = parseFloat($(this).find('.pu').val()) || 0;
                    const remise = parseFloat($(this).find('.remise').val()) || 0;
                    const lineHT = qty * pu * (1 - remise / 100);
                    const lineTTC = lineHT * (1 + tva / 100);
                    $(this).find('.tva_ligne').val(tva.toFixed(2));
                    $(this).find('.total').val(lineHT.toFixed(2));
                    $(this).find('.totalttc').val(lineTTC.toFixed(2));
                    totalHT += lineHT;
                });
                const totalTTC = totalHT * (1 + tva / 100);
                $('#grandTotal').text(totalHT.toFixed(2));
                $('#grandTotalTTC').text(totalTTC.toFixed(2));
                $('#tva_rate').val(tva);
            }
            $('#customer_id').on('change', function () {
                $('#tva_display').val(0);
                $('#tva_rate').val(0);
                $('.select2-documents').val(null).trigger('change');
                $('#lines').empty();
                recalculate();
            });
            $('.select2-documents').on('change', function () {
                $('#lines').empty();
                lineIndex = 0;
                const selectedItems = $(this).select2('data');
                const tva = getTVA();
                $('#tva_display').val(tva.toFixed(2));
                $('#tva_rate').val(tva);
                selectedItems.forEach(item => {
                    item.lines.forEach(line => {
                        const qty = line.ordered_quantity;
                        const lineHT = qty * line.unit_price_ht * (1 - (line.remise || 0) / 100);
                        const lineTTC = lineHT * (1 + tva / 100);
                        const row = `
                            <tr>
                                <td><input type="text" value="${item.text}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][${item.type === 'delivery' ? 'delivery_note_id' : 'sales_return_id'}]" value="${item.id.replace(/^(delivery|return)_/, '')}">
                                </td>
                                <td><input type="text" value="${line.article_code} - ${line.item_name || '-'}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][article_code]" value="${line.article_code}">
                                </td>
                                <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" value="${qty}" required></td>
                                <td><input type="number" step="0.01" name="lines[${lineIndex}][unit_price_ht]" class="form-control pu" value="${line.unit_price_ht}" required></td>
                                <td><input type="number" step="0.01" name="lines[${lineIndex}][remise]" class="form-control remise" value="${line.remise || 0}"></td>
                                <td><input type="text" name="lines[${lineIndex}][tva]" class="form-control tva_ligne" value="${tva.toFixed(2)}" readonly></td>
                                <td><input type="text" class="form-control total" value="${lineHT.toFixed(2)}" readonly></td>
                                <td><input type="text" class="form-control totalttc" value="${lineTTC.toFixed(2)}" readonly></td>
                            </tr>`;
                        $('#lines').append(row);
                        lineIndex++;
                    });
                });
                recalculate();
            });
            $('#lines').on('input', '.qty, .pu, .remise', recalculate);
            $('#customer_id').trigger('change');
        });
    </script>
</body>
</html>