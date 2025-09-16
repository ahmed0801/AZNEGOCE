<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />
<!-- jQuery + Bootstrap JS (v4) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
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





    <style>
#panierDropdown + .dropdown-menu {
    width: 900px; /* Adjust the width as needed */
    min-width: 350px; /* Ensure a minimum width */
    padding: 10px; /* Add padding to create space inside the dropdown */
    border-radius: 8px; /* Optional: Rounded corners for a cleaner look */
}

.panier-dropdown {
    width: 100%; /* Use full width of the parent container */
    min-width: 350px; /* Ensure minimum width */
}

.panier-dropdown .notif-item {
    padding: 10px; /* Add padding between items */
    margin-bottom: 5px; /* Space between items */
    border-bottom: 1px solid #ddd; /* Optional: Border between items */
}

.dropdown-title {
    font-weight: bold;
    margin-bottom: 10px; /* Space below the title */
}

.notif-scroll {
    padding: 10px; /* Add padding inside the scrollable area */
}

.notif-center {
    padding: 5px 0; /* Space around each notification */
}

.dropdown-footer {
    padding: 10px;
    border-top: 1px solid #ddd; /* Optional: Border to separate the footer */
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
                        <li class="nav-item">
                            <a href="/dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
                        </li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-shopping-cart"></i></span><h4 class="text-section">Ventes</h4></li>
                        <li class="nav-item"><a href="/sales/create"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
                        <li class="nav-item"><a href="/sales"><i class="fas fa-file-alt"></i><p>Commandes Vente</p></a></li>
                        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Devis</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/list"><i class="fas fa-file-invoice-dollar"></i><p>Bons De Livraison</p></a></li>
                        <li class="nav-item"><a href="/delivery_notes/returns/list"><i class="fas fa-undo-alt"></i><p>Retours Vente</p></a></li>
                        <li class="nav-item"><a href="/salesinvoices"><i class="fas fa-money-bill-wave"></i><p>Factures Vente</p></a></li>
                        <li class="nav-item"><a href="/salesnotes/list"><i class="fas fa-reply-all"></i><p>Avoirs Vente</p></a></li>
                        <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-box"></i></span><h4 class="text-section">Achats</h4></li>
                        <li class="nav-item"><a href="/purchases/list"><i class="fas fa-file-alt"></i><p>Commandes Achat</p></a></li>
                        <li class="nav-item"><a href="/purchaseprojects/list"><i class="fas fa-file-alt"></i><p>Projets de Commande</p></a></li>
                        <li class="nav-item"><a href="/returns"><i class="fas fa-undo-alt"></i><p>Retours Achat</p></a></li>
                        <li class="nav-item"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item active"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
                      <li class="nav-section"><span class="sidebar-mini-icon"><i class="fas fa-credit-card"></i></span><h4 class="text-section">Comptabilité</h4></li>
                                                <li class="nav-item {{ Route::is('generalaccounts.index') ? 'active' : '' }}">
                            <a href="{{ route('generalaccounts.index') }}"><i class="fas fa-book"></i><p>Comptes Généraux</p></a>
                        </li>
                                                <li class="nav-item {{ Route::is('payments.index') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i><p>Règlements</p></a>
                        </li>
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
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="{{ asset('assets/img/logop.png')}}"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
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
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">





              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                

 





              <!-- test panier -->
      

         
                
                
                

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="{{ asset('assets/img/avatar.png')}}"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <!-- <span class="op-7">Hi,</span> -->
                      <span class="fw-bold">{{ Auth::user()->name}}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="{{ asset('assets/img/avatar.png')}}"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
<div class="u-text">
                            <h4>{{ Auth::user()->name}}</h4>

                            <p class="text-muted">{{ Auth::user()->email}}</p>
                            <a
                              href="/setting"
                              class="btn btn-xs btn-secondary btn-sm"
                              >Paramétres</a>

                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <!-- <a class="dropdown-item" href="#">My Profile</a> -->
                        <!-- <a class="dropdown-item" href="#">My Balance</a> -->
                        <!-- <div class="dropdown-divider"></div> -->

    <!-- Formulaire de déconnexion -->
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
          <!-- End Navbar -->
        </div>

        
        

       <div class="container">
                <div class="page-inner">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="container mt-4">
                        <h4>📄 Liste des avoirs d'achat :
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Nouvel avoir <i class="fas fa-plus-circle ms-2"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if($returns->isEmpty())
                                        <a class="dropdown-item disabled" href="#">À partir d’un retour (aucun retour disponible)</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('notes.create_from_return') }}">À partir d’un retour</a>
                                    @endif
                                    @if($invoices->isEmpty())
                                        <a class="dropdown-item disabled" href="#">À partir d’une facture (aucune facture disponible)</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('notes.create_from_invoice') }}">À partir d’une facture</a>
                                    @endif
                                </div>
                            </div>
                        </h4>

                        <form method="GET" action="{{ route('notes.list') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                            <select name="supplier_id" class="form-select form-select-sm select2" style="width: 150px;">
                                <option value="">Fournisseur (Tous)</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="status" class="form-select form-select-sm select2" style="width: 170px;">
                                <option value="">Statut avoir (Tous)</option>
                                <option value="brouillon" {{ request('status') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="validée" {{ request('status') == 'validée' ? 'selected' : '' }}>Validée</option>
                            </select>

                            <select name="type" class="form-select form-select-sm select2" style="width: 170px;">
                                <option value="">Type avoir (Tous)</option>
                                <option value="from_return" {{ request('type') == 'from_return' ? 'selected' : '' }}>À partir d’un retour</option>
                                <option value="from_invoice" {{ request('type') == 'from_invoice' ? 'selected' : '' }}>À partir d’une facture</option>
                            </select>
de
                            <input type="date" name="date_from" class="form-control form-control-sm" style="width: 120px;" placeholder="Date début"
                                value="{{ request('date_from') }}">
à
                            <input type="date" name="date_to" class="form-control form-control-sm" style="width: 150px;" placeholder="Date fin"
                                value="{{ request('date_to') }}">

                            <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                                <i class="fas fa-filter me-1"></i> Filtrer
                            </button>

                            <button type="submit" name="action" value="export" formaction="{{ route('notes.export') }}"
                                class="btn btn-outline-success btn-sm px-3">
                                <i class="fas fa-file-excel me-1"></i> EXCEL
                            </button>

                            <a href="{{ route('notes.list') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-undo me-1"></i> Réinitialiser
                            </a>
                        </form>

                        @forelse ($notes as $note)
                            <div class="card mb-4 shadow-sm border-0">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center border-start border-4 border-primary">
                                    <div>
                                        <h6 class="mb-0">
                                            <strong>Avoir N° : {{ $note->numdoc }}</strong> –
                                            {{ $note->supplier ? $note->supplier->name : 'Fournisseur inconnu' }}
                                            <span class="text-muted small">({{ \Carbon\Carbon::parse($note->note_date)->format('d/m/Y') }})</span>
                                        </h6>
                                        @if($note->status === 'brouillon')
                                            <span class="badge bg-secondary">{{ ucfirst($note->status) }}</span>
                                        @else
                                            <span class="badge bg-success">{{ ucfirst($note->status) }}</span>
                                        @endif

                                        @if($note->status != 'brouillon')
                                            @if($note->paid)
                                                <span class="badge bg-success">Payé</span>
                                            @else
                                                <span class="badge bg-danger">Non payé ({{ number_format($note->getRemainingBalanceAttribute(), 2, ',', ' ') }} €)</span>
                                            @endif
                                        @endif


                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $note->type)) }}</span>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="toggleLines({{ $note->id }})">
                                            ➕ Détails
                                        </button>
                                        <a href="{{ route('notes.exportSingle', $note->id) }}" class="btn btn-xs btn-outline-success">
                                            EXCEL <i class="fas fa-file-excel"></i>
                                        </a>
                                        <a href="{{ route('notes.printSingle', $note->id) }}" class="btn btn-xs btn-outline-primary" title="Télécharger PDF" target="_blank">
                                            PDF <i class="fas fa-print"></i>
                                        </a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Actions</span> <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if($note->status === 'brouillon')
                                                    <a class="dropdown-item" href="{{ route('notes.edit', $note->id) }}">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                @endif

                                                 @if($note->status === 'validée' && !$note->paid)
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#makePaymentModal{{ $note->id }}">
                                                        <i class="fas fa-credit-card"></i> Faire un règlement
                                                    </a>
                                                @endif


                                                @if($note->purchaseReturn)
                                                    <a class="dropdown-item" href="{{ route('returns.show', $note->purchaseReturn->id) }}">
                                                        <i class="fas fa-eye"></i> Retour #{{ $note->purchaseReturn->numdoc }}
                                                    </a>
                                                @endif
                                                @if($note->purchaseInvoice)
                                                    <a class="dropdown-item" href="{{ route('invoices.show', $note->purchaseInvoice->id) }}>
                                                        <i class="fas fa-eye"></i> Facture #{{ $note->purchaseInvoice->numdoc }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="lines-{{ $note->id }}" class="card-body d-none bg-light">
                                    <h6 class="fw-bold mb-3">🧾 Lignes de l’avoir</h6>
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Code Article</th>
                                                <th>Désignation</th>
                                                <th>Qté</th>
                                                <th>PU HT</th>
                                                <th>Remise (%)</th>
                                                <th>TVA (%)</th>
                                                <th>Total Ligne</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($note->lines as $line)
                                                <tr>
                                                    <td>{{ $line->article_code ?? '-' }}</td>
                                                    <td>{{ $line->item ? $line->item->name : ($line->description ?? '-') }}</td>
                                                    <td class="text-center">{{ $line->quantity }}</td>
                                                    <td class="text-end">{{ number_format($line->unit_price_ht, 2) }} €</td>
                                                    <td class="text-end">{{ $line->remise }}%</td>
                                                    <td class="text-end">{{ $line->tva }}%</td>
                                                    <td class="text-end">{{ number_format($line->total_ligne_ht, 2) }} €</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="text-end mt-3">
                                        <div class="p-3 bg-white border rounded d-inline-block">
                                            <strong>Total HT :</strong> {{ number_format($note->total_ht, 2) }} €<br>
                                            <strong>Total TTC :</strong> {{ number_format($note->total_ttc, 2) }} €
                                        </div>
                                    </div>
                                </div>



                                 <!-- Make Payment Modal -->
                                @if($note->status === 'validée' && !$note->paid)
                                    <div class="modal fade" id="makePaymentModal{{ $note->id }}" tabindex="-1" aria-labelledby="makePaymentModalLabel{{ $note->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="makePaymentModalLabel{{ $note->id }}">Faire un règlement pour {{ $note->numdoc }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('notes.make_payment', $note->id) }}" method="POST" class="payment-form">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label for="amount{{ $note->id }}" class="form-label">Montant (€)</label>
                                                            <input type="number" step="0.01" class="form-control" id="amount{{ $note->id }}" name="amount"  value="{{ old('amount') }}" required>
                                                            <small>Reste à payer : {{ number_format($note->getRemainingBalanceAttribute(), 2, ',', ' ') }} €</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_date{{ $note->id }}" class="form-label">Date de paiement</label>
                                                            <input type="date" class="form-control" id="payment_date{{ $note->id }}" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_mode{{ $note->id }}" class="form-label">Mode de paiement</label>
                                                            <select class="form-control select2" id="payment_mode{{ $note->id }}" name="payment_mode" required>
                                                                @foreach(\App\Models\PaymentMode::all() as $mode)
                                                                    <option value="{{ $mode->name }}" {{ old('payment_mode') == $mode->name ? 'selected' : '' }}>{{ $mode->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="reference{{ $note->id }}" class="form-label">Référence (optionnel)</label>
                                                            <input type="text" class="form-control" id="reference{{ $note->id }}" name="reference" value="{{ old('reference') }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="notes{{ $note->id }}" class="form-label">Notes (optionnel)</label>
                                                            <textarea class="form-control" id="notes{{ $note->id }}" name="notes">{{ old('notes') }}</textarea>
                                                        </div>
                                                        <input type="hidden" name="form_id" value="payment_form_{{ $note->id }}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary payment-submit-btn">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif





                            </div>
                        @empty
                            <div class="alert alert-info">
                                Aucun avoir trouvé.
                            </div>


                            
                        @endforelse
                    </div>
   </div>   </div>


    
        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <!-- <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul> -->
            </nav>
            <div class="copyright">
            © AZ NEGOCE. All Rights Reserved.
              <!-- <a href="http://www.themekita.com">By Ahmed Arfaoui</a> -->
            </div>
            <div>
               by
              <a target="_blank" href="https://themewagon.com/">Ahmed Arfaoui</a>.
            </div>
          </div>
        </footer>
      </div>

      
    </div>
   <!-- Core JS Files -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
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
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Debug: Log when Select2 is initialized
        console.log('Select2 initialized');

        // Handle return selection
        $('#return_id').on('select2:select', function(e) {
            e.stopPropagation();
            const returnId = $(this).val();
            console.log('Return selected:', returnId);
            if (returnId) {
                const url = '{{ route("notes.create_from_return", ":returnId") }}'.replace(':returnId', returnId);
                $('#createFromReturnForm').attr('action', url);
                $('#createFromReturnButton').removeAttr('disabled').removeClass('disabled');
                console.log('Updated return form action:', url);
            } else {
                $('#createFromReturnForm').attr('action', '#');
                $('#createFromReturnButton').attr('disabled', 'disabled').addClass('disabled');
                console.log('Return form action reset to #');
            }
        });

        // Handle invoice selection
        $('#invoice_id').on('select2:select', function(e) {
            e.stopPropagation();
            const invoiceId = $(this).val();
            console.log('Invoice selected:', invoiceId);
            if (invoiceId) {
                const url = '{{ route("notes.create_from_invoice", ":invoiceId") }}'.replace(':invoiceId', invoiceId);
                $('#createFromInvoiceForm').attr('action', url);
                $('#createFromInvoiceButton').removeAttr('disabled').removeClass('disabled');
                console.log('Updated invoice form action:', url);
            } else {
                $('#createFromInvoiceForm').attr('action', '#');
                $('#createFromInvoiceButton').attr('disabled', 'disabled').addClass('disabled');
                console.log('Invoice form action reset to #');
            }
        });

        // Debug: Log when modals are opened
        $('#selectReturnModal').on('shown.bs.modal', function() {
            console.log('Return modal opened');
        });
        $('#selectInvoiceModal').on('shown.bs.modal', function() {
            console.log('Invoice modal opened');
        });

        // Debug: Log when buttons are clicked
        $('#createFromReturnButton').on('click', function() {
            const url = $('#createFromReturnForm').attr('action');
            console.log('Create from return button clicked, form action:', url);
            if (url && url !== '#') {
                console.log('Submitting return form to:', url);
                // Form submission will handle navigation
            } else {
                console.error('Invalid return URL');
                alert('Veuillez sélectionner un retour valide.');
            }
        });

        $('#createFromInvoiceButton').on('click', function() {
            const url = $('#createFromInvoiceForm').attr('action');
            console.log('Create from invoice button clicked, form action:', url);
            if (url && url !== '#') {
                console.log('Submitting invoice form to:', url);
                // Form submission will handle navigation
            } else {
                console.error('Invalid invoice URL');
                alert('Veuillez sélectionner une facture valide.');
            }
        });
    });

    function toggleLines(id) {
        const section = document.getElementById('lines-' + id);
        section.classList.toggle('d-none');
    }
</script>

  </body>
</html>
