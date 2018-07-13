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
use Illuminate\Foundation\Auth\RegistersUsers;


/**
* Classe para visualização da página inicial.
*/
class CoordenadorController extends BaseController
{

	public $locale_default = 'pt-br';

	public function getMenu()
	{	
		return view('home');
	}
}