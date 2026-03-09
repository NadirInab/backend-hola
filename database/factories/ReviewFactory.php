<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\User;
use App\Models\Excursion;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'excursion_id' => Excursion::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence,
            'comment' => $this->faker->paragraph,
        ];
    }
}
