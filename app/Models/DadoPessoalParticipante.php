<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DadoPessoalParticipante extends Model
{
    protected $primaryKey = 'id_candidato';

    protected $table = 'dados_pessoais_participante';

    protected $fillable = [
        'nome',
        'data_nascimento',
        'numerorg',
        'endereco',
        'cep',
        'pais',
        'estado',
        'cidade',
        'celular',
    ];

    public function retorna_dados_pessoais($id_candidato)
    {
        
        return $this->where('id_candidato', $id_candidato)->join('users', 'users.id_user', 'dados_pessoais_candidato.id_candidato')->select('users.nome', 'users.email', 'dados_pessoais_candidato.*')->get()->first();

    }
}
