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
                        <li class="nav-item active"><a href="/invoices"><i class="fas fa-file-invoice"></i><p>Factures Achat</p></a></li>
                        <li class="nav-item"><a href="/notes"><i class="fas fa-sticky-note"></i><p>Avoirs Achat</p></a></li>
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
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modifier la facture #{{ $invoice->numdoc }}</h5>
            </div>
            <div class="card-body">
               <form action="{{ route('invoices.update', $invoice->numdoc) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="{{ $invoice->type }}">
                            <input type="hidden" name="tva_rate" id="tva_rate" value="{{ $invoice->tva_rate }}">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Fournisseur</label>
                                    <select name="supplier_id" class="form-control select2" required>
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $invoice->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">TVA %</label>
                                    <input type="text" id="tva_display" class="form-control" value="{{ $invoice->tva_rate }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de facture</label>
                                    <input type="date" name="invoice_date" class="form-control" value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}" required>
                                </div>
                            </div>



                            <h6 class="mt-4 mb-2">Lignes de facture</h6>
                            <table class="table table-bordered" id="linesTable">
                                <thead class="table-light">
                                    <tr>
                                        @if($invoice->type === 'groupée')
                                            <th>Article</th>
                                        @endif
                                        <th>Description</th>
                                        <th>Qté</th>
                                        <th>PU HT</th>
                                        <th>Remise %</th>
                                        <th>TVA %</th>
                                        <th>Total HT</th>
                                        <th>Total TTC</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="lines">
                                    @foreach($invoice->lines as $index => $line)
                                        <tr>
                                            @if($invoice->type === 'groupée')
                                                <td>
                                                    <input type="text" value="{{ $line->article_code }} - {{ $line->item ? $line->item->name : ($line->description ?? '-') }}" class="form-control" readonly>
                                                    <input type="hidden" name="lines[{{ $index }}][article_code]" value="{{ $line->article_code }}">
                                                </td>
                                            @endif
                                            <td><input type="text" name="lines[{{ $index }}][description]" class="form-control description" value="{{ $line->description ?? ($line->item->name ?? '') }}" required placeholder="{{ $invoice->type === 'libre' ? 'Ex: Bouteilles d\'eau' : '' }}"></td>
                                            <td><input type="number" name="lines[{{ $index }}][quantity]" class="form-control qty" value="{{ $line->quantity }}" required></td>
                                            <td><input type="number" step="0.01" name="lines[{{ $index }}][unit_price_ht]" class="form-control pu" value="{{ $line->unit_price_ht }}" required></td>
                                            <td><input type="number" step="0.01" name="lines[{{ $index }}][remise]" class="form-control remise" value="{{ $line->remise ?? 0 }}"></td>
                                            <td><input type="text" name="lines[{{ $index }}][tva]" class="form-control tva_ligne" value="{{ $line->tva }}" readonly></td>
                                            <td><input type="text" class="form-control total" value="{{ number_format($line->total_ligne_ht, 2) }}" readonly></td>
                                            <td><input type="text" class="form-control totalttc" value="{{ number_format($line->prix_ttc, 2) }}" readonly></td>
                                            <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-outline-secondary" id="addLine">+ Ajouter une ligne</button>
                            </div>

                            <div class="row align-items-center mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Notes / Commentaire</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Remarques internes, conditions de facturation, etc.">{{ $invoice->notes }}</textarea>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="p-3 bg-light border rounded shadow-sm">
                                        <h5 class="mb-1">Total HT : <span id="grandTotal" class="text-success fw-bold">{{ number_format($invoice->total_ht, 2) }}</span> €</h5>
                                        <h6 class="mb-0">Total TTC : <span id="grandTotalTTC" class="text-danger fw-bold">{{ number_format($invoice->total_ttc, 2) }}</span> €</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="action" value="save" class="btn btn-primary px-4">✅ Enregistrer Brouillon</button>
                                <button type="submit" name="action" value="validate" class="btn btn-success px-4 ms-2">✔️ Valider la Facture</button>
                            </div>
                        </form>
            </div>
    </div>
</div>

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



