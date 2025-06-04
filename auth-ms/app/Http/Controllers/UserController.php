<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function index()
    {

    }

    public function store()
    {

    }

    public function show()
    {
    }

    public function update()
    {

    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json([
            'message' => 'Пользователь успешно удален'
        ], Response::HTTP_OK);
    }
}
