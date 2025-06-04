<?php

namespace App\Models;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use stdClass;

class LessonComment extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'content'
    ];

    protected $appends = [
        'user'
    ];


    public function getUserAttribute(): ?stdClass
    {
        if (!request()->bearerToken())
            return null;
        $userConnector = app(UserMicroserviceConnector::class);
        $userConnector->token = request()->bearerToken();
        return json_decode($userConnector->getUserById($this->user_id)->body());
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
