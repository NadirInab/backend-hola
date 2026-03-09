<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::firstOrCreate(['name' => 'Desert Tours'], [
            'description' => 'Explore the vast and beautiful Sahara desert.',
        ]);

        Category::firstOrCreate(['name' => 'City Tours'], [
            'description' => 'Discover the vibrant cities of Morocco.',
        ]);

        Category::firstOrCreate(['name' => 'Mountain Biking'], [
            'description' => 'Experience the thrill of biking in the Atlas mountains.',
        ]);

        Category::firstOrCreate(['name' => 'Water Sports'], [
            'description' => 'Enjoy the beautiful beaches of Agadir.',
        ]);
    }
}
