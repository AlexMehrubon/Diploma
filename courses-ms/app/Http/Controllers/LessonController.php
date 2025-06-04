<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\Module;
use App\Services\LessonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LessonController extends Controller
{
    /**
     * @param LessonService $service
     */
    public function __construct(
        protected LessonService $service
    )
    {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->all();
        $courses = $this->service->all(
            order: 'id',
            perPage: $query['per_page'] ?? 0,
            page: $query['page'] ?? 1,
            filters: $query['filter'] ?? [],
        );
        return LessonResource::collection($courses)->response();
    }

    /**
     * @param UpdateCourseRequest $request
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function update(Request $request, Lesson $lesson): JsonResponse
    {
        $validated = $request->all();
        $lesson->update($validated);

        $lesson->save();
        return response()->json($lesson->fresh());
    }

    /**
     * @param CreateCourseRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json($this->service->create($request->all()), Response::HTTP_CREATED);
    }
}
