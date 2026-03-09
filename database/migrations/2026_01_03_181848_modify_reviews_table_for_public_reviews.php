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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('name')->after('user_id')->nullable();
            $table->boolean('is_approved')->default(false)->after('comment');
            $table->foreignId('user_id')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->dropUnique(['excursion_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique(['excursion_id', 'user_id']);
            $table->dropColumn(['name', 'is_approved']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
        });
    }
};
