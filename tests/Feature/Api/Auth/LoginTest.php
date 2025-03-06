<?php

use App\Models\User;

it('should be retrieve the access token to login', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $response = $this->postJson(route('api.auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['access_token']);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => $user->email,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$response->json('access_token'),
    ])->getJson(route('api.me'));

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['id', 'name', 'email']);
});
