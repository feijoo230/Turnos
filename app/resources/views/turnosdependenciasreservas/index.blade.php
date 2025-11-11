@extends('layouts.panel-abm')

@section('title', 'RESERVAS DE TRUNOS POR DEPENDENCIAS')
@section('subtitle', 'Administración de las reservas de turnos de dependencias.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
              {!! Form::open(['route' => 'turnosdependenciasreservas.index', 'method' => 'get']) !!}
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="inputEmail4">Código turno</label>
                    {!! Form::text('codigo_turno', (isset($codigo_turno)? $codigo_turno : null), ['class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Código turno']) !!}
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputEmail4">Fecha turno</label>
                    {!! Form::text('fecha_turno', (isset($fecha_turno)? $fecha_turno : null), ['class' => 'form-control', 'placeholder' => 'dd/mm/aaaa']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    {{ Form::submit('Buscar', array('class' => 'btn btn-primary pull-right')) }}
              </form>
              {!! Form::open(['route' => 'turnosdependenciasreservas.print', 'method' => 'post']) !!}
                {!! Form::hidden('codigo_turno', (isset($codigo_turno)? $codigo_turno : null)) !!}
                {!! Form::hidden('fecha_turno', (isset($fecha_turno)? $fecha_turno : null)) !!}
                {{ Form::submit('Imprimir', array('class' => 'btn btn-secundary pull-right')) }}
              </form>
                  </div>
                </div>
              <br>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('turnosdependenciasreservas.table')
            <div class="text-center">{{ $reservas->appends(['codigo_turno' => $codigo_turno, 'fecha_turno' => $fecha_turno])->links() }}</div>
          </div>
        </div>
      </div>
    </div>
@stop