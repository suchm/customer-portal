<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {

        // Get the logged-in user
        $user = Auth::user();

        $chats = $user->chats()->get();

        $chats = ChatResource::collection($chats);

        return Inertia::render('Chats/Index', [
            'chats' => $chats,
            'currentChat' => $chats->isNotEmpty() ? $chats->first() : null
        ]);
    }

    public function create()
    {
        return Inertia::render('Chats/Create');
    }

    public function show(Chat $chat)
    {

        // Get the logged-in user
        $user = Auth::user();

        $chats = $user->chats()->get();

        // If the chat passed in the url is not found in the users chats abort
        if (!$chats->contains('id', $chat->id)) {
            abort(404);
        }

        $chats = ChatResource::collection($chats);

        return Inertia::render('Chats/Index', [
            'chats' => $chats,
            'currentChat' => $chat
        ]);
    }
}
