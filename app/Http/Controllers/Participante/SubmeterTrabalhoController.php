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

		$participacao = new TipoParticipacao();

		$dados_participacao = $participacao->retorna_participacao($evento_corrente->id_inscricao_evento, $id_participante);

		// if (count($dados_participacao) == 0) {
		// 	dd("sem dados");
		// }

		// $dados_academicos = new DadoAcademicoCandidato();

		// $tipo_formacao = new Formacao();

		// $graduacao = $tipo_formacao->where('nivel','Graduação')->whereNotNull($nome_coluna)->pluck($nome_coluna,'id')->prepend(trans('mensagens_gerais.selecionar'),'');

		// $pos = $tipo_formacao->where('nivel','Pós-Graduação')->whereNotNull($nome_coluna)->pluck($nome_coluna,'id')->prepend(trans('mensagens_gerais.selecionar'),'');;

		// $dados_academicos_candidato = $dados_academicos->retorna_dados_academicos($id_candidato);

		// $nivel_candidato[0] = 'Especialista';
		// $nivel_candidato[1] = 'Mestrado';
		// $nivel_candidato[2] = 'Doutorado';


		// if (is_null($dados_academicos_candidato)) {
		// 	$dados = [];
		// 	$dados['curso_graduacao'] = '';
		// 	$dados['tipo_curso_graduacao'] = '';
		// 	$dados['instituicao_graduacao'] = '';
		// 	$dados['ano_conclusao_graduacao'] = '';
		// 	$dados['curso_pos'] = '';
		// 	$dados['tipo_curso_pos'] = '';
		// 	// $dados['nivel_pos'] = '';
		// 	$dados['instituicao_pos'] = '';
		// 	$dados['ano_conclusao_pos'] = '';
		// 	return view('templates.partials.candidato.dados_academicos')->with(compact('dados', 'graduacao', 'nivel_candidato','pos'));
		// }else{
		// 	$dados['curso_graduacao'] = $dados_academicos_candidato->curso_graduacao;
		// 	$dados['tipo_curso_graduacao'] = $dados_academicos_candidato->tipo_curso_graduacao;
		// 	$dados['instituicao_graduacao'] = $dados_academicos_candidato->instituicao_graduacao;
		// 	$dados['ano_conclusao_graduacao'] = $dados_academicos_candidato->ano_conclusao_graduacao;
		// 	$dados['curso_pos'] = $dados_academicos_candidato->curso_pos;
		// 	// $dados['nivel_pos'] = $dados_academicos_candidato->nivel_pos;
		// 	$dados['tipo_curso_pos'] = $dados_academicos_candidato->tipo_curso_pos;
		// 	$dados['instituicao_pos'] = $dados_academicos_candidato->instituicao_pos;
		// 	$dados['ano_conclusao_pos'] = $dados_academicos_candidato->ano_conclusao_pos;


			// return view('templates.partials.candidato.dados_academicos')->with(compact('dados', 'graduacao', 'nivel_candidato', 'pos'));
		// }
		// 
		
		$categoria = new CategoriaParticipante();

		$categorias = $categoria->pega_nome_categoria();

		$tipo_apresentacao = new TipoApresentacao();

		$tipos_apresentacao = $tipo_apresentacao->pega_tipo_apresentacao();

		$area_pos = new AreaPosMat();

		$secao = $area_pos->retorna_areas_evento($id_area_evento, $locale_candidato);
		
		return view('templates.partials.participante.submete_trabalho')->with(compact('categorias', 'tipos_apresentacao', 'secao'));
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

			$submeter_trabalho->id_participante = $id_participante;
			$submeter_trabalho->id_area_trabalho = $id_area_trabalho;
			$submeter_trabalho->id_inscricao_evento = $evento_corrente->id_inscricao_evento;
			$submeter_trabalho->titulo_trabalho = $titulo_trabalho;
			$submeter_trabalho->autor_trabalho = $autor_trabalho;
			$submeter_trabalho->abstract_trabalho = $abstract_trabalho;

			$submeter_trabalho->save();

		}else{
			$apresentar_trabalho = 0;
			$id_tipo_apresentacao = null;
		}	

		$nova_participacao = new TipoParticipacao();

		$nova_participacao->id_participante = $id_participante;
		$nova_participacao->id_categoria_participante = $id_categoria_participante;
		$nova_participacao->id_inscricao_evento = $evento_corrente->id_inscricao_evento;
		$nova_participacao->apresentar_trabalho = $apresentar_trabalho;
		$nova_participacao->id_tipo_apresentacao = $id_tipo_apresentacao;

		$nova_participacao->save();



		
		

		// $user = $this->SetUser();
		
		// $id_candidato = $user->id_user;
		
		// $formacao = new Formacao;

		// $nivel_candidato[0] = 'Especialista';
		// $nivel_candidato[1] = 'Mestrado';
		// $nivel_candidato[2] = 'Doutorado';

		// $dados_academicos = DadoAcademicoCandidato::find($id_candidato);

		// $cria_dados_academicos['curso_graduacao'] = Purifier::clean(trim($request->input('curso_graduacao')));
		// $cria_dados_academicos['tipo_curso_graduacao'] = (int)Purifier::clean(trim($request->input('tipo_curso_graduacao')));
		// $cria_dados_academicos['instituicao_graduacao'] = Purifier::clean(trim($request->input('instituicao_graduacao')));
		// $cria_dados_academicos['ano_conclusao_graduacao'] = (int)Purifier::clean(trim($request->input('ano_conclusao_graduacao')));
		// $cria_dados_academicos['curso_pos'] = Purifier::clean(trim($request->input('curso_pos')));
		
		// if (is_null(($request->input('tipo_curso_pos')))) {
		// 	$cria_dados_academicos['tipo_curso_pos'] = 1;
		// }else{
		// 	$cria_dados_academicos['tipo_curso_pos'] = (int)Purifier::clean(trim($request->input('tipo_curso_pos')));
		// }

		// $cria_dados_academicos['instituicao_pos'] = Purifier::clean(trim($request->input('instituicao_pos')));
		// $cria_dados_academicos['ano_conclusao_pos'] = (int)Purifier::clean(trim($request->input('ano_conclusao_pos')));

		// if (is_null($dados_academicos)) {
		// 	$cria_dados_academicos = new DadoAcademicoCandidato();
		// 	$cria_dados_academicos->id_candidato = $id_candidato;
		// 	$cria_dados_academicos->curso_graduacao = Purifier::clean(trim($request->input('curso_graduacao')));
		// 	$cria_dados_academicos->tipo_curso_graduacao = (int)Purifier::clean(trim($request->input('tipo_curso_graduacao')));
		// 	$cria_dados_academicos->instituicao_graduacao = Purifier::clean(trim($request->input('instituicao_graduacao')));
		// 	$cria_dados_academicos->ano_conclusao_graduacao = (int)Purifier::clean(trim($request->input('ano_conclusao_graduacao')));
		// 	$cria_dados_academicos->curso_pos = Purifier::clean(trim($request->input('curso_pos')));
		// 	if (is_null(($request->input('tipo_curso_pos')))) {
		// 		$cria_dados_academicos['tipo_curso_pos'] = 1;
		// 	}else{
		// 		$cria_dados_academicos['tipo_curso_pos'] = (int)Purifier::clean(trim($request->input('tipo_curso_pos')));
		// 	}
		// 	$cria_dados_academicos->instituicao_pos = Purifier::clean(trim($request->input('instituicao_pos')));
		// 	$cria_dados_academicos->ano_conclusao_pos = (int)Purifier::clean(trim($request->input('ano_conclusao_pos')));

		// 	$cria_dados_academicos->save();

		// }else{
			

		// 	$dados_academicos->update($cria_dados_academicos);
		// }
		dd("morreu aqui");
		return redirect()->route('finalizar.inscricao');
	}
}