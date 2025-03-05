<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $event, public array $payload) {}

    public function handle(): void
    {
        Log::info(Http::post(config('services.webhook.url'), [
            'event' => $this->event,
            'payload' => $this->payload,
        ]));
    }
}
