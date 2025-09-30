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
                                                    {{ $customer->name }} ({{ $customer->code ?? 'N/A'}}) 
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
                                        <select name="documents[]" id="documents" class="form-control select2-documents" multiple required>
                                            @php
                                                $selectedDocuments = [];
                                                foreach ($invoice->lines as $line) {
                                                    if ($line->delivery_note_id && $line->deliveryNote) {
                                                        $key = 'delivery_' . $line->delivery_note_id;
                                                        if (!in_array($key, $selectedDocuments)) {
                                                            $selectedDocuments[] = $key;
                                                            echo '<option value="' . $key . '" selected data-tva-rate="' . ($line->deliveryNote->tva_rate ?? $invoice->tva_rate ?? 0) . '">#' . ($line->deliveryNote->numdoc ?? 'N/A') . ' - ' . ($line->deliveryNote->customer_name ?? $invoice->customer->name ?? 'N/A') . ' (' . ($line->deliveryNote->order_date ? \Carbon\Carbon::parse($line->deliveryNote->order_date)->format('d/m/Y') : 'N/A') . ')</option>';
                                                        }
                                                    } elseif ($line->sales_return_id && $line->salesReturn) {
                                                        $key = 'return_' . $line->sales_return_id;
                                                        if (!in_array($key, $selectedDocuments)) {
                                                            $selectedDocuments[] = $key;
                                                            echo '<option value="' . $key . '" selected data-tva-rate="' . ($line->salesReturn->tva_rate ?? $invoice->tva_rate ?? 0) . '">#' . ($line->salesReturn->numdoc ?? 'N/A') . ' - ' . ($line->salesReturn->customer_name ?? $invoice->customer->name ?? 'N/A') . ' (' . ($line->salesReturn->order_date ? \Carbon\Carbon::parse($line->salesReturn->order_date)->format('d/m/Y') : 'N/A') . ')</option>';
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </select>
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
                                                            <input type="text" value="{{ $line->deliveryNote ? ' #' . ($line->deliveryNote->numdoc ?? 'N/A') : ($line->salesReturn ? '#' . ($line->salesReturn->numdoc ?? 'N/A') : '-') }}" class="form-control" readonly>
                                                            <input type="hidden" name="lines[{{ $index }}][delivery_note_id]" value="{{ $line->delivery_note_id ?? '' }}">
                                                            <input type="hidden" name="lines[{{ $index }}][sales_return_id]" value="{{ $line->sales_return_id ?? '' }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ $line->article_code ?? '' }} - {{ $line->item ? $line->item->name : ($line->description ?? '-') }}" class="form-control" readonly>
                                                            <input type="hidden" name="lines[{{ $index }}][article_code]" value="{{ $line->article_code ?? '' }}">
                                                        </td>
                                                    @else
                                                        <td><input type="text" name="lines[{{ $index }}][description]" class="form-control description" value="{{ $line->description ?? ($line->item->name ?? '') }}" required></td>
                                                    @endif
                                                    <td><input type="number" name="lines[{{ $index }}][quantity]" class="form-control qty" value="{{ $line->quantity ?? 1 }}" min="1" required></td>
                                                    <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ht]" class="form-control pu" value="{{ $line->unit_price_ht ?? 0 }}" min="0" required></td>
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
                                            <h5 class="mb-1">Total HT : <span id="grandTotal" class="text-success fw-bold">{{ number_format($invoice->total_ht ?? 0, 2, ',', ' ') }}</span> TND</h5>
                                            <h6 class="mb-0">Total TTC : <span id="grandTotalTTC" class="text-danger fw-bold">{{ number_format($invoice->total_ttc ?? 0, 2, ',', ' ') }}</span> TND</h6>
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
        let isInitialLoad = true; // Flag to track initial load

        $(document).ready(function () {
            console.log('Document ready, initializing Select2 and form');

            // Initialize Select2 for customer dropdown
            $('.select2').select2({ width: '100%' });

            // Initialize Select2 for documents without triggering change
            if (invoiceType !== 'libre') {
                $('.select2-documents').select2({
                    ajax: {
                        url: "{{ route('sales.orders.search') }}",
                        dataType: 'json',
                        delay: 500, // Increased delay to avoid race conditions
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
                                    numdoc: item.numdoc,
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
                const tvaRates = selectedItems.map(item => parseFloat(item.tva_rate || $('#documents option[value="' + item.id + '"]').data('tva-rate')));
                if (new Set(tvaRates).size > 1) {
                    alert('Erreur : Les documents sélectionnés ont des taux de TVA différents.');
                    $('.select2-documents').val(null).trigger('change');
                    return {{ $invoice->tva_rate ?? 0 }};
                }
                return tvaRates[0] || {{ $invoice->tva_rate ?? 0 }};
            }

            function recalculate() {
                console.log('Recalculating totals');
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
                console.log('Customer changed:', $(this).val());
                const selectedOption = $(this).find('option:selected');
                const numclient = selectedOption.data('code') || '';
                $('#numclient').val(numclient);
                if (invoiceType !== 'libre') {
                    $('.select2-documents').val(null).trigger('change');
                }
                recalculate();
            });

            $('#lines').on('input', '.qty, .pu, .remise', function () {
                console.log('Line input changed, recalculating');
                recalculate();
            });

            if (invoiceType === 'libre') {
                $('#addLine').click(function () {
                    console.log('Adding new line, index:', lineIndex);
                    const tva = getTVA();
                    const newRow = `
                        <tr>
                            <td><input type="text" name="lines[${lineIndex}][description]" class="form-control description" required placeholder="Ex: Service de maintenance"></td>
                            <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" value="1" min="1" required></td>
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
                console.log('Removing line');
                $(this).closest('tr').remove();
                recalculate();
            });

            $('.select2-documents').on('select2:select select2:unselect', function (e) {
                console.log('Select2 event:', e.type, 'isInitialLoad:', isInitialLoad, 'selected:', $(this).val());
                if (invoiceType === 'libre') return;
                if (isInitialLoad) return; // Skip during initial load
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
                                    <input type="text" value="${item.type === 'delivery' ? 'Bon de Livraison' : 'Retour Vente'} #${item.numdoc}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][${item.type === 'delivery' ? 'delivery_note_id' : 'sales_return_id'}]" value="${item.id.replace(/^(delivery|return)_/, '')}">
                                </td>
                                <td>
                                    <input type="text" value="${line.article_code} - ${line.item_name || line.description || '-'}" class="form-control" readonly>
                                    <input type="hidden" name="lines[${lineIndex}][article_code]" value="${line.article_code}">
                                </td>
                                <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" value="${qty}" min="1" required></td>
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
                const preSelectedDocs = [
                    @foreach($invoice->lines as $line)
                        @if($line->delivery_note_id)
                            'delivery_{{ $line->delivery_note_id }}',
                        @elseif($line->sales_return_id)
                            'return_{{ $line->sales_return_id }}',
                        @endif
                    @endforeach
                ].filter(Boolean); // Remove duplicates and empty values
                console.log('Pre-selected Docs:', preSelectedDocs);
                if (preSelectedDocs.length > 0) {
                    // Set initial Select2 values without triggering change
                    $('.select2-documents').val(preSelectedDocs);
                    // Delay AJAX to avoid interfering with initial rendering
                    setTimeout(() => {
                        console.log('Fetching document details via AJAX');
                        $.ajax({
                            url: "{{ route('sales.orders.search') }}",
                            data: {
                                customer_id: $('#customer_id').val(),
                                customer_code: $('#customer_id').find(':selected').data('code') || '',
                                type: invoiceType === 'direct' ? 'delivery' : 'all'
                            },
                            dataType: 'json',
                            success: function (data) {
                                console.log('AJAX Success, Data:', data);
                                isInitialLoad = false; // Allow future changes to update lines
                                recalculate();
                            },
                            error: function (xhr, status, error) {
                                console.error('AJAX Error:', status, error, xhr.responseText);
                                isInitialLoad = false;
                                recalculate(); // Keep Blade-rendered lines
                            }
                        });
                    }, 1000); // 1-second delay
                } else {
                    isInitialLoad = false;
                    recalculate();
                }
            @else
                isInitialLoad = false;
                recalculate();
            @endif

            // $('#customer_id').trigger('change');
        });
    </script>
</body>
</html>