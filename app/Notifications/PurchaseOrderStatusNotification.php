<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderStatusNotification extends Notification
{
    use Queueable;

    protected $po;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($po, $status)
    {
        $this->po = $po;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan di database
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Purchase Order {$this->po->po_number} telah {$this->status}.",
            'po_id' => $this->po->id,
            'url' => route('purchase-orders.show', $this->po->id),
        ];
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
