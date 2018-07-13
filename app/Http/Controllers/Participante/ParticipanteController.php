<?php

namespace InscricoesEventos\Http\Controllers\Participante;

use Auth;
use DB;
use Mail;
use Session;
use Validator;
use Purifier;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\AssociaEmailsRecomendante;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\CartaMotivacao;
use InscricoesEventos\Models\ProgramaPos;
use InscricoesEventos\Models\DadoPessoalCandidato;
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\Estado;
use InscricoesEventos\Models\DadoAcademico;
use InscricoesEventos\Models\EscolhaCandidato;
use InscricoesEventos\Models\DadoPessoalRecomendante;
use InscricoesEventos\Models\ContatoRecomendante;
use InscricoesEventos\Models\CartaRecomendacao;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\Documento;
use InscricoesEventos\Models\Paises;
use InscricoesEventos\Models\Cidade;
use InscricoesEventos\Notifications\NotificaRecomendante;
use InscricoesEventos\Notifications\NotificaCandidato;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\CidadeController;
use InscricoesEventos\Http\Controllers\BaseController;
use InscricoesEventos\Http\Controllers\APIController;
use Illuminate\Foundation\Auth\RegistersUsers;
use InscricoesEventos\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
* Classe para manipulação do candidato.
*/
class ParticipanteController extends BaseController
{

	private $estadoModel;

    public function __construct(Estado $estado)
    {
        $this->estadoModel = $estado;
    }

    public function getCidades($idEstado)
    {
        $estado = $this->estadoModel->find($idEstado);
        $cidades = $estado->cidades()->getQuery()->get(['id', 'cidade']);
        return Response::json($cidades);
    }
	public function getMenu()
	{	
		Session::get('locale');
		
		return view('home');
	}
}