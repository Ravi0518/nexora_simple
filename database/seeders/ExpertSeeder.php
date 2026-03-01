<?php

namespace Database\Seeders;

use App\Models\Expert;
use Illuminate\Database\Seeder;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        $experts = [
            [
                'user_id'           => null,
                'name'              => 'Dr. Alistair Finch',
                'role'              => 'Certified Herpetologist',
                'phone'             => '0771234567',
                'lat'               => 6.9271,
                'lng'               => 79.8612,
                'status'            => 'available',
                'rating'            => 4.8,
                'total_rescues'     => 23,
                'profile_image_url' => null,
            ],
            [
                'user_id'           => null,
                'name'              => 'Roshan Perera',
                'role'              => 'Wildlife Conservationist',
                'phone'             => '0712345678',
                'lat'               => 8.3444,
                'lng'               => 80.5024,
                'status'            => 'available',
                'rating'            => 4.5,
                'total_rescues'     => 14,
                'profile_image_url' => null,
            ],
            [
                'user_id'           => null,
                'name'              => 'Amaya Dissanayake',
                'role'              => 'Snake Enthusiast',
                'phone'             => '0751234567',
                'lat'               => 7.2906,
                'lng'               => 80.6337,
                'status'            => 'busy',
                'rating'            => 4.2,
                'total_rescues'     => 8,
                'profile_image_url' => null,
            ],
        ];

        foreach ($experts as $expert) {
            Expert::create($expert);
        }
    }
}
