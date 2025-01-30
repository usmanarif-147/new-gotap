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


    </div>
</nav>
