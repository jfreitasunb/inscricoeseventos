@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('link_muda_senha')
@if ($modo_pesquisa)
  {!! Form::open(array('route' => 'pesquisa.email.muda.senha', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
@else
  {!! Form::open(array('route' => 'pesquisa.email.muda.senha', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!} 
@endif

<div class="form-group">
  {!! Form::label('email', 'Pesquisar Usuário', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-4">
    {!! Form::text('email', '' , ['class' => 'form-control input-md']) !!}
  </div>
</div>
@if (!$modo_pesquisa)
  {!! Form::hidden('id_user', $user->id_user, []) !!}
   <div class="form-group">
    {!! Form::label('link', 'Link de mudança de senha', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
      {!! Form::text('link', $url_mudar_senha, ['class' => 'form-control input-md', 'readonly' => 'readonly']) !!}
    </div>
  </div>
@endif

@if ($modo_pesquisa)
  <div class="form-group">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        {!! Form::submit('Gerar link', ['class' => 'btn btn-warning btn-lg register-submit']) !!}
      </div>
    </div>
  </div> 
@else
  <div class="form-group">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        {!! Form::submit('Limpar', ['class' => 'btn btn-primary btn-lg register-submit', 'name' =>'cancelar',]) !!}
      </div>
    </div>
  </div>
@endif

{!! Form::close() !!}
@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {{-- {!! Html::script( asset('js/datepicker.js') ) !!} --}}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
@endsection