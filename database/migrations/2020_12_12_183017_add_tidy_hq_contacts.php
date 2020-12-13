<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTidyHqContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tidyhqcontacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('nickname')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthday')->nullable();
            $table->string('details')->nullable();
            $table->integer('subscribed')->nullable();
            $table->string('thqcreatedat')->nullable();
            $table->string('thqupdatedat')->nullable();
            $table->string('contactid')->nullable();
            $table->string('contactstatus')->nullable();

            $table->string('contactidnumber')->nullable();
            $table->string('membershipnumber')->nullable();
            $table->string('membershipstatus')->nullable();
            $table->string('membershipid')->nullable();
            $table->text('customfields')->nullable();
            $table->timestamps();

            $table->index(['userid', 'email', 'membershipnumber']);
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
