<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos UNSa') }} | Acceso</title>

    <!-- Google Fonts Outfit & FontAwesome 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link href="{{ asset('css/appl.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1e3c72;
            --primary-dark: #1e293b;
            --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --accent-gradient: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            --font-main: 'Outfit', sans-serif;
            --card-shadow: 0 20px 45px rgba(15, 23, 42, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body.login-body {
            font-family: var(--font-main);
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(30, 60, 114, 0.85)), url("{{ asset('img/turnosonline/turnosonline7.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            margin: 0;
            color: #334155;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-header {
            background: var(--primary-gradient);
            color: white;
            padding: 32px 24px 28px;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            right: 0;
            height: 30px;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }

        .login-logo {
            height: 60px;
            margin-bottom: 12px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.25));
        }

        .login-title {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
        }

        .input-group-modern {
            position: relative;
            margin-bottom: 18px;
        }

        .input-group-modern .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            z-index: 10;
            transition: color 0.2s ease;
        }

        .form-control-modern {
            width: 100%;
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
            height: 48px;
            font-family: var(--font-main);
            background-color: #f8fafc;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-control-modern:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-control-modern:focus + .input-icon,
        .input-group-modern:focus-within .input-icon {
            color: #2563eb;
        }

        .btn-gradient-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.25);
        }

        .btn-gradient-primary:hover {
            box-shadow: 0 8px 22px rgba(30, 60, 114, 0.4);
            color: white;
            transform: translateY(-1px);
        }

        .btn-gradient-primary:active {
            transform: translateY(0);
        }

        .btn-google {
            background: white;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 0.95rem;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-decoration: none;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            z-index: 10;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #475569;
        }
    </style>
</head>
<body class="login-body">
        
    @yield('content')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/appl.js') }}"></script>
    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (field && icon) {
                if (field.type === "password") {
                    field.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    field.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        }
    </script>
</body>
</html>
