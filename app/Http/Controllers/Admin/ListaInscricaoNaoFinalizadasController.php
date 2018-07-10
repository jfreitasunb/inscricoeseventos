<?php

namespace Veraomat\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use Veraomat\Models\{User, ConfiguraInscricaoPos, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao,  DadoPessoalCandidato, EscolhaCursoVerao};
use Illuminate\Http\Request;
use Veraomat\Mail\EmailVerification;
use Veraomat\Http\Controllers\Controller;
use Veraomat\Http\Controllers\AuthController;
use Veraomat\Http\Controllers\CoordenadorController;
use Veraomat\Http\Controllers\DataTable\UserController;
use Veraomat\Notifications\NotificaRecomendante;
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
		$relatorio = new ConfiguraInscricaoPos();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();

		$tipo_programa_pos = new EscolhaCursoVerao;

		$inscricoes_nao_finalizadas = $tipo_programa_pos->usuarios_nao_finalizados($relatorio_disponivel->id_inscricao_verao)->paginate(10);

		return view('templates.partials.admin.nao_finalizadas')->with(compact('inscricoes_nao_finalizadas'));
	}
}