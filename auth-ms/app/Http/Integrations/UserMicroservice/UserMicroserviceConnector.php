<?php

namespace App\Http\Integrations\UserMicroservice;

use App\DTO\UserDTO;
use App\Http\Integrations\UserMicroservice\Requests\CreateUserRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

class UserMicroserviceConnector extends Connector
{
    use AcceptsJson, HasTimeout,AlwaysThrowOnErrors;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'http://users:8000/api/v1';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @param UserDTO $user
     * @return Response
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function createUser(UserDTO $user): Response
    {
        return $this->send(new CreateUserRequest($user));
    }
}
