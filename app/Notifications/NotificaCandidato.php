<?php

namespace InscricoesEventosMat\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificaCandidato extends Notification
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
            ->subject(trans('mensagens_gerais.inscricao_mat'))
            ->line(trans('mensagens_gerais.inscricao_mat_1').$this->dados_email['nome_candidato'].',')
            ->line(trans('mensagens_gerais.inscricao_mat_2').$this->dados_email['programa'].trans('mensagens_gerais.inscricao_mat_3'))
            ->attach($this->dados_email['ficha_inscricao'], [
                        'as' => 'Ficha_de_Inscrição.pdf',
                        'mime' => 'application/pdf']);
    }
}
