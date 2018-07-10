<?php

namespace InscricoesEventosMat\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{

    public function retorna_nome_pais_por_id($id_pais)
    {
    	return $this->select('name')->where('id',$id_pais)->get()->first()->name;
    }

}
