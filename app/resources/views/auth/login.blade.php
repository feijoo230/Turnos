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
        <h1 class="login-title"><span>Observatorio Alanís</span></h1>
        <p class="login-subtitle">Portal de Turnos, Visitantes y Operadores</p>
    </div>

    <div class="login-card-body">
        @if ($errors->any())
            <div class="alert-cosmic-danger">
                <div class="font-weight-bold mb-1" style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-exclamation-triangle"></i> Verifique los siguientes datos:
                </div>
                <ul class="mb-0 pl-3" style="font-size: 0.82rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="email" class="form-label-modern">
                    <i class="fas fa-envelope"></i> Correo Electrónico <span style="color: #dc2626;">*</span>
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" class="form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ejemplo@unsa.edu.ar" autofocus>
                </div>
                @error('email')
                    <span class="small font-weight-bold mt-1 d-block" style="color: #dc2626;" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label-modern">
                    <i class="fas fa-lock"></i> Contraseña <span style="color: #dc2626;">*</span>
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" class="form-control-modern @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <i class="fas fa-eye toggle-password" id="icon-toggle-pass" onclick="togglePasswordVisibility('password', 'icon-toggle-pass')" title="Mostrar/ocultar contraseña"></i>
                </div>
                @error('password')
                    <span class="small font-weight-bold mt-1 d-block" style="color: #dc2626;" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="auth-meta-row">
                <label class="custom-checkbox-wrap" for="remember">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="link-cosmic" href="{{ route('password.request') }}">
                        ¿Olvidó su contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-gradient-primary">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="auth-divider">
            <span>O continuar con</span>
        </div>

        <div>
            <a href="{{ route('google.login') }}" class="btn-google">
                <svg width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    <path fill="none" d="M0 0h48v48H0z"/>
                </svg>
                <span>Acceder con Google</span>
            </a>
        </div>

        <div class="text-center mt-4 pt-3" style="border-top: 1px solid rgba(148, 163, 184, 0.12);">
            <a href="{{ url('/') }}" class="back-to-home">
                <i class="fas fa-arrow-left"></i> Volver al Portal de Turnos
            </a>
        </div>
    </div>
</div>
@endsection

