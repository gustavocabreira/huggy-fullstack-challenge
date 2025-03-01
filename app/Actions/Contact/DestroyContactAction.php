<?php

namespace App\Actions\Contact;

use App\Models\Contact;
use Illuminate\Support\Facades\Storage;

class DestroyContactAction
{
    public function execute(Contact $contact): bool
    {
        if ($contact->photo) {
            Storage::delete($contact->photo);
        }

        return $contact->delete();
    }
}
