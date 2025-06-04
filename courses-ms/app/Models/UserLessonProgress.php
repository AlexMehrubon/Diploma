<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLessonProgress extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_lesson_progress';

    /**
     * @var string[]
     */
    protected $fillable = [
      'user_id',
      'lesson_id',
      'is_completed'
    ];
}
