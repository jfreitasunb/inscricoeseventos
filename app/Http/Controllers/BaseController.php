<?php

namespace InscricoesEventos\Http\Controllers;

use InscricoesEventos\Models\{ConfiguraInscricaoEvento, User};
use Auth;
use Session;

use View;



/**
* Classe base.
*/

class BaseController extends Controller
{
    protected $array_meses  = array(
                '1' => 'Janeiro',
                '2' => 'Fevereiro',
                '3' => 'Março',
                '4' => 'Abril',
                '5' => 'Maio',
                '6' => 'Junho',
                '7' => 'Julho',
                '8' => 'Agosto',
                '9' => 'Setembro',
                '10' => 'Outubro',
                '11' => 'Novembro',
                '12' => 'Dezembro',
              );

	public $periodo_inscricao;

	public function __construct() {

       $inscricao_pos = new ConfiguraInscricaoEvento();

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
