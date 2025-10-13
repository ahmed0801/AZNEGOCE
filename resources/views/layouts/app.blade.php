<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AZ ERP</title>
  <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

  {{-- Fonts and Icons --}}
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

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css') }}">

  {{-- Styles additionnels injectables --}}
  @stack('styles')
</head>

<body>
  <div class="wrapper">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="main-panel">

      {{-- Navbar --}}
      @include('partials.navbar')

      {{-- Contenu principal --}}
      <div class="content">
        @yield('content')
      </div>

      {{-- Footer --}}
      @include('partials.footer')

    </div>
  </div>

  {{-- Core JS --}}
  <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

  {{-- Plugins --}}
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

  {{-- Scripts additionnels injectables --}}
  @stack('scripts')
</body>
</html>
