<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika Anda berencana menggunakan Sanctum untuk API
use Notification;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Pastikan ini ada
        'phone_number',
        'address',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function serviceOrdersAsCustomer(): HasMany
    {
        // Pastikan 'customer_id' adalah foreign key di tabel service_orders
        return $this->hasMany(ServiceOrder::class, 'customer_id');
    }

    /**
     * Get the service orders assigned to the user if they are a technician.
     */
    public function serviceOrdersAsTechnician(): HasMany
    {
        // Pastikan 'assigned_technician_id' adalah foreign key di tabel service_orders
        return $this->hasMany(ServiceOrder::class, 'assigned_technician_id');
    }

    /**
     * Get the articles written by the user if they are an author.
     */
    public function articles(): HasMany
    {
        // Pastikan 'author_id' adalah foreign key di tabel articles
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Get the reviews made by the user if they are a customer.
     */
    public function reviews(): HasMany
    {
        // Pastikan 'customer_id' adalah foreign key di tabel reviews
        return $this->hasMany(Review::class, 'customer_id');
    }

    /**
     * Get the complaints made by the user if they are a customer.
     */
    public function complaints(): HasMany
    {
        // Pastikan 'customer_id' adalah foreign key di tabel complaints
        return $this->hasMany(Complaint::class, 'customer_id');
    }

    /**
     * Get the technician profile for the user if they are a technician.
     */
    public function technicianProfile(): HasOne
    {
        // Pastikan 'user_id' adalah foreign key (dan PK) di tabel technician_profiles
        return $this->hasOne(TechnicianProfile::class, 'user_id');
    }
}
