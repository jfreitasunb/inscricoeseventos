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
class RelatorioEventoController extends CoordenadorController
{

	public function getGeraArquivosDiversos()
	{
		$tipo_de_arquivo_disponivel['cracha'] = "Arquivos para gerar os crachás";
		
		$tipo_de_arquivo_disponivel['lista_participante'] = "Arquivos com a lista de todos os participantes inscritos";
		
		$tipo_de_arquivo_disponivel['lista_trabalhos_submetidos'] = "Arquivos com a lista de trablhos submetidos";

		$tipo_de_arquivo_disponivel['lista_trabalhos_aceitos'] = "Arquivos com a lista dos trablhos aceitos";

		return view('templates.partials.coordenador.relatorio_arquivos_diversos')->with(compact('tipo_de_arquivo_disponivel'));
	}

	public function postGeraArquivosDiversos(Request $request)
	{
		dd($request);
	}
}