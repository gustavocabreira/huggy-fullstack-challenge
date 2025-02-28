<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function __construct(private readonly Contact $model) {}

    public function store(CreateContactRequest $request): JsonResponse
    {
        $contact = $this->model->query()->create($request->validated());

        return response()->json(new ContactResource($contact), Response::HTTP_CREATED);
    }
}
