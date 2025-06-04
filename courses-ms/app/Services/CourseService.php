<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class CourseService extends Service
{
    /**
     * @var array|string[]
     */
    public array $relations = [
        'tags' => Tag::class,
        'modules' => Module::class,
        'modules.lessons' => Lesson::class,
    ];

    public function __construct()
    {
        parent::__construct(Course::class);
    }


    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $result = parent::create($data);
        if (isset($data['modules']))
            foreach ($data['modules'] as $module) {
                $module['course_id'] = $result['id'];
                Module::create($module);
            }

        return $result;
    }

}
