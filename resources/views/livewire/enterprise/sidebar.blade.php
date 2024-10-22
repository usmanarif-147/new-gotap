<div>
    <style>
        .submenu {
            list-style: none;
        }

        .bg-active {
            background-color: #deccfe;
            border-radius: 8px;
            display: inline-block;
            width: auto;
        }

        .menu-item .active a {
            color: #703ccc;
            font-weight: bold;
        }
    </style>
    <div class="app-brand demo mb-2 d-flex justify-content-center align-items-center">
        <a href="/enterprise/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo" style="background:white">
                <div>
                    <img src="{{ asset(auth()->user()->enterprise_logo ? Storage::url(auth()->user()->enterprise_logo) : 'logo.png') }}"
                        class="img-fluid" height="50" width="50" alt="Logo here">

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
                <i class="menu-icon tf-icons bx bxs-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link {{ request()->routeIs('enterprise.profiles') || request()->routeIs('enterprise.profile.create') || request()->routeIs('enterprise.profile.subteams') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#TeamSubmenu" role="button"
                aria-expanded="{{ request()->routeIs('enterprise.profiles') || request()->routeIs('enterprise.profile.create') || request()->routeIs('enterprise.profile.subteams') ? 'true' : 'false' }}"
                aria-controls="TeamSubmenu">
                <i class="menu-icon tf-icons bx bxs-group"></i>
                <div class="me-5">Team</div>
                <i
                    class='arrow bx {{ request()->routeIs('enterprise.profiles') || request()->routeIs('enterprise.profile.create') || request()->routeIs('enterprise.profile.subteams') ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ request()->routeIs('enterprise.profiles') || request()->routeIs('enterprise.profile.create') || request()->routeIs('enterprise.profile.subteams') ? 'show' : '' }}"
                id="TeamSubmenu">
                <li class="{{ request()->routeIs('enterprise.profiles') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profiles') }}" class="dropdown-item d-flex align-items-center">
                        <i class='tf-icons bx bxs-user-detail me-3'></i>
                        <div>Profiles</div>
                    </a>
                </li>
                <li class="{{ request()->routeIs('enterprise.profile.subteams') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profile.subteams') }}"
                        class="dropdown-item d-flex align-items-center">
                        <i class='tf-icons bx bxs-user-detail me-3'></i>
                        <div>Sub Teams</div>
                    </a>
                </li>
                <li class="{{ request()->routeIs('enterprise.profile.create') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profile.create') }}" class="dropdown-item d-flex align-items-center">
                        <i class='tf-icons bx bxs-user-detail me-3'></i>
                        <div>Create Profile</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a class="menu-link {{ request()->routeIs('enterprise.leads') || request()->routeIs('enterprise.leads-map') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#LeadsSubmenu" role="button"
                aria-expanded="{{ request()->routeIs('enterprise.leads') || request()->routeIs('enterprise.leads-map') ? 'true' : 'false' }}"
                aria-controls="LeadsSubmenu">
                <i class="menu-icon tf-icons bx bxs-group"></i>
                <div class="me-5">Leads</div>
                <i
                    class='arrow bx {{ request()->routeIs('enterprise.leads') || request()->routeIs('enterprise.leads-map') ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ request()->routeIs('enterprise.leads') || request()->routeIs('enterprise.leads-map') ? 'show' : '' }}"
                id="LeadsSubmenu">
                <li class="{{ request()->routeIs('enterprise.leads') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.leads') }}" class="dropdown-item d-flex align-items-center">
                        <i class='tf-icons bx bxs-group me-3'></i>
                        <div>Leads</div>
                    </a>
                </li>
                <li class="{{ request()->routeIs('enterprise.leads-map') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.leads-map') }}" class="dropdown-item d-flex align-items-center">
                        <i class='tf-icons bx bxs-map me-3'></i>
                        <div>Map</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ request()->routeIs('enterprise.edit') ? 'active' : '' }}">
            <a href="{{ route('enterprise.edit') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-account'></i>
                <div>Manage Account</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" onclick="changePassword()" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-key"></i>
                <div>Change Password</div>
            </a>
        </li>
    </ul>
    <script>
        // Handle arrow rotation on submenu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const settingsLink = document.querySelector('[href="#TeamSubmenu"]');
            settingsLink?.addEventListener('click', function() {
                const arrow = settingsLink.querySelector('.arrow');
                arrow.classList.toggle('rotate-180'); // Toggle rotation class
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const settingsLink = document.querySelector('[href="#leadsSubmenu"]');
            settingsLink?.addEventListener('click', function() {
                const arrow = settingsLink.querySelector('.arrow');
                arrow.classList.toggle('rotate-180'); // Toggle rotation class
            });
        });
    </script>
</div>
