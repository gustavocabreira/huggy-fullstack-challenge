<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('should be able to delete the specified contact', function () {
    $model = new Contact;
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('api.contacts.destroy', [
        'contact' => $contact->id,
    ]));

    $response
        ->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing($model->getTable(), [
        'id' => $contact->id,
    ]);
});

it('should return not found if the contact does not exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('api.contacts.destroy', [
        'contact' => -1,
    ]));

    $response
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

it('should return forbidden if the contact belongs to another user', function () {
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);
    $anotherUser = User::factory()->create();

    $response = $this->actingAs($anotherUser)->delete(route('api.contacts.destroy', [
        'contact' => $contact->id,
    ]));

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete the contact photo', function () {
    Storage::fake();
    $model = new Contact;

    $user = User::factory()->create();
    $oldPhoto = UploadedFile::fake()->image('avatar.png');
    $oldPhotoPath = $oldPhoto->storeAs('uploads', uniqid().'.'.$oldPhoto->extension());
    $contact = Contact::factory()->create(['user_id' => $user->id, 'photo' => $oldPhotoPath]);

    $response = $this->actingAs($user)->delete(route('api.contacts.destroy', [
        'contact' => $contact->id,
    ]));

    $response
        ->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing($model->getTable(), [
        'id' => $contact->id,
    ]);

    Storage::assertMissing($oldPhotoPath);
});
