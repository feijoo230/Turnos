@extends('frontend.layouts.app')

@section('content')
  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">Nuestro Grupo</span>
      <h2 class="page-title">Equipo de Trabajo</h2>
      <p class="text-muted lead" style="max-width: 600px; margin: 0 auto;">Docentes responsables, especialistas técnicos, gestores académicos y estudiantes colaboradores que hacen posible el funcionamiento del Observatorio.</p>
    </div>
  </div>

  <div class="container py-5">
    
    <div class="text-center mb-5">
        <h3 class="text-primary mb-4" style="font-weight: 700;"><i class="fas fa-chalkboard-teacher mr-2"></i> Docentes Responsables</h3>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-top: 4px solid var(--accent-blue);">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-4x" style="color: #cbd5e1;"></i>
                        </div>
                        <h4 class="card-title text-primary">Hugo Sebastián Zerpa</h4>
                        <p class="text-muted mb-0">Dirección y Gestión Institucional</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-top: 4px solid var(--accent-blue);">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-4x" style="color: #cbd5e1;"></i>
                        </div>
                        <h4 class="card-title text-primary">Carlos Martínez</h4>
                        <p class="text-muted mb-0">Gestión Técnica e Investigación</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr style="border-color: #cbd5e1; margin: 60px 0;">

    <div class="text-center">
        <h3 class="text-primary mb-4" style="font-weight: 700;"><i class="fas fa-hands-helping mr-2"></i> Colaboradores</h3>
        <p class="text-muted lead mb-5">Gestión, Técnico, Didáctico, Comunicación y Académico</p>
        
        <div class="d-flex flex-wrap justify-content-center" style="gap: 15px; max-width: 900px; margin: 0 auto;">
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Gómez, María José</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Martin, Marcos</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Cruz, Bruno</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Calderón, Débora</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Zerpa, Fabián</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Maldonado, Cristian</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Mirabal, Micaela</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Díaz, Ana Gabriela</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Ibarra, Janet</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Enrique, Candela</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Flores, Camila Anahí</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Rodríguez, Alfio Antonio</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Cabrera, Julián Nicolás</span>
            <span class="badge shadow-sm p-3" style="font-size: 1.05rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155;">Pachado, Agustina</span>
        </div>
    </div>

  </div>
@endsection
