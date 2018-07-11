@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('submete_trabalho')
{!! Form::open(array('route' => 'submeter.trabalho', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}

<fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_dados_academicos.categoria')}}</legend>
  <div class="col-md-6">
    @foreach ($categorias as $categoria)
      <label class="radio-inline">{!! Form::radio('id_categoria', $categoria->id, False , ['required' => '']) !!}{{ " ".$categoria->nome_categoria_ptbr }}</label>
    @endforeach
  </div>
</fieldset>

<fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_dados_academicos.apresentar_trabalho')}}</legend>
  <div class="col-md-6">
      <label class="radio-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="apresentar_trabalho" id="yesCheck">{{ " ".trans('tela_dados_academicos.sim') }}</label>
      <label class="radio-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="apresentar_trabalho" id="noCheck">{{ " ".trans('tela_dados_academicos.nao') }}</label>
  </div>
</fieldset>

<div id="ifYes" style="display:none">
    <fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_dados_academicos.categoria')}}</legend>
  <div class="col-md-6">
    @foreach ($tipos_apresentacao as $tipo)
      <label class="radio-inline">{!! Form::radio('id_tipo_apresentacao', $tipo->id, False , ['required' => '']) !!}{{ " ".$tipo->nome_tipo_apresentacao_ptbr }}</label>
    @endforeach
  </div>
</fieldset>

</div>


<div class="form-group">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 text-center">
      {!! Form::submit(trans('tela_dados_academicos.menu_enviar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
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