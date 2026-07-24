<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu" style="background: #ffffff; border-bottom: 1px solid #e6e9ed; padding: 0 15px; min-height: 56px;">
    <nav style="min-height: 56px;">
      <div class="nav toggle" style="float: left; padding-top: 14px; margin-right: 15px;">
        <a id="menu_toggle" style="cursor: pointer; font-size: 1.3rem; color: #2A3F54;"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right" style="margin: 0; float: right;">
        <li class="dropdown" style="padding-top: 8px; padding-bottom: 8px;">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="padding: 6px 14px; border-radius: 20px; background: #f8fafc; border: 1px solid #cbd5e1; text-decoration: none; display: inline-block;">
            <i class="fa fa-user-circle text-primary" style="font-size: 1.5rem; color: #1e3c72; vertical-align: middle; margin-right: 6px;"></i>
            <span style="color: #2A3F54; font-size: 14px; font-weight: 700; vertical-align: middle;">
              @if(Auth::check()) {{ Auth::user()->name }} @endif
            </span>
            @if(Auth::check() && Auth::user()->Roles->first())
              <span class="label label-primary" style="margin-left: 6px; font-size: 10px; padding: 3px 8px; border-radius: 10px; vertical-align: middle; background-color: #1e3c72;">
                {{ Auth::user()->Roles->first()->name }}
              </span>
            @endif
            <i class="fa fa-angle-down text-muted" style="margin-left: 6px; color: #64748b; vertical-align: middle;"></i>
          </a>

          <ul class="dropdown-menu dropdown-usermenu pull-right" role="menu" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 8px 25px rgba(0,0,0,0.1); padding: 8px 0; min-width: 230px;">
            @if(Auth::check() && Auth::user()->dependencias_origen()->first())
            <li style="padding: 6px 16px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
              <small style="font-size: 10px; text-transform: uppercase; font-weight: 700; color: #64748b; display: block;">
                <i class="fa fa-building text-primary" style="color: #1e3c72; margin-right: 4px;"></i> Dependencia Origen
              </small>
              <span style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-top: 2px;">
                {{ Auth::user()->dependencias_origen()->first()->name }}
              </span>
            </li>
            @endif

            <li>
              <a href="{{ url('usuarios.mi_perfil') }}" style="padding: 10px 16px; color: #334155; font-size: 13px;">
                <i class="fa fa-address-card text-primary pull-right" style="color: #1e3c72; margin-top: 2px;"></i> Mi Perfil
              </a>
            </li>
            <li class="divider" style="margin: 4px 0;"></li>
            <li>
              <a href="{{ url('/logout') }}"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                 style="padding: 10px 16px; color: #d9534f; font-size: 13px;">
                <i class="fa fa-sign-out text-danger pull-right" style="color: #d9534f; margin-top: 2px;"></i> Cerrar Sesión
              </a>

              <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->