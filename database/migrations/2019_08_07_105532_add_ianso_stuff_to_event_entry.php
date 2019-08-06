<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIansoStuffToEventEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evententrys', function($table) {
            $table->integer('individualqualround')->default(1);
            $table->integer('teamqualround')->default(1);
            $table->integer('individualfinal')->default(1);
            $table->integer('teamfinal')->default(1);
            $table->integer('mixedteamfinal')->default(1);
            $table->char('subclass', 4)->default('NZ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evententrys', function (Blueprint $table) {
            //
        });
    }
}
