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
use InscricoesEventosMat\Http\Controllers\RelatorioController;
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
		
		$relatorio = new ConfiguraInscricaoPos();

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
		
		$relatorio = new ConfiguraInscricaoPos();

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