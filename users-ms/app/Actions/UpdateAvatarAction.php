<?php

namespace App\Actions;

use App\Models\User;
use Storage;
use Throwable;

class UpdateAvatarAction
{
    protected string $defaultAvatarPath = 'avatars/default.svg';

    /**
     * Если нет ключа avatar - ничего не делаем.
     * Если ключ avatar null - ставим стандартную
     * @throws Throwable
     */
    public function execute(User $user, array $data): void
    {
        if (!key_exists('avatar', $data))
            return;
        $avatar = $data['avatar'];
        if (!$avatar) {
            $this->setDefaultAvatar($user);
            return;
        }
        $avatarPath = Storage::disk('public')->putFile("avatars/{$user->id}", $avatar);
        $oldAvatarPath = $user->image_url;

        $user->updateOrFail(['image_url' => Storage::disk('public')->url($avatarPath)]);

        if ($oldAvatarPath != $this->defaultAvatarPath)
            Storage::disk('public')->delete($oldAvatarPath);

    }

    private function setDefaultAvatar(User $user): void
    {
        $oldAvatarPath = $user->image_url;
        $user->update(['image_url' => $this->defaultAvatarPath]);
        if ($oldAvatarPath != $this->defaultAvatarPath)
            Storage::disk('public')->delete($oldAvatarPath);

    }
}
