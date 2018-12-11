<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ConfiguraDetalhesEvento extends FuncoesModels
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'configura_detalhes_evento';

    protected $fillable = [
        'titulo_evento', 'periodo_realizacao'
    ];

    public function retorna_detalhes_evento($id_inscricao_evento)
    {
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->get()->first();
    }
}