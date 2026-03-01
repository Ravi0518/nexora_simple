<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // INCIDENT (Incident_Id, Incident_type, Location) [cite: 794]
        Schema::create('incidents', function (Blueprint $table) {
            $table->id('incident_id');
            $table->unsignedBigInteger('user_id'); 
            $table->string('incident_type'); 
            $table->string('location'); // SRS: Geotagging Requirement [cite: 94]
            $table->string('image_path')->nullable(); 
            $table->text('description')->nullable();
            
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // REQUEST (Request_Id, Description, Location) [cite: 799]
        Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('user_id'); // The Requester [cite: 814]
            $table->unsignedBigInteger('enthusiast_id')->nullable(); // The Responder [cite: 828]
            $table->text('description'); 
            $table->string('location'); 
            $table->string('status')->default('pending'); 
            
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('enthusiast_id')->references('user_id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('requests');
        Schema::dropIfExists('incidents');
    }
};  