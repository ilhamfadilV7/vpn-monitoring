<header id="page-topbar" class="shadow-sm">
    <div class="w-100 px-3">
        <div class="navbar-header d-flex justify-content-between align-items-center">

            <!-- LOGO -->
            <div class="navbar-brand-box horizontal-logo d-flex align-items-center">
                <a href="{{ url('/') }}" class="logo logo-dark d-flex align-items-center gap-2">
                    <img src="{{ asset('build/images/logo-sm.png') }}" alt="Logo" height="28">
                    <span class="fw-semibold d-none d-md-inline">VPN Monitoring</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Theme Mode Toggle -->
                <div class="dropdown topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle mode-layout"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-sun align-middle fs-3xl"></i>
                    </button>
                    <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                        @foreach (['light' => 'Default (light mode)', 'dark' => 'Dark', 'auto' => 'Auto (system default)'] as $mode => $label)
                        <form method="POST" action="{{ route('toggle.theme') }}">
                            @csrf
                            <input type="hidden" name="mode" value="{{ $mode }}">
                            <button type="submit" class="dropdown-item">
                                <i class="bi {{ $mode === 'light' ? 'bi-sun' : ($mode === 'dark' ? 'bi-moon' : 'bi-moon-stars') }} align-middle me-2"></i>
                                {{ $label }}
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown header-item topbar-user">
                    <button type="button" class="btn shadow-none d-flex align-items-center" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user"
                            src="{{ URL::asset('build/images/users/32/user-dummy-img.jpg') }}" alt="User Avatar" height="32">
                        <div class="ms-2 d-none d-xl-block text-start">
                            <span class="fw-medium d-block user-name-text">
                                {{ session('user')->name ?? 'Guest' }}
                            </span>
                            <span class="fs-sm text-muted user-name-sub-text">
                                {{ session('user')->role ?? 'Guest' }}
                            </span>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Welcome {{ session('user')->name ?? 'Guest' }}!</h6>
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="mdi mdi-account-circle text-muted fs-lg align-middle me-1"></i>
                            <span class="align-middle">@lang('translation.profile')</span>
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="mdi mdi-cog-outline text-muted fs-lg align-middle me-1"></i>
                            <span class="align-middle">@lang('translation.settings')</span>
                        </a>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout text-muted fs-lg align-middle me-1"></i>
                            <span class="align-middle" data-key="t-logout">@lang('translation.logout')</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>