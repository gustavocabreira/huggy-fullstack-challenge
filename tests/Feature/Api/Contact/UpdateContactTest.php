<?php

use App\Jobs\SendWebhookJob;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
    Queue::fake();
});

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

    unset($payload['date_of_birth']);
    unset($payload['photoUrl']);
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

    Storage::disk('public')->assertExists($response->json('photo'));
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

    Storage::disk('public')->assertMissing($oldPhotoPath);
});

dataset('invalid_payload', dataset: [
    'empty name' => [
        ['name' => ''], ['name' => 'The name field is required.'],
    ],
    'name with more than 255 characters' => [
        ['name' => Str::repeat('a', 256)], ['name' => 'The name field must be at most 255 characters.'],
    ],
    'invalid date of birth' => [
        ['date_of_birth' => 'invalid'], ['date_of_birth' => 'The date of birth field is invalid.'],
    ],
    'date of birth is in the future' => [
        ['date_of_birth' => now()->addDay()->format('Y-m-d')], ['date_of_birth' => 'The date of birth field must be a date before today.'],
    ],
    'empty email' => [
        ['email' => ''], ['email' => 'The email field is required.'],
    ],
    'email with more than 255 characters' => [
        ['email' => Str::repeat('a', 256).'@email.com'], ['email' => 'The email field must be at most 255 characters.'],
    ],
    'invalid phone number' => [
        ['phone_number' => 'invalid'], ['phone_number' => 'The phone number field must be an integer.'],
    ],
    'phone number with more than 10 digits' => [
        ['phone_number' => (int) Str::repeat('9', 11)], ['phone_number' => 'The phone number field must be a valid phone number with 10 digits.'],
    ],
    'cellphone number with more than 11 digits' => [
        ['cellphone_number' => (int) Str::repeat('9', 12)], ['cellphone_number' => 'The cellphone number field must be a valid phone number with 11 digits.'],
    ],
    'phone number must be an integer' => [
        ['phone_number' => 'invalid'], ['phone_number' => 'The phone number field must be an integer.'],
    ],
    'cellphone number must be an integer' => [
        ['cellphone_number' => 'invalid'], ['cellphone_number' => 'The cellphone number field must be an integer.'],
    ],
    'cellphone number must starts with 9' => [
        ['cellphone_number' => '888888888'], ['cellphone_number' => 'The cellphone number field must start with 9.'],
    ],
    'address with more than 255 characters' => [
        ['address' => Str::repeat('a', 256)], ['address' => 'The address field must be at most 255 characters.'],
    ],
    'district with more than 255 characters' => [
        ['district' => Str::repeat('a', 256)], ['district' => 'The district field must be at most 255 characters.'],
    ],
    'city with more than 255 characters' => [
        ['city' => Str::repeat('a', 256)], ['city' => 'The city field must be at most 255 characters.'],
    ],
    'state with more than 2 characters' => [
        ['state' => Str::repeat('a', 256)], ['state' => 'The state field must be at most 2 characters.'],
    ],
    'zip code with more than 255 characters' => [
        ['zip_code' => Str::repeat('a', 256)], ['zip_code' => 'The zip code field must be at most 255 characters.'],
    ],
    'photo must be a file' => [
        ['photo' => 'invalid'], ['photo' => 'The photo field must be a file of type: jpg, jpeg, png.'],
    ],
    'photo must be a jpg, jpeg or png file' => [
        ['photo' => UploadedFile::fake()->image('avatar.xlsx')], ['photo' => 'The photo field must be a file of type: jpg, jpeg, png.'],
    ],
]);

it('should return unprocessable entity if the payload is invalid', function (array $payload, array $expectedErrors) {
    $key = array_keys($expectedErrors);
    $model = new Contact;
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors($key);

    $this->assertDatabaseMissing($model->getTable(), $payload);
    $this->assertDatabaseCount($model->getTable(), 1);
})->with('invalid_payload');

it('should return the email has already been taken', function () {
    $model = new Contact;

    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);
    $otherContact = Contact::factory()->create(['user_id' => $user->id]);
    $payload = Contact::factory()->make(['user_id' => $user->id, 'email' => $otherContact->email])->toArray();

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);

    expect($response->json('errors.email.0'))->toBe('O campo email já está sendo utilizado.');

    $this->assertDatabaseMissing($model->getTable(), $payload);
    $this->assertDatabaseCount($model->getTable(), 2);
});

it('should return the phone number has already been taken', function () {
    $model = new Contact;

    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);
    $otherContact = Contact::factory()->create(['user_id' => $user->id]);
    $payload = Contact::factory()->make(['user_id' => $user->id, 'phone_number' => $otherContact->phone_number])->toArray();

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['phone_number']);

    expect($response->json('errors.phone_number.0'))->toBe('O campo phone number já está sendo utilizado.');

    $this->assertDatabaseMissing($model->getTable(), $payload);
    $this->assertDatabaseCount($model->getTable(), 2);
});

it('should return the cellphone number has already been taken', function () {
    $model = new Contact;

    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);
    $otherContact = Contact::factory()->create(['user_id' => $user->id]);
    $payload = Contact::factory()->make(['user_id' => $user->id, 'cellphone_number' => $otherContact->cellphone_number])->toArray();

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['cellphone_number']);

    expect($response->json('errors.cellphone_number.0'))->toBe('O campo cellphone number já está sendo utilizado.');

    $this->assertDatabaseMissing($model->getTable(), $payload);
    $this->assertDatabaseCount($model->getTable(), 2);
});

it('should return not found if the contact does not exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => -1,
    ]));

    $response
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

it('should return not found if the contact belongs to another user', function () {
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);
    $anotherUser = User::factory()->create();

    $payload = Contact::factory()->make()->toArray();

    $response = $this->actingAs($anotherUser)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('should dispatch the contact.updated job', function () {
    $user = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $user->id]);

    $payload = Contact::factory()->make()->toArray();

    $response = $this->actingAs($user)->putJson(route('api.contacts.update', [
        'contact' => $contact->id,
    ]), $payload);

    Queue::assertPushed(SendWebhookJob::class, function ($job) use ($response) {
        return $job->event === 'contact.updated' && $job->payload['id'] === $response->json('id');
    });
});
