<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $primaryKey = 'id_candidato';

    protected $table = 'arquivos_enviados';

    protected $fillable = [
        'nome_arquivo',
        'tipo_arquivo',
    ];

    public function retorna_arquivo_enviado($id_candidato)
    {
    	return $this->select('nome_arquivo')->where('id_candidato',$id_candidato)->orderBy('created_at','desc')->first();
    }

    public function retorna_historico($id_candidato,$id_inscricao_verao)
    {
        if (!is_null($this->select('nome_arquivo')->where('id_candidato',$id_candidato)->where('tipo_arquivo','Histórico')->where('id_inscricao_verao',$id_inscricao_verao)->orderBy('created_at','desc')->first())) {
            
            return $this->select('nome_arquivo')->where('id_candidato',$id_candidato)->where('tipo_arquivo','Histórico')->where('id_inscricao_verao',$id_inscricao_verao)->orderBy('created_at','desc')->first();
        }else{

            return $this->select('nome_arquivo')->where('id_candidato',$id_candidato)->where('tipo_arquivo','Histórico')->orderBy('created_at','desc')->first();
        }
    }

    public function retorna_documento($id_candidato,$id_inscricao_verao)
    {   
        if (!is_null($this->select('nome_arquivo')->where('id_candidato',$id_candidato)->where('tipo_arquivo','Documentos')->where('id_inscricao_verao',$id_inscricao_verao)->orderBy('created_at','desc')->first())) {
            
            return $this->select('nome_arquivo')->where('id_candidato',$id_candidato)->where('tipo_arquivo','Documentos')->where('id_inscricao_verao',$id_inscricao_verao)->orderBy('created_at','desc')->first();
        }else{

            return $this->select('nome_arquivo')->where('id_candidato',$id_candidato)->where('tipo_arquivo','Documentos')->orderBy('created_at','desc')->first();

        }
    }
}
