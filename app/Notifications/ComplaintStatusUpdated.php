<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Complaint; // Import model Complaint

class ComplaintStatusUpdated extends Notification
{
    use Queueable;

    protected $complaint;

    /**
     * Create a new notification instance.
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
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
            'type' => 'complaint_update', // Tipe baru untuk notifikasi ini
            'complaint_id' => $this->complaint->id,
            'complaint_subject' => $this->complaint->subject,
            'new_status' => $this->complaint->status,
            'message' => "Status komplain Anda ('" . \Illuminate\Support\Str::limit($this->complaint->subject, 20) . "') telah diubah menjadi '{$this->complaint->status}'.",
            'url' => route('pelanggan.complaints.index'), // URL ke halaman daftar komplain pelanggan
        ];
    }
}