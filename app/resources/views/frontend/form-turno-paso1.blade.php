@extends('layouts.frontend')
@section('content')

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col-md-11">
			<div class="card box-turno box-paso1">
				<div class="card-body">
					<div class="encabezado">
					  	<ul class="list-group list-group-horizontal-lg" >
							<li class="list-group-item item-active text-center"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#1</span></span> Dirección</li>
							<li class="list-group-item"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#2</span></span> Fecha y hora</li>
							<li class="list-group-item"><span class="fa-stack"><span class="far fa-circle fa-2x"></span><span class="fa-stack-1x">#3</span></span> Confirmación</li>
						</ul>
						<p class="titulo">SELECCIONE UNA DIRECCIÓN Y TRÁMITE.</p>
					</div>
 
					{!! Form::open(['route' => 'tramite.paso2', 'files'=>'true', 'class' => 'form-horizontal text-center', 'method' => 'get']) !!}
					
						<div id="app">
				          <direccion-tramite dep_select_id="{{$dependencia_id}}" ></direccion-tramite>
				        </div>
				</div>
				<div class="card-footer">
					<div style="text-align: right;">
						<button type="submit" class="btn btn-primary">Siguiente</button>
					</div>
				</div>
					</form>

			</div>
		</div>
	</div>
</div>

@stop