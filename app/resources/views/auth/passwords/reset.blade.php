@extends('layouts.login')

@section('content')
<div class="login-card">
    <div class="login-header">
        <div class="brand-logo-wrap">
            <img src="{{ asset('img/logounsa.png') }}" alt="UNSa Logo">
        </div>
        <div>
            <span class="badge-eyebrow"><i class="fas fa-university mr-1"></i> CIENCIAS EXACTAS · UNSa</span>
        </div>
        <h1 class="login-title"><span>Nueva Clave</span></h1>
        <p class="login-subtitle">Observatorio Astronómico Dr. Elvio Alanís</p>
    </div>

    <div class="login-card-body">
        @if ($errors->any())
            <div class="alert-cosmic-danger">
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
                <label for="email" class="form-label-modern">
                    <i class="fas fa-envelope"></i> Correo Electrónico <span style="color: var(--cosmic-cyan);">*</span>
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" class="form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="ejemplo@unsa.edu.ar">
                </div>
                @error('email')
                    <span class="small font-weight-bold mt-1 d-block" style="color: #f87171;" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label-modern">
                    <i class="fas fa-lock"></i> Nueva Contraseña <span style="color: var(--cosmic-cyan);">*</span>
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" class="form-control-modern @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                    <i class="fas fa-eye toggle-password" id="icon-toggle-pass1" onclick="togglePasswordVisibility('password', 'icon-toggle-pass1')" title="Mostrar/ocultar contraseña"></i>
                </div>
                @error('password')
                    <span class="small font-weight-bold mt-1 d-block" style="color: #f87171;" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password-confirm" class="form-label-modern">
                    <i class="fas fa-check-double"></i> Confirmar Contraseña <span style="color: var(--cosmic-cyan);">*</span>
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-check-double input-icon"></i>
                    <input id="password-confirm" type="password" class="form-control-modern" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    <i class="fas fa-eye toggle-password" id="icon-toggle-pass2" onclick="togglePasswordVisibility('password-confirm', 'icon-toggle-pass2')" title="Mostrar/ocultar contraseña"></i>
                </div>
            </div>

            <button type="submit" class="btn-gradient-primary mb-3">
                <i class="fas fa-key"></i> Restablecer Contraseña
            </button>
        </form>

        <div class="text-center mt-4 pt-3" style="border-top: 1px solid rgba(148, 163, 184, 0.12);">
            <a href="{{ route('login') }}" class="back-to-home">
                <i class="fas fa-arrow-left"></i> Volver a Iniciar Sesión
            </a>
        </div>
    </div>
</div>
@endsection

