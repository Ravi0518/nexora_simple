<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIncidentsTableForEnthusiasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_enthusiast_id')->nullable()->after('priority');
            $table->foreign('assigned_enthusiast_id')->references('user_id')->on('users')->onDelete('set null');
        });

        // Modify enum safely (MySQL)
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE incidents MODIFY COLUMN status ENUM('open', 'in_progress', 'closed', 'pending', 'assigned', 'resolved', 'false_alarm') DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['assigned_enthusiast_id']);
            $table->dropColumn('assigned_enthusiast_id');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE incidents MODIFY COLUMN status ENUM('open', 'in_progress', 'closed') DEFAULT 'open'");
    }
}
