<?php

namespace InscricoesEventosMat\Http\Controllers\Coordenador;

use Auth;
use DB;
use Mail;
use Session;
use File;
use PDF;
use Notification;
use Purifier;
use Carbon\Carbon;
use InscricoesEventosMat\Models\User;
use InscricoesEventosMat\Models\ConfiguraInscricaoEvento;
use InscricoesEventosMat\Models\TipoEvento;
use InscricoesEventosMat\Models\AreaPosMat;
use InscricoesEventosMat\Models\OfertaCursoVerao;
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
class ConfiguraInscricaoEventoController extends CoordenadorController
{

	public function getConfiguraInscricaoEvento()
	{

		$inscricao_pos = new ConfiguraInscricaoEvento();

        $evento = new TipoEvento();

        $eventos_mat = $evento->retorna_tipo_eventos();

        $area_pos = new AreaPosMat();

        $id_area_evento = null;

        $secao = $area_pos->retorna_areas_evento($id_area_evento, $this->locale_default)->toArray();

        array_unshift($secao, "Todas");

		return view('templates.partials.coordenador.configurar_inscricao')->with(compact('eventos_mat', 'secao'));
	}

	public function postConfiguraInscricaoEvento(Request $request)
	{    

		$this->validate($request, [
			'inicio_inscricao' => 'required|date_format:"d/m/Y"|before:fim_inscricao|after:today',
			'fim_inscricao' => 'required|date_format:"d/m/Y"|after:inicio_inscricao|after:today',
            'id_evento_desejado' => 'required',
            'evento_ano' => 'required',
            'evento_nome' => 'required',
		]);

        $configura_nova_inscricao_evento = new ConfiguraInscricaoEvento();

		$user = Auth::user();
    
    	$inicio = Carbon::createFromFormat('d/m/Y', $request->inicio_inscricao);
    	$fim = Carbon::createFromFormat('d/m/Y', $request->fim_inscricao);

    	$data_inicio = $inicio->format('Y-m-d');
    	$data_fim = $fim->format('Y-m-d');

        $id_tipo_evento = (int)Purifier::clean(trim($request->id_evento_desejado));

        $id_area_evento = (int)Purifier::clean(trim($request->id_area_evento));

        $ano_evento = (int)Purifier::clean(trim($request->evento_ano));

        $nome_evento = Purifier::clean(trim($request->evento_nome));

    	if ($configura_nova_inscricao_evento->autoriza_configuracao_inscricao($data_inicio)) {

    		$configura_nova_inscricao_evento->inicio_inscricao = $data_inicio;
			$configura_nova_inscricao_evento->fim_inscricao = $data_fim;
            $configura_nova_inscricao_evento->id_tipo_evento = $id_tipo_evento;
            $configura_nova_inscricao_evento->nome_evento = $nome_evento;
            $configura_nova_inscricao_evento->ano_evento = $ano_evento;

            if ($id_area_evento == 0) {
                $configura_nova_inscricao_evento->id_area_evento = null;
            }else{
                $configura_nova_inscricao_evento->id_area_evento = $id_area_evento;
            }

			$configura_nova_inscricao_evento->id_coordenador = $user->id_user;
			
            $configura_nova_inscricao_evento->save();

			notify()->flash('Inscrição configurada com sucesso.','success');
			return redirect()->route('configura.inscricao');
    	}else{
    		notify()->flash('Já existe uma inscrição ativa para esse período.','error');
			return redirect()->back('configura.inscricao');
    	}
	}
}