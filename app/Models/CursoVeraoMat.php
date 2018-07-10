<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CursoVeraoMat extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id_curso_verao';

    protected $table = 'curso_verao_mat';

    protected $fillable = [
        'nome_ptbr',
        'nome_en',
        'nome_es',
    ];

    public function retorna_cursos_de_verao()
    {
        return $this->where('id_curso_verao','>', 1)->orderBy('id_curso_verao')->get();
    }

    public function define_nome_coluna_por_locale($locale)
    {
        switch ($locale) {
            case 'en':
                return 'nome_en';
                break;

            case 'es':
                return 'nome_es';
                break;
            
            default:
                return 'nome_ptbr';
                break;
        }
    }

    public function pega_area_pos_mat($area_pos, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        if ($area_pos == 0) {
            return null;
        }else{
            return $this->select($nome_coluna)
            ->where('id_curso_verao', $area_pos)->where('id_curso_verao', '!=', 10)
            ->value($nome_coluna);
        }   
    }
}