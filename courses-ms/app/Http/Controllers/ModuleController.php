<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use App\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ModuleController extends Controller
{

    /**
     * @param ModuleService $service
     */
    public function __construct(
      protected  ModuleService $service
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $request->all();
        $courses = $this->service->all(
            order: 'id',
            perPage: $query['per_page'] ?? 0,
            page: $query['page'] ?? 1,
            filters: $query['filter'] ?? [],
        );
        return ModuleResource::collection($courses)->response();
    }

    /**
     * @param CreateCourseRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json($this->service->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param UpdateCourseRequest $request
     * @param Module $module
     * @return JsonResponse
     */
    public function update(Request $request, Module $module): JsonResponse
    {
        $validated = $request->all();
        $module->update($validated);

        $module->save();
        return response()->json($module->fresh());
    }

    /**
     * @param Module $module
     * @return JsonResponse
     */
    public function destroy(Module $module): JsonResponse
    {
        $module->delete();
        return response()->json([
            'message' => 'Модуль успешно удален'
        ], status: Response::HTTP_OK);
    }
}
