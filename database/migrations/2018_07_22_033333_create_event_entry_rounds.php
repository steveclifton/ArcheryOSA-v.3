<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventEntryRounds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrycompetitions', function (Blueprint $table) {
            $table->increments('entrycompetitionid');
            $table->integer('entryid');
            $table->integer('eventcompetitionid');
            $table->integer('userid');
            $table->integer('divisionid');
            $table->integer('competitionid');
            $table->string('target')->nullable();
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
        //
    }
}
