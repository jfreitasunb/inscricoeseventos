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
          <th scope="col">Nome do candidato</th>
          <th scope="col">Programa</th>
        </tr>
      </thead>
      <tbody>
        @foreach( $inscricoes_nao_finalizadas as $nao_finalizada)
          <tr>
            <td>{{ $nao_finalizada['nome'] }}</td>
            <td>{{ $nao_finalizada['tipo_programa_pos_ptbr'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="text-center">
    {{ $inscricoes_nao_finalizadas->render() }}
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