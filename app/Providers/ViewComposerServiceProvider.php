<?php

namespace InscricoesEventosMat\Providers;

use Illuminate\Support\ServiceProvider;
use InscricoesEventosMat\Models\ConfiguraInscricaoEvento;


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
                }else{
                    $ano_evento = $periodo->retorna_inscricao_ativa()->ano_evento;
                }
        
                $view->with(compact('periodo_inscricao', 'ano_evento'));
            });
    }

    public function register()
    {
    }
}
