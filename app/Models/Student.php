<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int id
 * @property string username
 * @property string password
 * @property string full_name
 * @property int grade
 *
 */
class Student extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['username', 'password', 'full_name', 'grade'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['password' => 'hashed'];

    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class);
    }

    public function scopeByPeriod(Builder $query, int $periodId): Builder
    {
        return $query->whereHas('period', fn ($q) => $q->where('id', $periodId));
    }

    public function scopeByTeacher(Builder $query, int $teacherId): Builder
    {
        return $query->whereHas('teacher', fn($q) => $q->where('id', $teacherId));
    }
}
