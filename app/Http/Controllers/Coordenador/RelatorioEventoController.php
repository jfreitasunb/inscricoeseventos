<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Auth;
use DB;
use Mail;
use Session;
use File;
use PDF;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\TrabalhoSelecionado;
use InscricoesEventos\Models\TipoCoordenador;
use InscricoesEventos\Notifications\NotificaNovaInscricao;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\BaseController;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\RelatorioController;
use Illuminate\Foundation\Auth\RegistersUsers;
use League\Csv\Writer;


/**
* Classe para visualização da página inicial.
*/
class RelatorioEventoController extends CoordenadorController
{

	public function getGeraArquivosDiversos()
	{
		$user = $this->SetUser();
	    
	    $id_coordenador = $user->id_user;

	    $locale_relatorio = 'pt-br';

	    $relatorio = new ConfiguraInscricaoEvento();

	    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

	    $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

	    $trabalhos_selecionados = new TrabalhoSelecionado();

	    $existe_selecao = $trabalhos_selecionados->existe_trabalho_selecionado($id_inscricao_evento);

		$tipo_de_arquivo_disponivel['cracha'] = "Arquivos para gerar os crachás";
		
		$tipo_de_arquivo_disponivel['lista_participante'] = "Arquivos com a lista de todos os participantes inscritos";
		
		$tipo_de_arquivo_disponivel['lista_trabalhos_submetidos'] = "Arquivos com a lista de trabalhos submetidos";

		if ($existe_selecao) {
			$tipo_de_arquivo_disponivel['lista_trabalhos_aceitos'] = "Arquivos com a lista dos trabalhos aceitos";
		}

		return view('templates.partials.coordenador.relatorio_arquivos_diversos')->with(compact('tipo_de_arquivo_disponivel'));
	}

	public function postGeraArquivosDiversos(Request $request)
	{
		
		$this->validate($request, [
			'arquivos_para_gerar' => 'required',
		]);

		$arquivos_para_gerar = $request->arquivos_para_gerar;

		// dd($arquivos_para_gerar);

		$relatorio_controller = new RelatorioController();

		$user = $this->SetUser();
	    
	    $id_coordenador = $user->id_user;

	    $locale_relatorio = 'pt-br';

	    $relatorio = new ConfiguraInscricaoEvento();

	    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

	    $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

	    $coordenador = new TipoCoordenador();

    	$nivel_coordenador = $coordenador->retorna_dados_coordenador($id_coordenador, $id_inscricao_evento);

	    $locais_arquivos = $relatorio_controller->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

	    foreach ($arquivos_para_gerar as $tipo_arquivo) {

	    	$relatorio_csv = Writer::createFromPath($locais_arquivos['local_relatorios'].$locais_arquivos[$tipo_arquivo], 'w+');

	    	$linha_arquivo = [];

	    	$linha_arquivo = $relatorio_controller->ConsolidaCabecalhoCSV($tipo_arquivo);

	    	$relatorio_csv->insertOne($linha_arquivo);

	    	if ($tipo_arquivo == "cracha" OR $tipo_arquivo == "lista_participante") {
	    		
	    		$finaliza = new FinalizaInscricao();

			    $usuarios_finalizados = $finaliza->retorna_usuarios_relatorios($id_inscricao_evento, $nivel_coordenador);
			    
			    foreach ($usuarios_finalizados as $candidato) {

					$linha_arquivo = [];

					$dados_candidato_para_relatorio = [];

					$dados_candidato_para_relatorio['ano_evento'] = $relatorio->ano_evento;

					$dados_candidato_para_relatorio['id_participante'] = $candidato->id_participante;

					foreach ($relatorio_controller->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
					 $dados_candidato_para_relatorio[$key] = $value;
					}

					$linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];

					$linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_participante'])->email;

					foreach ($relatorio_controller->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
					$dados_candidato_para_relatorio[$key] = $value;
					}

					$linha_arquivo['categoria_participante'] = $dados_candidato_para_relatorio['categoria_participante'];

					$linha_arquivo['area_trabalho'] = $dados_candidato_para_relatorio['area_trabalho'];

					$linha_arquivo['tipo_apresentacao'] = $dados_candidato_para_relatorio['tipo_apresentacao'];

					$linha_arquivo['titulo_trabalho'] = $dados_candidato_para_relatorio['titulo_trabalho'];

					// dd($dados_candidato_para_relatorio);
			    }
	    	}

	    	if ($tipo_arquivo == "lista_trabalhos_submetidos") {
	    		# code...
	    	}
	    }
	}
}