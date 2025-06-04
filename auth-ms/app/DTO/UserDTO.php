<?php

namespace App\DTO;

class UserDTO
{
    /**
     * @param int|null $id
     * @param string $email
     * @param string|null $password
     * @param string $name
     */
    public function __construct(
        public ?int $id,
        public string $email,
        public ?string $password,
        public string $name,
    )
    {
    }

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'] ?? null,
            $array['email'],
            $array['password'] ?? null,
            $array['name']
        );
    }
}
