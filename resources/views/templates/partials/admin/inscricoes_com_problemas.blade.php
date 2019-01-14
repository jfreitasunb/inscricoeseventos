@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('nao_finalizadas')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Inscrições não finalizadas</legend>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th scope="col">Nome do participante</th>
          <th scope="col">Deseja apresentar trabalho</th>
          <th scope="col">Finalizar Inscrição?</th>
        </tr>
      </thead>
      <tbody>
        {!! Form::open(array('route' => 'inscricoes.com.problemas', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
        {!! Form::hidden('id_inscricao_evento', $id_inscricao_evento, []) !!}
        @foreach( $contas_para_finalizar as $key => $nao_finalizada)
          <tr>
            <td>{{ $nao_finalizada['nome'] }}</td>
            <td>{{ $nao_finalizada['apresentar_trabalho'] }}</td>
            <td><input type="radio" name="finalizar_manualmente[{{ $key }}]" value=1>Sim <input type="radio" name="finalizar_manualmente[{{ $key }}]" value="0" checked>Não</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="form-group">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 text-center">
      {!! Form::submit('Finalizar Inscrição', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
    </div>
  </div>
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