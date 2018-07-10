<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class EscolhaCursoVerao extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'escolhas_curso_verao';

    protected $fillable = [
        'programa_pretendido',
        'curso_verao',
        'id_inscricao_verao',
    ];

    public function retorna_escolha_candidato($id_candidato,$id_inscricao_verao)
    {
        return $this->where("id_candidato", $id_candidato)->where("id_inscricao_verao", $id_inscricao_verao)->get();
    }

    public function retorna_escolha_programa($id_candidato,$id_inscricao_verao)
    {
        return $this->where("id_candidato", $id_candidato)->where("id_inscricao_verao", $id_inscricao_verao)->get()->first();
    }

    public function usuarios_nao_finalizados($id_inscricao_verao)
    {
        return $this->where('id_inscricao_verao', $id_inscricao_verao)->join('users', 'users.id_user', 'escolhas_curso_verao.id_candidato')->join('programa_pos_mat', 'programa_pos_mat.id_programa_pos', 'escolhas_curso_verao.programa_pretendido')->whereNotIn('escolhas_curso_verao.id_candidato', function($query) use ($id_inscricao_verao) {
            $query->select('id_candidato')->from('finaliza_inscricao')->where('id_inscricao_verao', $id_inscricao_verao);
        } )->select('users.nome', 'programa_pos_mat.tipo_programa_pos_ptbr')->orderBy('programa_pos_mat.tipo_programa_pos_ptbr');
    }

    public function grava_escolhas_curso_verao($id_candidato, $id_inscricao_verao, $programa_pretendido, $escolhido)
    {

        $candidato_fez_escolhas = $this->retorna_escolha_candidato($id_candidato,$id_inscricao_verao);

        if (!is_null($candidato_fez_escolhas)) {
            $atualiza_escolhas = $this->where('id_candidato', $id_candidato)->where('id_inscricao_verao',$id_inscricao_verao);
            $dados_escolhas['programa_pretendido'] = $programa_pretendido;
            $dados_escolhas['curso_verao'] = $escolhido;
            $atualiza_escolhas->update($dados_escolhas);
        }else{
            $escolhas_curso_verao = new EscolhaCursoVerao();
            $escolhas_curso_verao->id_candidato = $id_candidato;
            $escolhas_curso_verao->programa_pretendido = $programa_pretendido;
            $escolhas_curso_verao->curso_verao = $escolhido;
            $escolhas_curso_verao->id_inscricao_verao = $id_inscricao_verao;
            $escolhas_curso_verao->save();
        }
    }

    public function retorna_area_distintas($id_inscricao_verao)
    {
        return $this->select('area_pos')->where('id_inscricao_verao', $id_inscricao_verao)->where('programa_pretendido', '2')->distinct()->orderBy('area_pos')->pluck('area_pos');
    }

    public function retorna_inscritos_por_area_pos($area_pos, $id_inscricao_verao)
    {
        return $this->select('id_candidato')->where('programa_pretendido', '2')->where('area_pos', $area_pos)->where('id_inscricao_verao', $id_inscricao_verao)->get()->pluck('id_candidato');
    }
}
