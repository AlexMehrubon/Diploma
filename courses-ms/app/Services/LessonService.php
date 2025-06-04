<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;

class LessonService extends Service
{
    /**
     * @var array|string[]
     */
    public array $relations = [
        'module' => Module::class,
        'comments' => LessonComment::class,
    ];

    public function __construct()
    {
        parent::__construct(Lesson::class);
    }



    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return parent::create($data);
    }


}
