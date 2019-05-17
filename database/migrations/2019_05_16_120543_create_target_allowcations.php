<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetAllowcations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targetallocations', function (Blueprint $table) {
            $table->increments('targetallocationid');
            $table->integer('userid');
            $table->integer('eventid');
            $table->integer('eventcompetitionid');
            $table->integer('divisionid');
            $table->integer('roundid');
            $table->string('target')->nullable();
            $table->text('info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targetallocations');
    }
}
