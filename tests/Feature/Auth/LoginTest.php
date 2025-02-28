<?php

use Laravel\Socialite\Facades\Socialite;

it('should redirect to Huggy OAuth2 authorization page', function () {
    $response = $this->get(route('auth.redirect', ['provider' => 'huggy']));
    $response->assertRedirect();

    $redirectUrl = $response->headers->get('Location');

    parse_str(parse_url($redirectUrl, PHP_URL_QUERY), $queryParams);

    expect($queryParams)
        ->toHaveKey('client_id')
        ->toHaveKey('redirect_uri')
        ->toHaveKey('response_type')
        ->toHaveKey('scope')
        ->and($queryParams['client_id'])
        ->toBe(config('services.huggy.client_id'))
        ->and($queryParams['redirect_uri'])
        ->toBe(config('services.huggy.redirect'))
        ->and($queryParams['response_type'])
        ->toBe('code')
        ->and($queryParams['scope'])
        ->toBe('install_app read_agent_profile');
});

it('logs in a user with Huggy OAuth2', function () {
    $huggyUser = (object) [
        'id' => '987654321',
        'name' => 'Huggy User',
        'email' => 'huggyuser@example.com',
        'user' => [
            'photo' => 'https://example.com/avatar.png',
        ],
        'token' => '1234567890',
        'refreshToken' => '0987654321',
        'expiresIn' => 3600,
    ];

    Socialite::shouldReceive('driver->stateless->user')
        ->once()
        ->andReturn($huggyUser);

    $response = $this->get(route('auth.callback', ['provider' => 'huggy']));
    $response
        ->assertOk()
        ->assertJsonStructure([
            'access_token',
        ]);

    $this->assertDatabaseHas('users', [
        'huggy_id' => '987654321',
        'email' => 'huggyuser@example.com',
    ]);
});
