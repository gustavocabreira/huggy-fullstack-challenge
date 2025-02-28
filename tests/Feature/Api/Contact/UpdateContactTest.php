<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

test('it should be able to update the specified contact photo', function () {
    Storage::fake();
    $model = new Contact;
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $payload = Contact::factory()->make(['user_id' => $user->id])->toArray();
    $payload['photo'] = UploadedFile::fake()->image('avatar.png');

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure($model->getFillable());

    $this->assertDatabaseHas('contacts', [
        'id' => $response->json('id'),
        'photo' => $response->json('photo'),
    ]);

    Storage::assertExists($response->json('photo'));
});

test('it should be able to delete the old photo', function () {
    Storage::fake();
    $model = new Contact;
    $user = User::factory()->create();

    $oldPhoto = UploadedFile::fake()->image('avatar.png');
    $oldPhotoPath = $oldPhoto->storeAs('uploads', uniqid().'.'.$oldPhoto->extension());
    $contact = Contact::factory()->create(['user_id' => $user->id, 'photo' => $oldPhotoPath]);
    $payload = Contact::factory()->make(['user_id' => $user->id])->toArray();
    $payload['photo'] = UploadedFile::fake()->image('avatar.png');

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure($model->getFillable());

    $this->assertDatabaseHas('contacts', [
        'id' => $response->json('id'),
        'photo' => $response->json('photo'),
    ]);

    Storage::assertMissing($oldPhotoPath);
});
