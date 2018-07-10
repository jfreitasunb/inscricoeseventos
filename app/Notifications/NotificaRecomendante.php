<?php

namespace Veraomat\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotificaRecomendante extends Notification
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
            ->from('posgrad@mat.unb.br', 'Postgraduate Comitee of MAT/UnB')
            ->subject('Recommendation letter to MAT/UnB')
            ->line('Dear professor, '.$this->dados_email['nome_professor'].',')
            ->line('you have been appointed for reference to the candidate to the postgradute program at MAT/UnB '.$this->dados_email['programa'].' '.$this->dados_email['nome_candidato'].'.')
            ->line('The recommendation letter must be sent by the website: '.url('/'))
            ->line('To access the system use as login: '.$this->dados_email['email_recomendante'])
            ->line('The deadline for submission of the recommendation letter is: '.$this->dados_email['prazo_envio'].'.')
            ->action('Click here to create your password', url('esqueci/senha'))
            ->line('Sincerely, postgraduate comitee of MAT/UnB.');
    }
}
