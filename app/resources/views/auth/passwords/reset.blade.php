@extends('layouts.login')
@section('content')
<div class="login_wrapper">
  <div class="animate form login_form">
    <div style="background: #FFFFFF; -webkit-box-shadow: 2px 2px 5px #999;
-moz-box-shadow: 2px 2px 5px #999;">
      <section class="login_content" style="padding-top: 10px;">
        <div>
          <h2>{{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</h2>
          <div>
              <h2 style="color: #008DDF;">CAMBIAR CLAVE</h1>
          </div>
          
          <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-7">
                    <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Password</label>

                <div class="col-md-7">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-7">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                    <button type="submit" class="btn btn-primary">
                        Reset Password
                    </button>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="separator">
              <div class="clearfix"></div>
              <br />
              <div>
                <p>{{ config('constants.COPYRIGHT', 'Laravel') }}. {{ config('constants.NOMBRE_SISTEMA', 'Laravel') }}</p>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection
