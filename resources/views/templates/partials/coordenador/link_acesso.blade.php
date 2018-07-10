@extends('templates.default')

@section('link_acesso')

<fieldset class="scheduler-border">
  		<legend class="scheduler-border">Criar link de acesso para o edital: {{ $relatorio_disponivel->edital }}</legend>
@if ($modo_pesquisa)
	{!! Form::open(array('route' => 'link.acesso', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
	<div class="row">
		<div class="form-group">
			{!! Form::label('validade_link', 'Tempo de validade do link', ['class' => 'col-md-4 control-label']) !!}
			<div class="col-md-2">
				{!! Form::text('validade_link', '' , ['class' => 'form-control input-md']) !!}
			</div>
			<label class="radio-inline">{!! Form::radio('tempo_validade', 'dias', ['class' => 'col-md-4 control-label', 'required' => '']) !!}Dias</label>
			<label class="radio-inline">{!! Form::radio('tempo_validade', 'horas', null, ['class' => 'col-md-4 control-label', 'required' => '']) !!}Horas</label>
			<label class="radio-inline">{!! Form::radio('tempo_validade', 'minutos', null, ['class' => 'col-md-4 control-label', 'required' => '']) !!}Minutos</label>
		</div>	
	</div>
@endif


@if ($modo_pesquisa)
	<div class="form-group">
	  <div class="row">
	    <div class="col-md-6 col-md-offset-3 text-center">
	      {!! Form::submit('Gerar link', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
	    </div>
	  </div>
	</div>
	{!! Form::close() !!}
</fieldset>
@else
	{!! Form::open(array('route' => 'link.acesso', 'class' => 'form-horizontal', 'method' => 'get')) !!}
	<div class="form-group">
	  <div class="row">
	    {!! Form::label('link_criado', 'Link para acessar os arquivos PDF', ['class' => 'col-md-4 control-label']) !!}
		<div class="col-md-12">
			{!! Form::text('link_criado', $link_de_acesso , ['class' => 'form-control input-md']) !!}
		</div>
	  </div>
	</div>
	<div class="form-group">
	  <div class="row">
	    <div class="col-md-6 col-md-offset-3 text-center">
	      {!! Form::submit('Novo link', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
	    </div>
	  </div>
	</div>
	{!! Form::close() !!}
</fieldset>
@endif


@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection
