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
      <label class="radio-inline">{!! Form::radio('apresenta_trabalho', 1, False , ['required' => '']) !!}{{ " ".trans('tela_dados_academicos.sim') }}</label>
      <label class="radio-inline">{!! Form::radio('apresenta_trabalho', 0, False , ['required' => '']) !!}{{ " ".trans('tela_dados_academicos.nao') }}</label>
  </div>
</fieldset>

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
@endsection