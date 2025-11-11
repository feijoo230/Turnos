@extends('layouts.login')

@section('content')
<div class="login_wrapper">
  <div class="animate form login_form">
    <div style="background: #FFFFFF; padding: 0 20px 0 20px;-webkit-box-shadow: 2px 2px 5px #999;
-moz-box-shadow: 2px 2px 5px #999;">
      <section class="login_content" style="padding-top: 10px;">
        <div>
          <h2>{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</h2>
          <div>
              <h2 style="color: #008DDF;">RECUPERAR CLAVE</h1>
          </div>
          <div>
              <i class="fa fa-user-circle fa-4x" aria-hidden="true"></i>
          </div>
          <form method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}
            <div>
              <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus />
            </div>
            <div>

              <button type="submit" class="btn btn-primary">
                  Email Blanqueo de Clave
              </button>
              <a class="reset_pass" href="{{ route('login') }}" style="color: #008DDF;">Volver</a>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <div class="clearfix"></div>
              <br />

              <div>
                <p>{{ config('constants.COPYRIGHT', 'Laravel') }} {{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</p>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection