<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CheckBoxResource;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{

    /**
     * @param CourseService $service
     */
    public function __construct(
        protected CourseService $service
    )
    {
    }

    /**
     * @param CourseRequest $request
     * @return JsonResponse
     */
    public function index(CourseRequest $request): JsonResponse
    {
        $query = $request->validated();
        $courses = $this->service->all(
            order: 'id',
            perPage: $query['per_page'] ?? 0,
            page: $query['page'] ?? 1,
            filters: $query['filter'] ?? [],
        );
        return CourseResource::collection($courses)->response();
    }


    /**
     * @param CreateCourseRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json($this->service->create($request->all()), Response::HTTP_CREATED);
    }


    public function show(Course $course)
    {
        //
    }


    /**
     * @param UpdateCourseRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $validated = $request->validated();
        $course->update($validated);

        $course->status()->associate($validated['status_id'] ?? $course['status_id']);
        $course->difficultyLevel()->associate($validated['difficulty_level_id'] ?? $course['difficulty_level_id']);

        $course->save();
        return response()->json($course->fresh());
    }

    /**
     * @param Course $course
     * @return JsonResponse
     */
    public function destroy(Course $course): JsonResponse
    {
        $course->delete();
        return response()->json([
            'message' => 'Курс успешно удален'
        ], status: Response::HTTP_OK);
    }
}
