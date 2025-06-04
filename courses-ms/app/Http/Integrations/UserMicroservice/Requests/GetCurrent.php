<?php

namespace App\Http\Integrations\UserMicroservice\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCurrent extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/users/current';
    }
}
