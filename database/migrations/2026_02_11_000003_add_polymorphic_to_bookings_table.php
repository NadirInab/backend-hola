<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add polymorphic columns for supporting multiple models
            $table->unsignedBigInteger('bookable_id')->nullable()->after('excursion_id');
            $table->string('bookable_type')->nullable()->after('bookable_id');
            
            // Add legacy circuit_id and activity_id for backwards compatibility mapping
            $table->unsignedBigInteger('circuit_id')->nullable()->after('bookable_type');
            $table->unsignedBigInteger('activity_id')->nullable()->after('circuit_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['bookable_id', 'bookable_type', 'circuit_id', 'activity_id']);
        });
    }
};
