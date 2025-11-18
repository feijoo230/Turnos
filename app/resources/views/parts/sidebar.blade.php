<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>MENÚ PRINCIPAL</h3>
    <ul class="nav side-menu">
      <li><a href="{{ url('/') }}"><i class="fa fa-calendar"></i> Nuevo Turno</a></li>
      @can('turnosgestion')
        <li><a href="{{ url('turnos_admin') }}"><i class="fa fa-bullhorn" aria-hidden="true"></i>Gestión de turnos</a></li>
      @endcan
    </ul>
    <ul class="nav side-menu">
      @can('reportes')
      <li><a><i class="fa fa-file-text" aria-hidden="true"></i>Reportes <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('reporte.operador') }}">Reporte de operadores</a></li>
        </ul>
      </li>
      @endcan
    </ul>
    <ul class="nav side-menu">
      @can('isadmin')
      <li><a><i class="fa fa-cog"></i> Administración <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('permisos') }}">Permisos</a></li>
          <li><a href="{{ url('roles') }}">Roles</a></li>
          <li><a href="{{ url('rolespermisos') }}">Roles-Permisos</a></li>
          <li><a href="{{ url('usuarios') }}">Usuarios</a></li>
          <li><a href="{{ url('dependencias') }}">Dependencias</a></li>
          <li><a href="{{ url('mesashabilitadas') }}">Mesas Habilitadas</a></li>
          <li><a href="{{ url('feriados') }}">Feriados</a></li>
        </ul>
      </li>
      @endcan
    </ul>
    <ul class="nav side-menu">
      @can('isoperador')
      <li><a><i class="fa fa-cog"></i> Operadores <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('tramitesdependencias') }}">Tramites por dependencias</a></li>
          <li><a href="{{ url('turnostramites') }}">Turnos por Tramites</a></li>

          <li><a href="{{ url('turnosdependenciasreservas') }}">Reservas de turnos</a></li>
        </ul>
      </li>
      @endcan
    </ul>
  </div>
</div>
<!-- /sidebar menu -->
