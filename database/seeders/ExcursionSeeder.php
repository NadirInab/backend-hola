<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Excursion;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class ExcursionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Load data from newData.json
        $jsonPath = base_path('../newData.json');
        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        $activities = $data['tours_and_activities'] ?? [];

        foreach ($activities as $activity) {
            // Handle price - if it's an object/array, get the first/lowest value
            $adultPrice = $activity['price_adult_eur'];
            if (is_array($adultPrice) || is_object($adultPrice)) {
                if (is_object($adultPrice)) {
                    $adultPrice = (array) $adultPrice;
                }
                $adultPrice = min(array_values($adultPrice));
            }

            // Determine type - default to 'activity' if not specified
            $type = $activity['type'] ?? 'activity';

            // Transform the data to match database schema
            $excursionData = [
                'title' => $activity['activity'],
                'description' => $activity['description'],
                'price' => $adultPrice, // Use adult price as main price
                'duration' => $this->determineDuration($activity['pickup_times'], $type, $activity['duration_days'] ?? null),
                'category_id' => 1, // Default category
                'group_size' => $type === 'circuit' ? '2-12 people' : '1-8 people',
                'languages' => 'English, French, Spanish',
                'type' => $type,
                'availability' => $this->normalizeAvailability($activity['availability']),
                'pickup_times' => $this->normalizePickupTimes($activity['pickup_times']),
                'price_adult_eur' => $activity['price_adult_eur'],
                'price_child_eur' => $activity['price_child_eur'],
            ];

            Excursion::updateOrCreate(
                ['title' => $excursionData['title']],
                $excursionData
            );
        }
    }

    /**
     * Determine duration based on pickup times and type
     */
    private function determineDuration($pickupTimes, $type = 'activity', $durationDays = null)
    {
        // For circuits, use the duration_days if provided
        if ($type === 'circuit' && $durationDays) {
            return $durationDays . ' Days';
        }

        if (is_array($pickupTimes)) {
            // If multiple time slots, assume full day
            return 'Full Day';
        }

        // Parse time range like "08:30 --> 17h"
        if (strpos($pickupTimes, '-->') !== false) {
            return 'Full Day';
        }

        // Parse time range like "10h-13h"
        if (strpos($pickupTimes, '-') !== false) {
            return 'Half Day';
        }

        return 'Half Day'; // Default
    }

    /**
     * Normalize availability to array format
     */
    private function normalizeAvailability($availability)
    {
        if (is_array($availability)) {
            return $availability;
        }

        if (is_string($availability)) {
            // Handle cases like "Everyday except Friday"
            if (strpos($availability, 'except') !== false) {
                return [$availability];
            }
            return [$availability];
        }

        return ['Everyday'];
    }

    /**
     * Normalize pickup times to array format
     */
    private function normalizePickupTimes($pickupTimes)
    {
        if (is_array($pickupTimes)) {
            return $pickupTimes;
        }

        if (is_string($pickupTimes)) {
            return [$pickupTimes];
        }

        return [];
    }
}
