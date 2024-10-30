<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;


class SubscriptionController extends Controller
{
    public function index() : Response {

        $user = auth()->user();

        $subscriptions =  Cache::remember('subscriptions-user-' . $user->id, 3600, function () use ($user) {
            $subscriptions = $user->subscriptions()
                ->with('products') // Eager load products and status
                ->get();
            return SubscriptionResource::collection($subscriptions);
        });

        return Inertia::render('Subscriptions/Index', compact('subscriptions'));
    }
}
