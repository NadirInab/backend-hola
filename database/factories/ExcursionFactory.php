<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Excursion;
use App\Models\Category;
use App\Models\Destination;

class ExcursionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Excursion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 50, 500),
            'duration' => $this->faker->numberBetween(2, 8) . ' hours',
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'destination_id' => Destination::inRandomOrder()->first()->id ?? Destination::factory(),
            'group_size' => $this->faker->numberBetween(4, 15),
            'languages' => $this->faker->randomElement(['English', 'Spanish', 'French', 'German']),
            'rating' => $this->faker->randomFloat(1, 4, 5),
            'reviews_count' => $this->faker->numberBetween(10, 100),
            'image' => $this->faker->imageUrl(),
            'included' => $this->faker->words(3),
            'not_included' => $this->faker->words(3),
            'itinerary' => [
                ['time' => '09:00', 'description' => 'Departure from Agadir'],
                ['time' => '12:00', 'description' => 'Lunch in a scenic spot'],
                ['time' => '17:00', 'description' => 'Return to Agadir'],
            ],
        ];
    }
}
