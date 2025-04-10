@extends('layouts.master-without-nav')
@section('title')
@lang('translation.success-message')
@endsection
@section('content')

<section class="auth-page-wrapper py-5 position-relative d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card mb-0">
                    <div class="row g-0 align-items-center">

                        <!--end col-->
                        <div class="col-xxl-6 mx-auto">
                            <div class="card mb-0 border-0 shadow-none mb-0">
                                <div class="card-body p-sm-5 m-lg-4">
                                    <div class="avatar-lg mx-auto mt-2">
                                        <div class="avatar-title bg-body-secondary text-primary display-5 rounded-circle">
                                            <i class="bi bi-shield-fill-check"></i>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <h4>Login Success !</h4>
                                        <p class="text-muted mx-4">Akun terverifikasi. anda dapat mengakses halaman dashboard</p>
                                        <div class="mt-4">
                                            <a href="{{ route('ovpn')}}" class="btn btn-primary w-100">Ke Halaman Dashboard</a>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>

@endsection