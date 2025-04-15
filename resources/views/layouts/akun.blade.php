@extends('layouts.master-layouts-horizontal')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Daftar Akun</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pengaturan</a></li>
                    <li class="breadcrumb-item active">Akun</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif



<div class="row">
    <div class="col-lg-12">
        <div class="card" id="agentList">
            <div class="card-header">
                <div class="row align-items-center gy-3">
                    <div class="col-lg-3 col-md-6 order-last order-md-first me-auto">
                        <div class="search-box">
                            <input type="text" class="form-control search" placeholder="Search for agent, email, address or something...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-auto col-6 text-end">
                        <div class="d-flex flex-wrap align-items-start gap-2">
                            <button class="btn btn-subtle-danger d-none" id="remove-actions" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addAgent"><i class="bi bi-person-plus align-baseline me-1"></i> Buat Akun</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                        <thead class="text-muted table-light">
                            <tr>
                                <th scope="col" class="sort cursor-pointer" data-sort="agent_id">No</th>
                                <th scope="col" class="sort cursor-pointer" data-sort="joining_date">Nama</th>
                                <th scope="col" class="sort cursor-pointer" data-sort="agent_Name">Email</th>
                                <th scope="col" class="sort cursor-pointer" data-sort="address">Role</th>
                                <th scope="col" class="sort cursor-pointer" data-sort="email">Last_login</th>
                                <th scope="col" class="sort cursor-pointer" data-sort="contact">Last_logout</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            @foreach ($users as $index => $user)

                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role == 'admin' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}</td>
                                <td>{{ $user->last_logout_at ? $user->last_logout_at->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editUserBtn"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal">
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody><!-- end tbody -->
                    </table><!-- end table -->
                    <div class="noresult" style="display: none">
                        <div class="text-center py-4">
                            <i class="ph-magnifying-glass fs-1 text-primary"></i>
                            <h5 class="mt-2">Sorry! No Result Found</h5>
                            <p class="text-muted mb-0">We've searched more than 150+ agent We did not find any agent for you search.</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 align-items-center" id="pagination-element">
                    <div class="col-sm">
                        <div class="text-muted text-center text-sm-start">
                            Showing <span class="fw-semibold">10</span> of <span class="fw-semibold">15</span> Results
                        </div>
                    </div><!--end col-->
                    <div class="col-sm-auto mt-3 mt-sm-0">
                        <div class="pagination-wrap justify-content-center hstack gap-2">
                            <a class="page-item pagination-prev disabled" href="javascript:void(0)">
                                Previous
                            </a>
                            <ul class="pagination listjs-pagination mb-0"></ul>
                            <a class="page-item pagination-next" href="javascript:void(0)">
                                Next
                            </a>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->





                <!-- Modal Tambah Akun-->
                <div class="modal fade" id="addAgent" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addAgentModalLabel">Buat Akun</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-addAgentModal"></button>
                            </div>
                            <!-- form -->
                            <form class="tablelist-form" method="POST" action="{{ route('users.store') }}" novalidate autocomplete="off">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="agent-name-input" class="form-label">Username / Nama<span class="text-danger">*</span></label>
                                        <input type="text" id="agent-name-input" name="name" class="form-control" placeholder="Masukan Username / Nama" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email-input" class="form-label">Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email-input" name="email" placeholder="Masukan Email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contact-input" class="form-label">Password<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="contact-input" name="password" placeholder="Masukan Password" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status-type-input" class="form-label">Role<span class="text-danger">*</span></label>
                                        <select class="form-select" id="status-type-input" name="role">
                                            <option value="admin">Admin</option>
                                            <option value="monitoring">Monitoring</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> Batal</button>
                                        <button type="submit" class="btn btn-primary" id="add-btn">Buat</button>
                                    </div>
                                </div>
                            </form>

                            <!-- end form  -->

                        </div>
                        <!-- modal-content -->
                    </div>
                </div><!--end add Property modal-->

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <form method="POST" id="deleteUserForm">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel">Peringatan !</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin ingin menghapus user <strong id="userNameToDelete"></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Edit User -->
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <form method="POST" id="editUserForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <input type="hidden" id="editUserId" name="id">

                                    <div class="mb-3">
                                        <label for="editName" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="editName" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="editEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="editEmail" name="email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="editPassword" class="form-label">Password (kosongkan jika tidak diubah)</label>
                                        <input type="password" class="form-control" id="editPassword" name="password">
                                    </div>

                                    <div class="mb-3">
                                        <label for="editRole" class="form-label">Role</label>
                                        <select class="form-select" id="editRole" name="role" required>
                                            <option value="admin">Admin</option>
                                            <option value="monitoring">Monitoring</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end edit modal -->


            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const deleteUserModal = document.getElementById('deleteUserModal');
        deleteUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            const form = deleteUserModal.querySelector('#deleteUserForm');
            form.action = `/user/${userId}`; // pastikan route-nya sesuai

            const userNameSpan = deleteUserModal.querySelector('#userNameToDelete');
            userNameSpan.textContent = userName;
        });



        $(document).ready(function() {
            $('.editUserBtn').click(function() {
                const id = $(this).data('id');
                console.log('Edit user ID:', id); // ← CEK DI CONSOLE BROWSER

                const name = $(this).data('name');
                const email = $(this).data('email');
                const role = $(this).data('role');

                $('#editUserId').val(id);
                $('#editName').val(name);
                $('#editEmail').val(email);
                $('#editRole').val(role);
                $('#editPassword').val('');

                // Set action ke form edit
                $('#editUserForm').attr('action', '/admin/akun/' + id);
            });
        });


        setTimeout(() => {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => new bootstrap.Alert(alert).close());
        }, 3000); // 5 detik
    </script>







    @endsection