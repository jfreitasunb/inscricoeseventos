@extends('templates.default')

@section('inicio')
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="idiomas btn-toolbar">
        <div class="btn-group-justified">
          <a href="{{ route('lang.portuguese') }}" class="btn btn-primary button">Português</a>
          <a href="{{ route('lang.english') }}" class="btn btn-primary button">English</a>
          <a href="{{ route('lang.spanish') }}" class="btn btn-primary button">Español</a>
        </div>  
      </div>
    </div>
  </div>
  <div class="row" style="margin-top:50px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
    <form role="form">
      <fieldset>
        <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-6">
            <a href="{{ route('auth.login') }}" class="btn btn-lg btn-success btn-block">{{trans('tela_inicial.menu_login')}}</a>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6">
            <a href="{{ route('auth.registrar') }}" class="btn btn-lg btn-primary btn-block">{{trans('tela_inicial.menu_registrar')}}</a>
          </div>
          <div class="text-center" style="margin-top:70px">
            <span class="button-checkbox">
          <a href="{{ route('password.request') }}" class="btn btn-link">{{trans('tela_login.menu_esqueceu_senha')}}</a>
        </span>  
          </div>
          
        </div>
      </fieldset>
    </form>
  </div>
</div>
  {{-- <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-login">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <a href="{{ route('auth.login') }}" class="active">Login</a>
            </div>
            <div class="col-xs-6">
              <a href="{{ route('auth.registrar') }}">{{trans('tela_inicial.menu_registrar')}}</a>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-center">
                <a href="{{ route('password.request') }}" tabindex="5" class="forgot-password">{{trans('tela_login.menu_esqueceu_senha')}}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  <p class="bottom-three">
   
  </p>
@endsection