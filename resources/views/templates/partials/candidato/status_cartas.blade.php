@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('status_cartas')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_status_cartas.status_cartas')}}</legend>

  <p>{{ trans('tela_status_cartas.mensagem_status_cartas') }}</p>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">{{ trans('tela_status_cartas.nome_recomendante') }}</th>
      <th scope="col">{{ trans('tela_status_cartas.situacao_carta') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach( $dados_para_template as $status)
      @if ($status['status_carta'])
        <tr class="success">
      @else
        <tr class="danger">
      @endif
      <td>{{ $status['nome_recomendante'] }}</td>
      <td>@if ($status['status_carta'])
          {{ trans('tela_status_cartas.status_enviada') }}
        @else
          {{ trans('tela_status_cartas.status_nao_enviada') }}
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
 
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