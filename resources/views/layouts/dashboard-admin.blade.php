@extends('layouts.master-layouts-horizontal')

@section('content')

<style>
    /* Hover effect untuk dark & light mode */
    table.dataTable tbody tr:hover {
        background-color: rgba(100, 100, 100, 0.15);
        cursor: pointer;
    }

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

    .text-success-bright {
        color: rgb(6, 213, 89);
        /* font-size: 34px; */
    }

    .text-danger-bright {
        color: rgb(211, 0, 0);
        /* font-size: 34px; */
    }
</style>

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

<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ADMIN DASHBOARD</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Admin</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Kartu -->
<div class="row">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-body shadow-lg">
                <div class="row g-4">

                    <div class="col-md-4 col-sm-6 border-end-md">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0">Total VPN</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0">
                                <span class="counter-value" data-target="{{ $totalVpn }}">{{ $totalVpn }}</span>
                            </h3>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 border-end-md">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0">VPN Terhubung</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0">
                                <span class="counter-value" data-target="{{ $vpnAktif }}">{{ $vpnAktif }}</span>
                            </h3>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0">VPN Tidak Terhubung</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0">
                                <span class="counter-value" data-target="{{ $vpnTidakAktif }}">{{ $vpnTidakAktif }}</span>
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Tabel Data VPN -->
<div class="container mt-4">
    <h4 class="mb-3">Daftar User VPN</h4>
    <table id="vpn-users-table" class="table table-bordered table-hover shadow-lg">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Remote Address</th>
                <th>Status</th> <!-- Tambah ini -->
                <th>Service</th>
                <th>Bandwidth</th>
                <th>Last Logged Out</th>
                <th>Last Disconnect Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vpnUsers as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user['name'] ?? '-' }}</td>
                <td>{{ $user['remote-address'] ?? '-' }}</td>
                <td>
                    @if($user['status'] === 'Connected')
                    <span style="color: #28a745; font-weight: bold;">Connected</span>
                    @else
                    <span style="color: #dc3545; font-weight: bold;">Disconnected</span>
                    @endif
                </td>
                <td>{{ $user['service'] ?? '-' }}</td>
                <td>{{ $user['bandwidth'] ?? '-' }}</td>
                <td>{{ $user['last-logged-out'] ?? '-' }}</td>
                <td>{{ $user['last-disconnect-reason'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')


<!-- Script jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

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

    // Auto close alert
    setTimeout(function() {
        let alert = document.getElementById('flash-alert');
        if (alert) {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 3000);
</script>
@endpush