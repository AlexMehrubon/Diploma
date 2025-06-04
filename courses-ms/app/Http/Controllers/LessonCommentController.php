<?php

namespace App\Http\Controllers;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\LessonCommentResource;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonCommentController
{

    public function __construct(
        protected UserMicroserviceConnector $connector
    )
    {
    }

    /**
     * @param Request $request
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function index(Request $request, Lesson $lesson): JsonResponse
    {
        return LessonCommentResource::collection($lesson->comments()->paginate(5, page: $request->get('page')))->response();
    }

    public function store(CreateCommentRequest $request, Lesson $lesson): JsonResponse
    {
        $comment = $lesson->comments()->create([
            'user_id' => $request->get('user_id'),
            'content' => $request->get('content'),
        ]);


        return response()->json($comment, 201);
    }
}
