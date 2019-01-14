<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, TipoParticipacao, TrabalhoSubmetido};
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\CoordenadorController;
use InscricoesEventos\Http\Controllers\DataTable\UserController;
use InscricoesEventos\Notifications\NotificaRecomendante;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;

/**
* Classe para visualização da página inicial.
*/
class InscricoesNaoFinalizadasController extends AdminController
{

	public function getInscricoesComProblemas()
	{

		$evento = new ConfiguraInscricaoEvento();

		$evento_corrente = $evento->retorna_edital_vigente();

		$id_inscricao_evento = $evento_corrente->id_inscricao_evento;

		$nao_finalizadas = (new FinalizaInscricao())->retorna_nao_finalizadas($id_inscricao_evento);

		$problemas_na_finalizacao = (new TipoParticipacao())->retorna_problemas_finalizacao($id_inscricao_evento);

		foreach ($nao_finalizadas as $nao_finalizou) {
			$temporario = (new TipoParticipacao())->retorna_participacao($id_inscricao_evento, $nao_finalizou->id_participante);

			if ($temporario->apresentar_trabalho) {
				
				if (!is_null((new TrabalhoSubmetido())->retorna_trabalho_submetido($nao_finalizou->id_participante, $id_inscricao_evento))) {

					$contas_para_finalizar[$nao_finalizou->id_participante]['nome'] = User::find($nao_finalizou->id_participante)->nome;

					$contas_para_finalizar[$nao_finalizou->id_participante]['apresentar_trabalho'] = "Sim";
					
				}
				
			}else{
				$contas_para_finalizar[$nao_finalizou->id_participante]['nome'] = User::find($nao_finalizou->id_participante)->nome;

				$contas_para_finalizar[$nao_finalizou->id_participante]['apresentar_trabalho'] = "Não";
			}
		}

		foreach ($problemas_na_finalizacao as $problema) {
			
			if ($problema->apresentar_trabalho) {
				
				if (!is_null((new TrabalhoSubmetido())->retorna_trabalho_submetido($problema->id_participante, $id_inscricao_evento))) {

					$contas_para_finalizar[$problema->id_participante]['nome'] = User::find($problema->id_participante)->nome;

					$contas_para_finalizar[$problema->id_participante]['apresentar_trabalho'] = "Sim";
					
				}
				
			}else{
				$contas_para_finalizar[$problema->id_participante]['nome'] = User::find($problema->id_participante)->nome;

				$contas_para_finalizar[$problema->id_participante]['apresentar_trabalho'] = "Não";
			}
		}

		if (!isset($contas_para_finalizar)) {
			
			notify()->flash('Não há inscrições com problemas na finalização!','warning');

			return redirect()->route('home');
		}



		return view('templates.partials.admin.inscricoes_com_problemas')->with(compact('contas_para_finalizar', 'id_inscricao_evento'));
	}

	public function postInscricoesComProblemas(Request $request)
	{
		dd("aqui");

	}
}