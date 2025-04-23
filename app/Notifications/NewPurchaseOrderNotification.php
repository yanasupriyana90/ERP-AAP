<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPurchaseOrderNotification extends Notification
{
    use Queueable;

    protected $po;
    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($po, $message)
    {
        $this->po = $po;
        $this->message = $message;
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Purchase Order {$this->po->po_number} membutuhkan persetujuan Anda.",
            'po_id' => $this->po->id,
            'url' => route('purchase-orders.show', $this->po->id),
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
            ->subject('Notifikasi Purchase Order')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($this->message)
            ->action('Lihat Purchase Order', url('/purchase-orders/' . $this->po->id))
            ->line('Terima kasih telah menggunakan ERP AAP!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'po_id' => $this->po->id,
            'message' => $this->message,
        ];
    }
}
