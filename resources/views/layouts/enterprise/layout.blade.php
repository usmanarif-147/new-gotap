<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GoTap') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />


    <meta name="description" content="" />

    @include('layouts.enterprise.partials.css')

    @yield('style')

    @livewireStyles

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
                <div class="content-wrapper" id="main-content" style="visibility: hidden;">
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

    @include('layouts.enterprise.partials.js')


    @yield('script')

    <script>
        $(document).ready(function() {
            $("#loader").hide();
            $('#main-content').css('visibility', 'visible');
        })
        $('#footer-date').text(new Date().getFullYear())
    </script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
</body>

</html>
