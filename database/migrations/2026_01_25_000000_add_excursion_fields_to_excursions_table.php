<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('excursions', function (Blueprint $table) {
            $table->enum('type', ['activity', 'circuit'])->default('activity')->after('id');
            $table->json('availability')->nullable()->after('description');
            $table->json('pickup_times')->nullable()->after('availability');
            $table->json('price_adult_eur')->nullable()->after('pickup_times');
            $table->json('price_child_eur')->nullable()->after('price_adult_eur');
        });
    }

    public function down(): void
    {
        Schema::table('excursions', function (Blueprint $table) {
            $table->dropColumn(['type', 'availability', 'pickup_times', 'price_adult_eur', 'price_child_eur']);
        });
    }
};