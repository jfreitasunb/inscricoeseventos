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
use InscricoesEventosMat\Models\ConfiguraInscricaoEvento;
use InscricoesEventosMat\Models\TipoEvento;
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

		return view('templates.partials.coordenador.configurar_inscricao')->with(compact('eventos_mat'));
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

        $ano_evento = (int)Purifier::clean(trim($request->ano_evento));

        $nome_evento = Purifier::clean(trim($request->nome_evento));

    	if ($configura_nova_inscricao_evento->autoriza_configuracao_inscricao($data_inicio)) {

    		$configura_nova_inscricao_evento->inicio_inscricao = $data_inicio;
			$configura_nova_inscricao_evento->fim_inscricao = $data_fim;
            $configura_nova_inscricao_evento->ano_evento = $ano_evento;
            $configura_nova_inscricao_evento->tipo_evento = 2;
			// $configura_nova_inscricao_evento->tipo_evento = implode("_", $request->escolhas_coordenador);
			$configura_nova_inscricao_evento->id_coordenador = $user->id_user;
			
            $configura_nova_inscricao_evento->save();

            foreach ($request->curso_verao as $curso) {
                
                $oferta_verao = new OfertaCursoVerao;

                $oferta_verao->id_inscricao_verao = $configura_nova_inscricao_pos->id_inscricao_verao;

                $oferta_verao->id_curso_verao = (int)$curso;

                $oferta_verao->seleciona_pos = $request->seleciona_pos[(int)$curso];

                $oferta_verao->save();
            }

            

			// $dados_email['inicio_inscricao'] = $request->inicio_inscricao;
			// $dados_email['fim_inscricao'] = $request->fim_inscricao;

			// foreach ($request->escolhas_coordenador as $key) {
				
			// 	$nome_programa_pos = new ProgramaPos();

			// 	$temp[] = $nome_programa_pos->pega_programa_pos_mat($key, $this->locale_default);
			// }

			// $dados_email['programa'] = implode('/', $temp);

			// Notification::send(User::find('1'), new NotificaNovaInscricao($dados_email));

			notify()->flash('Inscrição configurada com sucesso.','success');
			return redirect()->route('configura.inscricao');
    	}else{
    		notify()->flash('Já existe uma inscrição ativa para esse período.','error');
			return redirect()->back('configura.inscricao');
    	}
	}
}