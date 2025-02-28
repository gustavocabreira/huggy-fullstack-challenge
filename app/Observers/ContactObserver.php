<?php

namespace App\Observers;

use App\Models\Contact;

class ContactObserver
{
    public function creating(Contact $contact): void
    {
        if (auth()->user()) {
            $contact->user_id = auth()->user()->id;
        }
    }
}
