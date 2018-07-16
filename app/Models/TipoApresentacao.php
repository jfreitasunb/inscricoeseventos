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

    public function retorna_nome_tipo_participacao_por_id($id_tipo_apresentacao, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        return $this->select($nome_coluna)
            ->where('id', $id_tipo_apresentacao)
            ->value($nome_coluna);
    }

    public function pega_tipo_apresentacao($locale)
    {   
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        return $this->select('id', 'tipo_apresentacao.'.$nome_coluna.' AS nome_apresentacao')->orderBy('id')->get();
    }
}