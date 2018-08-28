<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlatScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores_flat', function (Blueprint $table) {
            $table->increments('flatscoreid');
            $table->integer('entryid');
            $table->integer('entrycompetitionid');

            $table->integer('userid');

            $table->integer('roundid');

            $table->integer('eventid');
            $table->integer('eventcompetitionid');

            $table->integer('divisionid');
            $table->char('unit')->default('m');

            $table->string('dist1')->default(0);
            $table->integer('dist1score')->default(0);

            $table->string('dist2')->nullable();
            $table->integer('dist2score')->nullable();

            $table->string('dist3')->nullable();
            $table->integer('dist3score')->nullable();

            $table->string('dist4')->nullable();
            $table->integer('dist4score')->nullable();

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
        Schema::dropIfExists('flat_scores');
    }
}
