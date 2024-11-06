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

        .vertical-line {
            width: 2px;
            height: 24px;
            background-color: #ccc;
            display: inline-block;
            margin-right: 10px;
        }

        .bg-active .vertical-line {
            background-color: #6b5bff;
        }
    </style>
    <div class="app-brand demo mb-2 d-flex justify-content-center align-items-center">
        <a href="/enterprise/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo" style="background:white">
                <div>
                    <img src="{{ asset(auth()->user()->enterprise_logo && file_exists(public_path('storage/' . auth()->user()->enterprise_logo)) ? Storage::url(auth()->user()->enterprise_logo) : 'logo.png') }}"
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
            <a class="menu-link {{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#TeamSubmenu" role="button"
                aria-expanded="{{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage') ? 'true' : 'false' }}"
                aria-controls="TeamSubmenu">
                <i class="menu-icon tf-icons bx bxs-group"></i>
                <div class="me-5">Team</div>
                <i
                    class='arrow bx {{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage') ? 'show' : '' }}"
                id="TeamSubmenu">
                <li
                    class="{{ Request::routeIs('enterprise.profiles', 'enterprise.profile.manage') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profiles') }}" class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        {{-- <i class='tf-icons bx bxs-user-detail me-3'></i> --}}
                        <div>Profiles</div>
                    </a>
                </li>
                <li class="{{ Request::routeIs('enterprise.profile.subteams') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profile.subteams') }}"
                        class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        {{-- <i class='tf-icons bx bxs-user-detail me-3'></i> --}}
                        <div>Sub Teams</div>
                    </a>
                </li>
                <li class="{{ Request::routeIs('enterprise.profile.create') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profile.create') }}" class="dropdown-item d-flex align-items-center">
                        {{-- <i class='tf-icons bx bxs-user-detail me-3'></i> --}}
                        <div class="vertical-line me-3"></div>
                        <div>Create Profile</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a class="menu-link {{ Request::routeIs('enterprise.leads', 'enterprise.leads-map', 'enterprise.leads.view') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#LeadsSubmenu" role="button"
                aria-expanded="{{ Request::routeIs('enterprise.leads', 'enterprise.leads-map', 'enterprise.leads.view') ? 'true' : 'false' }}"
                aria-controls="LeadsSubmenu">
                <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                <div class="me-5">Leads</div>
                <i
                    class='arrow bx {{ Request::routeIs('enterprise.leads', 'enterprise.leads-map', 'enterprise.leads.view') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ Request::routeIs('enterprise.leads', 'enterprise.leads-map', 'enterprise.leads.view') ? 'show' : '' }}"
                id="LeadsSubmenu">
                <li
                    class="{{ Request::routeIs('enterprise.leads', 'enterprise.leads.view') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.leads') }}" class="dropdown-item d-flex align-items-center">
                        {{-- <i class='tf-icons bx bxs-group me-3'></i> --}}
                        <div class="vertical-line me-3"></div>
                        <div>Leads</div>
                    </a>
                </li>

                <li class="{{ Request::routeIs('enterprise.leads-map') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.leads-map') }}" class="dropdown-item d-flex align-items-center">
                        {{-- <i class='tf-icons bx bxs-map me-3'></i> --}}
                        <div class="vertical-line me-3"></div>
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

            const settingsLinkLeads = document.querySelector('[href="#LeadsSubmenu"]');
            settingsLinkLeads?.addEventListener('click', function() {
                const arrowLeads = settingsLinkLeads.querySelector('.arrow');
                arrowLeads.classList.toggle('rotate-180'); // Toggle rotation class
            });
        });
    </script>
</div>
