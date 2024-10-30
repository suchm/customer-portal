<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function (User $user, $chatId) {

    //Pass the id and name data in the channel event
    // Add typing so we always have access to this in our user store
    return [
        'id' => $user->id,
        'name' => $user->name,
        'typing' => false,
    ];

    //Pass the id and name data in the channel event
//    return $user->only('id', 'name') + [
//        'typing' => false
//        ];
});
