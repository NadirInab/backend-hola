<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circuits', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['activity', 'circuit'])->default('circuit');
            $table->string('title')->unique();
            $table->longText('description');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('duration')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->nullable()->constrained()->onDelete('set null');
            $table->string('group_size')->nullable();
            $table->string('languages')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->string('image')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->json('included')->nullable();
            $table->json('not_included')->nullable();
            $table->json('itinerary')->nullable();
            $table->boolean('children_allowed')->default(true);
            $table->decimal('children_price', 8, 2)->nullable();
            $table->string('promotion_type')->nullable();
            $table->json('promotion_value')->nullable();
            $table->boolean('promotion_active')->default(false);
            $table->json('availability')->nullable();
            $table->json('pickup_times')->nullable();
            $table->json('price_adult_eur')->nullable();
            $table->json('price_child_eur')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('destination_id');
            $table->index('rating');
            $table->fullText('title', 'description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circuits');
    }
};
