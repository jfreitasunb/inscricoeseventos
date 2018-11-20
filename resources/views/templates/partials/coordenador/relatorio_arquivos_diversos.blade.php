@extends('templates.default')

@section('arquivos_diversos')
  <div class="row">
  	<legend>Selecione abaixo qual(is) arquivo(s) deseja gerar:</legend>
  	{!! Form::open(array('route' => 'relatorio.arquivos.diversos','data-parsley-validate' => '')) !!}

  	@foreach($tipo_de_arquivo_disponivel as $arquivo_gerado => $key)
            <div class="col-xs-6">
              <div class="form-group form-inline">
                <label>
                  {!! Form::checkbox('arquivos_para_gerar[]', $arquivo_gerado, null) !!} {{ $key }} 
                </label> 
              </div>
            </div>
          @endforeach
  	<div class="col-md-10 text-center"> 
        {!! Form::submit('Gerar Arquivos', array('class' => 'register-submit btn btn-primary btn-lg', 'id' => 'register-submit', 'tabindex' => '4')) !!}
	</div>

  	{!! Form::close() !!}
  </div>
    
	
    	
  
@stop
