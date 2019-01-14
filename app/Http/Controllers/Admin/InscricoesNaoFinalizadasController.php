<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, ContatoRecomendante, DadoPessoalRecomendante, DadoPessoalCandidato, EscolhaCandidato, CartaRecomendacao, AssociaEmailsRecomendante};
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

		return view('templates.partials.admin.inscricoes_com_problemas')->with(compact());
	}

	public function postInscricoesComProblemas(Request $request)
	{

	}
}