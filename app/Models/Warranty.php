<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'start_date',
        'end_date',
        'terms',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date:Y-m-d', // Sesuaikan format jika perlu
        'end_date' => 'date:Y-m-d',   // Sesuaikan format jika perlu
    ];

    /**
     * Get the service order that this warranty belongs to.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}