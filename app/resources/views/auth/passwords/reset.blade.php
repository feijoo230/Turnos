@extends('layouts.login')

@section('content')
<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('img/logounsa.png') }}" alt="UNSa Logo" class="login-logo">
        <h4 class="login-title">{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos') }}</h4>
        <p class="login-subtitle mb-0">Establecer Nueva Contraseña</p>
    </div>

    <div class="card-body p-4 pt-4">
        @if ($errors->any())
            <div class="alert alert-danger p-3 mb-3 small border-0 shadow-xs" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group mb-3">
                <label for="email" class="small font-weight-bold text-secondary mb-1">
                    Correo Electrónico *
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" class="form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="usuario@unsa.edu.ar">
                </div>
                @error('email')
                    <span class="text-danger small mt-1 d-block font-weight-bold" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="small font-weight-bold text-secondary mb-1">
                    Nueva Contraseña *
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" class="form-control-modern @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                    <i class="fas fa-eye toggle-password" id="icon-toggle-pass1" onclick="togglePasswordVisibility('password', 'icon-toggle-pass1')" title="Mostrar/ocultar contraseña"></i>
                </div>
                @error('password')
                    <span class="text-danger small mt-1 d-block font-weight-bold" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password-confirm" class="small font-weight-bold text-secondary mb-1">
                    Confirmar Nueva Contraseña *
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-check-double input-icon"></i>
                    <input id="password-confirm" type="password" class="form-control-modern" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    <i class="fas fa-eye toggle-password" id="icon-toggle-pass2" onclick="togglePasswordVisibility('password-confirm', 'icon-toggle-pass2')" title="Mostrar/ocultar contraseña"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-gradient-primary mb-3">
                <i class="fas fa-key"></i> Restablecer Contraseña
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top" style="border-top-color: #f1f5f9 !important;">
            <a href="{{ route('login') }}" class="small text-secondary font-weight-bold text-decoration-none d-inline-flex align-items-center" style="gap: 6px;">
                <i class="fas fa-arrow-left"></i> Volver a Iniciar Sesión
            </a>
        </div>
    </div>
</div>
@endsection
