<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('snakes', function (Blueprint $table) {
            // Multilingual names as JSON: {"English": "...", "සිංහල": "...", "தமிழ்": "..."}
            $table->json('names')->nullable()->after('snake_id');
            $table->boolean('is_venomous')->default(false)->after('venomous_status');
            $table->string('region')->nullable()->after('is_venomous');
            $table->string('image_url')->nullable()->after('region');
            $table->text('behavior')->nullable()->after('description');
            $table->text('habitat')->nullable()->after('behavior');
            $table->json('distribution')->nullable()->after('habitat');   // ["Sri Lanka", "India"]
            $table->text('warning_signs')->nullable()->after('first_aid_steps');
            $table->json('similar_species')->nullable()->after('warning_signs');
        });
    }

    public function down(): void
    {
        Schema::table('snakes', function (Blueprint $table) {
            $table->dropColumn([
                'names', 'is_venomous', 'region', 'image_url',
                'behavior', 'habitat', 'distribution',
                'warning_signs', 'similar_species',
            ]);
        });
    }
};
