<?php

namespace App\Models;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    /**
     * @var string
     */
    protected $table = 'modules';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'order',
        'course_id'
    ];


    protected $appends = [
        'progress'
    ];

    public function getProgressAttribute(): ?array
    {
        $userConnector = app(UserMicroserviceConnector::class);
        $token = request()->bearerToken();
        if (!$token)
            return null;
        $userConnector->token = $token;

        if (request()->attributes->get('user_id'))
            $user = request()->attributes->get('user_id');
        else {
            $user = json_decode($userConnector->getCurrent()->body());
            request()->attributes->add(['user_id' => $user]);
        }
        
        $lessonIds = $this->lessons()->pluck('id')->toArray();

        $completedLessonsCount = UserLessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();

        return [
            'value' => collect($lessonIds)->count() ? round(($completedLessonsCount / collect($lessonIds)->count()) * 100) : 0,
            'total_lessons' => collect($lessonIds)->count(),
            'completed_lessons' => $completedLessonsCount,
        ];
    }

    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'module_id');
    }
}
