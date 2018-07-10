<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FinalizaInscricao extends Model
{
    protected $primaryKey = 'id_candidato';

    protected $table = 'finaliza_inscricao';

    protected $fillable = [
    ];

    public function define_nome_coluna_por_locale($locale)
    {
        switch ($locale) {
            case 'en':
                return 'tipo_programa_pos_en';
                break;

            case 'es':
                return 'tipo_programa_pos_es';
                break;
            
            default:
                return 'tipo_programa_pos_ptbr';
                break;
        }
    }

    public function retorna_inscricao_finalizada($id_candidato,$id_inscricao_verao)
    {
        $finalizou_inscricao = $this->select('finalizada')->where("id_candidato", $id_candidato)->where("id_inscricao_verao", $id_inscricao_verao)->get();

        if (count($finalizou_inscricao)>0 and $finalizou_inscricao[0]['finalizada']) {
        	return TRUE;
        }else{
        	return FALSE;
        }

    }

    public function retorna_usuarios_relatorio_individual($id_inscricao_verao, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        return $this->where('finaliza_inscricao.id_inscricao_verao', $id_inscricao_verao)->where('finaliza_inscricao.finalizada', true)->join('dados_pessoais_candidato', 'dados_pessoais_candidato.id_candidato','finaliza_inscricao.id_candidato')->join('users', 'users.id_user', 'finaliza_inscricao.id_candidato')->join('escolhas_curso_verao', 'escolhas_curso_verao.id_candidato', 'dados_pessoais_candidato.id_candidato')->where('escolhas_curso_verao.id_inscricao_verao', $id_inscricao_verao)->join('programa_pos_mat', 'id_programa_pos', 'escolhas_curso_verao.programa_pretendido')->select('finaliza_inscricao.id_candidato', 'finaliza_inscricao.id_inscricao_verao','users.nome', 'users.email', 'programa_pos_mat.'.$nome_coluna)->orderBy('escolhas_curso_verao.programa_pretendido' , 'desc')->orderBy('users.nome','asc');
    }

    public function retorna_usuarios_relatorios($id_inscricao_verao)
    {
        return $this->where('id_inscricao_verao', $id_inscricao_verao)->where('finalizada', true)->get();
    }

    public function retorna_usuario_inscricao_finalizada($id_inscricao_verao, $id_candidato, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        return $this->where('finaliza_inscricao.id_inscricao_verao', $id_inscricao_verao)->where('finaliza_inscricao.finalizada', true)->where('finaliza_inscricao.id_candidato', $id_candidato)->join('users', 'users.id_user','finaliza_inscricao.id_candidato')->join('configura_inscricao_verao','configura_inscricao_verao.id_inscricao_verao', 'finaliza_inscricao.id_inscricao_verao')->join('escolhas_curso_verao', 'escolhas_curso_verao.id_candidato', 'users.id_user')->where('escolhas_curso_verao.id_inscricao_verao', $id_inscricao_verao)->join('programa_pos_mat', 'id_programa_pos', 'escolhas_curso_verao.programa_pretendido')->select('finaliza_inscricao.id', 'finaliza_inscricao.id_candidato', 'finaliza_inscricao.id_inscricao_verao', 'finaliza_inscricao.finalizada', 'users.nome','programa_pos_mat.'.$nome_coluna)->get()->first();
    }

    public function retorna_dados_inscricao_finalizada($id_inscricao_verao, $id_candidato)
    {
        return $this->where('id_inscricao_verao', $id_inscricao_verao)->where('id_candidato', $id_candidato)->get()->first();
    }

    public function retorna_se_finalizou($id_candidato, $id_inscricao_verao)
    {
        return $this->select('finalizada')->where('id_candidato',$id_candidato)->where('id_inscricao_verao',$id_inscricao_verao)->value('finalizada');
    }
}
