@extends('layouts.panel-abm')

@section('title', 'MI PERFIL')
@section('subtitle', 'Editar mis datos de usuario.')
@section('body')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            @include('parts.message')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="clearfix"></div>
            @endif
            <div class="ln_solid"></div>
              {!! Form::model($usuario, ['url' => ['usuarios.update_perfil'], 'method' => 'post', 'class' => 'form-horizontal form-label-left']) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Nombre:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::text('name', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
                  </div>
                </div>
                <div class="form-group">
                    {!! Form::label('email', 'Email:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::text('email', null, ['class' => 'form-control col-md-7 col-xs-12']) !!}
                  </div>
                </div>
                <div class="form-group">
                    {!! Form::label('dependencia_origen', 'Dependencia Origen:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <p style="padding-top: 8px;">{{ Auth::user()->dependencias_origen()->first()->name }}</p>
                    </div>
                </div>
                <div class="form-group">
                  {!! Form::label('dependencias_administradas', 'Dependencias para Administrar:', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                  <div style="padding-top: 8px;" class="col-md-6 col-sm-6 col-xs-12">
                    @foreach(Auth::user()->dependencias AS $dependencia)
                      <span >{{ $dependencia->nombre }}</span>
                      <br>
                    @endforeach
                  </div>
                </div>
                {{ Form::hidden('id', $usuario['id']) }}
                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <button onclick="location.href='{!! route('usuarios.index') !!}'" class="btn btn-primary  pull-right" type="button">Cancelar</button>
                    <button type="submit" class="btn btn-success  pull-right">Guardar</button>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
@stop