@extends('layouts.app')

@section('content')
  <div class="jumbotron" style="margin-bottom: 0px;">
    <h1 class="text-center">Bienvenidos</h1>
    <p class="text-center">{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</p>
  </div>
@stop