<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventpayments', function (Blueprint $table) {
            $table->increments('eventpaymentid');
            $table->integer('userid');
            $table->integer('eventid');
            $table->integer('entryid');
            $table->float('amount');
            $table->string('reference');
            $table->string('transaction');
            $table->text('description');
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
