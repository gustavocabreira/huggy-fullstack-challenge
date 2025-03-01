<?php

namespace App\Jobs;

use App\Actions\User\SendWelcomeNotificationAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWelcomeNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $userId)
    {
        //
    }

    public function handle(): void
    {
        (new SendWelcomeNotificationAction)->execute($this->userId);
    }
}
