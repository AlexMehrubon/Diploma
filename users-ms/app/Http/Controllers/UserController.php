<?php

namespace App\Http\Controllers;

use App\Actions\UpdateAvatarAction;
use App\DTO\UserDTO;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * @param UserService $userService
     */
    public function __construct(
        protected UserService $userService,
        protected UpdateAvatarAction $updateAvatarAction
    )
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $perPage = request('per_page', 5);
        $page = request('page', 1);
        $filters = request('filter', []);
        $query = User::query();
        foreach ($filters as $field => $value) {
            if ($value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        $users = $query->paginate($perPage, ['*'], 'page', $page);
        return UserResource::collection($users)->response();
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $dto = UserDto::fromArray($validated);
        $result = $this->userService->create($dto);
        return response()->json($result, Response::HTTP_CREATED);
    }


    public function show(string $id)
    {
        //
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);
        $this->updateAvatarAction->execute($user, $validated);
        return new UserResource($user->fresh());

    }


    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);
        return response()->json([
            'message' => 'Пользователь успешно удален'
        ], Response::HTTP_OK);
    }
}
