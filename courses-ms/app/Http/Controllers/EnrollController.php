<?php

namespace App\Http\Controllers;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use App\Models\Course;
use App\Models\CourseUser;
use Exception;
use Illuminate\Http\Request;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;


class EnrollController extends Controller
{

    /**
     * @param UserMicroserviceConnector $connector
     */
    public function __construct(
        protected UserMicroserviceConnector $connector
    )
    {
    }

    /**
     * Handle the incoming request.
     * @throws Exception
     */
    public function enroll(Request $request, Course $course): CourseUser
    {
        $this->connector->token = $request->bearerToken();
        $userId = json_decode($this->connector->getCurrent()->body())->id;
        if (!$userId)
            throw new Exception('Пользователь не найден', 404);

        $exists = CourseUser::where('course_id', $course->id)->where('user_id', $userId)->exists();
        if ($exists)
            throw new Exception('Вы уже записаны на этот курс', 422);

        return CourseUser::create([
                'user_id' => $userId,
                'course_id' => $course->id,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function enrollStatus(Request $request, Course $course): array
    {
        $this->connector->token = $request->bearerToken();
        $userId = json_decode($this->connector->getCurrent()->body())->id;
        if (!$userId)
            throw new Exception('Пользователь не найден', 404);

        $courseUser = CourseUser::where('course_id', $course->id)->where('user_id', $userId)->exists();

        return [
            'is_enrolled' => $courseUser,
            "progress" => $course->progress,
            "module_progress" => [
            ]
        ];
    }
}
