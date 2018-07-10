@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('graficos')

{!! Charts::styles() !!}

<div class="app">
    <center>
        {!! $inscritos_por_programa->html() !!}
    </center>
    @if ($inscricao_doutorado)
        <hr>
        <center>
            {!! $candidatos_por_area_doutorado->html() !!}
        </center>
    @endif
    
</div>
<!-- End Of Main Application -->
{!! Charts::scripts() !!}
{!! $inscritos_por_programa->script() !!}
@if ($inscricao_doutorado)
    {!! $candidatos_por_area_doutorado->script() !!}
@endif

@endsection