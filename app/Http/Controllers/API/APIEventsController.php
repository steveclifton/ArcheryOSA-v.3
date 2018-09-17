<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class APIEventsController extends Controller
{

    /**
     * Gets all upcoming events
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingEvents()
    {
        $events = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`end` > NOW()
            AND `e`.`visible` = 1
            ORDER BY IFNULL(e.entryclose, e.start) 
        ");

        return response()->json([
            'success' => true,
            'date' => [
                'events' => $events
            ]
        ]);
    }


    /**
     * Gets all previous events
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreviousEvents()
    {
        $events = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`end` < NOW()
            AND `e`.`visible` = 1
            ORDER BY `e`.`promoted` DESC, IFNULL(e.entryclose, e.start) 
        ");

        return response()->json([
            'success' => true,
            'date' => [
                'events' => $events
            ]
        ]);
    }
}
