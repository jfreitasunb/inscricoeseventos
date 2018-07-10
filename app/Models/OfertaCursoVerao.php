<?php

namespace InscricoesEventosMat\Models;

use Illuminate\Database\Eloquent\Model;

class OfertaCursoVerao extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id';

    protected $table = 'oferta_curso_verao';

    public function define_nome_coluna_por_locale($locale_candidato)
    {
        switch ($locale_candidato) {
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

    public function retorna_cursos_ofertados($id_inscricao_verao, $locale_candidato)
    {
        $nome_coluna = $this->define_nome_coluna_por_locale($locale_candidato);

        return $this->where('id_inscricao_verao', $id_inscricao_verao)->join('curso_verao_mat','curso_verao_mat.id_curso_verao', 'oferta_curso_verao.id_curso_verao')->select('oferta_curso_verao.id_curso_verao', 'curso_verao_mat.'.$nome_coluna, 'oferta_curso_verao.seleciona_pos')->get();
    }

    public function retorna_seleciona_pos($id_inscricao_verao, $id_curso_verao)
    {
        return $this->select('seleciona_pos')
            ->where('id_inscricao_verao', $id_inscricao_verao)
            ->where('id_curso_verao', $id_curso_verao)
            ->value('seleciona_pos');
    }

}
