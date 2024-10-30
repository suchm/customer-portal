<?php

use App\Models\Chat;
use App\Models\User;
use App\Models\Thread;
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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Chat::class)->constrained();
            $table->softDeletes();
            $table->timestamps();
            $table->timestamp('last_read')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
