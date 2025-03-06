<?php

namespace App\Observers;

use App\Jobs\SendWebhookJob;
use App\Models\Contact;

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
        SendWebhookJob::dispatch(
            userId: $contact->user_id,
            event: 'contact.created',
            payload: $contact->toArray(),
        );
    }

    public function updated(Contact $contact): void
    {
        SendWebhookJob::dispatch(
            userId: $contact->user_id,
            event: 'contact.updated',
            payload: $contact->toArray(),
        );
    }

    public function deleted(Contact $contact): void
    {
        SendWebhookJob::dispatch(
            userId: $contact->user_id,
            event: 'contact.deleted',
            payload: $contact->toArray(),
        );
    }
}
