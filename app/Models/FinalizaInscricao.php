<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FinalizaInscricao extends FuncoesModels
{
    protected $primaryKey = 'id';

    protected $table = 'finaliza_inscricao';

    protected $fillable = [
    ];

    public function retorna_inscricao_finalizada($id_participante,$id_inscricao_evento)
    {
        $finalizou_inscricao = $this->select('finalizada')->where("id_participante", $id_participante)->where("id_inscricao_evento", $id_inscricao_evento)->get();

        if (count($finalizou_inscricao)>0 and $finalizou_inscricao[0]['finalizada']) {
        	return TRUE;
        }else{
        	return FALSE;
        }

    }

    public function retorna_usuarios_relatorio_individual($id_inscricao_evento, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_tipo_programa_pos($locale);

        return $this->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->where('finaliza_inscricao.finalizada', true)->join('dados_pessoais_candidato', 'dados_pessoais_candidato.id_participante','finaliza_inscricao.id_participante')->join('users', 'users.id_user', 'finaliza_inscricao.id_participante')->join('escolhas_curso_verao', 'escolhas_curso_verao.id_participante', 'dados_pessoais_candidato.id_participante')->where('escolhas_curso_verao.id_inscricao_evento', $id_inscricao_evento)->join('programa_pos_mat', 'id_programa_pos', 'escolhas_curso_verao.programa_pretendido')->select('finaliza_inscricao.id_participante', 'finaliza_inscricao.id_inscricao_evento','users.nome', 'users.email', 'programa_pos_mat.'.$nome_coluna)->orderBy('escolhas_curso_verao.programa_pretendido' , 'desc')->orderBy('users.nome','asc');
    }

    public function retorna_usuarios_inscritos($id_inscricao_evento, $nivel_coordenador)
    {
        return $this->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->where('finaliza_inscricao.finalizada', true)->get()->unique('id_participante');
    }

    public function retorna_usuarios_relatorios($id_inscricao_evento, $nivel_coordenador)
    {
        if ($nivel_coordenador->coordenador_geral) {
            return $this->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->join('tipo_participacao','tipo_participacao.id_inscricao_evento', 'finaliza_inscricao.id_inscricao_evento')->where('tipo_participacao.apresentar_trabalho', true)->where('finaliza_inscricao.finalizada', true)->get()->unique('id_participante');
        }else{
            return $this->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->where('finaliza_inscricao.finalizada', true)->join('tipo_participacao','tipo_participacao.id_inscricao_evento', 'finaliza_inscricao.id_inscricao_evento')->where('tipo_participacao.apresentar_trabalho', true)->join('trabalho_submetido', 'trabalho_submetido.id_inscricao_evento', 'finaliza_inscricao.id_inscricao_evento')->where('trabalho_submetido.id_area_trabalho', $nivel_coordenador->coordenador_area)->get()->unique('id_participante');
        }
    }

    public function total_inscritos($id_inscricao_evento)
    {   
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('finalizada', true)->get()->count();
        
    }


    public function retorna_total_inscritos($id_inscricao_evento, $nivel_coordenador)
    {   
        if ($nivel_coordenador->coordenador_geral) {
            return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('finalizada', true)->get()->count();
        }else{
            return $this->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->where('finaliza_inscricao.finalizada', true)->join('trabalho_submetido', 'trabalho_submetido.id_inscricao_evento', 'finaliza_inscricao.id_inscricao_evento')->where('trabalho_submetido.id_area_trabalho', $nivel_coordenador->coordenador_area)->get()->count();
        }
        
    }

    public function retorna_usuario_inscricao_finalizada($id_inscricao_evento, $id_participante, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_tipo_programa_pos($locale);

        return $this->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->where('finaliza_inscricao.finalizada', true)->where('finaliza_inscricao.id_participante', $id_participante)->join('users', 'users.id_user','finaliza_inscricao.id_participante')->join('configura_inscricao_verao','configura_inscricao_verao.id_inscricao_evento', 'finaliza_inscricao.id_inscricao_evento')->join('escolhas_curso_verao', 'escolhas_curso_verao.id_participante', 'users.id_user')->where('escolhas_curso_verao.id_inscricao_evento', $id_inscricao_evento)->join('programa_pos_mat', 'id_programa_pos', 'escolhas_curso_verao.programa_pretendido')->select('finaliza_inscricao.id', 'finaliza_inscricao.id_participante', 'finaliza_inscricao.id_inscricao_evento', 'finaliza_inscricao.finalizada', 'users.nome','programa_pos_mat.'.$nome_coluna)->get()->first();
    }

    public function retorna_dados_inscricao_finalizada($id_inscricao_evento, $id_participante)
    {
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('id_participante', $id_participante)->get()->first();
    }

    public function retorna_se_finalizou($id_participante, $id_inscricao_evento)
    {
        return $this->select('finalizada')->where('id_participante',$id_participante)->where('id_inscricao_evento',$id_inscricao_evento)->value('finalizada');
    }
}
