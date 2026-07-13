<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    protected $table = 'ratings_reviews';
    
    protected $fillable = [
        'breakdown_request_id',
        'user_id',
        'mechanic_id',
        'rating',
        'review'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function breakdownRequest()
    {
        return $this->belongsTo(BreakdownRequest::class);
    }
}

