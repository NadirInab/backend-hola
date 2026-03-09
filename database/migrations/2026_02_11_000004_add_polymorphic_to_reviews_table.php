<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add polymorphic columns for supporting multiple models
            // Use addColumn when creating new columns; avoid change() on non-existent columns
            if (!Schema::hasColumn('reviews', 'reviewable_id')) {
                $table->unsignedBigInteger('reviewable_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->string('reviewable_type')->default('App\\Models\\Excursion')->after('reviewable_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Remove the polymorphic columns if they exist
            if (Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->dropColumn('reviewable_type');
            }
            if (Schema::hasColumn('reviews', 'reviewable_id')) {
                $table->dropColumn('reviewable_id');
            }
        });
    }
};
