    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="index" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="22">
                </span>
            </a>
            <a href="index" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="22">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>
        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">

                    <li class="menu-title"><span>@lang('translation.menu')</span></li>

                    @if(Session::get('role') === 'admin')
                    <!-- Hanya untuk admin -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="ph-gauge"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ph-gear-six"></i> <span>Manajemen User</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="" class="nav-link">Tambah User</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#vpnAccountModal" class="nav-link" data-bs-toggle="modal">Tambah Akun VPN</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    @elseif(Session::get('role') === 'monitoring')
                    <!-- Tampilan sidebar seperti biasa untuk monitoring -->
                    <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarDashboards" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ph-gauge"></i> <span>@lang('translation.dashboards')</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('monitoring.ovpn')}}" class="nav-link">OVPN</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('monitoring.pptp')}}" class="nav-link">PPTP</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif





                    <!-- <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarDashboards" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ph-gauge"></i> <span>@lang('translation.dashboards')</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('monitoring.ovpn')}}" class="nav-link" data-key="t-starters"> OVPN</a>
                                </li>
                                <li class="nav-item">
                                    <a href=" {{ route('monitoring.pptp')}} " class="nav-link" data-key="t-starters"> PPTP</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->

                    <!-- <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarLayouts" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                            <i class="ph-layout"></i><span>@lang('translation.layouts')</span> <span
                                class="badge badge-pill bg-danger" data-key="t-hot">Hot</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLayouts">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="layouts-horizontal" target="_blank" class="nav-link"
                                        data-key="t-horizontal">@lang('translation.horizontal')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="layouts-detached" target="_blank" class="nav-link"
                                        data-key="t-detached">@lang('translation.detached')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="layouts-two-column" target="_blank" class="nav-link"
                                        data-key="t-two-column">@lang('translation.two-column')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="layouts-vertical-hovered" target="_blank" class="nav-link"
                                        data-key="t-hovered">@lang('translation.hovered')</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->

                    <!-- <li class="menu-title"><i class="ri-more-fill"></i> <span
                            data-key="t-pages">@lang('translation.pages')</span></li> -->

                    <!-- <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarAuth" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarAuth">
                            <i class="ph-user-circle"></i> <span>@lang('translation.authentication')</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarAuth">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="" class="nav-link" role="button"
                                        data-key="t-signin">@lang('translation.signin') </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link" role="button"
                                        data-key="t-signup">@lang('translation.signup')</a>
                                </li>

                                <li class="nav-item">
                                    <a href="auth-pass-reset" class="nav-link" role="button"
                                        data-key="t-password-reset">
                                        @lang('translation.password-reset')
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="" class="nav-link" role="button"
                                        data-key="t-password-create">
                                        @lang('translation.password-create')
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="auth-lockscreen" class="nav-link" role="button"
                                        data-key="t-lock-screen">
                                        @lang('translation.lock-screen')
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="" class="nav-link" role="button" data-key="t-logout">
                                        @lang('translation.logout') </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-success-msg" class="nav-link" role="button"
                                        data-key="t-success-message">@lang('translation.success-message') </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-twostep" class="nav-link" role="button"
                                        data-key="t-two-step-verification"> @lang('translation.two-step-verification') </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sidebarErrors" class="nav-link" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false" aria-controls="sidebarErrors"
                                        data-key="t-errors"> @lang('translation.errors')</a>
                                    <div class="collapse menu-dropdown" id="sidebarErrors">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="auth-404" class="nav-link"
                                                    data-key="t-404-error">@lang('translation.404')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="auth-500" class="nav-link" data-key="t-500">
                                                    @lang('translation.500') </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="auth-503" class="nav-link"
                                                    data-key="t-503">@lang('translation.503')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="auth-offline" class="nav-link" data-key="t-offline-page">
                                                    @lang('translation.offline-page')</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li> -->

                    <!-- <li class="nav-item">
                        <a class="nav-link menu-link collapsed" href="#sidebarPages" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarPages">
                            <i class="ph-address-book"></i> <span data-key="t-pages">User</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarPages">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="" class="nav-link" data-key="t-starter">List User
                                    </a>
                                </li> -->
                    <!-- <li class="nav-item">
                                    <a href="pages-maintenance" class="nav-link"
                                        data-key="t-maintenance">@lang('translation.maintenance') </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages-coming-soon" class="nav-link"
                                        data-key="t-coming-soon">@lang('translation.coming-soon') </a>
                                </li> -->
                    <!-- </ul>
                        </div>
                    </li> -->

                    <!-- <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                            <i class="ri-share-line"></i> <span>@lang('translation.multi-level')</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMultilevel">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">@lang('translation.level-1.1')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse"
                                        role="button" aria-expanded="false"
                                        aria-controls="sidebarAccount">@lang('translation.level-1.2')
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarAccount">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">@lang('translation.level-2.1')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#sidebarCrm" class="nav-link" data-bs-toggle="collapse"
                                                    role="button" aria-expanded="false"
                                                    aria-controls="sidebarCrm">@lang('translation.level-2.2')
                                                </a>
                                                <div class="collapse menu-dropdown" id="sidebarCrm">
                                                    <ul class="nav nav-sm flex-column">
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">@lang('translation.level-3.1')</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">@lang('translation.level-3.2')</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li> -->

                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>





    <!-- Modal Tambah Akun VPN -->
    <div class="modal fade" id="vpnAccountModal" tabindex="-1" aria-labelledby="vpnAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('vpn.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="vpnAccountModalLabel">Tambah Akun VPN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">

                    <!-- Toggle Mode -->
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="modeToggle" onchange="toggleMode()">
                        <label class="form-check-label" for="modeToggle" id="modeLabel">Mode Manual</label>
                    </div>

                    <!-- Manual Mode Section -->
                    <div id="manualInputs">
                        <!-- Baris 1: Username & Password -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="vpn-username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="vpn-password" class="form-label">Password</label>
                                <input type="text" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <!-- Baris 2: Profile -->
                        <div class="mb-3">
                            <label for="vpn-profile" class="form-label">Profile</label>
                            <select name="profile" id="vpn-profile" class="form-select" required>
                                <option value="default">default</option>
                                <option value="default-encryption">default-encryption</option>
                            </select>
                        </div>

                        <!-- Baris 3: Local & Remote Address -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="local-address" class="form-label">Local Address</label>
                                <input type="text" name="local_address" id="local-address" class="form-control" placeholder="Contoh: 10.0.0.1">
                            </div>
                            <div class="col-md-6">
                                <label for="remote-address" class="form-label">Remote Address</label>
                                <input type="text" name="remote_address" id="remote-address" class="form-control" placeholder="Contoh: 10.0.0.100">
                            </div>
                        </div>

                        <!-- Baris 4: Limit Bandwidth -->
                        <div class="mb-3">
                            <label for="bandwidth-limit" class="form-label">Limit Bandwidth</label>
                            <select name="bandwidth_limit" id="bandwidth-limit" class="form-select" required>
                                <option value="" disabled selected>Pilih Limit Bandwidth</option>
                                <option value="64k/64k">64 Kbps</option>
                                <option value="128k/128k">128 Kbps</option>
                                <option value="256k/256k">256 Kbps</option>
                            </select>
                        </div>

                        <!-- Baris 4: Service -->
                        <div class="mb-3">
                            <label for="vpn-service" class="form-label">Tipe VPN</label>
                            <select name="service" id="vpn-service" class="form-select" required>
                                @foreach ($services as $service)
                                <option value="{{ $service }}">{{ strtoupper($service) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Automatic Mode Section (initially hidden) -->
                    <div id="autoInputs" style="display: none;">
                        <div class="alert alert-info">Mode Otomatis: Data akun akan dibuat otomatis berdasarkan konfigurasi default.</div>
                        <!-- Anda bisa tambahkan input tersembunyi jika dibutuhkan -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah Akun</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMode() {
            const isAuto = document.getElementById('modeToggle').checked;
            document.getElementById('manualInputs').style.display = isAuto ? 'none' : 'block';
            document.getElementById('autoInputs').style.display = isAuto ? 'block' : 'none';
            document.getElementById('modeLabel').innerText = isAuto ? 'Mode Otomatis' : 'Mode Manual';
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const switchInput = document.getElementById('inputModeSwitch');
            const manualSection = document.getElementById('manualInput');
            const autoSection = document.getElementById('autoInput');
            const modeLabel = document.getElementById('modeLabel');

            function updateView() {
                if (switchInput.checked) {
                    manualSection.style.display = 'block';
                    autoSection.style.display = 'none';
                    modeLabel.textContent = 'Mode Manual';
                } else {
                    manualSection.style.display = 'none';
                    autoSection.style.display = 'block';
                    modeLabel.textContent = 'Mode Otomatis';
                }
            }

            switchInput.addEventListener('change', updateView);

            // Jalankan sekali saat halaman dimuat
            updateView();
        });
    </script>