<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class SignupActivate extends Notification implements ShouldQueue
{
    use Queueable;
    protected $password, $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->password = $password;
        $this->user = $user;
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
        // var_dump($password); die;
        $url = url('/signup/activate/' . $notifiable->activation_token);
        // $url = url('/api/auth/signup/activate/'.$notifiable->activation_token);

        return (new MailMessage)
            ->subject('Confirm your account')
            ->line('You have been added to the Boxleo courier app! Before you begin, you must confirm your account. Login with your email and this password:' . $this->password)
            ->action('Confirm Account', url($url))
            ->line('Thank you for using our application!');
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
