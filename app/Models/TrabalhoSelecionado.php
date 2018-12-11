<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrabalhoSelecionado extends FuncoesModels
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'trabalho_selecionado';

    protected $fillable = [
        'id_participante',
        'id_categoria_participante',
        'id_tipo_apresentacao',
        'id_area_trabalho',
        'id_inscricao_evento',
        'id_coordenador',
    ];


    public function limpa_selecoes_anteriores($id_coordenador, $id_inscricao_evento)
    {
        return $this->where('id_coordenador', $id_coordenador)->where('id_inscricao_evento', $id_inscricao_evento)->delete();
    }

    public function retorna_trabalhos_selecionados($id_inscricao_evento)
    {
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->orderBy('id_area_trabalho')->get();
    }

    public function existe_trabalho_selecionado($id_inscricao_evento)
    {
        $trabalhos_selecionados = $this->where('id_inscricao_evento', $id_inscricao_evento)->get();

        if (sizeof($trabalhos_selecionados) > 0) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
}