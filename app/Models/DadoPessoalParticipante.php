<?php

namespace InscricoesEventosMat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DadoPessoalParticipante extends Model
{
    protected $primaryKey = 'id_participante';

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

    public function retorna_dados_pessoais($id_participante)
    {
        
        return $this->where('id_participante', $id_participante)->join('users', 'users.id_user', 'dados_pessoais_participante.id_participante')->select('users.nome', 'users.email', 'dados_pessoais_participante.*')->get()->first();

    }
}
