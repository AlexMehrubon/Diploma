<?php

namespace App\Http\Integrations\AuthMicroservice;

use App\Http\Integrations\AuthMicroservice\Requests\DeleteUser;
use App\Http\Integrations\AuthMicroservice\Requests\GetUserByToken;
use App\Models\User;
use Exception;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

class AuthMicroserviceConnector extends Connector
{
    use AcceptsJson, HasTimeout,AlwaysThrowOnErrors;
    public string $token;

    public function __construct() {}

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'http://auth:8000/api/v1';
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
     * @throws Exception
     */
    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }

    /**
     * @return User
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function getUserByToken(): User
    {
        $id =  json_decode($this->send(new GetUserByToken())->body())->id;
        return User::find($id);
    }

    /**
     * @param string $id
     * @return bool
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function deleteUser(string $id): bool
    {
        return $this->send(new DeleteUser($id))->body();
    }
}
