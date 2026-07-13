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
}

