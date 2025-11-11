<!-- Name Field -->
<div class="row">
    <div class="col-md-6">
     	<div class="form-group">
		    {!! Form::label('asunto', 'Asunto:') !!}
		    <p>{!! $tramite->asunto !!}</p>
		</div>
    </div>
    <div class="col-md-6">
		<div class="form-group">
		    {!! Form::label('remitente', 'Causante:') !!}
		    <p>{!! $tramite->remitente !!}</p>
		</div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
		<div class="form-group">
		    {!! Form::label('domicilio', 'Domicilio:') !!}
		    <p>{!! $tramite->domicilio !!}</p>
		</div>
    </div>
    <div class="col-md-6">
		<div class="form-group">
		    {!! Form::label('telefono', 'Teléfono:') !!}
		    <p>{!! $tramite->telefono !!}</p>
		</div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
		<div class="form-group">
		    {!! Form::label('correo', 'Correo:') !!}
		    <p>{!! $tramite->correo !!}</p>
		</div>
    </div>
    <div class="col-md-6">
		<div class="form-group">
		    {!! Form::label('dependencia_id', 'Dependencia:') !!}
		    <p>{!! $tramite->dependencia->nombre !!}</p>
		</div>
    </div>
</div>
<h5>DOCUMENTOS DIGITALES (p/descargar)</h5>
<ul>
	@foreach($tramite->documentos as $documento)
	 	<li><a href="" style="">{!! $documento->vcnombre !!}</a></li>
	@endforeach
</ul>
