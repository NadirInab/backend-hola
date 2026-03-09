<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExcursionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'City tour of Agadir',
                'description' => 'Join us for a beautiful day around Agadir with an speaking driver starting at the marina & port for a panoramic view of the Kasbah.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['10h-13h', '15h-18h'],
                'price_adult_eur' => 15,
                'price_child_eur' => 10,
            ],
            [
                'title' => 'City tour of Agadir + (cable car)',
                'description' => 'It\'s the same city tour plus a cable car ride.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['10h-13h', '15h-18h'],
                'price_adult_eur' => 30,
                'price_child_eur' => 20,
            ],
            [
                'title' => 'Small Sahara (4x4)',
                'description' => 'Discover the small desert and Atlas Mountains, Berber villages, banana plantations, and the Ibn Tachafin reservoir, with a lunch break.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['08:30 --> 17h'],
                'price_adult_eur' => 45,
                'price_child_eur' => 30,
            ],
            [
                'title' => 'Paradise Valley',
                'description' => 'Drive through the High Atlas foothills with lush oases, deep gorges, and rivers in \'Paradise Valley.\'',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['08:30 --> 14h', '14h --> 19h'],
                'price_adult_eur' => 25,
                'price_child_eur' => 20,
            ],
            [
                'title' => 'Taroudant & Tiout',
                'description' => 'Explore the ancient city of Taroudant, known as \'Little Marrakech,\' and visit the Tiout Kasbah.',
                'type' => 'activity',
                'availability' => ['Tuesday', 'Thursday', 'Saturday'],
                'pickup_times' => ['08:30 --> 17h'],
                'price_adult_eur' => 45,
                'price_child_eur' => 30,
            ],
            [
                'title' => 'Hot air balloon',
                'description' => 'Enjoy a scenic hot air balloon ride over Agadir at sunrise for a breathtaking view',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['04h45 --> 11h30'],
                'price_adult_eur' => 270,
                'price_child_eur' => 230,
            ],
            [
                'title' => 'Essaouira (Mogador)',
                'description' => 'Visit Mogador, famous for its port, Scala, and skilled craftsmen in the medina.',
                'type' => 'activity',
                'availability' => ['Monday', 'Wednesday', 'Friday'],
                'pickup_times' => ['07:30 --> 18h'],
                'price_adult_eur' => 40,
                'price_child_eur' => 25,
            ],
            [
                'title' => 'Cooking class',
                'description' => 'Prepare a traditional Berber tagine and hike through beautiful landscapes in Atlas villages.',
                'type' => 'activity',
                'availability' => ['Everyday except Friday'],
                'pickup_times' => ['09h --> 17h'],
                'price_adult_eur' => 45,
                'price_child_eur' => 25,
            ],
            [
                'title' => 'Marrakech',
                'description' => 'Visit the imperial city of Marrakech, including the Saadian Tombs, Bahia Palace, and souks.',
                'type' => 'activity',
                'availability' => ['Thursday', 'Tuesday', 'Saturday'],
                'pickup_times' => ['07:30 --> 21h'],
                'price_adult_eur' => 50,
                'price_child_eur' => 30,
            ],
            [
                'title' => 'Legzira & Tiznit',
                'description' => 'Explore Legzira beach, pottery sites, and the medina of Tiznit, known for its silver market.',
                'type' => 'activity',
                'availability' => ['Monday', 'Wednesday', 'Friday'],
                'pickup_times' => ['08h --> 18h'],
                'price_adult_eur' => 50,
                'price_child_eur' => 25,
            ],
            [
                'title' => 'Crocoparc',
                'description' => 'Discover 330 crocodiles, anacondas, and tropical gardens at Crocoparc.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['10h-13h', '15h-18h'],
                'price_adult_eur' => 30,
                'price_child_eur' => 20,
            ],
            [
                'title' => 'Boat trip/fishing',
                'description' => 'Sail along the Atlantic coast for a relaxing fishing trip with lunch included.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['09h --> 14h'],
                'price_adult_eur' => 45,
                'price_child_eur' => 25,
            ],
            [
                'title' => 'Camel or horse ride',
                'description' => 'Experience a camel or horseback ride, a traditional mode of transport.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['10h-12h', '17h-19h'],
                'price_adult_eur' => 30,
                'price_child_eur' => 15,
            ],
            [
                'title' => 'Camel BBQ at sunset',
                'description' => 'Ride a camel at sunset through an eucalyptus forest while enjoying a BBQ dinner.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['17h-20:30'],
                'price_adult_eur' => 40,
                'price_child_eur' => 15,
            ],
            [
                'title' => 'Quad / Buggy',
                'description' => 'Explore the countryside by quad or buggy, with a Moroccan snack included in a Berber house.',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['08h30-12h', '14h-18h'],
                'price_adult_eur' => ['quad' => 40, 'buggy' => 60],
                'price_child_eur' => null,
            ],
            [
                'title' => 'Fantasia',
                'description' => 'Offers an unforgettable evening of Moroccan culture with traditional music, captivating horse shows, and authentic cuisine in a vibrant atmosphere.',
                'type' => 'activity',
                'availability' => ['Thursday', 'Friday'],
                'pickup_times' => ['19:00 to 23:30'],
                'price_adult_eur' => 60,
                'price_child_eur' => null,
            ],
            [
                'title' => 'Sand-Boarding',
                'description' => 'Every day the plain of sousse with its greenhouses, courtyards and fields of argan trees and olive trees. Back on dry land, a change of scenery awaits you under a Berber tent: a breakfast made up of Berber products',
                'type' => 'activity',
                'availability' => ['Everyday'],
                'pickup_times' => ['09h00 --> 14h00'],
                'price_adult_eur' => 50,
                'price_child_eur' => 35,
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Excursion::create($item);
        }
    }
}
