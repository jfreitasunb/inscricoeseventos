<?php

namespace Veraomat\Http\Controllers\Candidato;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use Veraomat\Models\User;
use Veraomat\Models\AssociaEmailsRecomendante;
use Veraomat\Models\ConfiguraInscricaoPos;
use Veraomat\Models\AreaPosMat;
use Veraomat\Models\CartaMotivacao;
use Veraomat\Models\ProgramaPos;
use Veraomat\Models\DadoPessoalCandidato;
use Veraomat\Models\Formacao;
use Veraomat\Models\Estado;
use Veraomat\Models\DadoAcademicoCandidato;
use Veraomat\Models\EscolhaCursoVerao;
use Veraomat\Models\DadoPessoalRecomendante;
use Veraomat\Models\ContatoRecomendante;
use Veraomat\Models\CartaRecomendacao;
use Veraomat\Models\FinalizaInscricao;
use Veraomat\Models\Documento;
use Veraomat\Models\Paises;
use Veraomat\Models\Cidade;
use Veraomat\Notifications\NotificaRecomendante;
use Veraomat\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use Veraomat\Mail\EmailVerification;
use Veraomat\Http\Controllers\Controller;
use Veraomat\Http\Controllers\AuthController;
use Veraomat\Http\Controllers\CidadeController;
use Veraomat\Http\Controllers\BaseController;
use Veraomat\Http\Controllers\RelatorioController;
use Veraomat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Veraomat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class FinalizarInscricaoController extends BaseController
{

	public function getFinalizarInscricao(){

		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;

		$locale_candidato = Session::get('locale');

		$edital_ativo = new ConfiguraInscricaoPos();

		$id_inscricao_verao = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_verao;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();
		$arquivos_editais = storage_path("/app/editais/");

		if ($autoriza_inscricao) {


			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_candidato,$id_inscricao_verao);

			if ($status_inscricao) {

				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

			$dados_pessoais = new DadoPessoalCandidato();

			$dados_pessoais_candidato = $dados_pessoais->retorna_dados_pessoais($id_candidato);
			
			$nome_candidato = User::find($id_candidato)->nome;

			if (is_null($dados_pessoais_candidato)) {
				
				notify()->flash(trans('tela_finalizar_inscricao.falta_dados_pessoais'),'warning');

				return redirect()->route('dados.pessoais');
			}

			$informou_dados_academicos = DadoAcademicoCandidato::find($id_candidato);


			if (is_null($informou_dados_academicos)) {
				
				notify()->flash(trans('tela_finalizar_inscricao.falta_dados_academicos'),'warning');

				return redirect()->route('dados.academicos');
			}

			$informou_escolha = new EscolhaCursoVerao();

			$escolheu = $informou_escolha->retorna_escolha_candidato($id_candidato,$id_inscricao_verao);
			
			if (is_null($escolheu)) {
				
				notify()->flash(trans('tela_finalizar_inscricao.falta_escolha'),'warning');

				return redirect()->route('dados.escolhas');
			}

			$documentos = new Documento();

			$enviou_historico = $documentos->retorna_historico($id_candidato, $id_inscricao_verao);

			// $enviou_documentos = $documentos->retorna_documento($id_candidato, $id_inscricao_verao);

			if (is_null($enviou_historico)) {
				
				notify()->flash(trans('tela_finalizar_inscricao.falta_documentos'),'warning');

				return redirect()->route('motivacao.documentos');
			}
			
			$novo_relatorio = new RelatorioController;

			$ficha_inscricao = $novo_relatorio->geraFichaInscricao($id_candidato, $id_inscricao_verao, $locale_candidato);


			return view('templates.partials.candidato.finalizar_inscricao',compact('ficha_inscricao','nome_candidato'));

		}else{
			notify()->flash(trans('mensagens_gerais.inscricao_inativa'),'warning');
			
			return redirect()->route('home');
		}	
	}

	public function postFinalizarInscricao(Request $request){

		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;

		$locale_fixo = 'en';

		$edital_ativo = new ConfiguraInscricaoPos();

		$id_inscricao_verao = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_verao;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		if ($autoriza_inscricao) {
			
			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_candidato,$id_inscricao_verao);

			if ($status_inscricao) {
				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

			$dados_pessoais_candidato = User::find($id_candidato);

			$escolha_candidato = new EscolhaCursoVerao();

			$programa_pretendido = $escolha_candidato->retorna_escolha_programa($id_candidato,$id_inscricao_verao)->programa_pretendido;
			
			$programa_pos = new ProgramaPos();

			$nome_programa_pos_candidato = $programa_pos->pega_programa_pos_mat($programa_pretendido, $locale_fixo);

			$dados_email_candidato['nome_candidato'] = $dados_pessoais_candidato->nome;
			$dados_email_candidato['programa'] = $nome_programa_pos_candidato;

			$dados_email_candidato['ficha_inscricao'] = $request->ficha_inscricao;
			
			Notification::send(User::find($id_candidato), new NotificaCandidato($dados_email_candidato));

			$finalizar_inscricao = new FinalizaInscricao();

			$id_finalizada_anteriormente = $finalizar_inscricao->select('id')->where('id_candidato',$id_candidato)->where('id_inscricao_verao',$id_inscricao_verao)->pluck('id');

			if (count($id_finalizada_anteriormente)>0){

				DB::table('finaliza_inscricao')->where('id', $id_finalizada_anteriormente[0])->where('id_candidato', $id_candidato)->where('id_inscricao_verao', $id_inscricao_verao)->update(['finalizada' => True]);
			}else{
				
				$finalizar_inscricao->id_candidato = $id_candidato;
				$finalizar_inscricao->id_inscricao_verao = $id_inscricao_verao;
				$finalizar_inscricao->finalizada = true;
				$finalizar_inscricao->save();
			}



			notify()->flash(trans('mensagens_gerais.envio_final'),'success');

			return redirect()->route('home');

		}
	}
}