<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos UNSa') }} | Portal de Acceso</title>

    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link href="{{ asset('css/appl.css') }}" rel="stylesheet">

    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --accent-blue: #0284c7;
            --text-main: #334155;
            --text-muted: #64748b;
            --text-subtle: #94a3b8;
            --border-color: #e2e8f0;
            --font-display: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body.login-body {
            font-family: var(--font-body) !important;
            background-color: var(--bg-color) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            color: var(--text-main);
            position: relative;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
        }

        /* Card */
        .login-card {
            background: var(--card-bg) !important;
            border-radius: 16px !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
            width: 100%;
        }

        /* Login Header / Branding */
        .login-header {
            padding: 36px 28px 16px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            background: #f1f5f9;
        }

        .brand-logo-wrap {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 6px;
            margin: 0 auto 16px;
            border: 1px solid var(--border-color);
        }

        .brand-logo-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .badge-eyebrow {
            display: inline-block;
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--accent-blue) !important;
            background: #e0f2fe;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .login-title {
            font-family: var(--font-display) !important;
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
            margin: 0 0 4px 0 !important;
        }

        .login-subtitle {
            font-size: 0.9rem !important;
            color: var(--text-muted) !important;
            margin-bottom: 0 !important;
        }

        /* Form Body */
        .login-card-body {
            padding: 24px 28px 32px;
        }

        .form-label-modern {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-display);
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main) !important;
            margin-bottom: 7px;
        }

        .form-label-modern i {
            color: var(--text-muted);
        }

        .input-group-modern {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-modern .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            z-index: 10;
        }

        .form-control-modern {
            width: 100%;
            border-radius: 8px !important;
            padding: 12px 44px 12px 44px !important;
            border: 1px solid var(--border-color) !important;
            font-size: 0.95rem !important;
            height: 48px !important;
            background-color: #ffffff !important;
            color: var(--text-main) !important;
            transition: all 0.2s ease !important;
        }

        .form-control-modern:focus {
            outline: none !important;
            border-color: var(--accent-blue) !important;
            box-shadow: 0 0 0 3px #bae6fd !important;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            z-index: 10;
            padding: 6px;
        }

        /* Meta Row */
        .auth-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }

        .custom-checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-main);
        }

        .custom-checkbox-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .link-cosmic {
            color: var(--accent-blue) !important;
            font-weight: 500 !important;
            text-decoration: none !important;
        }
        .link-cosmic:hover {
            text-decoration: underline !important;
        }

        /* Buttons */
        .btn-gradient-primary {
            background: var(--accent-blue) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s ease !important;
        }

        .btn-gradient-primary:hover {
            background: #0369a1 !important;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
        }
        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }
        .auth-divider span {
            padding: 0 12px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .btn-google {
            background: #ffffff !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            padding: 12px !important;
            font-weight: 500 !important;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none !important;
            transition: background 0.2s ease !important;
        }
        .btn-google:hover {
            background: #f1f5f9 !important;
            text-decoration: none !important;
        }

        .back-to-home {
            color: var(--text-muted) !important;
            font-size: 0.9rem;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-to-home:hover {
            color: var(--accent-blue) !important;
        }

        .auth-roles-footer {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        .role-pill {
            font-size: 0.75rem;
            color: var(--text-muted);
            background: #ffffff;
            border: 1px solid var(--border-color);
            padding: 4px 12px;
            border-radius: 12px;
        }

        .alert-cosmic-danger {
            background: #fef2f2 !important;
            border: 1px solid #fecaca !important;
            color: #dc2626 !important;
            border-radius: 8px !important;
            padding: 12px 14px !important;
            font-size: 0.85rem !important;
            margin-bottom: 20px !important;
        }
    </style>
</head>
<body class="login-body">
    <div class="login-wrapper">
        @yield('content')

        <div class="auth-roles-footer">
            <span class="role-pill"><i class="fas fa-id-card"></i> Visitantes</span>
            <span class="role-pill"><i class="fas fa-graduation-cap"></i> Escuelas</span>
            <span class="role-pill"><i class="fas fa-user-shield"></i> Operadores</span>
        </div>
    </div>

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
