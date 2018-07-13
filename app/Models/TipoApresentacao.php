<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TipoApresentacao extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'tipo_apresentacao';

    protected $fillable = [
        'nome_tipo_apresentacao_ptbr',
        'nome_tipo_apresentacao_en',
        'nome_tipo_apresentacao_es',
    ];

    public function define_nome_coluna_por_locale($locale)
    {
        switch ($locale) {
            case 'en':
                return 'nome_tipo_apresentacao_en';
                break;

            case 'es':
                return 'nome_tipo_apresentacao_es';
                break;
            
            default:
                return 'nome_tipo_apresentacao_ptbr';
                break;
        }
    }

    public function pega_tipo_apresentacao()
    {
        return $this->get()->all();  
    }
}