@extends('templates.default')

@section('arquivos_diversos')
  <div class="row">
  	<legend>Selecione abaixo qual(is) arquivo(s) deseja gerar:</legend>
  	{!! Form::open(array('route' => 'relatorio.arquivos.diversos','data-parsley-validate' => '')) !!}


  	<div class="col-md-10 text-center"> 
        {!! Form::submit('Gerar Arquivos', array('class' => 'register-submit btn btn-primary btn-lg', 'id' => 'register-submit', 'tabindex' => '4')) !!}
	</div>

  	{!! Form::close() !!}
  </div>
    
	
    	
  
@stop
