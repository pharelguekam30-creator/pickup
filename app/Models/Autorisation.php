<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relation many-to-many avec Role via la table pivot role_autorisation.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_autorisation');
    }

    /**
     * Optionnel : si tu veux lier directement les utilisateurs
     * à leurs autorisations via les rôles.
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Role::class,
            'id',         // clé primaire Role
            'role_id',    // clé étrangère User
            'id',         // clé locale Autorisation
            'id'          // clé locale Role
        );
    }
}
