<?php

namespace App\Actions\Contact;

use App\Models\Contact;
use Illuminate\Support\Facades\Storage;

class UpdateContactAction
{
    public function execute(Contact $contact, $request): Contact
    {
        if ($request->hasFile('photo')) {
            Storage::disk('uploads')->delete($contact->photo);
            $contact->photo = $request->file('photo')->storeAs('uploads', uniqid().'.'.$request->file('photo')->extension());
        }

        $contact->query()->update($request->validated());

        return $contact;
    }
}
