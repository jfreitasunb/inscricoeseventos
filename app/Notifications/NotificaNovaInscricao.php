<?php

namespace InscricoesEventos\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificaNovaInscricao extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $dados_email = [];

    public function __construct(array $dados_email)
    {
        $this->dados_email = $dados_email;


    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('posgrad@mat.unb.br', 'Coordenação de Pós-Graduação do MAT/UnB')
            ->subject('Nova inscrição configurada')
            ->line('Fique atento, uma nova inscrição da Pós começará em breve. Veja os dados abaixo: ')
            ->line('Início da inscrição: '.$this->dados_email['inicio_inscricao'])
            ->line('Fim da inscrição: '.$this->dados_email['fim_inscricao'])
            ->line('Prazo para envio das cartas: '.$this->dados_email['prazo_carta'])
            ->line('Inscrição para: '.$this->dados_email['programa']);
    }
}
