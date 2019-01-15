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
class InscricaoManualController extends AdminController
{

	public function getInscricManual()
	{

		$evento = new ConfiguraInscricaoEvento();

		$evento_corrente = $evento->retorna_edital_vigente();

		$id_inscricao_evento = $evento_corrente->id_inscricao_evento;

		
		return view('templates.partials.admin.inscricoes_com_problemas')->with(compact('contas_para_finalizar', 'id_inscricao_evento'));
	}

	public function postInscricManual(Request $request)
	{
		$this->validate($request, [
			'finalizar_manualmente' => 'required',
		]);

		$finalizar_manualmente = $request->finalizar_manualmente;

		$id_inscricao_evento = $request->id_inscricao_evento;

		

	}
}