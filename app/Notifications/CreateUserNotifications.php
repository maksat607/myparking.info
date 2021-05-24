<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CreateUserNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $password = null;
    public function __construct($password)
    {
        $this->password = $password;
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
        if(!is_null($notifiable)) {
            $userName = $notifiable->name;
        }
        return (new MailMessage)
            ->greeting(sprintf('%s %s', Lang::get('Hello,'), $userName) )
            ->subject(sprintf('%s %s.', Lang::get('You were registered on the company\'s website'), env('APP_NAME' )))
            ->line(Lang::get('Your username and password:'))
            ->line(sprintf('%s: %s.', Lang::get('Email'), $notifiable->email))
            ->line(sprintf('%s: %s.', Lang::get('Password'), $this->password))
            ->action(Lang::get('Login'), route('login'))
            ->line(Lang::get('Change your password after confirming your registration.'));

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
