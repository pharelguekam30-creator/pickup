<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPlan extends Model
{
    protected $fillable = ['name', 'description', 'type', 'collections_per_week', 'collection_days', 'price_per_month', 'is_active'];

    protected $casts = [
        'collection_days' => 'array',
        'is_active' => 'boolean',
    ];
}
