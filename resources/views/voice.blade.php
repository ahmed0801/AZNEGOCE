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



        


<div class="container mt-4">
<style>

body {
  background-color: #122386ff;
  background: linear-gradient(to right, #1a2035, #122386ff);
}

.div-height {
  height: 30vh;
  width: 10vw;
  background-color: transparent;
}

.chat-bot {
  padding: 1em;
  background: linear-gradient(to left, #3b4052ff, #404b61e3);
  flex-direction: column;
  justify-content: center;
  display: flex;
  border: none;
  opacity: 1;
  border-radius: 10px;
  width: 90%;
  max-width: 600px;
  margin: auto;
}

#chat-form {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.chat-text-area {
  flex-grow: 1;
  width: 100%;
  resize: none;
  background-color: transparent;
  color: #ffffffff;
  border: none;
  font-family: 'Courier New', Courier, monospace;
  font-size: 16px;
}

.voice-assistant h4 {
    font-weight: 600;
    margin-bottom: 15px;
    color: #007bff;
}

.button-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1em;
  flex-wrap: wrap;
}

.voice-button {
  border: none;
  background: transparent;
  cursor: pointer;
  width: 3rem;
  height: 3rem;
  margin-right: 1rem;
  margin-left: 0.5rem;
  display: flex;
  justify-content: center;
  align-items: center;
  fill: #1a2035;
}

.submit-button {
  border: none;
  background: transparent;
  cursor: pointer;
  width: 2.5rem;
  height: 2.5rem;
  margin-right: 1rem;
  margin-left: 0.5rem;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  fill: #1a2035;
}

.nego-button img,
.voice-button svg,
.submit-button svg {
  width: 100%;
  height: auto;
}

.submit-button:hover svg,
.voice-button:hover svg,
.nego-button:hover img {
  transform: scale(1.3);
  transition: 0.3s ease;
}

.voice-icon {
  width: 24px;
  height: 24px;
  background: transparent;
}

.typing {
    display: inline-block;
    overflow: hidden;
    border-right: .15em solid #007bff;
    white-space: nowrap;
    animation: typing 2s steps(20, end), blink-caret 1s step-end infinite;
}


.conversation {
	border: none;
    border-radius: 10px;
    background: transparent;
    padding: 15px;
    min-height: 400px;
    max-height: 60vh;
    overflow-y: auto;
}

.user-message {
    background-color: #e3f2fd;
    padding: 10px 15px;
    margin: 8px 0;
    border-radius: 15px;
	border: none;
    border-bottom-left-radius: 5px;
    align-self: flex-end;
    max-width: 70%;
    margin-left: auto;
}

.bot-message {
    background-color: #f5f5f5;
    padding: 10px 15px;
    margin: 8px 0;
    border-radius: 15px;
    border-bottom-right-radius: 5px;
	border: none;
    align-self: flex-start;
    max-width: 70%;
}

.error-message {
    background-color: #ffebee;
    color: #c62828;
    padding: 10px 15px;
    margin: 8px 0;
    border-radius: 15px;
    font-style: italic;
}

@media (max-width: 600px) {
  .button-container {
    flex-direction: row;
    gap: 1em;
  }

  .nego-button,
  .voice-button,
  .submit-button {
    width: 40px;
    height: 40px;
  }
}

::-webkit-scrollbar {
  width: 1px;
}

#container {
  position: absolute;
  justify-content: center;
  transition: top 0.5s ease-out, left 0.5s ease-out;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  right: 35%;
  top: 50%;
}

