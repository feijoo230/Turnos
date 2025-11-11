@extends('layouts.frontend')
@section('content')
<div id="page-content">
  <div class="container text-center">
    <div class="jumbotron">
      <h1>SOLICITAR TURNO</h1>
      <p>Presionar botón siguiente para empezar ...</p>
      </br>
      <p><a class="btn btn-primary btn-lg" href="{!! url('turnos.dni') !!}" role="button">Siguiente</a></p>
    </div>
  </div>
</div>
@stop