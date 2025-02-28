<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

it('should be able to find the specified contact', function () {
    $model = new Contact;
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson(route('api.contacts.show', [
        'contact' => $contact->id,
    ]));

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure($model->getFillable());

    expect($response->json('id'))->toBe($contact->id);
});
