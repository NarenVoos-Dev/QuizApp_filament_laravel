<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | QuizApp</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Core CSS -->
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media (max-width: 1200px) {
            .cover-img {
                display: none !important;
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-in;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <main class="mt-0 main-content">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <!-- Login Card -->
                        <div class="mx-auto col-xl-4 col-lg-5 col-md-6 d-flex flex-column">
                            <div class="mt-8 card card-plain fade-in">
                                <div class="text-left bg-transparent card-header">
                                    <h3 class="text-dark text-gradient font-weight-bolder">Bienvenido de nuevo</h3>
                                    <p class="mb-0">Ingresa tu correo y contraseña</p>
                                </div>

                                <div class="card-body">
                                    <!-- Session Status -->
                                    @if (session('status'))
                                        <div class="mb-3 text-sm text-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                                class="form-control @error('email') is-invalid @enderror" placeholder="correo@ejemplo.com">
                                            @error('email')
                                                <span class="text-xs text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label>Contraseña</label>
                                            <input type="password" name="password" required
                                                class="form-control @error('password') is-invalid @enderror" placeholder="********">
                                            @error('password')
                                                <span class="text-xs text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="rememberMe">Recuérdame</label>
                                        </div>

                                        <!-- Submit -->
                                        <div class="text-center">
                                            <button type="submit" class="mt-4 mb-0 btn bg-gradient-info w-100">Iniciar Sesión</button>
                                        </div>
                                    </form>
                                </div>

                               <!-- <div class="px-4 pt-0 text-center card-footer">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-sm text-dark">
                                            ¿Olvidaste tu contraseña?
                                        </a>
                                    @endif
                                </div>-->
                            </div>
                        </div>

                        <!-- Imagen lateral con logo -->
<!-- Imagen lateral con logo -->
                        <div class="p-0 col-md-6 d-none d-md-flex align-items-center justify-content-center bg-dark cover-img">
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" >
                                <img src="{{ asset('assets/img/logo-ct-white.png') }}" alt="Logo QuizApp"
                                    style="max-width: 550px; filter: drop-shadow(0px 4px 10px rgba(0,0,0,0.4));">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
</body>
</html>
