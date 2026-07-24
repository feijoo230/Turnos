@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-9">
		<div class="card box-turno">
			<div class="card-body p-4">
				<!-- Stepper Wizard -->
				<div class="wizard-steps mb-4">
					<div class="wizard-step-item active">
						<div class="wizard-step-circle"><i class="fas fa-university"></i></div>
						<span class="wizard-step-title">1. Selección</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle">2</div>
						<span class="wizard-step-title">2. Fecha y Hora</span>
					</div>
					<div class="wizard-step-item">
						<div class="wizard-step-circle">3</div>
						<span class="wizard-step-title">3. Confirmación</span>
					</div>
				</div>

				<div class="text-center mb-4">
					<h4 class="font-weight-bold text-dark mb-1"><i class="fas fa-sitemap text-primary mr-2"></i>Seleccione la Oficina y el Trámite</h4>
					<p class="text-muted small mb-0">Elija la dependencia académica o administrativa donde desea ser atendido.</p>
				</div>

				{!! Form::open(['route' => 'tramite.paso2', 'files'=>'true', 'class' => 'form-horizontal text-center', 'method' => 'get']) !!}
					<div id="app" class="my-3">
						<direccion-tramite dep_select_id="{{$dependencia_id}}"></direccion-tramite>
					</div>

					<hr class="my-4">

					<div class="d-flex justify-content-between align-items-center">
						<a href="{{ url('/') }}" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 10px; padding: 8px 20px;">
							<i class="fas fa-arrow-left mr-1"></i> Volver
						</a>
						<button type="submit" class="btn btn-gradient-primary">
							Siguiente Paso <i class="fas fa-arrow-right ml-1"></i>
						</button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop