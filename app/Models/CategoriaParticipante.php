<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CategoriaParticipante extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'categoria_participante';

    protected $fillable = [
        'nome_categoria_ptbr',
        'nome_categoria_en',
        'nome_categoria_es',
    ];

    public function define_nome_coluna_por_locale($locale)
    {
        switch ($locale) {
            case 'en':
                return 'nome_categoria_en';
                break;

            case 'es':
                return 'nome_categoria_es';
                break;
            
            default:
                return 'nome_categoria_ptbr';
                break;
        }
    }

    public function pega_nome_categoria($locale)
    {
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        return $this->select('id', 'categoria_participante.'.$nome_coluna.' AS participante_categoria')->orderBy('id')->get(); 
    }
}