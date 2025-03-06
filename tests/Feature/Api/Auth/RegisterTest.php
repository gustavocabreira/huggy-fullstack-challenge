<?php

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

it('should be able to register', function () {
    $payload = User::factory()->make()->toArray();
    $payload['password'] = 'password';
    $payload['password_confirmation'] = 'password';

    $response = $this->postJson(route('api.auth.register'), $payload);

    $response
        ->assertStatus(Response::HTTP_CREATED);

    $this->assertDatabaseHas('users', [
        'id' => $response->json('id'),
        'name' => $response->json('name'),
        'email' => $response->json('email'),
    ]);

    $this->assertDatabaseCount('users', 1);
});

dataset('invalid_payload', [
    'empty name' => [
        ['name' => ''], ['name' => 'O campo nome é obrigatório.'],
    ],
    'name with more than 255 characters' => [
        ['name' => Str::repeat('a', 256)], ['name' => 'The name field must be at most 255 characters.'],
    ],
    'invalid email' => [
        ['email' => 'invalid'], ['email' => 'O campo email é obrigatório.'],
    ],
    'email with more than 255 characters' => [
        ['email' => Str::repeat('a', 256).'@email.com'], ['email' => 'The email field must be at most 255 characters.'],
    ],
    'password with less than 8 characters' => [
        ['password' => Str::repeat('a', 7)], ['password' => 'The password field must be at least 8 characters.'],
    ],
    'password with more than 255 characters' => [
        ['password' => Str::repeat('a', 256)], ['password' => 'The password field must be at most 255 characters.'],
    ],
]);

it('should return unprocessable entity if the payload is invalid', function (array $payload, array $expectedErrors) {
    $key = array_keys($expectedErrors);
    $response = $this->postJson(route('api.auth.register'), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors($key);

    $this->assertDatabaseMissing('users', $payload);
    $this->assertDatabaseCount('users', 0);
})->with('invalid_payload');
