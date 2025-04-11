@extends('layouts.master-layouts-horizontal')



@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">PPTP LIST</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">PPTP</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row align-items-center g-3 mb-4">
    <div class="col-sm-3 me-auto">
        <h5 class="card-title mb-0">Connected : <span class="badge bg-success ms-1 align-baseline"> {{ $pptp_count }}</span></h5>
    </div><!--end col-->
    <div class="col-lg-3 col-sm-5 col">
        <div class="search-box">
            <input type="text" class="form-control search" placeholder="Search for name, number, location or something...">
            <i class="ri-search-line search-icon"></i>
        </div>
    </div>
    <div class="col-sm-auto col-auto">
        <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
            <div>
                <a href="#" data-bs-toggle="modal" data-bs-target=".add-new" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add New</a>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->

<div class="row row-cols-1 row-cols-md-3 g-4">
    @foreach($active_pptp as $data)
    <div class="col">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="dropdown float-end">
                    <a class="text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="bx bx-dots-horizontal-rounded"></i></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Remove</a>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 position-relative">
                        <div class="avatar-title bg-body-secondary text-primary-emphasis display-6 rounded-circle">
                            <i class="mdi mdi-account-box-outline"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <h4 class="fs-md"><a href="pages-profile.html" class="text-dark">{{ $data['service'] }}</a></h4>
                        <h5 class="fs-md"><a href="pages-profile.html" class="text-dark">{{ $data['name'] }}</a></h5>
                    </div>
                </div>
                <div class="mt-4 p-1">
                    <div class="d-flex justify-content-between">
                        <p class="text-muted mb-2">Session id</p>
                        <p class="text-muted mb-2">{{ $data['session-id'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted mb-2">Caller id</p>
                        <p class="text-muted mb-2">{{ $data['caller-id'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted mb-2">Ip address</p>
                        <p class="text-muted mb-2">{{ $data['address'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted mb-2">Uptime</p>
                        <p class="text-muted mb-2">{{ $data['uptime_formatted'] }} </p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted mb-2">User</p>
                        <p class="text-muted mb-2">{{ $data['name'] }}</p>
                    </div>
                </div>



            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection