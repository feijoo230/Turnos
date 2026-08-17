@extends('layouts.login')

@section('content')
<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('img/logounsa.png') }}" alt="UNSa Logo" class="login-logo">
        <h4 class="login-title">{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos') }}</h4>
        <p class="login-subtitle mb-0">Acceso a Operadores y Administradores</p>
    </div>

    <div class="card-body p-4 pt-4">
        @if ($errors->any())
            <div class="alert alert-danger p-3 mb-3 small border-0 shadow-xs" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
                <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Por favor verifique los siguientes errores:</div>
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
                    Correo Electrónico *
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" class="form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="usuario@unsa.edu.ar" autofocus>
                </div>
                @error('email')
                    <span class="text-danger small mt-1 d-block font-weight-bold" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="small font-weight-bold text-secondary mb-1">
                    Contraseña *
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" class="form-control-modern @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <i class="fas fa-eye toggle-password" id="icon-toggle-pass" onclick="togglePasswordVisibility('password', 'icon-toggle-pass')" title="Mostrar/ocultar contraseña"></i>
                </div>
                @error('password')
                    <span class="text-danger small mt-1 d-block font-weight-bold" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group d-flex justify-content-between align-items-center mb-4">
                <div class="custom-control custom-checkbox d-flex align-items-center" style="gap: 6px;">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="cursor: pointer; width: 16px; height: 16px;">
                    <label class="small text-muted mb-0 cursor-pointer" for="remember" style="user-select: none;">
                        Recordarme
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="small font-weight-bold text-primary text-decoration-none" href="{{ route('password.request') }}">
                        ¿Olvidó su contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-gradient-primary mb-3">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="text-center position-relative my-3">
            <hr style="border-top: 1px solid #e2e8f0;">
            <span class="position-absolute bg-white px-2 small text-muted font-weight-bold" style="top: -11px; left: 50%; transform: translateX(-50%); border-radius: 4px;">O CONTINUAR CON</span>
        </div>

        <div class="mt-3 mb-3">
            <a href="{{ route('google.login') }}" class="btn-google">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 18px; height: 18px;">
                <span>Iniciar sesión con Google</span>
            </a>
        </div>

        <div class="text-center mt-4 pt-3 border-top" style="border-top-color: #f1f5f9 !important;">
            <a href="{{ url('/') }}" class="small text-secondary font-weight-bold text-decoration-none d-inline-flex align-items-center" style="gap: 6px;">
                <i class="fas fa-arrow-left"></i> Volver al Portal de Turnos
            </a>
        </div>
    </div>
</div>
@endsection
