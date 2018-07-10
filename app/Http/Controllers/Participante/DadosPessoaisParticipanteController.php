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
					'data_nascimento' => '',
					'numerorg' => '',
					'emissorrg' => '',
					'cpf' => '',
					'data_nascimento' => '',
					'endereco' => '',
					'pais' => '',
					'estado' => '',
					'cidade' => '',
					'cep' => '',
					'celular' => '',
				];
		}else{
			if (!is_null($dados_pessoais->data_nascimento)) {
			
				$nascimento = Carbon::createFromFormat('Y-m-d',$dados_pessoais->data_nascimento);

				$data_nascimento = $nascimento->format('d/m/Y');
			}else{
				$data_nascimento = '';
			}
			

			$nome_pais = new Paises;

			$nome_estado = new Estado;

			$nome_cidade = new Cidade;

			if (!is_null($dados_pessoais->pais)) {
				$pais = $nome_pais->retorna_nome_pais_por_id($dados_pessoais->pais);
			}else{

				$pais = '';
			}

			if (!is_null($dados_pessoais->estado)) {
				$estado = $nome_estado->retorna_nome_estados_por_id($dados_pessoais->pais, $dados_pessoais->estado);
			}else{

				$estado = '';
			}

			if (!is_null($dados_pessoais->cidade)) {
				$cidade = $nome_cidade->retorna_nome_cidade_por_id($dados_pessoais->cidade, $dados_pessoais->estado);
			}else{

				$cidade = '';
			}

			$dados = [
				'nome' => $dados_pessoais->nome,
				'data_nascimento' => $dados_pessoais->data_nascimento,
				'numerorg' => $dados_pessoais->numerorg,
				'emissorrg' => $dados_pessoais->emissorrg,
				'cpf' => $dados_pessoais->cpf,
				'data_nascimento' => $data_nascimento,
				'endereco' => $dados_pessoais->endereco,
				'pais' => $pais,
				'estado' => $estado,
				'cidade' => $cidade,
				'cep' => $dados_pessoais->cep,
				'celular' => $dados_pessoais->celular,
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
					'data_nascimento' => '',
					'numerorg' => '',
					'emissorrg' => '',
					'cpf' => '',
					'data_nascimento' => '',
					'endereco' => '',
					'pais' => '',
					'estado' => '',
					'cidade' => '',
					'cep' => '',
					'celular' => '',
				];
		}else{
			$dados = [
				'nome' => $dados_pessoais->nome,
				'data_nascimento' => $dados_pessoais->data_nascimento,
				'numerorg' => $dados_pessoais->numerorg,
				'emissorrg' => $dados_pessoais->emissorrg,
				'cpf' => $dados_pessoais->cpf,
				'data_nascimento' => $dados_pessoais->data_nascimento,
				'endereco' => $dados_pessoais->endereco,
				'pais' => $dados_pessoais->pais,
				'estado' => $dados_pessoais->estado,
				'cidade' => $dados_pessoais->cidade,
				'cep' => $dados_pessoais->cep,
				'celular' => $dados_pessoais->celular,
			];
		}


		return view('templates.partials.participante.dados_pessoais')->with(compact('countries','dados','editar_dados'));
		
	}

	public function postDadosPessoais(Request $request)
	{
		$this->validate($request, [
			'nome' => 'max:256',
			'data_nascimento' => 'required',
			'numerorg' => 'required|max:21',
			'endereco' => 'required|max:256',
			'cep' => 'required|max:20',
			'pais' => 'required',
			'celular' => 'max:21',
		]);

		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;

		$nascimento = Carbon::createFromFormat('d/m/Y', Purifier::clean(trim($request->data_nascimento)));

		$data_nascimento = $nascimento->format('Y-m-d');
	
		$dados_pessoais = [
			'id_candidato' => $id_candidato,
			'nome' => Purifier::clean(trim($request->input('nome'))),
			'data_nascimento' => $data_nascimento,
			'numerorg' => Purifier::clean(trim($request->input('numerorg'))),
			'endereco' => Purifier::clean(trim($request->input('endereco'))),
			'cep' => Purifier::clean(trim($request->input('cep'))),
			'estado' => $request->input('estado'),
			'cidade' => $request->input('cidade'),
			'pais' => $request->input('pais'),
			'celular' => Purifier::clean(trim($request->input('celular'))),
		];

		$candidato =  DadoPessoalParticipante::find($id_candidato);
		
		$usuario = User::find($id_candidato);

		$update_nome['candidato'] = Purifier::clean(trim($request->input('nome')));;

		if (is_null($candidato)) {
			$cria_candidato = new DadoPessoalParticipante();
			$cria_candidato->id_candidato = $id_candidato;
			$cria_candidato->data_nascimento = $data_nascimento;
			$cria_candidato->numerorg = Purifier::clean(trim($request->input('numerorg')));
			$cria_candidato->endereco = Purifier::clean(trim($request->input('endereco')));
			$cria_candidato->cep = Purifier::clean(trim($request->input('cep')));
			$cria_candidato->estado = $request->input('estado');
			$cria_candidato->cidade = $request->input('cidade');
			$cria_candidato->pais = $request->input('pais');
			$cria_candidato->celular = Purifier::clean(trim($request->input('celular')));
			$cria_candidato->save($dados_pessoais);

			$usuario->update($update_nome);

		}else{
			
			$candidato->update($dados_pessoais);

			$usuario->update($update_nome);
		}


		notify()->flash('Seus dados pessoais foram atualizados.','success');

		$edital_ativo = new ConfiguraInscricaoEvento();

		$id_inscricao_pos = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_pos;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		$finaliza_inscricao = new FinalizaInscricao();

		$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_candidato,$id_inscricao_pos);

		if ($autoriza_inscricao and !$status_inscricao) {
			return redirect()->route('dados.academicos');
		}else{

			return redirect()->back();

		}
	}
}