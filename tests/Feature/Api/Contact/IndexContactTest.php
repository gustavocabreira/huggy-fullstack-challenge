<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function () {
    config([
        'scout.driver' => 'database',
    ]);
});

it('should be able to list the contacts', function () {
    $model = new Contact;
    $user = User::factory()->create();

    Contact::factory()->count(10)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson(route('api.contacts.index'));

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                '*' => $model->getFillable(),
            ],
            'meta',
            'links',
        ]);

    expect(count($response->json('data')))->toBe(10)
        ->and($response->json('meta.current_page'))->toBe(1)
        ->and($response->json('meta.total'))->toBe(10);
});

it('should return the contacts ordered by name', function () {
    $model = new Contact;
    $user = User::factory()->create();

    Contact::factory()->count(10)->create(['user_id' => $user->id]);
    Contact::factory()->create(['user_id' => $user->id, 'name' => 'Aaa']);
    $response = $this->actingAs($user)->getJson(route('api.contacts.index', [
        'order_by' => 'name',
    ]));

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                '*' => $model->getFillable(),
            ],
            'meta',
            'links',
        ]);

    expect($response->json('data.0.name'))->toBe('Aaa');
});
