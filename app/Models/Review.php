<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'cottage_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function cottage()
    {
        return $this->belongsTo(Cottage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
