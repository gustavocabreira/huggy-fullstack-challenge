<?php

namespace App\Actions\User;

use App\Models\User;
use App\Notifications\WelcomeNotification;

class SendWelcomeNotificationAction
{
    public function execute(int $userId): void
    {
        $user = User::query()->find($userId);

        if (! $user) {
            return;
        }

        $user->notify(new WelcomeNotification(
            userName: $user->name,
            userEmail: $user->email,
        ));
    }
}
