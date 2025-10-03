<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Managment Presensi SMK YPC TASIKMALAYA</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('images/profile/YPC.png') }}" />
  <link rel="stylesheet" href="{{ asset('css/styles.min.css') }}" />
  <style>
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    .table th {
        white-space: nowrap;
        font-weight: 500;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--bs-secondary-color);
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    .pagination .page-link {
        color: var(--bs-primary);
    }
    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }
    .form-floating > label {
        color: var(--bs-secondary-color);
        transition: all 0.2s ease-in-out;
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 1rem 0.75rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.1);
    }
</style>
</head>

<body>
    @include('sweetalert::alert')

  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('Template.sidebar')
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      @include('Template.navbar')
      <!--  Header End -->
      <div class="container-fluid">
        @yield('container')
      </div>
    </div>
  </div>
  <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('js/app.min.js') }}"></script>
  <script src="{{ asset('libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('js/dashboard.js') }}"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>
