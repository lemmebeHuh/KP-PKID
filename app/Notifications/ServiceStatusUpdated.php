<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder; // Import model ServiceOrder

class ServiceStatusUpdated extends Notification
{
    use Queueable;

    protected $serviceOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Kita akan mengirim notifikasi ini melalui channel 'database'
        return ['database']; 
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data ini yang akan disimpan sebagai JSON di kolom 'data' pada tabel notifications
        return [
            'service_order_id' => $this->serviceOrder->id,
            'service_order_number' => $this->serviceOrder->service_order_number,
            'new_status' => $this->serviceOrder->status,
            'message' => "Status servis untuk order #{$this->serviceOrder->service_order_number} telah diperbarui menjadi '{$this->serviceOrder->status}'.",
            'url' => route('pelanggan.service-orders.show', $this->serviceOrder->id), // URL tujuan saat notifikasi diklik
        ];
    }
}