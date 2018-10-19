@extends('templates.default')

@section('relatorio_trabalhos_submetidos')
  <form action="" method="POST">
    <legend>Trabalhos Submetidos: {{ $area_trabalho }} {{ $total_trabalhos_submetidos }}</legend>
	<strong>Total de Inscritos no Evento:</strong> {{ $total_inscricoes_recebidas }}<br>
    	<div class="table-responsive">
		    <table class="table table-striped">
			  	<thead>
				    <tr>
				      <th>Ano</th>
				      <th>Nome do Evento</th>
				      <th>Período de Inscrição</th>
				      <th>Relatório de Inscritos</th>
				      <th>Lista de Inscritos</th>
				    </tr>
			  	</thead>
			  	<tbody>
			    	<tr>
			    	<td><a href="{!! route('gera.relatorio', ['id_inscricao_evento' => $relatorio_disponivel['id_inscricao_evento']]) !!}">{{ $relatorio_disponivel->ano_evento }}</a></td>
			    	<td><a href="{!! route('gera.relatorio', ['id_inscricao_evento' => $relatorio_disponivel['id_inscricao_evento']]) !!}">{{ $relatorio_disponivel->nome_evento }}</a></td>
			      	<td><a href="{!! route('gera.relatorio', ['id_inscricao_evento' => $relatorio_disponivel['id_inscricao_evento']]) !!}">{{\Carbon\Carbon::parse($relatorio_disponivel['inicio_inscricao'])->format('d/m/Y')." à ".\Carbon\Carbon::parse($relatorio_disponivel['fim_inscricao'])->format('d/m/Y')}}</a></td>
			      	<td>@if($monitoria == $relatorio_disponivel['id_inscricao_evento'] || isset($local_arquivos)) 
			      		@foreach ($arquivos_zipados_para_view as $zip_relatorio)
			      		 	<a target="_blank" href="{{ asset($local_arquivos['arquivo_zip'].$zip_relatorio) }}" >{{$zip_relatorio}}</a>
			      		@endforeach
			      		@endif</td>
			      	<td>{{-- @if($monitoria == $relatorio_disponivel['id_inscricao_evento'] || isset($local_arquivos)) <a target="_blank" href="{{asset($local_arquivos['local_relatorios'].$relatorio_csv)}}">{{$relatorio_csv}}</a> @endif --}}</td>
			    	</tr>
			  	</tbody>
			</table>
		</div>
  </form>
@stop
