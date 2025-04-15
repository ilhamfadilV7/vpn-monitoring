<!doctype html>
<html lang="en"
    data-layout="horizontal"
    data-sidebar="dark"
    data-sidebar-size="lg"
    data-preloader="disable"
    data-theme="default"
    data-topbar="{{ session('theme', 'light') }}"
    data-bs-theme="{{ session('theme', 'light') }}">

<head>
    <meta charset="utf-8" />
    <title>Aplikasi Web Monitoring | PT. Raharja Sinergi Komunikasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="web app monitoring" name="description" />
    <meta content="PT. Raharja Sinergi Komunikasi" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">

    <!-- App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

    <!-- Additional Head CSS -->
    @include('layouts.head-css')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Topbar -->
        @include('layouts.topbar')

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content" style="min-height: 100vh; display: flex; flex-direction: column;">
            <div class="page-content" style="flex: 1;">
                <div class="container-fluid pb-5"> {{-- Tambahkan padding-bottom agar tidak mepet --}}
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End right Content here -->
        <!-- ============================================================== -->

    </div>
    <!-- END layout-wrapper -->

    <!-- Vendor Scripts -->
    @include('layouts.vendor-scripts')

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Keep-Alive Script -->
    <script>
        setInterval(() => {
            fetch('/keep-alive');
        }, 10 * 60 * 1000); // setiap 10 menit
    </script>

    @stack('scripts')

</body>

</html>