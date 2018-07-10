<?php

namespace InscricoesEventosMat\Http\Controllers\Candidato;

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
use InscricoesEventosMat\Models\DadoPessoal;
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
use InscricoesEventosMat\Http\Controllers\RelatorioController;
use InscricoesEventosMat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use InscricoesEventosMat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class StatusCartasController extends BaseController
{

	public function getStatusCartas(){

		$user = $this->SetUser();
		
		$id_user = $user->id_user;

		$edital_ativo = new ConfiguraInscricaoEvento();

		$id_inscricao_pos = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_pos;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		if ($autoriza_inscricao) {
			
			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_user,$id_inscricao_pos);

			if (!$status_inscricao) {

				return redirect()->back();
			}

			$recomendante_candidato = new ContatoRecomendante();

			$recomendantes_candidato = $recomendante_candidato->retorna_recomendante_candidato($id_user,$id_inscricao_pos);
			
			$dados_para_template = [];

			foreach ($recomendantes_candidato as $recomendante) {

				$dado_pessoal_recomendante = new DadoRecomendante();

				$dados_para_template[$recomendante->id_recomendante]['nome_recomendante'] = $dado_pessoal_recomendante->retorna_dados_pessoais_recomendante($recomendante->id_recomendante)->nome_recomendante;

				$carta_recomendacao = new CartaRecomendacao();
				
				$carta_aluno = $carta_recomendacao->retorna_carta_recomendacao($recomendante->id_recomendante,$id_user,$id_inscricao_pos);

				$dados_para_template[$recomendante->id_recomendante]['status_carta'] = $carta_aluno->completada;

			}
			

			return view('templates.partials.candidato.status_cartas',compact('dados_para_template'));

		}else{
			return redirect()->back();
		}
	}
}