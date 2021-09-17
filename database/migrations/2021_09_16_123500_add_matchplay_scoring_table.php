<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatchplayScoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventcompetitions', function (Blueprint $table) {
            $table->integer('matchplay')->default(0);
        });

        Schema::create('matchplay_event', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eventid')->index();
            $table->integer('eventcompetitionid');
            $table->integer('roundid')->index();
            $table->integer('divisionid')->index();
            $table->string('gender')->nullable();
            $table->integer('count'); // count of archer's participating

            $table->timestamps();

        });

        Schema::create('matchplay_competition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('matchplay_event_id');
            $table->integer('entryid')->index();
            $table->integer('entrycompetitionid');
            $table->integer('matchplay_against_id')->nullable()->index(); // BYE would be NULL
            $table->integer('round'); // 1 = final, 2 = 1/2, 3 = 1/4, 4 = 1/8, 5 = 1/16, 6 = 1/32, 7 = 1/64
            $table->integer('scores_flat_id');
            $table->tinyInteger('winner')->default(0);
            $table->integer('bracket_order');
            $table->integer('links_from_id')->nullable();
            $table->integer('links_to_id')->nullable();

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
