{{-- @extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('ver_ficha') --}}

<fieldset class="scheduler-border">
  <legend class="scheduler-border"><h1>Fichas de Inscrição para o Edital: {{ $relatorio_disponivel->edital }}</h1></legend>
    @foreach( $fichas as $key => $ficha)
    <h2>Inscrições para o {{ $key }}</h2>
      @foreach ($ficha as $element)
        <div class="row">
        <a target="_blank" href="{{asset($local_arquivos_pdf.$element)}}" > {{ $element }} </a>
      </div>
      @endforeach
    @endforeach
</fieldset>

{{-- @endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {!! Html::script( asset('js/datepicker.js') ) !!}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
@endsection --}}