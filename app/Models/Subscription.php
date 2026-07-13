<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['collection_plan_id', 'client_id', 'vidangeur_id', 'start_date', 'end_date', 'status', 'current_month_start', 'current_month_end', 'month_status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_month_start' => 'date',
        'current_month_end' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(CollectionPlan::class, 'collection_plan_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function vidangeur()
    {
        return $this->belongsTo(User::class, 'vidangeur_id');
    }

    public function collections()
    {
        return $this->hasMany(SubscriptionCollection::class);
    }
}
