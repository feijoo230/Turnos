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
            --cosmic-bg: #070a13;
            --cosmic-card: rgba(13, 21, 39, 0.82);
            --cosmic-cyan: #38bdf8;
            --cosmic-blue: #2563eb;
            --cosmic-indigo: #6366f1;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-subtle: #cbd5e1;
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
            background-color: var(--cosmic-bg) !important;
            background-image: 
                radial-gradient(ellipse at 50% 15%, rgba(56, 189, 248, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 85%, rgba(99, 102, 241, 0.12) 0%, transparent 50%),
                linear-gradient(180deg, rgba(7, 10, 19, 0.82) 0%, rgba(13, 21, 39, 0.94) 100%),
                url("{{ asset('img/observatorio/hero-bg.jpg') }}") !important;
            background-size: cover !important;
            background-position: center center !important;
            background-attachment: fixed !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            color: var(--text-main);
            position: relative;
            overflow-x: hidden;
        }

        /* Starfield Canvas */
        #starfield-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
        }

        /* Cosmic Glass Card */
        .login-card {
            background: var(--cosmic-card) !important;
            backdrop-filter: blur(24px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
            border-radius: 24px !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-top: 1px solid rgba(56, 189, 248, 0.4) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 35px rgba(56, 189, 248, 0.12) !important;
            overflow: hidden;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .login-card:hover {
            box-shadow: 0 30px 60px -10px rgba(0, 0, 0, 0.8), 0 0 45px rgba(56, 189, 248, 0.18) !important;
            border-color: rgba(56, 189, 248, 0.3) !important;
        }

        /* Login Header / Branding */
        .login-header {
            padding: 36px 28px 16px;
            text-align: center;
            position: relative;
            background: transparent !important;
        }

        /* Circular glowing logo wrapper for UNSa */
        .brand-logo-wrap {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.45), inset 0 0 8px rgba(0, 0, 0, 0.08);
            padding: 6px;
            margin: 0 auto 16px;
            border: 2px solid rgba(255, 255, 255, 0.8);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .brand-logo-wrap:hover {
            transform: scale(1.05);
            box-shadow: 0 0 35px rgba(56, 189, 248, 0.7);
        }

        .brand-logo-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .badge-eyebrow {
            display: inline-block;
            font-family: var(--font-display);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--cosmic-cyan) !important;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.25);
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 8px;
        }

        .login-title {
            font-family: var(--font-display) !important;
            font-size: 1.6rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.5px !important;
            color: #ffffff !important;
            margin: 0 0 4px 0 !important;
            line-height: 1.2 !important;
        }

        .login-title span {
            background: linear-gradient(135deg, #ffffff 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-subtitle {
            font-size: 0.88rem !important;
            color: var(--text-muted) !important;
            font-weight: 400 !important;
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
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-subtle) !important;
            margin-bottom: 7px;
        }

        .form-label-modern i {
            color: var(--cosmic-cyan);
            font-size: 0.85rem;
        }

        .input-group-modern {
            position: relative;
            margin-bottom: 18px;
        }

        .input-group-modern .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 0.95rem;
            z-index: 10;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-control-modern {
            width: 100%;
            border-radius: 12px !important;
            padding: 13px 44px 13px 44px !important;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
            font-size: 0.95rem !important;
            height: 48px !important;
            font-family: var(--font-body) !important;
            background-color: rgba(15, 23, 42, 0.6) !important;
            color: #ffffff !important;
            transition: all 0.25s ease !important;
        }

        .form-control-modern::placeholder {
            color: #64748b !important;
        }

        .form-control-modern:focus {
            outline: none !important;
            background-color: rgba(15, 23, 42, 0.9) !important;
            border-color: var(--cosmic-cyan) !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2), 0 0 20px rgba(56, 189, 248, 0.15) !important;
            color: #ffffff !important;
        }

        .input-group-modern:focus-within .input-icon {
            color: var(--cosmic-cyan) !important;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            cursor: pointer;
            z-index: 10;
            padding: 6px;
            transition: all 0.2s ease;
        }

        .toggle-password:hover {
            color: var(--cosmic-cyan);
            transform: translateY(-50%) scale(1.1);
        }

        /* Checkbox & Forgot Password */
        .auth-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 0.85rem;
        }

        .custom-checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            color: var(--text-muted);
            transition: color 0.2s ease;
        }

        .custom-checkbox-wrap:hover {
            color: var(--text-subtle);
        }

        .custom-checkbox-wrap input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 17px;
            height: 17px;
            border: 1.5px solid rgba(148, 163, 184, 0.4);
            border-radius: 4px;
            background-color: rgba(15, 23, 42, 0.6);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.2s ease;
            margin: 0;
        }

        .custom-checkbox-wrap input[type="checkbox"]:checked {
            background-color: var(--cosmic-cyan);
            border-color: var(--cosmic-cyan);
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
        }

        .custom-checkbox-wrap input[type="checkbox"]:checked::after {
            content: '✓';
            font-size: 11px;
            font-weight: 900;
            color: #070a13;
            position: absolute;
        }

        .link-cosmic {
            color: var(--cosmic-cyan) !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .link-cosmic:hover {
            color: #7dd3fc !important;
            text-decoration: underline !important;
        }

        /* Buttons */
        .btn-gradient-primary {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 13px !important;
            font-family: var(--font-display) !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4) !important;
        }

        .btn-gradient-primary:hover {
            box-shadow: 0 12px 30px rgba(56, 189, 248, 0.45) !important;
            transform: translateY(-2px);
            color: #ffffff !important;
        }

        .btn-gradient-primary:active {
            transform: translateY(0);
        }

        /* Modern Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 22px 0 20px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        .auth-divider span {
            padding: 0 12px;
            font-family: var(--font-display);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #64748b;
            text-transform: uppercase;
        }

        /* Google OAuth Button */
        .btn-google {
            background: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.9) !important;
            border-radius: 12px !important;
            padding: 12px !important;
            font-family: var(--font-display) !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.25s ease !important;
            text-decoration: none !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25) !important;
        }

        .btn-google:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2) !important;
            text-decoration: none !important;
        }

        /* Back to Home Link */
        .back-to-home {
            color: var(--text-muted) !important;
            font-size: 0.88rem;
            font-weight: 500;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .back-to-home:hover {
            color: var(--cosmic-cyan) !important;
            transform: translateX(-3px);
        }

        /* Badges Footer */
        .auth-roles-footer {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 500;
            color: #94a3b8;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 4px 10px;
            border-radius: 12px;
        }

        .role-pill i {
            color: var(--cosmic-cyan);
            font-size: 0.7rem;
        }

        /* Alerts */
        .alert-cosmic-danger {
            background: rgba(239, 68, 68, 0.12) !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
            color: #fca5a5 !important;
            border-radius: 12px !important;
            padding: 12px 14px !important;
            font-size: 0.85rem !important;
            margin-bottom: 18px !important;
        }

        .alert-cosmic-success {
            background: rgba(16, 185, 129, 0.12) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            color: #6ee7b7 !important;
            border-radius: 12px !important;
            padding: 12px 14px !important;
            font-size: 0.85rem !important;
            margin-bottom: 18px !important;
        }
    </style>
</head>
<body class="login-body">
    <!-- Starfield background canvas -->
    <canvas id="starfield-canvas"></canvas>
        
    <div class="login-wrapper">
        @yield('content')

        <div class="auth-roles-footer">
            <span class="role-pill"><i class="fas fa-id-card"></i> Visitantes</span>
            <span class="role-pill"><i class="fas fa-graduation-cap"></i> Escuelas / Alumnos</span>
            <span class="role-pill"><i class="fas fa-user-shield"></i> Operadores UNSa</span>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/appl.js') }}"></script>
    <script>
        // Password toggle function
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

        // Lightweight Starfield Canvas
        (function() {
            const canvas = document.getElementById('starfield-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width, height;
            const stars = [];
            const numStars = 65;

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }

            function initStars() {
                stars.length = 0;
                for (let i = 0; i < numStars; i++) {
                    stars.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        radius: Math.random() * 1.4 + 0.4,
                        alpha: Math.random() * 0.7 + 0.2,
                        speed: Math.random() * 0.008 + 0.003,
                        dir: Math.random() > 0.5 ? 1 : -1
                    });
                }
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);
                for (let star of stars) {
                    star.alpha += star.speed * star.dir;
                    if (star.alpha > 0.95) {
                        star.alpha = 0.95;
                        star.dir = -1;
                    } else if (star.alpha < 0.15) {
                        star.alpha = 0.15;
                        star.dir = 1;
                    }

                    ctx.beginPath();
                    ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 255, 255, ${star.alpha})`;
                    ctx.fill();
                }
                requestAnimationFrame(animate);
            }

            window.addEventListener('resize', () => {
                resize();
                initStars();
            });

            resize();
            initStars();
            animate();
        })();
    </script>
</body>
</html>
