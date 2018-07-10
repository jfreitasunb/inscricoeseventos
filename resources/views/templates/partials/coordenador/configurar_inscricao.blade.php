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
        {{-- <legend>Escolher os programas para Inscrição:</legend>
          @foreach($programas_pos_mat as $programa)
            <div class="col-xs-6">
              <div class="form-group form-inline">
                <label>
                  {!! Form::checkbox('escolhas_coordenador[]', $programa->id_programa_pos, null) !!} {{ $programa->tipo_programa_pos_ptbr }} 
                </label> 
              </div>
            </div>
          @endforeach --}}

        <legend>Escolher os cursos do Verão:</legend>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th></th>
                  <th>Curso</th>
                  <th>Seleciona para Pós?</th>
                </tr>
              </thead>
            <tbody>
              @foreach($cursos_verao_mat as $curso)
                <tr>
                  <td>{!! Form::checkbox('curso_verao[]', $curso->id_curso_verao, null) !!} </td>
                  <td>{{ $curso->nome_ptbr }}</td>
                  <td><label class="radio-inline">{{ Form::radio('seleciona_pos['.$curso->id_curso_verao.']', 0, True) }}Não</label>
                  <label class="radio-inline">{{ Form::radio('seleciona_pos['.$curso->id_curso_verao.']', 1, False) }}Sim</label>
                  </td>
                  </tr>
              @endforeach
            </tbody>
    </table>
  </div>

        @if ($errors->has('edital'))
          <span class="help-block">{{ $errors->first('edital') }}</span>
        @endif
        
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
