<div>
    <style>
        .submenu {
            list-style: none;
        }

        .bg-active {
            background-color: #dcdcdc9f;
            border-radius: 8px;
            display: inline-block;
            width: auto;
        }

        .menu-item .active a {
            color: #000000;
            font-weight: bold;
        }

        .vertical-line {
            width: 2px;
            height: 24px;
            background-color: #b0afaf;
            display: inline-block;
            margin-right: 10px;
        }

        .bg-active .vertical-line {
            background-color: #000000;
        }

        hr.shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            border: 0;
            /* Removes the default border */
            height: 1px;
            /* Ensures it looks like an <hr> */
            background-color: #ddd;
            /* Adjust the color as needed */
        }
    </style>
    <div class="app-brand demo mb-2 d-flex justify-content-center align-items-center">
        <a href="/enterprise/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo" style="background:white;">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('gotapEnterprise.png') }}" class="img-fluid" height="100" width="100"
                        alt="Logo here">
                    <span class="ms-2 fw-bold" style="color: black">Teams</span>
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
            <a class="menu-link {{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage', 'enterprise.requests') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#TeamSubmenu" role="button"
                aria-expanded="{{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage', 'enterprise.requests') ? 'true' : 'false' }}"
                aria-controls="TeamSubmenu">
                <i class="menu-icon tf-icons bx bxs-group"></i>
                <div>Team</div>
                <i
                    class='ms-auto arrow bx {{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage', 'enterprise.requests') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ Request::routeIs('enterprise.profiles', 'enterprise.profile.create', 'enterprise.profile.subteams', 'enterprise.profile.manage', 'enterprise.requests') ? 'show' : '' }}"
                id="TeamSubmenu">
                <li
                    class="{{ Request::routeIs('enterprise.profiles', 'enterprise.profile.manage') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profiles') }}" class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>Profiles</div>
                    </a>
                </li>
                <li class="{{ Request::routeIs('enterprise.profile.subteams') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profile.subteams') }}"
                        class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>Sub Teams</div>
                    </a>
                </li>
                <li class="{{ Request::routeIs('enterprise.profile.create') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.profile.create') }}" class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>Create Profile</div>
                    </a>
                </li>
                <li class="{{ $isActive || Request::routeIs('enterprise.requests') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.requests') }}" class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>
                            Requests
                            @if ($pending)
                                <span class="badge bg-warning">
                                    {{ $pending }}
                                </span>
                            @endif
                        </div>
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
                <div>Leads</div>
                <i
                    class='ms-auto arrow bx {{ Request::routeIs('enterprise.leads', 'enterprise.leads-map', 'enterprise.leads.view') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
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
        <li class="menu-item">
            <a href="https://gotaps.me/standard-products/" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bxl-product-hunt"></i>
                <div>Accessories</div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link {{ Request::routeIs('enterprise.edit') ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                href="#AdminSubmenu" role="button"
                aria-expanded="{{ Request::routeIs('enterprise.edit') ? 'true' : 'false' }}"
                aria-controls="AdminSubmenu">
                <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                <div>Admin</div>
                <i
                    class='ms-auto arrow bx {{ Request::routeIs('enterprise.edit') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ Request::routeIs('enterprise.edit') ? 'show' : '' }}" id="AdminSubmenu">
                <li class="{{ Request::routeIs('enterprise.edit') ? 'active bg-active' : '' }}">
                    <a href="{{ route('enterprise.edit') }}" class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>Manage Account</div>
                    </a>
                </li>
                <li class="{{ Request::routeIs('') ? 'active bg-active' : '' }}">
                    <a href="javascript:void(0)" onclick="changePassword()"
                        class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>Password Reset</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="https://gotaps.me/support/" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div>Support</div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link {{ Request::routeIs('') ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                href="#SettingSubmenu" role="button" aria-expanded="{{ Request::routeIs('') ? 'true' : 'false' }}"
                aria-controls="SettingSubmenu">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Settings</div>
                <i class='ms-auto arrow bx {{ Request::routeIs('') ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}'></i>
            </a>
            <ul class="collapse submenu {{ Request::routeIs('') ? 'show' : '' }}" id="SettingSubmenu">
                <li class="{{ Request::routeIs('') ? 'active bg-active' : '' }}">
                    <a href="#" class="dropdown-item d-flex align-items-center">
                        <div class="vertical-line me-3"></div>
                        <div>My Subscription</div>
                    </a>
                </li>
            </ul>
        </li>
        <hr class="my-4 text-gray w-100 shadow">
        </hr>
        <li class="menu-item p-2">
            <div class="card text-white p-2" style="background: #0EA7C1;">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('logo.png') }}" alt="Card Logo" class="img-fluid me-3"
                        style="width: 50px; height: auto;">
                    <p class="card-title mb-0 text-wrap fw-bold text-black">Share Your Card on the GoTap</p>
                </div>
                <hr class="my-3 border-white">
                <a href="#" class="text-black text-decoration-none d-block text-center">
                    Click here to get the app
                    <i class='arrow bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </li>
        <li class="menu-item p-2 mt-4">
            <div class="d-flex align-items-center">
                <!-- Profile Image -->
                <div
                    style="width: 50px; height: 50px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                    <img src="{{ asset(auth()->user()->enterprise_logo && file_exists(public_path('storage/' . auth()->user()->enterprise_logo)) ? Storage::url(auth()->user()->enterprise_logo) : 'avatar.png') }}"
                        alt="Viewer Photo" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <!-- Name and Email -->
                <div style="margin-left: 5%;">
                    <span class="font-weight-bold text-dark"
                        style="font-size: 15px;">{{ Auth::user()->name ? Auth::user()->name : 'Enterpriser' }}</span>
                    <p class="mb-0" style="font-size: 12px;">
                        {{ Auth::user()->email ? Auth::user()->email : 'N/A' }}</p>
                </div>
            </div>
        </li>
        <hr class="my-4 text-gray w-100 shadow">
        </hr>
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

            const settingsLinkAdmin = document.querySelector('[href="#AdminSubmenu"]');
            settingsLinkAdmin?.addEventListener('click', function() {
                const arrowAdmin = settingsLinkAdmin.querySelector('.arrow');
                arrowAdmin.classList.toggle('rotate-180'); // Toggle rotation class
            });

            const settingsLinkSetting = document.querySelector('[href="#SettingSubmenu"]');
            settingsLinkSetting?.addEventListener('click', function() {
                const arrowSetting = settingsLinkSetting.querySelector('.arrow');
                arrowSetting.classList.toggle('rotate-180'); // Toggle rotation class
            });
        });
    </script>
</div>
