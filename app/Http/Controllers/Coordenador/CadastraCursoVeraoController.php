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
use InscricoesEventosMat\Models\CursoVeraoMat;
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
class CadastraCursoVeraoController extends CoordenadorController
{

	public function getCadastraAreaPos()
	{
		return view('templates.partials.coordenador.cadastra_area_pos');
	}


	public function postCadastraAreaPos(Request $request)
	{
		$this->validate($request, [
			'nome_ptbr' => 'required',
			'nome_en' => 'required',
			'nome_es' => 'required',
		]);

		$nova_area_pos = new CursoVeraoMat;

		$nova_area_pos->nome_ptbr = trim($request->nome_ptbr);
		$nova_area_pos->nome_en = trim($request->nome_en);
		$nova_area_pos->nome_es = trim($request->nome_es);
		$status_gravacao = $nova_area_pos->save();

		if ($status_gravacao) {
			notify()->flash('Dados salvos com sucesso.','success', [
				'timer' => 2000,
			]);
		
		}else{
			notify()->flash('Ocorreu um erro. Tente novamente mais tarde.','error', [
				'timer' => 2000,
			]);
		}

		return redirect()->route('cadastra.area.pos');
	}
}