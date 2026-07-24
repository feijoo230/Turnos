<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Portal Oficial de Gestión de Turnos - Universidad Nacional de Salta">
  <meta name="author" content="UNIVERSIDAD NACIONAL DE SALTA">

  <title>{{ config('constants.TITULO_PAGINA', 'Gestión de Turnos | UNSa') }}</title>

  <!-- Google Fonts Outfit & FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <link href="{{ asset('css/frontend-mix.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

  <style>
    :root {
      --primary-color: #1e3c72;
      --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      --accent-color: #11998e;
      --accent-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --bg-light: #f8fafc;
      --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
      --font-main: 'Outfit', sans-serif;
    }

    body {
      font-family: var(--font-main);
      background-color: var(--bg-light);
      color: #334155;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navbar Moderno */
    .custom-navbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(0,0,0,0.04);
      padding: 12px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .navbar-brand-title {
      font-weight: 700;
      color: #1e293b;
      font-size: 1.25rem;
      letter-spacing: -0.5px;
    }

    /* Hero Banner Header con la Imagen Original de Fondo */
    .portal-hero {
      background: linear-gradient(rgba(15, 23, 42, 0.72), rgba(30, 60, 114, 0.78)), url("{{ asset('img/turnosonline/turnosonline7.jpg') }}") no-repeat center center;
      background-size: cover;
      color: white;
      padding: 55px 0 75px;
      position: relative;
      overflow: hidden;
    }
    .portal-hero::after {
      content: '';
      position: absolute;
      bottom: -30px;
      left: 0;
      right: 0;
      height: 60px;
      background: var(--bg-light);
      border-radius: 50% 50% 0 0 / 100% 100% 0 0;
    }
    .hero-badge {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 30px;
      padding: 6px 20px;
      font-size: 0.9rem;
      font-weight: 600;
      display: inline-block;
      margin-bottom: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* Card Containers & Stepper */
    .box-turno {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      padding: 28px;
      margin-top: -45px;
      position: relative;
      z-index: 10;
    }

    /* Custom Stepper */
    .wizard-steps {
      display: flex;
      justify-content: space-around;
      align-items: center;
      margin-bottom: 30px;
      position: relative;
      padding: 0 10px;
    }
    .wizard-step-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 2;
    }
    .wizard-step-circle {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: #f1f5f9;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.1rem;
      border: 2px solid #cbd5e1;
      transition: all 0.3s ease;
    }
    .wizard-step-item.active .wizard-step-circle {
      background: var(--primary-gradient);
      color: white;
      border-color: #2563eb;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
    }
    .wizard-step-title {
      font-size: 0.9rem;
      font-weight: 600;
      margin-top: 8px;
      color: #64748b;
    }
    .wizard-step-item.active .wizard-step-title {
      color: #1e293b;
      font-weight: 700;
    }

    /* Buttons */
    .btn-gradient-primary {
      background: var(--primary-gradient);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 12px 28px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.25s ease;
    }
    .btn-gradient-primary:hover {
      box-shadow: 0 6px 20px rgba(30, 60, 114, 0.35);
      color: white;
      transform: translateY(-1px);
    }

    /* Datepicker Estilizado y MÁS GRANDE */
    .ui-datepicker {
      font-family: var(--font-main) !important;
      border-radius: 16px !important;
      border: 1px solid #cbd5e1 !important;
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08) !important;
      padding: 18px !important;
      width: 100% !important;
      max-width: 440px !important; /* Calendario más amplio */
      margin: 0 auto;
    }
    .ui-datepicker-header {
      background: var(--primary-gradient) !important;
      color: white !important;
      border: none !important;
      border-radius: 10px !important;
      padding: 12px 8px !important;
      margin-bottom: 14px !important;
    }
    .ui-datepicker-title {
      color: white !important;
      font-weight: 700 !important;
      font-size: 1.25rem !important; /* Título mes/año más grande */
    }
    .ui-datepicker th {
      padding: 10px 4px !important;
      font-size: 1rem !important;
      color: #64748b !important;
      font-weight: 700 !important;
    }
    .ui-datepicker td {
      padding: 5px !important;
    }
    .ui-state-default, .ui-widget-content .ui-state-default {
      border: none !important;
      background: #f1f5f9 !important;
      color: #1e293b !important;
      text-align: center !important;
      border-radius: 10px !important;
      font-weight: 600 !important;
      padding: 12px 8px !important; /* Días del calendario más altos y fáciles de pulsar */
      font-size: 1.1rem !important; /* Números del día más grandes */
      transition: all 0.2s ease !important;
    }
    .ui-state-active, .ui-widget-content .ui-state-active {
      background: #11998e !important;
      color: white !important;
      font-weight: 700 !important;
      box-shadow: 0 4px 14px rgba(17, 153, 142, 0.45) !important;
    }
    .ui-state-hover, .ui-widget-content .ui-state-hover {
      background: #3b82f6 !important;
      color: white !important;
    }
    .ui-datepicker-prev, .ui-datepicker-next {
      top: 12px !important;
      cursor: pointer !important;
    }
    .ui-datepicker-prev span, .ui-datepicker-next span {
      filter: invert(1) brightness(2);
    }
  </style>

  @include('parts.logo')
