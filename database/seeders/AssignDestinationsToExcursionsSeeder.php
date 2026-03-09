<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Excursion;
use App\Models\Destination;

class AssignDestinationsToExcursionsSeeder extends Seeder
{
    public function run(): void
    {
        // Get all destinations
        $destinations = Destination::all()->keyBy('name');

        // Get all excursions and assign destinations based on their titles/descriptions
        $excursions = Excursion::all();

        foreach ($excursions as $excursion) {
            $title = strtolower($excursion->title);
            $description = strtolower($excursion->description);

            $assigned = false;

            // Assign based on keywords in title/description
            if (str_contains($title, 'paradise valley') || str_contains($description, 'paradise valley')) {
                $excursion->update(['destination_id' => $destinations['Paradise Valley']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'agadir') || str_contains($description, 'agadir') || str_contains($title, 'beach') || str_contains($description, 'beach')) {
                $excursion->update(['destination_id' => $destinations['Agadir']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'atlas') || str_contains($description, 'atlas') || str_contains($title, 'mountain') || str_contains($description, 'mountain')) {
                $excursion->update(['destination_id' => $destinations['Atlas Mountains']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'sahara') || str_contains($description, 'sahara') || str_contains($title, 'desert') || str_contains($description, 'desert')) {
                $excursion->update(['destination_id' => $destinations['Sahara Desert']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'essaouira') || str_contains($description, 'essaouira')) {
                $excursion->update(['destination_id' => $destinations['Essaouira']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'marrakech') || str_contains($description, 'marrakech')) {
                $excursion->update(['destination_id' => $destinations['Marrakech']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'taghazout') || str_contains($description, 'taghazout') || str_contains($title, 'surf') || str_contains($description, 'surf')) {
                $excursion->update(['destination_id' => $destinations['Taghazout']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'souss') || str_contains($description, 'souss') || str_contains($title, 'massa') || str_contains($description, 'massa')) {
                $excursion->update(['destination_id' => $destinations['Souss-Massa']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'taroudant') || str_contains($description, 'taroudant')) {
                $excursion->update(['destination_id' => $destinations['Taroudant']->id ?? null]);
                $assigned = true;
            }
            elseif (str_contains($title, 'tiznit') || str_contains($description, 'tiznit')) {
                $excursion->update(['destination_id' => $destinations['Tiznit']->id ?? null]);
                $assigned = true;
            }

            // If no specific match, assign randomly to Agadir or Paradise Valley (most popular)
            if (!$assigned) {
                $popularDestinations = ['Agadir', 'Paradise Valley'];
                $randomDestination = $popularDestinations[array_rand($popularDestinations)];
                $excursion->update(['destination_id' => $destinations[$randomDestination]->id ?? null]);
            }
        }

        $this->command->info('Destinations assigned to excursions successfully!');
    }
}
