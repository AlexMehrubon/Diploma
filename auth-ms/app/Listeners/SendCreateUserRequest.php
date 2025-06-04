<?php

namespace App\Listeners;

use App\DTO\UserDTO;
use App\Events\UserCreated;
use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class SendCreateUserRequest
{
    /**
     * @param UserMicroserviceConnector $connector
     */
    public function __construct(
        public UserMicroserviceConnector $connector
    )
    {
    }

    /**
     * @param UserCreated $event
     * @return void
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function handle(UserCreated $event): void
    {
        $dto = UserDto::fromArray($event->user->toArray());
        $this->connector->createUser($dto);
    }
}
