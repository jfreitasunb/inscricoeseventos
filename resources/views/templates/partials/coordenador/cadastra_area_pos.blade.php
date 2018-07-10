@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('cadastra_area_pos')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Cadastrar novo curso de Verão</legend>
  {!! Form::open(array('route' => 'cadastra.area.pos', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
    <div class="row">
      {!! Form::label('nome_ptbr', 'Nome em Português', ['class' => 'col-md-4 control-label'])!!}
      <div class="col-md-4">
        {!! Form::text('nome_ptbr', '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
      </div>
    </div>
    <div class="row">
      {!! Form::label('nome_en', 'Nome em Inglês', ['class' => 'col-md-4 control-label'])!!}
      <div class="col-md-4">
        {!! Form::text('nome_en', '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
      </div>
    </div>
    <div class="row">
      {!! Form::label('nome_es', 'Nome em Espanhol', ['class' => 'col-md-4 control-label'])!!}
      <div class="col-md-4">
        {!! Form::text('nome_es', '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
      </div>
    </div>
    <div class="col-md-10 text-center"> 
      {!! Form::submit('Cadastrar', array('class' => 'register-submit btn btn-primary btn-lg', 'id' => 'register-submit', 'tabindex' => '4')) !!}
    </div>
  {!! Form::close() !!}
          
  
  
  
</fieldset>

@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {!! Html::script( asset('js/datepicker.js') ) !!}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
@endsection