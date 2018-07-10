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
class EditarCursoVeraoController extends CoordenadorController
{

	public function getEditarAreaPos()
	{
		$cursos = new CursoVeraoMat();

		$areas_pos_mat = $cursos->retorna_cursos_de_verao();

		return view('templates.partials.coordenador.editar_area_pos')->with(compact('areas_pos_mat'));
	}

	public function postEditarAreaPos(Request $request)
	{
		$this->validate($request, [
			'id_curso_verao' => 'required',
			'nome_ptbr' => 'required',
			'nome_en' => 'required',
			'nome_es' => 'required',
		]);

		$id_curso_verao = (int)$request->id_curso_verao;

		$dados_area_pos = [
			'nome_ptbr' => trim($request->nome_ptbr),
			'nome_en' => trim($request->nome_en),
			'nome_es' => trim($request->nome_es),
		];

		$area_pos = CursoVeraoMat::find($id_curso_verao);

		$status_atualizacao = $area_pos->update($dados_area_pos);

		if ($status_atualizacao) {
			notify()->flash('Dados salvos com sucesso.','success', [
				'timer' => 2000,
			]);
		
		}else{
			notify()->flash('Ocorreu um erro. Tente novamente mais tarde.','error', [
				'timer' => 2000,
			]);
		}

		return redirect()->route('editar.area.pos');

	}
}