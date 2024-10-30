<?php

use App\Models\Chat;
use App\Models\Participant;
use App\Models\User;

test('chats page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('chats.index'));

    $response->assertOk();
});

test('chats page displays when user has no chats', function () {
    $user = User::factory()->create();

    // Act as the user with no associated chats
    $response = $this
        ->actingAs($user)
        ->get(route('chats.index'));

    // Check that the page loads successfully (could be 200 or another status depending on the design)
    $response->assertOk();
});

test('chats page displays when passing chat slug', function () {

    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    Participant::create([
        'user_id' => $user->id,
        'chat_id' => $chat->id,
        'last_read' => now()->subDays(rand(0, 30)), // Random last read timestamp
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('chats.show', ['chat' => $chat->slug]));

    $response->assertOk();
});

test('return 404 when chat slug is not linked to user', function () {

    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('chats.show', ['chat' => $chat->slug]));

    $response->assertStatus(404);
});

test('return 404 for invalid chat slug format', function () {
    $user = User::factory()->create();

    // Act as the user and request a chat page with an invalid slug format
    $response = $this
        ->actingAs($user)
        ->get(route('chats.show', ['chat' => 'invalid-slug']));

    // Check for 404 status
    $response->assertStatus(404);
});

test('unauthenticated user for main chat route is redirected to login page', function () {
    $chat = Chat::factory()->create();

    // Attempt to access the chat page without logging in
    $response = $this->get(route('chats.index'));

    // Check for redirect to login
    $response->assertRedirect('/login');
});

test('unauthenticated user for chat route with slug is redirected to login page', function () {
    $chat = Chat::factory()->create();

    // Attempt to access the chat page without logging in
    $response = $this->get(route('chats.show', ['chat' => $chat->slug]));

    // Check for redirect to login
    $response->assertRedirect('/login');
});

