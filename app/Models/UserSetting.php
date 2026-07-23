<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';

    protected $fillable = [
        'user_id', 'notify_request_updates', 'notify_mechanic_nearby',
        'notify_chat', 'notify_promotions', 'notify_requests',
        'notify_messages', 'notify_reviews', 'notify_payments',
        'share_location', 'show_profile', 'show_earnings',
        'two_factor', 'language', 'currency'
    ];

    protected $casts = [
        'notify_request_updates' => 'boolean',
        'notify_mechanic_nearby' => 'boolean',
        'notify_chat'            => 'boolean',
        'notify_promotions'      => 'boolean',
        'notify_requests'        => 'boolean',
        'notify_messages'        => 'boolean',
        'notify_reviews'         => 'boolean',
        'notify_payments'        => 'boolean',
        'share_location'         => 'boolean',
        'show_profile'           => 'boolean',
        'show_earnings'          => 'boolean',
        'two_factor'             => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}