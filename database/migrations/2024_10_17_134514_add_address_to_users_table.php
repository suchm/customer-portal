<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->text('telephone')->nullable()->after('email');
            $table->text('tel_country_code')->nullable()->after('telephone');
            $table->text('address_1')->nullable()->after('tel_country_code');
            $table->text('address_2')->nullable()->after('address_1');
            $table->text('county')->nullable()->after('address_2');
            $table->text('city')->nullable()->after('county');
            $table->text('country')->nullable()->after('city');
            $table->text('postcode')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
