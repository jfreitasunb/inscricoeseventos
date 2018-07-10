@extends('templates.default')

@section('relatorio_pos_editais_anteriores')
  <form action="" method="POST">
    <legend>Relatório de inscritos</legend>
    	<div class="table-responsive">
    		<table class="table table-striped table-bordered table-hover">
			  	<thead>
				    <tr>
				      <th>Edital</th>
				      <th>Período de Inscrição</th>
				      <th>Relatório de Inscritos</th>
				      <th>Lista de Inscritos</th>
				    </tr>
			  	</thead>
			  	<tbody>
		  			@foreach ($relatorios_anteriores as $relatorio)
		  				<tr>
				      	<th scope="row"><a href="{!! route('gera.anteriores', ['id_inscricao_verao' => $relatorio['id_inscricao_verao']]) !!}">{{$relatorio['ano_evento']}}</a></th>
				      	<td><a href="{!! route('gera.anteriores', ['id_inscricao_verao' => $relatorio['id_inscricao_verao']]) !!}">{{\Carbon\Carbon::parse($relatorio['inicio_inscricao'])->format('d/m/Y')." à ".\Carbon\Carbon::parse($relatorio['fim_inscricao'])->format('d/m/Y')}}</a></td>
				      	<td>@if($monitoria == $relatorio['id_inscricao_verao'] or Session::get('monitoria') == $relatorio['id_inscricao_verao']) 
				      		@if (is_array($arquivos_zipados_para_view))
				      			@foreach ($arquivos_zipados_para_view as $zip_relatorio)
				      		 		<a target="_blank" href="{{asset('storage/relatorios/edital_'.$relatorio['ano_evento'].'/zip/'.$zip_relatorio)}}" >{{$zip_relatorio}}</a>
				      			@endforeach
				      		@else
				      			@foreach (Session::get('arquivos_zipados_para_view') as $zip_relatorio)
				      		 		<a target="_blank" href="{{asset('storage/relatorios/edital_'.$relatorio['ano_evento'].'/zip/'.$zip_relatorio)}}" >{{$zip_relatorio}}</a>
				      			@endforeach
				      		@endif
				      		@endif</td>
				      	<td>@if($monitoria == $relatorio['id_inscricao_verao']  or Session::get('monitoria') == $relatorio['id_inscricao_verao'] ) <a target="_blank" href="{{asset('storage/relatorios/edital_'.$relatorio['ano_evento'].'/'.Session::get('relatorio_csv'))}}">{{Session::has('relatorio_csv')? Session::get('relatorio_csv'): $relatorio_csv}}</a> @endif</td>
				    	</tr>
		  			@endforeach
			  	</tbody>
			</table>
    	</div>
		<div class="text-center">
			{!! $relatorios_anteriores->links(); !!}
		</div>
  </form>
@stop
