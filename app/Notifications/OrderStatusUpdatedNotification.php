<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $serviceOrder;

    public function __construct(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    /**
     * Tentukan channel pengiriman notifikasi.
     * Kita hanya ingin menyimpannya di database.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Ubah notifikasi menjadi array yang akan disimpan di database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->serviceOrder->id,
            'order_number' => $this->serviceOrder->service_order_number,
            'message' => "Status servis untuk order #{$this->serviceOrder->service_order_number} telah diubah menjadi '{$this->serviceOrder->status}'.",
            'url' => route('pelanggan.service-orders.show', $this->serviceOrder->id),
        ];
    }
}