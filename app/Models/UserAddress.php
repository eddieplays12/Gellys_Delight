<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
