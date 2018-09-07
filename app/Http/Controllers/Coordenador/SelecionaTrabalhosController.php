<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Illuminate\Http\Request;

class SelecionaTrabalhosController extends CoordenadorController
{
    public function getSelecionarTrabalhos()
    {
        $user = $this->SetUser();
    
        $id_coordenador = $user->id_user;

        $locale_relatorio = 'pt-br';

        $relatorio = new ConfiguraInscricaoEvento();

        $relatorio_disponivel = $relatorio->retorna_edital_vigente();

        $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

        $coordenador = new TipoCoordenador();

        $nivel_coordenador = $coordenador->retorna_dados_coordenador($id_coordenador, $id_inscricao_evento);

        $coordenador_area = $nivel_coordenador->coordenador_area;

        $trabalho_submetido = new TrabalhoSubmetido();

        return view('templates.partials.coordenador.seleciona_trabalhos_submetidos');
    }

    public function postSelecionarTrabalhos(Request $request)
    {
        # code...
    }
}
