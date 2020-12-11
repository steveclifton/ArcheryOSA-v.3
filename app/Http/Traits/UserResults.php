<?php

namespace App\Http\Traits;

use App\LeaguePoint;
use Illuminate\Support\Facades\DB;

trait UserResults
{
    private function getUserTop10Scores($userid, $divisionid, $eventid)
    {

        $result = DB::select("
            SELECT sum(`total`) as total
            FROM (SELECT `total`
                    FROM `scores_flat`
                    WHERE `userid` = :userid
                    AND `divisionid` = :divisionid
                    AND `eventid` = :eventid
                    ORDER BY `total` DESC
                    LIMIT 10
                ) AS total
            ", ['userid' => $userid, 'divisionid' => $divisionid, 'eventid' => $eventid]);

        return !empty($result[0]) ? $result[0] : 0;

    }

    private function getUserAverage($userid, $divisionid, $eventid)
    {
        $result = DB::select("
            SELECT avg(`total`) as average
            FROM `scores_flat`
            WHERE `userid` = :userid
            AND `divisionid` = :divisionid
            AND `eventid` = :eventid
            ORDER BY `total` DESC
            ", ['userid' => $userid, 'divisionid' => $divisionid, 'eventid' => $eventid]);

        return !empty($result[0]) ? $result[0] : 0;
    }

    public static function getUserTop10Points($userid, $divisionid, $eventid)
    {
        $result = DB::select("
            SELECT sum(`points`) as points
            FROM (
              SELECT `points`
                FROM `leaguepoints`
                WHERE `userid` = :userid
                AND `divisionid` = :divisionid
                AND `eventid` = :eventid
                ORDER BY `points` DESC
                LIMIT 10
            ) as points
            ",['userid'=>$userid, 'divisionid'=>$divisionid, 'eventid' => $eventid]);


        return !empty($result[0]) ? $result[0] : 0;
    }

    public static function getUserWeekPoints($userid, $divisionid, $eventid, $week)
    {
        $result = LeaguePoint::where('userid', $userid)
            ->where('divisionid', $divisionid)
            ->where('eventid', $eventid)
            ->where('week', $week)
            ->pluck('points')
            ->first();

        return $result ?? 0;
    }

}