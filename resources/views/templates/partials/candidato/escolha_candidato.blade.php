@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
@endsection

@section('escolha_monitoria')
<fieldset class="scheduler-border">
  <legend class="scheduler-border"></legend>
    <div class="row">
      {{trans('tela_escolha_candidato.texto_programa')}}
    </div>
</fieldset>

{!! Form::open(array('route' => 'dados.escolhas', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '' )) !!}
    {{-- <fieldset class="scheduler-border">
      <legend class="scheduler-border">{{trans('tela_escolha_candidato.programa_disponivel')}}</legend>
        <div class="row">
          @foreach($programa_para_inscricao as $programa => $key)
            <div class="col-md-4">
              <label class="radio-inline">{!! Form::radio('programa_pretendido', $programa, $dados['programa_pretendido'] == $programa ?: '', ['required' => '']) !!} {!! $key !!}</label>
            </div>
          @endforeach
        </div>
    </fieldset> --}}

    @if(isset($cursos_verao))
      <fieldset class="scheduler-border">
        <legend class="scheduler-border">{{trans('tela_escolha_candidato.texto_cursos_verao')}}</legend>
          <div class="row"">
              @foreach ($cursos_verao as $curso)
                <div class="col-md-4">
                  <label class="radio-inline">{!! Form::checkbox('curso_desejado[]', $curso->id_curso_verao, False , []) !!}{{ " ".$curso->$nome_coluna }}{{ $curso->seleciona_pos ? " (Seleciona para a Pós)" : "" }}</label>
                </div>    
              @endforeach
          </div>
      </fieldset>
    @endif

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
          {!! Form::submit(trans('tela_escolha_candidato.menu_enviar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
        </div>
      </div>
    </div>
  
  {!! Form::close() !!}
  
@endsection

@section('scripts')
  {!! Html::script( asset('js/parsley.min.js') ) !!}
  {!! Html::script( asset('i18n/pt-br.js') ) !!}
@endsection
