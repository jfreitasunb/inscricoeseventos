<?php

namespace InscricoesEventosMat\Http\Controllers\Coordenador;

use Auth;
use DB;
use Mail;
use Session;
use File;
use PDF;
use Notification;
use Carbon\Carbon;
use InscricoesEventosMat\Models\User;
use InscricoesEventosMat\Models\ConfiguraInscricaoPos;
use InscricoesEventosMat\Models\AreaPosMat;
use InscricoesEventosMat\Models\CartaRecomendacao;
use InscricoesEventosMat\Models\Formacao;
use InscricoesEventosMat\Models\ProgramaPos;
use InscricoesEventosMat\Models\FinalizaInscricao;
use InscricoesEventosMat\Notifications\NotificaNovaInscricao;
use Illuminate\Http\Request;
use InscricoesEventosMat\Mail\EmailVerification;
use InscricoesEventosMat\Http\Controllers\BaseController;
use InscricoesEventosMat\Http\Controllers\CidadeController;
use InscricoesEventosMat\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\RegistersUsers;


/**
* Classe para visualização da página inicial.
*/
class EditarFormacaoController extends CoordenadorController
{

	public function getEditarFormacao()
	{
		$tipo_formacao = Formacao::orderBy('id')->get()->all();

		return view('templates.partials.coordenador.editar_formacao')->with(compact('tipo_formacao'));
	}

	public function postEditarFormacao(Request $request)
	{

		$this->validate($request, [
			'id' => 'required',
			'tipo_ptbr' => 'required',
			'tipo_en' => 'required',
			'tipo_es' => 'required',
		]);



		$id = (int)$request->id;

		$dados_formacao = [
			'tipo_ptbr' => trim($request->tipo_ptbr),
			'tipo_en' => trim($request->tipo_en),
			'tipo_es' => trim($request->tipo_es),
		];

		$formacao = Formacao::find($id);

		$status_atualizacao = $formacao->update($dados_formacao);

		if ($status_atualizacao) {
			notify()->flash('Dados salvos com sucesso.','success', [
				'timer' => 2000,
			]);
		
		}else{
			notify()->flash('Ocorreu um erro. Tente novamente mais tarde.','error', [
				'timer' => 2000,
			]);
		}

		return redirect()->route('editar.formacao');

	}
}