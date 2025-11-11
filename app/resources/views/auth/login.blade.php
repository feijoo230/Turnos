@extends('layouts.login')

@section('content')
<div class="login_wrapper">
  <div class="animate form login_form">
    <div style="background: #FFFFFF; padding: 0 20px 0 20px;-webkit-box-shadow: 2px 2px 5px #999;
-moz-box-shadow: 2px 2px 5px #999;">
      @if ($errors->any())
          </br>
          <div class="alert alert-danger">
              <ul>
                  <li>Por favor verifique los datos ingresados y si tiene los permisos correspondientes.</li>
              </ul>
          </div>
          <div class="clearfix"></div>
      @endif
      <section class="login_content" style="padding-top: 10px;">
        <div>
          <h2 style="color: #008DDF;">{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</h2>
          <div>
              <i class="fa fa-user-circle fa-4x" aria-hidden="true"></i>
          </div>
          <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div>
              <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus />
            </div>
            <div>
              <input id="password" name="password" type="password" class="form-control" placeholder="Password" required />
            </div>
            
            <div>
              <button type="submit" class="btn btn-default submit" style="background: #008DDF; color: #FFFFFF">Entrar</button>
              <!--
              <a class="reset_pass" href="{{ url('/password/reset') }}" style="color: #008DDF;">¿Olvide mi clave?</a>
              -->
            </div>
            
            <div class="clearfix"></div>

            <div class="separator">
              <!--
              <p class="change_link">
                <a href="#signup" class="to_register"> ¿Desea solicitar una cuenta? </a>
              </p>
              -->
              <div class="clearfix"></div>
              <br />

              <div>
                <p>{{ config('constants.COPYRIGHT', 'Laravel') }}</p>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection