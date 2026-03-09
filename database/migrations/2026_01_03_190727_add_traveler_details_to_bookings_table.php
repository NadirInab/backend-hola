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
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('number_of_adults')->default(1)->after('booking_date');
            $table->integer('number_of_children')->default(0)->after('number_of_adults');
            $table->json('traveler_details')->nullable()->after('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['number_of_adults', 'number_of_children', 'traveler_details']);
        });
    }
};
