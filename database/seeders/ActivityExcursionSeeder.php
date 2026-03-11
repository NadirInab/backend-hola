<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Excursion;

class ActivityExcursionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Hot air ballon',
                'description' => 'Enjoy a scenic hot air balloon ride over Agadir at sunrise for a breathtaking view.',
                'availability' => ['Everyday'],
                'pickup_times' => ['04h45 --> 11h30'],
                'price_adult_eur' => 270,
                'price_child_eur' => 230,
            ],
            [
                'title' => 'Camel or horse ride',
                'description' => 'Experience a camel or horseback ride, a traditional mode of transport.',
                'availability' => ['Everyday'],
                'pickup_times' => ['10h-12h', '17h-19h'],
                'price_adult_eur' => 30,
                'price_child_eur' => 15,
            ],
            [
                'title' => 'Camel BBQ at sunset',
                'description' => 'Ride a camel at sunset through an eucalyptus forest while enjoying a BBQ dinner.',
                'availability' => ['Everyday'],
                'pickup_times' => ['17h-20:30'],
                'price_adult_eur' => 40,
                'price_child_eur' => 15,
            ],
            [
                'title' => 'Quad / Buggy',
                'description' => 'Explore the countryside by quad or buggy, with a Moroccan snack included in a Berber house.',
                'availability' => ['Everyday'],
                'pickup_times' => ['08h30-12h', '14h-18h'],
                'price_adult_eur' =>40,
                'price_child_eur' => null,
            ],
        ];

        $excursions = [
            [
                'title' => 'City tour of Agadir',
                'description' => 'Panoramic view of the Kasbah. Join us for a beautiful day around Agadir starting at the marina & port.',
                'availability' => ['Everyday'],
                'pickup_times' =>40,
                'price_adult_eur' => 15,
                'price_child_eur' => 10,
            ],
            [
                'title' => 'City tour of Agadir + (cable car)',
                'description' => "It's the same city tour plus a cable car ride.",
                'availability' => ['Everyday'],
                'pickup_times' => ['10h-13h', '15h-18h'],
                'price_adult_eur' => 30,
                'price_child_eur' => 20,
            ],
            [
                'title' => 'Small Sahara (4x4)',
                'description' => 'Discover small desert, Atlas Mountains, Berber villages, banana plantations, and reservoir with lunch.',
                'availability' => ['Everyday'],
                'pickup_times' => ['08:30 --> 17h'],
                'price_adult_eur' => 45,
                'price_child_eur' => 30,
            ],
            [
                'title' => 'Paradise Valley',
                'description' => 'Drive through High Atlas foothills with lush oases, deep gorges, and rivers.',
                'availability' => ['Everyday'],
                'pickup_times' => ['08:30 --> 14h', '14h --> 19h'],
                'price_adult_eur' => 25,
                'price_child_eur' => 20,
            ],
            [
                'title' => 'Taroudant & Tiout',
                'description' => 'Explore the ancient city of Taroudant and visit the Tiout Kasbah.',
                'availability' => ['Thursday', 'Saturday', 'Tuesday'],
                'pickup_times' => ['08:30 --> 17h'],
                'price_adult_eur' => 30,
                'price_child_eur' => 45,
            ],
            [
                'title' => 'Essaouira (Mogador)',
                'description' => 'Visit Mogador, famous for its port, Scala, and medina craftsmen.',
                'availability' => ['Monday', 'Wednesday', 'Friday'],
                'pickup_times' => ['07:30 --> 18h'],
                'price_adult_eur' => 40,
                'price_child_eur' => 25,
            ],
            [
                'title' => 'Cooking class',
                'description' => 'Prepare a traditional Berber tagine and hike through Atlas villages.',
                'availability' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'pickup_times' => ['09h --> 17h'],
                'price_adult_eur' => 45,
                'price_child_eur' => 25,
            ],
            [
                'title' => 'Marrakech',
                'description' => 'Visit the imperial city, Saadian Tombs, Bahia Palace, and souks.',
                'availability' => ['Everyday'],
                'pickup_times' => ['07:30 --> 21h'],
                'price_adult_eur' => 50,
                'price_child_eur' => 30,
            ],
            [
                'title' => 'Legzira & Tiznit',
                'description' => 'Explore Legzira beach and the silver market medina of Tiznit.',
                'availability' => ['Everyday'],
                'pickup_times' => ['08h --> 18h'],
                'price_adult_eur' => 50,
                'price_child_eur' => 25,
            ],
        ];

        // Ensure activities go into activities table only
        foreach ($activities as $act) {
            $data = [];
            // map only provided fields
            $data['title'] = $act['title'] ?? null;
            if (isset($act['description'])) $data['description'] = $act['description'];
            if (isset($act['availability'])) $data['availability'] = $act['availability'];
            if (isset($act['pickup_times'])) $data['pickup_times'] = $act['pickup_times'];
            // preserve price_adult_eur as provided (array or numeric or string parsed)
            if (isset($act['price_adult_eur'])) {
                if (is_string($act['price_adult_eur'])) {
                    $clean = preg_replace('/[^0-9\.,]/', '', $act['price_adult_eur']);
                    $clean = str_replace(',', '.', $clean);
                    if ($clean !== '') $data['price_adult_eur'] = floatval($clean);
                } else {
                    $data['price_adult_eur'] = $act['price_adult_eur'];
                }
            }
            if (isset($act['price_child_eur'])) {
                if (is_string($act['price_child_eur'])) {
                    $clean = preg_replace('/[^0-9\.,]/', '', $act['price_child_eur']);
                    $clean = str_replace(',', '.', $clean);
                    if ($clean !== '') $data['price_child_eur'] = floatval($clean);
                } else {
                    $data['price_child_eur'] = $act['price_child_eur'];
                }
            }
            // leave category_id, duration, group_size, languages unset unless provided
            Activity::updateOrCreate(
                ['title' => $data['title']],
                $data
            );
        }

        // Ensure excursions go into excursions table only
        foreach ($excursions as $ex) {
            $data = [];
            $data['title'] = $ex['title'] ?? null;
            if (isset($ex['description'])) $data['description'] = $ex['description'];
            if (isset($ex['availability'])) $data['availability'] = $ex['availability'];
            if (isset($ex['pickup_times'])) $data['pickup_times'] = $ex['pickup_times'];
            if (isset($ex['price_adult_eur'])) {
                if (is_string($ex['price_adult_eur'])) {
                    $clean = preg_replace('/[^0-9\.,]/', '', $ex['price_adult_eur']);
                    $clean = str_replace(',', '.', $clean);
                    if ($clean !== '') $data['price_adult_eur'] = floatval($clean);
                } else {
                    $data['price_adult_eur'] = $ex['price_adult_eur'];
                }
            }
            if (isset($ex['price_child_eur'])) {
                if (is_string($ex['price_child_eur'])) {
                    $clean = preg_replace('/[^0-9\.,]/', '', $ex['price_child_eur']);
                    $clean = str_replace(',', '.', $clean);
                    if ($clean !== '') $data['price_child_eur'] = floatval($clean);
                } else {
                    $data['price_child_eur'] = $ex['price_child_eur'];
                }
            }

            Excursion::updateOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
