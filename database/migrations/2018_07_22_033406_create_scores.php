<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('scoreid');
            $table->integer('entryid');
            $table->integer('entrycompetitionid');

            $table->integer('userid');

            $table->integer('roundid');

            $table->integer('eventid');
            $table->integer('eventcompetitionid');

            $table->integer('divisionid');

            $table->string('distance');
            $table->char('unit')->default('m');
            $table->integer('score');
            $table->integer('hits')->default(0);
            $table->integer('max')->default(0);
            $table->integer('inners')->default(0);

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
