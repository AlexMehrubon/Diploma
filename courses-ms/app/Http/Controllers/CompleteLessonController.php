<?php

namespace App\Http\Controllers;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use App\Models\Lesson;
use App\Models\UserLessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class CompleteLessonController extends Controller
{
    /**
     * @param Request $request
     * @param Lesson $lesson
     * @param UserMicroserviceConnector $userConnector
     * @return JsonResponse
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function __invoke(Request $request, Lesson $lesson, UserMicroserviceConnector $userConnector): JsonResponse
    {

        $userConnector->token = $request->bearerToken();
        $user = json_decode($userConnector->getCurrent()->body());
        $completed = $request->get('is_completed', false);
        $userLessonProgress = UserLessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => $completed,
            ]
        );
        return response()->json([
            'data' => [
                'progress' => $userLessonProgress,
            ],
            'message' => "Данные обновлены"
        ]);
    }
}
