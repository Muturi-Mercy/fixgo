<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakdownRequest extends Model
{
    protected $fillable = [
        'request_number',
        'user_id', 
        'mechanic_id', 
        'service_category_id',
        'vehicle_category_id', 
        'problem_description', 
        'user_latitude',
        'user_longitude',
        'user_address', 
        'status', 'price',
        'accepted_at', 
        'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function vehicleCategory()
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function photos()
    {
        return $this->hasMany(RequestPhoto::class);
    }

    public function rating()
    {
        return $this->hasOne(RatingReview::class);
    }

    public function chat()
    {
        return $this->hasMany(ChatMessage::class);
    }
}