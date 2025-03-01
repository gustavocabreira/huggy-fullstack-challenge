<?php

namespace App\Http\Controllers\Api;

use App\Actions\Contact\CreateContactAction;
use App\Actions\Contact\DestroyContactAction;
use App\Actions\Contact\UpdateContactAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\IndexContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function index(IndexContactRequest $request): JsonResponse
    {
        $contacts = Contact::query()->paginate($request->input('per_page', 10));

        return ContactResource::collection($contacts)->response();
    }

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
        Gate::authorize('update', $contact);

        $contact = $action->execute($contact, $request);

        return response()->json(new ContactResource($contact), Response::HTTP_OK);
    }

    public function destroy(Contact $contact, DestroyContactAction $action): JsonResponse
    {
        Gate::authorize('delete', $contact);

        $action->execute($contact);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
