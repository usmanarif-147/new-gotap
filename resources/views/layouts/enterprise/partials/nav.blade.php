<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-transparent"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item justify-content-middle display-6 custom-text">
                @if (request()->segment(4))
                    <a
                        href="{{ url(request()->segment(1) . '/' . Str::plural(request()->segment(2))) }}">{{ ucfirst(Str::plural(request()->segment(2))) }}</a>
                    /{{ ucfirst(request()->segment(4)) }}
                @elseif (request()->segment(3))
                    <a
                        href="{{ url(request()->segment(1) . '/' . Str::plural(request()->segment(2))) }}">{{ ucfirst(Str::plural(request()->segment(2))) }}</a>
                    /{{ ucfirst(request()->segment(3)) }}
                @elseif (request()->segment(2))
                    {{ ucfirst(request()->segment(2)) }}
                @endif
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item">
                {{-- <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"> --}}
                <div class="avatar avatar-online">
                    <img src="{{ asset(auth()->user()->enterprise_logo && file_exists(public_path('storage/' . auth()->user()->enterprise_logo)) ? Storage::url(auth()->user()->enterprise_logo) : 'avatar.png') }}"
                        alt class="rounded-circle" id="userImage" />
                </div>
                {{-- </a> --}}
                {{-- <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-user me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('admin/account') }}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Manage Account</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('enterprise.logout') }}">
                            @csrf

                            <a class="dropdown-item" href="{{ route('enterprise.logout') }}"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </form>
                    </li>
                </ul> --}}
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
