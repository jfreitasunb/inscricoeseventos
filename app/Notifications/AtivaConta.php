<?php

namespace Veraomat\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AtivaConta extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('tela_ativar_conta.assunto_email'))
            ->line(trans('tela_ativar_conta.texto_ativar'))
            ->action(trans('tela_ativar_conta.clique_aqui'), url('register/verify', $this->token), '.');
    }
}
