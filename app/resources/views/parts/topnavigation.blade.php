<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" >
            <h5 class="text-center">
              <i class="fa fa-user-circle fa-3x"></i> @if(Auth::check()) {{Auth::user()->name }} @endif <span class=" fa fa-angle-down"></span>
              <br>
              <span class="small text-center">@if(Auth::check() && Auth::user()->Roles->first()) {{Auth::user()->Roles->first()->name }} @endif</span>
            </h5>
          </a>
            <ul class="dropdown-menu" role="menu">
                <li class="text-center">
                  <span class="font-weight-bold"><u>DEPENDENCIA ORIGEN</u></span>
                  <br>
                  @if(Auth::check() && Auth::user()->dependencias_origen()->first())
                  {{ Auth::user()->dependencias_origen()->first()->name }}
                  @endif
                  {{-- @foreach(Auth::user()->dependencias AS $dependencia)
                    <span class="small">{{ $dependencia->nombre }}</span>
                    <br>
                  @endforeach --}}
                </li>
                <li>
                    <a href="{{ url('usuarios.mi_perfil') }}"><i class="fa fa-address-card" aria-hidden="true"></i> Mi perfil</a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i> Salir
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