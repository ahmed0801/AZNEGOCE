<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>AZ ERP — Clients</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <style>
    /* ── Design tokens ─────────────────────────────────────── */
    :root {
        --c-navy:   #1E2D4A;
        --c-blue:   #3B82F6;
        --c-mint:   #10B981;
        --c-amber:  #F59E0B;
        --c-red:    #EF4444;
        --c-bg:     #F0F4FF;
        --c-white:  #FFFFFF;
        --c-text:   #1A2B4A;
        --c-sub:    #6B7A99;
        --c-border: #E2E8F0;
        --shadow:   0 8px 32px rgba(30,45,74,0.12);
    }

    /* ── Table ─────────────────────────────────────────────── */
    .table { width:100%; margin-bottom:0; }
    .table th, .table td { text-align:center; vertical-align:middle; }
    .table-striped tbody tr:nth-child(odd) { background-color:#f2f2f2; }
    .btn-sm { padding:0.2rem 0.5rem; font-size:0.75rem; }
    .text-muted { font-size:0.85rem; }
    .table-text-small td, .table-text-small th { font-size:11px !important; }
    .search-box {
        max-width:400px; height:35px; padding:5px 12px;
        border:2px solid #007bff; border-radius:20px; font-size:14px;
        box-shadow:inset 0 0 4px rgba(0,0,0,0.1);
    }
    .search-box:focus { border-color:#0056b3; box-shadow:0 0 6px rgba(0,123,255,0.5); }
    .badge-very-sm { font-size:0.7rem; padding:0.15em 0.3em; vertical-align:middle; }

    /* ── AZ Modal Design ───────────────────────────────────── */
    .az-modal .modal-content {
        border:none; border-radius:20px;
        overflow:hidden; box-shadow:var(--shadow);
    }
    .az-modal .modal-header {
        background:linear-gradient(135deg, var(--c-navy) 0%, #2D4A8A 100%);
        padding:20px 28px 16px; border:none; position:relative;
    }
    .az-modal .modal-header::after {
        content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
        background:linear-gradient(90deg, var(--c-blue), var(--c-mint), var(--c-amber));
    }
    .az-modal .modal-title { color:white; font-size:1.05rem; font-weight:700; letter-spacing:0.02em; }
    .az-modal .modal-subtitle { color:rgba(255,255,255,0.55); font-size:0.75rem; margin-top:2px; }

    /* ── Stepper ───────────────────────────────────────────── */
    .az-stepper {
        display:flex; align-items:center;
        padding:18px 28px 0; background:var(--c-bg); gap:0;
    }
    .az-step { display:flex; align-items:center; flex:1; position:relative; }
    .az-step-circle {
        width:32px; height:32px; border-radius:50%;
        background:white; border:2px solid var(--c-border);
        color:var(--c-sub); font-size:0.78rem; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        transition:all 0.3s ease; flex-shrink:0; z-index:1;
    }
    .az-step.active .az-step-circle {
        background:var(--c-blue); border-color:var(--c-blue); color:white;
        box-shadow:0 0 0 4px rgba(59,130,246,0.2);
    }
    .az-step.done .az-step-circle { background:var(--c-mint); border-color:var(--c-mint); color:white; }
    .az-step-label { font-size:0.72rem; font-weight:600; color:var(--c-sub); margin-left:8px; white-space:nowrap; transition:color 0.3s; }
    .az-step.active .az-step-label { color:var(--c-blue); }
    .az-step.done .az-step-label   { color:var(--c-mint); }
    .az-step-line { flex:1; height:2px; background:var(--c-border); margin:0 10px; transition:background 0.4s; }
    .az-step-line.done { background:var(--c-mint); }

    /* ── Step panels ───────────────────────────────────────── */
    .az-step-panel { display:none; animation:fadeSlide 0.3s ease; }
    .az-step-panel.active { display:block; }
    @keyframes fadeSlide {
        from { opacity:0; transform:translateX(12px); }
        to   { opacity:1; transform:translateX(0); }
    }

    /* ── Form fields ───────────────────────────────────────── */
    .az-modal .modal-body { background:var(--c-bg); padding:20px 28px 8px; }
    .az-field-group { display:grid; gap:14px; }
    .az-field { display:flex; flex-direction:column; gap:5px; }
    .az-label { font-size:0.73rem; font-weight:700; color:var(--c-sub); text-transform:uppercase; letter-spacing:0.06em; }
    .az-label .required { color:var(--c-red); margin-left:2px; }
    .az-input {
        border:1.5px solid var(--c-border); border-radius:8px;
        padding:9px 13px; font-size:0.875rem; color:var(--c-text);
        background:white; transition:border-color 0.2s, box-shadow 0.2s;
        outline:none; width:100%;
    }
    .az-input:focus { border-color:var(--c-blue); box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
    .az-input.is-invalid { border-color:var(--c-red); }
    .az-select {
        appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7A99' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; padding-right:32px;
    }
    .az-section-title {
        font-size:0.8rem; font-weight:700; color:var(--c-navy);
        letter-spacing:0.08em; text-transform:uppercase;
        display:flex; align-items:center; gap:8px;
        margin-bottom:12px; padding-bottom:8px; border-bottom:1.5px solid var(--c-border);
    }
    .az-section-title i { color:var(--c-blue); font-size:0.9rem; }

    /* ── Modal footer ──────────────────────────────────────── */
    .az-modal .modal-footer {
        background:white; border-top:1.5px solid var(--c-border);
        padding:14px 28px; display:flex; justify-content:space-between; align-items:center;
    }
    .az-btn-prev {
        background:none; border:1.5px solid var(--c-border); color:var(--c-sub);
        border-radius:8px; padding:8px 18px; font-size:0.82rem; font-weight:600;
        cursor:pointer; transition:all 0.2s;
    }
    .az-btn-prev:hover { border-color:var(--c-blue); color:var(--c-blue); }
    .az-btn-next {
        background:var(--c-blue); border:none; color:white; border-radius:8px;
        padding:8px 22px; font-size:0.82rem; font-weight:700; cursor:pointer;
        transition:all 0.2s; display:flex; align-items:center; gap:6px;
    }
    .az-btn-next:hover { background:#2563EB; transform:translateY(-1px); }
    .az-btn-submit {
        background:linear-gradient(135deg, var(--c-mint), #059669);
        border:none; color:white; border-radius:8px; padding:8px 22px;
        font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        display:none; align-items:center; gap:6px;
    }
    .az-btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,0.3); }
    .az-step-counter { font-size:0.75rem; color:var(--c-sub); }

    /* ── Edit modal ────────────────────────────────────────── */
    .az-edit-header {
        background:linear-gradient(135deg, #1a2b4a 0%, #2d4a8a 100%);
        padding:16px 24px; display:flex; align-items:center; justify-content:space-between;
        border-radius:20px 20px 0 0;
    }
    .az-edit-badge {
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
        border-radius:20px; padding:4px 12px; font-size:0.72rem; font-weight:700;
        color:white; letter-spacing:0.05em;
    }
    .az-edit-title { color:white; font-weight:700; font-size:1rem; margin:0; }
    .az-edit-subtitle { color:rgba(255,255,255,0.6); font-size:0.78rem; margin:2px 0 0; }
    .az-lock-btn {
        background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.3);
        color:white; border-radius:8px; padding:6px 14px; font-size:0.78rem; font-weight:700;
        cursor:pointer; transition:all 0.25s; display:flex; align-items:center; gap:6px;
    }
    .az-lock-btn:hover { background:rgba(255,255,255,0.25); }
    .az-lock-btn.editing { background:rgba(239,68,68,0.2); border-color:rgba(239,68,68,0.4); color:#FCA5A5; }

    /* ── Tabs ──────────────────────────────────────────────── */
    .az-tabs {
        display:flex; gap:2px; background:var(--c-bg);
        padding:8px 24px 0; border-bottom:2px solid var(--c-border);
    }
    .az-tab {
        padding:8px 16px; font-size:0.78rem; font-weight:600; color:var(--c-sub);
        cursor:pointer; border-radius:8px 8px 0 0; border:none; background:none;
        border-bottom:2px solid transparent; margin-bottom:-2px; transition:all 0.2s;
    }
    .az-tab.active { color:var(--c-blue); background:white; border-bottom-color:var(--c-blue); }
    .az-tab-panel { display:none; padding:20px 24px; background:var(--c-bg); }
    .az-tab-panel.active { display:block; animation:fadeSlide 0.25s ease; }

    /* ── Solde card ────────────────────────────────────────── */
    .az-solde-card {
        background:linear-gradient(135deg, var(--c-navy), #2D4A8A);
        border-radius:12px; padding:16px 20px; color:white;
        display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;
    }
    .az-solde-amount { font-size:1.8rem; font-weight:800; }
    .az-solde-label  { font-size:0.72rem; opacity:0.7; text-transform:uppercase; letter-spacing:0.08em; }

    /* ── Status pill ───────────────────────────────────────── */
    .az-status-pill {
        display:inline-flex; align-items:center; gap:6px; padding:6px 14px;
        border-radius:20px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s;
    }
    .az-status-pill.active  { background:#D1FAE5; color:#065F46; }
    .az-status-pill.blocked { background:#FEE2E2; color:#991B1B; }

    /* ── Save btn ──────────────────────────────────────────── */
    .az-save-btn {
        background:linear-gradient(135deg, var(--c-mint), #059669);
        border:none; color:white; border-radius:8px; padding:8px 20px;
        font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        display:none; align-items:center; gap:6px;
    }
    .az-save-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,0.3); }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-inner">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="container mt-4">

                {{-- ── Header ─────────────────────────────────────────── --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Liste des Clients</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-success btn-round" data-bs-toggle="modal" data-bs-target="#createItemModal">
                            Nouveau <i class="fas fa-plus-circle ms-2"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">
                            Quitter <i class="fas fa-sign-out-alt ms-2"></i>
                        </button>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════
                     MODAL CRÉATION — Stepper 3 étapes
                     ════════════════════════════════════════════════════ --}}
                <div class="modal fade az-modal" id="createItemModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <div class="modal-title">Nouveau Client</div>
                                    <div class="modal-subtitle">Remplissez les informations en 3 étapes</div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            {{-- Stepper --}}
                            <div class="az-stepper">
                                <div class="az-step active" data-step="1">
                                    <div class="az-step-circle">1</div>
                                    <div class="az-step-label">Identité</div>
                                </div>
                                <div class="az-step-line" id="line1"></div>
                                <div class="az-step" data-step="2">
                                    <div class="az-step-circle">2</div>
                                    <div class="az-step-label">Contact</div>
                                </div>
                                <div class="az-step-line" id="line2"></div>
                                <div class="az-step" data-step="3">
                                    <div class="az-step-circle">3</div>
                                    <div class="az-step-label">Finances</div>
                                </div>
                            </div>

                            <form action="{{ route('customer.store') }}" method="POST" id="createCustomerForm">
                                @csrf
                                <div class="modal-body">

                                    {{-- Étape 1 --}}
                                    <div class="az-step-panel active" id="panel-1">
                                        <div class="az-section-title"><i class="fas fa-user"></i> Identité du client</div>
                                        <div class="az-field-group" style="grid-template-columns:2fr 1fr;">
                                            <div class="az-field">
                                                <label class="az-label">Nom complet <span class="required">*</span></label>
                                                <input type="text" name="name" class="az-input" required placeholder="Ex: DUPONT Jean">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Type <span class="required">*</span></label>
                                                <select name="type" class="az-input az-select" required>
                                                    <option value="particulier">Particulier</option>
                                                    <option value="jobber">Jobber</option>
                                                    <option value="professionnel">Professionnel</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                            <div class="az-field">
                                                <label class="az-label">Adresse</label>
                                                <input type="text" name="address" class="az-input" placeholder="Rue, numéro...">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Code Postal</label>
                                                <input type="text" name="address_delivery" class="az-input" placeholder="75001">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Ville</label>
                                                <input type="text" name="city" class="az-input" placeholder="Paris">
                                            </div>
                                        </div>
                                        <div class="az-field-group" style="grid-template-columns:2fr 1fr;margin-top:14px;">
                                            <div class="az-field">
                                                <label class="az-label">SIRET</label>
                                                <input type="text" name="matfiscal" class="az-input" placeholder="12345678901234">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Pays</label>
                                                <input type="text" name="country" class="az-input" value="France">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Étape 2 --}}
                                    <div class="az-step-panel" id="panel-2">
                                        <div class="az-section-title"><i class="fas fa-address-card"></i> Coordonnées</div>
                                        <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                            <div class="az-field">
                                                <label class="az-label">Email</label>
                                                <input type="email" name="email" class="az-input" placeholder="contact@exemple.fr">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">IBAN</label>
                                                <input type="text" name="bank_no" class="az-input" placeholder="FR76...">
                                            </div>
                                        </div>
                                        <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                            <div class="az-field">
                                                <label class="az-label">Téléphone principal</label>
                                                <input type="text" name="phone1" class="az-input" placeholder="06 00 00 00 00">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Téléphone secondaire</label>
                                                <input type="text" name="phone2" class="az-input" placeholder="06 00 00 00 00">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Étape 3 --}}
                                    <div class="az-step-panel" id="panel-3">
                                        <div class="az-section-title"><i class="fas fa-euro-sign"></i> Paramètres financiers</div>
                                        <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                            <div class="az-field">
                                                <label class="az-label">Condition de paiement <span class="required">*</span></label>
                                                <select name="payment_term_id" class="az-input az-select" required>
                                                    @foreach($paymentTerms as $term)
                                                        <option value="{{ $term->id }}">{{ $term->label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Mode de paiement <span class="required">*</span></label>
                                                <select name="payment_mode_id" class="az-input az-select" required>
                                                    @foreach($paymentModes as $mode)
                                                        <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                            <div class="az-field">
                                                <label class="az-label">TVA <span class="required">*</span></label>
                                                <select name="tva_group_id" class="az-input az-select" required>
                                                    @foreach($tvaGroups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->rate }}%)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Groupe remise <span class="required">*</span></label>
                                                <select name="discount_group_id" class="az-input az-select" required>
                                                    @foreach($discountGroups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->discount_rate }}%)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Plafond (€)</label>
                                                <input type="number" step="0.01" name="plafond" class="az-input" value="0">
                                            </div>
                                        </div>
                                        <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                            <div class="az-field">
                                                <label class="az-label">Risque</label>
                                                <input type="number" name="risque" class="az-input" value="0">
                                            </div>
                                            <div class="az-field">
                                                <label class="az-label">Solde initial (€)</label>
                                                <input type="number" step="0.01" name="solde" class="az-input" value="0" readonly style="background:#f0f4ff;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="az-btn-prev" id="btnPrev" style="display:none;" onclick="stepNav(-1)">
            <i class="fas fa-arrow-left"></i> Précédent
        </button>
        <span class="az-step-counter" id="stepCounter">Étape 1 sur 3</span>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="az-btn-next" id="btnNext" onclick="stepNav(1)">
            Suivant <i class="fas fa-arrow-right"></i>
        </button>

        <!-- Ces deux boutons n’apparaissent que sur l’étape 3 -->
        <button type="submit" name="action" value="create" class="az-btn-submit" id="btnSubmit" style="display:none;">
            <i class="fas fa-check"></i> Créer
        </button>
        <button type="submit" name="action" value="create_and_close" class="az-btn-submit" id="btnSubmitClose" style="display:none; background:linear-gradient(135deg,#3B82F6,#2563EB);">
            <i class="fas fa-check-double"></i> Créer et fermer
        </button>
    </div>
</div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- ── Fin modal création ─────────────────────────── --}}

                {{-- ── Filtres ────────────────────────────────────── --}}
                <div class="mb-4">
                    <form method="GET" action="{{ route('customer.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                        <input type="text" name="search" class="form-control form-control-sm" style="width:250px;"
                               placeholder="🔍 Recherche (nom, code, téléphone, email...)" value="{{ request('search') }}">
                        <select name="status" class="form-select form-select-sm" style="width:120px;">
                            <option value="">Statut (Tous)</option>
                            <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>🟢 Actif</option>
                            <option value="blocked"  {{ request('status')=='blocked'  ? 'selected':'' }}>🔴 Bloqué</option>
                        </select>
                        <select name="city" class="form-select form-select-sm" style="width:160px;">
                            <option value="">Ville (Toutes)</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city')==$city ? 'selected':'' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" name="min_solde" class="form-control form-control-sm" style="width:110px;" placeholder="Solde min" value="{{ request('min_solde') }}">
                        <span class="mx-1 text-muted">à</span>
                        <input type="number" step="0.01" name="max_solde" class="form-control form-control-sm" style="width:110px;" placeholder="Solde max" value="{{ request('max_solde') }}">
                        <button type="submit" name="action" value="filter" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fas fa-filter me-1"></i> Filtrer
                        </button>
                        <button type="submit" name="action" value="export"
                                formaction="{{ route('customers.export') . '?' . http_build_query(request()->query()) }}"
                                class="btn btn-outline-success btn-sm px-3">
                            <i class="fas fa-file-excel me-1"></i> EXCEL
                        </button>
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-undo me-1"></i> Réinitialiser
                        </a>
                    </form>
                </div>

                {{-- ── Tableau ────────────────────────────────────── --}}
                @if ($customers->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-text-small" id="itemsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Adresse / Ville</th>
                                    <th>Contact</th>
                                    <th>Solde</th>
                                    <th>Véhicules</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                <tr>
                                    <td>🧑‍💼{{ $customer->code }}<br>{{ $customer->name }}
                                        {{ $customer->blocked ? '🔴' : '🟢' }}
                                    </td>
                                    <td>
                                        @switch($customer->type)
                                            @case('particulier') <span class="badge bg-primary">Particulier</span> @break
                                            @case('jobber')      <span class="badge bg-warning text-dark">Jobber</span> @break
                                            @case('professionnel') <span class="badge bg-success">Professionnel</span> @break
                                        @endswitch
                                    </td>
                                    <td>{{ $customer->address }}<br>🏴󠁢󠁹󠁭󠁩󠁿{{ $customer->city }}</td>
                                    <td>📞 {{ $customer->phone1 }}<br>📧 {{ $customer->email }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary solde-btn"
                                                data-bs-toggle="modal" data-bs-target="#accountingModal{{ $customer->id }}"
                                                data-customer-id="{{ $customer->id }}">
                                            {{ number_format($customer->solde, 2, ',', ' ') }} €
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#viewVehiclesModal{{ $customer->id }}">
                                            <i class="fas fa-car"></i> <span class="badge bg-success">{{ $customer->vehicles->count() }}</span>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $customer->id }}">
                                            Editer <i class="fas fa-edit"></i>
                                        </button>
                                        <hr>
                                        <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce client ?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Supp. <i class="fas fa-trash-alt"></i></button>
                                        </form>

                                        {{-- ── Accounting Modal ──────────────────────── --}}
                                        <div class="modal fade accounting-modal" id="accountingModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ecritures comptables — {{ $customer->name }}</h5>
                                                        <button type="button" class="btn btn-secondary btn-round ms-2 dropdown-toggle" onclick="showBalance({{ $customer->id }})">
                                                            <i class="fas fa-balance-scale me-1"></i> Balance
                                                        </button>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="balanceSummary{{ $customer->id }}" class="card mb-3" style="display:none;">
                                                            <div class="card-body">
                                                                <h6 class="card-title text-primary">Balance Comptable</h6>
                                                                <table class="table table-sm table-bordered">
                                                                    <thead class="table-light"><tr><th>Total Débits</th><th>Total Crédits</th><th>Solde Net</th></tr></thead>
                                                                    <tbody><tr>
                                                                        <td id="debits{{ $customer->id }}">0,00 €</td>
                                                                        <td id="credits{{ $customer->id }}">0,00 €</td>
                                                                        <td id="balance{{ $customer->id }}">0,00 €</td>
                                                                    </tr></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <form id="accountingFilterForm{{ $customer->id }}" class="d-flex flex-wrap gap-2 mb-3">
                                                            <select name="type" class="form-select form-select-sm" style="width:200px;">
                                                                <option value="">Type (Tous)</option>
                                                                <option value="Factures">Factures</option>
                                                                <option value="Avoirs">Avoirs</option>
                                                                <option value="Règlements">Règlements</option>
                                                            </select>
                                                            <input type="date" name="start_date" class="form-control form-control-sm" style="width:150px;">
                                                            <input type="date" name="end_date"   class="form-control form-control-sm" style="width:150px;">
                                                            <button type="submit" class="btn btn-outline-primary btn-sm px-3"><i class="fas fa-filter me-1"></i> Filtrer</button>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="resetAccountingFilter({{ $customer->id }})"><i class="fas fa-undo me-1"></i> Réinitialiser</button>
                                                        </form>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover accounting-table">
                                                                <thead class="table-dark"><tr><th>Type</th><th>Num Document</th><th>Date</th><th>Montant TTC</th><th>Statut</th></tr></thead>
                                                                <tbody id="accountingEntries{{ $customer->id }}">
                                                                    <tr><td colspan="5" class="text-center">Chargement...</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── View Vehicles Modal ────────────────────── --}}
                                        <div class="modal fade" id="viewVehiclesModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Véhicules — {{ $customer->name }}</h5>
                                                        <button type="button" class="btn btn-outline-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#addVehicleModal{{ $customer->id }}">
                                                            <i class="fas fa-car"></i> Associer un véhicule
                                                        </button>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($customer->vehicles && $customer->vehicles->count())
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover table-text-small">
                                                                    <thead class="table-dark">
                                                                        <tr><th>Immatriculation</th><th>Marque</th><th>Modèle</th><th>Motorisation</th><th>Actions</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($customer->vehicles as $vehicle)
                                                                        <tr>
                                                                            <td>{{ $vehicle->license_plate }}</td>
                                                                            <td>{{ $vehicle->brand_name }}</td>
                                                                            <td>{{ $vehicle->model_name }}</td>
                                                                            <td>{{ $vehicle->engine_description }}</td>
                                                                            <td>
                                                                                <a href="{{ route('customer.vehicle.catalog', [$customer->id, $vehicle->id]) }}"
                                                                                   class="btn btn-outline-primary btn-sm"
                                                                                   onclick="window.open(this.href,'popupWindow','width=1000,height=700,scrollbars=yes');return false;">
                                                                                    <i class="fas fa-list"></i> Catalogue
                                                                                </a>
                                                                                <form action="{{ route('customer.vehicle.destroy', [$customer->id, $vehicle->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                                                                    @csrf @method('DELETE')
                                                                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <p class="text-muted">Aucun véhicule associé.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── Add Vehicle Modal ──────────────────────── --}}
                                        <div class="modal fade" id="addVehicleModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Associer un véhicule — {{ $customer->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('customer.vehicle.store', $customer->id) }}" id="vehicleForm{{ $customer->id }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Immatriculation :</label>
                                                                <input type="text" id="license_plate_{{ $customer->id }}" name="license_plate" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Marque :</label>
                                                                <select id="brand_id_{{ $customer->id }}" name="brand_id" class="form-control select2-brand" style="width:100%;" required>
                                                                    <option value="">Rechercher une marque...</option>
                                                                    @foreach($brands as $brand)
                                                                        <option value="{{ $brand['id'] }}" data-name="{{ $brand['name'] }}">{{ $brand['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="brand_name" id="brand_name_{{ $customer->id }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Modèle :</label>
                                                                <select id="model_id_{{ $customer->id }}" name="model_id" class="form-control select2-model" style="width:100%;" required>
                                                                    <option value="">Rechercher un modèle...</option>
                                                                </select>
                                                                <input type="hidden" name="model_name" id="model_name_{{ $customer->id }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Motorisation :</label>
                                                                <select id="engine_id_{{ $customer->id }}" name="engine_id" class="form-control select2-engine" style="width:100%;" required>
                                                                    <option value="">Rechercher une motorisation...</option>
                                                                </select>
                                                                <input type="hidden" name="engine_description" id="engine_description_{{ $customer->id }}">
                                                                <input type="hidden" name="linkage_target_id" id="linkage_target_id_{{ $customer->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-success">Associer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                                {{-- ════════════════════════════════════════════════════
                                     MODAL ÉDITION — Tabs + Lock/Unlock
                                     ════════════════════════════════════════════════════ --}}
                                <div class="modal fade az-modal" id="editItemModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="az-edit-header">
                                                <div>
                                                    <p class="az-edit-title">{{ $customer->name }}</p>
                                                    <p class="az-edit-subtitle">{{ $customer->code }} · {{ ucfirst($customer->type) }}</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="az-edit-badge">
                                                        {{ $customer->blocked ? '🚫 Bloqué' : '✅ Actif' }}
                                                    </span>
                                                    <button type="button" class="az-lock-btn" id="lockBtn{{ $customer->id }}" onclick="toggleEdit({{ $customer->id }})">
                                                        <i class="fas fa-lock"></i> Modifier
                                                    </button>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                            </div>

                                            <div class="az-tabs">
                                                <button class="az-tab active" onclick="showTab({{ $customer->id }}, 'info', this)">
                                                    <i class="fas fa-user me-1"></i> Informations
                                                </button>
                                                <button class="az-tab" onclick="showTab({{ $customer->id }}, 'finance', this)">
                                                    <i class="fas fa-euro-sign me-1"></i> Finances
                                                </button>
                                                <button class="az-tab" onclick="showTab({{ $customer->id }}, 'statut', this)">
                                                    <i class="fas fa-shield-alt me-1"></i> Statut
                                                </button>
                                            </div>

                                            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                                                @csrf @method('PUT')

                                                {{-- Tab Informations --}}
                                                <div class="az-tab-panel active" id="tab-info-{{ $customer->id }}">
                                                    <div class="az-field-group" style="grid-template-columns:2fr 1fr;">
                                                        <div class="az-field">
                                                            <label class="az-label">Nom</label>
                                                            <input type="text" name="name" class="az-input ef-{{ $customer->id }}" value="{{ $customer->name }}" required disabled>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Type</label>
                                                            <select name="type" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                                <option value="particulier" {{ $customer->type=='particulier'?'selected':'' }}>Particulier</option>
                                                                <option value="jobber"      {{ $customer->type=='jobber'?'selected':'' }}>Jobber</option>
                                                                <option value="professionnel" {{ $customer->type=='professionnel'?'selected':'' }}>Professionnel</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                                        <div class="az-field">
                                                            <label class="az-label">Email</label>
                                                            <input type="email" name="email" class="az-input ef-{{ $customer->id }}" value="{{ $customer->email }}" disabled>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Téléphone 1</label>
                                                            <input type="text" name="phone1" class="az-input ef-{{ $customer->id }}" value="{{ $customer->phone1 }}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                                        <div class="az-field">
                                                            <label class="az-label">Téléphone 2</label>
                                                            <input type="text" name="phone2" class="az-input ef-{{ $customer->id }}" value="{{ $customer->phone2 }}" disabled>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Ville</label>
                                                            <input type="text" name="city" class="az-input ef-{{ $customer->id }}" value="{{ $customer->city }}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                                        <div class="az-field">
                                                            <label class="az-label">Adresse</label>
                                                            <input type="text" name="address" class="az-input ef-{{ $customer->id }}" value="{{ $customer->address }}" disabled>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Code Postal</label>
                                                            <input type="text" name="address_delivery" class="az-input ef-{{ $customer->id }}" value="{{ $customer->address_delivery }}" disabled>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Pays</label>
                                                            <input type="text" name="country" class="az-input ef-{{ $customer->id }}" value="{{ $customer->country }}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;margin-top:14px;">
                                                        <div class="az-field">
                                                            <label class="az-label">SIRET</label>
                                                            <input type="text" name="matfiscal" class="az-input ef-{{ $customer->id }}" value="{{ $customer->matfiscal }}" disabled>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">IBAN</label>
                                                            <input type="text" name="bank_no" class="az-input ef-{{ $customer->id }}" value="{{ $customer->bank_no }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tab Finances --}}
                                                <div class="az-tab-panel" id="tab-finance-{{ $customer->id }}">
                                                    <div class="az-solde-card">
                                                        <div>
                                                            <div class="az-solde-label">Solde actuel</div>
                                                            <div class="az-solde-amount">{{ number_format($customer->solde ?? 0, 2, ',', ' ') }} €</div>
                                                        </div>
                                                        <i class="fas fa-wallet" style="font-size:2rem;opacity:0.3;"></i>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr;">
                                                        <div class="az-field">
                                                            <label class="az-label">Condition de paiement</label>
                                                            <select name="payment_term_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                                @foreach($paymentTerms as $term)
                                                                    <option value="{{ $term->id }}" {{ $customer->payment_term_id==$term->id?'selected':'' }}>{{ $term->label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Mode de paiement</label>
                                                            <select name="payment_mode_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                                @foreach($paymentModes as $mode)
                                                                    <option value="{{ $mode->id }}" {{ $customer->payment_mode_id==$mode->id?'selected':'' }}>{{ $mode->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr 1fr 1fr;margin-top:14px;">
                                                        <div class="az-field">
                                                            <label class="az-label">TVA</label>
                                                            <select name="tva_group_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                                @foreach($tvaGroups as $group)
                                                                    <option value="{{ $group->id }}" {{ $customer->tva_group_id==$group->id?'selected':'' }}>{{ $group->name }} ({{ $group->rate }}%)</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Groupe remise</label>
                                                            <select name="discount_group_id" class="az-input az-select ef-{{ $customer->id }}" disabled>
                                                                @foreach($discountGroups as $group)
                                                                    <option value="{{ $group->id }}" {{ $customer->discount_group_id==$group->id?'selected':'' }}>{{ $group->name }} ({{ $group->discount_rate }}%)</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="az-field">
                                                            <label class="az-label">Plafond (€)</label>
                                                            <input type="number" step="0.01" name="plafond" class="az-input ef-{{ $customer->id }}" value="{{ $customer->plafond ?? 0 }}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="az-field-group" style="grid-template-columns:1fr;margin-top:14px;">
                                                        <div class="az-field">
                                                            <label class="az-label">Risque</label>
                                                            <input type="number" name="risque" class="az-input ef-{{ $customer->id }}" value="{{ $customer->risque }}" disabled style="max-width:200px;">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tab Statut --}}
                                                <div class="az-tab-panel" id="tab-statut-{{ $customer->id }}">
                                                    <input type="hidden" name="blocked" value="0">
                                                    <div style="background:white;border-radius:12px;padding:20px;border:1.5px solid var(--c-border);margin-bottom:16px;">
                                                        <div style="font-weight:700;color:var(--c-navy);margin-bottom:10px;">Statut du compte</div>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <span class="az-status-pill {{ $customer->blocked ? 'blocked' : 'active' }}"
                                                                  id="statusPill{{ $customer->id }}"
                                                                  onclick="toggleStatus({{ $customer->id }})">
                                                                {{ $customer->blocked ? '🚫 Client Bloqué' : '✅ Client Actif' }}
                                                            </span>
                                                            <input type="checkbox" name="blocked" value="1"
                                                                   id="blockedCheck{{ $customer->id }}"
                                                                   {{ $customer->blocked ? 'checked' : '' }}
                                                                   class="ef-{{ $customer->id }}" disabled style="display:none;">
                                                            <small style="color:var(--c-sub);font-size:0.75rem;">Cliquez sur la pastille pour changer le statut</small>
                                                        </div>
                                                        <div style="margin-top:12px;padding:10px;background:var(--c-bg);border-radius:8px;font-size:0.75rem;color:var(--c-sub);line-height:1.6;">
                                                            ⚠️ Le blocage concerne uniquement l'expédition des ventes. La facturation reste possible même si le client est bloqué.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer" style="justify-content:flex-end;background:white;border-top:1.5px solid var(--c-border);padding:14px 24px;">
                                                    <button type="button" class="az-btn-prev" data-bs-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="az-save-btn" id="saveBtn{{ $customer->id }}">
                                                        <i class="fas fa-check"></i> Sauvegarder
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- ── Fin modal édition ──────────────────────── --}}

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $customers->appends(request()->query())->links() }}
                    </div>

                @else
                    <p class="text-muted text-center">Aucun client trouvé.</p>
                @endif

            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid d-flex justify-content-between">
            <div class="copyright">© AZ NEGOCE. All Rights Reserved.</div>
            <div>by <a target="_blank" href="https://themewagon.com/">AZ NEGOCE</a>.</div>
        </div>
    </footer>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
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
    // ════════════════════════════════════════════════════════
    // STEPPER — Création client
    // ════════════════════════════════════════════════════════
    var currentStep = 1;
    var totalSteps  = 3;

    function stepNav(dir) {
        var next = currentStep + dir;
        if (next < 1 || next > totalSteps) return;

        if (dir > 0) {
            var panel = document.getElementById('panel-' + currentStep);
            var required = panel.querySelectorAll('[required]');
            var valid = true;
            required.forEach(function(el) {
                if (!el.value.trim()) {
                    el.classList.add('is-invalid');
                    el.style.borderColor = 'var(--c-red)';
                    valid = false;
                } else {
                    el.style.borderColor = '';
                    el.classList.remove('is-invalid');
                }
            });
            if (!valid) return;
        }

        document.getElementById('panel-' + currentStep).classList.remove('active');
        currentStep = next;
        document.getElementById('panel-' + currentStep).classList.add('active');

        for (var i = 1; i <= totalSteps; i++) {
            var step = document.querySelector('[data-step="' + i + '"]');
            step.classList.remove('active', 'done');
            if (i < currentStep)        step.classList.add('done');
            else if (i === currentStep) step.classList.add('active');
            var circle = step.querySelector('.az-step-circle');
            circle.innerHTML = i < currentStep ? '<i class="fas fa-check" style="font-size:0.7rem;"></i>' : i;
        }
        for (var l = 1; l < totalSteps; l++) {
            var line = document.getElementById('line' + l);
            if (line) line.classList.toggle('done', l < currentStep);
        }

        document.getElementById('btnPrev').style.display   = currentStep > 1 ? 'flex' : 'none';
        var btnNext   = document.getElementById('btnNext');
        var btnSubmit = document.getElementById('btnSubmit');
        if (currentStep === totalSteps) {
            btnNext.style.display   = 'none';
            btnSubmit.style.display = 'flex';
            document.getElementById('btnSubmitClose').style.display = 'flex';
        } else {
            btnNext.style.display   = 'flex';
            btnSubmit.style.display = 'none';
            document.getElementById('btnSubmitClose').style.display = 'none';
        }
        document.getElementById('stepCounter').textContent = 'Étape ' + currentStep + ' sur ' + totalSteps;
    }

    document.getElementById('createItemModal').addEventListener('hidden.bs.modal', function() {
        currentStep = 1;
        for (var i = 1; i <= totalSteps; i++) {
            document.getElementById('panel-' + i).classList.toggle('active', i === 1);
            var step = document.querySelector('[data-step="' + i + '"]');
            step.classList.remove('active', 'done');
            if (i === 1) step.classList.add('active');
            step.querySelector('.az-step-circle').textContent = i;
            var line = document.getElementById('line' + i);
            if (line) line.classList.remove('done');
        }
        document.getElementById('btnPrev').style.display   = 'none';
        document.getElementById('btnNext').style.display   = 'flex';
        document.getElementById('btnSubmit').style.display = 'none';
        document.getElementById('stepCounter').textContent = 'Étape 1 sur 3';
        document.getElementById('createCustomerForm').reset();
    });

    // ════════════════════════════════════════════════════════
    // EDIT MODAL — Lock/unlock + Tabs
    // ════════════════════════════════════════════════════════
    var editState = {};

    function toggleEdit(id) {
        var fields  = document.querySelectorAll('.ef-' + id);
        var btn     = document.getElementById('lockBtn' + id);
        var saveBtn = document.getElementById('saveBtn' + id);
        var isEditing = editState[id] || false;

        if (!isEditing) {
            fields.forEach(function(f) { f.removeAttribute('disabled'); });
            btn.classList.add('editing');
            btn.innerHTML = '<i class="fas fa-times"></i> Annuler';
            saveBtn.style.display = 'flex';
            editState[id] = true;
        } else {
            fields.forEach(function(f) { f.setAttribute('disabled', true); });
            btn.classList.remove('editing');
            btn.innerHTML = '<i class="fas fa-lock"></i> Modifier';
            saveBtn.style.display = 'none';
            editState[id] = false;
        }
    }

    function showTab(customerId, tab, el) {
        document.querySelectorAll('#editItemModal' + customerId + ' .az-tab-panel').forEach(function(p) { p.classList.remove('active'); });
        document.querySelectorAll('#editItemModal' + customerId + ' .az-tab').forEach(function(t) { t.classList.remove('active'); });
        document.getElementById('tab-' + tab + '-' + customerId).classList.add('active');
        el.classList.add('active');
    }

    function toggleStatus(id) {
        var check = document.getElementById('blockedCheck' + id);
        var pill  = document.getElementById('statusPill' + id);
        if (check.disabled) return;
        check.checked = !check.checked;
        if (check.checked) {
            pill.className = 'az-status-pill blocked';
            pill.textContent = '🚫 Client Bloqué';
        } else {
            pill.className = 'az-status-pill active';
            pill.textContent = '✅ Client Actif';
        }
    }

    // ════════════════════════════════════════════════════════
    // ACCOUNTING
    // ════════════════════════════════════════════════════════
    (function() {
        var cache = {};
        document.querySelectorAll('.solde-btn').forEach(function(button) {
            var modal      = document.getElementById('accountingModal' + button.dataset.customerId);
            var filterForm = document.getElementById('accountingFilterForm' + button.dataset.customerId);

            modal.addEventListener('show.bs.modal', function() {
                var cid   = button.dataset.customerId;
                var tbody = document.getElementById('accountingEntries' + cid);
                if (cache[cid]) { applyFilters(cid); return; }
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Chargement...</td></tr>';
                fetch('/customers/' + cid + '/accounting-entries', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) { cache[cid] = data.entries || []; applyFilters(cid); })
                    .catch(function() {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erreur de chargement.</td></tr>';
                    });
            });

            if (filterForm) {
                filterForm.addEventListener('submit', function(e) { e.preventDefault(); applyFilters(button.dataset.customerId); });
            }
        });

        window.resetAccountingFilter = function(cid) {
            var f = document.getElementById('accountingFilterForm' + cid);
            var b = document.getElementById('balanceSummary' + cid);
            if (f) { f.reset(); b.style.display = 'none'; applyFilters(cid); }
        };
        window.showBalance = function(cid) {
            document.getElementById('balanceSummary' + cid).style.display = 'block';
            applyFilters(cid);
        };

        function applyFilters(cid) {
            var tbody      = document.getElementById('accountingEntries' + cid);
            var filterForm = document.getElementById('accountingFilterForm' + cid);
            var formData   = new FormData(filterForm);
            var typeFilter = formData.get('type') || '';
            var startDate  = formData.get('start_date') ? new Date(formData.get('start_date')) : null;
            var endDate    = formData.get('end_date')   ? new Date(formData.get('end_date'))   : null;
            var entries    = cache[cid] || [];

            if (typeFilter) {
                entries = entries.filter(function(e) {
                    if (typeFilter === 'Factures')   return e.type === 'Facture';
                    if (typeFilter === 'Avoirs')     return e.type === 'Avoir';
                    if (typeFilter === 'Règlements') return e.type !== 'Facture' && e.type !== 'Avoir';
                    return true;
                });
            }
            if (startDate || endDate) {
                entries = entries.filter(function(e) {
                    if (!e.date || e.date === '-') return false;
                    var p = e.date.split('/');
                    var d = new Date(p[2] + '-' + p[1] + '-' + p[0]);
                    if (startDate && d < startDate) return false;
                    if (endDate   && d > endDate)   return false;
                    return true;
                });
            }

            var debits = 0, credits = 0;
            entries.forEach(function(e) {
                if (e.type === 'Facture') debits  += parseFloat(e.amount) || 0;
                else                      credits += parseFloat(e.amount) || 0;
            });
            var balance = debits - credits;
            var dEl = document.getElementById('debits'  + cid);
            var cEl = document.getElementById('credits' + cid);
            var bEl = document.getElementById('balance' + cid);
            if (dEl) { dEl.textContent = debits.toFixed(2).replace('.', ',')  + ' €'; }
            if (cEl) { cEl.textContent = credits.toFixed(2).replace('.', ',') + ' €'; }
            if (bEl) { bEl.textContent = balance.toFixed(2).replace('.', ',') + ' €'; bEl.className = balance >= 0 ? 'text-success' : 'text-danger'; }

            tbody.innerHTML = '';
            if (!entries.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucune écriture trouvée.</td></tr>';
                return;
            }
            entries.forEach(function(e) {
                var row = document.createElement('tr');
                row.innerHTML = '<td>' + (e.type||'-') + '</td><td>' + (e.numdoc||e.reference||'-') + '</td><td>' + (e.date||'-') + '</td><td>' + (e.amount !== undefined ? Number(e.amount).toFixed(2).replace('.', ',') : '-') + ' €</td><td>' + (e.status||'-') + '</td>';
                tbody.appendChild(row);
            });
        }
    })();

    // ════════════════════════════════════════════════════════
    // VEHICLE SELECT2
    // ════════════════════════════════════════════════════════
    document.addEventListener("DOMContentLoaded", function() {
        function initVehicleSelect2(modal) {
            var cid = modal.id.replace('addVehicleModal', '');
            [$('#brand_id_' + cid), $('#model_id_' + cid), $('#engine_id_' + cid)].forEach(function($el) {
                if ($el.hasClass('select2-hidden-accessible')) $el.select2('destroy');
            });
            $('#brand_id_'  + cid).select2({ placeholder:"Rechercher une marque...",       allowClear:true, width:'100%', dropdownParent: modal });
            $('#model_id_'  + cid).select2({ placeholder:"Rechercher un modèle...",        allowClear:true, width:'100%', dropdownParent: modal });
            $('#engine_id_' + cid).select2({ placeholder:"Rechercher une motorisation...", allowClear:true, width:'100%', dropdownParent: modal });
        }

        $(document).on('shown.bs.modal', '[id^="addVehicleModal"]', function() { initVehicleSelect2(this); });

        $(document).on('change', '[id^="brand_id_"]', function() {
            var cid = this.id.replace('brand_id_', '');
            var brandId = this.value;
            $('#brand_name_' + cid).val($(this).find('option:selected').data('name') || '');
            if (brandId) {
                fetch('{{ route("getModels") }}?brand_id=' + brandId).then(function(r) { return r.json(); }).then(function(data) {
                    var $m = $('#model_id_' + cid).empty().append('<option value="">Rechercher un modèle...</option>');
                    data.forEach(function(m) { $m.append('<option value="' + m.id + '" data-name="' + m.name + '">' + m.name + '</option>'); });
                    $m.val('').trigger('change');
                });
            } else {
                $('#model_id_' + cid).empty().append('<option value="">Rechercher un modèle...</option>').val('').trigger('change');
            }
            $('#engine_id_' + cid).empty().append('<option value="">Rechercher une motorisation...</option>').val('').trigger('change');
        });

        $(document).on('change', '[id^="model_id_"]', function() {
            var cid = this.id.replace('model_id_', '');
            $('#model_name_' + cid).val($(this).find('option:selected').data('name') || '');
            if (this.value) {
                fetch('{{ route("getEngines") }}?model_id=' + this.value).then(function(r) { return r.json(); }).then(function(data) {
                    var $e = $('#engine_id_' + cid).empty().append('<option value="">Rechercher une motorisation...</option>');
                    data.forEach(function(e) { $e.append('<option value="' + e.id + '" data-description="' + e.description + '" data-linking-target-id="' + e.linkageTargetId + '">' + e.description + '</option>'); });
                    $e.val('').trigger('change');
                });
            } else {
                $('#engine_id_' + cid).empty().append('<option value="">Rechercher une motorisation...</option>').val('').trigger('change');
            }
        });

        $(document).on('change', '[id^="engine_id_"]', function() {
            var cid = this.id.replace('engine_id_', '');
            $('#engine_description_' + cid).val($(this).find('option:selected').data('description') || '');
            $('#linkage_target_id_'  + cid).val($(this).find('option:selected').data('linking-target-id') || '');
        });

        $(document).on('hidden.bs.modal', '[id^="addVehicleModal"]', function() {
            var cid = this.id.replace('addVehicleModal', '');
            $('#vehicleForm' + cid)[0].reset();
            $('#model_id_' + cid + ', #engine_id_' + cid).empty().append('<option value="">Rechercher...</option>').val('').trigger('change');
            $('#brand_name_' + cid + ', #model_name_' + cid + ', #engine_description_' + cid + ', #linkage_target_id_' + cid).val('');
        });
    });
    </script>
</body>
</html>