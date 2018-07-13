<?php

namespace InscricoesEventos\Providers;

use Illuminate\Support\ServiceProvider;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;


class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {

        view()->composer('templates.partials.cabecalho', function($view)
            {
                $periodo = new ConfiguraInscricaoEvento();

                $periodo_inscricao = $periodo->retorna_periodo_inscricao();

                if (is_null($periodo->retorna_inscricao_ativa())) {
                    $ano_evento = null;
                    $nome_evento = "Sem eventos";
                }else{
                    $ano_evento = $periodo->retorna_inscricao_ativa()->ano_evento;
                    $nome_evento = $periodo->retorna_inscricao_ativa()->nome_evento;
                }
        
                $view->with(compact('periodo_inscricao', 'ano_evento','nome_evento'));
            });
    }

    public function register()
    {
    }
}
