@extends('frontend.layouts.app')

@section('content')
  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">El Observatorio</span>
      <h2 class="page-title">Historia</h2>
      <p class="text-muted lead" style="max-width: 700px; margin: 0 auto;">Un espacio nacido del sueño de un grupo de docentes y aficionados hace más de 35 años.</p>
    </div>
  </div>

  <div class="container py-5">
    <div class="row mb-5">
      <div class="col-md-6 mb-4">
        <h4>Los inicios (1985)</h4>
        <p class="text-muted">En 1985, el Lic. Elvio Edgardo Alanís, junto a un grupo de docentes del Departamento de Física y del Instituto de Enseñanza Media (IEM) de la UNSa, se reunieron impulsados por el deseo de construir un planetario en la ciudad de Salta. Ese mismo año nació la Asociación Salteña de Astronomía (ASA), presidida por Elvio Alanís, con el objetivo de construir un observatorio astronómico en la provincia.</p>
      </div>
      <div class="col-md-6 mb-4">
        <h4>La cúpula (1986-1988)</h4>
        <p class="text-muted">El grupo fundador diseñó, construyó y montó artesanalmente una cúpula hemisférica que albergaría al futuro telescopio. Tras dos años de trabajo, la cúpula fue inaugurada el 29 de agosto de 1988.</p>
        <div class="mt-3">
            <div class="row">
              <div class="col-6">
                <img src="{{ asset('img/observatorio/historia-cupula-1.jpg') }}" alt="Construcción de la cúpula" class="img-fluid rounded border shadow-sm w-100">
              </div>
              <div class="col-6">
                <img src="{{ asset('img/observatorio/historia-cupula-2.jpg') }}" alt="Cúpula terminada" class="img-fluid rounded border shadow-sm w-100">
              </div>
            </div>
            <p class="mt-2 text-muted small text-center">Construcción artesanal y cúpula terminada</p>
        </div>
      </div>
    </div>

    <div class="row mb-5">
      <div class="col-md-6 mb-4">
        <h4>El telescopio (1988-1994)</h4>
        <p class="text-muted">En 1988 se firmó un acuerdo entre la Facultad de Ciencias Exactas, la ASA y la Facultad de Ciencias Astronómicas y Geofísicas de la Universidad Nacional de La Plata para construir un telescopio newtoniano de entre 400 y 600 mm de diámetro. El proyecto culminó en 1994 con la inauguración del Telescopio Reflector Newtoniano de 500 mm de diámetro, f/6, que continúa siendo el instrumento principal del Observatorio.</p>
        <div class="mt-3 text-center">
            <img src="{{ asset('img/observatorio/historia-telescopio.jpg') }}" alt="Telescopio reflector newtoniano" class="img-fluid rounded border shadow-sm w-100">
            <p class="mt-2 text-muted small">Telescopio reflector newtoniano de 500 mm, f/6</p>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <h4>Reconocimiento Institucional (2023)</h4>
        <p class="text-muted">El 26 de octubre de 2023, el Consejo Superior de la Universidad Nacional de Salta resolvió formalmente la creación del Observatorio Astronómico como establecimiento dependiente del Departamento de Física de la Facultad de Ciencias Exactas, imponiéndole el nombre de Profesor Elvio Edgardo Alanís, en homenaje a su fundador.</p>
        <div class="mt-3 text-center">
            <img src="{{ asset('img/observatorio/historia-nombre.jpg') }}" alt="Imposición del nombre" class="img-fluid rounded border shadow-sm w-100">
            <p class="mt-2 text-muted small">Imposición del nombre al Observatorio</p>
        </div>
      </div>
    </div>

    <hr style="border-color: #cbd5e1;">

    <div class="row mt-5 text-center">
      <div class="col-12">
        <h4 class="mb-4">El Observatorio hoy</h4>
        <p class="text-muted" style="max-width: 900px; margin: 0 auto; line-height: 1.8; font-size: 1.05rem;">
          Cuatro décadas después de aquella primera reunión, el Observatorio se ha consolidado como un centro de referencia regional para la promoción, divulgación y desarrollo de la educación en astronomía en el noroeste argentino, sosteniendo la doble misión con la que nació en 1985: fomentar la investigación científica y acercar el conocimiento del cielo a la sociedad salteña de todas las edades, con especial atención a los estudiantes de nivel medio.
        </p>
        <div class="mt-4">
            <a href="https://revistas.unc.edu.ar/index.php/revistaEF/article/view/50844" target="_blank" class="btn-outline-sober"><i class="fas fa-external-link-alt mr-2"></i> Leer publicación académica (Bugiolachio, Domenichini y Hoyos, 2025)</a>
        </div>
      </div>
    </div>
  </div>
@endsection
