<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TipoParticipacao extends FuncoesModels
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'tipo_participacao';

    protected $fillable = [
        'id_participante',
        'id_categoria_participante',
        'id_inscricao_evento',
        'apresentar_trabalho',
        'id_tipo_apresentacao',
    ];

    public function pega_tipo_apresentacao()
    {
        return $this->get()->all();  
    }

    public function retorna_participacao($id_inscricao_evento, $id_participante)
    {
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('id_participante', $id_participante)->get()->first();  
    }

    public function atualiza_tipo_participacao($id, $id_inscricao_evento, $id_participante, $atualiza_participacao)
    {
        $atualiza = DB::table('tipo_participacao')->where('id', $id)->where('id_participante', $id_participante)->where('id_inscricao_evento', $id_inscricao_evento)->update($atualiza_participacao);
        
        if ($atualiza) {
            return True;
        }else{
            return False;
        }
    }
}