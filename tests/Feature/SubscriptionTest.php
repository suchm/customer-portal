<?php

use App\Models\Status;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('successfully retrieves and renders subscriptions for the authenticated user', function () {
    $user = User::factory()->create();

    // Create the status first
    $status = Status::create([
        'id' => 1,  // Ensure the ID is set to 1
        'code' => 'A',
        'name' => 'Active'
    ]);

    // Create a subscription with products
    $subscription = Subscription::factory()->hasProducts(3)->create();

    // Attach the subscription to the user with necessary pivot data
    $user->subscriptions()->attach($subscription->id, [
        'start_date' => now(),
        'end_date' => now()->addYear(),
        'status_id' => $status->id // Use the actual ID of the created status
    ]);

    // Act
    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.index'));

    // Assert
    $response->assertOk();
});


test('caches subscriptions for the authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Mock the Cache to verify it is called
    Cache::shouldReceive('remember')
        ->once()
        ->with("subscriptions-user-{$user->id}", 3600, Closure::class)
        ->andReturn(collect());

    $this->get(route('subscriptions.index'));
});

test('redirects unauthenticated user to login', function () {
    $response = $this->get(route('subscriptions.index'));

    $response->assertRedirect(route('login'));
});

test('returns empty subscriptions if user has no subscriptions', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('subscriptions.index'));

    $response->assertInertia(fn ($page) =>
    $page->has('subscriptions', 0)
    );
});
