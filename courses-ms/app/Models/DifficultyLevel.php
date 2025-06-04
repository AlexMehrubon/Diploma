<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DifficultyLevel extends Model
{
    use HasFactory;

    protected $table = 'difficulty_levels';

    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function courses():HasMany
    {
       return $this->hasMany(Course::class, 'difficulty_level_id');
    }
}
