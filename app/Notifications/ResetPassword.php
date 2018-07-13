<?php

namespace InscricoesEventos\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
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
            ->subject(trans('tela_mudar_senha.assunto_email'))
            ->line(trans('tela_mudar_senha.motivo_mensagem'))
            ->action(trans('tela_mudar_senha.mudar_senha'), url('esqueci/senha', $this->token))
            ->line(trans('tela_mudar_senha.nao_solicitou'));
    }
}
