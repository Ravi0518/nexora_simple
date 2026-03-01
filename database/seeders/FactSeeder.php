<?php

namespace Database\Seeders;

use App\Models\Fact;
use Illuminate\Database\Seeder;

class FactSeeder extends Seeder
{
    public function run(): void
    {
        $facts = [
            ['fact' => 'Sri Lanka is home to over 100 snake species, of which only 5 are dangerously venomous to humans.'],
            ['fact' => 'The Inland Taipan of Australia has the most toxic venom of any snake in the world — enough to kill 100 adult humans.'],
            ['fact' => "Sri Lanka's Common Krait has a painless bite that people sometimes mistake for a mosquito sting, making it especially deadly."],
            ['fact' => "The Sri Lankan Green Pit Viper (Pambaya) is endemic to Sri Lanka — found nowhere else on Earth."],
            ['fact' => 'Snakes cannot blink — they have transparent scales called spectacles covering their eyes instead of eyelids.'],
            ['fact' => 'The Sri Lanka Flying Snake can glide up to 10 metres between trees by flattening its entire body like a ribbon.'],
            ['fact' => "Russell's Viper is responsible for the most snake bite deaths in Sri Lanka annually."],
            ['fact' => 'Snakes smell using their forked tongue — they collect airborne particles and bring them to the Jacobson\'s organ in the roof of their mouth.'],
            ['fact' => 'Applying a tourniquet is one of the worst things you can do for a snake bite — it concentrates the venom and causes tissue death.'],
            ['fact' => "The King Cobra is the world's longest venomous snake, capable of reaching 5.5 metres in length."],
        ];

        foreach ($facts as $fact) {
            Fact::create(array_merge($fact, ['image_url' => null]));
        }
    }
}
