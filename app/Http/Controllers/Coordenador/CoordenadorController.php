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