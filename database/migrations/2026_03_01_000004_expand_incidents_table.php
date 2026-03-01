<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->string('type')->nullable()->after('incident_type');         // "sighting" | "bite"
            $table->string('snake_name')->nullable()->after('type');
            $table->string('location_name')->nullable()->after('snake_name');
            $table->decimal('lat', 10, 7)->nullable()->after('location_name');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open')->after('lng');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn(['type', 'snake_name', 'location_name', 'lat', 'lng', 'status', 'priority']);
        });
    }
};
