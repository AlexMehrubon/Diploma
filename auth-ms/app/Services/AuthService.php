<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Events\UserCreated;
use App\Models\User;
use Hash;

class AuthService
{

    /**
     * @param UserDTO $user
     * @return User
     */
    public function createUser(UserDTO $user): User
    {
        $user =  User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($user->password),
        ]);
        UserCreated::dispatch($user);
        return $user;
    }
}
