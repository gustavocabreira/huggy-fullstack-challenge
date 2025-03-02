<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class CreateContactAction
{
    public function execute($request): Contact
    {
        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->storeAs('uploads', uniqid().'.'.$request->file('photo')->extension(), 'public');
        }

        return Contact::query()->create($payload);
    }
}
