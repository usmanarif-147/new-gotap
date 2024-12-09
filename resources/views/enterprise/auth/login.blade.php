<html>

<head>
    <title>GOtaps Teams Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
</head>

<body>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bx-hide');
                passwordIcon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bx-show');
                passwordIcon.classList.add('bx-hide');
            }
        }
    </script>
</body>
<!-- Login 8 - Bootstrap Brain Component -->
<section class="bg-light p-3 p-md-4 p-xl-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xxl-11">
                <div class="card border-light-subtle shadow-sm">
                    <div class="row g-0">
                        <div class="col-12 col-md-6">
                            <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy"
                                src="{{ asset('loginside.jpeg') }}" alt="Welcome back you've been missed!">
                        </div>
                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                            <div class="col-12 col-lg-11 col-xl-10">
                                <div class="card-body p-3 p-md-4 p-xl-5">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-5">
                                                <div class="text-center mb-4">
                                                    <a href="#!">
                                                        <img src="{{ asset('gotapsteam.png') }}"
                                                            alt="BootstrapBrain Logo" width="155" height="147">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('auth_failed'))
                                        <div class="mt-2 alert alert-danger">
                                            {{ $errors->first('auth_failed') }}
                                        </div>
                                    @endif
                                    <form action="{{ route('enterprise.login') }}" method="POST">
                                        @csrf
                                        <div class="row gy-3 overflow-hidden">
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" type="email"
                                                        name="email" required value="{{ old('email') }}"
                                                        placeholder="name@example.com" autofocus />
                                                    <label for="email" class="form-label">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating mb-3 position-relative">
                                                    <input type="password" id="password" class="form-control"
                                                        name="password" required autocomplete="current-password"
                                                        placeholder="Password" />
                                                    <label for="password">Password</label>
                                                    <span
                                                        class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer"
                                                        onclick="togglePasswordVisibility()">
                                                        <i class="bx bx-hide" id="password-icon"></i>
                                                    </span>
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="remember_me" id="remember_me">
                                                    <label class="form-check-label text-secondary" for="remember_me">
                                                        Keep me logged in
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-dark btn-lg" type="submit">Log in
                                                        now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-12">
                                            <div
                                                class="d-flex gap-2 gap-md-3 flex-column flex-md-row justify-content-md-center mt-5">
                                                <a href="{{ route('enterprise.password.request') }}"
                                                    class="link-secondary text-decoration-none">Forgot
                                                    password</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</html>
