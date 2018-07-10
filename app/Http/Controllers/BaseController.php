<?php

namespace Veraomat\Http\Controllers;

use Veraomat\Models\{ConfiguraInscricaoPos, User};
use Auth;
use Session;

use View;



/**
* Classe base.
*/

class BaseController extends Controller
{

	public $periodo_inscricao;

	public function __construct() {

       $inscricao_pos = new ConfiguraInscricaoPos();

	   $periodo_inscricao = $inscricao_pos->retorna_periodo_inscricao();

       View::share ( 'periodo_inscricao', $periodo_inscricao );
    }

    public function SetUser()
    {
        if (session()->has('impersonate')) {
            
            return User::find(session()->get('impersonate'));
        }else{
            return Auth::user();
        }
    }
}
