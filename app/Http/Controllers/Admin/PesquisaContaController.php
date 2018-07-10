<?php

namespace InscricoesEventosMat\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\{User, ConfiguraInscricaoPos, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, ContatoRecomendante, DadoRecomendante, DadoPessoalCandidato, EscolhaCandidato, CartaRecomendacao, AssociaEmailsRecomendante};
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
class PesquisaContaController extends AdminController
{

	public function getPesquisaConta()
	{	
		$modo_pesquisa = true;

		$tipo_pesquisa = $this->pesquisa;

		return view('templates.partials.admin.ativa_conta')->with(compact('modo_pesquisa', 'tipo_pesquisa'));
	}

	

	public function postPesquisaConta(Request $request)
	{
		
		$tipo_pesquisa = $this->pesquisa;

		$this->validate($request, [
			'campo_pesquisa' => 'required',
		]);

		$pesquisar_por = $request->tipo_pesquisa;

		$usuario = new User();

		switch ($pesquisar_por) {
			case 'nome':
				$termo_pesquisado = strtr($request->campo_pesquisa, $this->unwanted_array);

				$users = $usuario->retorna_user_por_nome($termo_pesquisado);
				
				break;
			
			default:
				$termo_pesquisado = strtolower(trim($request->campo_pesquisa));
				
				$users = $usuario->retorna_user_por_email($termo_pesquisado);

				break;
		}

		if (!is_null($users)) {
			
			$modo_pesquisa = false;

			return view('templates.partials.admin.ativa_conta')->with(compact('modo_pesquisa', 'users', 'tipo_pesquisa', 'pesquisar_por', 'termo_pesquisado'));
		}else{
			notify()->flash('Não existe nenhuma conta registrada com o e-mail: '.$termo_pesquisado.'!','error');
			return redirect()->route('pesquisa.usuario');
		}
	}

	public function postAlteraAtivaConta(Request $request)
	{
		if ($request->cancelar === 'Cancelar'){

			return redirect()->route('pesquisa.usuario');
		}

		$this->validate($request, [
			'email' => 'required|email',
			'id_user' => 'required',
			'locale' => 'required',
			'user_type' => 'required',
			'ativo' => 'required',
		]);

		$id_user = (int)$request->id_user;

		$novos_dados_usuario['email'] = strtolower(trim($request->email));
		$novos_dados_usuario['locale'] = strtolower(trim($request->locale));
		$novos_dados_usuario['validation_code'] = NULL;
		$novos_dados_usuario['user_type'] = strtolower(trim($request->user_type));
		$novos_dados_usuario['ativo'] = (strtolower(trim($request->ativo)) == 'sim' ? 1 : 0);
		
		$atualiza_usuario = User::find($id_user);

		$pesquisa_usuario = new User;

		$pesquisa_email = $pesquisa_usuario->retorna_user_por_email($novos_dados_usuario['email']);

		if (!is_null($pesquisa_email)) {
			if ($atualiza_usuario->email === $pesquisa_email->email) {
				$atualiza_usuario->update($novos_dados_usuario);
			}else{

				notify()->flash('Já existe uma conta registrada com o e-mail: '.$novos_dados_usuario['email'].'!','error');
				return redirect()->back();
			}
		}else{
			$atualiza_usuario->update($novos_dados_usuario);
		}
		
		notify()->flash('Dados atualizados com sucesso!','success');
		
		return redirect()->back();

		
	}
}