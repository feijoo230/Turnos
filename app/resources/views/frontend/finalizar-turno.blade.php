@extends('layouts.frontend')
@section('content')
<div id="page-content">
  <div class="container text-center">
    <div class="jumbotron">
      <h1>SU TURNO FUE REGISTRADO CON EXITO.</h1>
      <p>Espere que muy pronto será atendido...</p>
      </br>
      <p><a class="btn btn-primary btn-lg" href="{!! url('turnos') !!}" role="button">Aceptar</a></p>
    </div>
  </div>
</div>
@stop