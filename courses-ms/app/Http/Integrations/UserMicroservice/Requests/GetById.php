<?php

namespace App\Http\Integrations\UserMicroservice\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetById extends Request
{

    public function __construct(
        protected int $userId,
    )
    {
    }

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/users/?filter[id]=' . $this->userId;
    }
}
