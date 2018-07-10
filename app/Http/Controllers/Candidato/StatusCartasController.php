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
use Veraomat\Models\DadoPessoal;
use Veraomat\Models\Formacao;
use Veraomat\Models\Estado;
use Veraomat\Models\DadoAcademico;
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
use Veraomat\Http\Controllers\RelatorioController;
use Veraomat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Veraomat\Http\Requests;
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

		$edital_ativo = new ConfiguraInscricaoPos();

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