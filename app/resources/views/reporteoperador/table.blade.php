<div class="panel panel-default">
  <div class="panel-heading">LISTADO TURNOS</div>
    <div class="panel-body">
      {!! Form::open(['url' => ['listado.operadores'], 'class' => 'form-horizontal form-label-left', 'id' => 'form-filtros']) !!}
      <div class="form-group">
        {!! Form::label('operador_id', 'Operador:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
          {{ Form::select('operador_id', [null => 'TODOS...'] + $operadores_filtro, ((isset($operador_id))? $operador_id : NULL) , ['class' => 'form-control']) }}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('fecha_desde', 'Fecha desde:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
          {!! Form::date('fecha_desde', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('fecha_hasta', 'Fecha hasta:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
          {!! Form::date('fecha_hasta', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('es_afiliado', '¿Es afiliado?', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="checkbox" name="es_afiliado" value="TRUE" {{ (isset($es_afiliado) and $es_afiliado == TRUE)? 'checked' : '' }}>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <a id="listado-imprimir" class="btn btn-primary  pull-right"><i class="fa fa-print fa-lg"></i> Imprimir</a>
          <button type="submit" id="button-submit" class="btn btn-success pull-right">Filtrar</button>
        </div>
      </div>
    </form>
    <div class="ln_solid"></div>
    <div class="clearfix"></div> 
    @if ($operador_id <> 0)
      @include('reporteoperador.panel-resumen')
    @endif 
    <table id="datatable" class="table table-striped table-bordered" style="font-size: 10pt;">
      <thead>
        <tr>
          <th>Fecha Hora</th>
          <th>Nro. DNI</th>
          <th>Cliente</th>
          <th>Fecha Hora ingreso</th>
          <th>Fecha Hora egreso</th>
          <th>Tiempo atención (min)</th>
          <th>Operador</th>
          <th>¿Es afiliado?</th>
        </tr>
      </thead>
      <tbody>
      @foreach($turnos as $turno)
        <tr>
          <td>{!! $turno->created_at->format('d/m/Y H:i') !!}</td>
          <td>{!! $turno->cliente->persona->nro_documento !!}</td>
          <td>{!! $turno->cliente->persona->apellido !!}, {!! $turno->cliente->persona->nombre !!}</td>
          <td>{!! (isset($turno->fecha_hora_ingreso))? $turno->fecha_hora_ingreso->format('d/m/Y  H:i') :'' !!}</td>
          <td>{!! (isset($turno->fecha_hora_egreso))? $turno->fecha_hora_egreso->format('d/m/Y  H:i') :'' !!}</td>
          <td>{!! $turno->tiempo_atencion !!}</td>
          <td>{!! (isset($turno->operador->name))? $turno->operador->name :'' !!}</td>
          <td width="15">
            @if ($turno->cliente->es_afiliado == TRUE)
              SI
            @else
              NO
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@section('script')
<script>    
  // jQuery code to show the working of this method 
  $(document).ready(function() { 

    $("#listado-imprimir").click(function() { 
        $('#form-filtros').attr('action', "{!! url('listado.imprimir_listado') !!}");
        $("#form-filtros").submit();
    });
    
    $("#button-submit").click(function() { 
        $('#form-filtros').attr('action', "{!! url('listado.operadores') !!}");
        $("#form-filtros").submit();
    });

  }); 
</script> 
@stop