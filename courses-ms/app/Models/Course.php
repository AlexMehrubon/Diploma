<?php

namespace App\Models;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use Database\Factories\CourseFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Tag> $tags
 * @property-read int|null $tags_count
 * @method static Builder<static>|Course newModelQuery()
 * @method static Builder<static>|Course newQuery()
 * @method static Builder<static>|Course query()
 * @method static Builder<static>|Course whereCreatedAt($value)
 * @method static Builder<static>|Course whereId($value)
 * @method static Builder<static>|Course whereName($value)
 * @method static Builder<static>|Course whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'rating',
        'status_id',
        'difficulty_level_id',
        'about',
        'average_salary'
    ];


    protected $appends = [
        'progress',
    ];

    public function getProgressAttribute(): int|null
    {
        if (!request()->bearerToken())
            return null;
        $userConnector = app(UserMicroserviceConnector::class);
        $userConnector->token = request()->bearerToken();
        $lessonIds = $this->modules()
            ->with('lessons')
            ->get()
            ->pluck('lessons')
            ->flatten()
            ->pluck('id')
            ->toArray();


        if (!$lessonIds) {
            return 0;
        }

        $userResponse = $userConnector->getCurrent();
        $user = json_decode($userResponse?->body());

        if (!$user || !isset($user->id)) {
            throw new \Exception('User not found');
        }


        $completedLessonsCount = UserLessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();
        return round(($completedLessonsCount /  collect($lessonIds)->count()) * 100);
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): belongsToMany
    {
        return $this->belongsToMany(Tag::class, 'course_tag', 'course_id', 'tag_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * @return BelongsTo
     */
    public function difficultyLevel(): BelongsTo
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_level_id');
    }

    /**
     * @return HasMany
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}
