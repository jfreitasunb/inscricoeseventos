<?php

namespace InscricoesEventos\Providers;

use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    
    private $accordion_contas = ['pesquisa.email.muda.senha', 'admin.impersonate', 'pesquisa.usuario', 'criar.coordenador', 'lista.inativos', 'associa.recomendantes', 'visualiza.associacoes'];

    private $accordion_inscricoes = ['configura.inscricao', 'pesquisa.candidato', 'editar.inscricao', 'reativar.candidato', 'pesquisa.carta', 'pesquisa.recomendantes', 'pesquisa.indicacoes', 'lista.recomendacoes', 'reativar.carta', 'inscricoes.nao.finalizadas', 'cadastra.area.pos', 'editar.area.pos'];

    private $accordion_relatorios = ['relatorio.atual', 'relatorio.anteriores', 'gera.ficha.individual', 'ver.charts'];


    public function ativa_accordion_contas()
    {
        if (in_array(Route::currentRouteName(), $this->accordion_contas)) {
            return 'in';
        }else{
            return '';
        }
    }

    public function ativa_accordion_inscricoes()
    {
        if (in_array(Route::currentRouteName(), $this->accordion_inscricoes)) {
            return 'in';
        }else{
            return '';
        }
    }

    public function ativa_accordion_relatorios()
    {
        if (in_array(Route::currentRouteName(), $this->accordion_relatorios)) {
            return 'in';
        }else{
            return '';
        }
    }

    public function boot()
    {
        Blade::if('impersonating_recomendante', function () {

            if (session()->has('impersonate') || session()->has('impersonate_user_type')) {
                if (session()->get('impersonate_user_type') === 'recomendante') {
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        });

        Blade::if('impersonating_candidato', function () {

            if (session()->has('impersonate') || session()->has('impersonate_user_type')) {
                if (session()->get('impersonate_user_type') === 'candidato') {
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        });

        Blade::if('admin', function ( $user = null ){

            if (!$user && auth()->check()) {
                $user = auth()->user();
            }

            if (!$user) {
                return false;
            }

            View::share('keep_open_accordion_contas', $this->ativa_accordion_contas());
            
            View::share('keep_open_accordion_inscricoes', $this->ativa_accordion_inscricoes());

            View::share('keep_open_accordion_relatorios', $this->ativa_accordion_relatorios());

            return $user->isAdmin();
        });

        Blade::if('coordenador', function ( $user = null ){

            if (!$user && auth()->check()) {
                $user = auth()->user();
            }

            if (!$user) {
                return false;
            }

            View::share('keep_open_accordion_inscricoes', $this->ativa_accordion_inscricoes());

            View::share('keep_open_accordion_relatorios', $this->ativa_accordion_relatorios());
            
            return $user->isCoordenador();
        });

        Blade::if('coordenador_area', function ( $user = null ){

            if (!$user && auth()->check()) {
                $user = auth()->user();
            }

            if (!$user) {
                return false;
            }

            View::share('keep_open_accordion_inscricoes', $this->ativa_accordion_inscricoes());

            View::share('keep_open_accordion_relatorios', $this->ativa_accordion_relatorios());
            
            return $user->isCoordenador_Area();
        });

        Blade::if('participante', function ( $user = null ){

            if (!$user && auth()->check()) {
                $user = auth()->user();
            }

            if (!$user) {
                return false;
            }

            return $user->isParticipante();
        });

        Blade::if('liberamenu', function ( $user = null ){

            $user = auth()->user();
            $id_user = $user->id_user;

            $edital_ativo = new ConfiguraInscricaoEvento();

            $id_inscricao_evento = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_evento;
            // $edital = $edital_ativo->retorna_inscricao_ativa()->edital;
            $autoriza_inscricao = $edital_ativo->autoriza_inscricao();

            $finaliza_inscricao = new FinalizaInscricao();

            $status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_user,$id_inscricao_evento);

            if ($autoriza_inscricao and !$status_inscricao) {
                return true;
            }else{
                return false;
            }         
        });

         Blade::if('liberacarta', function ( $user = null ){

            $user = auth()->user();
            $id_user = $user->id_user;

            $edital_ativo = new ConfiguraInscricaoEvento();

            $autoriza_preenchimento_carta = $edital_ativo->autoriza_carta();


            if ($autoriza_preenchimento_carta) {
                return true;
            }else{
                return false;
            }         
        });

         Blade::if('statuscarta', function ( $user = null ){

            $user = auth()->user();
            $id_user = $user->id_user;

            $edital_ativo = new ConfiguraInscricaoEvento();

            $id_inscricao_pos = $edital_ativo->retorna_inscricao_ativa()->id_inscricao_pos;
            $edital = $edital_ativo->retorna_inscricao_ativa()->edital;
            $autoriza_inscricao = $edital_ativo->autoriza_inscricao();

            $finaliza_inscricao = new FinalizaInscricao();

            $status_inscricao = $finaliza_inscricao->retorna_inscricao_finalizada($id_user,$id_inscricao_pos);

            if ($autoriza_inscricao and $status_inscricao) {
                return true;
            }else{
                return false;
            }         
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
