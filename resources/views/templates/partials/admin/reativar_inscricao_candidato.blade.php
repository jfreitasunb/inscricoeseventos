@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('reativar_inscricao_candidato')
@if ($modo_pesquisa)
	{!! Form::open(array('route' => 'pesquisa.candidato', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
@else
	{!! Form::open(array('route' => 'salvar.alteracao', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}	
@endif

<div class="form-group">
	{!! Form::label('email_candidato', 'Pesquisar Candidato', ['class' => 'col-md-4 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('email_candidato', '' , ['class' => 'form-control input-md']) !!}
	</div>
</div>
@if (!$modo_pesquisa)
	{!! Form::hidden('id', $finalizou->id, []) !!}
	{!! Form::hidden('id_inscricao_verao', $finalizou->id_inscricao_verao, []) !!}
	{!! Form::hidden('id_candidato', $finalizou->id_candidato, []) !!}
	{!! Form::hidden('email_candidato', $email_candidato, []) !!}
	<div class="form-group">
		{!! Form::label('nome', 'Nome do candidato', ['class' => 'col-md-4 control-label']) !!}
		<div class="col-md-4">
			{!! Form::text('nome', $finalizou->nome, ['class' => 'form-control input-md']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('tipo_programa_pos', 'Inscreveu para:', ['class' => 'col-md-4 control-label']) !!}
		<div class="col-md-4">
			{!! Form::text('tipo_programa_pos', $finalizou->tipo_programa_pos_ptbr, ['class' => 'form-control input-md']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('finalizada', 'Finalizou Inscrição?', ['class' => 'col-md-4 control-label']) !!}
		<div class="col-md-4">
			{!! Form::text('finalizada', $finalizou->finalizada ? 'Sim' : 'Não', ['class' => 'form-control input-md']) !!}
		</div>
	</div>
@endif

@if ($modo_pesquisa)
	<div class="form-group">
	  <div class="row">
	    <div class="col-md-6 col-md-offset-3 text-center">
	      {!! Form::submit('Pesquisar', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
	    </div>
	  </div>
	</div> 
@else
	<div class="form-group">
	  <div class="row">
	    <div class="col-md-6 col-md-offset-3 text-center">
	      {!! Form::submit('Salvar', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
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