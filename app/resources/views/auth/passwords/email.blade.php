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
        <h1 class="login-title"><span>Recuperar Clave</span></h1>
        <p class="login-subtitle">Observatorio Astronómico Dr. Elvio Alanís</p>
    </div>

    <div class="login-card-body">
        @if (session('status'))
            <div class="alert-cosmic-success">
                <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-cosmic-danger">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="small text-center mb-4" style="color: var(--text-muted); line-height: 1.5;">
            Ingrese su correo electrónico registrado y le enviaremos un enlace seguro para restablecer su contraseña.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group mb-4">
                <label for="email" class="form-label-modern">
                    <i class="fas fa-envelope"></i> Correo Electrónico <span style="color: var(--cosmic-cyan);">*</span>
                </label>
                <div class="input-group-modern">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" class="form-control-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ejemplo@unsa.edu.ar" autofocus>
                </div>
                @error('email')
                    <span class="small font-weight-bold mt-1 d-block" style="color: #f87171;" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn-gradient-primary mb-3">
                <i class="fas fa-paper-plane"></i> Enviar Enlace de Restablecimiento
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