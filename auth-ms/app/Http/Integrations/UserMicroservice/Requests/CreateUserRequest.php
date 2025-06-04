<?php

namespace App\Http\Integrations\UserMicroservice\Requests;

use App\DTO\UserDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\HasTimeout;

class CreateUserRequest extends Request implements HasBody
{
    use HasTimeout, HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;


    /**
     * @param UserDTO $user
     */
    public function __construct(
        protected UserDTO $user,
    )
    {
        $this->body()->setJsonFlags(JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }


    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/users';
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

    /**
     * @return array
     */
    protected function defaultBody(): array
    {
        return [
            'id' => $this->user->id,
            'email' => $this->user->email,
            'name' => $this->user->name,
        ];
    }
}
