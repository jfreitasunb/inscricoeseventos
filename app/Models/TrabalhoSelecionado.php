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
}