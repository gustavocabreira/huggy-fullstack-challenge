<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

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
