<?php

namespace InscricoesEventos\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends FuncoesModels
{

    protected $table = 'cidades';

    protected $fillable = ['nome', 'estado_id'];

    public $timestamps = false;

    public function estado()
    {
        return $this->belongsTo('InscricoesEventos\Models\Estado');
    }

    public function retorna_nome_cidade_por_id($id_cidade, $id_estado)
    {
    	return $this->select('name')->where('id',$id_cidade)->where('state_id',$id_estado)->get()->first()->name;
    }

}
