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
    
    <div class="row align-items-center mb-5 pb-5 border-bottom">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="text-primary mb-3"><i class="fas fa-university text-muted mr-2" style="font-size: 1.5rem;"></i> Cúpula Hemisférica</h3>
        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">Construida de forma totalmente artesanal por el equipo fundador del Observatorio. Fue inaugurada el 29 de agosto de 1988 y desde entonces se ha convertido en el ícono arquitectónico que resguarda nuestro instrumento principal.</p>
        <ul class="list-unstyled text-muted mt-4">
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Montaje artesanal</li>
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Espacio para grupos reducidos</li>
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Diseño hemisférico optimizado</li>
        </ul>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <img src="{{ asset('img/observatorio/instalacion-cupula.jpg') }}" alt="Cúpula del Observatorio" class="img-fluid rounded shadow" style="border: 1px solid var(--border-color); width: 100%;">
      </div>
    </div>

    <div class="row align-items-center mb-5 pb-5 border-bottom flex-row-reverse">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="text-primary mb-3"><i class="fas fa-telescope text-muted mr-2" style="font-size: 1.5rem;"></i> Telescopio Reflector</h3>
        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">Es nuestro instrumento principal. Un telescopio reflector newtoniano de 500 milímetros de diámetro y relación focal f/6. Inaugurado en 1994, permite observaciones de espacio profundo y observación planetaria de alta calidad.</p>
        <ul class="list-unstyled text-muted mt-4">
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Newtoniano 500 mm</li>
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Relación focal f/6</li>
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Ideal para cielo profundo</li>
        </ul>
      </div>
      <div class="col-lg-5 mr-auto">
        <img src="{{ asset('img/observatorio/instalacion-telescopio.png') }}" alt="Telescopio del Observatorio" class="img-fluid rounded shadow" style="border: 1px solid var(--border-color); width: 100%;">
      </div>
    </div>

    <div class="row align-items-center mb-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="text-primary mb-3"><i class="fas fa-binoculars text-muted mr-2" style="font-size: 1.5rem;"></i> Instrumental Móvil</h3>
        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">Para las actividades de campo, observaciones masivas y proyectos de extensión como 'Un cielo en común', el Observatorio dispone de telescopios portátiles y binoculares de gran apertura.</p>
        <ul class="list-unstyled text-muted mt-4">
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Telescopios refractores portátiles</li>
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Binoculares astronómicos</li>
          <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Equipamiento didáctico auxiliar</li>
        </ul>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <img src="{{ asset('img/observatorio/instalacion-instrumental.png') }}" alt="Instrumental móvil y actividades de campo" class="img-fluid rounded shadow" style="border: 1px solid var(--border-color); width: 100%;">
      </div>
    </div>

  </div>
@endsection
