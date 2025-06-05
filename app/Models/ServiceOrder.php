<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_order_number',
        'customer_id',
        'assigned_technician_id',
        'device_type',
        'device_brand_model',
        'serial_number',
        'problem_description',
        'accessories_received',
        'status',
        'estimated_completion_date',
        'quotation_details',
        'customer_approval_status',
        'final_cost',
        'date_received',
        'date_completed',
        'date_picked_up',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_received' => 'datetime',
        'date_completed' => 'datetime',
        'date_picked_up' => 'datetime',
        'estimated_completion_date' => 'date',
        'final_cost' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the service order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the technician assigned to the service order.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    /**
     * Get all updates for the service order.
     */
    public function updates(): HasMany
    {
        return $this->hasMany(ServiceOrderUpdate::class);
    }

    /**
     * Get the review for the service order.
     * (Assuming one order has one review based on unique service_order_id in reviews table)
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get all complaints for the service order.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get the warranty for the service order.
     */
    public function warranty(): HasOne
    {
        return $this->hasOne(Warranty::class);
    }
}