<!doctype html>
<html lang="en" data-layout="horizontal" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable" data-theme="modern" data-topbar="{{ session('theme', 'light') }}" data-bs-theme="{{ session('theme', 'light') }}">

<head>
    <meta charset=" utf-8" />
    <title> Aplikasi Web Monitoring | PT. Raharja Sinergi Komunikasi </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="web app monitoring" name="description" />
    <meta content="PT. Raharja Sinergi Komunikasi" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
    @include('layouts.head-css')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- content -->
            </div>
            @include('layouts.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>


    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <!-- END Right Sidebar -->

    @include('layouts.vendor-scripts')
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        setInterval(() => {
            fetch('/keep-alive');
        }, 10 * 60 * 1000);
    </script>
</body>

</html>