<?php

namespace Veraomat\Http\Controllers\Candidato;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use Veraomat\Models\User;
use Veraomat\Models\AssociaEmailsRecomendante;
use Veraomat\Models\ConfiguraInscricaoPos;
use Veraomat\Models\AreaPosMat;
use Veraomat\Models\CartaMotivacao;
use Veraomat\Models\ProgramaPos;
use Veraomat\Models\DadoPessoalCandidato;
use Veraomat\Models\Formacao;
use Veraomat\Models\Estado;
use Veraomat\Models\DadoAcademicoCandidato;
use Veraomat\Models\EscolhaCandidato;
use Veraomat\Models\DadoRecomendante;
use Veraomat\Models\ContatoRecomendante;
use Veraomat\Models\CartaRecomendacao;
use Veraomat\Models\FinalizaInscricao;
use Veraomat\Models\Documento;
use Veraomat\Models\Paises;
use Veraomat\Models\Cidade;
use Veraomat\Notifications\NotificaRecomendante;
use Veraomat\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use Veraomat\Mail\EmailVerification;
use Veraomat\Http\Controllers\Controller;
use Veraomat\Http\Controllers\AuthController;
use Veraomat\Http\Controllers\CidadeController;
use Veraomat\Http\Controllers\BaseController;
use Veraomat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Veraomat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class DadosAcademicosController extends BaseController
{

/*
/Gravação dos dados Acadêmicos
 */
	public function getDadosAcademicos()
	{
		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;
		
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

		$dados_academicos = new DadoAcademicoCandidato();

		$tipo_formacao = new Formacao();

		$graduacao = $tipo_formacao->where('nivel','Graduação')->whereNotNull($nome_coluna)->pluck($nome_coluna,'id')->prepend(trans('mensagens_gerais.selecionar'),'');

		$pos = $tipo_formacao->where('nivel','Pós-Graduação')->whereNotNull($nome_coluna)->pluck($nome_coluna,'id')->prepend(trans('mensagens_gerais.selecionar'),'');;

		$dados_academicos_candidato = $dados_academicos->retorna_dados_academicos($id_candidato);

		$nivel_candidato[0] = 'Especialista';
		$nivel_candidato[1] = 'Mestrado';
		$nivel_candidato[2] = 'Doutorado';


		if (is_null($dados_academicos_candidato)) {
			$dados = [];
			$dados['curso_graduacao'] = '';
			$dados['tipo_curso_graduacao'] = '';
			$dados['instituicao_graduacao'] = '';
			$dados['ano_conclusao_graduacao'] = '';
			$dados['curso_pos'] = '';
			$dados['tipo_curso_pos'] = '';
			// $dados['nivel_pos'] = '';
			$dados['instituicao_pos'] = '';
			$dados['ano_conclusao_pos'] = '';
			return view('templates.partials.candidato.dados_academicos')->with(compact('dados', 'graduacao', 'nivel_candidato','pos'));
		}else{
			$dados['curso_graduacao'] = $dados_academicos_candidato->curso_graduacao;
			$dados['tipo_curso_graduacao'] = $dados_academicos_candidato->tipo_curso_graduacao;
			$dados['instituicao_graduacao'] = $dados_academicos_candidato->instituicao_graduacao;
			$dados['ano_conclusao_graduacao'] = $dados_academicos_candidato->ano_conclusao_graduacao;
			$dados['curso_pos'] = $dados_academicos_candidato->curso_pos;
			// $dados['nivel_pos'] = $dados_academicos_candidato->nivel_pos;
			$dados['tipo_curso_pos'] = $dados_academicos_candidato->tipo_curso_pos;
			$dados['instituicao_pos'] = $dados_academicos_candidato->instituicao_pos;
			$dados['ano_conclusao_pos'] = $dados_academicos_candidato->ano_conclusao_pos;


			return view('templates.partials.candidato.dados_academicos')->with(compact('dados', 'graduacao', 'nivel_candidato', 'pos'));
		}
	}

	public function postDadosAcademicos(Request $request)
	{
		$this->validate($request, [
			'curso_pos' => 'required_without_all:curso_graduacao',
			'tipo_curso_pos' => 'required_without_all:tipo_curso_graduacao',
			'instituicao_pos' => 'required_without_all:instituicao_graduacao',
			'ano_conclusao_pos' => 'required_without_all:ano_conclusao_graduacao',
		]);

		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;
		
		$formacao = new Formacao;

		$nivel_candidato[0] = 'Especialista';
		$nivel_candidato[1] = 'Mestrado';
		$nivel_candidato[2] = 'Doutorado';

		$dados_academicos = DadoAcademicoCandidato::find($id_candidato);

		$cria_dados_academicos['curso_graduacao'] = Purifier::clean(trim($request->input('curso_graduacao')));
		$cria_dados_academicos['tipo_curso_graduacao'] = (int)Purifier::clean(trim($request->input('tipo_curso_graduacao')));
		$cria_dados_academicos['instituicao_graduacao'] = Purifier::clean(trim($request->input('instituicao_graduacao')));
		$cria_dados_academicos['ano_conclusao_graduacao'] = (int)Purifier::clean(trim($request->input('ano_conclusao_graduacao')));
		$cria_dados_academicos['curso_pos'] = Purifier::clean(trim($request->input('curso_pos')));
		
		if (is_null(($request->input('tipo_curso_pos')))) {
			$cria_dados_academicos['tipo_curso_pos'] = 1;
		}else{
			$cria_dados_academicos['tipo_curso_pos'] = (int)Purifier::clean(trim($request->input('tipo_curso_pos')));
		}

		$cria_dados_academicos['instituicao_pos'] = Purifier::clean(trim($request->input('instituicao_pos')));
		$cria_dados_academicos['ano_conclusao_pos'] = (int)Purifier::clean(trim($request->input('ano_conclusao_pos')));

		if (is_null($dados_academicos)) {
			$cria_dados_academicos = new DadoAcademicoCandidato();
			$cria_dados_academicos->id_candidato = $id_candidato;
			$cria_dados_academicos->curso_graduacao = Purifier::clean(trim($request->input('curso_graduacao')));
			$cria_dados_academicos->tipo_curso_graduacao = (int)Purifier::clean(trim($request->input('tipo_curso_graduacao')));
			$cria_dados_academicos->instituicao_graduacao = Purifier::clean(trim($request->input('instituicao_graduacao')));
			$cria_dados_academicos->ano_conclusao_graduacao = (int)Purifier::clean(trim($request->input('ano_conclusao_graduacao')));
			$cria_dados_academicos->curso_pos = Purifier::clean(trim($request->input('curso_pos')));
			if (is_null(($request->input('tipo_curso_pos')))) {
				$cria_dados_academicos['tipo_curso_pos'] = 1;
			}else{
				$cria_dados_academicos['tipo_curso_pos'] = (int)Purifier::clean(trim($request->input('tipo_curso_pos')));
			}
			$cria_dados_academicos->instituicao_pos = Purifier::clean(trim($request->input('instituicao_pos')));
			$cria_dados_academicos->ano_conclusao_pos = (int)Purifier::clean(trim($request->input('ano_conclusao_pos')));

			$cria_dados_academicos->save();

		}else{
			

			$dados_academicos->update($cria_dados_academicos);
		}

		return redirect()->route('dados.escolhas');
	}
}