<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, TipoParticipacao};
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

		$array_nao_finalizadas = [];

		$array_problemas_na_finalizacao = [];

		foreach ($nao_finalizadas as $nao_finalizou) {
			$array_nao_finalizadas[] = $nao_finalizou->id_participante;
		}

		foreach ($problemas_na_finalizacao as $problema) {
			$array_problemas_na_finalizacao[] = $problema->id_participante;
		}


		$inscricoes_para_analise = array_unique(array_merge($array_problemas_na_finalizacao,$array_nao_finalizadas), SORT_REGULAR);

		foreach ($inscricoes_para_analise as $potencial) {
			dd($potencial);
		}

		return view('templates.partials.admin.inscricoes_com_problemas')->with(compact());
	}

	public function postInscricoesComProblemas(Request $request)
	{

	}
}