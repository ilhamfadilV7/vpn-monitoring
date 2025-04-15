@extends('layouts.master-layouts-horizontal')



@section('content')
<style>
    .icon-circle {
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .text-success-bright {
        color: rgb(6, 213, 89);
        font-size: 34px;
    }

    .text-danger-bright {
        color: rgb(211, 0, 0);
        font-size: 34px;
    }

    .badge-green-bright {
        background-color: rgb(6, 213, 89);
        color: #000 !important;
    }
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ALL CONNECTIONS</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">ALL</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row align-items-center g-3 mb-4">
    <div class="col-sm-3 me-auto">
        <h5>Total Connected Users: <span class="">{{ $connected_count }}</span></h5>
    </div><!--end col-->
    <div class="col-lg-3 col-sm-5 col">
        <div class="search-box ">
            <input type="text" id="searchInput" class="form-control" placeholder="Masukan nama, ip, service atau yang lain...">
            <i class="ri-search-line search-icon"></i>
        </div>
    </div>
    <div class="col-sm-auto col-auto">
        <!-- <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
            <div>
                <a href="#" data-bs-toggle="modal" data-bs-target=".add-new" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add New</a>
            </div>
        </div> -->
    </div><!--end col-->
</div><!--end row-->

<!-- VPN Cards -->
<div class="row row-cols-1 row-cols-md-3 g-4" id="vpnCardContainer">
    @foreach($active_vpn as $data)
    <div class="col vpn-card"
        data-name="{{ strtolower($data['name']) }}"
        data-service="{{ strtolower($data['service']) }}"
        data-ip="{{ strtolower($data['address']) }}">
        <div class="card shadow-lg overflow-hidden">
            <div class="card-body">
                <div class="dropdown float-end">
                    <a class="text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="bx bx-dots-horizontal-rounded"></i></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Details</a>
                        <!-- <a class="dropdown-item" href="#">Action</a> -->
                        <!-- <a class="dropdown-item" href="#">Remove</a> -->
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 position-relative">
                        @php
                        $iconClass = $data['status'] === 'Connected' ? 'ri-wifi-fill text-success-bright' : 'ri-wifi-off-fill text-danger-bright';
                        @endphp

                        <div class="flex-shrink-0 position-relative">
                            <div class="avatar-title bg-body-secondary">
                                <i class="ri {{ $iconClass }}"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <h5 class="fs-md"><a href="pages-profile.html" class="text-dark">{{ $data['name'] }}</a></h5>
                    </div>
                </div>
                <div class="mt-4 p-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="text-muted mb-0">Service</p>
                        <p class="text-muted mb-0">{{ $data['service'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="text-muted mb-0">Status</p>
                        <!-- <span class="badge {{ $data['status'] === 'Connected' ? 'badge-green-bright' : 'badge-red-bright' }}">
                            {{ $data['status'] }}
                        </span> -->
                        <p class="text-muted mb-0">{{ $data['status'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="text-muted mb-0">IP Address</p>
                        <p class="text-muted mb-0">{{ $data['address'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="text-muted mb-0">Uptime</p>
                        <p class="text-muted mb-0">{{ $data['uptime_formatted'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-muted mb-0">User</p>
                        <p class="text-muted mb-0">{{ $data['name'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            let search = $(this).val().toLowerCase();

            $('.vpn-card').each(function() {
                let name = $(this).data('name');
                let service = $(this).data('service');
                let ip = $(this).data('ip');

                if (name.includes(search) || service.includes(search) || ip.includes(search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection