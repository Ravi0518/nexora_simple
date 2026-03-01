<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatchReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catch_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->unsignedBigInteger('enthusiast_id');
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->decimal('caught_lat', 10, 7)->nullable();
            $table->decimal('caught_lng', 10, 7)->nullable();
            
            $table->string('snake_image_path')->nullable();
            $table->string('species_identified')->nullable();
            $table->string('snake_condition')->nullable(); // e.g., alive, injured, dead
            $table->text('enthusiast_comments')->nullable();
            
            $table->timestamps();

            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');
            $table->foreign('enthusiast_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catch_reports');
    }
}
