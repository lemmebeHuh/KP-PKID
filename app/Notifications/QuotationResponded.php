<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder;

class QuotationResponded extends Notification
{
    use Queueable;
    protected $serviceOrder;

    public function __construct(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $customerName = $this->serviceOrder->customer->name;
        $decision = $this->serviceOrder->customer_approval_status; // "Approved" atau "Rejected"
        $decisionText = ($decision === 'Approved') ? 'MENYETUJUI' : 'MENOLAK';

        return [
            'order_id' => $this->serviceOrder->id,
            'order_number' => $this->serviceOrder->service_order_number,
            'message' => "Pelanggan {$customerName} telah {$decisionText} penawaran untuk order #{$this->serviceOrder->service_order_number}.",
            'url' => route('admin.service-orders.show', $this->serviceOrder->id),
        ];
    }
}