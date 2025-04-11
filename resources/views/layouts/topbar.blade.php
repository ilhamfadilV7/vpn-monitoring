<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="" class="logo logo-dark">
                        <!-- <span class="logo-sm">
                            <i class="mdi mdi-router-wireless"></i>
                        </span>
                        <span class="logo-lg">
                            <i class="mdi mdi-router-wireless"></i>

                        </span>
                    </a>

                    <a href="" class="logo logo-light">
                        <span class="logo-sm">
                            <i class="mdi mdi-router-wireless"></i>
                        </span>
                        <span class="logo-lg">
                            <i class="mdi mdi-router-wireless"></i>
                        </span>
                    </a> -->
                        <!-- <div class="avatar-title bg-body-primary text-white rounded-circle"
                            style="width: 80px; height: 80px; font-size: 36px;">
                            <i class="mdi mdi-router-wireless"></i>
                        </div> -->
                </div>
            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown topbar-head-dropdown ms-1 header-item">

                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/dropbox.png') }}"
                                            alt="dropbox">
                                        <span>Dropbox</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/mail_chimp.png') }}"
                                            alt="mail_chimp">
                                        <span>Mail Chimp</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="{{ URL::asset('build/images/brands/slack.png') }}" alt="slack">
                                        <span>Slack</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle mode-layout"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-sun align-middle fs-3xl"></i>
                    </button>
                    <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                        <form method="POST" action="{{ route('toggle.theme') }}">
                            @csrf
                            <input type="hidden" name="mode" value="light">
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-sun align-middle me-2"></i> Default (light mode)
                            </button>
                        </form>
                        <form method="POST" action="{{ route('toggle.theme') }}">
                            @csrf
                            <input type="hidden" name="mode" value="dark">
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-moon align-middle me-2"></i> Dark
                            </button>
                        </form>
                        <form method="POST" action="{{ route('toggle.theme') }}">
                            @csrf
                            <input type="hidden" name="mode" value="auto">
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-moon-stars align-middle me-2"></i> Auto (system default)
                            </button>
                        </form>
                    </div>
                </div>


                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"

                                src="{{ URL::asset('build/images/users/32/user-dummy-img.jpg') }}" alt="Header Avatar">


                            <span class="text-start ms-xl-2">
                                @php
                                $user = session('user_data')['user'] ?? 'Guest';
                                @endphp

                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ session('user')->name ?? 'Guest' }}
                                </span>
                                <span class="d-none d-xl-block ms-1 fs-sm user-name-sub-text">{{ session('user')->role ?? 'Guest' }}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome {{ session('user')->name ?? 'Guest' }} !</h6>
                        <a class="dropdown-item" href="javascript:void(0)"><i
                                class="mdi mdi-account-circle text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle"> @lang('translation.profile')</span></a>
                        <a class="dropdown-item" href="javascript:void(0)"><span
                                class="mdi mdi-cog-outline text-muted fs-lg align-middle me-1"></i> <span
                                    class="align-middle">@lang('translation.settings')</span></a>
                        <a class="dropdown-item" href=""
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="mdi mdi-logout text-muted fs-lg align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">@lang('translation.logout')</span></a>
                        <form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="bi bi-trash display-4"></i>
                    </div>
                    <div class="mt-4 fs-base">
                        <h4 class="mb-1">Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete
                        It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- removeCartModal -->
<div id="removeCartModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-cartmodal"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="bi bi-trash display-5"></i>
                    </div>
                    <div class="mt-4">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this product ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="remove-cartproduct">Yes, Delete
                        It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->