<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Status;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            0 => [
                'name' => 'Basic',
                'description' => 'A free tier to get you started'
            ],
            1 => [
                'name' => 'Pro',
                'description' => 'Includes all the features of basic with enhanced support'
            ],
            2 => [
                'name' => 'Enterprise',
                'description' => 'Unlimited access to all features basic with 24/7 support contact'
            ]
        ];

        $products = Collect(Product::all()->modelKeys());
        $basic = $products->take(4);
        $pro = $products->take(8);
        $enterprise = $products->take(11);
        $addSubStatus = true;

        $status = Status::where('code', 'A')->first();

        foreach ($subscriptions as $subscription) {
            $subType = strtolower($subscription['name']);
            $sub = Subscription::factory([
                'name' => $subscription['name'],
                'description' => $subscription['description']
            ])->create();

            //if ( $addSubStatus ) {
            //    $sub->users()->attach($user->id, [
            //        'status_id' => $status->id,
            //        'start_date' => now(),
            //        'end_date' => now()->addYear(),
            //    ]);
            //    $addSubStatus = false;
            //}

            // add the relevant products depending on subtype
            // i.e either $basic, $pro or $enterprise
            // Use attach instead of sync for individual items
            $sub->products()->sync(${$subType});
        }

        $users = User::all();
        $subscriptionIds = Subscription::pluck('id');  // Get the subscription IDs

        foreach ($users as $key => $user) {
            // Cycle between subscription IDs
            $subscriptionId = $subscriptionIds[$key % 3];

            $user->subscriptions()->attach($subscriptionId, [
                'status_id' => $status->id,
                'start_date' => now(),
                'end_date' => now()->addYear(),
            ]);
        }

    }
}
