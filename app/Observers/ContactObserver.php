<?php

namespace App\Observers;

use App\Jobs\SendWebhookJob;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;

class ContactObserver
{
    public function creating(Contact $contact): void
    {
        if (auth()->user()) {
            $contact->user_id = auth()->user()->id;
        }
    }

    public function created(Contact $contact): void
    {
        SendWebhookJob::dispatch('contact.created', $contact->toArray());
    }

    public function updated(Contact $contact): void
    {
        SendWebhookJob::dispatch('contact.updated', $contact->toArray());
    }

    public function deleted(Contact $contact): void
    {
        SendWebhookJob::dispatch('contact.deleted', $contact->toArray());
    }
}
