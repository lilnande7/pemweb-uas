<?php

namespace Database\Seeders;

use App\Models\InstrumentCategory;
use Illuminate\Database\Seeder;

class InstrumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'String Instruments',
                'description' => 'Musical instruments that produce sound from vibrating strings',
                'is_active' => true,
            ],
            [
                'name' => 'Wind Instruments',
                'description' => 'Musical instruments that produce sound by vibrating air',
                'is_active' => true,
            ],
            [
                'name' => 'Percussion Instruments',
                'description' => 'Musical instruments that produce sound by being struck, shaken, or scraped',
                'is_active' => true,
            ],
            [
                'name' => 'Keyboard Instruments',
                'description' => 'Musical instruments played using a keyboard',
                'is_active' => true,
            ],
            [
                'name' => 'Electronic Instruments',
                'description' => 'Musical instruments that use electronic technology',
                'is_active' => true,
            ],
            [
                'name' => 'Traditional Instruments',
                'description' => 'Traditional Indonesian and regional musical instruments',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            InstrumentCategory::create($category);
        }
    }
}
