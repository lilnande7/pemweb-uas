<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $customers = [
            [
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '081234567890',
                'address' => 'Jl. Sudirman No. 123',
                'city' => 'Jakarta',
                'postal_code' => '12190',
                'date_of_birth' => '1990-05-15',
                'id_card_number' => '3171051505900001',
                'status' => 'active',
                'security_deposit' => 500000,
                'notes' => 'Regular customer, musician at local band',
            ],
            [
                'first_name' => 'Sari',
                'last_name' => 'Wijaya',
                'email' => 'sari.wijaya@email.com',
                'phone' => '081234567891',
                'address' => 'Jl. Thamrin No. 45',
                'city' => 'Jakarta',
                'postal_code' => '10230',
                'date_of_birth' => '1985-08-22',
                'id_card_number' => '3171052208850002',
                'status' => 'active',
                'security_deposit' => 300000,
                'notes' => 'Music teacher at local school',
            ],
            [
                'first_name' => 'Andi',
                'last_name' => 'Pratama',
                'email' => 'andi.pratama@email.com',
                'phone' => '081234567892',
                'address' => 'Jl. Gatot Subroto No. 78',
                'city' => 'Jakarta',
                'postal_code' => '12930',
                'date_of_birth' => '1992-11-10',
                'id_card_number' => '3171051011920003',
                'status' => 'active',
                'security_deposit' => 400000,
                'notes' => 'Event organizer, frequent rentals for events',
            ],
            [
                'first_name' => 'Maya',
                'last_name' => 'Sari',
                'email' => 'maya.sari@email.com',
                'phone' => '081234567893',
                'address' => 'Jl. Rasuna Said No. 101',
                'city' => 'Jakarta',
                'postal_code' => '12940',
                'date_of_birth' => '1988-03-28',
                'id_card_number' => '3171052803880004',
                'status' => 'active',
                'security_deposit' => 250000,
                'notes' => 'Student at music conservatory',
            ],
            [
                'first_name' => 'Rudi',
                'last_name' => 'Hermawan',
                'email' => 'rudi.hermawan@email.com',
                'phone' => '081234567894',
                'address' => 'Jl. HR Rasuna Said No. 88',
                'city' => 'Jakarta',
                'postal_code' => '12950',
                'date_of_birth' => '1995-07-12',
                'id_card_number' => '3171051207950005',
                'status' => 'active',
                'security_deposit' => 600000,
                'notes' => 'Professional musician, tours regularly',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create additional random customers
        for ($i = 6; $i <= 15; $i++) {
            Customer::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => '0812' . $faker->randomNumber(8),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'postal_code' => $faker->postcode,
                'date_of_birth' => $faker->date('Y-m-d', '2000-01-01'),
                'id_card_number' => '3171' . str_pad($i, 12, '0', STR_PAD_LEFT),
                'status' => $faker->randomElement(['active', 'active', 'active', 'inactive']),
                'security_deposit' => $faker->randomFloat(0, 100000, 800000),
                'notes' => $faker->optional(0.3)->sentence,
            ]);
        }
    }
}
