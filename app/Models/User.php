<?php

namespace Veraomat\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Veraomat\Notifications\ResetPassword as ResetPasswordNotification;
use Notification;
use Veraomat\Notifications\LinkSenha;

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
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function isCandidato()
    {
        if (auth()->user()->user_type === 'candidato') {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function isRecomendante()
    {
        if (auth()->user()->user_type === 'recomendante') {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function registra_recomendante($novo_recomendante)
    {
        if (is_null($this->retorna_user_por_email($novo_recomendante['email']))){
                
            $novo_usuario = new User();
            $novo_usuario->nome = $novo_recomendante['nome'];
            $novo_usuario->email = $novo_recomendante['email'];
            $novo_usuario->password = bcrypt(date("d-m-Y H:i:s:u").str_random(10));
            $novo_usuario->user_type =  "recomendante";
            $novo_usuario->ativo = true;
            $novo_usuario->save();
        }elseif ($this->retorna_user_por_email($novo_recomendante['email'])->user_type <> "recomendante"){
                return true;
        }
    }
}
