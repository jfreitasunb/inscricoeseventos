<?php

namespace Veraomat\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DadoAcademicoCandidato extends Model
{
    protected $primaryKey = 'id_candidato';

    protected $table = 'dados_academicos_candidato';

    protected $fillable = [
        'curso_graduacao',
        'tipo_curso_graduacao',
        'instituicao_graduacao',
        'ano_conclusao_graduacao',
        'curso_pos',
        'tipo_curso_pos',
        'instituicao_pos',
        'ano_conclusao_pos',
    ];

public function retorna_dados_academicos($id_candidato)
    {

        return $this->find($id_candidato);

    }
}