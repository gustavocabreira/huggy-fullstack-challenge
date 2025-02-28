<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class UpdateContactAction
{
    public function execute(Contact $contact, $request): Contact
    {
        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            $contact->photo = $request->file('photo')->storeAs('uploads', uniqid().'.'.$request->file('photo')->extension());
            $payload['photo'] = $contact->photo;
        }

        $contact->query()->update($payload);

        return $contact;
    }
}
