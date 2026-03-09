<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('excursions', function (Blueprint $table) {
            // Change JSON columns to decimal for price fields
            $table->decimal('price_adult_eur', 10, 2)->nullable()->change();
            $table->decimal('price_child_eur', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('excursions', function (Blueprint $table) {
            // Revert to JSON columns
            $table->json('price_adult_eur')->nullable()->change();
            $table->json('price_child_eur')->nullable()->change();
        });
    }
};
