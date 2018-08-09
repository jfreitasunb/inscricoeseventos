<?php

namespace InscricoesEventos\Http\Controllers\Admin;

use Auth;
use DB;
use Mail;
use Session;
use Notification;
use Purifier;
use Carbon\Carbon;
use InscricoesEventos\Models\{User, ConfiguraInscricaoEvento, AreaPosMat, RelatorioController, FinalizaInscricao, DadoPessoal};
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\CoordenadorController;
use InscricoesEventos\Http\Controllers\DataTable\UserController;
use InscricoesEventos\Notifications\NotificaRecomendante;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;

/**
* Classe para visualização da página inicial.
*/
class CriaCoordenadorController extends AdminController
{

	public function getCriarCoordenador()
	{

		$evento = new ConfiguraInscricaoEvento();

      	$evento_vigente = $evento->retorna_edital_vigente();

      	$id_area_evento = $evento_vigente->id_area_evento;

      	$area_pos = new AreaPosMat();

		$secao = $area_pos->retorna_areas_evento($id_area_evento, $this->locale_default);

      	return view('templates.partials.admin.criar_coordenador')->with(compact('evento_vigente', 'secao'));
	}

	public function postCriarCoordenador(Request $request)
	{
		
        $this->validate($request, [
            'nome' => 'required',
            'email' => 'required|email',
            'coordenador_geral' => 'required',
            'id_inscricao_evento' => 'required',
        ]);

        $nome = Purifier::clean(trim($request->nome));

        $email = Purifier::clean(strtolower(trim($request->email)));

        $coordenador_geral = (bool)$request->coordenador_geral;

        if (!$coordenador_geral) {
            $this->validate($request, [
                'nome' => 'required',
                'email' => 'required|email',
                'coordenador_geral' => 'required',
                'id_inscricao_evento' => 'required',
                'coordenador_area' => 'required',
            ]);

            $coordenador_area = (int)$request->coordenador_area;
        }

        $user = new User();

        $usuario_existe = $user->retorna_user_por_email($email);

        if (is_null($usuario_existe)) {
            
            $user->nome = $nome;

            $user->email = $email;

            $user->password = bcrypt(date("d-m-Y H:i:s:u").str_random(20));

            $user->user_type = 'coordenador';

            $user->ativo = True;

            $user->save();


        }else{
            dd("já existe");
        }
	}
}