<?php

namespace InscricoesEventosMat\Http\Controllers\Participante;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\User;
use InscricoesEventosMat\Models\AssociaEmailsRecomendante;
use InscricoesEventosMat\Models\ConfiguraInscricaoEvento;
use InscricoesEventosMat\Models\AreaPosMat;
use InscricoesEventosMat\Models\CategoriaParticipante;
use InscricoesEventosMat\Models\TipoApresentacao;
use InscricoesEventosMat\Models\TipoParticipacao;
use InscricoesEventosMat\Models\TrabalhoSubmetido;
use InscricoesEventosMat\Models\ProgramaPos;
use InscricoesEventosMat\Models\DadoPessoalCandidato;
use InscricoesEventosMat\Models\Formacao;
use InscricoesEventosMat\Models\Estado;
use InscricoesEventosMat\Models\DadoAcademicoCandidato;
use InscricoesEventosMat\Models\EscolhaCandidato;
use InscricoesEventosMat\Models\DadoRecomendante;
use InscricoesEventosMat\Models\ContatoRecomendante;
use InscricoesEventosMat\Models\CartaRecomendacao;
use InscricoesEventosMat\Models\FinalizaInscricao;
use InscricoesEventosMat\Models\Documento;
use InscricoesEventosMat\Models\Paises;
use InscricoesEventosMat\Models\Cidade;
use InscricoesEventosMat\Notifications\NotificaRecomendante;
use InscricoesEventosMat\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use InscricoesEventosMat\Mail\EmailVerification;
use InscricoesEventosMat\Http\Controllers\Controller;
use InscricoesEventosMat\Http\Controllers\AuthController;
use InscricoesEventosMat\Http\Controllers\CidadeController;
use InscricoesEventosMat\Http\Controllers\BaseController;
use InscricoesEventosMat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use InscricoesEventosMat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class SubmeterTrabalhoController extends BaseController
{

/*
/Gravação dos dados Acadêmicos
 */
	public function getSubmeterTrabalho()
	{
		$user = $this->SetUser();
		
		$id_participante = $user->id_user;

		$evento = new ConfiguraInscricaoEvento();

		$evento_corrente = $evento->retorna_edital_vigente();

		$id_area_evento = $evento_corrente->id_area_evento;

		$id_inscricao_evento = $evento_corrente->id_inscricao_evento;
		
		$locale_candidato = Session::get('locale');

		switch ($locale_candidato) {
		 	case 'en':
		 		$nome_coluna = 'tipo_en';
		 		break;

		 	case 'es':
		 		$nome_coluna = 'tipo_es';
		 		break;
		 	
		 	default:
		 		$nome_coluna = 'tipo_ptbr';
		 		break;
		}

		$categoria = new CategoriaParticipante();

		$categorias = $categoria->pega_nome_categoria();

		$tipo_apresentacao = new TipoApresentacao();

		$tipos_apresentacao = $tipo_apresentacao->pega_tipo_apresentacao();

		$area_pos = new AreaPosMat();

		$secao = $area_pos->retorna_areas_evento($id_area_evento, $locale_candidato);

		$participacao = new TipoParticipacao();

		$dados_participacao = $participacao->retorna_participacao($id_inscricao_evento, $id_participante);

		$trabalho_submetido = new TrabalhoSubmetido();

		$dados_trabalho = $trabalho_submetido->retorna_trabalho_submetido($id_participante, $id_inscricao_evento);

		if (is_null($dados_participacao)) {
			
			$dados = [];
			$dados['id_categoria_participante'] = '';
			$dados['id_tipo_apresentacao'] = '';

		}else{

			$dados = [];
			$dados['id_categoria_participante'] = $dados_participacao->id_categoria_participante;
			$dados['id_tipo_apresentacao'] = $dados_participacao->id_tipo_apresentacao;
			
		}

		if (is_null($dados_trabalho)) {
			$dados['titulo_trabalho'] = '';
			$dados['autor_trabalho'] = '';
			$dados['abstract_trabalho'] = '';
			$dados['id_area_trabalho'] = '';

		}else{
			$dados['titulo_trabalho'] = $dados_trabalho->titulo_trabalho;
			$dados['autor_trabalho'] = $dados_trabalho->autor_trabalho;
			$dados['abstract_trabalho'] = $dados_trabalho->abstract_trabalho;
			$dados['id_area_trabalho'] = $dados_trabalho->id_area_trabalho;
		}

		return view('templates.partials.participante.submete_trabalho')->with(compact('categorias', 'tipos_apresentacao', 'secao', 'dados'));
	}

	public function postSubmeterTrabalho(Request $request)
	{
		$this->validate($request, [
			'id_categoria_participante' => 'required',
			'apresentar_trabalho' => 'required',
		]);

		$user = $this->SetUser();

		$evento = new ConfiguraInscricaoEvento();

		$evento_corrente = $evento->retorna_edital_vigente();

		$id_inscricao_evento = $evento_corrente->id_inscricao_evento;
		
		$id_participante = $user->id_user;

		$id_categoria_participante = (int)$request->id_categoria_participante;

		$id_area_trabalho = (int)$request->id_area_trabalho;

		$titulo_trabalho = Purifier::clean(trim($request->titulo_trabalho));

		$id_tipo_apresentacao = (int)Purifier::clean(trim($request->id_tipo_apresentacao));

		$autor_trabalho = Purifier::clean(trim($request->autor_trabalho));

		$abstract_trabalho = Purifier::clean(trim($request->abstract_trabalho));

		$apresentar_trabalho = $request->apresentar_trabalho;
		
		if ($apresentar_trabalho === "on") {

			$this->validate($request, [
				'id_area_trabalho' => 'required',
				'titulo_trabalho' => 'required',
				'autor_trabalho' => 'required',
				'abstract_trabalho' => 'required',
			]);

			$apresentar_trabalho = 1;

			$submeter_trabalho = new TrabalhoSubmetido();

			$submeteu_trabalho = $submeter_trabalho->retorna_trabalho_submetido($id_participante, $id_inscricao_evento);

			if (is_null($submeteu_trabalho)) {
				$submeter_trabalho->id_participante = $id_participante;
				$submeter_trabalho->id_area_trabalho = $id_area_trabalho;
				$submeter_trabalho->id_inscricao_evento = $evento_corrente->id_inscricao_evento;
				$submeter_trabalho->titulo_trabalho = $titulo_trabalho;
				$submeter_trabalho->autor_trabalho = $autor_trabalho;
				$submeter_trabalho->abstract_trabalho = $abstract_trabalho;

				$submeter_trabalho->save();
			}else{
				$atualiza_trabalho = [];
				
				$id = $submeteu_trabalho->id;

				$atualiza_trabalho['id_area_trabalho'] = $submeteu_trabalho->id_area_trabalho;

				$atualiza_trabalho['titulo_trabalho'] = $titulo_trabalho;

				$atualiza_trabalho['autor_trabalho'] = $autor_trabalho;

				$atualiza_trabalho['abstract_trabalho'] = $abstract_trabalho;

				$status_trabalho = $submeteu_trabalho->atualiza_trabalho_submetido($id, $id_inscricao_evento, $id_participante, $atualiza_trabalho);
				
			}

		}else{
			$apresentar_trabalho = 0;
			$id_tipo_apresentacao = null;
		}	

		$nova_participacao = new TipoParticipacao();

		$submeteu_participacao = $nova_participacao->retorna_participacao($id_inscricao_evento, $id_participante);

		if (is_null($submeteu_participacao)) {
			$nova_participacao->id_participante = $id_participante;
			$nova_participacao->id_categoria_participante = $id_categoria_participante;
			$nova_participacao->id_inscricao_evento = $id_inscricao_evento;
			$nova_participacao->apresentar_trabalho = $apresentar_trabalho;
			$nova_participacao->id_tipo_apresentacao = $id_tipo_apresentacao;

			$nova_participacao->save();
		}else{

			$atualiza_participacao = [];

			$id_participacao = $submeteu_participacao->id;

			$atualiza_participacao['id_categoria_participante'] = $id_categoria_participante;

			$atualiza_participacao['apresentar_trabalho'] = $apresentar_trabalho;

			$atualiza_participacao['id_tipo_apresentacao'] = $id_tipo_apresentacao;

			$status_participacao = $submeteu_participacao->atualiza_trabalho_submetido($id_participacao, $id_inscricao_evento, $id_participante, $atualiza_participacao);


		}
		
		if ($status_trabalho AND $status_participacao) {
			notify()->flash(trans('mensagens_gerais.mensagem_sucesso'),'success');
			return redirect()->route('finalizar.inscricao');
		}else{
			notify()->flash(trans('mensagens_gerais.erro'),'error');
			return redirect()->back();
		}
		
	}
}