@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('seleciona_trabalhos_submentidos')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Selecionar Trabalhos</legend>
  {!! Form::open(array('route' => 'seleciona.trabalhos.submetidos', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
    {!! Form::hidden('id_inscricao_evento', $id_inscricao_evento, []) !!}
    {!! Form::hidden('id_coordenador', $id_coordenador, []) !!}
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">Área</th>
            <th scope="coll">Título</th>
            <th scope="col">Tipo de Apresentação</th>
            <th scope="col">Aceito?</th>
            <th scope="col">Mudar tipo de apresentação?</th>
          </tr>
        </thead>
        <tbody>
          {{-- <tr class="">
            <td>NomeNomeNomeNomeNomeNomeNomeNomeNomeNomeNome</td>
            <td>Área</td>
            <td>Instituição</td>
            <td>Tipo Apresentação</td>
            <td><input type="radio" name="aceito" value="1">Sim <input type="radio" name="aceito" value="0">Não</td>
            <td><input type="radio" name="muda_tipo_apresentacao" value="PA">Palestra <input type="radio" name="muda_tipo_apresentacao" value="PO">Poster</td>
          </tr> --}}
          @foreach( $dados_para_selecao as $dados)
          {{-- {{ dd($dados) }} --}}
            <tr class="">
              <td>{{ $dados['nome'] }} <br>{{"(".$dados['instituicao'].")" }}</td>
              <td>{{ $dados['area_trabalho'] }}</td>
              <td>{{ $dados['titulo_trabalho'] }}</td>
              <td>{{ $dados['nome_tipo_apresentacao_ptbr'] }}</td>
              <td><input type="radio" name={!! "aceito[".$dados['id_participante']."]" !!} value="1">Sim <br> <input type="radio" name={!! "aceito[".$dados['id_participante']."]" !!}  value="0">Não</td>
              <td>@foreach ($tipos_de_apresentacao as $element)
                <input type="radio" name={!! "muda_tipo_apresentacao[".$dados['id_participante']."]" !!} value="{{ $element['id'] }}" {{ $dados['id_tipo_apresentacao']===$element['id']? 'checked':'' }}> {{ $element['nome_apresentacao'] }} <br>
              @endforeach</td>
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