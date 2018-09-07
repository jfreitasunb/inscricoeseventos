<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Illuminate\Http\Request;

class SelecionaTrabalhosController extends CoordenadorController
{
    public function getSelecionarTrabalhos()
    {
        return view('templates.partials.coordenador.seleciona_trabalhos_submetidos');
    }

    public function postSelecionarTrabalhos(Request $request)
    {
        # code...
    }
}
