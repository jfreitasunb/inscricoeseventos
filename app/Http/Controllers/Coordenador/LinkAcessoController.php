<?php

namespace Veraomat\Http\Controllers\Coordenador;

use Auth;
use DB;
use Mail;
use Session;
use File;
use PDF;
use Notification;
use Carbon\Carbon;
use Veraomat\Models\User;
use Veraomat\Models\ConfiguraInscricaoPos;
use Veraomat\Models\AreaPosMat;
use Veraomat\Models\CartaRecomendacao;
use Veraomat\Models\Formacao;
use Veraomat\Models\ProgramaPos;
use Veraomat\Models\FinalizaInscricao;
use Veraomat\Notifications\NotificaNovaInscricao;
use Illuminate\Http\Request;
use Veraomat\Mail\EmailVerification;
use Veraomat\Http\Controllers\BaseController;
use Veraomat\Http\Controllers\CidadeController;
use Veraomat\Http\Controllers\AuthController;
use Veraomat\Http\Controllers\RelatorioController;
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