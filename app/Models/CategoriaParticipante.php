<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CategoriaParticipante extends FuncoesModels
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

    

    public function retorna_nome_categoria_por_id($id_categoria_participante, $locale)
    {
        $nome_coluna = $this->define_nome_coluna_categoria($locale);

        return $this->select($nome_coluna)
            ->where('id', $id_categoria_participante)
            ->value($nome_coluna);
    }

    public function pega_nome_categoria($locale)
    {
        $nome_coluna = $this->define_nome_coluna_categoria($locale);

        return $this->select('id', 'categoria_participante.'.$nome_coluna.' AS participante_categoria')->orderBy('id')->get();
    }
}