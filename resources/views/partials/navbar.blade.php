<!-- resources/views/partials/navbar.blade.php -->
<div class="main-header">
  <div class="main-header-logo">
    <div class="logo-header" data-background-color="dark">
      <a href="/" class="logo">
        <img src="{{ asset('assets/img/logop.png')}}" alt="navbar brand" class="navbar-brand" height="20" />
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
              <img src="{{ asset('assets/img/avatar.png')}}" alt="..." class="avatar-img rounded-circle" />
            </div>
            <span class="profile-username fw-bold">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    <img src="{{ asset('assets/img/avatar.png')}}" alt="profile" class="avatar-img rounded" />
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
