@extends('frontend.layouts.app')

@section('content')
  <!-- HERO BANNER -->
  <div style="background: linear-gradient(rgba(15, 23, 42, 0.82), rgba(15, 23, 42, 0.82)), url('{{ asset('img/observatorio/hero-bg.jpg') }}') center/cover no-repeat; padding: 120px 0; border-bottom: 1px solid #e2e8f0; color: white;">
    <div class="container text-center">
      <h2 class="page-title" style="color: white !important; font-size: 3rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Observatorio Astronómico<br>Prof. Elvio Alanís</h2>
      <p class="lead" style="max-width: 800px; margin: 0 auto; color: #e2e8f0; font-size: 1.15rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
        Un cielo abierto a toda la comunidad. Desde 1988, el Observatorio de la <strong>Universidad Nacional de Salta</strong> acerca el universo a estudiantes, docentes, investigadores y vecinos de Salta. Ubicado en el campus de la UNSa, es un espacio de divulgación, educación e investigación astronómica.
      </p>
      <div class="mt-5 d-flex justify-content-center flex-wrap" style="gap: 15px;">
        <a href="{{ route('turnos') }}" class="btn btn-lg" style="background: #0284c7; color: white; border: none; padding: 14px 28px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.2); border-radius: 8px;">
          <i class="fas fa-calendar-check mr-2"></i> Solicitar Mi Turno Online
        </a>
        <a href="{{ route('proyectos') }}" class="btn btn-lg btn-outline-light" style="padding: 14px 28px; font-weight: 600; font-size: 1.1rem; border-radius: 8px;">
          <i class="fas fa-compass mr-2"></i> Explorar Proyectos
        </a>
      </div>
    </div>
  </div>

  <!-- PILARES DE ACTIVIDAD -->
  <div class="container py-5 my-4">
    <div class="text-center mb-5">
      <span class="section-tag" style="background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">Nuestra Misión</span>
      <h3 class="font-weight-bold mt-2" style="color: #1e293b;">Áreas de Trabajo del Observatorio</h3>
      <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Promovemos la educación científica, el compromiso social y la producción académica desde Salta.</p>
    </div>

    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; transition: transform 0.2s;">
          <img src="{{ asset('img/observatorio/observacion-nocturna.png') }}" class="card-img-top" alt="Actividades Públicas" style="height: 200px; object-fit: cover;">
          <div class="card-body text-center p-4 d-flex flex-column">
            <h4 class="card-title text-primary font-weight-bold">Actividades Públicas</h4>
            <p class="card-text text-muted flex-grow-1">Observaciones nocturnas con telescopios, jornadas de divulgación y charlas científicas abiertas a toda la familia y vecinos de Salta.</p>
            <div class="mt-3">
              <a href="{{ route('turnos') }}" class="btn btn-sm btn-outline-primary font-weight-bold"><i class="fas fa-eye mr-1"></i> Reservar visita</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; transition: transform 0.2s;">
          <img src="{{ asset('img/observatorio/visitas-escolares.png') }}" class="card-img-top" alt="Visitas Escolares" style="height: 200px; object-fit: cover;">
          <div class="card-body text-center p-4 d-flex flex-column">
            <h4 class="card-title text-primary font-weight-bold">Visitas Escolares</h4>
            <p class="card-text text-muted flex-grow-1">Recorridos pedagógicos guiados y talleres prácticos adaptados para contingentes escolares de nivel inicial, primario y secundario.</p>
            <div class="mt-3">
              <a href="{{ route('turnos') }}" class="btn btn-sm btn-outline-primary font-weight-bold"><i class="fas fa-graduation-cap mr-1"></i> Turnos para escuelas</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; transition: transform 0.2s;">
          <img src="{{ asset('img/observatorio/divulgacion.jpg') }}" class="card-img-top" alt="Ciencia y Extensión" style="height: 200px; object-fit: cover;">
          <div class="card-body text-center p-4 d-flex flex-column">
            <h4 class="card-title text-primary font-weight-bold">Ciencia y Extensión</h4>
            <p class="card-text text-muted flex-grow-1">Proyectos comunitarios con escuelas rurales, publicaciones especializadas en enseñanza de la física e instrumental astronómico.</p>
            <div class="mt-3 d-flex justify-content-center" style="gap: 8px;">
              <a href="{{ route('proyectos') }}" class="btn btn-sm btn-outline-primary font-weight-bold">Proyectos</a>
              <a href="{{ route('investigacion') }}" class="btn btn-sm btn-outline-info font-weight-bold">Investigación</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SECCIÓN DINÁMICA: PROYECTOS DE EXTENSIÓN DESTACADOS -->
  @if(isset($ultimosProyectos) && $ultimosProyectos->count() > 0)
    <div class="bg-light py-5 border-top border-bottom">
      <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap">
          <div>
            <span class="section-tag" style="background: #fef3c7; color: #b45309; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;">Comunidad y Territorio</span>
            <h3 class="font-weight-bold mt-2 mb-1" style="color: #0f172a;">Proyectos de Extensión</h3>
            <p class="text-muted mb-0">Iniciativas que acercan la ciencia astronómica a diferentes puntos de la provincia.</p>
          </div>
          <div class="mt-3 mt-md-0">
            <a href="{{ route('proyectos') }}" class="btn btn-outline-primary font-weight-bold" style="border-radius: 6px;">
              Ver todos los proyectos <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        </div>

        <div class="row">
          @foreach($ultimosProyectos as $proyecto)
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px; overflow: hidden; background: white;">
                <div style="height: 160px; overflow: hidden; background: #f1f5f9;">
                  <img src="{{ $proyecto->imagen_url }}" alt="{{ $proyecto->nombre }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                  @if($proyecto->ano)
                    <div><span class="badge badge-info mb-2" style="font-size: 0.75rem;">{{ $proyecto->ano }}</span></div>
                  @endif
                  <h5 class="card-title font-weight-bold text-dark mb-1" style="font-size: 1.05rem;">{{ $proyecto->nombre }}</h5>
                  @if($proyecto->subtitulo)
                    <small class="text-muted font-italic mb-2 d-block">{{ \Illuminate\Support\Str::limit($proyecto->subtitulo, 45) }}</small>
                  @endif
                  <p class="card-text text-muted small flex-grow-1" style="line-height: 1.5;">
                    {{ \Illuminate\Support\Str::limit($proyecto->descripcion, 90) }}
                  </p>
                  <a href="{{ route('proyectos') }}" class="small text-primary font-weight-bold mt-2">
                    Leer más <i class="fas fa-chevron-right ml-1" style="font-size: 0.7rem;"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  <!-- SECCIÓN DINÁMICA: INVESTIGACIONES Y PUBLICACIONES -->
  @if(isset($ultimasInvestigaciones) && $ultimasInvestigaciones->count() > 0)
    <div class="container py-5 my-3">
      <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap">
        <div>
          <span class="section-tag" style="background: #e0f2fe; color: #0284c7; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;">Academia y Didáctica</span>
          <h3 class="font-weight-bold mt-2 mb-1" style="color: #0f172a;">Últimas Investigaciones</h3>
          <p class="text-muted mb-0">Publicaciones y artículos científicos del equipo del Observatorio.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ route('investigacion') }}" class="btn btn-outline-primary font-weight-bold" style="border-radius: 6px;">
            Ver todas las publicaciones <i class="fas fa-arrow-right ml-1"></i>
          </a>
        </div>
      </div>

      <div class="row">
        @foreach($ultimasInvestigaciones as $inv)
          <div class="col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px; border-top: 3px solid #0284c7 !important; background: white;">
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="text-danger"><i class="fas fa-file-pdf fa-lg"></i></span>
                  @if($inv->ano)
                    <span class="badge badge-light border text-muted">{{ $inv->ano }}</span>
                  @endif
                </div>
                <h5 class="card-title font-weight-bold text-dark mb-2" style="font-size: 1rem; line-height: 1.4;">{{ $inv->titulo }}</h5>
                @if($inv->revista)
                  <p class="small text-primary font-weight-bold mb-2">{{ $inv->revista }}</p>
                @endif
                <p class="card-text text-muted small flex-grow-1" style="line-height: 1.5;">
                  {{ \Illuminate\Support\Str::limit($inv->descripcion, 110) }}
                </p>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                  @if($inv->pdf_url)
                    <a href="{{ $inv->pdf_url }}" target="_blank" class="small text-danger font-weight-bold"><i class="fas fa-file-pdf mr-1"></i> Descargar PDF</a>
                  @elseif($inv->enlace_url)
                    <a href="{{ $inv->enlace_url }}" target="_blank" class="small text-primary font-weight-bold"><i class="fas fa-external-link-alt mr-1"></i> Ver en revista</a>
                  @else
                    <a href="{{ route('investigacion') }}" class="small text-primary font-weight-bold">Ver detalle</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
@endsection
