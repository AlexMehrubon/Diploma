<?php

namespace App\DTO;


class UserDTO
{
    public ?int $id = null;
    public string $name;
    public string $email;
    public ?string $imageUrl;

    /**
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        $dto = new self();
        $dto->id = $array['id'] ?? null;
        $dto->name = $array["name"];
        $dto->email = $array["email"];
        $dto->imageUrl = $array["imageUrl"] ?? null;
        return $dto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
       return [
           'id' => $this->id,
           'name' => $this->name,
           'email' => $this->email,
           'image_url' => $this->imageUrl,
       ];
    }


}
