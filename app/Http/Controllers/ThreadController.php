<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\ThreadResource;
use App\Models\Chat;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ThreadController extends Controller
{
    public function index()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get all chats linked to the user
        $chats = $user->chats()->get();

        return Inertia::render('Chats/Index', compact('chats'));
    }

    public function show(Chat $chat) {

        $chats = ChatResource::make($chat);

        return Inertia::render('Chats/Show', compact('chats'));
    }
}
