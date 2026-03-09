<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Destination;

class DestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Destination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'is_active' => true,
            'display_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
