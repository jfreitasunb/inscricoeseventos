<?php

namespace InscricoesEventosMat\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Purifier;
use Carbon\Carbon;
use InscricoesEventosMat\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, RelatorioController, FinalizaInscricao, DadoPessoal};
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