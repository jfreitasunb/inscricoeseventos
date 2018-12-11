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

    public function titleCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI"))
  {
    /*
     * Exceptions in lower case are words you don't want converted
     * Exceptions all in upper case are any words you don't want converted to title case
     *   but should be converted to upper case, e.g.:
     *   king henry viii or king henry Viii should be King Henry VIII
     */
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    
    foreach ($delimiters as $dlnr => $delimiter) {
      $words = explode($delimiter, $string);
      $newwords = array();
      foreach ($words as $wordnr => $word) {
        if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
          // check exceptions list for any words that should be in upper case
          $word = mb_strtoupper($word, "UTF-8");
        } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
          // check exceptions list for any words that should be in upper case
          $word = mb_strtolower($word, "UTF-8");
        } elseif (!in_array($word, $exceptions)) {
          // convert to uppercase (non-utf8 only)
          $word = ucfirst($word);
        }
        array_push($newwords, $word);
      }
      $string = join($delimiter, $newwords);
    }//foreach

   return $string;
  }
}
