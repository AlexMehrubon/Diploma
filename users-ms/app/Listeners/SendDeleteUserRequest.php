<?php

namespace App\Listeners;

use App\Events\UserDeleted;
use App\Http\Integrations\AuthMicroservice\AuthMicroserviceConnector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mockery\Exception;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class SendDeleteUserRequest
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public AuthMicroserviceConnector $connector
    )
    {
        //
    }

    /**
     * @param UserDeleted $event
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function handle(UserDeleted $event): void
    {
        $token = request()->bearerToken();
        if (!$token)
            throw new Exception("Token not provided");

        $this->connector->token = request()->bearerToken();
        $this->connector->deleteUser($event->user->id);
    }
}
