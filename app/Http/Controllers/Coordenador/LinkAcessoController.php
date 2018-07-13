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
use InscricoesEventos\Http\Controllers\RelatorioController;
use Illuminate\Foundation\Auth\RegistersUsers;
use UrlSigner;
use URL;

/**
* Classe para visualização da página inicial.
*/
class LinkAcessoController extends CoordenadorController
{

	public function getLinkAcesso()
	{
		$user = Auth::user();
		
		$relatorio = new ConfiguraInscricaoEvento();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();

      	$modo_pesquisa = true;

      	$link_de_acesso = null;

      	return view('templates.partials.coordenador.link_acesso', compact('relatorio_disponivel', 'modo_pesquisa', 'link_de_acesso'));
	}

	public function postLinkAcesso(Request $request)
	{
		$user = Auth::user();

		$this->validate($request, [
			'validade_link' => 'required',
			'tempo_validade' => 'required',
		]);

		$validade_link = (int)$request->validade_link;

		$tempo_validade = $request->tempo_validade;
		
		$relatorio = new ConfiguraInscricaoEvento();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();

      	$modo_pesquisa = false;
		
		$url_temporatia = URL::to('/')."/acesso/arquivos";

		switch ($tempo_validade) {
			case 'horas':
				$valido_por = Carbon::now()->addHours($validade_link);
				break;

			case 'minutos':
				$valido_por = Carbon::now()->addMinutes($validade_link);
				break;

			default:
				$valido_por = Carbon::now()->addDays($validade_link);
				break;
		}

		$link_de_acesso = UrlSigner::sign($url_temporatia, $valido_por);

      	return view('templates.partials.coordenador.link_acesso', compact('relatorio_disponivel', 'modo_pesquisa','link_de_acesso'));
	}
}