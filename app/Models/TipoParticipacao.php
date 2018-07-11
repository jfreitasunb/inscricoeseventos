<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TipoParticipacao extends Model
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
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('id_participante', $id_participante)->get();  
    }
}