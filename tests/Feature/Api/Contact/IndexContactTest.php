<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    config([
        'scout.driver' => 'database',
    ]);
    Queue::fake();
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

it('should return the contacts ordered by name descending', function () {
    $model = new Contact;
    $user = User::factory()->create();

    Contact::factory()->count(5)->create(['user_id' => $user->id]);
    Contact::factory()->create(['user_id' => $user->id, 'name' => 'Aaa']);

    $response = $this->actingAs($user)->getJson(route('api.contacts.index', [
        'order_by' => 'name',
        'direction' => 'desc',
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

    expect($response->json('data.5.name'))->toBe('Aaa');
});

it('should return the contacts in the second page', function () {
    $model = new Contact;
    $user = User::factory()->create();

    Contact::factory()->count(20)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson(route('api.contacts.index', [
        'page' => 2,
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

    expect(count($response->json('data')))->toBe(10)
        ->and($response->json('meta.current_page'))->toBe(2)
        ->and($response->json('meta.total'))->toBe(20);
});

it('should be able to filter the contacts by name', function () {
    $model = new Contact;
    $user = User::factory()->create();

    Contact::factory()->count(10)->create(['user_id' => $user->id]);
    Contact::factory()->create(['user_id' => $user->id, 'name' => 'Aaa']);
    $response = $this->actingAs($user)->getJson(route('api.contacts.index', [
        'query' => 'Aaa',
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

    expect(count($response->json('data')))->toBe(1)
        ->and($response->json('meta.current_page'))->toBe(1)
        ->and($response->json('meta.total'))->toBe(1);
});

it('should be able to filter the contacts by email', function () {
    $model = new Contact;
    $user = User::factory()->create();

    Contact::factory()->count(10)->create(['user_id' => $user->id]);
    Contact::factory()->create(['user_id' => $user->id, 'email' => 'aaa@example.com']);
    $response = $this->actingAs($user)->getJson(route('api.contacts.index', [
        'query' => 'aaa@example.com',
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

    expect(count($response->json('data')))->toBe(1)
        ->and($response->json('meta.current_page'))->toBe(1)
        ->and($response->json('meta.total'))->toBe(1);
});
