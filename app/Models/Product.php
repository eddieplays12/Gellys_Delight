<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
    ];

    protected $appends = ['image_url'];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return "https://via.placeholder.com/300x200?text=No+Image";
        }

        if (Route::has('product.image')) {
            return route('product.image', ['path' => $this->image]);
        }

        return url('storage/' . $this->image);
    }
}
