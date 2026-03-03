<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SyncCnnClassesToSnakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Truncate existing snakes and images to start fresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('snake_images')->truncate();
        DB::table('snakes')->truncate();
        
        // 2. Enable inserting ID 0 (temporarily for this session)
        DB::statement('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,"NO_AUTO_VALUE_ON_ZERO",""));');
        DB::statement('SET SESSION sql_mode = CONCAT(@@sql_mode, ",NO_AUTO_VALUE_ON_ZERO");');

        // 3. Define the 12 specific snakes mapped 0-11
        $snakes = [
            [
                'snake_id' => 0,
                'name' => 'Ahaetulla',
                'common_name' => 'Ahaetulla',
                'scientific_name' => 'Ahaetulla',
                'slug' => 'ahaetulla',
                'description' => 'A genus of colubrid snakes commonly known as vine snakes.',
                'venomous_status' => 'Mildly Venomous',
                'is_venomous' => true,
                'region' => 'Dry & Wet Zones',
            ],
            [
                'snake_id' => 1,
                'name' => 'Common Krait',
                'common_name' => 'Common Krait',
                'scientific_name' => 'Bungarus caeruleus',
                'slug' => 'common_krait',
                'description' => 'A highly venomous snake found across the Indian subcontinent.',
                'venomous_status' => 'Highly Venomous',
                'is_venomous' => true,
                'region' => 'Dry Zone',
            ],
            [
                'snake_id' => 2,
                'name' => 'Common Rat Snake',
                'common_name' => 'Common Rat Snake',
                'scientific_name' => 'Ptyas mucosa',
                'slug' => 'common_rat_snake',
                'description' => 'A large, non-venomous colubrid snake common in rural and urban areas.',
                'venomous_status' => 'Non-Venomous',
                'is_venomous' => false,
                'region' => 'All Regions',
            ],
            [
                'snake_id' => 3,
                'name' => 'Indian Cobra',
                'common_name' => 'Indian Cobra',
                'scientific_name' => 'Naja naja',
                'slug' => 'indian_cobra',
                'description' => 'A highly venomous snake known for its iconic hood.',
                'venomous_status' => 'Highly Venomous',
                'is_venomous' => true,
                'region' => 'All Regions',
            ],
            [
                'snake_id' => 4,
                'name' => 'Indian Python',
                'common_name' => 'Indian Python',
                'scientific_name' => 'Python molurus',
                'slug' => 'indian_python',
                'description' => 'A large, non-venomous constrictor snake.',
                'venomous_status' => 'Non-Venomous',
                'is_venomous' => false,
                'region' => 'Dry Zone',
            ],
            [
                'snake_id' => 5,
                'name' => 'Mudu Karawala',
                'common_name' => 'Mudu Karawala',
                'scientific_name' => 'Hydrophis',
                'slug' => 'mudu_karawala',
                'description' => 'A venomous sea snake found in coastal waters.',
                'venomous_status' => 'Highly Venomous',
                'is_venomous' => true,
                'region' => 'Coastal Zones',
            ],
            [
                'snake_id' => 6,
                'name' => 'Paduru Haaldanda',
                'common_name' => 'Buff-striped Keelback',
                'scientific_name' => 'Amphiesma stolatum',
                'slug' => 'paduru_haaldanda',
                'description' => 'A small to medium-sized non-venomous snake.',
                'venomous_status' => 'Non-Venomous',
                'is_venomous' => false,
                'region' => 'Wet Zone',
            ],
            [
                'snake_id' => 7,
                'name' => "Russell's Viper",
                'common_name' => "Russell's Viper",
                'scientific_name' => 'Daboia russelii',
                'slug' => 'russells_viper',
                'description' => 'A highly venomous snake responsible for many snakebite incidents.',
                'venomous_status' => 'Highly Venomous',
                'is_venomous' => true,
                'region' => 'Dry Zone',
            ],
            [
                'snake_id' => 8,
                'name' => 'Saw-scaled Viper',
                'common_name' => 'Saw-scaled Viper',
                'scientific_name' => 'Echis carinatus',
                'slug' => 'saw_scaled_viper',
                'description' => 'A small highly venomous snake recognizable by its threat display of rubbing its scales together.',
                'venomous_status' => 'Highly Venomous',
                'is_venomous' => true,
                'region' => 'Arid Zones',
            ],
            [
                'snake_id' => 9,
                'name' => 'Sri Lankan Green Vine Snake',
                'common_name' => 'Sri Lankan Green Vine Snake',
                'scientific_name' => 'Ahaetulla nasuta',
                'slug' => 'sri_lankan_green_vine_snake',
                'description' => 'A mildly venomous snake with an elongated snout, endemic to Sri Lanka.',
                'venomous_status' => 'Mildly Venomous',
                'is_venomous' => true,
                'region' => 'Dry & Wet Zones',
            ],
            [
                'snake_id' => 10,
                'name' => 'Green Pit Viper',
                'common_name' => 'Green Pit Viper',
                'scientific_name' => 'Trimeresurus trigonocephalus',
                'slug' => 'green_pit_viper',
                'description' => 'A venomous pit viper endemic to Sri Lanka.',
                'venomous_status' => 'Venomous',
                'is_venomous' => true,
                'region' => 'Wet Zone',
            ],
            [
                'snake_id' => 11,
                'name' => 'Green Vine Snake',
                'common_name' => 'Green Vine Snake',
                'scientific_name' => 'Ahaetulla nasuta',
                'slug' => 'green_vine_snake',
                'description' => 'A common mildly venomous snake found in foliage.',
                'venomous_status' => 'Mildly Venomous',
                'is_venomous' => true,
                'region' => 'Dry & Wet Zones',
            ],
        ];

        // 4. Insert snakes
        $now = now();
        foreach ($snakes as &$snake) {
            $snake['created_at'] = $now;
            $snake['updated_at'] = $now;
            DB::table('snakes')->insert($snake);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('snake_images')->truncate();
        DB::table('snakes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
