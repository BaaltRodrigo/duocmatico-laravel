<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // Change the default ward to web to use roles
    protected $guard_name = 'web';

    protected $fillable = [
        'id', // Internal ID

        // Following fields are provided by firebase        
        'name',
        'email',
        'firebase_uid',
    ];

    public function calendars(): HasMany
    {
        return $this->hasMany(Calendar::class);
    }
}
