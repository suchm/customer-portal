<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chats = Chat::factory(10)->create();

        foreach ( $chats as $key => $chat ) {

            // Get 2 random users
            $users = User::inRandomOrder()->take(2)->get();

            foreach ($users as $user) {
                // Seed participants table
                Participant::create([
                    'user_id' => $user->id,
                    'chat_id' => $chat->id,
                    'last_read' => now()->subDays(rand(0, 30)), // Random last read timestamp
                ]);
            }

            Message::factory(200)
                ->sequence(function ($sequence) use ($key, $users) {
                    return [
                        'body' => 'Message '. ($sequence->index + ($key*200)),
                        'created_at' => now()->subYear()->addHours($sequence->index),
                        // Get current time, minus a year and add an extra hour on each sequence
                        'user_id' => $sequence->index % 2 === 0 ? $users[0]->id : $users[1]->id, // Alternate between user_id
                    ];
                })
                ->create([
                    'chat_id' => $chat->id,
                ]);
        }
    }
}