.loading-icon {
  fill: #1a2035;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}


@keyframes typing {
    from { width: 0 }
    to { width: 100% }
}

@keyframes blink-caret {
    from, to { border-color: transparent }
    50% { border-color: #007bff; }
}
</style>

<div class="conversation">
	<hr>
	<ul id="list">
		<li></li>
	</ul>
</div>

<div class="chat-bot" id="container">
  <form id="chat-form">
    @csrf
    <label hidden for="question">Pose ta question :</label>
    <textarea class="chat-text-area" id="question" name="question" placeholder="Entrez des questions sur les commandes "></textarea>
   </form>
   <div class="button-container">
    <div class="btn-toolbar">
  <button type="button" class="voice-button" id="voice-button" onclick="startRecognition(event)">
        <svg
			class="voice-icon"
          	xmlns="http://www.w3.org/2000/svg"
          	height="20px"
          	viewBox="0 -960 960 960"
          	width="20px">
		<path d="M480-400q-50 0-85-35t-35-85v-240q0-50 35-85t85-35q50 0 85 35t35 85v240q0 50-35 85t-85 35Zm0-240Zm-40 520v-123q-104-14-172-93t-68-184h80q0 83 58.5 141.5T480-320q83 0 141.5-58.5T680-520h80q0 105-68 184t-172 93v123h-80Zm40-360q17 0 28.5-11.5T520-520v-240q0-17-11.5-28.5T480-800q-17 0-28.5 11.5T440-760v240q0 17 11.5 28.5T480-480Z"/>
        </svg>
      </button>
        </div>
      <button class="submit-button" id="submit-button" type="submit" form="chat-form">
        <svg
			xmlns="http://www.w3.org/2000/svg"
			height="24px"
          	viewBox="0 -960 960 960"
          	width="24px">
          <path d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z"/>
        </svg>
      </button>
  </div>
</div>


<script>
  document.getElementById('chat-form').addEventListener('submit', function (e) {

  e.preventDefault();

  const questionField = document.getElementById('question');
  const question = questionField.value.trim();
  
  if (question !== "") {
    const target = document.getElementById('container');
    target.style.right = "35%";
    target.style.top = "75%";
  }
});

</script>

<script>

function initChatBot(options) {
    const formSelector = options.formSelector;
    const inputSelector = options.inputSelector;
    const responseSelector = options.responseSelector;
    const routeUrl = options.url;
    const csrfToken = options.csrfToken;
    const list = document.getElementById('list');

    $(document).ready(function () {
        $(formSelector).on('submit', function (e) {
            e.preventDefault();
            let question = $(inputSelector).val().trim();
            if (question == "")
              return;
            const userQuestionItem = document.createElement('li');
            userQuestionItem.className = 'user-message';
            userQuestionItem.textContent = "Vous: " + question;
            list.appendChild(userQuestionItem);
            let responseItem;
            $(inputSelector).val('');
            $.ajax({
              url: routeUrl,
              type: "POST",
              data: {
                _token: csrfToken,
                question: question
              },
              beforeSend: function () {
                responseItem = document.createElement('li');
                responseItem.className = 'bot-message';
                responseItem.innerHTML = `
                <strong>
                <svg
                class="loading-icon"
                  xmlns="http://www.w3.org/2000/svg"
                  height="24px"
                  viewBox="0 -960 960 960"
                  width="24px"
                  fill="#e3e3e3">
                  <path d="M360-80q-58 0-109-22t-89-60q-38-38-60-89T80-360q0-81 42-148t110-102q20-39 49.5-68.5T350-728q33-68 101-110t149-42q58 0 109 22t89 60q38 38 60 89t22 109q0 85-42 150T728-350q-20 39-49.5 68.5T610-232q-35 68-102 110T360-80Zm0-80q33 0 63.5-10t56.5-30q-58 0-109-22t-89-60q-38-38-60-89t-22-109q-20 26-30 56.5T160-360q0 42 16 78t43 63q27 27 63 43t78 16Zm120-120q33 0 64.5-10t57.5-30q-59 0-110-22.5T403-403q-38-38-60.5-89T320-602q-20 26-30 57.5T280-480q0 42 15.5 78t43.5 63q27 28 63 43.5t78 15.5Zm120-120q18 0 34.5-3t33.5-9q22-60 6.5-115.5T621-621q-38-38-93.5-53.5T412-668q-6 17-9 33.5t-3 34.5q0 42 15.5 78t43.5 63q27 28 63 43.5t78 15.5Zm160-78q20-26 30-57.5t10-64.5q0-42-15.5-78T741-741q-27-28-63-43.5T600-800q-35 0-65.5 10T478-760q59 0 110 22.5t89 60.5q38 38 60.5 89T760-478Z"/>
                  <set attributeName="r" to="50" begin="3s" />
                  </svg>
                  </strong>
                `;
                list.appendChild(responseItem);
              },
              success: function (response) {
                // Render Markdown returned by the server safely using marked + DOMPurify
                responseItem.innerHTML = '';
                var md = (response && response.response) ? response.response : '';
                try {
                  var unsafeHtml = (typeof marked !== 'undefined') ? marked.parse(md) : md;
                  var clean = (typeof DOMPurify !== 'undefined') ? DOMPurify.sanitize(unsafeHtml) : unsafeHtml;
                  var htmlContainer = document.createElement('div');
                  htmlContainer.innerHTML = clean;
                  // Append the rendered/sanitized HTML directly so there is no extra label or line break
                  responseItem.appendChild(htmlContainer);
                } catch (e) {
                  // fallback to plain text if parser/sanitizer not available
                  responseItem.appendChild(document.createTextNode(md));
                }

                list.appendChild(responseItem);
                list.scrollTop = list.scrollHeight;
                responseItem = null;
              },
              error: function (xhr) {
                console.error("Erreur AJAX :", xhr.responseText);
                responseItem.className = 'error-message';
                responseItem.textContent = "❌ Une erreur est survenue.";
              }
            });
          });
        });
}

initChatBot({
    formSelector: "#chat-form",
    inputSelector: "#question",
    responseSelector: "#bot-response",
    url: "{{ route('chat-bot') }}",
    csrfToken: "{{ csrf_token() }}"
});
</script>

<script>
document.getElementById("question").addEventListener("keydown", function(event) {
  if (event.key === "Enter" && !event.shiftKey) {
    event.preventDefault();
    document.getElementById("chat-form").requestSubmit();
  }
});
</script>

<script>
  const myText = document.getElementById("question");
  myText.style.cssText = `height: ${myText.scrollHeight}px; overflow-y: hidden`;

  myText.addEventListener("input", function(){
    this.style.height = "auto";
    this.style.height = `${this.scrollHeight}px`;
  });

</script>

<script>
function startRecognition(e) {
  // prevent button default behaviour
  if (e && typeof e.preventDefault === 'function') e.preventDefault();

  // Feature detection for SpeechRecognition
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition || null;
  const resultDiv = document.getElementById("voice-result");
  if (!SpeechRecognition) {
    if (resultDiv) resultDiv.innerText = '❌ Reconnaissance vocale non supportée par ce navigateur.';
    return;
  }

  const recognition = new SpeechRecognition();
  recognition.lang = 'fr-FR';

  if (resultDiv) resultDiv.innerHTML = '<span class="typing">🔎 AZ écoute...</span>';

  recognition.onresult = function(event) {
    const voiceText = event.results[0][0].transcript;
    if (resultDiv) resultDiv.innerText = "🗣️ Commande reconnue : \"" + voiceText + "\" \n🤖 Traitement en cours...";

    fetch('/api/voice-command', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ command: voiceText })
    })
    .then(res => res.json())
    .then(data => {
      let msg = "✅ Réponse AZ :\n" + (data.status || '') + "\n\n";
      if (data.propositions && data.propositions.length > 0) {
        msg += "💡 Suggestions d'achat :\n";
        data.propositions.forEach(p => {
          // escape values used in string construction
          const q = p.quantité_suggérée || p.quantite || '';
          const article = p.article || '';
          const fournisseur = p.fournisseur || '';
          const prix = p.prix_unitaire || p.prix || '';
          msg += `• 🛒 ${q} x ${article} chez ${fournisseur} à ${prix}€/unité\n`;
        });
      } else {
        msg += "Aucune suggestion complémentaire.\n";
      }
      if (resultDiv) resultDiv.innerText = msg;
    })
    .catch(() => {
      if (resultDiv) resultDiv.innerText = "❌ Erreur réseau ou API. Réessayez.";
    });
  };
  recognition.onerror = function(err) {
    if (resultDiv) resultDiv.innerText = '❌ Erreur de reconnaissance vocale: ' + (err.error || err.message || JSON.stringify(err));
  };
  recognition.start();
}
</script>  
                     </div>

          </div>
        </div>
        </div>
    </body>
  
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

<!-- Client-side Markdown renderer + sanitizer -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.4.0/dist/purify.min.js"></script>

    

<script>
var _searchInput = document.getElementById("searchItemInput");
if (_searchInput) {
  _searchInput.addEventListener("keyup", function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll("#itemsTable tbody tr");

    rows.forEach(function(row) {
      row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
    });
  });
}
</script>

</html>