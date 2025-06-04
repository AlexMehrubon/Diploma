<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Enums\RoleEnum;
use App\Events\UserDeleted;
use App\Models\Role;
use App\Models\User;
use Storage;

class UserService
{
    /**
     * @param UserDTO $dto
     * @return User
     */
    public function create(UserDTO $dto): User
    {
        $dto->imageUrl = $this->getDefaultImageUrl();
        $user = User::create($dto->toArray());
        $userRoleId = Role::where("code", RoleEnum::USER->value)->first()->id;
        $user->roles()->attach($userRoleId);
        return $user;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        $user->delete();
        UserDeleted::dispatch($user);
        return true;
    }

    /**
     * @return string
     */
    public function getDefaultImageUrl(): string
    {
        return Storage::disk("public")->url("avatars/default.png");
    }
}
