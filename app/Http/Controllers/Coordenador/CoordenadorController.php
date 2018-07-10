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