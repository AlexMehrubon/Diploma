<?php

namespace App\Http\Integrations\AuthMicroservice\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteUser extends Request
{

    protected Method $method = Method::DELETE;

    /**
     * @param string $id
     */
    public function __construct(
        protected string $id,
    )
    {
    }

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return "/users/{$this->id}";
    }

    /**
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
