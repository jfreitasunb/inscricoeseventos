<?php

namespace InscricoesEventosMat\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailRememberRecomendante extends Notification
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

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->from('posgrad@mat.unb.br', 'Postgraduate Comitee of MAT/UnB')
                    ->subject('Deadline for submission of the recommendation letter')
                    ->line('Dear professor, '.$this->dados_email['nome_professor'].',')
                    ->line('The deadline for submission of the recommendation letter is: '.$this->dados_email['prazo_carta'].'.')
                    ->action('Click here to send the recommendation letter', url('/'))
                    ->line('Click here to create your password '.url('esqueci/senha'))
                    ->line('Sincerely,')
                    ->line('Postgraduate Comitee of MAT/UnB.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
