<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'course_user';

    /**
     * @var string[]
     */
    protected $fillable = [
      'course_id',
      'user_id',
      'progress'
    ];

    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
