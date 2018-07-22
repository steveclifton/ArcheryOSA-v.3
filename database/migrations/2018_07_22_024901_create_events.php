<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('eventid');
            $table->string('label');
            $table->string('hash');

            $table->date('entryclose');
            $table->date('start');
            $table->date('end');
            $table->integer('daycount')->default(1);

            $table->string('contactname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email');

            $table->text('location')->nullable();
            $table->string('cost')->nullable();
            $table->string('bankaccount')->nullable();
            $table->string('bankreference')->nullable();
            $table->text('schedule')->nullable();
            $table->text('info')->nullable();
            $table->string('eventurl')->nullable();
            $table->string('imagedt')->nullable();
            $table->string('imagemob')->nullable();
            $table->integer('sponsored')->default(0);
            $table->string('sponsorimagedt')->nullable();
            $table->string('sponsorimagemob')->nullable();
            $table->string('sponsorurl')->nullable();

            $table->integer('visible')->default(0);


            $table->integer('eventstatusid')->default(0);
            $table->integer('createdby');
            $table->integer('clubid')->nullable();
            $table->integer('organisationid')->nullable();


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
