<?php

namespace Veraomat\Http\Controllers\Auth;

use Auth;
use DB;
use Mail;
use Session;
use Purifier;
use Veraomat\Models\User;
use Veraomat\Models\Monitoria;
use Veraomat\Models\DadoPessoal;
use Illuminate\Http\Request;
use Veraomat\Mail\EmailVerification;
use Veraomat\Http\Controllers\Controller;
use Veraomat\Http\Controllers\BaseController;
use Veraomat\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Veraomat\Notifications\AtivaConta;



/**
* Classe para visualização da página inicial.
*/
class AuthController extends BaseController
{

	public function getSignup()
	{	
		return view('auth.registrar');
	}

	public function postSignup( Request $request)
	{

		/*string usada para gerar o código de validação do e-mail. Deve ser grande por questões de segurança.*/

		$STRING_VALIDA_EMAIL = "EsN7Qh2G#U(i24g@LQ=^=NMX74CmuVYZmAPNW?nE3ss6hxtUnvLZBjbD.V[7Y,8LW6trtj%CZWKr^aREKgm]QYW@87xZW4]CEK4mT[yz*o&t6VvzT,E2BGx2j2BP7%Jo{EkRM2Z=Pa4qWu4GeT83)pA]9*rHYctr}L4ka[c6YiweZq=Q>m$7tfPBQoW8wgFm86k8[iDu?HBA[9kiRJeH)7QGnND6oFAbD2Vq(2acX+TAmQbMq3jPUVJ,JPaA]9.)";

		$this->validate($request,[
			'nome' => 'required|max:255',
			'email'  => 'required|unique:users|email|max:255',
			'confirmar-email'  => 'required|email|same:email|max:255',
			'password'  => 'required|min:6',
			'confirmar-password'  => 'required|same:password|min:6',

		]);

		$novo_usuario = new User();

		if (Session::has('locale')) {
			$novo_usuario->locale = Session::get('locale');
		}else{
			$novo_usuario->locale = "pt-br";
		}
		
		$novo_usuario->nome = Purifier::clean(trim($request->input('nome')));
        $novo_usuario->email = Purifier::clean(strtolower(trim($request->input('email'))));
        $novo_usuario->password = bcrypt(trim($request->input('password')));
        $novo_usuario->validation_code =  md5($STRING_VALIDA_EMAIL.$request->input('email').date("d-m-Y H:i:s:u"));

        $novo_usuario->save();
		
		$id_user = $novo_usuario->id_user;

		Notification::send(User::find($id_user), new AtivaConta($novo_usuario->validation_code));

		notify()->flash(trans('tela_registro.menu_registro_sucesso_parte_inicial').' '.$novo_usuario->email.' '.trans('tela_registro.menu_registro_sucesso_parte_final'),'info');

		return redirect()->route('home');

	}

	public function getLogin()
	{	
		return view('auth.login');
	}

	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required',
		]);

		$email = strtolower(trim($request->input('email')));

		$password = trim($request->password);
		
		$user_ativo = DB::table('users')->where('email', $email)->value('ativo');
		

		if (!$user_ativo and !is_null($user_ativo)) {
			notify()->flash(trans('mensagens_gerais.conta_nao_ativa'),'info');
			return redirect()->back();
		}

		if (!Auth::attempt(['email' => $email, 'password' => $password])) {
			notify()->flash(trans('mensagens_gerais.erro_login'), 'error',[
				'timer' => 3000,
			]);
			return redirect()->back();
		}
		

		$user_type = DB::table('users')->where('email', $email)->value('user_type');

		$home = new HomeController();
		if (Session::has('locale')) {
			$home->setaLocale(Session::get('locale'));
		}
		
		Session::put('user_type', $user_type);

		if ($user_type === 'coordenador') {
			notify()->flash(trans('mensagens_gerais.bem_vindo'),'success',[
				'timer' => 1500,
			]);
			return redirect()->intended('coordenador');
		}
		
		if ($user_type === 'admin') {
			notify()->flash(trans('mensagens_gerais.bem_vindo'),'success',[
				'timer' => 1500,
			]);
			return redirect()->intended('admin');
		}

		if ($user_type === 'candidato') {
			notify()->flash(trans('mensagens_gerais.bem_vindo'),'success',[
				'timer' => 1500,
			]);
			return redirect()->intended('candidato');
		}

		if ($user_type === 'recomendante') {
			notify()->flash(trans('mensagens_gerais.bem_vindo'),'success',[
				'timer' => 1500,
			]);
			return redirect()->intended('recomendante');
		}

		notify()->flash('Você não se identificou ainda.','warning',[
				'timer' => 1500,
			]);

		return redirect()->route('home');
	}

	public function getLogout()
	{
		Auth::logout();

		notify()->flash(trans('mensagens_gerais.logout'),'info',[
				'timer' => 1500,
			]);

		return redirect()->route('home');
	}

	public function getMudouSenha()
	{
		if (Auth::check()) {
 		   Auth::logout();

			notify()->flash(trans('mensagens_gerais.senha_alterada'),'success',[
				'timer' => 1500,
			]);
			return redirect()->route('home');
		}else{
			return redirect()->route('home');
		}
	}

	public function verify($token)
	{
	    // The verified method has been added to the user model and chained here
	    // for better readability
	    
	    if (is_null(User::where('validation_code',$token)->first())) {
	    	notify()->flash(trans('mensagens_gerais.conta_ja_ativada'),'success',[
				'timer' => 1500,
			]);
	    }else{
	    	User::where('validation_code',$token)->firstOrFail()->verified();

	    	notify()->flash(trans('mensagens_gerais.conta_ativada'),'success',[
				'timer' => 1500,
			]);
	    }
	    
	    

	    return redirect()->route('home');
	}

	
}