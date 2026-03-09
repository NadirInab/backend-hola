<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->longText('description');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('duration')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('group_size')->nullable();
            $table->string('languages')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->string('image')->nullable();
            $table->json('included')->nullable();
            $table->json('not_included')->nullable();
            $table->json('itinerary')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('rating');
            $table->fullText('title', 'description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursions');
    }
};
