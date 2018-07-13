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
use InscricoesEventos\Models\CursoVeraoMat;
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