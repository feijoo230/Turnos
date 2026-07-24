<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos UNSa') }} | Iniciar Sesión</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link href="{{ asset('css/appl.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --font-main: 'Outfit', sans-serif;
        }

        body.login-body {
            font-family: var(--font-main);
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(30, 60, 114, 0.85)), url("{{ asset('img/turnosonline/turnosonline7.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
            margin: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.4);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .login-header {
            background: var(--primary-gradient);
            color: white;
            padding: 30px 24px;
            text-center: center;
        }

        .login-logo {
            height: 55px;
            margin-bottom: 12px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .btn-gradient-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.25s ease;
        }
        .btn-gradient-primary:hover {
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
            color: white;
            transform: translateY(-1px);
        }

        .btn-google {
            background: white;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 11px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-google:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-decoration: none;
        }

        .form-control-modern {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
            height: 44px;
        }
        .form-control-modern:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
    </style>

    @include('parts.logo')
</head>
<body class="login-body">
        
    @yield('content')

    <!-- Scripts -->
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ asset('js/appl.js') }}"></script>
</body>
</html>
