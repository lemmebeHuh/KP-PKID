<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Complaint;

class NewComplaintSubmitted extends Notification
{
    use Queueable;
    protected $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $customerName = $this->complaint->customer->name;
        return [
            'complaint_id' => $this->complaint->id,
            'customer_name' => $customerName,
            'message' => "Komplain baru diterima dari {$customerName} dengan subjek: '{$this->complaint->subject}'.",
            'url' => route('admin.complaints.edit', $this->complaint->id), // Arahkan ke halaman kelola komplain
        ];
    }
}