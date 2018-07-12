@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('configura_inscricao')
  
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      {!! Form::open(array('route' => 'configura.inscricao','data-parsley-validate' => '' ,'enctype' => 'multipart/form-data')) !!}
        <legend>Configurar período da abertura da inscrição</legend>
        <div class="col-xs-6">
          <div class="form-group form-inline">
            {!! Form::label('inicio_inscricao', 'Início da Inscrição:') !!}
            <div class='input-group' id='inicio_inscricao'>
              {!! Form::text('inicio_inscricao', null, ['class' => 'form-control', 'required' => '']) !!}
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="form-group form-inline">
            {!! Form::label('fim_inscricao', 'Final da Inscrição:') !!}
            <div class='input-group' id='fim_inscricao'>
              {!! Form::text('fim_inscricao', null, ['class' => 'form-control', 'required' => '']) !!}
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>

        <legend >Qual o tipo de evento deseja configurar?</legend>
          @foreach($eventos_mat as $evento)
            <div class="col-md-6">
              <label class="radio-inline">{!! Form::radio('id_evento_desejado', $evento->id, False , ['required' => '']) !!}{{ " ".$evento->tipo }}</label>
            </div> 
          @endforeach
        
        
       <legend>Nome do Evento</legend>
        <div class="col-xs-3">
          
          {!! Form::label('evento_ano', 'Ano', ['class' => 'form-label']) !!}
          {!! Form::text('evento_ano', null, ['class' => 'form-control', 'required' => '']) !!}
        </div>
        <div class="col-xs-9">
          {!! Form::label('evento_nome', 'Nome', ['class' => 'form-label']) !!}
          {!! Form::text('evento_nome', null, ['class' => 'form-control', 'required' => '']) !!}
        </div>

        <div class="row">
          {!! Form::label('id_area_evento', "Área do Evento:", ['class' => 'col-md-4 control-label'])!!}
          <div class="col-xs-9">
          <label class="radio">{!! Form::select('id_area_evento', $secao, '',  ['class' => 'form-control col-md-6']) !!}</label>
        </div>
    </div>
          
        
        <div class="col-md-10 text-center"> 
          {!! Form::submit('Salvar', array('class' => 'register-submit btn btn-primary btn-lg', 'id' => 'register-submit', 'tabindex' => '4')) !!}
        </div>
    </div>
      {!! Form::close() !!}
      
  </div>

@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {!! Html::script( asset('js/datepicker.js') ) !!}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
@endsection
