<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrderUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'updated_by_id',
        'update_type',
        'notes',
        'status_from',
        'status_to',
    ];

    /**
     * Get the service order that this update belongs to.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    /**
     * Get the user (admin/technician) who made this update.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    /**
     * Get all photos associated with this update.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ServiceOrderPhoto::class);
    }
}