<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int id
 * @property string username
 * @property string password,
 * @property string full_name
 * @property string email
 */
class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['username', 'password', 'full_name', 'email'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }
}
