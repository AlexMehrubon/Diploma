<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;

class ModuleService extends Service
{

    public array $relations = [
        'course' => Course::class,
        'lessons' => Lesson::class,
    ];

    public function __construct()
    {
        parent::__construct(Module::class);
    }
}
