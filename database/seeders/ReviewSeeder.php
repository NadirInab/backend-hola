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
        'The highlight of our trip! Everything was well-organized.',
    ];

    foreach ($excursions as $excursion) {
        // Create 2-3 reviews per excursion for a full-looking page
        for ($i = 0; $i < rand(2, 3); $i++) {
            Review::create([
                'user_id'         => $users->random()->id,
                'excursion_id'    => $excursion->id, // For your backward compatibility relation
                'reviewable_id'   => $excursion->id,
                'reviewable_type' => Excursion::class,
                'name'            => $users->random()->name,
                'rating'          => rand(4, 5),
                'title'           => 'Great Trip!',
                'comment'         => $comments[array_rand($comments)],
                'is_approved'     => true, // Matches your model's $fillable
            ]);
        }
    }

    $this->command->info('Successfully seeded reviews for all excursions.');
}
}
