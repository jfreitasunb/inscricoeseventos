<?php

namespace Veraomat\Providers;

use Illuminate\Support\ServiceProvider;
use Veraomat\Models\ConfiguraInscricaoPos;


class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {

        view()->composer('templates.partials.cabecalho', function($view)
            {
                $periodo = new ConfiguraInscricaoPos();

                $periodo_inscricao = $periodo->retorna_periodo_inscricao();

                $ano_evento = $periodo->retorna_inscricao_ativa()->ano_evento;
        
                $view->with(compact('periodo_inscricao', 'ano_evento'));
            });
    }

    public function register()
    {
    }
}
