<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cottage extends Model
{
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'price_per_night',
        'is_whole_chalet',
        'description',
    ];
}

