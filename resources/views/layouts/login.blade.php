<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Project SLF PT. KAN</title>

    <!-- SEO Optimization -->
    <meta name="description" content="Login Screen">
    <meta name="author" content="Spark Admin Team">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Local Third-Party Libraries (100% Offline Compatible) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Main Design System & Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>

<body>

    <!-- ==========================================
         START: Authentication Container & Login Card
         ========================================== -->
    <div class="login-wrapper">
        <!-- Glowing background shapes for modern visual appearance -->
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>

        <!-- Main centered login card -->
        <form class="user" id="loginForm">
            @csrf
            <div class="login-card">

                <!-- Brand Identity -->

                <div class="row g-3 justify-content-center">
                    <div class="col-3">
                        <img src="{{ asset('assets/images/ptkan.png') }}" alt="Monitoring Proyek"
                            class="img-fluid  dashboard-image">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset('assets/images/rpnp-dashboardfix.png') }}" alt="Dashboard Project"
                            class="img-fluid  dashboard-image">
                    </div>
                </div>
                <p class="mt-4">Please sign in to access your dashboard</p>

                <!-- Login Form -->
                <form action="index.html" method="GET" id="loginForm" class="needs-validation" novalidate>

                    <!-- Email Input Group -->
                    <div class="login-form-group">
                        <label for="email" class="login-form-label">Email Address</label>
                        <div class="login-input-group">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" id="email" class="login-input" name = "email"
                                placeholder="name@company.com" required>
                        </div>
                    </div>

                    <!-- Password Input Group -->
                    <div class="login-form-group">
                        <label for="password" class="login-form-label">Password</label>
                        <div class="login-input-group">
                            <i class="bi bi-shield-lock input-icon"></i>
                            <input type="password" id="password" name="password"
                                class="login-input login-input-password" placeholder="••••••••" required>
                            <button type="button" class="password-toggle-btn" id="toggle-password"
                                aria-label="Show password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Options (Remember me & Forgot Password) -->
                    {{-- <div class="login-options">
                        <label class="custom-control-label">
                            <input type="checkbox" class="custom-checkbox-input" id="rememberMe">
                            <span>Remember Me</span>
                        </label>
                        <a href="#" class="forgot-password-link">Forgot Password?</a>
                    </div> --}}

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="btn-submit">
                        <span>LOGIN</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>


            </div>
        </form>
    </div>
    <!-- END: Authentication Container -->

    <!-- Local Bootstrap bundle -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom Authentication interactions script -->
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('login') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Login berhasil, mengalihkan...',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan';

                        if (xhr.status === 401) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal!',
                            html: message,
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
