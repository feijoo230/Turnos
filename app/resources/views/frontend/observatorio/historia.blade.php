@extends('frontend.layouts.app')

@section('content')
  <style>
    .historia-badge {
      display: inline-block;
      background: #e0f2fe;
      color: #0284c7;
      font-weight: 700;
      font-size: 0.8rem;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 20px;
      margin-bottom: 10px;
    }
    .historia-section {
      padding-bottom: 3rem;
      margin-bottom: 3rem;
      border-bottom: 1px solid #e2e8f0;
    }
    .historia-photo-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .historia-photo-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.11);
    }
    .historia-photo-wrap {
      position: relative;
      overflow: hidden;
      background: #0f172a;
      cursor: zoom-in;
    }
    .historia-photo-wrap img {
      width: 100%;
      display: block;
      transition: transform 0.3s ease;
    }
    .historia-photo-card:hover .historia-photo-wrap img {
      transform: scale(1.02);
    }
    .historia-photo-cupula {
      height: 320px;
      object-fit: cover;
    }
    .historia-photo-reconocimiento {
      height: 300px;
      object-fit: cover;
    }
    .historia-photo-caption {
      padding: 12px 16px;
      font-size: 0.86rem;
      color: #475569;
      text-align: center;
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      flex-grow: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .historia-lightbox {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 2000;
      background: rgba(15, 23, 42, 0.9);
      padding: 24px;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }
    .historia-lightbox.open {
      display: flex;
    }
    .historia-lightbox img {
      max-width: 92vw;
      max-height: 80vh;
      border-radius: 8px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }
    .historia-lightbox-caption {
      color: #e2e8f0;
      margin-top: 14px;
      font-size: 0.95rem;
      text-align: center;
      max-width: 780px;
    }
    .historia-lightbox-close {
      position: absolute;
      top: 18px;
      right: 24px;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: #ffffff;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      font-size: 1.3rem;
      line-height: 1;
      cursor: pointer;
    }
    .historia-lightbox-close:hover {
      background: rgba(255, 255, 255, 0.26);
    }
  </style>

  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">El Observatorio</span>
      <h2 class="page-title">Historia</h2>
      <p class="text-muted lead" style="max-width: 700px; margin: 0 auto;">Un espacio nacido del sueño de un grupo de docentes y aficionados hace más de 35 años.</p>
    </div>
  </div>

  <div class="container py-5">
    <!-- 1. Los inicios (1985) -->
    <div class="row align-items-center historia-section">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <span class="historia-badge">1985</span>
        <h3 class="mb-3">Los inicios</h3>
        <p class="text-muted" style="line-height: 1.8; font-size: 1.03rem;">
          En 1985, el Lic. Elvio Edgardo Alanís, junto a un grupo de docentes del Departamento de Física y del Instituto de Enseñanza Media (IEM) de la UNSa, se reunieron impulsados por el deseo de construir un planetario en la ciudad de Salta. Ese mismo año nació la Asociación Salteña de Astronomía (ASA), presidida por Elvio Alanís, con el objetivo de construir un observatorio astronómico en la provincia.
        </p>
      </div>
      <div class="col-lg-6">
        <figure class="historia-photo-card mb-0">
          <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-inicios-asa.jpg') }}" data-lightbox-caption="Integrantes de la Asociación Salteña de Astronomía (ASA) — Viernes 21 de diciembre de 1990">
            <img src="{{ asset('img/observatorio/historia-inicios-asa.jpg') }}" alt="Equipo de la Asociación Salteña de Astronomía (ASA), 1990">
          </div>
          <figcaption class="historia-photo-caption">Integrantes de la Asociación Salteña de Astronomía (ASA) — 21 de diciembre de 1990</figcaption>
        </figure>
      </div>
    </div>

    <!-- 2. La cúpula (1986-1988) -->
    <div class="historia-section">
      <div class="row mb-4">
        <div class="col-lg-9">
          <span class="historia-badge">1986 – 1988</span>
          <h3 class="mb-3">La cúpula</h3>
          <p class="text-muted mb-0" style="line-height: 1.8; font-size: 1.03rem;">
            El grupo fundador diseñó, construyó y montó artesanalmente una cúpula hemisférica que albergaría al futuro telescopio. Tras dos años de trabajo, la cúpula fue inaugurada el 29 de agosto de 1988.
          </p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <figure class="historia-photo-card mb-0">
            <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-cupula-1.jpg') }}?v=2" data-lightbox-caption="Armado artesanal de la estructura hemisférica metálica de la cúpula">
              <img src="{{ asset('img/observatorio/historia-cupula-1.jpg') }}?v=2" alt="Armado artesanal de la estructura de la cúpula" class="historia-photo-cupula" style="object-position: center center;">
            </div>
            <figcaption class="historia-photo-caption">Armado artesanal de la estructura hemisférica</figcaption>
          </figure>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
          <figure class="historia-photo-card mb-0">
            <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-cupula-2.jpg') }}?v=2" data-lightbox-caption="Montaje de la cúpula sobre la terraza del edificio de la UNSa">
              <img src="{{ asset('img/observatorio/historia-cupula-2.jpg') }}?v=2" alt="Montaje de la cúpula en altura" class="historia-photo-cupula" style="object-position: center 82%;">
            </div>
            <figcaption class="historia-photo-caption">Montaje de la cúpula sobre la terraza</figcaption>
          </figure>
        </div>
        <div class="col-md-4">
          <figure class="historia-photo-card mb-0">
            <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-cupula-3.jpg') }}" data-lightbox-caption="Cúpula terminada en su inauguración el 29 de agosto de 1988, junto al Prof. Elvio Alanís">
              <img src="{{ asset('img/observatorio/historia-cupula-3.jpg') }}" alt="Cúpula terminada junto al Prof. Elvio Alanís en 1988" class="historia-photo-cupula" style="object-position: center 40%;">
            </div>
            <figcaption class="historia-photo-caption">Inauguración de la cúpula (1988) junto al Prof. Elvio Alanís</figcaption>
          </figure>
        </div>
      </div>
    </div>

    <!-- 3. El telescopio (1988-1994) -->
    <div class="row align-items-center flex-lg-row-reverse historia-section">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <span class="historia-badge">1988 – 1994</span>
        <h3 class="mb-3">El telescopio</h3>
        <p class="text-muted" style="line-height: 1.8; font-size: 1.03rem;">
          En 1988 se firmó un acuerdo entre la Facultad de Ciencias Exactas, la ASA y la Facultad de Ciencias Astronómicas y Geofísicas de la Universidad Nacional de La Plata para construir un telescopio newtoniano de entre 400 y 600 mm de diámetro. El proyecto culminó en 1994 con la inauguración del Telescopio Reflector Newtoniano de 500 mm de diámetro, f/6, que continúa siendo el instrumento principal del Observatorio.
        </p>
      </div>
      <div class="col-lg-6">
        <figure class="historia-photo-card mb-0">
          <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-telescopio.jpg') }}?v=2" data-lightbox-caption="Telescopio reflector newtoniano de 500 mm, f/6 (1994), junto al Prof. Elvio Alanís y colaboradores">
            <img src="{{ asset('img/observatorio/historia-telescopio.jpg') }}?v=2" alt="Telescopio reflector newtoniano de 500 mm, f/6 en 1994">
          </div>
          <figcaption class="historia-photo-caption">Telescopio reflector newtoniano de 500 mm, f/6 (1994)</figcaption>
        </figure>
      </div>
    </div>

    <!-- 4. Reconocimiento Institucional (2023) -->
    <div class="historia-section">
      <div class="row mb-4">
        <div class="col-lg-9">
          <span class="historia-badge">2023</span>
          <h3 class="mb-3">Reconocimiento Institucional</h3>
          <p class="text-muted mb-0" style="line-height: 1.8; font-size: 1.03rem;">
            El 26 de octubre de 2023, el Consejo Superior de la Universidad Nacional de Salta resolvió formalmente la creación del Observatorio Astronómico como establecimiento dependiente del Departamento de Física de la Facultad de Ciencias Exactas, imponiéndole el nombre de Profesor Elvio Edgardo Alanís, en homenaje a su fundador.
          </p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-5 mb-4 mb-md-0">
          <figure class="historia-photo-card mb-0">
            <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-nombre.jpg') }}?v=2" data-lightbox-caption="Prof. Elvio Edgardo Alanís, fundador del Observatorio Astronómico de la UNSa">
              <img src="{{ asset('img/observatorio/historia-nombre.jpg') }}?v=2" alt="Prof. Elvio Edgardo Alanís" class="historia-photo-reconocimiento" style="object-position: center 20%;">
            </div>
            <figcaption class="historia-photo-caption">Prof. Elvio Edgardo Alanís, fundador del Observatorio</figcaption>
          </figure>
        </div>
        <div class="col-md-7">
          <figure class="historia-photo-card mb-0">
            <div class="historia-photo-wrap" data-lightbox-src="{{ asset('img/observatorio/historia-aprobacion-nombre.jpg') }}" data-lightbox-caption="Docentes e integrantes del Departamento de Física aprobando la imposición del nombre del Observatorio">
              <img src="{{ asset('img/observatorio/historia-aprobacion-nombre.jpg') }}" alt="Departamento de Física aprueba la imposición del nombre del Observatorio" class="historia-photo-reconocimiento" style="object-position: center center;">
            </div>
            <figcaption class="historia-photo-caption">El Departamento de Física aprueba la imposición del nombre del Observatorio</figcaption>
          </figure>
        </div>
      </div>
    </div>

    <!-- 5. El Observatorio hoy -->
    <div class="row mt-4 text-center">
      <div class="col-12">
        <h3 class="mb-4">El Observatorio hoy</h3>
        <p class="text-muted" style="max-width: 900px; margin: 0 auto; line-height: 1.8; font-size: 1.05rem;">
          Cuatro décadas después de aquella primera reunión, el Observatorio se ha consolidado como un centro de referencia regional para la promoción, divulgación y desarrollo de la educación en astronomía en el noroeste argentino, sosteniendo la doble misión con la que nació en 1985: fomentar la investigación científica y acercar el conocimiento del cielo a la sociedad salteña de todas las edades, con especial atención a los estudiantes de nivel medio.
        </p>
        <div class="mt-4">
          <a href="https://revistas.unc.edu.ar/index.php/revistaEF/article/view/50844" target="_blank" rel="noopener noreferrer" class="btn-outline-sober"><i class="fas fa-external-link-alt mr-2"></i> Leer publicación académica (Bugiolachio, Domenichini y Hoyos, 2025)</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Lightbox para ampliar fotografías históricas -->
  <div id="historiaLightbox" class="historia-lightbox" role="dialog" aria-modal="true" aria-label="Vista ampliada de fotografía histórica">
    <button type="button" class="historia-lightbox-close" id="historiaLightboxClose" aria-label="Cerrar">&times;</button>
    <img id="historiaLightboxImg" src="" alt="">
    <p id="historiaLightboxCaption" class="historia-lightbox-caption"></p>
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      var lightbox = document.getElementById('historiaLightbox');
      var lightboxImg = document.getElementById('historiaLightboxImg');
      var lightboxCaption = document.getElementById('historiaLightboxCaption');
      var closeBtn = document.getElementById('historiaLightboxClose');

      if (!lightbox) return;

      document.querySelectorAll('[data-lightbox-src]').forEach(function (el) {
        el.addEventListener('click', function () {
          lightboxImg.src = el.getAttribute('data-lightbox-src');
          lightboxImg.alt = el.getAttribute('data-lightbox-caption') || '';
          lightboxCaption.textContent = el.getAttribute('data-lightbox-caption') || '';
          lightbox.classList.add('open');
        });
      });

      function closeLightbox() {
        lightbox.classList.remove('open');
        lightboxImg.src = '';
      }

      closeBtn.addEventListener('click', closeLightbox);
      lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) closeLightbox();
      });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && lightbox.classList.contains('open')) closeLightbox();
      });
    })();
  </script>
@endpush
