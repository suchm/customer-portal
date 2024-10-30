<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Find the subscription with the name 'Basic'
        $basicSubscription = Subscription::where('name', 'Basic')->firstOrFail();
        $activeStatus = Status::where('name', 'Active')->firstOrFail();

        $user->subscriptions()->attach($basicSubscription->id, [
            'status_id' => $activeStatus->id,
            'start_date' => now(),
            'end_date' => now()->addYear(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('my-account', absolute: false));
    }
}
