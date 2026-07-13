<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionCollection extends Model
{
    protected $fillable = ['subscription_id', 'scheduled_date', 'time_slot', 'status', 'completed_at', 'notes'];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
