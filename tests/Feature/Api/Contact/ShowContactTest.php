<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
});

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

it('should return not found if the contact does not exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('api.contacts.show', [
        'contact' => -1,
    ]));

    $response
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

it('should return not found if the contact belongs to another user', function () {
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $anotherUser = User::factory()->create();

    $response = $this->actingAs($anotherUser)->getJson(route('api.contacts.show', [
        'contact' => $contact->id,
    ]));

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});
