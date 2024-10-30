<?php

use App\Events\MessageCreated;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\actingAs;

test('messages are retrieved successfully for a chat', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    // Create messages for the chat
    Message::factory(30)->create([
        'chat_id' => $chat->id,
        'user_id' => $user->id,
    ]);

    Participant::create([
        'chat_id' => $chat->id,
        'user_id' => $user->id,
        'last_read' => now()->subDays(rand(0, 30)), // Random last read timestamp
    ]);

    // Act as the user and request the messages for the chat
    $response = $this
        ->actingAs($user)
        ->getJson(route('chats.messages.show', ['chat' => $chat->slug]));

    // Check for successful response
    $response->assertOk();

    // Check if the response has the paginated data
    $response->assertJsonStructure([
        'data' => [
            '*' => [  // '*' indicates that there can be multiple items in the array
                'id',
                'body',
                'user',
                'created_at'
            ],
        ],
        'links',
        'meta',
    ]);
});

test('messages are paginated', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    // Create more than 20 messages for pagination testing
    Message::factory(30)->create([
        'chat_id' => $chat->id,
        'user_id' => $user->id,
    ]);

    Participant::create([
        'chat_id' => $chat->id,
        'user_id' => $user->id,
        'last_read' => now()->subDays(rand(0, 30)), // Random last read timestamp
    ]);

    // Act as the user and request the first page of messages
    $response = $this
        ->actingAs($user)
        ->getJson(route('chats.messages.show', [
            'chat' => $chat->slug,
            'page' => 2,
        ]));

    // Check that 10 messages are returned if page = 2
    $response->assertJsonCount(10, 'data');

    // Check if the response contains pagination meta
    $response->assertJsonStructure([
        'meta' => [
            'current_page',
            'last_page',
            'per_page',
            'total',
        ]
    ]);
});

test('no messages for chat returns empty data', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    Participant::create([
        'chat_id' => $chat->id,
        'user_id' => $user->id,
        'last_read' => now()->subDays(rand(0, 30)), // Random last read timestamp
    ]);

    // Act as the user and request the messages for the chat with no messages
    $response = $this
        ->actingAs($user)
        ->getJson(route('chats.messages.show', ['chat' => $chat->slug]));

    // Check that the data array is empty
    $response->assertJsonCount(0, 'data');
});

test('unauthorized user cannot access messages of a chat', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $chat = Chat::factory()->create();

    // Create a message in the chat by another user
    Message::factory()->create([
        'chat_id' => $chat->id,
        'user_id' => $otherUser->id,
    ]);

    // Act as a user who is not part of the chat
    $response = $this
        ->actingAs($user)
        ->getJson(route('chats.messages.show', ['chat' => $chat->slug]));

    // Check for 403 Forbidden status
    $response->assertStatus(403);
});

test('user can create a message in a chat', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->hasParticipants(1)->create();  // Generate 1 participant using the factory

    // Attach the existing user to the chat as a participant
    $chat->participants()->attach($user);

    $response = actingAs($user)
        ->post(route('chats.messages.store', ['chat' => $chat->slug]), [
            'body' => 'Hello, this is a test message'
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id', 'body', 'created_at', 'updated_at'
        ]);

    expect(Message::where('body', 'Hello, this is a test message')->exists())->toBeTrue();
});

test('a body is required to create a message', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->hasParticipants(1)->create();

    // Attach the existing user to the chat as a participant
    $chat->participants()->attach($user);

    actingAs($user)
        ->post(route('chats.messages.store', ['chat' => $chat->slug]), [])
        ->assertInvalid(['body' => 'required']);
});


test('the last_read timestamp is updated after message creation', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->hasParticipants(1)->create();  // Generate 1 participant using the factory

    // Attach the existing user to the chat as a participant
    $chat->participants()->attach($user);

    actingAs($user)
        ->post(route('chats.messages.store', ['chat' => $chat->slug]), [
            'body' => 'Test message'
        ]);

    // Retrieve the participant record and check last_read timestamp
    $participant = $chat->participants()->where('user_id', $user->id)->first();

    $this->assertNotNull($participant); // Ensure the participant exists
    $this->assertTrue(
        Carbon::parse($participant->pivot->last_read)->between(
            Carbon::now()->subSecond(),
            Carbon::now()
        ),
        'The last_read timestamp was not updated correctly.'
    ); // Assert the last_read timestamp was updated within the last second
});

test('MessageCreated event is broadcast after a message is created', function () {
    Event::fake();

    $user = User::factory()->create();
    $chat = Chat::factory()->hasParticipants(1)->create();  // Generate 1 participant using the factory

    // Attach the existing user to the chat as a participant
    $chat->participants()->attach($user);

    actingAs($user)
        ->post(route('chats.messages.store', ['chat' => $chat->slug]), [
            'body' => 'Test message'
        ]);

    Event::assertDispatched(MessageCreated::class, function ($event) use ($chat) {
        return $event->getMessage()->chat_id === $chat->id;
    });
});

test('the message associates the chat and user correctly', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create();

    $message = $chat->messages()->make(['body' => 'Test message']);
    $message->user()->associate($user);
    $message->save();

    expect($message)
        ->chat_id->toBe($chat->id)
        ->user_id->toBe($user->id);
});

test('non-participants are prevented from posting messages', function () {
    $user = User::factory()->create();
    $chat = Chat::factory()->create(); // User not a participant

    actingAs($user)
        ->post(route('chats.messages.store', ['chat' => $chat->slug]), [
            'body' => 'Test message'
        ])
        ->assertStatus(403);
});

test('authentication is required to post a message', function () {
    $chat = Chat::factory()->create();

    $this->post(route('chats.messages.store', ['chat' => $chat->slug]), [
        'body' => 'Test message'
    ])->assertRedirect(route('login'));
});
