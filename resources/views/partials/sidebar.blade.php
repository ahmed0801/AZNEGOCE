<!-- resources/views/partials/sidebar.blade.php -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <div class="logo-header" data-background-color="dark">
      <a href="/" class="logo">
        <img src="{{ asset('assets/img/logop.png')}}" alt="navbar brand" class="navbar-brand" height="40" />
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
        <li class="nav-item"><a href="/commande"><i class="fas fa-shopping-cart"></i><p>Nouvelle Commande</p></a></li>
        <li class="nav-item"><a href="/orders"><i class="fas fa-file-invoice-dollar"></i><p>Mes BL</p></a></li>
        <li class="nav-item"><a href="/listdevis"><i class="fas fa-file-alt"></i><p>Mes Devis</p></a></li>
        <li class="nav-item"><a href="/listbrouillon"><i class="fas fa-reply-all"></i><p>Brouillons</p></a></li>
        <li class="nav-item"><a href="/invoices"><i class="fas fa-money-bill-wave"></i><p>Mes Factures</p></a></li>
        <li class="nav-item"><a href="/avoirs"><i class="fas fa-reply-all"></i><p>Mes Avoirs</p></a></li>
        <li class="nav-item"><a href="/receptions"><i class="fas fa-money-bill-wave"></i><p>Réception</p></a></li>
        <li class="nav-item"><a href="/articles"><i class="fas fa-money-bill-wave"></i><p>Articles</p></a></li>
        <li class="nav-item"><a href="/setting"><i class="fas fa-money-bill-wave"></i><p>Paramétres</p></a></li>
        <li class="nav-item"><a href="/tecdoc"><i class="fas fa-cogs"></i><p>TecDoc</p></a></li>
        <li class="nav-item">
          <a href="{{ route('logout.admin') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i><p>Déconnexion</p>
          </a>
          <form id="logout-form" action="{{ route('logout.admin') }}" method="POST" style="display: none;">@csrf</form>
        </li>
      </ul>
    </div>
  </div>
</div>
