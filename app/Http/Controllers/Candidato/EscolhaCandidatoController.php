<?php

namespace InscricoesEventosMat\Http\Controllers\Candidato;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\User;
use InscricoesEventosMat\Models\AssociaEmailsRecomendante;
use InscricoesEventosMat\Models\ConfiguraInscricaoPos;
use InscricoesEventosMat\Models\OfertaCursoVerao;
use InscricoesEventosMat\Models\CartaMotivacao;
use InscricoesEventosMat\Models\ProgramaPos;
use InscricoesEventosMat\Models\DadoPessoalCandidato;
use InscricoesEventosMat\Models\Formacao;
use InscricoesEventosMat\Models\Estado;
use InscricoesEventosMat\Models\DadoAcademico;
use InscricoesEventosMat\Models\EscolhaCursoVerao;
use InscricoesEventosMat\Models\DadoPessoalRecomendante;
use InscricoesEventosMat\Models\ContatoRecomendante;
use InscricoesEventosMat\Models\CartaRecomendacao;
use InscricoesEventosMat\Models\FinalizaInscricao;
use InscricoesEventosMat\Models\Documento;
use InscricoesEventosMat\Models\Paises;
use InscricoesEventosMat\Models\Cidade;
use InscricoesEventosMat\Notifications\NotificaRecomendante;
use InscricoesEventosMat\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use InscricoesEventosMat\Mail\EmailVerification;
use InscricoesEventosMat\Http\Controllers\Controller;
use InscricoesEventosMat\Http\Controllers\AuthController;
use InscricoesEventosMat\Http\Controllers\CidadeController;
use InscricoesEventosMat\Http\Controllers\BaseController;
use InscricoesEventosMat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use InscricoesEventosMat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class EscolhaCandidatoController extends BaseController
{



	/*
/Gravação dos escolhas do Candidato
 */
	public function getEscolhaCandidato()
	{
		$user = $this->SetUser();
		
		$id_user = $user->id_user;

		$locale_candidato = Session::get('locale');
		
		$edital_ativo = new ConfiguraInscricaoPos();

		$id_inscricao_verao = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_verao;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		if ($autoriza_inscricao) {
			$programas_disponiveis = explode("_", $edital_ativo->retorna_inscricao_ativa()->tipo_evento);

			$nome_programa_pos = new ProgramaPos();


			foreach ($programas_disponiveis as $programa) {
				$programa_para_inscricao[$programa] = $nome_programa_pos->pega_programa_pos_mat($programa, $locale_candidato);
			}


			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_user,$id_inscricao_verao);

			if ($status_inscricao) {

				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

			$dados = [];
			$dados['programa_pretendido'] = null;
			$dados['curso_verao'] = null;

			$escolha_candidato = new EscolhaCursoVerao();

			$candidato_ja_escolheu = $escolha_candidato->retorna_escolha_candidato($id_user, $id_inscricao_verao);

			if (is_null($candidato_ja_escolheu)) {

				$dados['programa_pretendido'] = $candidato_ja_escolheu->programa_pretendido;
				$dados['curso_verao'] = $candidato_ja_escolheu->area_pos;
			}

			switch ($locale_candidato) {
			 	case 'en':
			 		$nome_coluna = 'nome_en';
			 		break;

			 	case 'es':
			 		$nome_coluna = 'nome_es';
			 		break;
			 	
			 	default:
			 		$nome_coluna = 'nome_ptbr';
			 		break;
			 }

			$oferta_verao = new OfertaCursoVerao;

			$cursos_verao = $oferta_verao->retorna_cursos_ofertados($id_inscricao_verao, $locale_candidato);
			
			return view('templates.partials.candidato.escolha_candidato')->with(compact('disable','programa_para_inscricao','cursos_verao','dados','nome_coluna'));


		}else{
			
			notify()->flash(trans('mensagens_gerais.inscricao_inativa'),'warning');
			
			return redirect()->route('home');
		}
	}

	public function postEscolhaCandidato(Request $request)
	{

		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;
		
		$edital_ativo = new ConfiguraInscricaoPos();

		$id_inscricao_verao = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_verao;
		$autoriza_inscricao = $edital_ativo->autoriza_inscricao();

		if ($autoriza_inscricao) {
			
			$finaliza_inscricao = new FinalizaInscricao();

			$status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_candidato,$id_inscricao_verao);

			if ($status_inscricao) {

				notify()->flash(trans('mensagens_gerais.inscricao_finalizada'),'warning');

				return redirect()->back();
			}

			$programas_disponiveis = explode("_", $edital_ativo->retorna_inscricao_ativa()->programa);

			$this->validate($request, [
				'historico' => 'required|max:20000',
				'curso_desejado' => 'required',
			]);

			$programa_pretendido = 2;
			
			// if (is_null($request->curso_desejado) and ($request->programa_pretendido === '2')) {
				
			// 	notify()->flash(trans('mensagens_gerais.informe_area'),'warning');

			// 	return redirect()->back();
			// }

			if ($programa_pretendido === 2) {
				
				foreach ($request->curso_desejado as $escolhido) {
					
					$escolhas_curso_verao = new EscolhaCursoVerao();

					$escolhas_curso_verao->id_candidato = $id_candidato;
            		$escolhas_curso_verao->programa_pretendido = $programa_pretendido;
            		$escolhas_curso_verao->curso_verao = $escolhido;
            		$escolhas_curso_verao->id_inscricao_verao = $id_inscricao_verao;
            		$escolhas_curso_verao->save();
				}
			}

			$hist = $request->historico->store('uploads');
			$arquivo = new Documento();
			$arquivo->id_candidato = $id_candidato;
			$arquivo->nome_arquivo = $hist;
			$arquivo->tipo_arquivo = "Histórico";
			$arquivo->id_inscricao_verao = $id_inscricao_verao;
			$arquivo->save();
			
			notify()->flash(trans('mensagens_gerais.mensagem_sucesso'),'success');
			
			return redirect()->route('finalizar.inscricao');
		}else{
			notify()->flash(trans('mensagens_gerais.inscricao_inativa'),'warning');
			
			return redirect()->route('home');
		}
	}
}