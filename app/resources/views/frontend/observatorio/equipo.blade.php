@extends('frontend.layouts.app')

@section('content')
  <style>
    .colab-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(245px, 1fr));
      gap: 16px;
      max-width: 1100px;
      margin: 0 auto;
    }
    .colab-card {
      background: #ffffff;
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 14px 18px;
      display: flex;
      align-items: center;
      gap: 14px;
      box-shadow: 0 2px 5px rgba(15, 23, 42, 0.03);
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      min-height: 72px;
    }
    .colab-card:hover {
      transform: translateY(-3px);
      border-color: #38bdf8;
      box-shadow: 0 10px 22px -4px rgba(2, 132, 199, 0.15);
    }
    .colab-card::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background: linear-gradient(to bottom, #38bdf8, #0284c7);
      opacity: 0;
      transition: opacity 0.2s ease;
    }
    .colab-card:hover::before {
      opacity: 1;
    }
    .colab-avatar {
      width: 42px;
      height: 42px;
      min-width: 42px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      color: #0284c7;
      border: 1px solid #bae6fd;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      transition: all 0.2s ease;
    }
    .colab-card:hover .colab-avatar {
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
      color: #ffffff;
      border-color: #0284c7;
    }
    .colab-info {
      flex: 1;
      min-width: 0;
      text-align: left;
    }
    .colab-nombre {
      font-weight: 600;
      font-size: 0.93rem;
      color: #1e293b;
      margin: 0;
      line-height: 1.3;
      display: block;
    }
    .colab-cargo {
      font-size: 0.8rem;
      color: #64748b;
      margin-top: 3px;
      line-height: 1.2;
      display: block;
    }
    .colab-tag {
      display: inline-block;
      font-size: 0.72rem;
      font-weight: 500;
      color: #0369a1;
      background-color: #f0f9ff;
      border-radius: 4px;
      padding: 1px 6px;
      margin-top: 4px;
    }
  </style>

  <div class="page-header text-center">
    <div class="container">
      <span class="section-tag">Nuestro Grupo</span>
      <h2 class="page-title">Equipo de Trabajo</h2>
      <p class="text-muted lead" style="max-width: 650px; margin: 0 auto;">Docentes responsables, especialistas técnicos, gestores académicos y estudiantes colaboradores que hacen posible el funcionamiento y las actividades del Observatorio.</p>
    </div>
  </div>

  <div class="container py-5">
    
    <!-- 1. Docentes Responsables -->
    <div class="text-center mb-5">
        <h3 class="text-primary mb-4" style="font-weight: 700;">
          <i class="fas fa-chalkboard-teacher mr-2 text-info"></i> Docentes Responsables
        </h3>
        <div class="row justify-content-center">
            @forelse($responsables as $resp)
                <div class="col-md-5 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0 h-100" style="border-top: 4px solid var(--accent-blue); border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                @if($resp->foto_url)
                                    <img src="{{ $resp->foto_url }}" alt="{{ $resp->nombre }}" class="rounded-circle shadow-sm" style="width: 88px; height: 88px; object-fit: cover; border: 3px solid #e0f2fe;">
                                @else
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 88px; height: 88px; background: #f1f5f9; color: #94a3b8; border: 3px solid #e2e8f0;">
                                        <i class="fas fa-user-tie fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="card-title text-primary mb-1" style="font-weight: 700; font-size: 1.25rem;">{{ $resp->nombre }}</h4>
                            <p class="text-muted mb-2 font-weight-medium" style="font-size: 0.95rem;">{{ $resp->cargo ?? 'Docente Responsable' }}</p>
                            @if($resp->area)
                                <span class="badge badge-light" style="font-size: 0.8rem; color: #475569; background-color: #f1f5f9; padding: 5px 10px; border-radius: 20px; font-weight: 500;">
                                  <i class="fas fa-bookmark text-info mr-1"></i> {{ $resp->area }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>No hay docentes responsables registrados actualmente.</p>
                </div>
            @endforelse
        </div>
    </div>

    <hr style="border-color: #e2e8f0; margin: 55px 0;">

    <!-- 2. Colaboradores -->
    <div class="text-center">
        <h3 class="text-primary mb-2" style="font-weight: 700;">
          <i class="fas fa-user-friends mr-2 text-info"></i> Colaboradores del Observatorio
        </h3>
        <p class="text-muted lead mb-5" style="font-size: 1.05rem;">
          Gestión, Técnico, Didáctico, Comunicación y Formación Académica
        </p>
        
        <div class="colab-grid">
            @forelse($colaboradores as $colab)
                <div class="colab-card" title="{{ $colab->nombre }}">
                    <div class="colab-avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="colab-info">
                        <span class="colab-nombre">{{ $colab->nombre }}</span>
                        @if($colab->cargo && !in_array(trim($colab->cargo), ['Colaborador / Estudiante', 'Colaborador', 'Estudiante']))
                            <span class="colab-cargo">{{ $colab->cargo }}</span>
                        @else
                            <span class="colab-tag">Colaborador</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-4">
                    <p>No hay colaboradores registrados actualmente.</p>
                </div>
            @endforelse
        </div>
    </div>

  </div>
@endsection
