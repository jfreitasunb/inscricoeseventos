@extends('templates.default')

@section('stylesheets')
  <link href="{{ asset('css/parsley.css') }}" rel="stylesheet">
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-login">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-12">
              <a href="#" id="register-form-link">{{trans('tela_registro.menu_registro')}}</a>
            </div>
            <hr>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <form data-parsley-validate="" id="register-form" action="{{ route('auth.registrar') }}" method="post" role="form">
                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                  <label for="nome" class="control-label">{{trans('tela_registro.menu_nome')}}</label>
                  <input type="text" name="nome" tabindex="1" class="form-control" required="" placeholder="{{trans('tela_registro.menu_nome')}}" value="{{ Request::old('nome') ?: '' }}">
                  @if ($errors->has('nome'))
                      <span class="help-block">{{ $errors->first('nome') }}</span>
                  @endif
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email" class="control-label">{{trans('tela_registro.menu_email')}}</label>
                  <input type="email" name="email" id="email" tabindex="1" class="form-control" required="" data-parsley-type="email" placeholder="{{trans('tela_registro.menu_email')}}" value="{{ Request::old('email') ?: '' }}" >
                  @if ($errors->has('email'))
                      <span class="help-block">{{ $errors->first('email') }}</span>
                  @endif
                </div>
                <div class="form-group{{ $errors->has('confirmar-email') ? ' has-error' : '' }}">
                  <label for="confirmar-email" class="control-label">{{trans('tela_registro.menu_confirma_email')}}</label>
                  <input type="email" name="confirmar-email" id="confirmar-email" tabindex="1" class="form-control"  required="" data-parsley-type="email" data-parsley-equalto="#email" placeholder="{{trans('tela_registro.menu_confirma_email')}}" value="{{ Request::old('confirmar-email') ?: '' }}">
                  @if ($errors->has('confirmar-email'))
                      <span class="help-block">{{ $errors->first('confirmar-email') }}</span>
                  @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="password" class="control-label">{{trans('tela_registro.menu_senha')}}</label>
                  <input type="password" name="password" id="password" tabindex="2" class="form-control" required="" data-parsley-minlength="6" placeholder="{{trans('tela_registro.menu_senha')}}">
                  @if ($errors->has('password'))
                      <span class="help-block">{{ $errors->first('password') }}</span>
                  @endif
                </div>
                <div class="form-group{{ $errors->has('confirmar-password') ? ' has-error' : '' }}">
                  <label for="confirmar-password" class="control-label">{{trans('tela_registro.menu_confira_senha')}}</label>
                  <input type="password" name="confirmar-password" id="confirmar-password" tabindex="2" class="form-control" required="" data-parsley-minlength="6" data-parsley-equalto="#password" placeholder="{{trans('tela_registro.menu_confira_senha')}}">
                  @if ($errors->has('confirmar-password'))
                      <span class="help-block">{{ $errors->first('confirmar-password') }}</span>
                  @endif
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                      <input type="submit" name="registrar" id="register-submit" tabindex="4" class="form-control btn btn-register" value={{trans('tela_registro.menu_registro')}}>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <script src="{{ asset('i18n/pt-br.js') }}"></script>
@endsection
