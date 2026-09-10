<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Observatorio Astronómico Dr. Elvio Alanís - Facultad de Ciencias Exactas, Universidad Nacional de Salta (UNSa). Reserva de turnos online para visitas y observaciones astronómicas.">
  <meta name="author" content="Universidad Nacional de Salta - Facultad de Ciencias Exactas">
  <title>Observatorio Astronómico Dr. Elvio Alanís | UNSa</title>

  <!-- Favicon -->
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
    /* ==========================================================================
       VARIABLES Y PALETA DE COLORES CÓSMICA
       ========================================================================== */
    :root {
      --space-bg: #050814;
      --space-bg-alt: #0a0f26;
      --space-card: rgba(14, 22, 52, 0.82);
      --space-card-hover: rgba(22, 36, 78, 0.94);
      --space-border: rgba(99, 130, 241, 0.28);
      --space-border-glow: rgba(56, 189, 248, 0.5);
      --accent-cyan: #38bdf8;
      --accent-purple: #a855f7;
      --accent-indigo: #6366f1;
      --accent-gold: #f59e0b;
      --text-main: #f8fafc;
      --text-muted: #cbd5e1;
      --glow-cyan: 0 0 25px rgba(56, 189, 248, 0.35);
      --font-heading: 'Outfit', sans-serif;
      --font-body: 'Inter', sans-serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    /* Sobreescritura estricta para evitar que Bootstrap ensucie el modo oscuro */
    body {
      background-color: var(--space-bg) !important;
      color: var(--text-main) !important;
      font-family: var(--font-body);
      overflow-x: hidden;
      line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6 {
      font-family: var(--font-heading) !important;
      font-weight: 700 !important;
      color: #ffffff !important;
      letter-spacing: -0.02em;
    }

    p {
      color: #cbd5e1 !important;
    }

    .text-muted, small.text-muted, p.text-muted, span.text-muted {
      color: #cbd5e1 !important;
    }

    .text-secondary {
      color: #cbd5e1 !important;
    }

    /* Fondo con Estrellas Dinámicas Sutiles */
    .stars-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
      background: radial-gradient(circle at 50% 20%, rgba(30, 41, 89, 0.4) 0%, transparent 60%),
                  radial-gradient(circle at 85% 75%, rgba(88, 28, 135, 0.25) 0%, transparent 50%);
    }

    /* ==========================================================================
       BARRA DE NAVEGACIÓN SUPERIOR (NAVBAR)
       ========================================================================== */
    .cosmic-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1050;
      background: rgba(5, 8, 20, 0.88);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(56, 189, 248, 0.22);
      box-shadow: 0 4px 25px rgba(0, 0, 0, 0.5);
      padding: 10px 0;
      transition: all 0.3s ease;
    }

    .brand-container {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      text-decoration: none !important;
      white-space: nowrap !important;
      flex-shrink: 0;
    }

    /* Badge contenedor del Logo UNSa: Circular, elegante, nítido y luminoso */
    .brand-logo-wrap {
      background: #ffffff;
      border: 2px solid rgba(56, 189, 248, 0.6);
      border-radius: 50%;
      padding: 3px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 16px rgba(56, 189, 248, 0.45);
      height: 44px;
      width: 44px;
      flex-shrink: 0;
    }

    .brand-logo-wrap img {
      max-height: 32px;
      max-width: 32px;
      object-fit: contain;
    }

    .brand-text h1 {
      font-size: 1.15rem;
      margin-bottom: 0;
      line-height: 1.2;
      color: #ffffff !important;
      font-weight: 700;
      white-space: nowrap !important;
    }

    .brand-text small {
      color: var(--accent-cyan) !important;
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      white-space: nowrap !important;
      display: block;
    }

    .nav-links-wrap {
      display: flex;
      align-items: center;
      gap: 4px;
      margin: 0 15px;
    }

    .nav-link-custom {
      color: #cbd5e1 !important;
      font-size: 0.88rem !important;
      font-weight: 500;
      padding: 7px 13px !important;
      transition: all 0.25s ease;
      text-decoration: none !important;
      border-radius: 30px;
      white-space: nowrap !important;
      display: inline-block;
    }

    .nav-link-custom:hover {
      color: #ffffff !important;
      background: rgba(56, 189, 248, 0.15) !important;
      box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
    }

    .btn-cosmic-primary {
      background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-indigo) 100%);
      color: #ffffff !important;
      font-weight: 600;
      font-size: 0.88rem;
      padding: 9px 20px;
      border-radius: 30px;
      border: none;
      box-shadow: 0 4px 16px rgba(56, 189, 248, 0.4);
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none !important;
      cursor: pointer;
      white-space: nowrap !important;
      flex-shrink: 0;
    }

    .btn-cosmic-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 22px rgba(56, 189, 248, 0.65);
      color: #ffffff !important;
    }

    .btn-cosmic-outline {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid var(--space-border-glow);
      color: #ffffff !important;
      font-weight: 500;
      font-size: 0.88rem;
      padding: 8px 18px;
      border-radius: 30px;
      backdrop-filter: blur(8px);
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none !important;
      cursor: pointer;
      white-space: nowrap !important;
      flex-shrink: 0;
    }

    .btn-cosmic-outline:hover {
      background: rgba(56, 189, 248, 0.16);
      border-color: var(--accent-cyan);
      transform: translateY(-2px);
      color: #ffffff !important;
    }

    @media (max-width: 1200px) {
      .nav-link-custom {
        font-size: 0.82rem !important;
        padding: 6px 9px !important;
      }
      .brand-text h1 {
        font-size: 1.05rem !important;
      }
      .btn-cosmic-primary, .btn-cosmic-outline {
        padding: 7px 15px !important;
        font-size: 0.84rem !important;
      }
    }

    /* Dropdown personalizado de usuario */
    .user-dropdown-container {
      position: relative;
    }

    .user-dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      margin-top: 10px;
      min-width: 250px;
      background: rgba(10, 16, 38, 0.96);
      border: 1px solid var(--space-border-glow);
      backdrop-filter: blur(16px);
      border-radius: 16px;
      padding: 10px 0;
      box-shadow: 0 14px 40px rgba(0, 0, 0, 0.7);
      z-index: 1060;
    }

    .user-dropdown-menu.show {
      display: block;
      animation: fadeInDropdown 0.25s ease-out;
    }

    @keyframes fadeInDropdown {
      from { opacity: 0; transform: translateY(-8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .user-dropdown-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 20px;
      color: #cbd5e1 !important;
      text-decoration: none !important;
      font-size: 0.92rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .user-dropdown-item:hover {
      background: rgba(56, 189, 248, 0.14);
      color: #ffffff !important;
    }

    .user-dropdown-item i {
      width: 18px;
      text-align: center;
    }

    /* ==========================================================================
       HERO SECTION
       ========================================================================== */
    .hero-section {
      position: relative;
      min-height: 94vh;
      padding-top: 140px;
      padding-bottom: 120px;
      display: flex;
      align-items: center;
      background: linear-gradient(180deg, rgba(5, 8, 20, 0.55) 0%, rgba(5, 8, 20, 0.92) 85%, var(--space-bg) 100%),
                  url("{{ asset('img/observatorio/hero-bg.jpg') }}") no-repeat center center;
      background-size: cover;
      background-attachment: scroll;
      z-index: 1;
    }

    .hero-badge-live {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(15, 23, 42, 0.85);
      border: 1px solid rgba(56, 189, 248, 0.5);
      padding: 7px 20px;
      border-radius: 50px;
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--accent-cyan);
      margin-bottom: 24px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 15px rgba(56, 189, 248, 0.25);
    }

    .pulsing-dot {
      width: 9px;
      height: 9px;
      background-color: #10b981;
      border-radius: 50%;
      box-shadow: 0 0 8px #10b981;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
      70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .hero-title {
      font-size: 3.3rem;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 20px;
      background: linear-gradient(135deg, #ffffff 45%, var(--accent-cyan) 100%) !important;
      -webkit-background-clip: text !important;
      -webkit-text-fill-color: transparent !important;
    }

    .hero-lead {
      font-size: 1.22rem;
      color: #e2e8f0 !important;
      margin-bottom: 34px;
      max-width: 680px;
      font-weight: 400;
      line-height: 1.65;
    }

    .hero-lead strong {
      color: #ffffff !important;
    }

    .hero-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 36px;
      margin-bottom: 10px;
    }

    .hero-pill-item {
      background: rgba(13, 21, 48, 0.82);
      border: 1px solid rgba(56, 189, 248, 0.3);
      border-radius: 30px;
      padding: 8px 18px;
      font-size: 0.88rem;
      color: #ffffff !important;
      backdrop-filter: blur(8px);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
    }

    .hero-pill-item i {
      color: var(--accent-cyan);
    }

    /* ==========================================================================
       CARDS DE EXPERIENCIAS Y ACTIVIDADES
       ========================================================================== */
    .section-padding {
      padding: 95px 0;
      position: relative;
      z-index: 2;
    }

    .section-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-tag {
      color: var(--accent-cyan) !important;
      font-size: 0.88rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.14em;
      margin-bottom: 10px;
      display: inline-block;
      text-shadow: 0 0 12px rgba(56, 189, 248, 0.4);
    }

    .section-title {
      font-size: 2.35rem;
      margin-bottom: 14px;
      background: linear-gradient(135deg, #ffffff 50%, var(--accent-cyan) 100%) !important;
      -webkit-background-clip: text !important;
      -webkit-text-fill-color: transparent !important;
      display: inline-block;
    }

    .section-subtitle {
      color: #cbd5e1 !important;
      font-size: 1.1rem !important;
      max-width: 680px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .activity-card {
      background: rgba(13, 21, 48, 0.85);
      border: 1px solid var(--space-border);
      border-radius: 20px;
      overflow: hidden;
      transition: all 0.35s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      backdrop-filter: blur(12px);
    }

    .activity-card:hover {
      transform: translateY(-8px);
      border-color: var(--space-border-glow);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45), var(--glow-cyan);
    }

    .activity-img-wrap {
      position: relative;
      height: 220px;
      overflow: hidden;
    }

    .activity-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.6s ease;
    }

    .activity-card:hover .activity-img {
      transform: scale(1.08);
    }

    .activity-badge {
      position: absolute;
      top: 14px;
      right: 14px;
      background: rgba(5, 8, 20, 0.88);
      border: 1px solid var(--space-border);
      color: #ffffff !important;
      padding: 5px 14px;
      border-radius: 30px;
      font-size: 0.78rem;
      font-weight: 600;
      backdrop-filter: blur(6px);
    }

    .activity-body {
      padding: 24px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .activity-body h3 {
      font-size: 1.35rem;
      margin-bottom: 12px;
      color: #ffffff !important;
    }

    .activity-body p {
      color: #cbd5e1 !important;
      font-size: 0.95rem !important;
      margin-bottom: 20px;
      flex-grow: 1;
      line-height: 1.6;
    }

    /* ==========================================================================
       SECCIÓN PASO A PASO (CÓMO FUNCIONA EL SISTEMA DE TURNOS)
       ========================================================================== */
    .steps-section {
      background: linear-gradient(180deg, var(--space-bg) 0%, var(--space-bg-alt) 50%, var(--space-bg) 100%);
      position: relative;
      z-index: 2;
    }

    .step-card {
      background: rgba(13, 21, 48, 0.75);
      border: 1px solid var(--space-border);
      border-radius: 18px;
      padding: 32px 24px;
      text-align: center;
      position: relative;
      height: 100%;
      transition: all 0.3s ease;
    }

    .step-card:hover {
      border-color: var(--accent-cyan);
      background: rgba(16, 26, 62, 0.9);
      transform: translateY(-4px);
    }

    .step-number {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(56, 189, 248, 0.18) 0%, rgba(99, 102, 241, 0.28) 100%);
      border: 2px solid var(--accent-cyan);
      color: var(--accent-cyan);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      font-weight: 800;
      margin: 0 auto 20px;
      box-shadow: 0 0 18px rgba(56, 189, 248, 0.3);
    }

    .step-card h4 {
      font-size: 1.2rem;
      margin-bottom: 12px;
      color: #ffffff !important;
    }

    .step-card p {
      color: #cbd5e1 !important;
      font-size: 0.95rem;
      line-height: 1.55;
      margin-bottom: 0;
    }

    /* ==========================================================================
       SECCIÓN CONSULTA RÁPIDA DE TURNO
       ========================================================================== */
    .quick-search-box {
      background: linear-gradient(135deg, rgba(17, 24, 56, 0.92) 0%, rgba(26, 16, 61, 0.92) 100%);
      border: 1px solid var(--space-border-glow);
      border-radius: 24px;
      padding: 40px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
      position: relative;
      overflow: hidden;
    }

    .quick-search-box::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(56, 189, 248, 0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .search-input-group {
      background: rgba(5, 8, 20, 0.9);
      border: 1px solid var(--space-border);
      border-radius: 50px;
      padding: 6px 8px 6px 20px;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .search-input-group:focus-within {
      border-color: var(--accent-cyan);
      box-shadow: 0 0 18px rgba(56, 189, 248, 0.35);
    }

    .search-input-group input {
      background: transparent;
      border: none;
      color: #ffffff !important;
      outline: none;
      width: 100%;
      font-size: 1rem;
    }

    .search-input-group input::placeholder {
      color: #94a3b8 !important;
    }

    /* ==========================================================================
       SECCIÓN PORTAL DEL VISITANTE / AUTOGESTIÓN
       ========================================================================== */
    .portal-section {
      background: linear-gradient(180deg, var(--space-bg) 0%, rgba(15, 23, 50, 0.65) 50%, var(--space-bg) 100%);
      position: relative;
      z-index: 2;
    }

    .portal-feature-card {
      background: rgba(14, 22, 52, 0.85);
      border: 1px solid var(--space-border);
      border-radius: 20px;
      padding: 28px 24px;
      transition: all 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      backdrop-filter: blur(12px);
    }

    .portal-feature-card:hover {
      border-color: var(--accent-cyan);
      transform: translateY(-6px);
      box-shadow: 0 14px 35px rgba(0,0,0,0.5), 0 0 20px rgba(56, 189, 248, 0.3);
    }

    .portal-feature-card h4 {
      color: #ffffff !important;
      font-size: 1.25rem !important;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .portal-feature-card p {
      color: #cbd5e1 !important;
      font-size: 0.95rem !important;
      line-height: 1.55;
    }

    .portal-icon-wrap {
      width: 54px;
      height: 54px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 20px;
    }

    .badge-proximamente {
      background: rgba(245, 158, 11, 0.22) !important;
      color: #fcd34d !important;
      border: 1px solid rgba(245, 158, 11, 0.6) !important;
      font-weight: 700 !important;
      padding: 5px 12px !important;
      border-radius: 20px !important;
      font-size: 0.72rem !important;
      letter-spacing: 0.05em;
      display: inline-block;
      margin-bottom: 12px;
      align-self: flex-start;
    }

    .badge-desarrollo {
      background: rgba(16, 185, 129, 0.22) !important;
      color: #6ee7b7 !important;
      border: 1px solid rgba(16, 185, 129, 0.6) !important;
      font-weight: 700 !important;
      padding: 5px 12px !important;
      border-radius: 20px !important;
      font-size: 0.72rem !important;
      letter-spacing: 0.05em;
      display: inline-block;
      margin-bottom: 12px;
      align-self: flex-start;
    }

    .portal-cta-banner {
      background: linear-gradient(135deg, rgba(30, 58, 138, 0.5) 0%, rgba(88, 28, 135, 0.4) 100%);
      border: 1px solid rgba(56, 189, 248, 0.4);
      border-radius: 22px;
      padding: 34px;
      margin-top: 45px;
      backdrop-filter: blur(10px);
    }

    /* ==========================================================================
       PREGUNTAS FRECUENTES (FAQ ACORDEÓN)
       ========================================================================== */
    .faq-item {
      background: var(--space-card);
      border: 1px solid var(--space-border);
      border-radius: 14px;
      margin-bottom: 14px;
      overflow: hidden;
      transition: all 0.25s ease;
    }

    .faq-item:hover {
      border-color: rgba(99, 130, 241, 0.5);
    }

    .faq-question {
      padding: 20px 24px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-family: var(--font-heading);
      font-weight: 600;
      font-size: 1.05rem;
      color: #ffffff !important;
      user-select: none;
    }

    .faq-icon {
      color: var(--accent-cyan);
      transition: transform 0.3s ease;
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease, padding 0.35s ease;
      padding: 0 24px;
      color: #cbd5e1 !important;
      font-size: 0.95rem;
      line-height: 1.6;
      border-top: 1px solid transparent;
    }

    .faq-item.active {
      border-color: var(--accent-cyan);
      background: rgba(16, 26, 62, 0.95);
    }

    .faq-item.active .faq-icon {
      transform: rotate(180deg);
    }

    .faq-item.active .faq-answer {
      max-height: 350px;
      padding: 0 24px 20px;
      border-top-color: rgba(255, 255, 255, 0.08);
    }

    /* ==========================================================================
       UBICACIÓN Y MAPA
       ========================================================================== */
    .location-box {
      background: var(--space-card);
      border: 1px solid var(--space-border);
      border-radius: 20px;
      padding: 34px;
      height: 100%;
    }

    .location-box h3, .location-box h4 {
      color: #ffffff !important;
    }

    .info-line {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      margin-bottom: 22px;
    }

    .info-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: rgba(56, 189, 248, 0.15);
      color: var(--accent-cyan);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.15rem;
      flex-shrink: 0;
    }

    /* ==========================================================================
       FOOTER
       ========================================================================== */
    .cosmic-footer {
      background: #040611 !important;
      border-top: 1px solid rgba(99, 130, 241, 0.25);
      padding: 65px 0 35px;
      position: relative;
      z-index: 2;
    }

    .cosmic-footer p {
      color: #cbd5e1 !important;
      line-height: 1.6;
    }

    .footer-links {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-links li {
      margin-bottom: 10px;
    }

    .footer-links a {
      color: #cbd5e1 !important;
      text-decoration: none;
      font-size: 0.92rem;
      transition: color 0.2s ease;
    }

    .footer-links a:hover {
      color: var(--accent-cyan) !important;
      text-decoration: underline;
    }

    .footer-bottom-text {
      color: #94a3b8 !important;
      font-size: 0.88rem;
    }

    /* Responsive */
    @media (max-width: 991px) {
      .hero-title {
        font-size: 2.5rem;
      }
      .hero-section {
        padding-top: 120px;
        min-height: auto;
      }
    }

    @media (max-width: 767px) {
      .hero-title {
        font-size: 2.1rem;
      }
      .hero-lead {
        font-size: 1.05rem;
      }
      .brand-text small {
        display: none;
      }
    }
  </style>
</head>

<body>
  <!-- Fondo estrellado cósmico -->
  <div class="stars-canvas"></div>

  <!-- ==========================================================================
       BARRA DE NAVEGACIÓN
       ========================================================================== -->
  <nav class="cosmic-nav">
    <div class="container-fluid px-xl-5 px-lg-4 px-3 d-flex justify-content-between align-items-center" style="max-width: 1540px;">
      <!-- Marca y Logos -->
      <a href="{{ url('/') }}" class="brand-container">
        <!-- 
          NOTA DE IMAGEN: Logo de la Universidad Nacional de Salta
          Ruta del archivo: public/img/logounsa.png
          Envuelto en un badge circular blanco luminoso para nitidez total
        -->
        <div class="brand-logo-wrap">
          <img src="{{ asset('img/logounsa.png') }}" alt="Logo UNSa">
        </div>
        <div class="brand-text">
          <h1>Observatorio Alanís</h1>
          <small>Facultad de Ciencias Exactas · UNSa</small>
        </div>
      </a>

      <!-- Menú de Enlaces Rápidos (Desktop) -->
      <div class="d-none d-lg-flex nav-links-wrap">
        <a href="#experiencias" class="nav-link-custom">Experiencias</a>
        <a href="#como-funciona" class="nav-link-custom">Cómo Funciona</a>
        <a href="#consulta" class="nav-link-custom">Consultar Turno</a>
        <a href="#faq" class="nav-link-custom">Preguntas Frecuentes</a>
        <a href="#ubicacion" class="nav-link-custom">Ubicación</a>
      </div>

      <!-- Acciones Principales -->
      <div class="d-flex align-items-center" style="gap: 12px; flex-shrink: 0;">
        <!-- Botón para ir al formulario de turnos -->
        <a href="{{ route('turnos') }}" class="btn-cosmic-primary">
          <i class="fas fa-calendar-check"></i>
          <span>Reservar Turno</span>
        </a>

        <!-- Acceso Unificado al Portal de Usuarios / Operadores -->
        @guest
          <a href="{{ route('login') }}" class="btn-cosmic-outline" title="Ingreso para visitantes (ver turnos anteriores y próximos) o personal del Observatorio">
            <i class="fas fa-user-circle"></i>
            <span>Mi Portal</span>
          </a>
        @else
          <!-- Dropdown para usuario autenticado (visitante u operador) -->
          <div class="user-dropdown-container">
            <button class="btn-cosmic-outline" id="userDropdownBtn" onclick="toggleUserDropdown()" style="border-color: var(--accent-cyan); gap: 8px;">
              <i class="fas fa-user-astronaut text-info"></i>
              <span>{{ Auth::user()->name }}</span>
              <i class="fas fa-chevron-down ml-1" style="font-size: 0.75rem;"></i>
            </button>

            <div class="user-dropdown-menu" id="userDropdownMenu">
              <div class="px-3 py-2 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                <small class="text-muted d-block" style="font-size: 0.75rem; color: #94a3b8 !important;">Conectado como</small>
                <strong class="text-white d-block text-truncate" style="font-size: 0.88rem;">{{ Auth::user()->email }}</strong>
              </div>

              <!-- Acceso a Mis Turnos (para cualquier usuario) -->
              <a class="user-dropdown-item" href="{{ route('mis-turnos') }}">
                <i class="fas fa-calendar-check" style="color: var(--accent-cyan);"></i>
                <span>Mis Turnos y Reservas</span>
              </a>

              <!-- Acceso al Panel de Control (solo para Operadores/Administradores) -->
              @if(Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('OPERADOR'))
                <a class="user-dropdown-item" href="{{ url('/home') }}">
                  <i class="fas fa-tachometer-alt text-warning"></i>
                  <span>Panel de Operador</span>
                </a>
              @endif

              <a class="user-dropdown-item" href="{{ url('usuarios.mi_perfil') }}">
                <i class="fas fa-id-badge text-primary"></i>
                <span>Mi Perfil</span>
              </a>

              <div style="border-top: 1px solid rgba(255,255,255,0.08); margin: 6px 0;"></div>

              <a class="user-dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
              </a>

              <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </div>
        @endguest
      </div>
    </div>
  </nav>

  <!-- ==========================================================================
       HERO SECTION
       ==========================================================================
       NOTA DE IMAGEN PRINCIPAL (HERO):
       Ruta: public/img/observatorio/hero-bg.jpg
       Resolución recomendada: 1920x1080px (16:9), formato JPG o WebP optimizado.
       Para reemplazar: colocar la nueva foto en dicha ruta o cambiar la URL en el bloque CSS .hero-section
  -->
  <header class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 col-md-10">
          <!-- Badge de Estado Activo -->
          <div class="hero-badge-live">
            <span class="pulsing-dot"></span>
            <span>Sistema de Turnos y Visitas Habilitado</span>
          </div>

          <!-- Título Principal -->
          <h2 class="hero-title">
            Explorá los Secretos del Universo en Salta
          </h2>

          <!-- Subtítulo -->
          <p class="hero-lead">
            El <strong>Observatorio Astronómico Dr. Elvio Alanís</strong> de la <strong>Facultad de Ciencias Exactas (UNSa)</strong> abre sus puertas para vivir una experiencia celestial única. Gestioná tu turno online para observaciones guiadas, visitas de colegios y actividades de divulgación científica.
          </p>

          <!-- Botones de Llamada a la Acción (CTA) -->
          <div class="d-flex flex-wrap align-items-center" style="gap: 16px;">
            <a href="{{ route('turnos') }}" class="btn-cosmic-primary" style="font-size: 1.05rem; padding: 14px 32px;">
              <i class="fas fa-telescope"></i>
              <span>Solicitar Mi Turno Online</span>
            </a>

            <a href="#portal-usuario" class="btn-cosmic-outline" style="font-size: 1.05rem; padding: 13px 26px;">
              <i class="fas fa-user-astronaut"></i>
              <span>Mi Portal de Usuario</span>
            </a>
          </div>

          <!-- Píldoras de Información Destacada -->
          <div class="hero-pills">
            <div class="hero-pill-item">
              <i class="fas fa-moon"></i>
              <span>Observaciones Nocturnas</span>
            </div>
            <div class="hero-pill-item">
              <i class="fas fa-school"></i>
              <span>Delegaciones Escolares</span>
            </div>
            <div class="hero-pill-item">
              <i class="fas fa-university"></i>
              <span>Actividad Gratuita UNSa</span>
            </div>
            <div class="hero-pill-item">
              <i class="fas fa-map-marker-alt"></i>
              <span>Campus Castañares</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- ==========================================================================
       SECCIÓN: EXPERIENCIAS Y MODALIDADES DE VISITA
       ========================================================================== -->
  <section id="experiencias" class="section-padding">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Nuestras Actividades</span>
        <h2 class="section-title">Experiencias en el Observatorio</h2>
        <p class="section-subtitle">
          Acercamos el conocimiento astronómico y la observación directa del cielo a toda la comunidad salteña a través de diferentes propuestas adaptadas a cada público.
        </p>
      </div>

      <div class="row">
        <!-- Tarjeta 1: Observaciones Nocturnas -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="activity-card">
            <!-- 
              NOTA DE IMAGEN: Observaciones Nocturnas
              Ruta: public/img/observatorio/observacion-nocturna.jpg
              Resolución sugerida: 800x600px (4:3)
            -->
            <div class="activity-img-wrap">
              <img src="{{ asset('img/observatorio/observacion-nocturna.jpg') }}" alt="Observación Nocturna con Telescopio" class="activity-img">
              <span class="activity-badge"><i class="fas fa-star mr-1"></i> Público General</span>
            </div>
            <div class="activity-body">
              <h3>Observaciones Nocturnas</h3>
              <p>
                Contemplación guiada de la Luna, los cráteres lunares, los anillos de Saturno, Júpiter y cúmulos estelares. Guiados por docentes y especialistas en astronomía.
              </p>
              <a href="{{ route('turnos') }}" class="btn-cosmic-outline text-center w-100">
                <span>Reservar Fecha</span>
                <i class="fas fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Tarjeta 2: Visitas Escolares e Institucionales -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="activity-card">
            <!-- 
              NOTA DE IMAGEN: Visitas Escolares
              Ruta: public/img/observatorio/visitas-escolares.jpg
              Resolución sugerida: 800x600px (4:3)
            -->
            <div class="activity-img-wrap">
              <img src="{{ asset('img/observatorio/visitas-escolares.jpg') }}" alt="Visitas Escolares e Institucionales" class="activity-img">
              <span class="activity-badge"><i class="fas fa-graduation-cap mr-1"></i> Institucional</span>
            </div>
            <div class="activity-body">
              <h3>Visitas de Escuelas y Colegios</h3>
              <p>
                Turnos especiales para contingentes educativos de nivel primario, secundario y terciario. Recorrido pedagógico con actividades explicativas adaptadas a las edades de los estudiantes.
              </p>
              <a href="{{ route('turnos') }}" class="btn-cosmic-outline text-center w-100">
                <span>Turnos para Instituciones</span>
                <i class="fas fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Tarjeta 3: Divulgación Científica y Charlas -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="activity-card">
            <!-- 
              NOTA DE IMAGEN: Divulgación Científica
              Ruta: public/img/observatorio/divulgacion.jpg
              Resolución sugerida: 800x600px (4:3)
            -->
            <div class="activity-img-wrap">
              <img src="{{ asset('img/observatorio/divulgacion.jpg') }}" alt="Charlas de Divulgación Científica" class="activity-img">
              <span class="activity-badge"><i class="fas fa-atom mr-1"></i> Extensión UNSa</span>
            </div>
            <div class="activity-body">
              <h3>Divulgación y Charlas</h3>
              <p>
                Encuentros abiertos, conferencias sobre astrofísica, fenómenos celestes extraordinarios (eclipses, cometas) y proyectos de extensión universitaria abiertos a la sociedad.
              </p>
              <a href="{{ route('turnos') }}" class="btn-cosmic-outline text-center w-100">
                <span>Ver Disponibilidad</span>
                <i class="fas fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECCIÓN: CÓMO FUNCIONA EL SISTEMA DE TURNOS (3 PASOS)
       ========================================================================== -->
  <section id="como-funciona" class="section-padding steps-section">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Proceso Sencillo y Rápido</span>
        <h2 class="section-title">¿Cómo Solicitar tu Turno?</h2>
        <p class="section-subtitle">
          El sistema organiza la capacidad de atención y las jornadas de observación para garantizar una experiencia cómoda y segura para todos los visitantes.
        </p>
      </div>

      <div class="row">
        <!-- Paso 1 -->
        <div class="col-md-4 mb-4">
          <div class="step-card">
            <div class="step-number">1</div>
            <h4>Elegí la Modalidad</h4>
            <p>
              Seleccioná el trámite o tipo de actividad que querés realizar (observación nocturna, visita grupal escolar o actividad especial).
            </p>
          </div>
        </div>

        <!-- Paso 2 -->
        <div class="col-md-4 mb-4">
          <div class="step-card">
            <div class="step-number">2</div>
            <h4>Seleccioná Fecha y Hora</h4>
            <p>
              El calendario te muestra los días y horarios con cupos disponibles en tiempo real según el cronograma operativo del Observatorio.
            </p>
          </div>
        </div>

        <!-- Paso 3 -->
        <div class="col-md-4 mb-4">
          <div class="step-card">
            <div class="step-number">3</div>
            <h4>Descargá tu Comprobante</h4>
            <p>
              Completá los datos requeridos y obtené tu confirmación digital con código de reserva en formato PDF para ingresar el día pactado.
            </p>
          </div>
        </div>
      </div>

      <!-- CTA de Inicio -->
      <div class="text-center mt-4">
        <a href="{{ route('turnos') }}" class="btn-cosmic-primary" style="font-size: 1.1rem; padding: 14px 38px;">
          <i class="fas fa-arrow-right"></i>
          <span>Comenzar Reserva de Turno Ahora</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECCIÓN: CONSULTA RÁPIDA DE TURNO
       ========================================================================== -->
  <section id="consulta" class="section-padding">
    <div class="container">
      <div class="quick-search-box">
        <div class="row align-items-center">
          <div class="col-lg-6 mb-4 mb-lg-0">
            <span class="section-tag" style="color: var(--accent-cyan);"><i class="fas fa-ticket-alt mr-1"></i> Estado de Reserva</span>
            <h3 style="font-size: 1.95rem; margin-bottom: 12px; color: #ffffff !important;">¿Ya tenés un turno reservado?</h3>
            <p style="color: #cbd5e1 !important; margin-bottom: 0;">
              Ingresá tu número de DNI o código de reserva para verificar la fecha, horario y descargar nuevamente tu comprobante si lo necesitás.
            </p>
          </div>

          <div class="col-lg-6">
            <!-- Formulario hacia el buscador de turnos -->
            <form action="{{ route('turno.buscar') }}" method="GET">
              <div class="search-input-group">
                <i class="fas fa-id-card text-muted mr-3" style="color: #94a3b8 !important;"></i>
                <input type="text" name="dni" placeholder="Ingresá tu DNI sin puntos..." required>
                <button type="submit" class="btn-cosmic-primary" style="padding: 10px 24px; border-radius: 40px; margin-left: 8px;">
                  <i class="fas fa-search"></i>
                  <span>Buscar</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECCIÓN: PORTAL DE AUTOGESTIÓN DEL VISITANTE & COMUNIDAD
       ========================================================================== -->
  <section id="portal-usuario" class="section-padding portal-section">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Tu Cuenta en el Observatorio</span>
        <h2 class="section-title">Portal del Visitante y Comunidad</h2>
        <p class="section-subtitle">
          Iniciá sesión o registrate para tener el control total de tus visitas, consultar turnos anteriores y próximos, y acceder a las nuevas funciones de la plataforma.
        </p>
      </div>

      <div class="row">
        <!-- Tarjeta 1: Historial y Próximos Turnos -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="portal-feature-card">
            <div class="portal-icon-wrap" style="background: rgba(56, 189, 248, 0.18); color: var(--accent-cyan);">
              <i class="fas fa-history"></i>
            </div>
            <h4>Turnos Anteriores y Próximos</h4>
            <p class="mb-0">
              Visualizá en tiempo real el historial de todas tus visitas, las fechas confirmadas a futuro y el estado de atención en un único panel personal.
            </p>
          </div>
        </div>

        <!-- Tarjeta 2: Comprobantes y Nóminas -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="portal-feature-card">
            <div class="portal-icon-wrap" style="background: rgba(168, 85, 247, 0.18); color: var(--accent-purple);">
              <i class="fas fa-file-pdf"></i>
            </div>
            <h4>Comprobantes y Nóminas</h4>
            <p class="mb-0">
              Re-descargá tus comprobantes PDF oficiales cuando lo necesites y cargá o modificá la lista de integrantes si reservaste para una escuela o grupo.
            </p>
          </div>
        </div>

        <!-- Tarjeta 3: Opiniones y Comentarios (Próximamente) -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="portal-feature-card">
            <div class="portal-icon-wrap" style="background: rgba(245, 158, 11, 0.18); color: var(--accent-gold);">
              <i class="fas fa-comments"></i>
            </div>
            <h4>Comentarios y Reseñas</h4>
            <span class="badge-proximamente">PRÓXIMAMENTE</span>
            <p class="mb-0">
              Espacio para compartir tus fotos, testimonios y opiniones sobre la experiencia en la cúpula, enriqueciendo la divulgación comunitaria.
            </p>
          </div>
        </div>

        <!-- Tarjeta 4: Pagos y Facturación (En Desarrollo) -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="portal-feature-card">
            <div class="portal-icon-wrap" style="background: rgba(16, 185, 129, 0.18); color: #10b981;">
              <i class="fas fa-receipt"></i>
            </div>
            <h4>Gestión de Facturación</h4>
            <span class="badge-desarrollo">EN DESARROLLO</span>
            <p class="mb-0">
              Módulo preparado para emisión de comprobantes arancelarios y facturación electrónica para delegaciones o visitas especiales aranceladas.
            </p>
          </div>
        </div>
      </div>

      <!-- Banner CTA para Acceder al Portal -->
      <div class="portal-cta-banner">
        <div class="row align-items-center">
          <div class="col-lg-8 mb-3 mb-lg-0 text-center text-lg-left">
            <h3 style="font-size: 1.75rem; margin-bottom: 8px; color: #ffffff !important;">Accedé a tu Portal de Usuario</h3>
            <p style="color: #cbd5e1 !important; margin-bottom: 0;">
              Podés ingresar con tu correo registrado o conectar directamente con tu cuenta de <strong>Google</strong> con un solo clic.
            </p>
          </div>

          <div class="col-lg-4 text-center text-lg-right">
            @guest
              <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-end" style="gap: 10px;">
                <a href="{{ route('login') }}" class="btn-cosmic-primary">
                  <i class="fas fa-sign-in-alt"></i>
                  <span>Ingresar a Mi Portal</span>
                </a>
                <a href="{{ route('google.login') }}" class="btn-cosmic-outline" title="Iniciar sesión con Google">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 16px; height: 16px;">
                  <span>Google</span>
                </a>
              </div>
            @else
              <a href="{{ route('mis-turnos') }}" class="btn-cosmic-primary" style="padding: 12px 28px;">
                <i class="fas fa-calendar-check"></i>
                <span>Ver Mis Turnos y Reservas</span>
              </a>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECCIÓN: PREGUNTAS FRECUENTES (FAQ)
       ========================================================================== -->
  <section id="faq" class="section-padding">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Dudas Habituales</span>
        <h2 class="section-title">Preguntas Frecuentes</h2>
        <p class="section-subtitle">
          Respuestas a las consultas más comunes antes de visitar el Observatorio Astronómico Alanís.
        </p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-9">
          <!-- Item 1 -->
          <div class="faq-item">
            <div class="faq-question">
              <span>¿La visita al Observatorio Alanís tiene algún costo?</span>
              <i class="fas fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              No. El Observatorio Astronómico Dr. Elvio Alanís depende de la Facultad de Ciencias Exactas de la UNSa y todas sus actividades de extensión y divulgación científica son de carácter público y gratuito para la comunidad.
            </div>
          </div>

          <!-- Item 2 -->
          <div class="faq-item">
            <div class="faq-question">
              <span>¿Qué ocurre si la noche del turno está nublada o llueve?</span>
              <i class="fas fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              La observación con telescopios depende 100% de las condiciones meteorológicas y la visibilidad del cielo. En caso de cielo completamente cubierto o precipitaciones, la observación telescópica puede suspenderse o ser reemplazada por una charla explicativa en sala. Se recomienda estar atento a las comunicaciones del Observatorio.
            </div>
          </div>

          <!-- Item 3 -->
          <div class="faq-item">
            <div class="faq-question">
              <span>¿Cómo deben solicitar turno las escuelas y colegios?</span>
              <i class="fas fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              Los docentes o directivos deben ingresar al sistema y elegir la opción de Visitas Escolares/Institucionales. Allí podrán consignar el nombre de la institución, la cantidad estimada de alumnos y coordinar el cupo adecuado para la delegación.
            </div>
          </div>

          <!-- Item 4 -->
          <div class="faq-item">
            <div class="faq-question">
              <span>¿Qué debo presentar al momento de ingresar?</span>
              <i class="fas fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              Es necesario exhibir el comprobante de turno emitido por este sistema (puede ser presentado directamente en la pantalla de tu teléfono celular o impreso en papel) y el DNI de la persona que figura como titular de la reserva.
            </div>
          </div>

          <!-- Item 5 -->
          <div class="faq-item">
            <div class="faq-question">
              <span>¿Pueden asistir niños pequeños?</span>
              <i class="fas fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              ¡Sí! Las actividades están pensadas para toda la familia. Los menores de edad deben asistir acompañados en todo momento por un adulto responsable.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECCIÓN: UBICACIÓN Y CONTACTO
       ========================================================================== -->
  <section id="ubicacion" class="section-padding" style="background: rgba(10, 15, 38, 0.5);">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Cómo Llegar</span>
        <h2 class="section-title">Ubicación del Observatorio</h2>
        <p class="section-subtitle">
          El observatorio está emplazado en el campus universitario de la Universidad Nacional de Salta.
        </p>
      </div>

      <div class="row align-items-stretch">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="location-box">
            <h3 style="font-size: 1.5rem; margin-bottom: 24px;">Información de Acceso</h3>

            <div class="info-line">
              <div class="info-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div>
                <strong style="color: var(--accent-cyan) !important; display: block; font-size: 1.05rem;">Dirección:</strong>
                <p class="mb-0" style="color: #cbd5e1 !important;">
                  Complejo Universitario General José de San Martín<br>
                  Av. Bolivia 5150, Salta Capital (CP 4400).
                </p>
              </div>
            </div>

            <div class="info-line">
              <div class="info-icon">
                <i class="fas fa-university"></i>
              </div>
              <div>
                <strong style="color: var(--accent-cyan) !important; display: block; font-size: 1.05rem;">Institución:</strong>
                <p class="mb-0" style="color: #cbd5e1 !important;">
                  Facultad de Ciencias Exactas - Departamento de Física.<br>
                  Universidad Nacional de Salta (UNSa).
                </p>
              </div>
            </div>

            <div class="info-line">
              <div class="info-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div>
                <strong style="color: var(--accent-cyan) !important; display: block; font-size: 1.05rem;">Horarios de Visitas:</strong>
                <p class="mb-0" style="color: #cbd5e1 !important;">
                  Según las fechas habilitadas en el sistema de turnos (consultar disponibilidad al reservar).
                </p>
              </div>
            </div>

            <div class="mt-4 pt-2">
              <a href="https://maps.google.com/?q=Universidad+Nacional+de+Salta" target="_blank" rel="noopener noreferrer" class="btn-cosmic-outline w-100 justify-content-center">
                <i class="fas fa-directions"></i>
                <span>Ver en Google Maps</span>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="location-box d-flex flex-column justify-content-center text-center p-4">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(56, 189, 248, 0.15); color: var(--accent-cyan); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 20px;">
              <i class="fas fa-compass"></i>
            </div>
            <h4 style="font-size: 1.35rem; margin-bottom: 12px; color: #ffffff !important;">Recomendaciones para la Visita</h4>
            <p style="color: #cbd5e1 !important; font-size: 0.95rem; max-width: 440px; margin: 0 auto 20px; line-height: 1.6;">
              Para las observaciones nocturnas, recomendamos asistir con abrigo adecuado (las temperaturas suelen descender en la noche) y calzado cómodo. El acceso se realiza por el portal principal de la universidad.
            </p>
            <div>
              <a href="{{ route('turnos') }}" class="btn-cosmic-primary">
                <i class="fas fa-calendar-alt"></i>
                <span>Gestionar mi Reserva</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       FOOTER INSTITUCIONAL
       ========================================================================== -->
  <footer class="cosmic-footer">
    <div class="container">
      <div class="row">
        <!-- Columna 1: Identidad -->
        <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
          <div class="d-flex align-items-center mb-3" style="gap: 12px;">
            <!-- Badge para el logo en el footer -->
            <div class="brand-logo-wrap">
              <img src="{{ asset('img/logounsa.png') }}" alt="UNSa">
            </div>
            <div>
              <h4 style="font-size: 1.15rem; margin-bottom: 0; color: #ffffff !important;">Observatorio Alanís</h4>
              <small style="color: var(--accent-cyan); font-weight: 600;">Facultad de Ciencias Exactas - UNSa</small>
            </div>
          </div>
          <p style="color: #cbd5e1 !important; font-size: 0.92rem; max-width: 380px;">
            Plataforma oficial de turnos y gestión de actividades astronómicas. Promoviendo la ciencia, la educación y el asombro por el cosmos en el norte argentino.
          </p>
        </div>

        <!-- Columna 2: Enlaces Rápidos -->
        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
          <h5 style="font-size: 1.05rem; margin-bottom: 18px; color: #ffffff !important;">Navegación</h5>
          <ul class="footer-links">
            <li><a href="#experiencias"><i class="fas fa-chevron-right mr-2" style="font-size: 0.75rem; color: var(--accent-cyan);"></i> Experiencias</a></li>
            <li><a href="#como-funciona"><i class="fas fa-chevron-right mr-2" style="font-size: 0.75rem; color: var(--accent-cyan);"></i> Cómo Reservar</a></li>
            <li><a href="#portal-usuario"><i class="fas fa-chevron-right mr-2" style="font-size: 0.75rem; color: var(--accent-cyan);"></i> Portal de Usuario</a></li>
            <li><a href="#consulta"><i class="fas fa-chevron-right mr-2" style="font-size: 0.75rem; color: var(--accent-cyan);"></i> Consultar mi Turno</a></li>
            <li><a href="#faq"><i class="fas fa-chevron-right mr-2" style="font-size: 0.75rem; color: var(--accent-cyan);"></i> Preguntas Frecuentes</a></li>
            <li><a href="#ubicacion"><i class="fas fa-chevron-right mr-2" style="font-size: 0.75rem; color: var(--accent-cyan);"></i> Ubicación</a></li>
          </ul>
        </div>

        <!-- Columna 3: Portal y Gestión -->
        <div class="col-lg-4 col-md-12">
          <h5 style="font-size: 1.05rem; margin-bottom: 18px; color: #ffffff !important;">Portal de Usuarios y Gestión</h5>
          <p style="color: #cbd5e1 !important; font-size: 0.9rem; margin-bottom: 16px;">
            Acceso unificado para visitantes (ver turnos anteriores y próximos) y operadores de dependencias de la facultad.
          </p>
          <div class="d-flex flex-column" style="gap: 12px;">
            <a href="{{ route('login') }}" class="btn-cosmic-outline justify-content-center" style="font-size: 0.9rem; padding: 10px 20px;">
              <i class="fas fa-user-astronaut"></i>
              <span>Ingresar a Mi Portal (Visitantes)</span>
            </a>
            <a href="{{ route('login') }}" class="small" style="color: #94a3b8 !important; text-decoration: none;">
              <i class="fas fa-lock mr-1" style="color: var(--accent-cyan);"></i> Acceso para Operadores y Administradores
            </a>
          </div>
        </div>
      </div>

      <hr style="border-color: rgba(255, 255, 255, 0.12); margin: 40px 0 24px;">

      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center footer-bottom-text">
        <div>
          © {{ date('Y') }} Universidad Nacional de Salta · Todos los derechos reservados.
        </div>
        <div class="mt-2 mt-md-0">
          Observatorio Astronómico Dr. Elvio Alanís
        </div>
      </div>
    </div>
  </footer>

  <!-- ==========================================================================
       SCRIPTS INTERACTIVOS (NATIVO, ULTRA RÁPIDO)
       ========================================================================== -->
  <script>
    // Dropdown de Usuario en Navbar
    function toggleUserDropdown() {
      const menu = document.getElementById('userDropdownMenu');
      if (menu) {
        menu.classList.toggle('show');
      }
    }

    // Cerrar dropdown al hacer click afuera
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById('userDropdownMenu');
      const button = document.getElementById('userDropdownBtn');
      if (dropdown && button && !button.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    });

    // Comportamiento interactivo del Acordeón de Preguntas Frecuentes (FAQ)
    document.querySelectorAll('.faq-question').forEach(item => {
      item.addEventListener('click', () => {
        const parent = item.parentElement;
        const isActive = parent.classList.contains('active');
        
        // Cerrar otros ítems abiertos para mantener limpieza visual
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));
        
        if (!isActive) {
          parent.classList.add('active');
        }
      });
    });

    // Desplazamiento suave para enlaces ancla
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if (targetId && targetId !== '#') {
          const target = document.querySelector(targetId);
          if (target) {
            e.preventDefault();
            const navOffset = 80;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - navOffset;
            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
          }
        }
      });
    });
  </script>
</body>
</html>
