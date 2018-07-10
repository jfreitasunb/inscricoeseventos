<?php

namespace InscricoesEventosMat\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao,  DadoPessoalCandidato, EscolhaCursoVerao};
use Illuminate\Http\Request;
use InscricoesEventosMat\Mail\EmailVerification;
use InscricoesEventosMat\Http\Controllers\Controller;
use InscricoesEventosMat\Http\Controllers\AuthController;
use InscricoesEventosMat\Http\Controllers\CoordenadorController;
use InscricoesEventosMat\Http\Controllers\DataTable\UserController;
use InscricoesEventosMat\Notifications\NotificaRecomendante;
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