<?php
// Veritabanındaki 'cars' tablosunu temsil eder.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_name', 'series', 'fuel_type', 'price', 'year', 
        'description', 'main_image_url', 'gallery_images', 'status'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'price' => 'decimal:2',
    ];

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_cars', 'car_id', 'user_id');
    }
}
