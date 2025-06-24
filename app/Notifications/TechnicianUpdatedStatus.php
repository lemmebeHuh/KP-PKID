<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder;
use App\Models\User;

class TechnicianUpdatedStatus extends Notification
{
    use Queueable;

    protected $serviceOrder;
    protected $technician;

    /**
     * Create a new notification instance.
     */
    public function __construct(ServiceOrder $serviceOrder, User $technician)
    {
        $this->serviceOrder = $serviceOrder;
        $this->technician = $technician;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kirim ke database untuk notifikasi in-app
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'technician_update', // Tipe baru untuk ikon notifikasi
            'order_id' => $this->serviceOrder->id,
            'order_number' => $this->serviceOrder->service_order_number,
            'technician_name' => $this->technician->name,
            'new_status' => $this->serviceOrder->status,
            'message' => "Teknisi {$this->technician->name} mengubah status order #{$this->serviceOrder->service_order_number} menjadi '{$this->serviceOrder->status}'.",
            'url' => route('admin.service-orders.show', $this->serviceOrder->id), // URL ke halaman detail order di SISI ADMIN
        ];
    }
}