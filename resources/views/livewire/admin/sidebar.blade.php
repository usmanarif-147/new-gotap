<style>
    .custom-logo-width {
        height: 50px !important;
        width: 50px !important;
    }

    .custom-logo-width img {
        height: 100%;
        width: 100%;
        object-fit: cover
    }
</style>

<div>

    <div class="app-brand demo mb-2 d-flex justify-content-center align-items-center">
        <a href="/admin/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo " style="background:white">
                <div class="custom-logo-width">
                    <img src="{{ asset('logo.png') }}" class="img-fluid  " alt="Logo here">
                </div>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <a href="{{ route('admin.users') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Users</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.applications') ? 'active' : '' }}">
            <a href="{{ route('admin.applications') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div>
                    Applications
                    @if ($pendingApplications)
                        <span class="badge bg-primary text-white fs-6">
                            {{ $pendingApplications }}
                        </span>
                    @endif
                </div>
            </a>

        </li>
        <li class="menu-item {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
            <a href="{{ route('admin.categories') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div>Categories</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.platforms') ? 'active' : '' }}">
            <a href="{{ route('admin.platforms') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shape-square"></i>
                <div>Platforms</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.cards') ? 'active' : '' }}">
            <a href="{{ route('admin.cards') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card-alt"></i>
                <div>Cards</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.enterpriser.create') ? 'active' : '' }}">
            <a href="{{ route('admin.enterpriser.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Add Enterpriser</div>
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
