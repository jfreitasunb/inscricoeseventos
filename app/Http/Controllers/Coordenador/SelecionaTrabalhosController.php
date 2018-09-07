<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Illuminate\Http\Request;

use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\ConfiguraTipoEvento;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\OfertaCursoVerao;
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\ProgramaPos;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\TipoCoordenador;
use InscricoesEventos\Models\TrabalhoSubmetido;

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

        if ($nivel_coordenador->coordenador_geral) {
          
          dd($trabalho_submetido->retorna_todos_trabalhos(Null, $id_inscricao_evento));
          
        }else{
          
          dd($trabalho_submetido->retorna_todos_trabalhos($coordenador_area, $id_inscricao_evento));
        }

        foreach ($areas_com_trabalho as $area) {
          

            $area_pos = new AreaPosMat();

            $area_trabalho = $area_pos->pega_area_pos_mat($area, $locale_relatorio);
          }
        return view('templates.partials.coordenador.seleciona_trabalhos_submetidos');
    }

    public function postSelecionarTrabalhos(Request $request)
    {
        # code...
    }
}