</head>

<body>
  <!-- Navbar Top -->
  <nav class="custom-navbar">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset('img/logounsa.png') }}" alt="UNSa Logo" style="height: 44px; margin-right: 12px;">
        <div class="d-flex flex-column">
          <span class="navbar-brand-title">{{ config('constants.NOMBRE_SISTEMA', 'Sistema de Turnos') }}</span>
          <small class="text-muted" style="font-size: 0.75rem; margin-top: -3px;">Universidad Nacional de Salta</small>
        </div>
      </a>

      <div class="d-flex align-items-center" style="gap: 10px;">
        @guest
          <a href="{{ route('login') }}" class="btn btn-outline-primary font-weight-bold" style="border-radius: 20px; padding: 6px 18px;">
            <i class="fas fa-sign-in-alt mr-1"></i> Acceso
          </a>
        @else
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle font-weight-bold border" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
              <i class="fas fa-user-circle text-primary"></i> {{ Auth::user()->name }}
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="dropdownMenuButton" style="border-radius: 12px;">
              <li>
                <a class="dropdown-item py-2" href="{{ route('mis-turnos') }}">
                  <i class="fas fa-calendar-alt text-success mr-2"></i> Mis Turnos
                </a>
              </li>
              @hasanyrole('ADMINISTRADOR|OPERADOR')
              <li>
                <a class="dropdown-item py-2" href="{{ url('/home') }}">
                  <i class="fas fa-tachometer-alt text-primary mr-2"></i> Panel de Control
                </a>
              </li>
              @endhasanyrole
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
        @endguest
      </div>
    </div>
  </nav>

  <!-- Hero Header con Imagen Original de Fondo -->
  <header class="portal-hero text-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <span class="hero-badge"><i class="fas fa-graduation-cap mr-1"></i> Portal Oficial de Atención</span>
          <h1 class="font-weight-bold mb-2" style="font-size: 2.3rem; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Reserva tu Turno en la UNSa</h1>
          <p class="lead opacity-90 mb-0" style="font-size: 1.1rem; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">Solicita tu turno de atención de manera rápida, ágil y transparente desde cualquier dispositivo.</p>
        </div>
      </div>
    </div>
  </header>

  <!-- Main View Container -->
  <main class="flex-grow-1" style="margin-bottom: 60px;">
    <div class="container">
      <!-- Validation Errors -->
      @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4" style="border-radius: 12px;">
          <strong class="d-block mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Por favor corrija los siguientes errores:</strong>
          <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Success Alerts -->
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  <!-- Home Banner / Consulta section -->
  @include('frontend.home')

  <!-- Footer -->
  @include('frontend.footer')
  
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/frontend-mix.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  @yield('script')

</body>
</html>
