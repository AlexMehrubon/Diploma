<?php

namespace App\Http\Controllers;

use App\Http\Integrations\AuthMicroservice\AuthMicroserviceConnector;
use Arr;
use Illuminate\Http\JsonResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class CurrentUserController
{

    /**
     * @param AuthMicroserviceConnector $connector
     */
    public function __construct(
       protected AuthMicroserviceConnector $connector
    )
    {
    }

    /**
     * @return JsonResponse
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function __invoke(): JsonResponse
    {
        $this->connector->token = request()->bearerToken();
        $user = $this->connector->getUserByToken();
        $data = array_merge($user->toArray(), [
            'roles' => $user->roles,
        ]);
        return response()->json($data, 200);
    }
}
