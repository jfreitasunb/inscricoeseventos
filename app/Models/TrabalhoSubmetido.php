<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TrabalhoSubmetido extends Model
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

    public function pega_tipo_apresentacao()
    {
        return $this->get()->all();  
    }
}