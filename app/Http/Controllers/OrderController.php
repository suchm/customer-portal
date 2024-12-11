<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $subscriptions = $user->subscriptions()
            ->where('subscription_user.status', 1)
            ->get();

        return SubscriptionResource::collection($subscriptions);

    }
}
