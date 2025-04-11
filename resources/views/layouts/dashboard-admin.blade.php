@extends('layouts.master-layouts-horizontal')

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
@section('content')
<style>
    /* Hover effect untuk dark & light mode */
    table.dataTable tbody tr:hover {
        background-color: rgba(100, 100, 100, 0.15);
        /* Netral, cocok untuk dua mode */
        cursor: pointer;
    }

    /* Penyesuaian warna untuk dark mode */
    body[data-bs-theme="dark"] table.dataTable {
        color: #f1f1f1;
        background-color: #1e1e2f;
    }

    body[data-bs-theme="dark"] table.dataTable thead {
        background-color: #2c2f4a;
        color: #ffffff;
    }

    body[data-bs-theme="dark"] table.dataTable tbody tr {
        background-color: #26293c;
        border-color: #3c3f57;
    }

    body[data-bs-theme="dark"] table.dataTable tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Light mode defaults, boleh diatur kalau mau */
    body[data-bs-theme="light"] table.dataTable thead {
        background-color: #343a40;
        color: #ffffff;
    }

    body[data-bs-theme="light"] table.dataTable tbody tr {
        background-color: #ffffff;
        color: #212529;
    }

    body[data-bs-theme="light"] table.dataTable tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container-fluid">

    {{-- Notifikasi Flash --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert" id="flash-alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" id="flash-alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Start Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">ADMIN DASHBOARD</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <!-- Statistik Kartu -->
    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body shadow-lg">
                    <div class="row g-4">
                        <div class="col-lg-3 col-sm-6 border-end-sm">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="avatar-xs flex-shrink-0">
                                        <div class="avatar-title bg-body-secondary text-primary border border-primary-subtle rounded-circle">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0">Total User</p>
                                    </div>
                                </div>
                                <h3 class="mb-0">
                                    <span class="counter-value" data-target="8956">0</span>
                                    <small class="text-success fs-xs fw-normal ms-1">
                                        <i class="bi bi-arrow-up align-baseline"></i> 12.09%
                                    </small>
                                </h3>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 border-end-lg">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="avatar-xs flex-shrink-0">
                                        <div class="avatar-title bg-body-secondary text-success border border-success-subtle rounded-circle">
                                            <i class="bi bi-patch-check-fill"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0">Total Akun VPN</p>
                                    </div>
                                </div>
                                <h3 class="mb-0">
                                    <span class="counter-value" data-target="4519">0</span>
                                    <small class="text-success fs-xs fw-normal ms-1">
                                        <i class="bi bi-arrow-up align-baseline"></i> 6.57%
                                    </small>
                                </h3>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 border-end-sm">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="avatar-xs flex-shrink-0">
                                        <div class="avatar-title bg-body-secondary text-warning border border-warning-subtle rounded-circle">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0">VPN Aktif</p>
                                    </div>
                                </div>
                                <h3 class="mb-0">
                                    <span class="counter-value" data-target="2648">0</span>
                                    <small class="text-success fs-xs fw-normal ms-1">
                                        <i class="bi bi-arrow-up align-baseline"></i> 4.07%
                                    </small>
                                </h3>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="avatar-xs flex-shrink-0">
                                        <div class="avatar-title bg-body-secondary text-danger border border-danger-subtle rounded-circle">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0">VPN Tidak Aktif</p>
                                    </div>
                                </div>
                                <h3 class="mb-0">
                                    <span class="counter-value" data-target="871">0</span>
                                    <small class="text-danger fs-xs fw-normal ms-1">
                                        <i class="bi bi-arrow-down align-baseline"></i> 3.49%
                                    </small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div><!-- end col -->

        <!-- 
        <div class="col-xl-5">
            <div class="card h-auto">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Tambah Akun VPN</h6>
                    <a href="#" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i> Buat
                    </a>
                </div>
            </div>
        </div>
        -->
    </div><!-- end row -->

    <!-- Tabel Data VPN -->
    <div class="container mt-4">
        <h4 class="mb-3">Daftar User VPN</h4>
        <table id="vpn-users-table" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Service</th>
                    <th>Profile</th>
                    <th>Remote Address</th>
                    <th>Last Logged Out</th>
                    <th>Last Disconnect Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vpnUsers as $user)
                <tr>
                    <td>{{ $user['name'] ?? '-' }}</td>
                    <td>{{ $user['service'] ?? '-' }}</td>
                    <td>{{ $user['profile'] ?? '-' }}</td>
                    <td>{{ $user['remote-address'] ?? '-' }}</td>
                    <td>{{ $user['last-logged-out'] ?? '-' }}</td>
                    <td>{{ $user['last-disconnect-reason'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- Script jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#vpn-users-table').DataTable({
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                zeroRecords: "Tidak ada data ditemukan",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                }
            }
        });
    });
</script>

<!-- Auto Close Alert -->
<script>
    setTimeout(function() {
        let alert = document.getElementById('flash-alert');
        if (alert) {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 3000);
</script>

@endsection