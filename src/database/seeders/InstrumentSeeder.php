<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\InstrumentCategory;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stringCategory = InstrumentCategory::where('name', 'String Instruments')->first();
        $windCategory = InstrumentCategory::where('name', 'Wind Instruments')->first();
        $percussionCategory = InstrumentCategory::where('name', 'Percussion Instruments')->first();
        $keyboardCategory = InstrumentCategory::where('name', 'Keyboard Instruments')->first();
        $electronicCategory = InstrumentCategory::where('name', 'Electronic Instruments')->first();
        $traditionalCategory = InstrumentCategory::where('name', 'Traditional Instruments')->first();

        $instruments = [
            // String Instruments
            [
                'name' => 'Acoustic Guitar',
                'brand' => 'Yamaha',
                'model' => 'FG830',
                'category_id' => $stringCategory->id,
                'description' => 'Full-size steel-string acoustic guitar perfect for beginners and professionals',
                'daily_rate' => 50000,
                'weekly_rate' => 300000,
                'monthly_rate' => 1000000,
                'quantity_total' => 5,
                'quantity_available' => 5,
                'condition' => 'excellent',
                'year_made' => 2023,
                'specifications' => [
                    'body' => 'Dreadnought',
                    'top' => 'Solid Sitka Spruce',
                    'back_sides' => 'Rosewood',
                    'neck' => 'Nato',
                    'fingerboard' => 'Rosewood',
                    'scale_length' => '25" (634mm)',
                    'nut_width' => '43mm',
                    'finish' => 'Natural'
                ],
            ],
            [
                'name' => 'Classical Guitar',
                'brand' => 'Yamaha',
                'model' => 'C40',
                'category_id' => $stringCategory->id,
                'description' => 'Nylon-string classical guitar ideal for classical and flamenco music',
                'daily_rate' => 40000,
                'weekly_rate' => 250000,
                'monthly_rate' => 800000,
                'quantity_total' => 3,
                'quantity_available' => 3,
                'condition' => 'good',
                'year_made' => 2022,
                'specifications' => [
                    'body' => 'Classical',
                    'top' => 'Spruce',
                    'back_sides' => 'Meranti',
                    'neck' => 'Nato',
                    'fingerboard' => 'Rosewood',
                    'scale_length' => '25 9/16" (650mm)',
                    'nut_width' => '52mm',
                ],
            ],
            [
                'name' => 'Electric Guitar',
                'brand' => 'Fender',
                'model' => 'Player Stratocaster',
                'category_id' => $stringCategory->id,
                'description' => 'Professional electric guitar with classic Stratocaster tone',
                'daily_rate' => 75000,
                'weekly_rate' => 450000,
                'monthly_rate' => 1500000,
                'quantity_total' => 2,
                'quantity_available' => 2,
                'condition' => 'excellent',
                'year_made' => 2023,
                'specifications' => [
                    'body' => 'Alder',
                    'neck' => 'Maple',
                    'fingerboard' => 'Maple',
                    'pickups' => '3 Player Series Alnico 5 Strat Single-coil',
                    'bridge' => '2-Point Synchronized Tremolo',
                    'tuning_machines' => 'Standard Cast/Sealed',
                ],
            ],
            [
                'name' => 'Bass Guitar',
                'brand' => 'Yamaha',
                'model' => 'TRBX174',
                'category_id' => $stringCategory->id,
                'description' => '4-string electric bass guitar with punchy tone',
                'daily_rate' => 60000,
                'weekly_rate' => 350000,
                'monthly_rate' => 1200000,
                'quantity_total' => 2,
                'quantity_available' => 2,
                'condition' => 'good',
                'year_made' => 2022,
            ],
            [
                'name' => 'Violin',
                'brand' => 'Stentor',
                'model' => 'Student II',
                'category_id' => $stringCategory->id,
                'description' => 'Full-size student violin with bow and case',
                'daily_rate' => 45000,
                'weekly_rate' => 280000,
                'monthly_rate' => 900000,
                'quantity_total' => 3,
                'quantity_available' => 3,
                'condition' => 'good',
                'year_made' => 2021,
            ],

            // Wind Instruments
            [
                'name' => 'Trumpet',
                'brand' => 'Yamaha',
                'model' => 'YTR-2330',
                'category_id' => $windCategory->id,
                'description' => 'Student trumpet in Bb with excellent tone and projection',
                'daily_rate' => 70000,
                'weekly_rate' => 400000,
                'monthly_rate' => 1300000,
                'quantity_total' => 2,
                'quantity_available' => 2,
                'condition' => 'excellent',
                'year_made' => 2023,
                'specifications' => [
                    'key' => 'Bb',
                    'bore' => 'ML (11.65mm)',
                    'bell' => 'Yellow brass (123mm)',
                    'finish' => 'Gold lacquer',
                ],
            ],
            [
                'name' => 'Saxophone Alto',
                'brand' => 'Yamaha',
                'model' => 'YAS-280',
                'category_id' => $windCategory->id,
                'description' => 'Alto saxophone perfect for students and intermediate players',
                'daily_rate' => 100000,
                'weekly_rate' => 600000,
                'monthly_rate' => 2000000,
                'quantity_total' => 1,
                'quantity_available' => 1,
                'condition' => 'excellent',
                'year_made' => 2023,
                'specifications' => [
                    'key' => 'Eb',
                    'body' => 'Yellow brass',
                    'keys' => 'Nickel-plated',
                    'finish' => 'Clear lacquer',
                ],
            ],
            [
                'name' => 'Flute',
                'brand' => 'Yamaha',
                'model' => 'YFL-222',
                'category_id' => $windCategory->id,
                'description' => 'Student flute with silver-plated head joint',
                'daily_rate' => 55000,
                'weekly_rate' => 320000,
                'monthly_rate' => 1100000,
                'quantity_total' => 2,
                'quantity_available' => 2,
                'condition' => 'good',
                'year_made' => 2022,
            ],

            // Percussion Instruments
            [
                'name' => 'Drum Set',
                'brand' => 'Pearl',
                'model' => 'Roadshow RS525SC',
                'category_id' => $percussionCategory->id,
                'description' => 'Complete 5-piece drum set with cymbals and hardware',
                'daily_rate' => 150000,
                'weekly_rate' => 900000,
                'monthly_rate' => 3000000,
                'quantity_total' => 1,
                'quantity_available' => 1,
                'condition' => 'excellent',
                'year_made' => 2023,
                'specifications' => [
                    'shells' => 'Poplar',
                    'bass_drum' => '22" x 16"',
                    'toms' => '10" x 8", 12" x 9"',
                    'floor_tom' => '16" x 16"',
                    'snare' => '14" x 5.5"',
                    'cymbals' => 'Sabian SBR Performance Set',
                ],
            ],
            [
                'name' => 'Cajon',
                'brand' => 'Meinl',
                'model' => 'Headliner Series',
                'category_id' => $percussionCategory->id,
                'description' => 'Wooden cajon with excellent bass and snare sounds',
                'daily_rate' => 35000,
                'weekly_rate' => 200000,
                'monthly_rate' => 650000,
                'quantity_total' => 3,
                'quantity_available' => 3,
                'condition' => 'good',
                'year_made' => 2022,
            ],
            [
                'name' => 'Djembe',
                'brand' => 'Toca',
                'model' => 'TODJ-8AM',
                'category_id' => $percussionCategory->id,
                'description' => 'African djembe drum with goatskin head',
                'daily_rate' => 30000,
                'weekly_rate' => 180000,
                'monthly_rate' => 600000,
                'quantity_total' => 4,
                'quantity_available' => 4,
                'condition' => 'good',
                'year_made' => 2021,
            ],

            // Keyboard Instruments
            [
                'name' => 'Digital Piano',
                'brand' => 'Yamaha',
                'model' => 'P-125',
                'category_id' => $keyboardCategory->id,
                'description' => '88-key digital piano with weighted keys and realistic sound',
                'daily_rate' => 120000,
                'weekly_rate' => 700000,
                'monthly_rate' => 2500000,
                'quantity_total' => 2,
                'quantity_available' => 2,
                'condition' => 'excellent',
                'year_made' => 2023,
                'specifications' => [
                    'keys' => '88 weighted keys',
                    'sound_source' => 'Pure CF Sound Engine',
                    'voices' => '24',
                    'polyphony' => '192',
                    'speakers' => '2 x 7W',
                ],
            ],
            [
                'name' => 'Keyboard Synthesizer',
                'brand' => 'Roland',
                'model' => 'JUNO-DS61',
                'category_id' => $keyboardCategory->id,
                'description' => '61-key synthesizer with professional sounds and features',
                'daily_rate' => 90000,
                'weekly_rate' => 540000,
                'monthly_rate' => 1800000,
                'quantity_total' => 1,
                'quantity_available' => 1,
                'condition' => 'excellent',
                'year_made' => 2023,
            ],

            // Electronic Instruments
            [
                'name' => 'Electric Violin',
                'brand' => 'Yamaha',
                'model' => 'YEV-104',
                'category_id' => $electronicCategory->id,
                'description' => 'Electric violin with built-in pickup and headphone output',
                'daily_rate' => 80000,
                'weekly_rate' => 480000,
                'monthly_rate' => 1600000,
                'quantity_total' => 1,
                'quantity_available' => 1,
                'condition' => 'excellent',
                'year_made' => 2022,
            ],
            [
                'name' => 'Guitar Amplifier',
                'brand' => 'Fender',
                'model' => 'Champion 40',
                'category_id' => $electronicCategory->id,
                'description' => '40-watt guitar amplifier with built-in effects',
                'daily_rate' => 40000,
                'weekly_rate' => 240000,
                'monthly_rate' => 800000,
                'quantity_total' => 3,
                'quantity_available' => 3,
                'condition' => 'good',
                'year_made' => 2022,
            ],

            // Traditional Instruments
            [
                'name' => 'Angklung',
                'brand' => 'Saung Udjo',
                'model' => 'Traditional Set',
                'category_id' => $traditionalCategory->id,
                'description' => 'Traditional Sundanese bamboo angklung set (1 octave)',
                'daily_rate' => 25000,
                'weekly_rate' => 150000,
                'monthly_rate' => 500000,
                'quantity_total' => 2,
                'quantity_available' => 2,
                'condition' => 'good',
                'year_made' => 2021,
            ],
            [
                'name' => 'Gamelan Bonang',
                'brand' => 'Traditional',
                'model' => 'Javanese',
                'category_id' => $traditionalCategory->id,
                'description' => 'Traditional Javanese bonang gamelan instrument',
                'daily_rate' => 60000,
                'weekly_rate' => 350000,
                'monthly_rate' => 1200000,
                'quantity_total' => 1,
                'quantity_available' => 1,
                'condition' => 'good',
                'year_made' => 2020,
            ],
        ];

        foreach ($instruments as $instrument) {
            Instrument::create($instrument);
        }
    }
}
