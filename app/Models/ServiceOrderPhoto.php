<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_update_id',
        'file_path',
        'caption',
        'uploaded_by_id',
    ];

    /**
     * Get the service order update that this photo belongs to.
     */
    public function serviceOrderUpdate(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderUpdate::class);
    }

    /**
     * Get the user (admin/technician) who uploaded this photo.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }
}