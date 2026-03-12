<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Excursion;

class BookingSeeder extends Seeder
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
        $this->command->info('No users or excursions found, skipping bookings.');
        return;
    }

    $statuses = [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED];

    for ($i = 0; $i < 20; $i++) {
        $excursion = $excursions->random();
        $adults = rand(1, 4);
        $children = rand(0, 2);
        
        // Calculate total based on your Activity/Excursion price logic
        // Using floatval to ensure it works with the Model cast
        $totalPrice = ($adults * floatval($excursion->price_adult_eur)) + ($children * floatval($excursion->price_child_eur));

        Booking::create([
            'user_id'             => $users->random()->id,
            'excursion_id'        => $excursion->id,
            'bookable_id'         => $excursion->id,
            'bookable_type'       => Excursion::class,
            'booking_date'        => now()->addDays(rand(1, 30)),
            'number_of_adults'    => $adults,       // Matched to your model
            'number_of_children'  => $children,     // Matched to your model
            'number_of_travelers' => $adults + $children,
            'customer_name'       => $users->random()->name,
            'customer_email'      => $users->random()->email,
            'customer_phone'      => '+2126' . rand(10000000, 99999999),
            'total_price'         => $totalPrice,
            'status'              => $statuses[array_rand($statuses)],
            'is_approved'         => true,
        ]);
    }

    $this->command->info('Successfully seeded 20 manual bookings matching the Booking Model.');
}
}
