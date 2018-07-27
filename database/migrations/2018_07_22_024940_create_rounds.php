<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRounds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('roundid');
            $table->string('label');
            $table->integer('organisationid');
            $table->string('code')->nullable();
            $table->string('unit')->default('m');
            $table->string('dist1');
            $table->string('dist1max');
            $table->string('dist2')->nullable();
            $table->string('dist2max')->nullable();
            $table->string('dist3')->nullable();
            $table->string('dist3max')->nullable();
            $table->string('dist4')->nullable();
            $table->string('dist4max')->nullable();
            $table->integer('visible')->default(1);

            $table->string('totalmax');
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
