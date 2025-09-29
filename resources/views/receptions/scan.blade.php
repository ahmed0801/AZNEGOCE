<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>AZ ERP - Réception Achat</title>
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon">
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
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
        .table {
            width: 100%;
            margin-bottom: 0;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            font-size: 1rem;
            padding: 8px;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .scan-section {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 10px;
            margin-top: 10px;
            text-align: center;
            display: none;
        }
        .scan-section.active {
            display: block;
        }
        .scan-input {
            width: 100%;
            max-width: 320px;
            height: 50px;
            font-size: 1.2rem;
            margin: auto;
            border-radius: 5px;
        }
        .scan-btn {
            font-size: 1.2rem;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            margin-top: 10px;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 5px;
            display: none;
        }
        .progress {
            height: 10px;
            margin: 5px 0;
        }
        .completed {
            background-color: #d4edda;
        }
        .article-details {
            margin-top: 10px;
            padding-left: 20px;
        }
        .article-details li {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        @media (max-width: 576px) {
            .card-body {
                padding: 1rem;
            }
            .card h3 {
                font-size: 1.5rem;
            }
            .scan-input {
                font-size: 1rem;
                height: 45px;
            }
            .scan-btn {
                font-size: 1rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
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
                            <li><a href="/sales"><span class="sub-item">Devis & Précommandes</span></a></li>
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
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card card-round {{ $reception->status === 'reçu' ? 'completed' : '' }}">
                        <div class="card-header">
                            <div class="card-title">Scanner réception #{{ $reception->id }} (Commande : {{ $reception->purchaseOrder->numdoc }})</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>{{ \Carbon\Carbon::parse($reception->reception_date)->format('d/m/Y') }}</h6>
                                <strong>Fournisseur :</strong>
                                @if ($reception->purchaseOrder->supplier)
                                    {{ $reception->purchaseOrder->supplier->code }} - {{ $reception->purchaseOrder->supplier->name }}<br>
                                    {{ $reception->purchaseOrder->supplier->address_delivery ?? $reception->purchaseOrder->supplier->address ?? 'Adresse inconnue' }}
                                @else
                                    Fournisseur inconnu
                                @endif
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary scan-toggle"><i class="fas fa-barcode"></i> Scanner avec PDA</button>
                                <div class="scan-section">
                                    <form class="scan-form" action="{{ route('receptions.scan.update', $reception->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="reception_id" value="{{ $reception->id }}">
                                        <div class="form-group mb-3">
                                            <input type="text" name="code_article" class="form-control scan-input" placeholder="Scanner le code barre" required>
                                            <div class="error-message"></div>
                                        </div>
                                        <textarea hidden name="notes" class="form-control" placeholder="Notes"></textarea>
                                        <button type="submit" class="btn btn-primary scan-btn">Scanner</button>
                                    </form>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($reception->total_received / ($reception->purchaseOrder->lines->sum('ordered_quantity') ?: 1)) * 100 }}%;" aria-valuenow="{{ $reception->total_received }}" aria-valuemin="0" aria-valuemax="{{ $reception->purchaseOrder->lines->sum('ordered_quantity') ?: 1 }}"></div>
                            </div>
                            <small>Scanné : {{ $reception->total_received }} / {{ $reception->purchaseOrder->lines->sum('ordered_quantity') ?: 1 }}</small>
                            <ul class="article-details">
                                @foreach ($reception->lines as $line)
                                    <li class="{{ $line->received_quantity >= ($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0) ? 'completed' : '' }}" data-code="{{ $line->article_code }}">
                                        {{ $line->article_code }} - {{ $line->item->name ?? 'Non disponible' }} - Qté à recevoir : {{ $reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0 }} (Scannée : {{ $line->received_quantity }})
                                    </li>
                                @endforeach
                            </ul>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-striped article-table">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Article</th>
                                            <th>Demandée</th>
                                            <th>Scannée</th>
                                            <th>Restant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reception->lines as $line)
                                            <tr data-code="{{ $line->article_code }}" class="{{ $line->received_quantity >= ($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0) ? 'completed' : '' }}">
                                                <td>{{ $line->article_code }}</td>
                                                <td>{{ $line->item->name ?? 'Non disponible' }}</td>
                                                <td>{{ $reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0 }}</td>
                                                <td>{{ $line->received_quantity }}</td>
                                                <td>{{ max(0, ($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0) - $line->received_quantity) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('receptions.show', $reception->id) }}" class="btn btn-secondary">Annuler</a>
                                <a href="{{ route('receptions.generate_pdf', $reception->id) }}" class="btn btn-info" target="_blank">📄 Générer PDF</a>
                            </div>
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

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize article quantities
            window.articleQuantities = @json($articleQuantities);

            // Play beep sound on successful scan
            const beepSound = new Audio('https://www.soundjay.com/buttons/beep-01a.mp3');

            // Toggle scan section
            $('.scan-toggle').on('click', function () {
                const scanSection = $('.scan-section');
                const isActive = scanSection.hasClass('active');
                if (!isActive) {
                    scanSection.addClass('active').show();
                    $('.scan-input').focus();
                } else {
                    scanSection.removeClass('active').hide();
                    $('.scan-input').val('');
                }
            });

            // Handle barcode scan
            $('.scan-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const code_article = form.find('.scan-input').val().trim().replace(/[\r\n]+/g, '');
                const reception_id = form.find('input[name="reception_id"]').val();
                const notes = form.find('textarea[name="notes"]').val();

                if (!code_article) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Veuillez scanner un code article.',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                    form.find('.scan-input').val('').focus();
                    return;
                }

                // Prepare validation data
                const validateData = {
                    _token: '{{ csrf_token() }}',
                    code_article: code_article,
                    reception_id: reception_id,
                    quantite: 1,
                    validate_only: 1 // Send as 1 instead of true
                };
                console.log('Validate AJAX Data:', validateData);

                // Log serialized Form Data
                const formData = $.param(validateData);
                console.log('Serialized Form Data:', formData);

                // Validate article
                $.ajax({
                    url: '{{ route('receptions.scan.update', $reception->id) }}',
                    method: 'POST',
                    data: validateData,
                    success: function (response) {
                        console.log('Validate AJAX Response:', response);
                        if (response.valid) {
                            form.find('.error-message').hide();
                            // Prepare scan data
                            const scanData = {
                                _token: '{{ csrf_token() }}',
                                code_article: code_article,
                                reception_id: reception_id,
                                quantite: 1,
                                notes: notes
                            };
                            console.log('Scan AJAX Data:', scanData);
                            console.log('Serialized Scan Data:', $.param(scanData));

                            // Submit scan
                            $.ajax({
                                url: '{{ route('receptions.scan.update', $reception->id) }}',
                                method: 'POST',
                                data: scanData,
                                success: function (response) {
                                    console.log('Scan response:', response);
                                    if (response.success) {
                                        // Play beep sound
                                        beepSound.play();

                                        // Update article quantities
                                        window.articleQuantities = response.article_quantities;

                                        // Update table
                                        const tbody = $('.article-table tbody');
                                        tbody.find('tr').each(function () {
                                            const code = $(this).data('code');
                                            if (!window.articleQuantities[code]) return;
                                            const scanned = window.articleQuantities[code].scanned || 0;
                                            const demanded = window.articleQuantities[code].demanded || 0;
                                            const remaining = window.articleQuantities[code].remaining || 0;
                                            $(this).find('td').eq(3).text(scanned);
                                            $(this).find('td').eq(4).text(remaining);
                                            if (scanned >= demanded) {
                                                $(this).addClass('completed');
                                            } else {
                                                $(this).removeClass('completed');
                                            }
                                        });

                                        // Update article details list
                                        $('.article-details li').each(function () {
                                            const code = $(this).data('code');
                                            if (!window.articleQuantities[code]) return;
                                            const scanned = window.articleQuantities[code].scanned || 0;
                                            const demanded = window.articleQuantities[code].demanded || 0;
                                            const name = window.articleQuantities[code].name || 'Non disponible';
                                            $(this).text(`${code} - ${name} - Qté à recevoir : ${demanded} (Scannée : ${scanned})`);
                                            if (scanned >= demanded) {
                                                $(this).addClass('completed');
                                            } else {
                                                $(this).removeClass('completed');
                                            }
                                        });

                                        // Update progress bar and scanned count
                                        const total = {{ $reception->purchaseOrder->lines->sum('ordered_quantity') ?: 1 }};
                                        const percentage = (response.scanned_quantity / total) * 100;
                                        $('.progress-bar').css('width', `${percentage}%`).attr('aria-valuenow', response.scanned_quantity);
                                        $('small').text(`Scanné : ${response.scanned_quantity} / ${total}`);

                                        // Show notification
                                        Swal.fire({
                                            icon: 'success',
                                            title: `+1 ${response.scan_data.code_article}`,
                                            text: `Scanné: ${response.article_quantities[code_article].scanned} | Restant: ${response.scan_data.remaining_quantity}`,
                                            timer: 1500,
                                            showConfirmButton: false,
                                            position: 'top-end',
                                            toast: true
                                        });

                                        // Reset input
                                        form.find('.scan-input').val('').focus();
                                        form.find('textarea[name="notes"]').val('');

                                        // Handle article completion
                                        if (response.scan_data.article_completed) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Article Complété !',
                                                text: `L'article ${response.scan_data.code_article} est entièrement scanné.`,
                                                timer: 2000,
                                                showConfirmButton: false,
                                                position: 'top-end',
                                                toast: true
                                            });
                                            $(`tr[data-code="${code_article}"]`).addClass('completed');
                                            $(`li[data-code="${code_article}"]`).addClass('completed');
                                        }

                                        // Handle document completion
                                        if (response.scan_data.document_completed) {
                                            $('.card').addClass('completed');
                                            $('.scan-section').removeClass('active').hide();
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Réception Complétée !',
                                                text: `La réception #${reception_id} est entièrement scannée.`,
                                                timer: 2000,
                                                showConfirmButton: false,
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.location.href = '{{ route('receptions.show', $reception->id) }}';
                                            });
                                        }
                                    } else {
                                        console.log('Scan Error Response:', response);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erreur',
                                            text: response.message,
                                            timer: 2000,
                                            showConfirmButton: false,
                                            position: 'top-end',
                                            toast: true
                                        });
                                        form.find('.scan-input').val('').focus();
                                    }
                                },
                                error: function (xhr) {
                                    console.log('Scan AJAX Error:', xhr.responseJSON);
                                    let errorMessage = xhr.responseJSON?.message || 'Erreur lors du scan.';
                                    if (xhr.responseJSON?.errors) {
                                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(' ');
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: errorMessage,
                                        timer: 2000,
                                        showConfirmButton: false,
                                        position: 'top-end',
                                        toast: true
                                    });
                                    form.find('.scan-input').val('').focus();
                                }
                            });
                        } else {
                            console.log('Validate Error Response:', response);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                            form.find('.scan-input').val('').focus();
                        }
                    },
                    error: function (xhr) {
                        console.log('Validate AJAX Error:', xhr.responseJSON);
                        let errorMessage = xhr.responseJSON?.message || 'Erreur lors de la validation de l\'article.';
                        if (xhr.responseJSON?.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(' ');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: errorMessage,
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true
                        });
                        form.find('.scan-input').val('').focus();
                    }
                });
            });

            // Auto-focus input when scan section is opened
            $('.scan-section').on('shown.bs.collapse', function () {
                $(this).find('.scan-input').focus();
            });
        });
    </script>
</body>
</html>