<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="22">
            </span>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="22">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>

                @if(Session::get('role') === 'admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="ph-gauge"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="ph-gear-six"></i> <span>Pengaturan</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.akun') }}" class="nav-link">Akun</a>
                            </li>
                            <li class="nav-item">
                                <a href="#vpnAccountModal" class="nav-link" data-bs-toggle="modal">VPN</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @elseif(Session::get('role') === 'monitoring')
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarDashboards" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ph-gauge"></i> <span>@lang('translation.dashboards')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('monitoring.ovpn') }}" class="nav-link">All Connections</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="{{ route('monitoring.ovpn') }}" class="nav-link">PPTP</a>
                            </li> -->
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<div class="vertical-overlay"></div>

<!-- Modal Tambah Akun VPN -->
<div class="modal fade" id="vpnAccountModal" tabindex="-1" aria-labelledby="vpnAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('vpn.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="mode" id="mode" value="manual">

            <div class="modal-header">
                <h5 class="modal-title" id="vpnAccountModalLabel">Buat VPN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <!-- Toggle Mode -->
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="modeToggle" onchange="toggleMode()" checked>
                    <label class="form-check-label" id="modeLabel" for="modeToggle">Mode Manual</label>
                </div>

                <!-- Manual Inputs -->
                <div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" data-name="username" class="form-control" data-mode="manual">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="text" data-name="password" class="form-control" data-mode="manual">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile</label>
                        <select name="profile" class="form-select" data-mode="manual">
                            <option value="default">default</option>
                            <option value="default-encryption">default-encryption</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Local Address</label>
                            <input type="text" name="local_address" class="form-control" placeholder="Contoh: 10.0.0.1" data-mode="manual">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Remote Address</label>
                            <input type="text" name="remote_address" class="form-control" placeholder="Contoh: 10.0.0.100" data-mode="manual">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Limit Bandwidth</label>
                        <select name="bandwidth_limit" class="form-select" data-mode="manual">
                            <option value="" disabled selected>Pilih Limit Bandwidth</option>
                            <option value="64k/64k">64 Kbps</option>
                            <option value="128k/128k">128 Kbps</option>
                            <option value="256k/256k">256 Kbps</option>
                        </select>
                    </div>
                </div>

                <!-- Auto Inputs -->
                <div>
                    <div class="alert alert-info" data-mode="auto">Mode Otomatis: VPN akan dibuat otomatis berdasarkan konfigurasi dibawah</div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Akun yang Ingin Dibuat</label>
                        <input type="number" data-name="jumlah" class="form-control" data-mode="auto">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prefix Username</label>
                        <input type="text" data-name="username_prefix" class="form-control" data-mode="auto">
                        <div class="form-text">Username akan menjadi seperti: <code>user1</code>, <code>user2</code>, dst.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Limit Bandwidth</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bandwidth_limit_auto" value="64k/64k" data-mode="auto">
                            <label class="form-check-label">64 Kbps</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bandwidth_limit_auto" value="128k/128k" data-mode="auto">
                            <label class="form-check-label">128 Kbps</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bandwidth_limit_auto" value="256k/256k" data-mode="auto">
                            <label class="form-check-label">256 Kbps</label>
                        </div>
                    </div>
                </div>

                <!-- VPN Service untuk keduanya -->
                <div class="mb-3">
                    <label class="form-label">Tipe VPN</label>
                    <select name="service" class="form-select">
                        @foreach ($services as $service)
                        <option value="{{ $service }}">{{ strtoupper($service) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Tambah Akun</button>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script>
    // function toggleMode() {
    //     const isManual = document.getElementById('modeToggle').checked;
    //     const mode = isManual ? 'manual' : 'auto';
    //     const modeLabel = document.getElementById('modeLabel');
    //     const manualFields = document.querySelectorAll('[data-mode="manual"]');
    //     const autoFields = document.querySelectorAll('[data-mode="auto"]');

    //     document.getElementById('mode').value = mode;
    //     modeLabel.innerText = isManual ? 'Mode Manual' : 'Mode Otomatis';

    //     manualFields.forEach(el => {
    //         const wrapper = el.closest('.mb-3, .row, .alert');
    //         if (wrapper) wrapper.style.display = isManual ? '' : 'none';
    //     });

    //     autoFields.forEach(el => {
    //         const wrapper = el.closest('.mb-3, .row, .alert');
    //         if (wrapper) wrapper.style.display = isManual ? 'none' : '';
    //     });
    // }

    // document.addEventListener('DOMContentLoaded', () => {
    //     toggleMode();
    // });

    function toggleMode() {
        const isManual = document.getElementById('modeToggle').checked;
        const mode = isManual ? 'manual' : 'auto';
        const modeLabel = document.getElementById('modeLabel');
        const manualFields = document.querySelectorAll('[data-mode="manual"]');
        const autoFields = document.querySelectorAll('[data-mode="auto"]');

        document.getElementById('mode').value = mode;
        modeLabel.innerText = isManual ? 'Mode Manual' : 'Mode Otomatis';

        // Tampilkan dan aktifkan field sesuai mode
        manualFields.forEach(el => {
            const wrapper = el.closest('.mb-3, .row, .alert');
            if (wrapper) wrapper.style.display = isManual ? '' : 'none';
            if (isManual) {
                // restore name dari data-name
                if (el.dataset.name) el.name = el.dataset.name;
            } else {
                // simpan name ke data-name, lalu hapus name
                if (el.name) {
                    el.dataset.name = el.name;
                    el.removeAttribute('name');
                }
            }
        });

        autoFields.forEach(el => {
            const wrapper = el.closest('.mb-3, .row, .alert');
            if (wrapper) wrapper.style.display = isManual ? 'none' : '';
            if (!isManual) {
                if (el.dataset.name) el.name = el.dataset.name;
            } else {
                if (el.name) {
                    el.dataset.name = el.name;
                    el.removeAttribute('name');
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleMode();
    });
</script>