<!-- Ajoute select2 si pas déjà inclus -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




<script>
let lineIndex = {{ count($invoice->lines) }};
const tvaMap = {!! $tvaRates !!};
const invoiceType = '{{ $invoice->type }}';

$(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    function getTVA() {
        const supplierId = parseInt($('select[name="supplier_id"]').val());
        return parseFloat(tvaMap[supplierId]) || 0;
    }

    function recalculate() {
        let totalHT = 0;
        const tva = getTVA();

        $('#lines tr').each(function() {
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
        $('#tva_display').val(tva.toFixed(2));
    }

    $('select[name="supplier_id"]').on('change', function() {
        recalculate();
    });

    $('#lines').on('input', '.qty, .pu, .remise', recalculate);

    $('#addLine').click(function() {
        const tva = getTVA();
        let newRow = '';
        if (invoiceType === 'groupée') {
            newRow = `
                <tr>
                    <td>
                        <select name="lines[${lineIndex}][article_code]" class="form-control select2-article" required></select>
                    </td>
                    <td><input type="text" name="lines[${lineIndex}][description]" class="form-control description" required></td>
                    <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" required></td>
                    <td><input type="number" step="0.01" name="lines[${lineIndex}][unit_price_ht]" class="form-control pu" required></td>
                    <td><input type="number" step="0.01" name="lines[${lineIndex}][remise]" class="form-control remise" value="0"></td>
                    <td><input type="text" name="lines[${lineIndex}][tva]" class="form-control tva_ligne" value="${tva.toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control total" readonly></td>
                    <td><input type="text" class="form-control totalttc" readonly></td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                </tr>`;
        } else {
            newRow = `
                <tr>
                    <td><input type="text" name="lines[${lineIndex}][description]" class="form-control description" required placeholder="Ex: Bouteilles d'eau"></td>
                    <td><input type="number" name="lines[${lineIndex}][quantity]" class="form-control qty" required></td>
                    <td><input type="number" step="0.01" name="lines[${lineIndex}][unit_price_ht]" class="form-control pu" required></td>
                    <td><input type="number" step="0.01" name="lines[${lineIndex}][remise]" class="form-control remise" value="0"></td>
                    <td><input type="text" name="lines[${lineIndex}][tva]" class="form-control tva_ligne" value="${tva.toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control total" readonly></td>
                    <td><input type="text" class="form-control totalttc" readonly></td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm remove-line">×</button></td>
                </tr>`;
        }
        $('#lines').append(newRow);
        if (invoiceType === 'groupée') {
            initSelect2(lineIndex);
        }
        lineIndex++;
        recalculate();
    });

    $('#lines').on('click', '.remove-line', function() {
        $(this).closest('tr').remove();
        recalculate();
    });

    function initSelect2(index) {
        const selector = `select[name="lines[${index}][article_code]"]`;
        $(selector).select2({
            ajax: {
                url: "{{ route('items.search') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { term: params.term };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            },
            placeholder: 'Rechercher un article',
            minimumInputLength: 2,
            width: '100%',
            templateResult: function(data) {
                return data.text;
            },
            templateSelection: function(data) {
                return data.text || data.id;
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            const $row = $(this).closest('tr');
            $row.find('.description').val(data.text.split(' - ')[1] || '');
            $row.find('.pu').val(parseFloat(data.price || 0).toFixed(2));
            recalculate();
        });
    }

    if (invoiceType === 'groupée') {
        $('#lines .select2-article').each(function(index) {
            initSelect2(index);
            const articleCode = $(this).closest('tr').find('input[name="lines[' + index + '][article_code]"]').val();
            const description = $(this).closest('tr').find('.description').val();
            if (articleCode) {
                const option = new Option(`${articleCode} - ${description}`, articleCode, true, true);
                $(this).append(option).trigger('change');
            }
        });
    }

    $('select[name="supplier_id"]').trigger('change');
    recalculate();
});
</script>









<script>
document.getElementById("searchItemInput").addEventListener("keyup", function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll("#itemsTable tbody tr");

    rows.forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
    });
});
</script>
















  </body>
</html>
