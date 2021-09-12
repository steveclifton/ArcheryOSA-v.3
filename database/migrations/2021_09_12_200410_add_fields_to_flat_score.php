<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFlatScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scores_flat', function (Blueprint $table) {
            $table->integer('dist1hits')->default(0);
            $table->integer('dist2hits')->default(0);
            $table->integer('dist3hits')->default(0);
            $table->integer('dist4hits')->default(0);
            $table->integer('dist1max')->default(0);
            $table->integer('dist2max')->default(0);
            $table->integer('dist3max')->default(0);
            $table->integer('dist4max')->default(0);
            $table->integer('dist1inners')->default(0);
            $table->integer('dist2inners')->default(0);
            $table->integer('dist3inners')->default(0);
            $table->integer('dist4inners')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flat_score', function (Blueprint $table) {
            //
        });
    }
}
