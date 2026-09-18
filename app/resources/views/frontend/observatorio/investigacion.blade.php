@extends('frontend.layouts.app')

@section('content')
  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">Publicaciones</span>
      <h2 class="page-title">Investigación y Publicaciones</h2>
      <p class="text-muted lead" style="max-width: 800px; margin: 0 auto;">El equipo del Observatorio participa activamente en la investigación sobre enseñanza de la astronomía y la física, publicando sus experiencias y hallazgos en revistas especializadas.</p>
    </div>
  </div>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        @forelse($investigaciones as $inv)
          <div class="card shadow-sm mb-4" style="border: none; border-left: 4px solid #0284c7; border-radius: 8px; transition: transform 0.15s ease;">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-start">
              <div class="mr-md-4 mb-3 mb-md-0 text-center text-md-left">
                  @if($inv->pdf_url)
                    <i class="fas fa-file-pdf fa-2x" style="color: #ef4444; background: #fee2e2; padding: 15px; border-radius: 8px;"></i>
                  @else
                    <i class="fas fa-book-open fa-2x" style="color: #0284c7; background: #e0f2fe; padding: 15px; border-radius: 8px;"></i>
                  @endif
              </div>
              <div class="flex-grow-1">
                  <div class="d-flex justify-content-between align-items-baseline flex-wrap">
                    <h4 class="mb-1 font-weight-bold" style="color: #1e293b;">{{ $inv->titulo }}</h4>
                    @if($inv->ano)
                      <span class="badge badge-light border text-muted ml-md-2 mb-1" style="font-size: 0.85rem;">{{ $inv->ano }}</span>
                    @endif
                  </div>

                  @if($inv->autores)
                    <p class="text-muted small mb-2"><i class="fas fa-users mr-1"></i> {{ $inv->autores }}</p>
                  @endif

                  @if($inv->revista)
                    <p class="text-primary font-weight-bold mb-2 small"><i class="fas fa-bookmark mr-1"></i> {{ $inv->revista }}</p>
                  @endif

                  @if($inv->descripcion)
                    <p class="text-muted mb-3" style="line-height: 1.6;">{{ $inv->descripcion }}</p>
                  @endif

                  <div class="d-flex flex-wrap" style="gap: 10px;">
                    @if($inv->pdf_url)
                      <a href="{{ $inv->pdf_url }}" target="_blank" class="btn btn-sm btn-danger font-weight-bold" style="border-radius: 6px;">
                        <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
                      </a>
                    @endif

                    @if($inv->enlace_url)
                      <a href="{{ $inv->enlace_url }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 6px;">
                        <i class="fas fa-external-link-alt mr-1"></i> Ver artículo completo
                      </a>
                    @endif
                  </div>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-5">
            <div class="p-5 bg-light" style="border-radius: 12px;">
              <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
              <h4 class="text-muted">Próximamente publicaciones científicas</h4>
              <p class="text-muted">Los artículos y publicaciones del Observatorio se listarán aquí una vez aprobados.</p>
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
