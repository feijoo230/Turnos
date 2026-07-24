@extends('layouts.panel-abm')

@section('title', 'MI PERFIL DE USUARIO')
@section('subtitle', 'Gestión de información personal y dependencias asignadas')

@section('body')
<div class="row">
  <!-- Tarjeta Resumen del Perfil -->
  <div class="col-md-4 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_content text-center" style="padding: 20px 15px;">
        <div class="profile-avatar" style="margin-bottom: 15px;">
          <i class="fa fa-user-circle text-primary" style="font-size: 5rem; color: #1e3c72;"></i>
        </div>
        <h3 class="font-weight-bold text-dark" style="margin-top: 10px; margin-bottom: 5px; font-weight: 700;">{{ Auth::user()->name }}</h3>
        <p class="text-muted small" style="margin-bottom: 15px; color: #64748b;"><i class="fa fa-envelope" style="margin-right: 5px;"></i> {{ Auth::user()->email }}</p>

        <div style="margin-bottom: 20px;">
          @foreach(Auth::user()->roles as $role)
            <span class="label label-primary" style="font-size: 11px; padding: 5px 12px; border-radius: 12px; display: inline-block; margin: 2px; background-color: #1e3c72;">
              <i class="fa fa-shield"></i> {{ $role->name }}
            </span>
          @endforeach
        </div>

        <div class="ln_solid" style="margin: 15px 0;"></div>

        <!-- Dependencia Origen -->
        <div class="text-left pull-left" style="width: 100%; text-align: left; margin-bottom: 15px;">
          <h5 style="font-size: 12px; text-transform: uppercase; font-weight: 700; color: #64748b; margin-bottom: 5px; letter-spacing: 0.5px;">
            <i class="fa fa-building text-primary" style="color: #1e3c72; margin-right: 6px;"></i> Dependencia Origen
          </h5>
          <div style="font-size: 14px; font-weight: 700; color: #1e293b; padding-left: 20px;">
            {{ optional(Auth::user()->dependencias_origen()->first())->name ?? 'No asignada' }}
          </div>
        </div>

        <!-- Dependencias Administradas -->
        <div class="text-left pull-left" style="width: 100%; text-align: left; margin-top: 10px;">
          <h5 style="font-size: 12px; text-transform: uppercase; font-weight: 700; color: #64748b; margin-bottom: 8px; letter-spacing: 0.5px;">
            <i class="fa fa-sitemap text-info" style="color: #11998e; margin-right: 6px;"></i> Dependencias Administradas
          </h5>
          <ul class="list-unstyled" style="padding-left: 20px; margin-bottom: 0;">
            @forelse(Auth::user()->dependencias as $dependencia)
              <li style="padding: 4px 0; font-size: 13px; color: #334155;">
                <i class="fa fa-check-circle text-success" style="color: #10b981; margin-right: 6px;"></i> {{ $dependencia->nombre }}
              </li>
            @empty
              <li class="text-muted small" style="font-size: 12px; color: #94a3b8;">Sin dependencias asignadas</li>
            @endforelse
          </ul>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>

  <!-- Formulario de Edición de Perfil -->
  <div class="col-md-8 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><i class="fa fa-edit text-primary" style="color: #1e3c72; margin-right: 6px;"></i> Actualizar Mis Datos</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @include('parts.message')
        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px;">
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::model($usuario, ['url' => ['usuarios.update_perfil'], 'method' => 'post', 'class' => 'form-horizontal form-label-left']) !!}
          {{ Form::hidden('id', $usuario['id']) }}

          <div class="form-group" style="margin-bottom: 20px;">
            <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;">
              <i class="fa fa-user" style="color: #64748b; margin-right: 5px;"></i> Nombre Completo:
            </label>
            <div class="col-md-8 col-sm-9 col-xs-12">
              {!! Form::text('name', null, ['class' => 'form-control', 'required' => true, 'style' => 'border-radius: 6px;']) !!}
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 20px;">
            <label for="email" class="control-label col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;">
              <i class="fa fa-envelope" style="color: #64748b; margin-right: 5px;"></i> Correo Electrónico:
            </label>
            <div class="col-md-8 col-sm-9 col-xs-12">
              {!! Form::text('email', null, ['class' => 'form-control', 'required' => true, 'style' => 'border-radius: 6px;']) !!}
            </div>
          </div>

          <div class="ln_solid" style="margin: 25px 0 20px;"></div>

          <div class="form-group">
            <div class="col-md-11 text-right">
              <a href="{{ url('/home') }}" class="btn btn-default" style="border-radius: 6px; margin-right: 5px;">
                <i class="fa fa-times"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-success" style="border-radius: 6px; background-color: #10b981; border-color: #10b981;">
                <i class="fa fa-save"></i> Guardar Cambios
              </button>
            </div>
          </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@stop