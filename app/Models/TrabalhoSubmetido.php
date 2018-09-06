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

    
}