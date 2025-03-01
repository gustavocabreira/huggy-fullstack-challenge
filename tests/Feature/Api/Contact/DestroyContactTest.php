<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;

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
