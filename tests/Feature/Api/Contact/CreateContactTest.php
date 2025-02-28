<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

it('should be able to create a contact', function () {
    $model = new Contact;
    $user = User::factory()->create();

    $payload = Contact::factory()->make()->toArray();

    $response = $this->actingAs($user)->postJson(route('api.contacts.store'), $payload);

    $response
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure($model->getFillable());

    $this->assertDatabaseHas('contacts', [
        'id' => $response->json('id'),
        'name' => $response->json('name'),
        'email' => $response->json('email'),
        'phone_number' => $response->json('phone_number'),
        'cellphone_number' => $response->json('cellphone_number'),
        'address' => $response->json('address'),
        'district' => $response->json('district'),
        'city' => $response->json('city'),
        'state' => $response->json('state'),
        'zip_code' => $response->json('zip_code'),
        'photo' => $response->json('photo'),
    ]);
});
