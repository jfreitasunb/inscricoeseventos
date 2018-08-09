@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('criar_coordenador')
  {!! Form::open(array('route' => 'criar.coordenador', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Dados básicos</legend>
  <div class="col-md-4">
    {!! Form::label('nome', 'Nome: ', ['class' => 'control-label']) !!}
    {!! Form::text('nome', '' , ['class' => 'form-control', 'required' => '']) !!}
  </div>
  <div class="col-md-8">
    {!! Form::label('email', 'E-mail: ', ['class' => 'control-label']) !!}
    {!! Form::text('email_recomendante[]', '' , ['id' => 'email_recomendante', 'class' => 'form-control email', 'required' => '', 'data-parsley-type' => 'email']) !!}
  </div>
</fieldset>

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Coordenador Geral do Evento?</legend>
  <div class="col-md-6">
    <label class="radio-inline">{!! Form::radio('coordenador_geral', 0, 1, []) !!}Não</label>
    <label class="radio-inline">{!! Form::radio('coordenador_geral', 1, '', []) !!}Sim</label>
  </div>
</fieldset>

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Coordenador de qual área?</legend>
  <div class="col-md-6">
    {!! Form::select('coordenador_area', $secao, '',  ['class' => 'form-control col-md-6']) !!}
  </div>
</fieldset>

{{-- <fieldset class="scheduler-border">
  <legend class="scheduler-border">Em qual evento?</legend>
  <div class="col-md-6">
    {!! Form::select('coordenador_evento', $evento, '',  ['class' => 'form-control col-md-6']) !!}
  </div>
</fieldset> --}}


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
@endsection
