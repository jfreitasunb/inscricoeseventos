<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, TipoParticipacao, TrabalhoSubmetido, ConfiguraCategoriaParticipante, ConfiguraTipoApresentacao};
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\CoordenadorController;
use InscricoesEventos\Http\Controllers\DataTable\UserController;
use InscricoesEventos\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;

/**
* Classe para visualização da página inicial.
*/
class InscricaoManualController extends AdminController
{

	public function getInscricaoManual()
	{

		$evento = new ConfiguraInscricaoEvento();

		$evento_corrente = $evento->retorna_edital_vigente();

		$id_inscricao_evento = $evento_corrente->id_inscricao_evento;

		$id_area_evento = $evento_corrente->id_area_evento;

		$id_inscricao_evento = $evento_corrente->id_inscricao_evento;
		
		$locale_participante = Session::get('locale');

		$categoria = new ConfiguraCategoriaParticipante();

		$categorias = $categoria->pega_nome_categoria($locale_participante);

		$tipo_apresentacao = new ConfiguraTipoApresentacao();

		$tipos_apresentacao = $tipo_apresentacao->pega_tipo_apresentacao($locale_participante);

		$area_pos = new AreaPosMat();

		$secao = $area_pos->retorna_areas_evento($id_area_evento, $locale_participante);

		$getcountries = new APIController();

		$countries = $getcountries->index();


		return view('templates.partials.admin.inscricao_manual')->with(compact('categorias', 'tipos_apresentacao', 'secao', 'countries', 'id_inscricao_evento'));
	}

	public function postInscricaoManual(Request $request)
	{
		$this->validate($request, [
			'finalizar_manualmente' => 'required',
		]);

		$finalizar_manualmente = $request->finalizar_manualmente;

		$id_inscricao_evento = $request->id_inscricao_evento;

		

	}
}