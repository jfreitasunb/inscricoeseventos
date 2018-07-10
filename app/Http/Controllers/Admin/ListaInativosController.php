<?php

namespace InscricoesEventosMat\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, ContatoRecomendante, DadoRecomendante, DadoPessoal, EscolhaCandidato, CartaRecomendacao, AssociaEmailsRecomendante};
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
class ListaInativosController extends AdminController
{

	public function getListaInativos()
	{

		$user = new User;

		$usuarios_inativos = $user->retorna_contas_nao_ativas()->paginate(10);

		return view('templates.partials.admin.lista_inativos')->with(compact('usuarios_inativos'));

	}

	public function postListaInativos(Request $request)
	{
		$id_user = (int) $request->id_user;

		$ativa_usuario = [
			'ativo' => TRUE,
			'$validation_code' => NULL,
		];

		$user = User::find($id_user);

		$status = $user->update($ativa_usuario);

		if ($status) {
			notify()->flash('Conta ativada com sucesso!','success', ['timer' => 1500,]);
			return redirect()->route('lista.inativos');
		}else{
			notify()->flash('Erro ao ativar a conta.','error', ['timer' => 1500,]);
			return redirect()->route('lista.inativos');
		}
		
	}
}