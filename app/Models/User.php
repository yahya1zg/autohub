<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    // Kullanıcının favori arabaları ile olan ilişkisi
    public function favoriteCars()
    {
        return $this->belongsToMany(Car::class, 'favorite_cars', 'user_id', 'car_id');
    }

    // Kullanıcının sepetindeki arabalar ile olan ilişkisi (YENİ)
    public function cartItems()
    {
        return $this->belongsToMany(Car::class, 'cart_items', 'user_id', 'car_id')->withTimestamps();
    }

    // Kullanıcının siparişleri ile olan ilişkisi
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
// Bu model, kullanıcıların favori arabalarını ve sepetindeki arabaları yönetmek için kullanılır.
// Ayrıca, kullanıcıların siparişlerini de yönetir.