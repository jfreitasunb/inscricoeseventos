<?php

namespace Veraomat\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use Veraomat\Models\{User, ConfiguraInscricaoPos, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, ContatoRecomendante, DadoRecomendante, DadoPessoal, EscolhaCandidato, CartaRecomendacao, AssociaEmailsRecomendante};
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
class LinkSenhaController extends AdminController
{

	public function getPesquisaLinkMudarSenha()
	{

		$modo_pesquisa = true;

		return view('templates.partials.admin.link_muda_senha')->with(compact('modo_pesquisa'));
	}


	public function postPesquisaLinkMudarSenha(Request $request)
	{

		if ($request->cancelar === 'Limpar'){

			$modo_pesquisa = true;

			return redirect()->route('pesquisa.email.muda.senha');
		}

		$this->validate($request, [
			'email' => 'email|max:256',
		]);

		$email = strtolower(trim($request->email));
		

		$usuario = new User();

		$user = $usuario->retorna_user_por_email($email);

		if (!is_null($user)) {
			
			$modo_pesquisa = false;

			$token_link = str_random(64);

			$url_mudar_senha = url('esqueci/senha').'/'.$token_link;
			$token = (bcrypt($token_link));
			

			DB::table('password_resets')->insert([
				'email' => $user->email,
				'token' => $token,
				'created_at' => Carbon::now(),
			]);

			return view('templates.partials.admin.link_muda_senha')->with(compact('modo_pesquisa', 'user', 'url_mudar_senha'));
		}else{
			notify()->flash('Não existe nenhuma conta registrada com o e-mail: '.$email.'!','error');
			return redirect()->route('pesquisa.usuario');
		}
	}
}