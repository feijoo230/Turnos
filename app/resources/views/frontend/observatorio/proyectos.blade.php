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
      <!-- Proyecto 1 -->
      <div class="col-lg-6 mb-5">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-img-top border-bottom text-center bg-white" style="border-color: #e2e8f0;">
            <img src="{{ asset('img/observatorio/proyecto-cielo-comun.jpg') }}" alt="Un cielo en común" class="img-fluid" style="width: 100%;">
          </div>
          <div class="card-body p-4">
            <span class="badge badge-info mb-2">2021</span>
            <h4 class="card-title text-primary">Un cielo en común</h4>
            <h6 class="text-muted mb-3">Astronomía cultural en Tonco</h6>
            <p class="card-text text-muted">El proyecto tuvo como objetivo acercar un telescopio a la comunidad educativa de Tonco, un paraje cercano al Parque Nacional Los Cardones. Pese a las demoras impuestas por la pandemia y el contexto inflacionario, en noviembre de 2021 se concretó la donación de un telescopio refractor al Colegio Secundario Rural de la zona.</p>
          </div>
        </div>
      </div>

      <!-- Proyecto 2 -->
      <div class="col-lg-6 mb-5">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-img-top border-bottom text-center bg-white" style="border-color: #e2e8f0;">
            <img src="{{ asset('img/observatorio/proyecto-base-planetas.jpg') }}" alt="La base de los planetas" class="img-fluid" style="width: 100%;">
          </div>
          <div class="card-body p-4">
            <span class="badge badge-info mb-2">2023</span>
            <h4 class="card-title text-primary">La base de los Planetas</h4>
            <h6 class="text-muted mb-3">Astronomía cultural y capacitación docente en Tonco</h6>
            <p class="card-text text-muted">Este proyecto dio continuidad al vínculo con Tonco, avanzando en la construcción del Sendero de los Planetas, un sistema solar a escala entre los cerros. Se construyó la base y columna del Sol y se trazaron los senderos hacia los primeros planetas, completando el trabajo con jornadas de capacitación.</p>
          </div>
        </div>
      </div>

      <!-- Proyecto 3 -->
      <div class="col-lg-6 mb-5">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-img-top border-bottom text-center bg-white" style="border-color: #e2e8f0;">
            <img src="{{ asset('img/observatorio/proyecto-aparente-real.jpg') }}" alt="Lo aparente y lo real" class="img-fluid" style="width: 100%;">
          </div>
          <div class="card-body p-4">
            <span class="badge badge-info mb-2">2023</span>
            <h4 class="card-title text-primary">Lo aparente y lo real</h4>
            <h6 class="text-muted mb-3">Astronomía de posición y Didáctica de la Astronomía</h6>
            <p class="card-text text-muted">Un curso de extensión destinado a docentes y estudiantes de profesorado, desarrollado en cuatro jornadas intensivas: desde la construcción de un "Aula Celeste" con globo terráqueo paralelo, hasta el uso del simulador Stellarium y una jornada de cierre con observación astronómica.</p>
          </div>
        </div>
      </div>

      <!-- Proyecto 4 -->
      <div class="col-lg-6 mb-5">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-img-top border-bottom text-center bg-white" style="border-color: #e2e8f0;">
            <img src="{{ asset('img/observatorio/proyecto-primera-fisica.jpg') }}" alt="Mi primera física" class="img-fluid" style="width: 100%;">
          </div>
          <div class="card-body p-4">
            <span class="badge badge-info mb-2">2025</span>
            <h4 class="card-title text-primary">Mi primera Física</h4>
            <h6 class="text-muted mb-3">Talleres prácticos con simuladores e instrumentos</h6>
            <p class="card-text text-muted">Un proyecto pensado para estudiantes de profesorado de educación primaria, que acercó nociones de óptica geométrica y astronomía de posición a futuros docentes de dos institutos de la provincia, explorando cómo estos contenidos pueden adaptarse al aula de nivel primario.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
