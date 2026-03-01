<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('snake_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('snake_id');
            $table->string('image_url', 500);
            $table->tinyInteger('sort_order')->default(0)->comment('0=main hero, 1-2=gallery');
            $table->string('label', 100)->nullable();
            $table->timestamps();

            $table->foreign('snake_id')
                  ->references('snake_id')
                  ->on('snakes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snake_images');
    }
};
