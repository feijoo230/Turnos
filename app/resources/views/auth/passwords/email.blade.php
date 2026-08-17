@extends('layouts.login')

@section('content')
<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('img/logounsa.png') }}" alt="UNSa Logo" class="login-logo">
        <h4 class="login-title">{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos') }}</h4>
        <p class="login-subtitle mb-0">Recuperación de Contraseña</p>
    </div>

    <div class="card-body p-4 pt-4">
        @if (session('status'))
            <div class="alert alert-success p-3 mb-3 small font-weight-bold" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger p-3 mb-3 small border-0 shadow-xs" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="text-muted small text-center mb-4">
            Ingrese la dirección de correo electrónico asociada a su cuenta y le enviaremos un enlace para restablecer su contraseña.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group mb-4">
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

            <button type="submit" class="btn btn-gradient-primary mb-3">
                <i class="fas fa-paper-plane"></i> Enviar Enlace de Restablecimiento
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