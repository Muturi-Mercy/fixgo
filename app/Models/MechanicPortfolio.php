<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MechanicPortfolio extends Model
{
    protected $fillable = [
        'mechanic_id', 'title', 'description', 'category', 'work_date'
    ];

    public function images()
    {
        return $this->hasMany(PortfolioImage::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}
