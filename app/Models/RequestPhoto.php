<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPhoto extends Model
{
    protected $fillable = ['breakdown_request_id','photo_path'];
}
