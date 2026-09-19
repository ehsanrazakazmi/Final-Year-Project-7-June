<!DOCTYPE html>

@if (\Request::is('rtl'))
  <html dir="rtl" lang="ar">
@else
  <html lang="en" >
@endif
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  @if (env('IS_DEMO'))
      <x-demo-metas></x-demo-metas>
  @endif

  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/ali_logo.png') }}">
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  {{-- Font Awesome.
       Was https://kit.fontawesome.com/42d5adcbca.js - a Kit belonging to the
       Soft UI Dashboard template's author. Kits are tied to an account and a
       whitelist of allowed domains, so it returned 403 Forbidden here and no
       icons rendered. The free CDN build needs neither. --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  {{-- nucleo-svg.css was linked twice; the duplicate is removed. --}}
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
  
  
</head>

<body class="g-sidenav-show  bg-gray-100 {{ (\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '')) }} ">
  
  @auth
    @yield('auth')
  @endauth
  @guest
    @yield('guest')
  @endguest

  {{-- Flash toast.
       This used Alpine (x-data / x-init / x-show) to auto-hide after 4s, but
       Alpine is not loaded anywhere in the project, so those attributes were
       inert: the toast never dismissed. It also had `position-fixed` with no
       top/bottom/left, so it fell back to its static position at the bottom of
       the page instead of floating. Both are now handled in CSS, no JS needed. --}}
  @if (session()->has('success') || session()->has('error'))
    @php $isError = session()->has('error'); @endphp
    <div class="app-flash {{ $isError ? 'app-flash--error' : 'app-flash--success' }}" role="status">
      {{ $isError ? session('error') : session('success') }}
    </div>
  @endif

  <style>
    .app-flash {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1080;
      max-width: 360px;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 14px;
      line-height: 1.4;
      color: #fff;
      box-shadow: 0 8px 24px rgba(15, 23, 42, .18);
      animation: app-flash-in .18s ease-out, app-flash-out .4s ease-in 4.5s forwards;
    }
    .app-flash--success { background: #059669; }
    .app-flash--error   { background: #dc2626; }
    @keyframes app-flash-in  { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }
    @keyframes app-flash-out { to { opacity: 0; visibility: hidden; transform: translateY(-8px); } }
    @media (prefers-reduced-motion: reduce) { .app-flash { animation: none; } }
  </style>
    <!--   Core JS Files   -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>


  @stack('rtl')
  @stack('dashboard')
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
    $('#myTable').DataTable();
} );
    </script>
</body>

</html>
