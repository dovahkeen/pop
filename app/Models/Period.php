<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string name
 * @property int teacher_id
 */
class Period extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function scopeByTeacher(Builder $query, int $teacherId): Builder
    {
        return $query->whereHas('teacher', fn($q) => $q->where('id', $teacherId));
    }
}
