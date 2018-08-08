@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('criar_coordenador')
  {!! Form::open(array('route' => 'criar.coordenador', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}

<fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_submeter_trabalho.categoria')}}</legend>
  <div class="col-md-6">
    <label class="radio-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="tipo_acao" id="yesCheck">Criar nova conta</label>
    <label class="radio-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="tipo_acao" id="noCheck">Usar conta existente</label>
  </div>
</fieldset>

{{-- <fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_submeter_trabalho.apresentar_trabalho')}}</legend>
  <div class="col-md-6">
      <label class="radio-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="apresentar_trabalho" id="yesCheck">{{trans('tela_submeter_trabalho.sim') }}</label>
      <label class="radio-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="apresentar_trabalho" id="noCheck">{{ " ".trans('tela_submeter_trabalho.nao') }}</label>
  </div>
</fieldset> --}}

<div id="ifYes" style="display:none">
  <fieldset class="scheduler-border">
    <legend class="scheduler-border">{{trans('tela_submeter_trabalho.tipo_apresentacao')}}</legend>
    <div class="col-md-6">
      <label class="radio-inline">{!! Form::radio('id_tipo_apresentacao', 1, '', []) !!}fd</label>
      <label class="radio-inline">{!! Form::radio('id_tipo_apresentacao', 1, '', []) !!}ghg</label>
    </div>
  </fieldset>
</div>

<div id="ifNo" style="display:none">
  <fieldset class="scheduler-border">
    <legend class="scheduler-border">{{trans('tela_submeter_trabalho.tipo_apresentacao')}}</legend>
    <div class="col-md-6">
      <label class="radio-inline">{!! Form::radio('id_tipo_apresentacao', 1, '', []) !!}essdfds</label>
      <label class="radio-inline">{!! Form::radio('id_tipo_apresentacao', 1, '', []) !!}fgsdfg</label>
    </div>
  </fieldset>
</div>


<div class="form-group">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 text-center">
      {!! Form::submit(trans('tela_submeter_trabalho.menu_enviar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
    </div>
  </div>
</div>
{!! Form::close() !!}
@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {!! Html::script( asset('js/datepicker.js') ) !!}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
  {!! Html::script( asset('js/show_hide.js') ) !!}
 
@endsection
