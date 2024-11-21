<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GoTap') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />


    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}" />
    @include('layouts.enterprise.partials.css')
    @livewireStyles
    @yield('style')

</head>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.enterprise.partials.sidebar')
            <div class="layout-page">
                @include('layouts.enterprise.partials.nav')
                <div id="loader">
                    <div class="wrapper">
                        <div class="loading-spinner"></div>
                    </div>
                </div>
                <div class="content-wrapper" id="main-content" style="visibility: hidden">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    @include('layouts.enterprise.partials.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide the loader
            document.getElementById('loader').style.display = 'none';

            // Make the main content visible
            document.getElementById('main-content').style.visibility = 'visible';

            // Set the footer date to the current year
            document.getElementById('footer-date').textContent = new Date().getFullYear();
        });
    </script>

    @include('layouts.enterprise.partials.js')
    @yield('script')
    @livewireScripts
    <script src="{{ asset('assets/js/livewire-sortable.js') }}"></script>
</body>

</html>
