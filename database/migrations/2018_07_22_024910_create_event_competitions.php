<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventCompetitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventcompetitions', function (Blueprint $table) {
            $table->increments('eventcompetitionid');
            $table->date('date');
            $table->integer('competitionid');
            $table->integer('scoringenabled');
            $table->integer('currentweek')->nullable();
            $table->integer('scoringlevel')->default(0); // admins only
            $table->string('location')->nullable();
            $table->text('schedule')->nullable();
            $table->integer('visible')->default(1);

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
