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
use Veraomat\Models\ProgramaPos;
use Veraomat\Models\DadoPessoal;
use Veraomat\Models\Formacao;
use Veraomat\Models\Estado;
use Veraomat\Models\DadoAcademico;
use Veraomat\Models\EscolhaCursoVerao;
use Veraomat\Models\DadoRecomendante;
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
use Veraomat\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Veraomat\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class MotivacaoDocumentosController extends BaseController
{

	public function getMotivacaoDocumentos()
	{

		$user = $this->SetUser();
		
		$id_candidato = $user->id_user;

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
			}else{

				return view('templates.partials.candidato.motivacao_documentos');
				
			}
		}else{
			notify()->flash(trans('mensagens_gerais.inscricao_inativa'),'warning');
			
			return redirect()->route('home');
		}
	}

	public function postMotivacaoDocumentos(Request $request)
	{
		$this->validate($request, [
			'documentos_pessoais' => 'required|max:20000',
			'historico' => 'required|max:20000',
		]);

			$user = $this->SetUser();
			
			$id_candidato = $user->id_user;

			$edital_ativo = new ConfiguraInscricaoPos();

			$id_inscricao_verao = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_verao;
			
			$doc_pessoais = $request->documentos_pessoais->store('uploads');
			$arquivo = new Documento();
			$arquivo->id_candidato = $id_candidato;
			$arquivo->nome_arquivo = $doc_pessoais;
			$arquivo->tipo_arquivo = "Documentos";
			$arquivo->id_inscricao_verao = $id_inscricao_verao;
			$arquivo->save();

			$hist = $request->historico->store('uploads');
			$arquivo = new Documento();
			$arquivo->id_candidato = $id_candidato;
			$arquivo->nome_arquivo = $hist;
			$arquivo->tipo_arquivo = "Histórico";
			$arquivo->id_inscricao_verao = $id_inscricao_verao;
			$arquivo->save();

			notify()->flash(trans('mensagens_gerais.mensagem_sucesso'),'success');

			return redirect()->route('finalizar.inscricao');		
	}
}