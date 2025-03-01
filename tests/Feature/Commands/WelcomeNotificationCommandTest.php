<?php

use App\Jobs\SendWelcomeNotificationJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('it should dispatch the welcome notification', function () {
    Queue::fake();

    User::factory()->count(10)->create([
        'created_at' => now()->subMinutes(30),
    ]);

    $this->artisan('send:welcome-notification');

    Queue::assertPushed(SendWelcomeNotificationJob::class, 10);
});
