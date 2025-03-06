<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
});

it('should be able to group by state', function () {
    $user = User::factory()->create();
    Contact::factory()->count(10)->create(['user_id' => $user->id, 'state' => 'SP']);
    Contact::factory()->count(3)->create(['user_id' => $user->id, 'state' => 'RJ']);

    $response = $this->actingAs($user)->getJson(route('api.reports.grouped-by-state'));
    $response->assertStatus(Response::HTTP_OK);

    expect($response->json('0.state'))->toBe('SP')
        ->and($response->json('0.count'))->toBe(10)
        ->and($response->json('1.state'))->toBe('RJ')
        ->and($response->json('1.count'))->toBe(3);
});

it('should be able to group by city', function () {
    $user = User::factory()->create();
    Contact::factory()->count(3)->create(['user_id' => $user->id, 'state' => 'SP', 'city' => 'São Paulo']);
    Contact::factory()->count(5)->create(['user_id' => $user->id, 'state' => 'RJ', 'city' => 'Rio de Janeiro']);
    Contact::factory()->count(8)->create(['user_id' => $user->id, 'state' => 'MG', 'city' => 'Belo Horizonte']);

    $response = $this->actingAs($user)->getJson(route('api.reports.grouped-by-city'));
    $response->assertStatus(Response::HTTP_OK);

    expect($response->json('0.city'))->toBe('Belo Horizonte')
        ->and($response->json('0.count'))->toBe(8)
        ->and($response->json('1.city'))->toBe('Rio de Janeiro')
        ->and($response->json('1.count'))->toBe(5)
        ->and($response->json('2.city'))->toBe('São Paulo')
        ->and($response->json('2.count'))->toBe(3);
});
