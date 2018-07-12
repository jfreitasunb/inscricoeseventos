<?php

namespace InscricoesEventosMat\Http\Controllers\Participante;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\User;
use InscricoesEventosMat\Models\ConfiguraInscricaoEvento;
use InscricoesEventosMat\Models\AreaPosMat;
use InscricoesEventosMat\Models\ProgramaPos;
use InscricoesEventosMat\Models\DadoPessoalParticipante;
use InscricoesEventosMat\Models\Formacao;
use InscricoesEventosMat\Models\Estado;
use InscricoesEventosMat\Models\FinalizaInscricao;
use InscricoesEventosMat\Models\Paises;
use InscricoesEventosMat\Models\Cidade;
use InscricoesEventosMat\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use InscricoesEventosMat\Mail\EmailVerification;
use InscricoesEventosMat\Http\Controllers\Controller;
use InscricoesEventosMat\Http\Controllers\AuthController;
use InscricoesEventosMat\Http\Controllers\CidadeController;
use InscricoesEventosMat\Http\Controllers\BaseController;
use InscricoesEventosMat\Http\Controllers\RelatorioController;
use InscricoesEventosMat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use InscricoesEventosMat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class FinalizarInscricaoController extends BaseController
{

	public function getFinalizarInscricao(){

		$user = $this->SetUser();
		
		$id_participante = $user->id_user;

		$locale_candidato = Session::get('locale');

		$edital_ativo = new ConfiguraInscricaoEvento();

		$id_inscricao_evento = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_evento;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();
		$arquivos_editais = storage_path("/app/editais/");

		if ($autoriza_inscricao) {


			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_participante,$id_inscricao_evento);

			if ($status_inscricao) {

				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

			$dados_pessoais = new DadoPessoalParticipante();

			$dados_pessoais_candidato = $dados_pessoais->retorna_dados_pessoais($id_participante);
			
			$nome_candidato = User::find($id_participante)->nome;

			if (is_null($dados_pessoais_candidato)) {
				
				notify()->flash(trans('tela_finalizar_inscricao.falta_dados_pessoais'),'warning');

				return redirect()->route('dados.pessoais');
			}

			
			$novo_relatorio = new RelatorioController;

			$ficha_inscricao = $novo_relatorio->geraFichaInscricao($id_participante, $id_inscricao_evento, $locale_candidato);


			return view('templates.partials.candidato.finalizar_inscricao',compact('ficha_inscricao','nome_candidato'));

		}else{
			notify()->flash(trans('mensagens_gerais.inscricao_inativa'),'warning');
			
			return redirect()->route('home');
		}	
	}

	public function postFinalizarInscricao(Request $request){

		$user = $this->SetUser();
		
		$id_participante = $user->id_user;

		$locale_fixo = 'en';

		$edital_ativo = new ConfiguraInscricaoEvento();

		$id_inscricao_evento = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_evento;
		$edital = $edital_ativo->retorna_inscricao_ativa()->edital;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		if ($autoriza_inscricao) {
			
			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_participante,$id_inscricao_evento);

			if ($status_inscricao) {
				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

			$dados_pessoais_candidato = User::find($id_participante);

			
			Notification::send(User::find($id_participante), new NotificaCandidato($dados_email_candidato));

			$finalizar_inscricao = new FinalizaInscricao();

			$id_finalizada_anteriormente = $finalizar_inscricao->select('id')->where('id_participante',$id_participante)->where('id_inscricao_evento',$id_inscricao_evento)->pluck('id');

			if (count($id_finalizada_anteriormente)>0){

				DB::table('finaliza_inscricao')->where('id', $id_finalizada_anteriormente[0])->where('id_participante', $id_participante)->where('id_inscricao_evento', $id_inscricao_evento)->update(['finalizada' => True]);
			}else{
				
				$finalizar_inscricao->id_participante = $id_participante;
				$finalizar_inscricao->id_inscricao_evento = $id_inscricao_evento;
				$finalizar_inscricao->finalizada = true;
				$finalizar_inscricao->save();
			}



			notify()->flash(trans('mensagens_gerais.envio_final'),'success');

			return redirect()->route('home');

		}
	}
}