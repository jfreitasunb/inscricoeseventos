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

		$id_inscricao_evento = (int)$request->id_inscricao_evento;

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

			if ($apresentar_trabalho === "on") {

				$id_area_trabalho = (int)$request->id_area_trabalho;

				$titulo_trabalho = Purifier::clean(trim($request->titulo_trabalho));

				$id_tipo_apresentacao = (int)Purifier::clean(trim($request->id_tipo_apresentacao));

				$autor_trabalho = Purifier::clean(trim($request->autor_trabalho));

				$abstract_trabalho = Purifier::clean(trim($request->abstract_trabalho));

				$apresentar_trabalho = true;

				$submeter_trabalho = new TrabalhoSubmetido();

				$submeter_trabalho->id_participante = $id_participante;
				
				$submeter_trabalho->id_area_trabalho = $id_area_trabalho;
				
				$submeter_trabalho->id_inscricao_evento = $id_inscricao_evento;
				
				$submeter_trabalho->titulo_trabalho = $titulo_trabalho;
				
				$submeter_trabalho->autor_trabalho = $autor_trabalho;
				
				$submeter_trabalho->abstract_trabalho = $abstract_trabalho;

				$status_trabalho = $submeter_trabalho->save();
			}else{
				$apresentar_trabalho = false;
				
				$id_tipo_apresentacao = null;
			
				$status_trabalho = false;

				$nova_participacao = new TipoParticipacao();

				$nova_participacao->id_participante = $id_participante;
				
				$nova_participacao->id_categoria_participante = $id_categoria_participante;
				
				$nova_participacao->id_inscricao_evento = $id_inscricao_evento;
				
				$nova_participacao->apresentar_trabalho = $apresentar_trabalho;
				
				$nova_participacao->id_tipo_apresentacao = $id_tipo_apresentacao;
			
				$nova_participacao->participante_convidado = $participante_convidado;

				$status_participacao = $nova_participacao->save();
			}

			$inicializa_finalizacao = new FinalizaInscricao();

			$inicializa_finalizacao->id_inscricao_evento = $id_inscricao_evento;

			$inicializa_finalizacao->id_participante = $id_participante;

			$inicializa_finalizacao->finalizada = True;

			$inicializa_finalizacao->save();

		}else{

			$id_participante = $email_existe->id_user;

			$participante =  DadoPessoalParticipante::find($id_participante);

			if (is_null($participante)) {
				
				$cria_participante = new DadoPessoalParticipante();
				
				$cria_participante->id_participante = $id_participante;
				
				$cria_participante->nome_cracha = $nome_cracha;
				
				$cria_participante->numero_documento = $numero_documento;
				
				$cria_participante->instituicao = $instituicao;
				
				$cria_participante->id_pais = $id_pais;
				
				$cria_participante->atualizado = True;
				
				$cria_participante->save();

			}else{
				
				$dados_pessoais = [
					'id_participante' => $id_participante,
					'nome' => $nome,
					'nome_cracha' => $nome_cracha,
					'numero_documento' => $numero_documento,
					'instituicao' => $instituicao,
					'id_pais' => $id_pais,
					'atualizado' => True,
				];

				$participante->update($dados_pessoais);

			}

			if ($apresentar_trabalho === "on") {

				$id_area_trabalho = (int)$request->id_area_trabalho;

				$titulo_trabalho = Purifier::clean(trim($request->titulo_trabalho));

				$id_tipo_apresentacao = (int)Purifier::clean(trim($request->id_tipo_apresentacao));

				$autor_trabalho = Purifier::clean(trim($request->autor_trabalho));

				$abstract_trabalho = Purifier::clean(trim($request->abstract_trabalho));

				$apresentar_trabalho = true;

				$submeter_trabalho = new TrabalhoSubmetido();

				$submeteu_trabalho = $submeter_trabalho->retorna_trabalho_submetido($id_participante, $id_inscricao_evento);

				if (is_null($submeteu_trabalho)) {
					$submeter_trabalho->id_participante = $id_participante;
					$submeter_trabalho->id_area_trabalho = $id_area_trabalho;
					$submeter_trabalho->id_inscricao_evento = $id_inscricao_evento;
					$submeter_trabalho->titulo_trabalho = $titulo_trabalho;
					$submeter_trabalho->autor_trabalho = $autor_trabalho;
					$submeter_trabalho->abstract_trabalho = $abstract_trabalho;

					$status_trabalho = $submeter_trabalho->save();
				}else{
					$atualiza_trabalho = [];
					
					$id = $submeteu_trabalho->id;

					$atualiza_trabalho['id_area_trabalho'] = $submeteu_trabalho->id_area_trabalho;

					$atualiza_trabalho['titulo_trabalho'] = $titulo_trabalho;

					$atualiza_trabalho['autor_trabalho'] = $autor_trabalho;

					$atualiza_trabalho['abstract_trabalho'] = $abstract_trabalho;

					$status_trabalho = $submeteu_trabalho->atualiza_trabalho_submetido($id, $id_inscricao_evento, $id_participante, $atualiza_trabalho);
					
				}

			}else{
				$apresentar_trabalho = false;
				$id_tipo_apresentacao = null;
				$status_trabalho = false;
			}	

			$nova_participacao = new TipoParticipacao();

			$submeteu_participacao = $nova_participacao->retorna_participacao($id_inscricao_evento, $id_participante);

			if (is_null($submeteu_participacao)) {


				$nova_participacao->id_participante = $id_participante;
				$nova_participacao->id_categoria_participante = $id_categoria_participante;
				$nova_participacao->id_inscricao_evento = $id_inscricao_evento;
				$nova_participacao->apresentar_trabalho = $apresentar_trabalho;
				$nova_participacao->id_tipo_apresentacao = $id_tipo_apresentacao;
				$nova_participacao->participante_convidado = $participante_convidado;

				$status_participacao = $nova_participacao->save();
			}else{

				$atualiza_participacao = [];

				$id_participacao = $submeteu_participacao->id;

				$atualiza_participacao['id_categoria_participante'] = $id_categoria_participante;

				$atualiza_participacao['apresentar_trabalho'] = $apresentar_trabalho;

				$atualiza_participacao['id_tipo_apresentacao'] = $id_tipo_apresentacao;

				$atualiza_participacao['participante_convidado'] = $participante_convidado;

				$status_participacao = $submeteu_participacao->atualiza_tipo_participacao($id_participacao, $id_inscricao_evento, $id_participante, $atualiza_participacao);

			}

			$finalizar_inscricao = new FinalizaInscricao();

			$id_finalizada_anteriormente = $finalizar_inscricao->select('id')->where('id_participante',$id_participante)->where('id_inscricao_evento',$id_inscricao_evento)->pluck('id');

			if (count($id_finalizada_anteriormente)>0){

				DB::table('finaliza_inscricao')->where('id', $id_finalizada_anteriormente[0])->where('id_participante', $id_participante)->where('id_inscricao_evento', $id_inscricao_evento)->update(['finalizada' => True, 'updated_at' => date('Y-m-d H:i:s')]);
			}else{
				
				$finalizar_inscricao->id_participante = $id_participante;
				
				$finalizar_inscricao->id_inscricao_evento = $id_inscricao_evento;
				
				$finalizar_inscricao->finalizada = true;
				
				$finalizar_inscricao->save();
			}
		}
	
	notify()->flash('Inscrição realizada com sucesso!','success');
	
	return redirect()->route('inscricao.manual');
	
	}
}