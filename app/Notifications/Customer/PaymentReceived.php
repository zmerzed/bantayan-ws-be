<?php

namespace App\Notifications\Customer;

use Kolette\Marketplace\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    public function toDatabase($notifiable)
    {
        return $this->data($notifiable);
    }

    private function data($notifiable)
    {
        return [
            'id' => $this->order->id,
            'type' => 'payment_received',
            'message' => "We have received your payment",
            'actor_id' => $notifiable->id,
            'actor_name' => $notifiable->full_name,
            'actor_avatar' => optional($notifiable->avatar)->getFullUrl(),
            'notifiable_id' => $notifiable->id,
            'notifiable_name' => $notifiable->full_name,
            'notifiable_avatar' => optional($notifiable->avatar)->getFullUrl(),
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
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
