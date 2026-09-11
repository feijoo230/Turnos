@extends('frontend.layouts.app')

@section('content')
  <div style="background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('{{ asset('img/observatorio/hero-bg.jpg') }}') center/cover no-repeat; padding: 120px 0; border-bottom: 1px solid #e2e8f0; color: white;">
    <div class="container text-center">
      <h2 class="page-title" style="color: white !important; font-size: 3rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Observatorio Astronómico<br>Prof. Elvio Alanís</h2>
      <p class="lead" style="max-width: 800px; margin: 0 auto; color: #e2e8f0; font-size: 1.15rem; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
        Un cielo abierto a toda la comunidad. Desde 1988, el Observatorio de la <strong>Universidad Nacional de Salta</strong> acerca el universo a estudiantes, docentes, investigadores y vecinos de Salta. Ubicado en el campus de la UNSa, es un espacio de divulgación, educación e investigación astronómica.
      </p>
      <div class="mt-5">
        <a href="{{ route('turnos') }}" class="btn btn-lg" style="background: #0284c7; color: white; border: none; padding: 15px 30px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: transform 0.2s;"><i class="fas fa-calendar-check mr-2"></i> Solicitar Mi Turno Online</a>
      </div>
    </div>
  </div>

  <div class="container py-5 my-4">
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <img src="{{ asset('img/observatorio/observacion-nocturna.png') }}" class="card-img-top" alt="Actividades Públicas" style="height: 200px; object-fit: cover;">
          <div class="card-body text-center p-4">
            <h4 class="card-title text-primary">Actividades Públicas</h4>
            <p class="card-text text-muted">Observaciones nocturnas y charlas de divulgación para toda la familia y vecinos de Salta.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <img src="{{ asset('img/observatorio/visitas-escolares.png') }}" class="card-img-top" alt="Visitas Escolares" style="height: 200px; object-fit: cover;">
          <div class="card-body text-center p-4">
            <h4 class="card-title text-primary">Visitas Escolares</h4>
            <p class="card-text text-muted">Recorridos pedagógicos para contingentes educativos de todos los niveles.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <img src="{{ asset('img/observatorio/divulgacion.jpg') }}" class="card-img-top" alt="Ciencia y Extensión" style="height: 200px; object-fit: cover;">
          <div class="card-body text-center p-4">
            <h4 class="card-title text-primary">Ciencia y Extensión</h4>
            <p class="card-text text-muted">Proyectos universitarios, publicaciones e investigación en enseñanza de la física.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
