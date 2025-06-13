<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Review; // Import model Review

class NewReviewSubmitted extends Notification
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Kirim ke database untuk notifikasi in-app
    }

    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'customer_name' => $this->review->customer->name,
            'rating' => $this->review->rating,
            'message' => "Ulasan baru ({$this->review->rating} bintang) diterima dari {$this->review->customer->name}.",
            'url' => route('admin.reviews.index'), // URL ke halaman manajemen ulasan
        ];
    }
}