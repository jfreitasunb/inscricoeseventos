<?php

namespace InscricoesEventos\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends FuncoesModels
{

    public function retorna_nome_pais_por_id($id_pais)
    {
    	return $this->select('name')->where('id',$id_pais)->get()->first()->name;
    }

}
