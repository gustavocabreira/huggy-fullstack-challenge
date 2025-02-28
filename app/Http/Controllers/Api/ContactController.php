<?php

namespace App\Http\Controllers\Api;

use App\Actions\Contact\CreateContactAction;
use App\Actions\Contact\UpdateContactAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function store(CreateContactRequest $request, CreateContactAction $action): JsonResponse
    {
        $contact = $action->execute($request);

        return response()->json(new ContactResource($contact), Response::HTTP_CREATED);
    }

    public function show(Contact $contact): JsonResponse
    {
        Gate::authorize('view', $contact);

        return response()->json(new ContactResource($contact), Response::HTTP_OK);
    }

    public function update(Contact $contact, UpdateContactRequest $request, UpdateContactAction $action): JsonResponse
    {
        $contact = $action->execute($contact, $request);

        return response()->json(new ContactResource($contact), Response::HTTP_OK);
    }
}
