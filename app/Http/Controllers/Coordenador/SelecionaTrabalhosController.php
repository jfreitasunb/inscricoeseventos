<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Illuminate\Http\Request;

use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\ConfiguraTipoEvento;
use InscricoesEventos\Models\ConfiguraTipoApresentacao;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\OfertaCursoVerao;
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\ProgramaPos;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\TipoCoordenador;
use InscricoesEventos\Models\TipoParticipacao;
use InscricoesEventos\Models\TrabalhoSubmetido;
use InscricoesEventos\Models\TrabalhoSelecionado;

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

        $tipo_apresentacao = new ConfiguraTipoApresentacao();

        $tipos_de_apresentacao = $tipo_apresentacao->pega_tipo_apresentacao($locale_relatorio);

        if ($nivel_coordenador->coordenador_geral) {
          
          $dados_para_selecao = $trabalho_submetido->retorna_todos_trabalhos(Null, $id_inscricao_evento);
          
        }else{
          
          $dados_para_selecao = $trabalho_submetido->retorna_todos_trabalhos($coordenador_area, $id_inscricao_evento);
        }

        return view('templates.partials.coordenador.seleciona_trabalhos_submetidos')->with(compact('dados_para_selecao', 'tipos_de_apresentacao', 'id_coordenador', 'id_inscricao_evento'));
    }

    public function postSelecionarTrabalhos(Request $request)
    {   
        $user = $this->SetUser();
    
        $id_coordenador = $user->id_user;

        $locale_relatorio = 'pt-br';

        $relatorio = new ConfiguraInscricaoEvento();

        $relatorio_disponivel = $relatorio->retorna_edital_vigente();

        $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

        if ($request->id_inscricao_evento != $id_inscricao_evento) {
            notify()->flash('Você está tentando selecionar trabalhos para evento não configurado!','error');
        
            return redirect()->back();
        }

        if ($request->id_coordenador != $id_coordenador) {
            notify()->flash('Você não tem permissão para executar essa operação!','error');
        
            return redirect()->back();
        }
        
        $this->validate($request, [
            'aceito' => 'required',
        ]);
// dd($request);
        $trabalhos_aceitos = $request->aceito;
        
        $tipo_apresentacao = $request->muda_tipo_apresentacao;

        $limpa_selecao = new TrabalhoSelecionado();

        $limpa_selecao->limpa_selecoes_anteriores($id_coordenador, $id_inscricao_evento);

        foreach ($trabalhos_aceitos as $key => $aceito) {
            if ($aceito) {
                
                $selecionado = new TrabalhoSelecionado();

                $tipo_participacao = new TipoParticipacao();

                $submentido = new TrabalhoSubmetido();

                $dados_submissao = $submentido->retorna_trabalho_submetido($key, $id_inscricao_evento);

                $dados_participacao = $tipo_participacao->retorna_participacao($id_inscricao_evento, $key);

                $id_categoria_participante = $dados_participacao->id_categoria_participante;
                
                $id_area_trabalho = $dados_submissao->id_area_trabalho;
                
                $selecionado->id_participante = $key;
                $selecionado->id_categoria_participante = $id_categoria_participante;
                $selecionado->id_tipo_apresentacao = $tipo_apresentacao[$key];
                $selecionado->id_area_trabalho = $id_area_trabalho;
                $selecionado->id_coordenador = $id_coordenador;
                $selecionado->id_inscricao_evento = $id_inscricao_evento;

                $selecionado->save();
            }
        }
        
        notify()->flash('Dados salvos com sucesso!','success');
        
        return redirect()->back();
    }
}
