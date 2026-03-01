<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('snakes', function (Blueprint $table) {
            // Slug for URL/search matching (e.g. "green_vine_snake")
            $table->string('slug')->nullable()->unique()->after('snake_id');

            // Multilingual names
            $table->string('name')->nullable()->after('slug');      // English common name
            $table->string('name_si')->nullable()->after('name');   // Sinhala
            $table->string('name_ta')->nullable()->after('name_si');// Tamil

            // Danger level in 3 languages
            $table->string('danger_level')->nullable()->after('venomous_status');
            $table->string('danger_level_si')->nullable()->after('danger_level');
            $table->string('danger_level_ta')->nullable()->after('danger_level_si');

            // About / Description (EN already exists as 'description' — add SI and TA)
            $table->text('about')->nullable()->after('description');
            $table->text('about_si')->nullable()->after('about');
            $table->text('about_ta')->nullable()->after('about_si');

            // Habitat in 3 languages (EN already exists — add SI and TA)
            $table->text('habitat_si')->nullable()->after('habitat');
            $table->text('habitat_ta')->nullable()->after('habitat_si');

            // Behavior in 3 languages (EN already exists — add SI and TA)
            $table->text('behavior_si')->nullable()->after('behavior');
            $table->text('behavior_ta')->nullable()->after('behavior_si');

            // Diet (new field, 3 languages)
            $table->text('diet')->nullable()->after('behavior_ta');
            $table->text('diet_si')->nullable()->after('diet');
            $table->text('diet_ta')->nullable()->after('diet_si');

            // First aid as JSON arrays in 3 languages (EN was 'first_aid_steps', add SI and TA)
            $table->text('first_aid')->nullable()->after('first_aid_steps');   // JSON array EN
            $table->text('first_aid_si')->nullable()->after('first_aid');
            $table->text('first_aid_ta')->nullable()->after('first_aid_si');

            // Do NOTs as JSON arrays in 3 languages
            $table->text('donts')->nullable()->after('first_aid_ta');
            $table->text('donts_si')->nullable()->after('donts');
            $table->text('donts_ta')->nullable()->after('donts_si');
        });
    }

    public function down(): void
    {
        Schema::table('snakes', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'name', 'name_si', 'name_ta',
                'danger_level', 'danger_level_si', 'danger_level_ta',
                'about', 'about_si', 'about_ta',
                'habitat_si', 'habitat_ta',
                'behavior_si', 'behavior_ta',
                'diet', 'diet_si', 'diet_ta',
                'first_aid', 'first_aid_si', 'first_aid_ta',
                'donts', 'donts_si', 'donts_ta',
            ]);
        });
    }
};
