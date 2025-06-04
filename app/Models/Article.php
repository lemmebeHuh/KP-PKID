<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'published_at',
        'featured_image_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the category that owns the article.
     */
    public function category(): BelongsTo // Type hinting
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the author of the article.
     */
    public function author(): BelongsTo // Type hinting
    {
        // Kita perlu spesifikasikan foreign key jika tidak mengikuti konvensi Laravel (author_id sudah benar)
        return $this->belongsTo(User::class, 'author_id');
    }
}