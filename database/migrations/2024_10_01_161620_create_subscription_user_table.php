<?php

use App\Models\Status;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Subscription::class)->constrained();
            $table->foreignIdFor(Status::class)->default(1)->constrained();
            $table->timestamps();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_user');
    }
};
