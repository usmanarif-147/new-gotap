<div>
    <div class="app-brand demo mb-4">
        <a href="/enterprise/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo" style="background:white">
                <img src="{{ asset('logo.png') }}" class="img-fluid" width="140" alt="Logo here">
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
        <li class="menu-item {{ request()->routeIs('enterprise.profiles') ? 'active' : '' }}">
            <a href="{{ route('enterprise.profiles') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Manage Profiles</div>
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
