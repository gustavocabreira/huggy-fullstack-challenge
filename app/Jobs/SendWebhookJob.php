<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendWebhookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $userId,
        public string $event,
        public array $payload
    ) {}

    public function handle(): void
    {
        $log = WebhookLog::query()->create([
            'user_id' => $this->userId,
            'to' => config('services.webhook.url'),
            'event' => $this->event,
            'payload' => $this->payload,
            'response' => '',
            'status' => 'processing',
        ]);

        try {
            $response = Http::post(config('services.webhook.url'), [
                'event' => $this->event,
                'payload' => $this->payload,
            ]);

            if ($response->successful()) {
                $log->update([
                    'response' => $response->json(),
                    'status' => 'success',
                ]);
            } else {
                $log->update([
                    'response' => $response->json(),
                    'status' => 'failed',
                ]);
            }
        } catch (\Exception $e) {
            $log->update([
                'response' => $e->getMessage(),
                'status' => 'failed',
            ]);
        }
    }
}
