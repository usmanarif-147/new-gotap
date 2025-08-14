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
                <div class="d-flex align-items-center">
                    <img src="{{ asset('gotapEnterprise.png') }}" class="img-fluid" height="100" width="100"
                        alt="Logo here">
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

        <li class="menu-item {{ request()->routeIs('admin.users', 'admin.user.edit') ? 'active' : '' }}">
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
                <div>Enterprisers</div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link {{ Request::routeIs('admin.emailcompaign.create') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#CompaignSubmenu" role="button"
                aria-expanded="{{ Request::routeIs('admin.emailcompaign.create') ? 'true' : 'false' }}"
                aria-controls="CompaignSubmenu">
                <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                <div>Campaign</div>
                <i
                    class='ms-auto arrow bx {{ Request::routeIs('admin.emailcompaign.create', 'admin.pushnotification.create') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ Request::routeIs('admin.emailcompaign.create', 'admin.pushnotification.create') ? 'show' : '' }}"
                id="CompaignSubmenu">
                <li class="{{ Request::routeIs('admin.emailcompaign.create') ? 'active bg-active' : '' }}">
                    <a href="{{ route('admin.emailcompaign.create') }}"
                        class="dropdown-item d-flex align-items-center">
                        {{-- <i class='tf-icons bx bxs-group me-3'></i> --}}
                        <div class="vertical-line me-3"></div>
                        <div>Email Compaign</div>
                    </a>
                </li>

                <li class="{{ Request::routeIs('admin.pushnotification.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.pushnotification.create') }}"
                        class="dropdown-item d-flex align-items-center">
                        {{-- <i class='tf-icons bx bxs-map me-3'></i> --}}
                        <div class="vertical-line me-3"></div>
                        <div>Push Notification</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" onclick="changePassword()" class="menu-link">
                <i class="menu-icon tf-icons bx bx-key"></i>
                <div>Change Password</div>
            </a>
        </li>
    </ul>
    <script>
        const settingsLinkLeads = document.querySelector('[href="#CompaignSubmenu"]');
        settingsLinkLeads?.addEventListener('click', function() {
            const arrowLeads = settingsLinkLeads.querySelector('.arrow');
            arrowLeads.classList.toggle('rotate-180'); // Toggle rotation class
        });
    </script>
</div>
