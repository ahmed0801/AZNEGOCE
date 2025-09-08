
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP - Créer un avoir groupé</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- jQuery + Bootstrap JS (v4) -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.4/dist/select2-bootstrap4.min.css" rel="stylesheet" />

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
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item">
                            <a href="/dashboard">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/commande">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Nouvelle Commande</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/orders">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <p>Mes BL</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/listdevis">
                                <i class="fas fa-file-alt"></i>
                                <p>Mes Devis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/listbrouillon">
                                <i class="fas fa-reply-all"></i>
                                <p>Brouillons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/invoices">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Mes Factures</p>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a href="/avoirs">
                                <i class="fas fa-reply-all"></i>
                                <p>Mes Avoirs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/purchases/list">
                                <i class="fas fa-file-alt"></i>
                                <p>Commandes Achat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/returns">
                                <i class="fas fa-file-alt"></i>
                                <p>Retours Achat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/invoices">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Factures Achat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/receptions">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Réception</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/articles">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Articles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/customers">
                                <i class="fa fa-user"></i>
                                <p>Clients</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/suppliers">
                                <i class="fa fa-user"></i>
                                <p>Fournisseurs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/setting">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Paramétres</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tecdoc">
                                <i class="fas fa-cogs"></i>
                                <p>TecDoc</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <p>Déconnexion</p>
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
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
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

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Créer un avoir groupé</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('notes.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="groupée">
                                <input type="hidden" name="tva_rate" id="tva_rate" value="0">

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Fournisseur</label>
                                        <select name="supplier_id" class="form-control select2" required>
                                            <option value="">Sélectionner un fournisseur</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">TVA %</label>
                                        <input type="text" id="tva_display" class="form-control" readonly value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date de l’avoir</label>
                                        <input type="date" name="credit_note_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Commandes et retours à inclure</label>
                                    <select name="orders[]" class="form-control select2-orders" multiple required></select>
                                </div>

                                <h6 class="mt-4 mb-2">Lignes de l’avoir</h6>
                                <table class="table table-bordered" id="linesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Commande/Retour</th>
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
                                        <label class="form-label">Notes / Commentaire</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de facturation, etc."></textarea>
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
                                    <button type="submit" name="action" value="validate" class="btn btn-success px-4 ms-2">✔️ Valider l’Avoir</button>
                                    <a href="{{ route('notes.list') }}" class="btn btn-outline-secondary px-4 ms-2">Annuler</a>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let lineIndex = 0;

        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap4'
            });

            $('.select2-orders').select2({
                ajax: {
                    url: "{{ route('purchases.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        const data = {
                            term: params.term || '',
                            supplier_id: $('select[name="supplier_id"]').val() || '',
                            status: 'validée'
                        };
                        console.log('AJAX data:', data);
                        return data;
                    },
                    processResults: function (data) {
                        console.log('AJAX response:', data);
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: `${item.type === 'order' ? 'Commande' : 'Retour'} #${item.numdoc} - ${item.supplier_name} (${new Date(item.order_date || item.return_date).toLocaleDateString()})`,
                                type: item.type,
                                lines: item.lines,
                                tva_rate: item.tva_rate
                            }))
                        };
                    },
                    cache: true
                },
                placeholder: 'Sélectionner des commandes ou retours validés',
                minimumInputLength: 0,
                width: '100%',
                theme: 'bootstrap4'
            });

            function getTVA() {
                const selectedItems = $('.select2-orders').select2('data');
                if (selectedItems.length === 0) return 0;
                const tvaRates = selectedItems.map(item => parseFloat(item.tva_rate));
                if (new Set(tvaRates).size > 1) {
                    alert('Erreur : Les commandes et retours sélectionnés ont des taux de TVA différents.');
                    $('.select2-orders').val(null).trigger('change');
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

            $('select[name="supplier_id"]').on('change', function () {
                console.log('Fournisseur changé:', $(this).val());
                $('#tva_display').val(0);
                $('#tva_rate').val(0);
                $('.select2-orders').val(null).trigger('change');
                $('#lines').empty();
                recalculate();
            });

            $('.select2-orders').on('change', function () {
                $('#lines').empty();
                lineIndex = 0;
                const selectedItems = $(this).select2('data');
                const tva = getTVA();
                $('#tva_display').val(tva.toFixed(2));
                $('#tva_rate').val(tva);

                selectedItems.forEach(item => {
                    item.lines.forEach(line => {
                        const quantity = item.type === 'return' ? -(line.returned_quantity || line.ordered_quantity) : line.ordered_quantity;
                        const lineHT = quantity * line.unit_price_ht * (1 - (line.remise || 0) / 100);
                        const lineTTC = lineHT * (1 + tva / 100);
                        const row = `
                            <tr>
                                <td><input type="text" value="${item.text}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][${item.type === 'order' ? 'purchase_order_id' : 'purchase_return_id'}]" value="${item.id}">
                                </td>
                                <td><input type="text" value="${line.article_code} - ${line.item_name || '-'}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][article_code]" value="${line.article_code}">
                                </td>
                                <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" value="${quantity}" ${item.type === 'return' ? 'min="' + quantity + '" max="0"' : ''} required></td>
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

            $('#lines').on('input', '.qty, .pu, .remise', function () {
                const qtyInput = $(this).closest('tr').find('.qty');
                const min = parseFloat(qtyInput.attr('min')) || -Infinity;
                const max = parseFloat(qtyInput.attr('max')) || Infinity;
                const qty = parseFloat(qtyInput.val()) || 0;
                if (qty < min || qty > max) {
                    qtyInput.val(min);
                    alert('La quantité doit être dans la plage autorisée.');
                }
                recalculate();
            });

            $('select[name="supplier_id"]').trigger('change');
        });
    </script>
</body>
</html>
