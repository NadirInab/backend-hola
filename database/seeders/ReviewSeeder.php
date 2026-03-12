<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Excursion;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
{
    $users = User::where('role', User::ROLE_CUSTOMER)->get();
    $excursions = Excursion::all();

    if ($users->isEmpty() || $excursions->isEmpty()) {
        $this->command->info('No users or excursions found, skipping reviews.');
        return;
    }

    $comments = [
        'Amazing experience! The guide was very knowledgeable.',
        'Beautiful views of Agadir. Highly recommend the sunset tour.',
        'A bit long, but definitely worth the price.',
        'Excellent service and very friendly staff.',
        'The highlights of our trip! Everything was well-organized.',
    ];

    // Create 20 manual reviews without using the Factory/Faker
    for ($i = 0; $i < 20; $i++) {
        Review::create([
            'user_id' => $users->random()->id,
            'excursion_id' => $excursions->random()->id,
            'rating' => rand(4, 5), // Most reviews are 4 or 5 stars
            'comment' => $comments[array_rand($comments)],
            'status' => 'approved',
            'created_at' => now()->subDays(rand(1, 30)),
        ]);
    }

    $this->command->info('Successfully seeded 20 manual reviews.');
}
}
