<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Auth;
use DB;
use Mail;
use Session;
use File;
use PDF;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\CartaRecomendacao;
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\ProgramaPos;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Notifications\NotificaNovaInscricao;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\BaseController;
use InscricoesEventos\Http\Controllers\CidadeController;
use InscricoesEventos\Http\Controllers\AuthController;
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