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
        Schema::table('excursions', function (Blueprint $table) {
            $table->boolean('children_allowed')->default(false);
            $table->decimal('children_price', 10, 2)->nullable();
            $table->string('promotion_type')->nullable(); // e.g., 'buy_x_get_y_free', 'percentage_discount'
            $table->json('promotion_value')->nullable();
            $table->boolean('promotion_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('excursions', function (Blueprint $table) {
            $table->dropColumn([
                'children_allowed',
                'children_price',
                'promotion_type',
                'promotion_value',
                'promotion_active'
            ]);
        });
    }
};
