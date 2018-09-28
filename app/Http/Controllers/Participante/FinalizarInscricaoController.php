<?php

namespace InscricoesEventos\Http\Controllers\Participante;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\ProgramaPos;
use InscricoesEventos\Models\DadoPessoalParticipante;
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\Estado;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\Paises;
use InscricoesEventos\Models\Cidade;
use InscricoesEventos\Models\TipoParticipacao;
use InscricoesEventos\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\CidadeController;
use InscricoesEventos\Http\Controllers\BaseController;
use InscricoesEventos\Http\Controllers\RelatorioController;
use InscricoesEventos\Http\Controllers\APIController;
use InscricoesEventos\Http\Controllers\LatexTemplateController;
use Illuminate\Foundation\Auth\RegistersUsers;
use InscricoesEventos\Http\Requests;
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

		$locale_participante = Session::get('locale');

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

			$dados_pessoais = new DadoPessoalParticipante();

			$dados_pessoais_candidato = $dados_pessoais->retorna_dados_pessoais($id_participante);
			
			$nome_candidato = User::find($id_participante)->nome;

			$tipo_participacao = new TipoParticipacao();

			$apresenta_trabalho = $tipo_participacao->retorna_participacao($id_inscricao_evento, $id_participante)->apresentar_trabalho;

			if (is_null($dados_pessoais_candidato) or !$dados_pessoais_candidato->atualizado) {
				
				notify()->flash(trans('tela_finalizar_inscricao.falta_dados_pessoais'),'warning');

				return redirect()->route('dados.pessoais');
			}

			
			$novo_relatorio = new RelatorioController;

			if ($apresenta_trabalho) {
				$ficha_abstract = $novo_relatorio->geraAbstract($id_participante, $id_inscricao_evento);
				$sem_abstract = false;
			}else{
				$ficha_abstract = "";
				$sem_abstract = true;
			}
			
			$ficha_inscricao = $novo_relatorio->geraFichaInscricao($id_participante, $id_inscricao_evento, $locale_participante);

			return view('templates.partials.participante.finalizar_inscricao',compact('sem_abstract', 'ficha_abstract', 'ficha_inscricao','nome_candidato'));

		}else{
			notify()->flash(trans('mensagens_gerais.inscricao_inativa'),'warning');
			
			return redirect()->route('home');
		}	
	}

	public function postFinalizarInscricao(Request $request){

		$user = $this->SetUser();
		
		$id_participante = $user->id_user;

		$edital_ativo = new ConfiguraInscricaoEvento();

		$evento = $edital_ativo->retorna_inscricao_ativa();

		$id_inscricao_evento = $evento->id_inscricao_evento;

		$nome_evento = $evento->nome_evento;
		
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();		

		if ($autoriza_inscricao) {
			
			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_participante,$id_inscricao_evento);

			if ($status_inscricao) {
				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

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


			$dados_pessoais = new DadoPessoalParticipante();

			$dados_pessoais_candidato = $dados_pessoais->retorna_dados_pessoais($id_participante);
				
			$nome_candidato = User::find($id_participante)->nome;

			$dados_email_candidato['nome_evento'] = $nome_evento;

			$dados_email_candidato['ficha_inscricao'] = public_path().$request->ficha_inscricao;

			$dados_email_candidato['ficha_abstract'] = public_path().'/'.$request->ficha_abstract;

			$dados_email_candidato['nome_candidato'] = $dados_pessoais_candidato->nome;
				$locale_fixo = 'en';
				
			Notification::send(User::find($id_participante), new NotificaCandidato($dados_email_candidato));

			@unlink($dados_email_candidato['ficha_inscricao']);

			@unlink($dados_email_candidato['ficha_abstract']);
		

			notify()->flash(trans('mensagens_gerais.envio_final'),'success');

			return redirect()->route('home');

		}
	}
}