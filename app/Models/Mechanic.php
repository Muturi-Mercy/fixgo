<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'max_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakdownRequests()
    {
        return $this->hasMany(BreakdownRequest::class);
    }

    public function portfolios()
    {
        return $this->hasMany(MechanicPortfolio::class);
    }

    public function ratings()
    {
        return $this->hasMany(RatingReview::class);
    }

    public function documents()
    {
        return $this->hasMany(MechanicDocument::class);
    }

    public function favouritedBy()
    {
        return $this->hasMany(Favourite::class);
    }
}