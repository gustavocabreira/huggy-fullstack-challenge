<?php

namespace App\Http\Controllers\Api;

use App\Actions\Contact\CreateContactAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function store(CreateContactRequest $request, CreateContactAction $action): JsonResponse
    {
        $contact = $action->execute($request);

        return response()->json(new ContactResource($contact), Response::HTTP_CREATED);
    }
}
