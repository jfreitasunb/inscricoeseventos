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
        'id_inscricao_evento',
        'deleted_at',
    ];

    public function limpa_conta_criada($id_coordenador, $id_inscricao_evento)
    {
        return $this->where('id_coordenador', $id_coordenador)->where('id_inscricao_evento', $id_inscricao_evento)->delete();
    }

    public function retorna_nivel_coordenador($id_coordenador, $id_inscricao_evento)
    {
        return $this->select('coordenador_geral')
            ->where('id_inscricao_evento', $id_inscricao_evento)->where('id_coordenador', $id_coordenador)
            ->value('coordenador_geral');
    }

    public function retorna_dados_coordenador($id_coordenador, $id_inscricao_evento)
    {
        return $this->where('id_inscricao_evento', $id_inscricao_evento)->where('id_coordenador', $id_coordenador)
            ->get()->first();
    }
}