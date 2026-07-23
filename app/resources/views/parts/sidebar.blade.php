<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>MENÚ PRINCIPAL</h3>
    <ul class="nav side-menu">
      @hasanyrole('ADMINISTRADOR|OPERADOR')
      <li><a href="{{ url('/home') }}"><i class="fa fa-tachometer text-primary"></i> Panel de Control</a></li>
      @endhasanyrole
      <li><a href="{{ url('/') }}" target="_blank"><i class="fa fa-external-link text-info"></i> Portal Solicitud Turnos</a></li>
    </ul>

    @hasanyrole('ADMINISTRADOR|OPERADOR')
    <h3>GESTIÓN DE OPERACIONES</h3>
    <ul class="nav side-menu">
      <li><a><i class="fa fa-calendar-check-o text-success"></i> Atención de Turnos <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('turnosdependenciasreservas') }}"><i class="fa fa-list-alt"></i> Reservas de Turnos</a></li>
          <li><a href="{{ url('turnos_admin') }}"><i class="fa fa-bullhorn"></i> Llamador y Atención</a></li>
          <li><a href="{{ url('turnostramites') }}"><i class="fa fa-clock-o"></i> Horarios de Atención</a></li>
        </ul>
      </li>

      <li><a><i class="fa fa-briefcase text-warning"></i> Trámites y Servicios <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('tramitesdependencias') }}"><i class="fa fa-building-o"></i> Trámites por Dependencia</a></li>
          <li><a href="{{ url('tramites') }}"><i class="fa fa-file-text-o"></i> Trámites Digitales</a></li>
          <li><a href="{{ url('proyectos-extension') }}"><i class="fa fa-folder-open-o"></i> Proyectos de Extensión</a></li>
          <li><a href="{{ url('tipos-evento') }}"><i class="fa fa-tags"></i> Tipos de Eventos</a></li>
        </ul>
      </li>

      <li><a><i class="fa fa-bar-chart text-info"></i> Reportes y Métricas <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('estadisticas.index') }}"><i class="fa fa-area-chart"></i> Estadísticas Generales</a></li>
          <li><a href="{{ url('reporte.operador') }}"><i class="fa fa-user-circle-o"></i> Reportes por Operador</a></li>
        </ul>
      </li>
    </ul>
    @endhasanyrole

    @hasrole('ADMINISTRADOR')
    <h3>ADMINISTRACIÓN DEL SISTEMA</h3>
    <ul class="nav side-menu">
      <li><a><i class="fa fa-cogs text-danger"></i> Configuración General <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('usuarios') }}"><i class="fa fa-users"></i> Usuarios</a></li>
          <li><a href="{{ url('roles') }}"><i class="fa fa-shield"></i> Roles</a></li>
          <li><a href="{{ url('permisos') }}"><i class="fa fa-key"></i> Permisos</a></li>
          <li><a href="{{ url('rolespermisos') }}"><i class="fa fa-lock"></i> Asignación Roles-Permisos</a></li>
          <li><a href="{{ url('dependencias') }}"><i class="fa fa-sitemap"></i> Dependencias</a></li>
          <li><a href="{{ url('mesashabilitadas') }}"><i class="fa fa-desktop"></i> Mesas Habilitadas</a></li>
          <li><a href="{{ url('feriados') }}"><i class="fa fa-calendar-times-o"></i> Feriados</a></li>
        </ul>
      </li>
    </ul>
    @endhasrole
  </div>
</div>
<!-- /sidebar menu -->
