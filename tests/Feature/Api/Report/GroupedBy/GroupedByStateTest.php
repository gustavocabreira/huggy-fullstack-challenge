<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

it('should be able to group by state', function() {
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
