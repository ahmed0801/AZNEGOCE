<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Modifier la Facture #{{ $invoice->numdoc }}</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- jQuery + Bootstrap JS -->
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        #documents + .select2-container .select2-selection--multiple {
            width: 900px;
            min-width: 350px;
            padding: 10px;
            border-radius: 8px;
        }
        .select2-documents {
            width: 100%;
            min-width: 350px;
        }
        .select2-results__option {
            padding: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .select2-results__group {
            font-weight: bold;
            margin-bottom: 10px;
            padding: 10px;
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
        .btn-primary, .btn-success, .btn-danger {
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .btn-danger:hover {
            background-color: #c82333;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
        }
        .form-label {
            font-weight: 500;
            color: #343a40;
        }
        .total-ligne-ht, .total-ligne-ttc {
            display: inline-block;
            width: 100%;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar (unchanged from previous) -->
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
                        <li class="nav-item"><a href="/commande"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item active"><a href="/invoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
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
                                                    <a href="/setting" class="btn btn-xs btn-secondary btn-sm">Paramétres</a>
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

                    <div class="card mt-4">
                        <div class="card-header bg-white border-start border-4 border-primary">
                            <h3>📝 Modifier la Facture #{{ $invoice->numdoc }}</h3>
                            <h6 class="text-muted">Type: {{ ucfirst($invoice->type ?? 'N/A') }}</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('salesinvoices.update', $invoice->numdoc) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="{{ $invoice->type }}">
                                <input type="hidden" name="tva_rate" id="tva_rate" value="{{ $invoice->tva_rate ?? 0 }}">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="customer_id">Client</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" data-code="{{ $customer->code ?? 'N/A' }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} ({{ $customer->code ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="numclient" id="numclient" value="{{ $invoice->customer->code ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">TVA %</label>
                                        <input type="text" id="tva_display" class="form-control" value="{{ $invoice->tva_rate ?? 0 }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="invoice_date">Date de Facture</label>
                                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ $invoice->invoice_date->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                @if($invoice->type !== 'libre')
                                    <div class="mb-3">
                                        <label class="form-label">Bons de Livraison et Retours</label>
                                        <select name="documents[]" id="documents" class="form-control select2-documents" multiple></select>
                                    </div>
                                @endif
                                <h6 class="fw-bold mb-3">🧾 Lignes de la Facture</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle" id="lines-table">
                                        <thead class="table-light text-center">
                                            <tr>
                                                @if($invoice->type !== 'libre')
                                                    <th>Document</th>
                                                    <th>Article</th>
                                                @else
                                                    <th>Description</th>
                                                @endif
                                                <th>Qté</th>
                                                <th>PU HT</th>
                                                <th>Remise (%)</th>
                                                <th>TVA (%)</th>
                                                <th>Total HT</th>
                                                <th>Total TTC</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lines">
                                            @foreach($invoice->lines as $index => $line)
                                                <tr>
                                                    @if($invoice->type !== 'libre')
                                                        <td>
                                                            <input type="text" value="{{ $line->delivery_note_id ? 'Bon de Livraison #' . ($line->deliveryNote->numdoc ?? '-') : ($line->sales_return_id ? 'Retour Vente #' . ($line->salesReturn->numdoc ?? '-') : '-') }}" class="form-control" readonly>
                                                            <input type="hidden" name="lines[{{ $index }}][delivery_note_id]" value="{{ $line->delivery_note_id }}">
                                                            <input type="hidden" name="lines[{{ $index }}][sales_return_id]" value="{{ $line->sales_return_id }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ $line->article_code }} - {{ $line->item ? $line->item->name : ($line->description ?? '-') }}" class="form-control" readonly>
                                                            <input type="hidden" name="lines[{{ $index }}][article_code]" value="{{ $line->article_code }}">
                                                        </td>
                                                    @else
                                                        <td><input type="text" name="lines[{{ $index }}][description]" class="form-control description" value="{{ $line->description ?? ($line->item->name ?? '') }}" required></td>
                                                    @endif
                                                    <td><input type="number" name="lines[{{ $index }}][quantity]" class="form-control qty" value="{{ $line->quantity }}" required></td>
                                                    <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ht]" class="form-control pu" value="{{ $line->unit_price_ht }}" min="0" required></td>
                                                    <td><input type="number" step="0.01" name="lines[{{ $index }}][remise]" class="form-control remise" value="{{ $line->remise ?? 0 }}" min="0" max="100"></td>
                                                    <td><input type="text" name="lines[{{ $index }}][tva]" class="form-control tva_ligne" value="{{ $line->tva ?? ($invoice->tva_rate ?? 0) }}" readonly></td>
                                                    <td><input type="text" class="form-control total" value="{{ number_format($line->total_ligne_ht ?? ($line->quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100)), 2, ',', ' ') }}" readonly></td>
                                                    <td><input type="text" class="form-control totalttc" value="{{ number_format($line->total_ligne_ttc ?? ($line->quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100) * (1 + ($line->tva ?? $invoice->tva_rate ?? 0) / 100)), 2, ',', ' ') }}" readonly></td>
                                                    <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($invoice->type === 'libre')
                                    <div class="mb-3 text-end">
                                        <button type="button" class="btn btn-outline-secondary" id="addLine">+ Ajouter une Ligne</button>
                                    </div>
                                @endif
                                <div class="row align-items-center mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Notes / Commentaire</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de facturation, etc.">{{ $invoice->notes }}</textarea>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="p-3 bg-light border rounded shadow-sm">
                                            <h5 class="mb-1">Total HT : <span id="grandTotal" class="text-success fw-bold">{{ number_format($invoice->total_ht, 2, ',', ' ') }}</span> €</h5>
                                            <h6 class="mb-0">Total TTC : <span id="grandTotalTTC" class="text-danger fw-bold">{{ number_format($invoice->total_ttc, 2, ',', ' ') }}</span> €</h6>
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
                    <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
                    <div>by <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.</div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <script>
        let lineIndex = {{ count($invoice->lines) }};
        const invoiceType = '{{ $invoice->type }}';
        const tvaMap = {!! json_encode($tvaRates ?? []) !!};

        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });

            if (invoiceType !== 'libre') {
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
                                customer_code: customer.data('code') || '',
                                type: invoiceType === 'direct' ? 'delivery' : 'all'
                            };
                        },
                        processResults: function (data) {
                            console.log('Search Response:', data);
                            return {
                                results: data.map(item => ({
                                    id: `${item.type}_${item.id}`,
                                    text: `${item.type === 'delivery' ? 'Bon de Livraison' : 'Retour Vente'} #${item.numdoc} - ${item.customer_name} (${new Date(item.order_date).toLocaleDateString('fr-FR')})`,
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
            }

            function getTVA() {
                if (invoiceType === 'libre') {
                    const customerId = parseInt($('select[name="customer_id"]').val());
                    return parseFloat(tvaMap[customerId]) || {{ $invoice->tva_rate ?? 0 }};
                }
                const selectedItems = $('.select2-documents').select2('data');
                if (selectedItems.length === 0) return parseFloat(tvaMap[$('#customer_id').val()]) || {{ $invoice->tva_rate ?? 0 }};
                const tvaRates = selectedItems.map(item => parseFloat(item.tva_rate));
                if (new Set(tvaRates).size > 1) {
                    alert('Erreur : Les documents sélectionnés ont des taux de TVA différents.');
                    $('.select2-documents').val(null).trigger('change');
                    $('#lines').empty();
                    return {{ $invoice->tva_rate ?? 0 }};
                }
                return tvaRates[0] || {{ $invoice->tva_rate ?? 0 }};
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
                    $(this).find('.total').val(lineHT.toLocaleString('fr-FR', { minimumFractionDigits: 2 }));
                    $(this).find('.totalttc').val(lineTTC.toLocaleString('fr-FR', { minimumFractionDigits: 2 }));
                    totalHT += lineHT;
                });
                const totalTTC = totalHT * (1 + tva / 100);
                $('#grandTotal').text(totalHT.toLocaleString('fr-FR', { minimumFractionDigits: 2 }));
                $('#grandTotalTTC').text(totalTTC.toLocaleString('fr-FR', { minimumFractionDigits: 2 }));
                $('#tva_rate').val(tva);
                $('#tva_display').val(tva.toFixed(2));
            }

            $('#customer_id').on('change', function () {
                const selectedOption = $(this).find('option:selected');
                const numclient = selectedOption.data('code') || '';
                $('#numclient').val(numclient);
                if (invoiceType !== 'libre') {
                    $('.select2-documents').val(null).trigger('change');
                }
                recalculate();
            });

            $('#lines').on('input', '.qty, .pu, .remise', recalculate);

            if (invoiceType === 'libre') {
                $('#addLine').click(function () {
                    const tva = getTVA();
                    const newRow = `
                        <tr>
                            <td><input type="text" name="lines[${lineIndex}][description]" class="form-control description" required placeholder="Ex: Service de maintenance"></td>
                            <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" value="1"  required></td>
                            <td><input type="number" step="0.01" name="lines[${lineIndex}][unit_price_ht]" class="form-control pu" value="0" min="0" required></td>
                            <td><input type="number" step="0.01" name="lines[${lineIndex}][remise]" class="form-control remise" value="0" min="0" max="100"></td>
                            <td><input type="text" name="lines[${lineIndex}][tva]" class="form-control tva_ligne" value="${tva.toFixed(2)}" readonly></td>
                            <td><input type="text" class="form-control total" value="0,00" readonly></td>
                            <td><input type="text" class="form-control totalttc" value="0,00" readonly></td>
                            <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                        </tr>`;
                    $('#lines').append(newRow);
                    lineIndex++;
                    recalculate();
                });
            }

            $('#lines').on('click', '.remove-line', function () {
                $(this).closest('tr').remove();
                recalculate();
            });

            $('.select2-documents').on('change', function () {
                if (invoiceType === 'libre') return;
                $('#lines').empty();
                lineIndex = 0;
                const selectedItems = $(this).select2('data');
                console.log('Selected Items:', selectedItems);
                const tva = getTVA();
                $('#tva_display').val(tva.toFixed(2));
                $('#tva_rate').val(tva);
                selectedItems.forEach(item => {
                    item.lines.forEach(line => {
                        const qty = line.quantity || line.ordered_quantity || line.delivered_quantity || 1;
                        const lineHT = qty * line.unit_price_ht * (1 - (line.remise || 0) / 100);
                        const lineTTC = lineHT * (1 + tva / 100);
                        const row = `
                            <tr>
                                <td>
                                    <input type="text" value="${item.text}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][${item.type === 'delivery' ? 'delivery_note_id' : 'sales_return_id'}]" value="${item.id.replace(/^(delivery|return)_/, '')}">
                                </td>
                                <td>
                                    <input type="text" value="${line.article_code} - ${line.item_name || line.description || '-'}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][article_code]" value="${line.article_code}">
                                </td>
                                <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" value="${qty}" required></td>
                                <td><input type="number" step="0.01" name="lines[${lineIndex}][unit_price_ht]" class="form-control pu" value="${line.unit_price_ht}" min="0" required></td>
                                <td><input type="number" step="0.01" name="lines[${lineIndex}][remise]" class="form-control remise" value="${line.remise || 0}" min="0" max="100"></td>
                                <td><input type="text" name="lines[${lineIndex}][tva]" class="form-control tva_ligne" value="${tva.toFixed(2)}" readonly></td>
                                <td><input type="text" class="form-control total" value="${lineHT.toLocaleString('fr-FR', { minimumFractionDigits: 2 })}" readonly></td>
                                <td><input type="text" class="form-control totalttc" value="${lineTTC.toLocaleString('fr-FR', { minimumFractionDigits: 2 })}" readonly></td>
                                <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                            </tr>`;
                        $('#lines').append(row);
                        lineIndex++;
                    });
                });
                recalculate();
            });

            // Initialize existing documents and lines
            @if($invoice->type !== 'libre')
                const preSelectedDocs = [];
                const existingLines = [];
                @foreach($invoice->lines as $index => $line)
                    @if($line->delivery_note_id || $line->sales_return_id)
                        preSelectedDocs.push('{{ $line->delivery_note_id ? "delivery_{$line->delivery_note_id}" : "return_{$line->sales_return_id}" }}');
                        existingLines.push({
                            index: {{ $index }},
                            delivery_note_id: '{{ $line->delivery_note_id }}',
                            sales_return_id: '{{ $line->sales_return_id }}',
                            article_code: '{{ $line->article_code }}',
                            description: '{{ $line->item ? $line->item->name : ($line->description ?? '-') }}',
                            quantity: {{ $line->quantity }},
                            unit_price_ht: {{ $line->unit_price_ht }},
                            remise: {{ $line->remise ?? 0 }},
                            tva: {{ $line->tva ?? ($invoice->tva_rate ?? 0) }},
                            total_ligne_ht: {{ $line->total_ligne_ht ?? ($line->quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100)) }},
                            total_ligne_ttc: {{ $line->total_ligne_ttc ?? ($line->quantity * $line->unit_price_ht * (1 - ($line->remise ?? 0) / 100) * (1 + ($line->tva ?? $invoice->tva_rate ?? 0) / 100)) }}
                        });
                    @endif
                @endforeach
                if (preSelectedDocs.length > 0) {
                    // Fetch document details to initialize Select2
                    $.ajax({
                        url: "{{ route('sales.orders.search') }}",
                        data: {
                            customer_id: $('#customer_id').val(),
                            customer_code: $('#customer_id').find(':selected').data('code') || '',
                            type: invoiceType === 'direct' ? 'delivery' : 'all'
                        },
                        dataType: 'json',
                        success: function (data) {
                            const select2Data = data.map(item => ({
                                id: `${item.type}_${item.id}`,
                                text: `${item.type === 'delivery' ? 'Bon de Livraison' : 'Retour Vente'} #${item.numdoc} - ${item.customer_name} (${new Date(item.order_date).toLocaleDateString('fr-FR')})`,
                                type: item.type,
                                lines: item.lines,
                                tva_rate: item.tva_rate
                            }));
                            $('.select2-documents').select2('data', select2Data.filter(item => preSelectedDocs.includes(item.id)));
                            $('.select2-documents').val(preSelectedDocs).trigger('change');

                            // Ensure existing lines are preserved
                            $('#lines').empty();
                            existingLines.forEach(line => {
                                const docText = line.delivery_note_id ? `Bon de Livraison #${line.delivery_note_id}` : `Retour Vente #${line.sales_return_id}`;
                                const row = `
                                    <tr>
                                        <td>
                                            <input type="text" value="${docText}" class="form-control" readonly>
                                            <input type="hidden" name="lines[${line.index}][delivery_note_id]" value="${line.delivery_note_id || ''}">
                                            <input type="hidden" name="lines[${line.index}][sales_return_id]" value="${line.sales_return_id || ''}">
                                        </td>
                                        <td>
                                            <input type="text" value="${line.article_code} - ${line.description}" class="form-control" readonly>
                                            <input type="hidden" name="lines[${line.index}][article_code]" value="${line.article_code}">
                                        </td>
                                        <td><input type="number" name="lines[${line.index}][quantity]" class="form-control qty" value="${line.quantity}"  required></td>
                                        <td><input type="number" step="0.01" name="lines[${line.index}][unit_price_ht]" class="form-control pu" value="${line.unit_price_ht}" min="0" required></td>
                                        <td><input type="number" step="0.01" name="lines[${line.index}][remise]" class="form-control remise" value="${line.remise}" min="0" max="100"></td>
                                        <td><input type="text" name="lines[${line.index}][tva]" class="form-control tva_ligne" value="${line.tva.toFixed(2)}" readonly></td>
                                        <td><input type="text" class="form-control total" value="${line.total_ligne_ht.toLocaleString('fr-FR', { minimumFractionDigits: 2 })}" readonly></td>
                                        <td><input type="text" class="form-control totalttc" value="${line.total_ligne_ttc.toLocaleString('fr-FR', { minimumFractionDigits: 2 })}" readonly></td>
                                        <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                                    </tr>`;
                                $('#lines').append(row);
                            });
                            recalculate();
                        }
                    });
                } else {
                    recalculate();
                }
            @else
                recalculate();
            @endif

            $('#customer_id').trigger('change');
        });
    </script>
</body>
</html>