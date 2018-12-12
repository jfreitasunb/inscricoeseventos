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
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\ConfiguraDetalhesEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\TrabalhoSubmetido;
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
use Response;
use ZipArchive;
use Storage;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
/**
* Classe para visualização da página inicial.
*/
class RelatorioEventoController extends CoordenadorController
{

	protected $array_arquivos_resumos = array(
		1 => "resumos/algebratn.tex",
		2 => "resumos/analise.tex",
		3 => "resumos/analisenumerica.tex",
		4 => "resumos/dinamicafluidos.tex",
		5 => "resumos/geometria.tex",
		6 => "resumos/probabilidade.tex",
		7 => "resumos/sistemasdinamicos.tex",
		8 => "resumos/teoriacomputacao.tex",
		9 => "resumos/algebratn.tex",
		10 => "resumos/mecanica.tex",
		11 => "resumos/educacaomatematica.tex",
	);

	protected $normalize_to_tex = array(
      'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'\^A', 'Ã'=>'\~A', 'Ä'=>'\"A',
      'Å'=>'A', 'Æ'=>'A', 'Ç'=>'\c C', 'È'=>'\`E', 'É'=>'\'E', 'Ê'=>'\^E', 'Ë'=>'\"E', 'Ì'=>'\`I', 'Í'=>'\'I', 'Î'=>'I',
      'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'\`O', 'Ó'=>'\'O', 'Ô'=>'\^O', 'Õ'=>'\~O', 'Ö'=>'\"O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
      'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'\'a', 'á'=>'\`a', 'â'=>'\^a', 'ã'=>'\~a', 'ä'=>'a',
      'å'=>'a', 'æ'=>'a', 'ç'=>'\c c', 'è'=>'\`e', 'é'=>'\'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
      'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'\`o', 'ó'=>'\'o', 'ô'=>'\^o', 'õ'=>'\~o', 'ö'=>'\"o', 'ø'=>'o', 'ù'=>'u',
      'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
      'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
    );

	public function get_string_between($string, $start, $end)
	{
	    $string = ' ' . $string;

	    $ini = strpos($string, $start);

	    if ($ini == 0) return '';

	    $ini += strlen($start);

	    $len = strpos($string, $end, $ini) - $ini;

	    return substr($string, $ini, $len);
	}
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

			$tipo_de_arquivo_disponivel['caderno_de_resumos'] = "Caderno de Resumos";
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

	    	if ($tipo_arquivo != "caderno_de_resumos") {

	    		$arquivo_a_ser_gerado = $locais_arquivos['local_relatorios'].$locais_arquivos[$tipo_arquivo];

		    	@unlink($arquivo_a_ser_gerado);
		    	
		    	$relatorio_csv = Writer::createFromPath($arquivo_a_ser_gerado, 'w+');

		    	$linha_arquivo = [];

		    	$linha_arquivo = $relatorio_controller->ConsolidaCabecalhoCSV($tipo_arquivo);

		    	$relatorio_csv->insertOne($linha_arquivo);
	    	}

	    	if ($tipo_arquivo == "cracha" OR $tipo_arquivo == "lista_participante") {
	    		
	    		$finaliza = new FinalizaInscricao();

			    $usuarios_finalizados = $finaliza->retorna_usuarios_inscritos($id_inscricao_evento, $nivel_coordenador);
			    
			    foreach ($usuarios_finalizados as $candidato) {

					$linha_arquivo = [];
					
					$dados_candidato_para_relatorio = [];

					$dados_candidato_para_relatorio['ano_evento'] = $relatorio->ano_evento;

					$dados_candidato_para_relatorio['id_participante'] = $candidato->id_participante;

					foreach ($relatorio_controller->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
					 	$dados_candidato_para_relatorio[$key] = $value;
					}

					$linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];
					
					$linha_arquivo['instituicao'] = $dados_candidato_para_relatorio['instituicao'];
					
					$linha_arquivo['nome_cracha'] = $dados_candidato_para_relatorio['nome_cracha'];

					if ($tipo_arquivo == "lista_participante") {
						
						$linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_participante'])->email;

						foreach ($relatorio_controller->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
								$dados_candidato_para_relatorio[$key] = $value;
						}

						if ($dados_candidato_para_relatorio['apresentar_trabalho']) {

							$linha_arquivo['Deseja apresentar trabalho?'] = "Sim";

							$linha_arquivo['categoria_participante'] = $dados_candidato_para_relatorio['categoria_participante'];

							$linha_arquivo['area_trabalho'] = $dados_candidato_para_relatorio['area_trabalho'];

							$linha_arquivo['tipo_apresentacao'] = $dados_candidato_para_relatorio['tipo_apresentacao'];

							$linha_arquivo['titulo_trabalho'] = $dados_candidato_para_relatorio['titulo_trabalho'];
						}else{
							$linha_arquivo['Deseja apresentar trabalho?'] = "Não";

							$linha_arquivo['categoria_participante'] = null;

							$linha_arquivo['area_trabalho'] = null;

							$linha_arquivo['tipo_apresentacao'] = null;

							$linha_arquivo['titulo_trabalho'] = null;
						}
						
					}
					
					$relatorio_csv->insertOne($linha_arquivo);
			    }
	    	}

	    	if ($tipo_arquivo == "lista_trabalhos_submetidos") {
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
					
					$linha_arquivo['instituicao'] = $dados_candidato_para_relatorio['instituicao'];
						
					$linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_participante'])->email;

					foreach ($relatorio_controller->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
							$dados_candidato_para_relatorio[$key] = $value;
					}

					$linha_arquivo['categoria_participante'] = $dados_candidato_para_relatorio['categoria_participante'];

					$linha_arquivo['area_trabalho'] = $dados_candidato_para_relatorio['area_trabalho'];

					$linha_arquivo['tipo_apresentacao'] = $dados_candidato_para_relatorio['tipo_apresentacao'];

					$linha_arquivo['titulo_trabalho'] = $dados_candidato_para_relatorio['titulo_trabalho'];
					
					if ($dados_candidato_para_relatorio['apresentar_trabalho']) {
						$relatorio_csv->insertOne($linha_arquivo);
					}
			    }
	    	}

	    	if ($tipo_arquivo == "lista_trabalhos_aceitos") {
	    		
	    		$aceitos = new TrabalhoSelecionado();

	    		$trabalhos_aceitos = $aceitos->retorna_trabalhos_selecionados($id_inscricao_evento);
			    
			    foreach ($trabalhos_aceitos as $aceito) {

					$linha_arquivo = [];

					$dados_candidato_para_relatorio = [];

					$dados_candidato_para_relatorio['ano_evento'] = $relatorio->ano_evento;

					$dados_candidato_para_relatorio['id_participante'] = $aceito->id_participante;

					foreach ($relatorio_controller->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
					 $dados_candidato_para_relatorio[$key] = $value;
					}

					$linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];
					
					$linha_arquivo['instituicao'] = $dados_candidato_para_relatorio['instituicao'];
						
					$linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_participante'])->email;

					foreach ($relatorio_controller->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
							$dados_candidato_para_relatorio[$key] = $value;
					}

					$linha_arquivo['categoria_participante'] = $dados_candidato_para_relatorio['categoria_participante'];

					$linha_arquivo['area_trabalho'] = $dados_candidato_para_relatorio['area_trabalho'];

					$linha_arquivo['tipo_apresentacao'] = $dados_candidato_para_relatorio['tipo_apresentacao'];

					$linha_arquivo['titulo_trabalho'] = $dados_candidato_para_relatorio['titulo_trabalho'];
					
					$relatorio_csv->insertOne($linha_arquivo);
			    }
	    	}
	    }

	    if (sizeof($arquivos_para_gerar) > 1) {
	    	
    		$zip = new ZipArchive;
	    
		    $inscricoes_zipadas = 'Arquivos_Diversos_Evento_'.$relatorio_disponivel->ano_evento.'.zip';

		    @unlink($locais_arquivos['arquivo_zip'].$inscricoes_zipadas);
		    
		    if ( $zip->open( $locais_arquivos['arquivo_zip'].$inscricoes_zipadas, ZipArchive::CREATE ) === true ){

		        foreach (glob( $locais_arquivos['local_relatorios']."/*.csv") as $fileName ){
		          $file = basename( $fileName );
		          $zip->addFile( $fileName, $file );
		        }
		    }

		    $zip->close();

		    return Response::download($locais_arquivos['arquivo_zip'].$inscricoes_zipadas, $inscricoes_zipadas);
	    }else{
	    	
	    	return Response::download($locais_arquivos['local_relatorios'].$locais_arquivos[$arquivos_para_gerar[0]], $locais_arquivos[$arquivos_para_gerar[0]]);
	    }

	    notify()->flash('Dados salvos com sucesso!','success');

	    return redirect()->back();
	}
}