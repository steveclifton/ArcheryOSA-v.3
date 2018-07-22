<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventEntrys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evententrys', function (Blueprint $table) {
            $table->increments('entryid');
            $table->integer('userid');
            $table->integer('eventid');
            $table->integer('entrystatusid');
            $table->integer('paid')->default(0);
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->char('gender', 2)->nullable();
            $table->string('membership')->nullable();
            $table->integer('enteredby')->default(0);
            $table->string('notes')->nullable();
            $table->string('hash')->nullable();
            $table->string('dateofbirth')->nullable();
            $table->integer('confirmationemail')->default(0);

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
