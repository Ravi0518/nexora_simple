<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('snakes', function (Blueprint $table) {
            $table->id('snake_id'); // SDS Primary Key [cite: 844]
            $table->string('common_name'); // [cite: 105]
            $table->string('scientific_name'); // [cite: 105]
            $table->string('venomous_status'); // SDS venom type requirement [cite: 749]
            $table->text('description'); // [cite: 105]
            $table->text('first_aid_steps')->nullable(); // SRS requirement [cite: 108]
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('snakes');
    }
};