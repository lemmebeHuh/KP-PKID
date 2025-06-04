<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'estimated_price',
        'estimated_duration',
    ];

    /**
     * Get the category that owns the service.
     */
    public function category(): BelongsTo // Type hinting
    {
        return $this->belongsTo(Category::class);
    }

    // Nanti kita bisa tambahkan relasi ke ServiceOrder jika satu jenis service bisa ada di banyak order
    // public function serviceOrders()
    // {
    //     return $this->hasMany(ServiceOrder::class); // Asumsi di ServiceOrder ada service_id (FK)
    // }
    // Namun, berdasarkan desain kita, ServiceOrder tidak memiliki FK langsung ke tabel `services` (jenis jasa),
    // melainkan detail layanan mungkin dicatat langsung di ServiceOrder atau melalui deskripsi.
    // Jika ada kebutuhan relasi, skema database perlu disesuaikan. Untuk saat ini, kita skip dulu.
}