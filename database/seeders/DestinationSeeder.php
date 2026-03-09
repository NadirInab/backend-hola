<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Destination::firstOrCreate(['name' => 'Agadir'], [
            'description' => 'A beautiful coastal city in Morocco.',
        ]);

        Destination::firstOrCreate(['name' => 'Marrakech'], [
            'description' => 'A vibrant city with a rich history.',
        ]);

        Destination::firstOrCreate(['name' => 'Essaouira'], [
            'description' => 'A charming coastal town with a relaxed atmosphere.',
        ]);
    }
}