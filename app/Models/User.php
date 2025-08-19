<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'expire_at' => 'datetime',
    ];

    // Add the $userType property
    protected $appends = ['userType'];

    public function getUserTypeAttribute()
    {
        $parts = explode('@', $this->email);
        return isset($parts[1]) ? $parts[1] : null;
    }

    public function setUserTypeAttribute($value)
    {
        // You can customize this setter if needed
        $this->attributes['user_type'] = $value;
    }
}
