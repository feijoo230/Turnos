@extends('frontend.layouts.app')

@section('content')
  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">Infraestructura</span>
      <h2 class="page-title">Instalaciones y Equipamiento</h2>
      <p class="text-muted lead" style="max-width: 600px; margin: 0 auto;">El Observatorio cuenta con un espacio físico propio en el Campus Universitario Castañares y diversos instrumentos astronómicos.</p>
    </div>
  </div>

  <div class="container py-5">
    
    @forelse($instalaciones as $inst)
      <div class="row align-items-center mb-5 {{ !$loop->last ? 'pb-5 border-bottom' : '' }} {{ $loop->iteration % 2 == 0 ? 'flex-row-reverse' : '' }}">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <h3 class="text-primary mb-3">
            <i class="{{ $inst->icono ?: 'fas fa-university' }} text-muted mr-2" style="font-size: 1.5rem;"></i> {{ $inst->nombre }}
          </h3>
          <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">{{ $inst->descripcion }}</p>
          
          @if(count($inst->caracteristicas_list) > 0)
            <ul class="list-unstyled text-muted mt-4">
              @foreach($inst->caracteristicas_list as $caract)
                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> {{ $caract }}</li>
              @endforeach
            </ul>
          @endif
        </div>
        
        <div class="col-lg-5 {{ $loop->iteration % 2 == 0 ? 'mr-auto' : 'offset-lg-1' }}">
          @if($inst->imagen_url)
            <img src="{{ $inst->imagen_url }}" alt="{{ $inst->nombre }}" class="img-fluid rounded shadow" style="border: 1px solid var(--border-color); width: 100%;">
          @else
            <div class="d-flex align-items-center justify-content-center rounded shadow bg-light" style="border: 1px solid var(--border-color); width: 100%; height: 260px;">
              <i class="{{ $inst->icono ?: 'fas fa-university' }} fa-4x text-muted"></i>
            </div>
          @endif
        </div>
      </div>
    @empty
      <div class="text-center text-muted py-5">
        <p class="lead">No hay instalaciones o equipamiento registrados actualmente.</p>
      </div>
    @endforelse

  </div>
@endsection
