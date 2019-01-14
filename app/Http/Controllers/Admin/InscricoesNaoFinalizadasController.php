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

		// $problemas_na_finalizacao = (new TipoParticipacao())->retorna_problemas_finalizacao($id_inscricao_evento);

		dd($nao_finalizadas);

		dd($problemas_na_finalizacao);

		return view('templates.partials.admin.inscricoes_com_problemas')->with(compact());
	}

	public function postInscricoesComProblemas(Request $request)
	{

	}
}