@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('contas_inativas')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Contas não ativadas ainda</legend>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th scope="col">Nome do candidato</th>
          <th scope="col">E-mail</th>
          <th>Tipo de conta</th>
          <th>Data de criação</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        @foreach( $usuarios_inativos as $inativo)
        {!! Form::open(array('route' => 'lista.inativos', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
        {!! Form::hidden('id_user', $inativo['id_user'], []) !!}
          <tr>
            <td>{{ $inativo['nome'] }}</td>
            <td>{{ $inativo['email'] }}</td>
            <td>{{ $inativo['user_type'] }}</td>
            <td>{{ $inativo['created_at'] }}</td>
            <td>{!! Form::submit('Ativar', ['class' => 'btn btn-danger pull-righ']) !!}</td>
          </tr>
        {!! Form::close() !!}
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="text-center">
    {{ $usuarios_inativos->render() }}
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