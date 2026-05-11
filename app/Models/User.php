<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'country', 'region', 
        'city', 'quarter', 'address', 'role', 'tarif', 
        'disponibilite', 'birthdate'
    ];

    protected $hidden = ['password', 'remember_token'];

    // Relations
    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
