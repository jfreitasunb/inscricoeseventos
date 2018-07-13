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
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\ProgramaPos;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Notifications\NotificaNovaInscricao;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\BaseController;
use InscricoesEventos\Http\Controllers\CidadeController;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\RelatorioController;
use Illuminate\Foundation\Auth\RegistersUsers;


/**
* Classe para visualização da página inicial.
*/
class RelatorioPosController extends CoordenadorController
{

	public function getRelatorioPos()
	{

		return view('templates.partials.coordenador.relatorio_pos');
	}

	public function VerFichaIndividual($nome_pdf, $id_aluno_pdf)
	{

		$user = Auth::user();
		
		$relatorio = new ConfiguraInscricaoEvento();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();


		$finalizacoes = new FinalizaInscricao;

		$inscricoes_finalizadas = $finalizacoes->retorna_usuarios_relatorio_individual($relatorio_disponivel->id_inscricao_verao)->paginate(10);


		return view('templates.partials.coordenador.ficha_individual', compact('inscricoes_finalizadas', 'nome_pdf', 'id_aluno_pdf'));

	}

	public function getFichaInscricaoPorCandidato()
	{

		$user = Auth::user();
		
		$relatorio = new ConfiguraInscricaoEvento();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();


		$finalizacoes = new FinalizaInscricao;

		if (session()->has('nome_pdf')) {
			$nome_pdf = session()->get('nome_pdf');
		}else{
			$nome_pdf = null;
		}

		if (session()->has('id_aluno_pdf')) {
			$id_aluno_pdf = session()->get('id_aluno_pdf');
		}else{
			$id_aluno_pdf = null;
		}
		
		$new_user = null;

		$inscricoes_finalizadas = $finalizacoes->retorna_usuarios_relatorio_individual($relatorio_disponivel->id_inscricao_verao, $this->locale_default)->paginate(10);

		return view('templates.partials.coordenador.ficha_individual', compact('inscricoes_finalizadas', 'nome_pdf', 'id_aluno_pdf', 'new_user'));
		
	}

	public function GeraPdfFichaIndividual()
	{

		$user = Auth::user();
		

		$id_inscricao_verao = (int) $_GET['id_inscricao_verao'];
		
		$id_aluno_pdf = (int) $_GET['id_aluno'];

		$ficha = new RelatorioController;
	
		$nome_pdf = $ficha->geraFichaIndividual($id_aluno_pdf, $this->locale_default);
      	
      	
      	return redirect()->back()->with(compact('nome_pdf','id_aluno_pdf'));
	}
}