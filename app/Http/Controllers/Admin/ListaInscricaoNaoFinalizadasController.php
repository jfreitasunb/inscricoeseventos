<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao,  DadoPessoalCandidato, EscolhaCursoVerao};
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
class ListaInscricaoNaoFinalizadasController extends AdminController
{

	public function getInscricoesNaoFinalizadas()
	{
		$relatorio = new ConfiguraInscricaoEvento();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();

		$tipo_programa_pos = new EscolhaCursoVerao;

		$inscricoes_nao_finalizadas = $tipo_programa_pos->usuarios_nao_finalizados($relatorio_disponivel->id_inscricao_verao)->paginate(10);

		return view('templates.partials.admin.nao_finalizadas')->with(compact('inscricoes_nao_finalizadas'));
	}
}