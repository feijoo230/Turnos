@extends('layouts.frontend')
@section('content')

<div class="row justify-content-center">
	<div class="col-lg-8 col-xl-7">
		<div class="card box-turno text-center">
			<div class="card-body p-4">
				<div class="rounded-circle bg-light-success text-success mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #dcfce7;">
					<i class="fas fa-check-circle fa-3x text-success"></i>
				</div>
				<h3 class="font-weight-bold text-dark mb-2">¡Su Turno Fue Registrado con Éxito!</h3>
				<p class="text-muted lead mb-4">Espere en la sala de atención que muy pronto será llamado por nuestros operadores.</p>
				<div>
					<a href="{!! url('turnos') !!}" class="btn btn-gradient-primary btn-lg font-weight-bold px-4 shadow-sm" style="border-radius: 10px;">
						<i class="fas fa-home mr-1"></i> Volver al Inicio
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
@stop