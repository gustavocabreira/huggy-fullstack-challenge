<?php

use App\Actions\User\SendWelcomeNotificationAction;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

test('it should send the welcome notification', function () {
    Notification::fake();

    $user = User::factory()->create();

    $action = new SendWelcomeNotificationAction;

    $action->execute(
        userId: $user->id
    );

    expect($user->fresh()->notified_at)->not()->toBeNull();

    Notification::assertSentTo($user, WelcomeNotification::class, function ($notification) use ($user) {
        return $notification->userName === $user->name
            && $notification->userEmail === $user->email;
    });
});
