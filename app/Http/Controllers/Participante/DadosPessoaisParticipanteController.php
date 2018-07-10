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
use InscricoesEventosMat\Models\CartaMotivacao;
use InscricoesEventosMat\Models\ProgramaPos;
use InscricoesEventosMat\Models\DadoPessoalParticipante;
use InscricoesEventosMat\Models\Formacao;
use InscricoesEventosMat\Models\Estado;
use InscricoesEventosMat\Models\DadoAcademico;
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
class DadosPessoaisParticipanteController extends BaseController
{

	/*
/Gravação dos dados Pessoais
 */

	public function getDadosPessoais()
	{

		$getcountries = new APIController();

		$countries = $getcountries->index();

		$user = $this->SetUser();
		
		$id_user = $user->id_user;

		$editar_dados = false;
		
		$candidato = new DadoPessoalParticipante();
		$dados_pessoais = $candidato->retorna_dados_pessoais($id_user);

		if (is_null($dados_pessoais)) {
				$dados = [
					'nome' => $user->nome,
					'nome_cracha' => '',
					'numero_documento' => '',
					'instituicao' => '',
					'id_pais' => '',
				];
		}else{
			
			$nome_pais = new Paises;

			if (!is_null($dados_pessoais->pais)) {
				$pais = $nome_pais->retorna_nome_pais_por_id($dados_pessoais->pais);
			}else{

				$pais = '';
			}

			$dados = [
				'nome' => $dados_pessoais->nome,
				'nome_cracha' => $dados_pessoais->nome_cracha,
				'numero_documento' => $dados_pessoais->numero_documento,
				'instituicao' => $dados_pessoais->instituicao,
				'id_pais' => $pais,
			];
		}

		return view('templates.partials.participante.dados_pessoais')->with(compact('countries','dados','editar_dados'));
		
	}

	public function getDadosPessoaisEditar()
	{

		$getcountries = new APIController();

		$countries = $getcountries->index();

		$user = $this->SetUser();
		
		$nome = $user->nome;
		$id_user = $user->id_user;

		$editar_dados = true;
		
		$candidato = new DadoPessoalParticipante();
		$dados_pessoais = $candidato->retorna_dados_pessoais($id_user);

		if (is_null($dados_pessoais)) {
			$dados = [
					'nome' => $user->nome,
					'nome_cracha' => '',
					'numero_documento' => '',
					'instituicao' => '',
					'id_pais' => '',
				];
		}else{
			$dados = [
				'nome' => $dados_pessoais->nome,
				'nome_cracha' => $dados_pessoais->nome_cracha,
				'numero_documento' => $dados_pessoais->numero_documento,
				'instituicao' => $dados_pessoais->instituicao,
				'id_pais' => $dados_pessoais->pais,
			];
		}


		return view('templates.partials.participante.dados_pessoais')->with(compact('countries','dados','editar_dados'));
		
	}

	public function postDadosPessoais(Request $request)
	{
		
		$this->validate($request, [
			'nome' => 'max:256',
			'nome_cracha' => 'required',
			'numero_documento' => 'required',
			'instituicao' => 'required',
			'id_pais' => 'required',
		]);

		$user = $this->SetUser();
		
		$id_participante = $user->id_user;

		$nome = Purifier::clean(trim($request->input('nome')));
		$nome_cracha = Purifier::clean(trim($request->input('nome_cracha')));
		$numero_documento = Purifier::clean(trim($request->input('numero_documento')));
		$instituicao = Purifier::clean(trim($request->input('instituicao')));
		$id_pais = (int) Purifier::clean($request->input('id_pais'));

		$dados_pessoais = [
			'id_participante' => $id_participante,
			'nome' => $nome,
			'nome_cracha' => $nome_cracha,
			'numero_documento' => $numero_documento,
			'instituicao' => $instituicao,
			'id_pais' => $id_pais,
		];

		$participante =  DadoPessoalParticipante::find($id_participante);
		
		$usuario = User::find($id_participante);

		$update_nome['nome'] = $nome;

		if (is_null($participante)) {
			$cria_participante = new DadoPessoalParticipante();
			$cria_participante->id_participante = $id_participante;
			$cria_participante->nome_cracha = $nome_cracha;
			$cria_participante->numero_documento = $numero_documento;
			$cria_participante->instituicao = $instituicao;
			$cria_participante->id_pais = $id_pais;
			$cria_participante->save($dados_pessoais);

			$usuario->update($update_nome);

		}else{
			
			$participante->update($dados_pessoais);

			$usuario->update($update_nome);
		}


		notify()->flash('Seus dados pessoais foram atualizados.','success');

		$edital_ativo = new ConfiguraInscricaoEvento();

		$id_inscricao_pos = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_pos;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		$finaliza_inscricao = new FinalizaInscricao();

		$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_participante,$id_inscricao_pos);
		dd("parou");
		if ($autoriza_inscricao and !$status_inscricao) {
			return redirect()->route('dados.academicos');
		}else{

			return redirect()->back();

		}
	}
}