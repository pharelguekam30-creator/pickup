<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relation many-to-many avec Autorisation
    public function autorisations()
    {
        return $this->belongsToMany(Autorisation::class, 'role_autorisation');
    }

    // Relation one-to-many avec User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
