@extends('frontend.layouts.app')

@section('content')
  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">Extensión Universitaria</span>
      <h2 class="page-title">Proyectos de Extensión</h2>
      <p class="text-muted lead" style="max-width: 800px; margin: 0 auto;">El Observatorio impulsa proyectos de extensión con participación estudiantil que llevan la astronomía más allá de la Universidad, hacia escuelas y comunidades de la provincia.</p>
    </div>
  </div>

  <div class="container py-5">
    <div class="row">
      @forelse($proyectos as $proyecto)
        <div class="col-lg-6 mb-5">
          <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;">
            <div class="card-img-top border-bottom text-center bg-light" style="border-color: #e2e8f0; height: 260px; overflow: hidden;">
              <img src="{{ $proyecto->imagen_url }}" alt="{{ $proyecto->nombre }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex justify-content-between align-items-center mb-2">
                @if($proyecto->ano)
                  <span class="badge badge-info" style="font-size: 0.85rem; padding: 5px 10px; border-radius: 6px;">{{ $proyecto->ano }}</span>
                @endif
              </div>
              <h4 class="card-title text-primary font-weight-bold mb-1">{{ $proyecto->nombre }}</h4>
              @if($proyecto->subtitulo)
                <h6 class="text-muted mb-3 font-italic">{{ $proyecto->subtitulo }}</h6>
              @endif
              <p class="card-text text-muted flex-grow-1" style="line-height: 1.6;">{{ $proyecto->descripcion }}</p>
              
              @if($proyecto->enlace_url)
                <div class="mt-3 pt-2 border-top">
                  <a href="{{ $proyecto->enlace_url }}" target="_blank" class="btn btn-sm text-primary font-weight-bold p-0">
                    <i class="fas fa-external-link-alt mr-1"></i> Conocer más del proyecto
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="p-5 bg-light" style="border-radius: 12px;">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Próximamente nuevos proyectos de extensión</h4>
            <p class="text-muted">Estamos planificando las próximas actividades con la comunidad educativa y escuelas de la región.</p>
          </div>
        </div>
      @endforelse
    </div>
  </div>
@endsection
