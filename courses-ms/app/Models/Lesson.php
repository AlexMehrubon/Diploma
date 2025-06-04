<?php

namespace App\Models;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    /**
     * @var string
     */
    protected $table = 'lessons';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'content',
        'order',
        'module_id'
    ];

    protected $appends = [
        'is_completed',
        'comments_count'
    ];


    /**
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LessonComment::class);
    }

    public function getIsCompletedAttribute(): bool
    {
        if (!request()->bearerToken())
            return false;
        $userConnector = app(UserMicroserviceConnector::class);
        $userConnector->token = request()->bearerToken();
        $user = json_decode($userConnector->getCurrent()->body());
        if (!$user)
            return false;
        return (bool)UserLessonProgress::where('user_id', $user->id)->where('lesson_id', $this->id)->first()?->is_completed;
    }

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }
}
