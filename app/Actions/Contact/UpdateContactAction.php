<?php

namespace App\Actions\Contact;

use App\Models\Contact;
use Illuminate\Support\Facades\Storage;

class UpdateContactAction
{
    public function execute(Contact $contact, $request): Contact
    {
        $payload = $request->validated();

        if ($request->hasFile('photo')) {

            if ($contact->photo) {
                Storage::delete($contact->photo);
            }

            $contact->photo = $request->file('photo')->storeAs('uploads', uniqid().'.'.$request->file('photo')->extension(), 'public');
            $payload['photo'] = $contact->photo;
        }

        $contact->query()->update($payload);

        return $contact;
    }
}
