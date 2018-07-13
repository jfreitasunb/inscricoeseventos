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
class EditarInscricaoController extends AdminController
{

	public function getEditarInscricao()
	{

		$edital = new ConfiguraInscricaoEvento();

      	$edital_vigente = $edital->retorna_edital_vigente();

      	return view('templates.partials.admin.editar_inscricao')->with(compact('edital_vigente'));
	}

	public function postEditarInscricao(Request $request)
	{
		$this->validate($request, [
			'inicio_inscricao' => 'required|date_format:"Y-m-d"|before:fim_inscricao',
			'fim_inscricao' => 'required|date_format:"Y-m-d"|after:inicio_inscricao',
			'id_tipo_evento' => 'required',
			'nome_evento' => 'required',
			'ano_evento' => 'required',
		]);

		$edital_vigente = ConfiguraInscricaoEvento::find((int)$request->id_inscricao_evento);

		$novos_dados_edital['inicio_inscricao'] = Purifier::clean(trim($request->inicio_inscricao));
		$novos_dados_edital['fim_inscricao'] = Purifier::clean(trim($request->fim_inscricao));
		$novos_dados_edital['id_tipo_evento'] = (int)Purifier::clean(trim($request->id_tipo_evento));
		$novos_dados_edital['nome_evento'] = Purifier::clean(trim($request->nome_evento));
		$novos_dados_edital['ano_evento'] = (int)Purifier::clean(trim($request->ano_evento));

		$edital_vigente->update($novos_dados_edital);

		notify()->flash('Inscrição alterada com sucesso!','success', ['timer' => 3000,]);

		return redirect()->route('editar.inscricao');
	}
}