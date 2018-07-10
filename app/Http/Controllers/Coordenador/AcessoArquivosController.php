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
class AcessoArquivosController extends CoordenadorController
{

	public function getVerArquivos()
	{
		
		$relatorio = new ConfiguraInscricaoPos();

      	$relatorio_disponivel = $relatorio->retorna_edital_vigente();

      	$modo_pesquisa = false;

      	$arquivos = new RelatorioController;

      	$endereco_zip_mudar = '/var/www/posmat/storage/app/public/';

		$local_arquivos = $arquivos->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

		$local_arquivos_pdf = str_replace($endereco_zip_mudar, '/storage/', $local_arquivos['local_relatorios']);

		$programas_disponiveis = explode("_", $relatorio_disponivel->retorna_inscricao_ativa()->programa);

		$nome_programa_pos = new ProgramaPos();

		foreach ($programas_disponiveis as $programa) {
			$programa_para_inscricao[$programa] = $nome_programa_pos->pega_programa_pos_mat($programa, $this->locale_default);
		}

		foreach ($programa_para_inscricao as $programa) {
			foreach (glob($local_arquivos['local_relatorios']."Inscricao_".$programa."*.pdf") as $filename) {
            	$fichas[$programa][] = basename($filename);
        	}
		}

      	return view('templates.partials.coordenador.ver_arquivos', compact('relatorio_disponivel', 'fichas', 'local_arquivos_pdf'));
	}
}