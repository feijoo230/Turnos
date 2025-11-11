@extends('layouts.frontend')
@section('content')
<div id="page-content">
  <div class="container text-center">
    <div class="jumbotron">
      <h1>INGRESE SU DNI</h1>
      <p>"Sin puntos".</p>
      </br>
		{!! Form::open(['url' => 'turnos.buscar', 'class' => 'form-horizontal text-center']) !!}
			<div class="form-group">
				<div class="row">
				  <div class="text-right col-md-3 col-md-offset-3">{!! Form::label('dni', 'DNI:', array('class' => 'control-label label-lg')) !!}</div>
				  <div class="col-md-3">{!! Form::text('dni', null, ['class' => 'form-control input-lg']) !!}</div>
				</div>
			</div>
			<br>
			<div class="ln_solid"></div>
			<div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<button type="submit" class="btn-lg btn-success pull-center" style="width: 150px">Aceptar</button>
				  	<button onclick="location.href='{!! url('turnos') !!}'" class="btn-lg btn-primary pull-center" style="width: 150px" type="button">Cancelar</button>
				</div>
			</div>
		</form>
    </div>
  </div>
</div>
@stop