<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class AreaPosMat extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id_area_pos';

    protected $table = 'area_pos_mat';

    protected $fillable = [
        'nome_ptbr',
        'nome_en',
        'nome_es',
    ];

    public function retorna_cursos_de_verao()
    {
        return $this->where('id_area_pos','>', 1)->orderBy('id_area_pos')->get();
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
            ->where('id_area_pos', $area_pos)->where('id_area_pos', '!=', 10)
            ->value($nome_coluna);
        }   
    }

    public function retorna_areas_evento($id_area_evento, $locale)
    {   
        $nome_coluna = $this->define_nome_coluna_por_locale($locale);

        if (is_null($id_area_evento)) {
            return $this->select('id_area_pos', $nome_coluna)->orderBy($nome_coluna)->get()->pluck($nome_coluna, 'id_area_pos');
        }else{
            return $this->where('id_area_pos', $id_area_evento)->select('id_area_pos', $nome_coluna)->orderBy('id_area_pos')->get()->pluck($nome_coluna, 'id_area_pos')->toArray();
        }
        
    }
}