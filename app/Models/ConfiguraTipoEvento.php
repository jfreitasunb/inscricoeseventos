<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ConfiguraTipoEvento extends FuncoesModels
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'configura_tipo_evento';

    protected $fillable = [
        'tipo',
    ];

    public function retorna_tipo_eventos()
    {
        return $this->all();
    }
}