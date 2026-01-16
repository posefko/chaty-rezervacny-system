<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Reservation;

class Cottage extends Model
{
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'price_per_night',
        'is_whole_chalet',
        'description',
        'image_path',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

