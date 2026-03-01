<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rescue_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->unsignedBigInteger('expert_id')->nullable(); // expert from experts table
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');

            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rescue_requests');
    }
};
