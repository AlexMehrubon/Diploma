<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{

    /**
     * @param AuthService $authService
     */
    public function __construct(
        public AuthService $authService
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (!auth()->attempt($request->only('email', 'password')))
            return response()->json(['message' => 'Unauthorized'], 401);
        $user = auth()->user();
        $token = $user->createToken('Laravel Passport Token')->accessToken;
        return response()->json(['token' => $token]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = $this->authService->createUser(UserDto::fromArray($request->validated()));
            DB::commit();
            return response()->json($user);
        }catch (Throwable $exception){
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
