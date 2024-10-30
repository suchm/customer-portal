<?php

use App\Models\Status;
use App\Models\Subscription;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {

    Subscription::factory()->create([
        'name' => 'Basic'
    ]);

    Status::create([
        'name' => 'Active',
        'code' => 'A'
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'last_name' => 'Test Last Name',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('my-account', absolute: false));

});
