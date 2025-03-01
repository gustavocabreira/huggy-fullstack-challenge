<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeNotificationJob;
use App\Models\User;
use Illuminate\Console\Command;

class SendWelcomeNotificationCommand extends Command
{
    protected $signature = 'send:welcome-notification';

    protected $description = 'Send welcome notification to users 30 minutes after they created their account';

    public function handle()
    {
        User::query()
            ->where('created_at', '<=', now()->subMinutes(30))
            ->whereNull('notified_at')
            ->chunk(100, function ($users) {
                $users->each(function ($user) {
                    SendwelcomeNotificationJob::dispatch(
                        userId: $user->id,
                    );
                });
            });
    }
}
