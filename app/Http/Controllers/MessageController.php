<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function show(Chat $chat)
    {
        $participant = $chat->participants()->where('user_id', auth()->id())->first();

        if (!$participant) {
            abort(403); // If user is not a participant, return 403 Forbidden
        }

        return MessageResource::collection(
            $chat->messages()->with('user')->latest()->paginate(20)
        );
    }

    public function store(MessageRequest $request, Chat $chat)
    {
        $participant = $chat->participants()->where('user_id', auth()->id())->first();

        if (!$participant) {
            abort(403); // If user is not a participant, return 403 Forbidden
        }

        $message = $chat->messages()->make($request->only(['body']));

        $message->user()->associate(auth()->user()); // associate the message with the current authenticated user

        $message->save();

        if ($participant) {
            $participant->pivot->last_read = now(); // Set to the current timestamp
            $participant->pivot->save(); // Save the updated pivot record
        }

        //broadcast
        broadcast(new MessageCreated($message))->toOthers();

        return MessageResource::make($message);
    }
}
