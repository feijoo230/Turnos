<div class="clearfix"></div> 
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">RESUMEN</div>
  <div class="panel-body">
	<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-horizontal form-label-left">
			 	<div class="form-group">
				  {!! Form::label('promedio', 'Tiempo promedio :', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
				  <div class="col-md-6 col-sm-6 col-xs-12">
				  	 {!! Form::text('promedio', $promedio , ['class' => 'form-control col-md-7 col-xs-12', 'readonly' => 'TRUE']) !!}
				  </div>
				</div>
				<div class="form-group">
				  {!! Form::label('cantidad', 'Cantidad atención :', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
				  <div class="col-md-6 col-sm-6 col-xs-12">
				  	{!! Form::text('cantidad', $cantidad_atenciones , ['class' => 'form-control col-md-7 col-xs-12', 'readonly' => 'TRUE']) !!}
				  </div>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
<div class="clearfix"></div>
