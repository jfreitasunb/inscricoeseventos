<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Purifier;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, RelatorioController, FinalizaInscricao, DadoPessoal};
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
class CriaCoordenadorController extends AdminController
{

	public function getCriarCoordenador()
	{

		$evento = new ConfiguraInscricaoEvento();

      	$evento_vigente = $evento->retorna_edital_vigente();

      	$id_area_evento = $evento_vigente->id_area_evento;

      	$area_pos = new AreaPosMat();

		$secao = $area_pos->retorna_areas_evento($id_area_evento, $this->locale_default);

		dd($id_area_evento);

      	return view('templates.partials.admin.criar_coordenador')->with(compact('evento_vigente', 'secao'));
	}

	public function postCriarCoordenador(Request $request)
	{
		dd("Aqui");
	}
}