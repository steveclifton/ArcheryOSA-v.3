<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRounds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->integer('anz_record_id')->nullable();
            $table->integer('anz_record_dist1_id')->nullable();
            $table->integer('anz_record_dist2_id')->nullable();
            $table->integer('anz_record_dist3_id')->nullable();
            $table->integer('anz_record_dist4_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
