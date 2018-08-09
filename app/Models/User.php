<?php

namespace InscricoesEventos\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use InscricoesEventos\Notifications\ResetPassword as ResetPasswordNotification;
use Notification;
use InscricoesEventos\Notifications\LinkSenha;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nome',
        'email',
        'locale',
        'password',
        'validation_code',
        'ativo',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'validation_code', 'remember_token'
    ];

    public function verified()
    {
        $this->ativo = 1;
        $this->validation_code = null;
        $this->save();
    }

    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        // Notification::send(User::find('1'), new LinkSenha($token));
        $this->notify(new ResetPasswordNotification($token));
        
    }

    // public function retorna_usuario_por_email($email)
    // {
    //     return $this->where('email',$email)->join('dados_pessoais', 'dados_pessoais.id_user','users.id_user')->get()->first();

    // }

    public function retorna_user_por_email($email)
    {
        return $this->get()->where('email',$email)->first();
    }

    public function retorna_user_por_nome($nome_pesquisado)
    {
        return $this->where('nome', 'ILIKE', $nome_pesquisado.'%')->get();
    }
    
    public function retorna_papeis()
    {
        return $this->groupBy('user_type')->orderBy('user_type')->pluck('user_type');
    }


    public function retorna_contas_nao_ativas()
    {
        return $this->where('ativo',FALSE)->orderBy('users.created_at', 'DESC');
    }

    public function isAdmin()
    {
        if (auth()->user()->user_type === 'admin') {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function isCoordenador()
    {

        if (auth()->user()->user_type === 'coordenador') {
            
            $tipo_coordenador = new TipoCoordenador();
            
            $evento = new ConfiguraInscricaoEvento();

            $evento_vigente = $evento->retorna_edital_vigente();

            $id_inscricao_evento = $evento_vigente->id_inscricao_evento;

            $coordenador_geral = $tipo_coordenador->retorna_nivel_coordenador(auth()->user()->id_user, $id_inscricao_evento);

            if ($coordenador_geral) {
                return TRUE;
            }else{

                return False;
            }

        }else{
            return FALSE;
        }
    }

    public function isCoordenador_Area()
    {

        if (auth()->user()->user_type === 'coordenador') {
            
            $tipo_coordenador = new TipoCoordenador();
            
            $evento = new ConfiguraInscricaoEvento();

            $evento_vigente = $evento->retorna_edital_vigente();

            $id_inscricao_evento = $evento_vigente->id_inscricao_evento;

            $coordenador_geral = $tipo_coordenador->retorna_nivel_coordenador(auth()->user()->id_user, $id_inscricao_evento);

            if ($coordenador_geral) {
                return False;
            }else{

                return True;
            }

            
        }else{
            return FALSE;
        }
    }

    public function isParticipante()
    {
        if (auth()->user()->user_type === 'participante') {
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
