<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mechanic extends Model
{
    protected $fillable = [
        'user_id',
        'specialization',
        'years_of_experience',
        'bio',
        'location_address',
        'latitude',
        'longitude',
        'availability',
        'verification_status',
        'rating',
        'total_jobs',
        'response_time',
        'min_price',
        'max_price',
    ];

    /**
     * Get the user that owns the mechanic profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}