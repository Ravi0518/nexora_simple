<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Optional link to user account
            $table->string('name');
            $table->string('role')->default('Snake Enthusiast'); // e.g. "Certified Herpetologist"
            $table->string('phone')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->enum('status', ['available', 'busy', 'offline'])->default('available');
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('total_rescues')->default(0);
            $table->string('profile_image_url')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experts');
    }
};
