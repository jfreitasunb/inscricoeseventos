<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoCoordenador extends FuncoesModels
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'tipo_coordenador';

    protected $fillable = [
        'id_coordenador',
        'coordenador_geral',
        'coordenador_area',
        'id_evento',
        'deleted_at',
    ];

}