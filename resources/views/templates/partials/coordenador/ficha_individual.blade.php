@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('ficha_individual')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Fichas de Inscrição Individuais</legend>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th scope="col">{{ trans('tela_cartas_pendentes.nome_candidato') }}</th>
          <th>Ficha de Inscrição</th>
        </tr>
      </thead>
      <tbody>
        @foreach( $inscricoes_finalizadas as $finalizada)
          @if ($new_user <> $finalizada['id_candidato'])
            <tr>
              <td><a href=" {{ route('ver.ficha.individual', ['id_inscricao_verao' => $finalizada['id_inscricao_verao'],'id_aluno' => $finalizada['id_candidato']]) }}">{{ $finalizada['nome'] }}</a></td>
              <td>@if($id_aluno_pdf == $finalizada['id_candidato']) <a target="_blank" href="{{asset($nome_pdf)}}" > Ficha de Inscrição </a> @endif</td>
            </tr>
          @endif
          @php ($new_user = $finalizada['id_candidato'])
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="text-center">
    {{ $inscricoes_finalizadas->render() }}
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