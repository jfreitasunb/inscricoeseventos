<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TrabalhoSubmetido extends FuncoesModels
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'trabalho_submetido';

    protected $fillable = [
        'id_participante',
        'id_area_trabalho',
        'id_inscricao_evento',
        'titulo_trabalho',
        'autor_trabalho',
        'abstract_trabalho',

    ];

    public function retorna_trabalho_submetido($id_participante, $id_inscricao_evento)
    {

        return $this->where('id_participante', $id_participante)->where('id_inscricao_evento', $id_inscricao_evento)->get()->first();
    }

    public function atualiza_trabalho_submetido($id, $id_inscricao_evento, $id_participante, $atualiza_trabalho)
    {
        $atualiza = DB::table('trabalho_submetido')->where('id', $id)->where('id_participante', $id_participante)->where('id_inscricao_evento', $id_inscricao_evento)->update($atualiza_trabalho);

        if ($atualiza) {
            return True;
        }else{
            return False;
        }
    }

    public function retorna_area_com_trabalho_submentido($coordenador_area, $id_inscricao_evento)
    {
        if (is_null($coordenador_area)) {
            return $this->where('id_inscricao_evento', $id_inscricao_evento)->distinct('id_area_trabalho')->pluck('id_area_trabalho');
        }else{
            return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('id_area_trabalho', $coordenador_area)->distinct('id_area_trabalho')->pluck('id_area_trabalho');
        }
    }

    public function retorna_todos_trabalhos($area_trabalho, $id_inscricao_evento)
    {
        if (is_null($area_trabalho)) {
            return $this->where('trabalho_submetido.id_inscricao_evento', $id_inscricao_evento)->join('finaliza_inscricao', 'finaliza_inscricao.id_participante', 'trabalho_submetido.id_participante')->join('area_pos_mat', 'area_pos_mat.id_area_pos', 'trabalho_submetido.id_area_trabalho')->join('users', 'users.id_user', 'trabalho_submetido.id_participante')->join('tipo_participacao', 'tipo_participacao.id_participante', 'trabalho_submetido.id_participante')->join('configura_tipo_apresentacao', 'configura_tipo_apresentacao.id', 'tipo_participacao.id_tipo_apresentacao')->join('configura_categoria_participante', 'configura_categoria_participante.id', 'tipo_participacao.id_categoria_participante')->select('trabalho_submetido.id_inscricao_evento', 'trabalho_submetido.id_participante', 'users.nome','trabalho_submetido.id_area_trabalho', 'area_pos_mat.nome_ptbr', 'trabalho_submetido.titulo_trabalho', 'configura_tipo_apresentacao.id AS id_tipo_apresentacao', 'configura_tipo_apresentacao.nome_tipo_apresentacao_ptbr', 'configura_categoria_participante.id AS id_categoria_participante', 'configura_categoria_participante.nome_categoria_ptbr')->get();
        }
    }

    
}