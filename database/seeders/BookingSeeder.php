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
            $this->command->info('No users or excursions found, please seed users and excursions first.');
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            $booking = Booking::factory()->make([
                'user_id' => $users->random()->id,
                'excursion_id' => $excursions->random()->id,
            ]);
            $booking->total_price = $booking->calculateTotalPrice();
            $booking->save();
        }
    }
}
