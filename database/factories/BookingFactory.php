<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Models\User;
use App\Models\Excursion;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $excursion = Excursion::factory()->create();
        $numberOfTravelers = $this->faker->numberBetween(1, 10);
        return [
            'user_id' => User::factory(),
            'excursion_id' => $excursion->id,
            'booking_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
            'number_of_travelers' => $numberOfTravelers,
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->email,
            'customer_phone' => $this->faker->phoneNumber,
            'total_price' => $excursion->price * $numberOfTravelers,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'notes' => $this->faker->sentence,
        ];
    }
}
