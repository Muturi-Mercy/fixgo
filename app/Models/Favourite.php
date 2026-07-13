<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = ['user_id', 'mechanic_id'];

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}