<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type', // 'product', 'service', 'article'
    ];

    /**
     * Get the products associated with the category.
     */
        public function products(): HasMany
    {
        return $this->hasMany(Product::class); // Mengasumsikan Product memiliki category_id
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class); // Mengasumsikan Service memiliki category_id
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class); // Mengasumsikan Article memiliki category_id
    }
        
    // public function products(): HasMany // Type hinting untuk relasi
    // {
    //     // Asumsi 'type' untuk kategori produk adalah 'product'
    //     return $this->hasMany(Product::class)->where('type', 'product'); 
    //     // Jika tidak ada kolom 'type' di tabel products untuk membedakan,
    //     // dan category_id di products sudah cukup, maka cukup:
    //     // return $this->hasMany(Product::class);
    //     // Namun, karena kolom 'type' ada di tabel 'categories', kita bisa gunakan itu
    //     // untuk memastikan kita hanya mengambil kategori yang relevan jika satu tabel category
    //     // melayani banyak jenis.
    //     // Berdasarkan desain, 'type' ada di 'categories', jadi kita filter di sini.
    // }

    // /**
    //  * Get the services (jenis jasa) associated with the category.
    //  */
    // public function services(): HasMany // Type hinting
    // {
    //     // Asumsi 'type' untuk kategori jasa adalah 'service'
    //     return $this->hasMany(Service::class)->where('type', 'service');
    //     // Sama seperti products, jika tidak ada pembeda di tabel services,
    //     // cukup: return $this->hasMany(Service::class);
    // }

    // /**
    //  * Get the articles associated with the category.
    //  */
    // public function articles(): HasMany // Type hinting
    // {
    //     // Asumsi 'type' untuk kategori artikel adalah 'article'
    //     return $this->hasMany(Article::class)->where('type', 'article');
    //     // Sama seperti di atas, jika tidak ada pembeda di tabel articles,
    //     // cukup: return $this->hasMany(Article::class);
    // }
}