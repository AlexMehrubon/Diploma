<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
{
    /**
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $arUser = parent::toArray($request);
        $user = User::find($arUser['id']);

        return array_merge(
            [
              'id' => $user->id,
              'name' => $user->name,
              'email' => $user->email,
              'image_url' => $user->image_url,
              'created_at' => Carbon::parse($user->created_at)->setTimezone('Europe/Moscow')->format('d.m.Y'),
            ],
            [
                'roles' => $user->roles->toArray()
            ]
        );
    }
}
