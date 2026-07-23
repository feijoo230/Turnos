<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>MENÚ PRINCIPAL</h3>
    <ul class="nav side-menu">
      @hasanyrole('ADMINISTRADOR|OPERADOR')
      <li><a href="{{ url('/home') }}"><i class="fa fa-tachometer"></i> Panel de Control</a></li>
      @endhasanyrole
      <li><a href="{{ url('/') }}"><i class="fa fa-calendar"></i> Nuevo Turno</a></li>
    </ul>
    <ul class="nav side-menu">
      @hasrole('ADMINISTRADOR')
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
      @endhasrole
    </ul>
    <ul class="nav side-menu">
      @hasanyrole('ADMINISTRADOR|OPERADOR')
      <li><a><i class="fa fa-cog"></i> Operadores <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ url('tramitesdependencias') }}">Tramites por dependencias</a></li>
          <li><a href="{{ url('proyectos-extension') }}">Proyectos de Extensión</a></li>
          <li><a href="{{ url('tipos-evento') }}">Tipos de Evento</a></li>
          <li><a href="{{ url('turnostramites') }}">Turnos por Tramites</a></li>

          <li><a href="{{ url('turnosdependenciasreservas') }}">Reservas de turnos</a></li>
        </ul>
      </li>
      @endhasanyrole
    </ul>
  </div>
</div>
<!-- /sidebar menu -->
