<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Observatorio Astronómico Dr. Elvio Alanís - Facultad de Ciencias Exactas, Universidad Nacional de Salta (UNSa).">
  <title>Observatorio Astronómico Dr. Elvio Alanís | UNSa</title>

  <link rel="shortcut icon" href="{{ asset('img/logounsa.ico') }}" type="image/x-icon">

  <!-- Google Fonts: Outfit & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <!-- Estilos Base / Bootstrap Grid & Components -->
  <link href="{{ asset('css/frontend-mix.css') }}" rel="stylesheet">

  <style>
    :root {
      --primary-color: #0f172a;
      --secondary-color: #f8fafc;
      --accent-blue: #0284c7;
      --accent-gold: #b45309;
      --text-main: #334155;
      --text-muted: #64748b;
      --border-color: #e2e8f0;
      --font-heading: 'Outfit', sans-serif;
      --font-body: 'Inter', sans-serif;
    }

    body {
      background-color: var(--secondary-color) !important;
      color: var(--text-main) !important;
      font-family: var(--font-body);
      overflow-x: hidden;
      line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: var(--font-heading) !important;
      font-weight: 700 !important;
      color: var(--primary-color) !important;
      letter-spacing: -0.02em;
    }

    /* NAVBAR */
    .sober-nav {
      position: sticky;
      top: 0;
      z-index: 1050;
      background: #ffffff;
      border-bottom: 1px solid var(--border-color);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 12px 0;
    }

    .brand-container {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      text-decoration: none !important;
    }

    .brand-logo-wrap img {
      max-height: 40px;
    }

    .brand-text h1 {
      font-size: 1.15rem;
      margin-bottom: 0;
      color: var(--primary-color) !important;
    }
    .brand-text small {
      color: var(--text-muted);
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .nav-links-wrap {
      display: flex;
      gap: 15px;
    }
    .nav-link-custom {
      color: var(--text-main) !important;
      font-weight: 500;
      font-size: 0.9rem;
      padding: 8px 12px;
      text-decoration: none !important;
      border-radius: 6px;
      transition: all 0.2s;
    }
    .nav-link-custom:hover {
      background: #f1f5f9;
      color: var(--accent-blue) !important;
    }

    .btn-primary-sober {
      background: var(--accent-blue);
      color: #fff !important;
      padding: 8px 20px;
      border-radius: 6px;
      font-weight: 600;
      border: none;
      text-decoration: none !important;
      transition: background 0.3s;
    }
    .btn-primary-sober:hover {
      background: #0369a1;
    }
    
    .btn-outline-sober {
      background: transparent;
      color: var(--primary-color) !important;
      border: 1px solid var(--border-color);
      padding: 8px 18px;
      border-radius: 6px;
      font-weight: 500;
      text-decoration: none !important;
      transition: all 0.3s;
    }
    .btn-outline-sober:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }

    /* FOOTER */
    .sober-footer {
      background: #1e293b;
      padding: 60px 0 30px;
      color: #cbd5e1;
      margin-top: 60px;
    }
    .sober-footer h4, .sober-footer h5 {
      color: #ffffff !important;
    }
    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a { color: #cbd5e1; text-decoration: none; }
    .footer-links a:hover { color: #fff; }
    
    /* UTILS */
    .page-header {
      background: #f1f5f9;
      padding: 60px 0;
      border-bottom: 1px solid var(--border-color);
      margin-bottom: 40px;
    }
    .page-title {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    .section-tag {
      color: var(--accent-blue);
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 1px;
    }
  </style>
  @stack('styles')
</head>
<body>

  <!-- NAVBAR -->
  <nav class="sober-nav">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="{{ url('/') }}" class="brand-container">
        <div class="brand-logo-wrap">
          <img src="{{ asset('img/logounsa.png') }}" alt="UNSa">
        </div>
        <div class="brand-text">
          <h1>Observatorio Alanís</h1>
          <small>Ciencias Exactas · UNSa</small>
        </div>
      </a>

      <div class="d-none d-lg-flex nav-links-wrap">
        <a href="{{ route('landing') }}" class="nav-link-custom">Inicio</a>
        <a href="{{ route('historia') }}" class="nav-link-custom">Historia</a>
        <a href="{{ route('proyectos') }}" class="nav-link-custom">Proyectos</a>
        <a href="{{ route('investigacion') }}" class="nav-link-custom">Investigación</a>
        <a href="{{ route('instalaciones') }}" class="nav-link-custom">Instalaciones</a>
        <a href="{{ route('equipo') }}" class="nav-link-custom">Equipo</a>
      </div>

      <div class="d-flex align-items-center" style="gap: 12px;">
        <a href="{{ route('turnos') }}" class="btn-primary-sober">Reservar Turno</a>
        @guest
          <a href="{{ route('login') }}" class="btn-outline-sober">Mi Portal</a>
        @else
          <a href="{{ route('mis-turnos') }}" class="btn-outline-sober">Mis Turnos</a>
        @endguest
      </div>
    </div>
  </nav>

  <!-- CONTENT -->
  <main>
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="sober-footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <h4>Observatorio Alanís</h4>
          <p class="mt-3">Departamento de Física, Facultad de Ciencias Exactas, Universidad Nacional de Salta.</p>
          <p><i class="fas fa-map-marker-alt"></i> Av. Bolivia 5150, Salta, Argentina.</p>
          <p><i class="fas fa-envelope"></i> observatorioalanis@exa.unsa.edu.ar</p>
        </div>
        <div class="col-lg-4 mb-4">
          <h5>Navegación</h5>
          <ul class="footer-links">
            <li><a href="{{ route('historia') }}">Historia</a></li>
            <li><a href="{{ route('proyectos') }}">Proyectos de Extensión</a></li>
            <li><a href="{{ route('investigacion') }}">Investigación</a></li>
            <li><a href="{{ route('instalaciones') }}">Instalaciones</a></li>
            <li><a href="{{ route('equipo') }}">Equipo</a></li>
          </ul>
        </div>
        <div class="col-lg-4 mb-4">
          <h5>Gestión</h5>
          <ul class="footer-links">
            <li><a href="{{ route('turnos') }}">Solicitar Turno</a></li>
            <li><a href="{{ route('login') }}">Ingreso a Portal</a></li>
          </ul>
        </div>
      </div>
      <hr style="border-color: #334155;">
      <div class="text-center mt-3" style="color: #94a3b8;">
        <small>&copy; {{ date('Y') }} Universidad Nacional de Salta. Todos los derechos reservados.</small>
      </div>
    </div>
  </footer>

  @stack('scripts')
</body>
</html>
