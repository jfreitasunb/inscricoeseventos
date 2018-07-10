@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('edita_formacao')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Áreas da Pós-Graduação</legend>

  
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Nome Português</th>
          <th>Nome Inglês</th>
          <th>Nome Espanhol</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($tipo_formacao as $formacao)
          <tr>
            {!! Form::open(array('route' => 'editar.formacao', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
            {!! Form::hidden('id', $formacao->id, []) !!}
            <td>{!! Form::text('tipo_ptbr', $formacao->tipo_ptbr, ['class' => 'form-control']) !!}</td>
            <td>{!! Form::text('tipo_en', $formacao->tipo_en, ['class' => 'form-control']) !!}</td>
            <td>{!! Form::text('tipo_es', $formacao->tipo_es, ['class' => 'form-control']) !!}</td>
            <td>{!! Form::submit('Alterar', ['class' => 'btn btn-danger pull-righ']) !!}</td>
            {!! Form::close() !!}
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
  
  
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