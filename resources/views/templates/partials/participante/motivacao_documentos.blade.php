@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
@endsection

@section('motivacao_documentos')

{!! Form::open(array('route' => 'motivacao.documentos', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '' )) !!}

  <fieldset class="scheduler-border">
        <legend class="scheduler-border">{{ trans('tela_motivacao_documentos.documentos_pessoais') }}</legend>
        <div class="form-horizontal"{{ $errors->has('documentos_pessoais') ? ' has-error' : '' }}>
          <div class="row">
            <span class="input-group-btn">
                <!-- image-preview-clear button -->
                <button type="button" class="btn btn-primary" style="display:none;">
                    <span class="glyphicon glyphicon-remove"></span> Clear
                </button>
                <!-- image-preview-input -->
                <div class="btn btn-primary">
                    <input type="file" accept="application/pdf, image/png, image/jpeg, image/jpg, image/gif" name="documentos_pessoais" required=""/> <!-- rename it -->
                </div>
            </span>
          </div>
           @if ($errors->has('documentos_pessoais'))
            <span class="help-block">{{ $errors->first('documentos_pessoais') }}</span>
          @endif
        </div>
  </fieldset>

  <fieldset class="scheduler-border">
        <legend class="scheduler-border">{{ trans('tela_motivacao_documentos.historico') }}</legend>
        <div class="form-horizontal"{{ $errors->has('historico') ? ' has-error' : '' }}>
          <div class="row">
            <span class="input-group-btn">
                <!-- image-preview-clear button -->
                <button type="button" class="btn btn-primary" style="display:none;">
                    <span class="glyphicon glyphicon-remove"></span> Clear
                </button>
                <!-- image-preview-input -->
                <div class="btn btn-primary">
                    <input type="file" accept="application/pdf, image/png, image/jpeg, image/jpg, image/gif" name="historico" required=""/> <!-- rename it -->
                </div>
            </span>
          </div>
           @if ($errors->has('historico'))
            <span class="help-block">{{ $errors->first('historico') }}</span>
          @endif
        </div>
  </fieldset>

      <div class="form-group">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 text-center">
            {!! Form::submit(trans('tela_motivacao_documentos.menu_enviar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
          </div>
        </div>
      </div>
      {!! Form::close() !!}
  
@endsection

@section('scripts')
  {!! Html::script( asset('js/parsley.min.js') ) !!}
  {!! Html::script( asset('i18n/pt-br.js') ) !!}
@endsection
