<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderEventNotification extends Notification
{
    use Queueable;

    public function __construct(
        public array $payload
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->payload['subject'] ?? 'Update pesanan SABISHOP';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line($this->payload['message'] ?? 'Ada update untuk pesanan Anda.')
            ->line('Order #' . ($this->payload['order_id'] ?? '-'))
            ->action($this->payload['action_text'] ?? 'Lihat Pesanan', $this->payload['action_url'] ?? url('/orders'))
            ->line('Terima kasih sudah berbelanja di SABISHOP.');
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload;
    }
}
