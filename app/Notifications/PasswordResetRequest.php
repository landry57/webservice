<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification
{
    use Queueable;
    protected $token;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token,$url='')
    {   $this->url=$url;
        $this->token = $token;
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
       $url="";  
        if(!empty($this->url)){
        $url =$this->url.$this->token;
        }else{
           $url = url('http://d1815c57.ngrok.io/yetiweb/login/find/'.$this->token);
        }
        return (new MailMessage)
           ->greeting('Salut!')
            ->line('Vous recevez cet e-mail, car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
            ->action('Réinitialisez', url($url))
            ->line("Si vous n'avez pas demandé de réinitialisation du mot de passe, aucune autre action n'est requise.");
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
