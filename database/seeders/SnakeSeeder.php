<?php

namespace Database\Seeders;

use App\Models\Snake;
use Illuminate\Database\Seeder;

class SnakeSeeder extends Seeder
{
    public function run(): void
    {
        $snakes = [
            [
                'names' => json_encode([
                    'English'  => 'Indian Cobra',
                    'සිංහල'   => 'නයා',
                    'தமிழ்'   => 'நாகப்பாம்பு',
                ]),
                'common_name'     => 'Indian Cobra',
                'scientific_name' => 'Naja naja',
                'venomous_status' => 'highly_venomous',
                'is_venomous'     => true,
                'region'          => 'Island-wide',
                'description'     => 'One of the Big Four dangerous snakes in Sri Lanka. Recognizable by its distinctive hood which it spreads when threatened.',
                'behavior'        => 'Mostly nocturnal but can be active at dawn and dusk. Not aggressive unless provoked, but will strike when cornered.',
                'habitat'         => 'Agricultural lands, rice paddies, scrub forests, and areas near human habitation.',
                'distribution'    => json_encode(['Sri Lanka', 'India', 'Pakistan', 'Bangladesh', 'Nepal']),
                'first_aid_steps' => 'Call 1990. Keep patient calm and immobile. Do NOT apply tourniquet. Transport to hospital.',
                'warning_signs'   => 'Spreads hood wide, hisses loudly, raises head off the ground.',
                'similar_species' => json_encode(['King Cobra', 'Spectacled Cobra']),
                'image_url'       => null,
            ],
            [
                'names' => json_encode([
                    'English'  => "Russell's Viper",
                    'සිංහල'   => 'තිතු පොළඟා',
                    'தமிழ்'   => 'கண்ணாடி விரியன்',
                ]),
                'common_name'     => "Russell's Viper",
                'scientific_name' => 'Daboia russelii',
                'venomous_status' => 'highly_venomous',
                'is_venomous'     => true,
                'region'          => 'Dry Zone, North, East',
                'description'     => "One of the most dangerous snakes in Sri Lanka. Responsible for many snake bite deaths. Has a distinctive chain of oval brown blotches.",
                'behavior'        => 'Sluggish but very aggressive when disturbed. Coils tightly and strikes with great speed.',
                'habitat'         => 'Open scrub jungle, grassy hillsides, agricultural lands.',
                'distribution'    => json_encode(['Sri Lanka', 'India', 'Myanmar', 'Thailand']),
                'first_aid_steps' => 'Call 1990. Immobilise the affected limb. Transport urgently for antivenom.',
                'warning_signs'   => 'Loud hissing, coils into tight S-shape, strikes repeatedly.',
                'similar_species' => json_encode(['Indian Python', 'Sand Boa']),
                'image_url'       => null,
            ],
            [
                'names' => json_encode([
                    'English'  => 'Sri Lankan Green Pit Viper',
                    'සිංහල'   => 'හරිත වෙලමිනිස්සා',
                    'தமிழ்'   => 'பச்சை வர்ணப் பாம்பு',
                ]),
                'common_name'     => 'Sri Lankan Green Pit Viper',
                'scientific_name' => 'Trimeresurus trigonocephalus',
                'venomous_status' => 'venomous',
                'is_venomous'     => true,
                'region'          => 'Wet Zone, Hill Country',
                'description'     => 'A venomous pit viper endemic to Sri Lanka. Bright green coloration provides camouflage in foliage.',
                'behavior'        => 'Arboreal and nocturnal. Ambush predator, slow moving during the day.',
                'habitat'         => 'Tropical rainforests, tea estates, home gardens with dense vegetation.',
                'distribution'    => json_encode(['Sri Lanka (endemic)']),
                'first_aid_steps' => 'Call 1990. Keep patient calm. Immobilise bitten limb. Antivenom available.',
                'warning_signs'   => 'Flattens body and vibrates tail when threatened.',
                'similar_species' => json_encode(["Pope's Pit Viper", 'Bamboo Pit Viper']),
                'image_url'       => null,
            ],
            [
                'names' => json_encode([
                    'English'  => 'Common Krait',
                    'සිංහල'   => 'තේ කරවලා',
                    'தமிழ்'   => 'கட்டு வீரியன்',
                ]),
                'common_name'     => 'Common Krait',
                'scientific_name' => 'Bungarus caeruleus',
                'venomous_status' => 'highly_venomous',
                'is_venomous'     => true,
                'region'          => 'Dry Zone, Low Country',
                'description'     => 'A highly venomous nocturnal snake. Bites often occur while people sleep. Neurotoxic venom causes respiratory paralysis.',
                'behavior'        => 'Strictly nocturnal. Docile during the day, but aggressive at night. Often enters homes.',
                'habitat'         => 'Low scrub, cultivated areas, near human settlements, inside homes.',
                'distribution'    => json_encode(['Sri Lanka', 'India', 'Pakistan', 'Bangladesh']),
                'first_aid_steps' => 'Call 1990. Urgent transport — may need antivenom and respiratory support.',
                'warning_signs'   => 'Curls into tight ball, hides head under coils.',
                'similar_species' => json_encode(['Banded Krait', 'Wolf Snake']),
                'image_url'       => null,
            ],
            [
                'names' => json_encode([
                    'English'  => 'Sri Lanka Flying Snake',
                    'සිංහල'   => 'ශ්‍රී ලංකා ඉගිල් සර්පයා',
                    'தமிழ்'   => 'பறக்கும் பாம்பு',
                ]),
                'common_name'     => 'Sri Lanka Flying Snake',
                'scientific_name' => 'Chrysopelea taprobanica',
                'venomous_status' => 'mildly_venomous',
                'is_venomous'     => false,
                'region'          => 'Wet Zone, Low Country',
                'description'     => 'An endemic colubrid famous for gliding between trees by flattening its body. Not dangerous to humans.',
                'behavior'        => 'Diurnal and arboreal. Glides between trees. Feeds on lizards and small frogs.',
                'habitat'         => 'Tropical lowland forests, home gardens, coastal coconut plantations.',
                'distribution'    => json_encode(['Sri Lanka (endemic)', 'Southern India']),
                'first_aid_steps' => 'Not dangerous. Clean wound with antiseptic if bitten. Seek medical advice if concerned.',
                'warning_signs'   => 'Flattens body and ribs when threatened.',
                'similar_species' => json_encode(['Golden Tree Snake', 'Vine Snake']),
                'image_url'       => null,
            ],
        ];

        foreach ($snakes as $snake) {
            Snake::updateOrCreate(
                ['scientific_name' => $snake['scientific_name']],
                array_merge($snake, ['updated_at' => now()])
            );
        }
    }
}