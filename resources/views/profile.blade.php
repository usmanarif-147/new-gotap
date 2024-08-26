<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />

    <title>{{ config('app.name', 'GoTap') }}</title>

    <meta name="description" content="" />

    <!-- Link Stylesheet -->
    {{-- <link rel="stylesheet" href="{{ asset('profile/style.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('profile/newstyle.css') }}" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
    <!------------------------------------------------ Boxicon CDN ------------------------------------------->

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        .profile_img {
            display: flex;
            justify-content: center;
            border-radius: 50%;
            margin-top: -10%;

        }

        .profile_img img {
            height: 150px;
            width: 150px;
            border-radius: 50%;
            border: 7px solid #fff;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <livewire:profile.view />

    @livewireScripts
</body>

</html>
