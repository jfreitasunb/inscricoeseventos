@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('seleciona_candidatos')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Seleciona candidatos</legend>
  {!! Form::open(array('route' => 'seleciona.candidatos', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
    {!! Form::hidden('id_inscricao_pos', $relatorio_disponivel->id_inscricao_pos, []) !!}
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th scope="col">{{ trans('tela_cartas_pendentes.nome_candidato') }}</th>
            <th scope="col">{{ trans('tela_cartas_pendentes.tipo_programa') }}</th>
            <th>Candidato foi selecionado?</th>
          </tr>
        </thead>
        <tbody>
          @foreach( $inscricoes_finalizadas as $finalizada)
            <tr class="">
              {!! Form::hidden('id_programa_pos', $finalizada['id_programa_pos'], []) !!}
              <td>{{ $finalizada['nome'] }}</td>
              <td>{{ $finalizada['tipo_programa_pos_ptbr'] }}</td>
              <td>{!! Form::radio('selecionar['.$finalizada['id_candidato'].']','1_'.$finalizada['id_programa_pos'],true) !!} Sim {!! Form::radio('selecionar['.$finalizada['id_candidato'].']','0_'.$finalizada['id_programa_pos'],false) !!} Não</td>
            </tr>
          @endforeach
        </tbody>
        
      </table>
    </div>
    <div class="col-md-10 text-center"> 
      {!! Form::submit('Enviar', array('class' => 'register-submit btn btn-primary btn-lg', 'id' => 'register-submit', 'tabindex' => '4')) !!}
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