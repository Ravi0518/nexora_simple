<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SnakeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('snakes')->insert([
            [
                'common_name' => 'Indian Cobra',
                'scientific_name' => 'Naja naja',
                'venomous_status' => 'Venomous',
                'description' => 'A highly venomous snake native to the Indian subcontinent. It is easily recognized by the hood it spreads when threatened.',
                'first_aid_steps' => '1. Keep the patient calm. \n2. Immobilize the bitten limb. \n3. Transport to a hospital immediately. \n4. Do NOT suck the venom.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'common_name' => 'Russell’s Viper',
                'scientific_name' => 'Daboia russelii',
                'venomous_status' => 'Venomous',
                'description' => 'Responsible for the most snakebite incidents in Sri Lanka. It is aggressive and has a loud hiss.',
                'first_aid_steps' => '1. Do not apply a tourniquet. \n2. Keep the limb below heart level. \n3. Rush to the nearest hospital with anti-venom.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'common_name' => 'Rat Snake',
                'scientific_name' => 'Ptyas mucosa',
                'venomous_status' => 'Non-Venomous',
                'description' => 'A common, harmless snake often found near human settlements. It controls the rodent population.',
                'first_aid_steps' => '1. Wash the wound with soap and water. \n2. Apply a clean bandage. \n3. Tetanus shot may be required.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'common_name' => 'Common Krait',
                'scientific_name' => 'Bungarus caeruleus',
                'venomous_status' => 'Highly Venomous',
                'description' => 'Nocturnal and highly venomous. Its bite is often painless but can lead to respiratory failure.',
                'first_aid_steps' => '1. Seek immediate medical attention even if no pain is felt. \n2. Monitor breathing. \n3. Keep patient awake.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'common_name' => 'Sri Lankan Green Pit Viper',
                'scientific_name' => 'Craspedocephalus trigonocephalus',
                'venomous_status' => 'Venomous',
                'description' => 'An endemic arboreal snake found in wet zones. It has a triangular head and green body.',
                'first_aid_steps' => '1. Remove rings or tight clothing near the bite. \n2. Keep the patient still. \n3. Go to hospital.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}