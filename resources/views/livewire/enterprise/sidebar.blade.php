<div>
    <div class="app-brand demo mb-2 d-flex justify-content-center align-items-center">
        <a href="/enterprise/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo" style="background:white">
                <div>
                    <img src="{{ asset('logo.png') }}" class="img-fluid" height="50" width="50" alt="Logo here">

                </div>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('enterprise.dashboard') ? 'active' : '' }}">
            <a href="{{ route('enterprise.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>
        {{-- <li class="menu-item">
            <a href="#submenu1" data-bs-toggle="collapse" aria-expanded="false" aria-controls="submenu1"
                class="menu-link d-flex align-items-center">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div class="ms-2">Profiles</div>
                <i class="bx bx-chevron-down ms-auto"></i> <!-- Chevron icon for dropdown indicator -->
            </a>
            <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                <li class="menu-item {{ request()->routeIs('enterprise.profile.create') ? 'active' : '' }}">
                    <a href="{{ route('enterprise.profile.create') }}" class="menu-link d-flex align-items-center">
                        <i class="menu-icon tf-icons bx bx-plus-circle"></i>
                        <div class="ms-2">Create Profile</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('enterprise.profiles') ? 'active' : '' }}">
                    <a href="{{ route('enterprise.profiles') }}" class="menu-link d-flex align-items-center">
                        <i class="menu-icon tf-icons bx bx-list-ul"></i>
                        <div class="ms-2">Manage Profiles</div>
                    </a>
                </li>
            </ul>
        </li> --}}

        <li class="menu-item {{ request()->routeIs('enterprise.profiles') ? 'active' : '' }}">
            <a href="{{ route('enterprise.profiles') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Manage Profiles</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('enterprise.leads') ? 'active' : '' }}">
            <a href="{{ route('enterprise.leads') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Leads</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" onclick="changePassword()" class="menu-link">
                <i class="menu-icon tf-icons bx bx-key"></i>
                <div>Change Password</div>
            </a>
        </li>
    </ul>
</div>
