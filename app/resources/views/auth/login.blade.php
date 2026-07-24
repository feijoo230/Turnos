@extends('layouts.login')

@section('content')
<div class="login-card">
    <div class="login-header text-center">
        <img src="{{ asset('img/logounsa.png') }}" alt="UNSa Logo" class="login-logo">
        <h4 class="font-weight-bold mb-1" style="font-size: 1.35rem; color: white;">{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos') }}</h4>
        <p class="small mb-0 text-white-50" style="opacity: 0.9;">Acceso a Operadores y Administradores</p>
    </div>

    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger p-2 mb-3 small" style="border-radius: 8px;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="email" class="small font-weight-bold text-secondary mb-1">
                    <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                </label>
                <input id="email" type="email" class="form-control form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="usuario@unsa.edu.ar" autofocus>
                @error('email')
                    <span class="invalid-feedback d-block small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="small font-weight-bold text-secondary mb-1">
                    <i class="fas fa-lock mr-1"></i> Contraseña
                </label>
                <input id="password" type="password" class="form-control form-control-modern @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <span class="invalid-feedback d-block small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group d-flex justify-content-between align-items-center mb-3">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label small text-muted cursor-pointer" for="remember">
                        Recordarme
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="small font-weight-bold text-primary" href="{{ route('password.request') }}">
                        ¿Olvidó su contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-gradient-primary shadow-sm mb-3">
                <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
            </button>
        </form>

        <div class="text-center position-relative my-3">
            <hr>
            <span class="position-absolute bg-white px-2 small text-muted" style="top: -10px; left: 50%; transform: translateX(-50%);">o continuar con</span>
        </div>

        <div class="mt-3">
            <a href="{{ route('google.login') }}" class="btn-google">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 18px;">
                <span>Iniciar sesión con Google</span>
            </a>
        </div>

        <div class="text-center mt-4 pt-2 border-top">
            <a href="{{ url('/') }}" class="small text-muted font-weight-bold text-decoration-none">
                <i class="fas fa-arrow-left mr-1"></i> Volver al Portal de Turnos
            </a>
        </div>
    </div>
</div>
@endsection
