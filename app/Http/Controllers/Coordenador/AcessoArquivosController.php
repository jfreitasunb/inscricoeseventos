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
use InscricoesEventosMat\Models\ConfiguraInscricaoEvento;
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
class AcessoArquivosController extends CoordenadorController
{

	public function getVerArquivos()
	{
		
		$relatorio = new ConfiguraInscricaoEvento();

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