<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

it('should be able to update the specified contact', function () {
    $model = new Contact;
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $payload = Contact::factory()->make()->toArray();

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure($model->getFillable());

    $this->assertDatabaseHas('contacts', $payload);
    $this->assertDatabaseCount($model->getTable(), 1);
});
