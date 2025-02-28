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
        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->storeAs('uploads', uniqid().'.'.$request->file('photo')->extension());
        }

        $contact = $this->model->query()->create($payload);

        return response()->json(new ContactResource($contact), Response::HTTP_CREATED);
    }
}
