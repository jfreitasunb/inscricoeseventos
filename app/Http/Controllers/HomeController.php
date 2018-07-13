<?php

namespace InscricoesEventos\Http\Controllers;

use Auth;
use Session;
use InscricoesEventos\Models\User;


/**
* Classe para visualização da página inicial.
*/
class HomeController extends BaseController
{
	
	public function __construct(){
       parent::__construct();
    }

	public function index()
	{
		return view('home');
	}

	public function setaLocale($locale)
    {
    	if(Auth::check()){

	       $user = User::find(Auth::user()->id_user);

	       $user->update(['locale'=>$locale]);

	  	}else{
            
	    	Session::put('locale',$locale);
	  	}
    }

	public function getLangPortuguese()
    {
    	$this->setaLocale('pt-br');

    	return redirect()->back();
    }

    public function getLangEnglish()
    {
    	$this->setaLocale('en');

    	return redirect()->back();
    }

    public function getLangSpanish()
    {
    	$this->setaLocale('es');

    	return redirect()->back();
    }    

}