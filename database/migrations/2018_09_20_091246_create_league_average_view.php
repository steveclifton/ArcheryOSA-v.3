<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueAverageView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW leagueaverages AS
            SELECT u.`userid`, s.`eventid`, s.`roundid`, `s`.`divisionid` AS `divisionid`,
                count(s.`flatscoreid`) as scorecount,
                avg(s.`dist1score`) as avg_distance1_total,
                avg(s.`dist2score`) as avg_distance2_total,
                avg(s.`dist3score`) as avg_distance3_total,
                avg(s.`dist4score`) as avg_distance4_total,
                avg(s.`total`) as avg_total_score,
                avg(s.`inners`) as avg_total_10,
                avg(s.`max`) as avg_total_x
            
            FROM `scores_flat` s
            JOIN `users` u USING (`userid`)
            JOIN `events` e ON (s.eventid = e.eventid)
            WHERE `e`.`eventtypeid` = 2
            AND `s`.`total` <> 0
            GROUP BY u.`userid`, s.`eventid`, s.`roundid`, `s`.`divisionid`
        ");
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
