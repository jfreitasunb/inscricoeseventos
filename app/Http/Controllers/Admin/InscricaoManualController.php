<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Purifier;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, ProgramaPos, RelatorioController, FinalizaInscricao, TipoParticipacao, TrabalhoSubmetido, ConfiguraCategoriaParticipante, ConfiguraTipoApresentacao, DadoPessoalParticipante};
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
use Illuminate\Support\Str;

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
			'id_inscricao_evento' => 'required',
			'nome' => 'required',
			'email' => 'required|email',
			'nome_cracha' => 'required',
			'numero_documento' => 'required',
			'instituicao' => 'required',
			'id_pais' => 'required',
			'id_categoria_participante' => 'required',
			'participante_convidado' => 'required',
			'apresentar_trabalho' => 'required',
			'participante_convidado' => 'required_if:apresentar_trabalho,==,on',
			'id_tipo_apresentacao' => 'required_if:apresentar_trabalho,==,on',
			'titulo_trabalho' => 'required_if:apresentar_trabalho,==,on',
			'autor_trabalho' => 'required_if:apresentar_trabalho,==,on',
			'abstract_trabalho' => 'required_if:apresentar_trabalho,==,on',
			'id_area_trabalho' => 'required_if:apresentar_trabalho,==,on',
		]);

		$nome = Purifier::clean(trim($request->input('nome')));

		$email = strtolower(trim($request->input('email')));
		
		$nome_cracha = Purifier::clean(trim($request->input('nome_cracha')));
		
		$numero_documento = Purifier::clean(trim($request->input('numero_documento')));
		
		$instituicao = Purifier::clean(trim($request->input('instituicao')));
		
		$id_pais = (int) Purifier::clean($request->input('id_pais'));
		
		$id_categoria_participante = (int)$request->id_categoria_participante;

		$participante_convidado = (int)$request->participante_convidado;

		$apresentar_trabalho = $request->apresentar_trabalho;

		$email_existe = (new User())->retorna_user_por_email($email);

		if (is_null($email_existe)) {
			$registra_novo_usuario = new User();

			$registra_novo_usuario->nome = $nome;

			$registra_novo_usuario->email = $email;

			$registra_novo_usuario->password = bcrypt(Str::random(32));

			$registra_novo_usuario->ativo = TRUE;

			$registra_novo_usuario->save();

			$id_participante = $registra_novo_usuario->id_user;

			$cria_participante = new DadoPessoalParticipante();
			
			$cria_participante->id_participante = $id_participante;
			
			$cria_participante->nome_cracha = $nome_cracha;
			
			$cria_participante->numero_documento = $numero_documento;
			
			$cria_participante->instituicao = $instituicao;
			
			$cria_participante->id_pais = $id_pais;
			
			$cria_participante->atualizado = True;
			
			$cria_participante->save();
		}
	}
}