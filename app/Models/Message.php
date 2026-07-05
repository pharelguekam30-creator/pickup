<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false;

    protected $fillable = ['reservation_id', 'sender_id', 'message', 'created_at', 'is_read'];

    protected $casts = ['created_at' => 'datetime'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
