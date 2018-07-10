<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'formacao';

    protected $fillable = [
        'tipo_ptbr',
        'tipo_en',
        'tipo_es',
    ];

    public function define_coluna_formacao_por_locale($locale)
    {
        switch ($locale) {
            case 'en':
                return 'tipo_en';
                break;

            case 'es':
                return 'tipo_es';
                break;
            
            default:
                return 'tipo_ptbr';
                break;
        }

    }

    public function pega_tipo_formacao($id,$nivel, $locale)
    {
        $nome_coluna = $this->define_coluna_formacao_por_locale($locale);
        
        return $this->select($nome_coluna)
            ->where('id', $id)
            ->where('nivel', $nivel)
            ->value($nome_coluna);
    }

     public function retorna_id_formacao($tipo,$nivel, $locale_candidato)
     {
        $nome_coluna = $this->define_coluna_formacao_por_locale($locale);

        return $this->select('id')
            ->where($nome_coluna, $tipo)
            ->where('nivel', $nivel)
            ->value('id');
    }

    // public function pega_disciplinas_monitoria()
    // {
    
    //     $disciplinas_para_monitoria = $this->select('codigo', 'nome')->orderBy('nome')->get();

    //     return $disciplinas_para_monitoria;
    // }

    // public function retorna_nome_pelo_codigo($codigo)
    // {
    //     $nome_disciplina = $this->select('nome')->where('codigo',$codigo)->get();

    //     return $nome_disciplina;

    // }
}