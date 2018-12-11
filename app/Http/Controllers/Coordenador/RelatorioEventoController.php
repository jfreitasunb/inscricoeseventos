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

	    	if ($tipo_arquivo == "caderno_de_resumos") {

	    		File::deleteDirectory($locais_arquivos['caderno_de_resumos']);

	    		File::isDirectory($locais_arquivos['caderno_de_resumos']) or File::makeDirectory($locais_arquivos['caderno_de_resumos'],0775,true);

	    		File::copyDirectory( storage_path("app/latex_templates/caderno_de_resumos/"), $locais_arquivos['caderno_de_resumos']);
	    		
	    		$arquivo_capa_creditos = $locais_arquivos['caderno_de_resumos']."/obj/cap-cred-pref.tex";

	    		$aceitos = new TrabalhoSelecionado();

	    		$trabalhos_aceitos = $aceitos->retorna_trabalhos_selecionados($id_inscricao_evento);

	    		$detalhes = new ConfiguraDetalhesEvento();

	    		$detalhes_evento_corrente = $detalhes->retorna_detalhes_evento($id_inscricao_evento);

	    		$data_inicio_evento = explode("-", $detalhes_evento_corrente->inicio_evento)[2];

	    		$data_fim_evento = explode("-", $detalhes_evento_corrente->fim_evento)[2];

	    		$ano_realizacao_evento = explode("-", $detalhes_evento_corrente->inicio_evento)[0];

	    		$mes_realizacao_evento = $this->array_meses[ltrim(explode("-", $detalhes_evento_corrente->inicio_evento)[1], '0')];

	    		$edicao = explode("_", $detalhes_evento_corrente->titulo_evento);

	    		$edicao_verao = explode(" ", $edicao[0])[0];

	    		$edicao_workshop = explode(" ", $edicao[1])[0];
			    
			    $str=file_get_contents($arquivo_capa_creditos);

			    $str=str_replace("edicao_verao", $edicao_verao, $str);

			    $str=str_replace("edicao_workshop", $edicao_workshop, $str);

			    $str=str_replace("data_inicio_evento", $data_inicio_evento, $str);

			    $str=str_replace("data_fim_evento", $data_fim_evento, $str);

			    $str=str_replace("ano_realizacao_evento", $ano_realizacao_evento, $str);

			    $str=str_replace("mes_realizacao_evento", $mes_realizacao_evento, $str);

			    file_put_contents($arquivo_capa_creditos, $str);

			    $dados_resumo = [];
			    
			    foreach ($trabalhos_aceitos as $aceito) {

			    	$total_aceitos_por_area = $aceitos->total_trabalhos_por_area($id_inscricao_evento, $aceito->id_area_trabalho);

			    	$arquivo_area = $locais_arquivos['caderno_de_resumos'].$this->array_arquivos_resumos[$aceito->id_area_trabalho];

			    	if ($aceito->id_tipo_apresentacao == 1) {

						foreach ($relatorio_controller->ConsolidaDadosPessoais($aceito->id_participante) as $key => $value) {
						 $dados_candidato_para_relatorio[$key] = $value;
						}

						$nome = $this->titleCase($dados_candidato_para_relatorio['nome']);

						$dados_resumo[$nome]['email_autor'] = $dados_candidato_para_relatorio['email'];

						$dados_resumo[$nome]['instituicao_autor'] = $dados_candidato_para_relatorio['instituicao'];

						$trabalho_enviado = new TrabalhoSubmetido();

						$trabalho = $trabalho_enviado->retorna_trabalho_submetido($aceito->id_participante, $id_inscricao_evento);

						$dados_resumo[$nome]['autor_trabalho'] = $trabalho->autor_trabalho;

						$dados_resumo[$nome]['titulo_trabalho'] = $trabalho->titulo_trabalho;

						$dados_resumo[$nome]['abstract_trabalho'] = $trabalho->abstract_trabalho;
						
			    		if (sizeof($dados_resumo) == $total_aceitos_por_area) {
			    			
			    			ksort($dados_resumo);
			    			
			    			$str=file_get_contents($arquivo_area);

			    			$parsed = $this->get_string_between($str, '%inicio_bloco_repetir', '%fim_bloco_repetir');
			    			
			    			foreach ($dados_resumo as $key => $value) {
			    				
								$words = explode(" ", $key);
								
								$label_autor = "";
								
								foreach ($words as $letter) {
    								$label_autor .= strtolower(substr($letter, 0, 1));
								}

			    				$str = str_replace("nome_autor", $key, $str);

			    				$str = str_replace("email_autor", $value['email_autor'], $str);

			    				$str = str_replace("instituicao_autor", $value['instituicao_autor'], $str);

			    				$str = str_replace("autor_trabalho", $value['autor_trabalho'], $str);

			    				$str = str_replace("titulo_trabalho", $value['titulo_trabalho'], $str);

			    				$str = str_replace("texto_trabalho", $value['abstract_trabalho'], $str);

			    				$str = str_replace("label_autor", $label_autor, $str);

			    				$str .= "%inicio_bloco_repetir\n".$parsed."%fim_bloco_repetir\n";

			    				file_put_contents($arquivo_area, $str);
			    			}

			    			$str .= "\n\\clearpage";

			    			file_put_contents($arquivo_area, $str);

			    			dd($dados_resumo);
			    			
			    		}
			    		
			    	}
			    }
	    	}
	    }

	    dd("aquy");
	    
	    
	    if (sizeof($arquivos_para_gerar) > 1) {
	    	
	    	$zip = new ZipArchive;
		    
		    $inscricoes_zipadas = 'Arquivos_Diversos_Evento_'.$relatorio_disponivel->ano_evento.'.zip';

		    @unlink($locais_arquivos['arquivo_zip'].$inscricoes_zipadas);
		    
		    if ( $zip->open( $locais_arquivos['arquivo_zip'].$inscricoes_zipadas, ZipArchive::CREATE ) === true ){

		        foreach (glob( $locais_arquivos['local_relatorios'].'*.csv') as $fileName ){
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