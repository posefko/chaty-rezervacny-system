<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'cottage_id',
        'user_id',
        'date_from',
        'date_to',
        'guests',
        'status',
        'note',
    ];

    public function cottage(): BelongsTo
    {
        return $this->belongsTo(Cottage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
