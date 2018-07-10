@extends('templates.default')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-login">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <a href="#" id="login-form-link">Login</a>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                {!! Form::open(array('route' => 'auth.login','data-parsley-validate' => '' , 'id' => 'login-form', 'role' => 'form')) !!}
                <div class="input-group form-group">
                  <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                  {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'login', 'tabindex' => '1', 'placeholder' => trans('tela_login.menu_email')]) !!}
                </div>
                <div class="input-group form-group">
                  <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                  {!! Form::password('password', ['class' => 'form-control', 'tabindex' => '2', 'placeholder' => trans('tela_login.menu_senha')]) !!}
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                      {!! Form::submit(trans('tela_login.menu_entrar'), ['class' => 'form-control btn btn-login']) !!}
                    </div>
                  </div>
                </div>  
                {!! Form::close() !!}
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
        </div>
      </div>
    </div>
  </div>
@endsection
