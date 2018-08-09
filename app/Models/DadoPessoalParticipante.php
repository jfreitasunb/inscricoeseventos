<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DadoPessoalParticipante extends FuncoesModels
{
    protected $primaryKey = 'id_participante';

    protected $table = 'dados_pessoais_participante';

    protected $fillable = [
        'nome_cracha',
        'numero_documento',
        'instituicao',
        'pais',
        'atualizado',
    ];

    public function retorna_dados_pessoais($id_participante)
    {
        
        return $this->where('id_participante', $id_participante)->join('users', 'users.id_user', 'dados_pessoais_participante.id_participante')->select('users.nome', 'users.email', 'dados_pessoais_participante.*')->get()->first();

    }
}
